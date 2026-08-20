# Concession Student Report — How It Works

URL: `admin/report/concession_student_report`

This report lists students who are getting a fee **concession** — i.e. students who have a `recommendationNumber` set (on `student_session` or `students`) and whose assigned fee is lower than the standard class fee for at least one fee item.

---

## 1. Files involved

| Layer | File |
|---|---|
| Route target / controller | [Report.php](admin/application/controllers/Report.php) |
| List/summary query | [Studentfee_model.php](admin/application/models/Studentfee_model.php) → `getConcessionStudentReport()` |
| Detail query (one student) | `Studentfee_model.php` → `getConcessionStudentReportDetails()` |
| Filter page | [concessionStudentReport.php](admin/application/views/reports/concessionStudentReport.php) |
| DataTable row partial | [_concessionStudentReportResults.php](admin/application/views/reports/_concessionStudentReportResults.php) |
| Per-student detail page | [concessionStudentReportDetail.php](admin/application/views/reports/concessionStudentReportDetail.php) |
| Print view | `concessionStudentReportPrint.php` |

---

## 2. Controller endpoints (`Report.php`)

| Method | Purpose |
|---|---|
| `concession_student_report()` | Initial page load — renders filters + first pass of data (line 514) |
| `concession_student_report_search()` | AJAX endpoint for the filter form's plain search (line 555) |
| `dtconcessionstudentreportlist()` | **DataTables** AJAX source that actually feeds the on-screen table (line 587) |
| `concession_student_report_export()` | Streams a CSV of the filtered rows (line 638) |
| `concession_student_report_print()` | Printer-friendly view (line 702) |
| `concession_student_report_detail($student_session_id)` | Drill-down page for one student (line 743) |

All of them are gated by `rbac->hasPrivilege('student_report', 'can_view')`.

Every one of these methods follows the same pattern:

```php
$filters     = $this->getConcessionStudentReportFilters();          // read session/class/section/free_type/search_text from GET/POST
$report_data = $this->buildConcessionStudentReportData(...$filters); // run the query + aggregate
```

### `getConcessionStudentReportFilters()` (line 883)
Reads `session_id`, `class_id`, `section_id`, `free_type`, `search_text` from the request. If `session_id` isn't posted at all, it defaults to the school's **current session** (`setting_model->getCurrentSession()`).

### `buildConcessionStudentReportData()` (line 843)
1. Calls the model (`getConcessionStudentReport`) to get raw rows.
2. Applies free-text search filtering in PHP (`filterConcessionReportRowsBySearch`, line 946) — matches student name, roll no, section, recommendation number, reg no, class, phone, session name, discount breakdown, or free-status against the typed text (case-insensitive substring match).
3. Groups the rows by **session** into `session_groups` (used by the print view).
4. Rolls up summary numbers:
   - `summary_total_students` = row count
   - `summary_total_sessions` = distinct sessions
   - `summary_total_discount` = sum of `total_discount_amount`
   - `summary_monthly_discount` = sum of `monthly_discount_amount`
   - `summary_admission_discount` = sum of `admission_discount_amount`

---

## 3. The core query — `getConcessionStudentReport()` (model, line 536)

This is where the actual "who counts as a concession student" logic lives. It's one query built from `student_fees_management` (`sfm`) joined to the class-wise standard fee table, plus two correlated subqueries.

### 3.1 Two helper subqueries

**`admission_fee_subquery`** — per `student_session_id`, figures out whether the student's *discounted* admission fee (`discounted_fees > 0`) came from a `NEW ADMISSION` fee type or a `RE- ADMISSION` fee type. Used later to make sure a student isn't double-counted under both admission types.

**`fee_mix_subquery`** — per `student_session_id`, aggregates **all** of that student's fee items:
- `total_admission_count` / `total_monthly_count` — how many admission-type vs monthly-type fee rows exist
- `skipped_monthly_count` / `active_monthly_count` — monthly fee rows where `is_skipped = 1` vs `= 0`
- `skipped_new_admission_count` / `skipped_re_admission_count` — admission fee rows that are skipped (`is_skipped = 1`), split by fee type text (`NEW ADMISSION` / `RE- ADMISSION`) — kept only for the admission-type de-duplication in §5
- `total_admission_payable` / `total_monthly_payable` — **sum of `discounted_fees` for non-skipped rows**, i.e. the amount the student would actually still have to pay in each category. This is what drives the "fully waived" determination (see §4) instead of relying on a single item's skip flag.

