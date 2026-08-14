<?php include "../layout/header.php"?>

  <main>
    <section class="container my-5">
      <div class="row justify-content-center">
        <div class="col-lg-9">
          <div class="form-header mb-4">
            <div class="form-header-content">
              <div class="form-header-logo">
                <img
                  src="<?= base_url ?>assets/image/childrens_corner_logo.png"
                  alt="Logo" />
              </div>
              <div class="form-header-text">
                <h1>Children's Corner — Online Admission Form</h1>
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
              Note: Submitting this form does not guarantee admission. The
              school will contact you for verification and next steps.
            </span>
          </div>

          <div class="form-card">
            <form>
              <!-- APPLICANT DETAILS -->
              <h2 class="section-title">Applicant Details</h2>
              <div class="row g-3 mb-4">
                <div class="col-md-6">
                  <div class="form-floating mb-3">
                    <input
                      type="text"
                      class="form-control"
                      id="firstName"
                      placeholder="e.g., Jonayed Hassan"
                      required />
                    <label for="firstName">First Name *</label>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-floating mb-3">
                    <input
                      type="text"
                      class="form-control"
                      id="lastName"
                      placeholder="e.g., Laskar"
                      required />
                    <label for="lastName">Last Name *</label>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-floating mb-3">
                    <input
                      type="date"
                      class="form-control"
                      id="dob"
                      required />
                    <label for="dob">Date of Birth *</label>
                  </div>
                </div>
                <div class="col-md-6">
                  <label for="gender" class="form-label">Gender <span class="text-danger">*</span></label><br />
                  <div class="form-check form-check-inline">
                    <input
                      class="form-check-input"
                      type="radio"
                      name="gender"
                      id="genderMale"
                      value="Male"
                      required />
                    <label class="form-check-label" for="genderMale">Male</label>
                  </div>
                  <div class="form-check form-check-inline">
                    <input
                      class="form-check-input"
                      type="radio"
                      name="gender"
                      id="genderFemale"
                      value="Female" />
                    <label class="form-check-label" for="genderFemale">Female</label>
                  </div>
                  <div class="form-check form-check-inline">
                    <input
                      class="form-check-input"
                      type="radio"
                      name="gender"
                      id="genderOther"
                      value="Other" />
                    <label class="form-check-label" for="genderOther">Other</label>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-floating mb-3">
                    <select class="form-select" id="bloodGroup">
                      <option selected disabled value="">
                        Select Blood Group
                      </option>
                      <option value="A+">A+</option>
                      <option value="A-">A-</option>
                      <option value="B+">B+</option>
                      <option value="B-">B-</option>
                      <option value="AB+">AB+</option>
                      <option value="AB-">AB-</option>
                      <option value="O+">O+</option>
                      <option value="O-">O-</option>
                    </select>
                    <!-- <label for="bloodGroup">Blood Group</label> -->
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-floating mb-3">
                    <select class="form-select" id="category">
                      <option selected disabled value="">
                        Select Category
                      </option>
                      <option value="General">General</option>
                      <option value="SC">SC</option>
                      <option value="ST">ST</option>
                      <option value="OBC-A">OBC-A</option>
                      <option value="OBC-B">OBC-B</option>
                    </select>
                    <!-- <label for="category">Category</label> -->
                  </div>
                </div>
              </div>

              <!-- ADMISSION DETAILS -->
              <h2 class="section-title">Admission Details</h2>
              <div class="row g-3 mb-4">
                <div class="col-md-6">
                  <div class="form-floating mb-3">
                    <select
                      class="form-select"
                      id="classApplyingFor"
                      required>
                      <option selected disabled value="">Select Class</option>
                      <option value="Nursery">Nursery</option>
                      <option value="LKG">L.K.G</option>
                      <option value="UKG">U.K.G</option>
                      <option value="1">Class I</option>
                      <option value="2">Class II</option>
                      <option value="3">Class III</option>
                      <option value="4">Class IV</option>
                      <option value="5">Class V</option>
                      <option value="6">Class VI</option>
                      <option value="7">Class VII</option>
                      <option value="8">Class VIII</option>
                      <option value="9">Class IX</option>
                      <option value="10">Class X</option>
                      <option value="11">Class XI</option>
                      <option value="12">Class XII</option>
                    </select>
                    <!-- <label for="classApplyingFor">Class Applying For</label> -->
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-floating mb-3">
                    <select class="form-select" id="academicYear" required>
                      <option selected disabled value="">
                        Select Academic Year
                      </option>
                      <option value="2024-2025">2024-2025</option>
                      <option value="2025-2026">2025-2026</option>
                      <option value="2026-2027">2026-2027</option>
                    </select>
                    <!-- <label for="academicYear">Academic Year</label> -->
                  </div>
                </div>
                <div class="col-12">
                  <div class="form-floating mb-3">
                    <input
                      type="text"
                      class="form-control"
                      id="previousSchool"
                      placeholder="School name" />
                    <label for="previousSchool">Previous School (if any)</label>
                  </div>
                </div>
              </div>

              <!-- CONTACT & ADDRESS -->
              <h2 class="section-title">Contact & Address</h2>
              <div class="row g-3 mb-4">
                <div class="col-md-6">
                  <div class="form-floating mb-3">
                    <input
                      type="text"
                      class="form-control"
                      id="parentGuardianName"
                      placeholder="e.g., Alauddin sardar"
                      required />
                    <label for="parentGuardianName">Parent/Guardian Name *</label>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-floating mb-3">
                    <select class="form-select" id="relation" required>
                      <option selected disabled value="">
                        Select Relation
                      </option>
                      <option value="Father">Father</option>
                      <option value="Mother">Mother</option>
                      <option value="Legal Guardian">Legal Guardian</option>
                    </select>
                    <!-- <label for="relation">Relation</label> -->
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-floating mb-3">
                    <input
                      type="tel"
                      class="form-control"
                      id="mobileNumber"
                      placeholder="10-digit mobile"
                      required />
                    <label for="mobileNumber">Mobile Number *</label>
                  </div>
                  <small class="text-danger-custom">Enter 10 digits without country code.</small>
                </div>
                <div class="col-md-6">
                  <div class="form-floating mb-3">
                    <input
                      type="email"
                      class="form-control"
                      id="email"
                      placeholder="name@example.com" />
                    <label for="email">Email</label>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-floating mb-3">
                    <input
                      type="tel"
                      class="form-control"
                      id="alternatePhone"
                      placeholder="10-digit mobile" />
                    <label for="alternatePhone">Alternate Phone</label>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-floating mb-3">
                    <input
                      type="text"
                      class="form-control"
                      id="pinCode"
                      placeholder="6-digit"
                      required />
                    <label for="pinCode">PIN Code *</label>
                  </div>
                </div>
                <div class="col-12">
                  <div class="form-floating mb-3">
                    <textarea
                      class="form-control"
                      id="address"
                      rows="3"
                      placeholder="House/Street, Area/Village"
                      required></textarea>
                    <label for="address">Address *</label>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-floating mb-3">
                    <input
                      type="text"
                      placeholder="cityTown"
                      class="form-control"
                      id="cityTown"
                      required />
                    <label for="cityTown">City/Town *</label>
                  </div>
                </div>
              </div>

              <!-- DECLARATION -->
              <div class="form-check mb-4">
                <input
                  class="form-check-input"
                  type="checkbox"
                  id="declaration"
                  required />
                <label class="form-check-label" for="declaration">
                  I hereby declare that the information provided is true and
                  correct. <span class="text-danger">*</span>
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

<?php include "../layout/footer.php"?>