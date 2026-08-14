<?php include "../layout/header.php" ?>

<main class="container my-5">
  <h1 class="text-center mb-4">Hostel Notice Board</h1>
  <div class="accordion" id="hostelNoticeAccordion">
    <div class="accordion-item">
      <h2 class="accordion-header" id="headingOne">
        <button
          class="accordion-button"
          type="button"
          data-bs-toggle="collapse"
          data-bs-target="#collapseOne"
          aria-expanded="true"
          aria-controls="collapseOne">
          Hostel Admission Open for Academic Year 2025-2026
        </button>
      </h2>
      <div
        id="collapseOne"
        class="accordion-collapse collapse show"
        aria-labelledby="headingOne"
        data-bs-parent="#hostelNoticeAccordion">
        <div class="accordion-body">
          Applications for hostel admission for the upcoming academic year
          2025-2026 are now open. Interested students are requested to
          submit their forms by <strong>January 31, 2025</strong>. Limited
          seats available.
        </div>
      </div>
    </div>
    <div class="accordion-item">
      <h2 class="accordion-header" id="headingTwo">
        <button
          class="accordion-button collapsed"
          type="button"
          data-bs-toggle="collapse"
          data-bs-target="#collapseTwo"
          aria-expanded="false"
          aria-controls="collapseTwo">
          New Study Hour Schedule
        </button>
      </h2>
      <div
        id="collapseTwo"
        class="accordion-collapse collapse"
        aria-labelledby="headingTwo"
        data-bs-parent="#hostelNoticeAccordion">
        <div class="accordion-body">
          A revised study hour schedule will be implemented from
          <strong>February 15, 2025</strong>. All hostel residents must
          adhere to the new timings. Details have been posted on the common
          room notice board.
        </div>
      </div>
    </div>
    <div class="accordion-item">
      <h2 class="accordion-header" id="headingThree">
        <button
          class="accordion-button collapsed"
          type="button"
          data-bs-toggle="collapse"
          data-bs-target="#collapseThree"
          aria-expanded="false"
          aria-controls="collapseThree">
          Hostel Mess Menu for Next Week
        </button>
      </h2>
      <div
        id="collapseThree"
        class="accordion-collapse collapse"
        aria-labelledby="headingThree"
        data-bs-parent="#hostelNoticeAccordion">
        <div class="accordion-body">
          The updated mess menu for the week of
          <strong>February 3 - February 9, 2025</strong> is now available.
          Feedback on previous menus can be submitted to the mess committee
          representative.
        </div>
      </div>
    </div>
  </div>
</main>

<!-- Footer Section -->
<?php include "../layout/footer.php" ?>