"Skipped" (`is_skipped = 1`) means the fee item was waived entirely for that student.

### 3.2 Main select

Joins: `student_fees_management` → `fees_master_class_wise` (standard fee for that class/session/feetype) → `student_session` → `students` → `classes` → `sections` → `sessions` → `feetype`, plus the two subqueries above.

Key computed columns:
- `total_discount_amount` = Σ(`fees_amount - discounted_fees`) for non-skipped rows
- `monthly_discount_amount` / `admission_discount_amount` = same sum split by `is_monthly`
- `active_discount_breakdown` = a `GROUP_CONCAT` string like `Tuition Fee: 500<br>Admission Fee: 200`, built only from non-skipped rows, ordered monthly-last... actually monthly first (`ORDER BY is_monthly ASC`)

### 3.3 WHERE filters (who is eligible at all)

```
sfm.status = 1
sfm.is_monthly IN (0, 1)
fmcw.is_monthly IN (0, 1)
(fmcw.status IS NULL OR fmcw.status = 1)
(sfm.is_skipped = 1 OR sfm.discounted_fees < fmcw.fees_amount)      -- either fully waived, or charged less than standard
(af.admission_fee_type IS NULL
    OR (af.admission_fee_type = 'NEW ADMISSION' AND ft.type <> 'RE- ADMISSION')
    OR (af.admission_fee_type = 'RE- ADMISSION' AND ft.type <> 'NEW ADMISSION'))
NULLIF(ss.recommendationNumber, '') IS NOT NULL   -- must have a recommendation number on student_session
```
Plus optional `session_id` / `class_id` / `section_id` equality filters from the form.

So a fee row is included only if it's a discounted (or fully skipped) fee item, belongs to a student **session** that has a recommendation number, and doesn't belong to the "other" admission type (this prevents a student who got a NEW ADMISSION discount from also showing a stray RE-ADMISSION row from a different session, etc.).

> **Note:** The recommendation number is always read from `student_session.recommendationNumber` (the per-session value) — it does **not** fall back to `students.recommendationNumber`. If a student's current session record has no recommendation number, that session's fee rows are excluded even if the student's profile-level `recommendationNumber` is set. (This applies only to the concession report; other reports/dashboard widgets may still use the `students`-level fallback.)

Rows are grouped per student/session and ordered by session desc, then class, section, roll no.

### 3.4 Post-processing in PHP

For every returned row:
1. `free_status` is computed by `getConcessionFreeStatusFromRow()` (see §4 below) — the overall bucket (Fully Free / Admission Free / Monthly Free / Concession).
2. `concession_reason` is computed by `getConcessionReasonFromRow()` (see §4.1 below) — the fee-level "why included" explanation shown in its own report column.
3. `discount_breakdown` (the human-readable text) is built:
   - `fully_free` → just the string `"Fully Free"`
   - `admission_free` → `"Admission Free"` + (if any active/non-skipped breakdown exists) that breakdown appended
   - `monthly_free` → `"Monthly Free"` + any active breakdown appended
   - otherwise → just the raw `active_discount_breakdown` (plain partial concession)
4. Finally `filterConcessionAdmissionFeeRows()` is applied (see §5) to drop rows for the "wrong" admission type and to apply the `free_type` filter from the form.

---

## 4. Free-status classification — `getConcessionFreeStatusFromRow()`

This decides the overall **Status** bucket a student falls into. Rather than checking whether *any single* fee item was skipped, it checks whether the student's **total payable amount** for that fee category is zero — i.e. it evaluates the whole fee liability, not one item:

```php
$admission_fully_waived = $total_admission_count > 0 && $total_admission_payable <= 0;
$monthly_fully_waived   = $total_monthly_count > 0   && $total_monthly_payable   <= 0;
```

| Status | Condition |
|---|---|
| **Fully Free** | `admission_fully_waived` **and** `monthly_fully_waived` — no payable amount left in either category |
| **Monthly Free** | `monthly_fully_waived` only — every monthly fee item is skipped/zero, but admission fees still have a payable balance |
| **Admission Free** | `admission_fully_waived` only — every admission fee item is skipped/zero, but monthly fees still have a payable balance |
| *(blank = plain "Concession")* | Neither category is fully waived — just a partial discount somewhere |

