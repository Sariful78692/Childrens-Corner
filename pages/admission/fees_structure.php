<?php
require_once __DIR__ . '/../includes/fees_structure_data.php';

$sessions = get_fee_structure_sessions();
$current_session_id = get_fee_structure_current_session_id();
$selected_session_id = isset($_GET['session_id']) ? (int) $_GET['session_id'] : $current_session_id;
if ($selected_session_id <= 0) {
  $selected_session_id = $current_session_id;
}

$selected_class_id = isset($_GET['class_id']) ? (int) $_GET['class_id'] : 0;
$classes = get_fee_structure_classes($selected_session_id);
$fee_rows = get_fee_structure_rows($selected_session_id, $selected_class_id);

$grouped_fee_rows = array();
foreach ($fee_rows as $row) {
  $class_key = (int) $row['class_id'];
  if (!isset($grouped_fee_rows[$class_key])) {
    $grouped_fee_rows[$class_key] = array(
      'class_name' => $row['class'],
      'session_name' => $row['session'],
      'rows' => array()
    );
  }
  $grouped_fee_rows[$class_key]['rows'][] = $row;
}
?>

<style>
  .fees-hero {
    background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 45%, #2563eb 100%);
    color: #fff;
    border-radius: 18px;
    padding: 28px;
    box-shadow: 0 18px 40px rgba(15, 23, 42, 0.18);
  }

  .fees-table thead th {
    background: #0f172a;
    color: #fff;
    vertical-align: middle;
    white-space: nowrap;
  }

  .fees-table tbody td {
    vertical-align: middle;
    white-space: nowrap;
  }

  .fees-badge {
    display: inline-block;
    padding: 0.35rem 0.7rem;
    border-radius: 999px;
    background: #dbeafe;
    color: #1d4ed8;
    font-weight: 600;
    font-size: 0.85rem;
  }

  .class-card {
    border: 1px solid #dbeafe;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 10px 24px rgba(15, 23, 42, 0.06);
    background: #fff;
  }

  .class-card-header {
    background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
    color: #1e3a8a;
    font-weight: 700;
    padding: 16px 20px;
    border-bottom: 1px solid #bfdbfe;
  }

  .class-card-header small {
    font-weight: 500;
    color: #475569;
  }
</style>

<?php include "../layout/header.php" ?>

<main>
  <section class="container py-5">
    <div class="fees-hero mb-4">
      <div class="row align-items-center g-3">
        <div class="col-lg-8">
          <h2 class="mb-2">Fees Structure</h2>
          <p class="mb-0">View class-wise fees for the selected session.</p>
        </div>
        <div class="col-lg-4">
          <form method="get" action="">
            <div class="row g-2">
              <div class="col-md-6">
                <label for="session_id" class="form-label fw-semibold " style="color: #fff;">Session</label>
                <select name="session_id" id="session_id" class="form-select">
                  <?php foreach ($sessions as $session): ?>
                    <option value="<?php echo (int) $session['id']; ?>" <?php echo ($selected_session_id == (int) $session['id']) ? 'selected' : ''; ?>>
                      <?php echo htmlspecialchars($session['session']); ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="col-md-6">
                <label for="class_id" class="form-label fw-semibold" style="color: #fff;">Class</label>
                <select name="class_id" id="class_id" class="form-select">
                  <option value="0"<?php echo $selected_class_id === 0 ? ' selected' : ''; ?>>All Classes</option>
                  <?php foreach ($classes as $class): ?>
                    <option value="<?php echo (int) $class['id']; ?>" <?php echo ($selected_class_id == (int) $class['id']) ? 'selected' : ''; ?>>
                      <?php echo htmlspecialchars($class['class']); ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>

    <?php if (!empty($grouped_fee_rows)): ?>
      <div class="row g-4">
        <?php foreach ($grouped_fee_rows as $group): ?>
          <div class="col-12">
            <div class="class-card">
              <div class="class-card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
                <div>
                  <div class="h5 mb-1"><?php echo htmlspecialchars($group['class_name']); ?></div>
                  <small>Session: <?php echo htmlspecialchars($group['session_name']); ?></small>
                </div>
                <div class="text-muted">
                  <?php echo count($group['rows']); ?> fee item<?php echo count($group['rows']) === 1 ? '' : 's'; ?>
                </div>
              </div>
              <div class="table-responsive">
                <table class="table table-striped table-bordered mb-0 fees-table">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Fee Type</th>
                      <th class="text-end">Fees Amount</th>
                      <th class="text-end">Tuition Fees</th>
                      <th class="text-end">Meal Charges</th>
                      <th>Fee Mode</th>
                      <th>Order</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($group['rows'] as $index => $row): ?>
                      <tr>
                        <td><?php echo $index + 1; ?></td>
                        <td><?php echo htmlspecialchars($row['type']); ?></td>
                        <td class="text-end"><?php echo number_format((float) $row['fees_amount'], 2); ?></td>
                        <td class="text-end"><?php echo number_format((float) $row['tuition_fees'], 2); ?></td>
                        <td class="text-end"><?php echo number_format((float) $row['meal_charges'], 2); ?></td>
                        <td>
                          <?php if ((int) $row['is_monthly'] === 1): ?>
                            <span class="fees-badge">Monthly</span>
                          <?php else: ?>
                            <span class="fees-badge" style="background:#fee2e2;color:#b91c1c;">One Time</span>
                          <?php endif; ?>
                        </td>
                        <td><?php echo (int) $row['fees_order']; ?></td>
                      </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <div class="alert alert-info mb-0">
        No fee structure found for the selected session and class.
      </div>
    <?php endif; ?>

    <div class="text-center mt-5 pt-4 border-top">
      <a href="/index.php" class="btn btn-outline-secondary btn-lg"><i class="fas fa-home me-2"></i> Back to Home</a>
    </div>
  </section>
</main>

<?php include "../layout/footer.php" ?>

<script>
  document.getElementById('session_id').addEventListener('change', function() {
    this.form.submit();
  });
  document.getElementById('class_id').addEventListener('change', function() {
    this.form.submit();
  });
</script>
