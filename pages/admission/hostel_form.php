<?php include '../layout/header.php'; ?>

<main>
  <!-- Hero Section for Hostel Form -->
  <section
    class="hero-image-section"
    style="background-image: url('../../assets/image/IMG_7345.JPG')">
    <div class="hero-content">
      <h1 class="hero-title">Hostel Application Form</h1>
      <p class="hero-subtitle">Apply for a spot in our secure hostel</p>
    </div>
  </section>

  <!-- Main Content for Hostel Form -->
  <section class="container my-5">
    <div class="row justify-content-center">
      <div class="col-lg-9">
        <div class="form-header mb-4">
          <div class="form-header-content">
            <div class="form-header-logo">
              <img
                src="../../assets/image/childrens_corner_logo.png"
                alt="Logo" />
            </div>
            <div class="form-header-text">
              <h1>Children's Corner — Hostel Application</h1>
              <p>
                Please fill out all required fields (<span>*</span>)
                carefully.
              </p>
            </div>
          </div>
        </div>
        <div class="alert-info-banner">
          <i class="fas fa-exclamation-circle"></i>
          <span>
            Note: Submitting this form does not guarantee a hostel
            allotment. The school will contact you for verification and next
            steps.
          </span>
        </div>

        <div class="form-card">
          <form>
            <!-- STUDENT DETAILS -->
            <h2 class="section-title">Student Details</h2>
            <div class="row g-3 mb-4">
              <div class="col-md-6">
                <label for="studentFirstName" class="form-label">First Name <span class="text-danger">*</span></label>
                <input
                  type="text"
                  class="form-control"
                  id="studentFirstName"
                  placeholder="e.g., Rahul"
                  required />
              </div>
              <div class="col-md-6">
                <label for="studentLastName" class="form-label">Last Name <span class="text-danger">*</span></label>
                <input
                  type="text"
                  class="form-control"
                  id="studentLastName"
                  placeholder="e.g., Sharma"
                  required />
              </div>
              <div class="col-md-6">
                <label for="studentRollNo" class="form-label">School Roll Number
                  <span class="text-danger">*</span></label>
                <input
                  type="text"
                  class="form-control"
                  id="studentRollNo"
                  placeholder="e.g., 12345"
                  required />
              </div>
              <div class="col-md-6">
                <label for="studentClass" class="form-label">Class <span class="text-danger">*</span></label>
                <select class="form-select" id="studentClass" required>
                  <option selected disabled value="">Select Class</option>
                  <option value="5">V</option>
                  <option value="6">VI</option>
                  <option value="7">VII</option>
                  <option value="8">VIII</option>
                  <option value="9">IX</option>
                  <option value="10">X</option>
                  <option value="11">XI</option>
                  <option value="12">XII</option>
                </select>
              </div>
              <div class="col-md-6">
                <label for="studentDob" class="form-label">Date of Birth <span class="text-danger">*</span></label>
                <input
                  type="date"
                  class="form-control"
                  id="studentDob"
                  required />
              </div>
              <div class="col-md-6">
                <label for="studentGender" class="form-label">Gender <span class="text-danger">*</span></label><br />
                <div class="form-check form-check-inline">
                  <input
                    class="form-check-input"
                    type="radio"
                    name="studentGender"
                    id="studentGenderMale"
                    value="Male"
                    required />
                  <label class="form-check-label" for="studentGenderMale">Male</label>
                </div>
                <div class="form-check form-check-inline">
                  <input
                    class="form-check-input"
                    type="radio"
                    name="studentGender"
                    id="studentGenderFemale"
                    value="Female" />
                  <label class="form-check-label" for="studentGenderFemale">Female</label>
                </div>
              </div>
            </div>

            <!-- PARENT/GUARDIAN DETAILS -->
            <h2 class="section-title">Parent/Guardian Details</h2>
            <div class="row g-3 mb-4">
              <div class="col-md-6">
                <label for="parentName" class="form-label">Parent/Guardian Name
                  <span class="text-danger">*</span></label>
                <input
                  type="text"
                  class="form-control"
                  id="parentName"
                  placeholder="e.g., S. K. Sharma"
                  required />
              </div>
              <div class="col-md-6">
                <label for="parentMobile" class="form-label">Mobile Number <span class="text-danger">*</span></label>
                <input
                  type="tel"
                  class="form-control"
                  id="parentMobile"
                  placeholder="10-digit mobile"
                  required />
                <small class="text-danger-custom">Enter 10 digits without country code.</small>
              </div>
              <div class="col-md-6">
                <label for="parentEmail" class="form-label">Email</label>
                <input
                  type="email"
                  class="form-control"
                  id="parentEmail"
                  placeholder="name@example.com" />
              </div>
              <div class="col-md-6">
                <label for="parentOccupation" class="form-label">Occupation</label>
                <input
                  type="text"
                  class="form-control"
                  id="parentOccupation"
                  placeholder="e.g., Engineer" />
              </div>
              <div class="col-12">
                <label for="parentAddress" class="form-label">Permanent Address
                  <span class="text-danger">*</span></label>
                <textarea
                  class="form-control"
                  id="parentAddress"
                  rows="3"
                  placeholder="House/Street, Area/Village"
                  required></textarea>
              </div>
              <div class="col-md-6">
                <label for="parentCity" class="form-label">City/Town <span class="text-danger">*</span></label>
                <input
                  type="text"
                  class="form-control"
                  id="parentCity"
                  required />
              </div>
              <div class="col-md-6">
                <label for="parentPinCode" class="form-label">PIN Code <span class="text-danger">*</span></label>
                <input
                  type="text"
                  class="form-control"
                  id="parentPinCode"
                  placeholder="6-digit"
                  required />
              </div>
            </div>

            <!-- EMERGENCY CONTACT -->
            <h2 class="section-title">Emergency Contact</h2>
            <div class="row g-3 mb-4">
              <div class="col-md-6">
                <label for="emergencyName" class="form-label">Name <span class="text-danger">*</span></label>
                <input
                  type="text"
                  class="form-control"
                  id="emergencyName"
                  required />
              </div>
              <div class="col-md-6">
                <label for="emergencyRelation" class="form-label">Relation <span class="text-danger">*</span></label>
                <input
                  type="text"
                  class="form-control"
                  id="emergencyRelation"
                  required />
              </div>
              <div class="col-md-6">
                <label for="emergencyMobile" class="form-label">Mobile Number <span class="text-danger">*</span></label>
                <input
                  type="tel"
                  class="form-control"
                  id="emergencyMobile"
                  placeholder="10-digit mobile"
                  required />
              </div>
            </div>

            <!-- DECLARATION -->
            <div class="form-check mb-4">
              <input
                class="form-check-input"
                type="checkbox"
                id="hostelDeclaration"
                required />
              <label class="form-check-label" for="hostelDeclaration">
                I have read and understood the hostel rules and regulations
                and agree to abide by them.
                <span class="text-danger">*</span>
              </label>
            </div>

            <!-- SUBMIT BUTTONS -->
            <div class="d-flex justify-content-end gap-3">
              <button type="reset" class="btn btn-secondary">Reset</button>
              <button type="submit" class="btn btn-primary">
                Submit Application
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </section>
</main>

<?php include '../layout/footer.php'; ?>