This means a single skipped admission item no longer earns "Admission Free" if the student still has other admission fees to pay — that combination now falls through to the generic "Concession" bucket (see §4.1 for how the reason column still explains it precisely).

The label shown in the UI comes from `getConcessionFreeStatusLabel()`: `fully_free` → "Fully Free", `admission_free` → "Admission Free", `monthly_free` → "Monthly Free", anything else → "Concession".

### 4.1 "Why Included" / Concession Reason — `getConcessionReasonFromRow()` / `getConcessionReason()`

While `free_status` gives the overall bucket, the **Why Included** column pinpoints the exact fee category responsible, using the same payable totals plus the (non-skipped-only) `admission_discount_amount` / `monthly_discount_amount` sums from §3.2:

```php
$has_admission_concession = $total_admission_count > 0
    && ($admission_fully_waived || $admission_discount_amount > 0);
$has_monthly_concession = $total_monthly_count > 0
    && ($monthly_fully_waived || $monthly_discount_amount > 0);
```

| Reason shown | When |
|---|---|
| **Admission + Monthly Concession** | Both categories have some concession (full or partial) |
| **Admission Fee Fully Waived** | Only admission has a concession, and it's fully waived |
| **Admission Fee Discount** | Only admission has a concession, and it's a partial discount (not fully waived) |
| **Monthly Fee Fully Waived** | Only monthly has a concession, and it's fully waived |
| **Monthly Fee Discount** | Only monthly has a concession, and it's a partial discount |
| **Concession** | Fallback, shouldn't normally occur given the report's eligibility filter |

This is deliberately independent of `free_status`: e.g. a student with one admission item fully skipped but another admission item still payable gets `free_status = ''` (plain "Concession" badge) but `concession_reason = 'Admission Fee Discount'` (since the category isn't *fully* waived, just has *a* discount in it) — giving the admin the precise fee-level explanation without collapsing it into a misleading "Admission Free" status.

Both the model helper (public, reused by the detail page) and this reason column exist purely for transparency/audit purposes — they don't change which rows are included in the report, only how each row is labeled.

**Shared flags.** `getConcessionCategoryFlagsFromRow()` / `getConcessionCategoryFlags()` compute `admission_fully_waived`, `monthly_fully_waived`, `has_admission_concession`, `has_monthly_concession` once; `getConcessionFreeStatusFromRow()` and `getConcessionReasonFromRow()` both build on top of these same flags, so the overall Status and the Why Included reason can never disagree about the underlying facts. `getConcessionStudentReport()` also stores `has_admission_concession` / `has_monthly_concession` directly on each row — these back the dashboard's "Monthly/Admission Concession Students" counters (§9).

---

## 5. Admission-type de-duplication — `filterConcessionAdmissionFeeRows()` (line 724)

1. `detectConcessionAdmissionFeeType()` scans the rows and returns `'NEW ADMISSION'` or `'RE- ADMISSION'` — whichever admission fee type has `student_fee > 0` (i.e. the student actually has an active, non-zero fee under that type). Returns `null` if neither is found.
2. Then for each row:
   - Recomputes `free_status` per row (via the same §4 logic)
   - If a `free_type` filter (`fully_free` / `admission_free` / `monthly_free`) was passed in, skips rows that don't match.
   - If an admission type was detected, drops rows belonging to the *other* admission type (e.g. if the student is `NEW ADMISSION`, any stray `RE- ADMISSION` row is excluded).

This same helper is reused by `getConcessionStudentReportDetails()` for the single-student drill-down.

---

## 6. Detail drill-down — `concession_student_report_detail()`

Calls `getConcessionStudentReportDetails($student_session_id)`, which is a narrower version of the same query scoped to one `student_session_id`, returning one row per fee item (not aggregated) with `standard_fee`, `student_fee`, `discount_amount`, `is_monthly`, `is_skipped`, plus the same `total_admission_payable` / `total_monthly_payable` aggregates from §3.1.

The controller then applies **different display/calculation rules for admission vs. monthly items**, and — critically — a skipped fee item never contributes a monetary value anywhere:

- **Skipped monthly items** (`is_monthly=1 AND is_skipped=1`) are dropped entirely from the displayed list (`continue`d out of the loop) and don't feed any total. They're only reflected indirectly through the overall `free_status` / `concession_reason` (computed from the aggregate `total_monthly_payable`, not from these rows). `has_skipped_monthly` is set to `true` so the view can show a footnote when this happens.
- **Active monthly items** and **all admission items** (skipped or active) are kept in `$fee_details` for display, in original DB order.
- For a **skipped admission item**: `display_student_fee` and `display_discount_amount` are both set to `null` (rendered as "—" in the view) — it contributes **nothing** to `total_standard_fee`, `total_student_fee`, `total_discount_amount`, or `admission_discount_amount`. Only its `is_admission_skipped = true` flag and `standard_fee` (informational only) are kept.
- For an **active admission item** or **any monthly item shown**: `display_student_fee`/`display_discount_amount` are the real `student_fee`/`discount_amount` values, and they *do* count toward the totals and toward `admission_discount_amount` / `monthly_discount_amount`.
- Those two discount sums, together with `$student['total_admission_payable']` / `total_monthly_payable` / counts, feed into `getConcessionReason()` to get `concession_reason`.
- `getConcessionFreeStatusFromRow($student)` still gives `free_status`, from which `fully_free` / `admission_free` / `monthly_free` booleans and `free_status_label` are derived (§4 logic, not duplicated in the controller).

The view ([concessionStudentReportDetail.php](admin/application/views/reports/concessionStudentReportDetail.php)) shows:
- A **"Why Included / Concession Reason"** banner at the top of the page.
- Student info + a Concession Summary card with `Status` and `Why Included` lines.
- A fee-by-fee comparison table (Fee Name, Category, **Admission Status**, Standard Fee, Student Fee, Discount Amount) where:
  - Admission rows show a **Skipped** or **Active** badge.
  - Monthly rows show **no status badge** (blank cell) — only active monthly items are ever listed.
  - A skipped admission row shows "—" for Student Fee and Discount Amount instead of a $0.00/full-amount figure, since skipped fees are never priced.
  - A footnote appears below the table when `has_skipped_monthly` is true, explaining that fully-skipped monthly items aren't itemized but are reflected in the Status/Why Included values above.
  - The grand-total row sums only the non-skipped rows actually contributing a value, so Standard = Student + Discount reconciles.

---

## 7. Front-end behavior (`concessionStudentReport.php` view)

- Filter form (Session, Class, Section, Fee Status, free-text Search) — submits normally on button click, but is also wired for **live AJAX reload**:
  - Changing Session or Fee Status → reload immediately.
  - Changing Class → loads that class's sections via `sections/getByClass`, then reloads.
  - Changing Section → reload immediately.
  - Typing in Search → debounced 300ms, then reload.
- The actual table is a **DataTable** (`searching: false`, `serverSide: false`, `pageLength: 100`) whose `ajax` source is `report/dtconcessionstudentreportlist` (POST, sending the current filter values each time). The response also updates the 5 summary cards (Concession Students, Sessions Covered, Total Discount, Admission Fee Concession, Monthly Fee Concession) directly from the JSON payload.
- Columns: Session, Reg No., Name, Roll, Section, Recommendation Number, Class, Phone, **Fee Status**, **Why Included**, Discount Breakdown, Total Discount Amount, Details. "Fee Status" is the overall bucket (§4); "Why Included" is the fee-level reason (§4.1) — they're deliberately separate columns since they can disagree (see the mixed-admission-items example in §4.1).
- Export buttons (Copy/Excel/CSV/Print) are DataTables buttons operating on the currently loaded rows (client-side), separate from the server-side CSV export endpoint (`concession_student_report_export`, which also includes a "Why Included" column) and the print view (`concession_student_report_print`) which re-query the DB with the same filters.
- Each row has a "View" button linking to `report/concession_student_report_detail/{student_session_id}`.

---

## 8. Summary of the eligibility rule (plain English)

A student/session/fee-item shows up in this report when **all** of these are true:
1. The fee item is active (`status = 1`) and the fee is either admission-type or monthly-type.
2. The student's **session record** (`student_session.recommendationNumber`) has a recommendation number.
3. The fee item is either **fully waived** (`is_skipped = 1`) or **charged less than the standard class fee**.
4. The fee item's type doesn't conflict with the student's detected admission type (prevents mixing NEW/RE-ADMISSION rows).
5. It matches whatever Session / Class / Section / Fee-Status / Search filters are currently applied.

The student is then labeled **Fully Free**, **Admission Free**, **Monthly Free**, or plain **Concession** based on whether their total *payable* admission/monthly amount is zero (§4) — not on whether a single item was skipped — and a separate **Why Included** reason (§4.1) pinpoints the exact fee category (admission, monthly, or both) responsible, so every row is traceable back to the specific fee(s) that qualified it.

---

## 9. Dashboard widget — "Concession Students Overview"

The dashboard ([dashboard.php](admin/application/views/admin/dashboard.php), gated by the same `student_report`/`can_view` privilege) shows a summary of the **current session's** concession data, built by `admin\Admin::index()` calling `getConcessionStudentReport($current_session_id)` and aggregating in PHP (not via the report's own controller helpers, since the dashboard needs class/section-wise grouping instead of session-wise grouping).

### 9.1 Overview stat cards

Nine cards, using terminology consistent with the full report (§4/§4.1) — the "Students" suffix marks a **count**, the "Amount" suffix marks a **currency value**:

| Card | Source |
|---|---|
| Total Concession Students | `count($concession_students)` |
| Monthly Concession Students | count of rows where `has_monthly_concession` is true (§4.1 flag) |
| Admission Concession Students | count of rows where `has_admission_concession` is true |
| Fully Free Students | `free_status === 'fully_free'` count |
| Monthly Free Students | `free_status === 'monthly_free'` count |
| Admission Free Students | `free_status === 'admission_free'` count |
| Total Concession Amount | Σ `total_discount_amount` |
| Monthly Concession Amount | Σ `monthly_discount_amount` |
| Admission Concession Amount | Σ `admission_discount_amount` |

Note "Monthly/Admission Concession Students" (any concession, full or partial, in that category) and "Monthly/Admission Free Students" (*fully* waived in that category) are different, overlapping metrics — a student can count toward "Admission Concession Students" without being "Admission Free" if they still owe part of an admission fee.

### 9.2 Class/section-wise table

Same student data, grouped by `class_id`-`section_id` instead of session, with the same 9-metric terminology applied per row: Class, Section, Boys, Girls, **Fully Free Students**, **Monthly Free Students**, **Admission Free Students**, **Monthly Concession Students**, **Admission Concession Students**, **Total Concession Students**, **Total Concession Amount** — plus a footer row summing every column across all classes/sections. The footer's "Total" label is a single (non-colspan) cell, kept that way specifically so the Excel export below doesn't need to reconcile merged-cell XML references.

### 9.3 Excel export — overview summary + class-wise table in one file

The "Export Excel" button (in the widget header, and the identical `.buttons-excel` button DataTables generates for the table itself) exports **one workbook containing both**:
1. A **summary block** at the top — the same 9 metrics/values as the overview cards (§9.1), as "Label" / "Value" pairs in columns A/B.
2. A **blank separator row**, then the full class/section-wise table (header, data rows, footer totals) from §9.2, exactly as it appears on screen.

This is implemented in `dashboard.php`'s inline script via the `excelHtml5` button's `customize(xlsx)` callback, because DataTables Buttons only natively exports the DataTable's own rows — there's no built-in option to prepend arbitrary extra rows. The callback:
- Reads `xlsx.xl.worksheets['sheet1.xml']` (the raw SpreadsheetML XML DataTables Buttons generated for the table).
- Shifts every existing `<row r="…">` (and each cell's `r="A1"`-style reference) down by the number of summary rows, using jQuery's XML-aware selectors (`$('row', sheet)`, `$('c', $row)`).
- Prepends the summary rows' XML (`<row>`/`<c t="inlineStr">` elements) via `$('sheetData', sheet).prepend(...)`.
- Updates the sheet's `<dimension ref="…">` to cover the new row count.

This intentionally avoids Excel merged cells (`<mergeCells>`) entirely — the table has none (see the footer note in §9.2) — because shifting merge-cell references correctly would add significant complexity for no visual benefit here. If the table ever gains a colspan/rowspan cell in the future, this `customize` callback would need to also shift `<mergeCell ref="…">` entries, or the row-insertion approach would need revisiting.
