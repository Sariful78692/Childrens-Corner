<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Student extends Admin_Controller
{

    public $sch_setting_detail = array();

    public function __construct()
    {
        parent::__construct();
        $this->load->library('media_storage');
        $this->config->load('app-config');
        $this->config->load("payroll");
        $this->load->library('smsgateway');
        $this->load->library('mailsmsconf');
        $this->load->library('encoding_lib');
        $this->load->model("classteacher_model");
        $this->load->model(array("timeline_model", "student_edit_field_model", 'transportfee_model', 'marksdivision_model', 'module_model'));
        $this->blood_group        = $this->config->item('bloodgroup');
        $this->sch_setting_detail = $this->setting_model->getSetting();
        $this->role;
        $this->staff_attendance = $this->config->item('staffattendance');
    }

    private function canHardDeleteStudents()
    {
        if ($this->rbac->hasPrivilege('superadmin', 'can_view')) {
            return true;
        }

        $admin = $this->session->userdata('admin');
        return !empty($admin['id']) && $this->staff_model->is_temp_superadmin($admin['id']);
    }

    public function index()
    {
        $data['title']       = 'Student List';
        $student_result      = $this->student_model->get();
        $data['studentlist'] = $student_result;
        $this->load->view('layout/header', $data);
        $this->load->view('student/studentList', $data);
        $this->load->view('layout/footer', $data);
    }

    public function multiclass()
    {
        if (!$this->rbac->hasPrivilege('multi_class_student', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Student Information');
        $this->session->set_userdata('sub_menu', 'student/multiclass');
        $data['title']       = 'student fees';
        $data['title']       = 'student fees';
        $class               = $this->class_model->get();
        $data['classlist']   = $class;
        $data['sch_setting'] = $this->sch_setting_detail;

        $this->form_validation->set_rules('section_id', $this->lang->line('section'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required|xss_clean');

        if ($this->form_validation->run() == false) {
        } else {
            $class                   = $this->class_model->get();
            $data['classlist']       = $class;
            $data['student_due_fee'] = array();
            $class_id                = $this->input->post('class_id');
            $section_id              = $this->input->post('section_id');
            $data['classes']         = $this->classsection_model->allClassSections();
            $students                = $this->studentsession_model->searchMultiStudentByClassSection($class_id, $section_id);
            $data['students']        = $students;
        }
        $this->load->view('layout/header', $data);
        $this->load->view('student/multiclass', $data);
        $this->load->view('layout/footer', $data);
    }

    public function download($student_id, $doc_id)
    {
        $this->load->helper('download');
        $doc_details = $this->student_model->studentdocbyid($doc_id);
        $this->media_storage->filedownload($doc_details['doc'], "uploads/student_documents/" . $student_id);
    }

    public function view($id)
    {
        if (!$this->rbac->hasPrivilege('student', 'can_view')) {
            access_denied();
        }

        $userdata        = $this->customlib->getUserData();
        $data['role_id'] = $userdata["role_id"];

        $data['marks_division'] = $this->marksdivision_model->get();

        $data['title'] = $this->lang->line('student_details');
        $student       = ctype_digit((string) $id) ? $this->student_model->get((int) $id) : array();

        // A hard-deleted student (or a malformed/non-existent ID) must not
        // continue into the profile's dependent fee, attendance, and exam queries.
        if (empty($student) || empty($student['student_session_id'])) {
            $this->session->set_flashdata('student_notice', 'Student profile was deleted permanently.');
            redirect('student/search');
            return;
        }

        $studentSession = $this->student_model->getStudentSession((int) $id);
        if (empty($studentSession)) {
            $this->session->set_flashdata('student_notice', 'Student profile was deleted permanently.');
            redirect('student/search');
            return;
        }

        $data['gradeList'] = $this->grade_model->get();

        $data["timeline_list"] = $this->timeline_model->getStudentTimeline($id, $status = '');

        $data['sch_setting'] = $this->sch_setting_detail;

        $data['adm_auto_insert']      = $this->sch_setting_detail->adm_auto_insert;
        $data['student_timeline']     = $this->sch_setting_detail->student_timeline;
        $data["session"]              = $studentSession["session"];
        $student_due_fee              = $this->studentfeemaster_model->getStudentFeesHistory($id);
        /* echo "<pre>";
        print_r($student_due_fee);
        die; */
        $student_discount_fee         = $this->feediscount_model->getStudentFeesDiscount($student['student_session_id']);
        $data['student_discount_fee'] = $student_discount_fee;
        $data['student_due_fee']      = $student_due_fee;
        $data['siblings']             = $this->student_model->getMySiblings($student['parent_id'], $student['id']);

        $data['student_doc'] = $this->student_model->getstudentdoc($id);

        $transport_fees = [];

        $data['superadmin_visible'] = $this->customlib->superadmin_visible();

        $getStaffRole       = $this->customlib->getStaffRole();
        $data['staffrole']         = json_decode($getStaffRole);

        if ($this->module_lib->hasModule('behaviour_records')) {
            //------- Behaviour Report Start--------

            $this->load->model("studentincidents_model");

            // This is used to get assign incident record of student by student id
            $data['assignstudent'] = $this->studentincidents_model->studentbehaviour($id);

            // This is used to get total points of student by student id
            $total_points            = $this->studentincidents_model->totalpoints($id);
            $student['total_points'] = $total_points['totalpoints'];

            //------- Behaviour Report End----------
        }

        // ------------- CBSE Exam Start ---------------------
        if ($this->module_lib->hasModule('cbseexam')) {

            $this->load->model("cbseexam/cbseexam_exam_model");
            $this->load->model("cbseexam/cbseexam_grade_model");
            $this->load->model("cbseexam/cbseexam_assessment_model");


            $exam_list = $this->cbseexam_exam_model->getStudentExamByStudentSession($student['student_session_id']);

            $student_exams = [];
            if (!empty($exam_list)) {
                foreach ($exam_list as $exam_key => $exam_value) {


                    $exam_subjects = $this->cbseexam_exam_model->getexamsubjects($exam_value->cbse_exam_id);
                    $exam_value->{"subjects"} = $exam_subjects;
                    $exam_value->{"grades"} = $this->cbseexam_grade_model->getGraderangebyGradeID($exam_value->cbse_exam_grade_id);
                    $exam_value->{"exam_assessments"} = $this->cbseexam_assessment_model->getWithAssessmentTypeByAssessmentID($exam_value->cbse_exam_assessment_id);

                    $exam_value->{"exam_subject_assessments"} = $this->cbseexam_assessment_model->getSubjectAssessmentsByExam($exam_subjects);

                    $cbse_exam_result = $this->cbseexam_exam_model->getStudentResultByExamId($exam_value->cbse_exam_id, [$exam_value->student_session_id]);

                    $students = [];
                    $student_rank = "";

                    if (!empty($cbse_exam_result)) {

                        foreach ($cbse_exam_result as $student_key => $student_value) {
                            $student_rank = $student_value->rank;

                            if (!empty($students)) {

                                if (!array_key_exists($student_value->subject_id, $students['subjects'])) {

                                    $new_subject = [
                                        'subject_id' => $student_value->subject_id,
                                        'subject_name' => $student_value->subject_name,
                                        'subject_code' => $student_value->subject_code,
                                        'exam_assessments' => [
                                            $student_value->cbse_exam_assessment_type_id => [
                                                'cbse_exam_assessment_type_name' => $student_value->cbse_exam_assessment_type_name,
                                                'cbse_exam_assessment_type_id' => $student_value->cbse_exam_assessment_type_id,
                                                'cbse_exam_assessment_type_code' => $student_value->cbse_exam_assessment_type_code,
                                                'maximum_marks' => $student_value->maximum_marks,
                                                'cbse_student_subject_marks_id' => $student_value->cbse_student_subject_marks_id,
                                                'marks' => $student_value->marks,
                                                'note' => $student_value->note,
                                                'is_absent' => $student_value->is_absent,
                                            ],
                                        ],
                                    ];

                                    $students['subjects'][$student_value->subject_id] = $new_subject;
                                } elseif (!array_key_exists($student_value->cbse_exam_assessment_type_id, $students['subjects'][$student_value->subject_id]['exam_assessments'])) {

                                    $new_assesment = [
                                        'cbse_exam_assessment_type_name' => $student_value->cbse_exam_assessment_type_name,
                                        'cbse_exam_assessment_type_id' => $student_value->cbse_exam_assessment_type_id,
                                        'cbse_exam_assessment_type_code' => $student_value->cbse_exam_assessment_type_code,
                                        'maximum_marks' => $student_value->maximum_marks,
                                        'cbse_student_subject_marks_id' => $student_value->cbse_student_subject_marks_id,
                                        'marks' => $student_value->marks,
                                        'note' => $student_value->note,
                                        'is_absent' => $student_value->is_absent,
                                    ];

                                    $students['subjects'][$student_value->subject_id]['exam_assessments'][$student_value->cbse_exam_assessment_type_id] = $new_assesment;
                                }
                            } else {

                                $students['subjects'] = [
                                    $student_value->subject_id => [
                                        'subject_id' => $student_value->subject_id,
                                        'subject_name' => $student_value->subject_name,
                                        'subject_code' => $student_value->subject_code,
                                        'exam_assessments' => [
                                            $student_value->cbse_exam_assessment_type_id => [
                                                'cbse_exam_assessment_type_name' => $student_value->cbse_exam_assessment_type_name,
                                                'cbse_exam_assessment_type_id' => $student_value->cbse_exam_assessment_type_id,
                                                'cbse_exam_assessment_type_code' => $student_value->cbse_exam_assessment_type_code,
                                                'maximum_marks' => $student_value->maximum_marks,
                                                'cbse_student_subject_marks_id' => $student_value->cbse_student_subject_marks_id,
                                                'marks' => $student_value->marks,
                                                'note' => $student_value->note,
                                                'is_absent' => $student_value->is_absent,

                                            ],

                                        ],
                                    ],

                                ];
                            }
                        }
                    }
                    $exam_value->{"rank"} = $student_rank;
                    $exam_value->{"exam_data"} = $students;
                }
            }

            $data['exams'] = $exam_list;
        }
        // ------------- CBSE Exam End---------------------

        $module = $this->module_model->getPermissionByModulename('transport');
        if ($module['is_active']) {

            $transport_fees = $this->studentfeemaster_model->getStudentTransportFees($student['student_session_id'], $student['route_pickup_point_id']);
        }

        $data['transport_fees'] = $transport_fees;

        $data['student_doc_id'] = $id;
        $data['category_list']  = $this->category_model->get();

        $data['student'] = $student;

        $data["class_section"] = $this->student_model->getClassSection($student["class_id"]);
        $session               = $this->setting_model->getCurrentSession();

        $data["studentlistbysection"] = $this->student_model->getStudentClassSection($student["class_id"], $session);

        $data['guardian_credential'] = $this->student_model->guardian_credential($student['parent_id']);

        $data['reason'] = $this->disable_reason_model->get();

        if ($student['is_active'] === 'no') {
            $data['reason_data'] = $this->disable_reason_model->get($student['dis_reason']);
        }

        $data['exam_result'] = $this->examgroupstudent_model->searchStudentExams($student['student_session_id'], true, true);
        $data['exam_grade']  = $this->grade_model->getGradeDetails();

        $data['yearlist'] = $this->stuattendence_model->attendanceYearCount();

        $startmonth        = $this->setting_model->getStartMonth();
        $monthlist         = $this->customlib->getMonthDropdown($startmonth);
        $data["monthlist"] = $monthlist;

        $data['attendencetypeslist'] = $this->attendencetype_model->get();

        $year = date("Y");

        $input = $this->setting_model->getCurrentSessionName();
        $session_parts = explode('-', $input);

        $a = $session_parts[0] ?? '';
        $b = $session_parts[1] ?? '';

        $start_year = $a;

        if (strlen($b) == 2) {
            $Next_year = substr($a, 0, 2) . $b;
        } else {
            $Next_year = $b;
        }

        $start_end_month = $this->startmonthandend();

        $session_year_start = date("Y-m-01", strtotime($start_year . '-' . $start_end_month[0] . '-01'));
        $session_year_end   = date("Y-m-t", strtotime($Next_year . '-' . $start_end_month[1] . '-01'));

        $data["countAttendance"] = $this->countAttendance($session_year_start, $student['student_session_id']);

        foreach ($monthlist as $key => $value) {

            $datemonth       = date("m", strtotime($key));
            // $date_each_month = date('Y-' . $datemonth . '-01');

            $date_each_month = date($start_year . '-' . $datemonth . '-01');

            $date_end        = date('t', strtotime($date_each_month));
            for ($n = 1; $n <= $date_end; $n++) {
                $att_date           = sprintf("%02d", $n);
                $attendence_array[] = $att_date;
                $datemonth          = date("m", strtotime($key));
                $att_dates          = $start_year . "-" . $datemonth . "-" . sprintf("%02d", $n);

                $date_array[]    = $att_dates;
                $res[$att_dates] = $this->stuattendence_model->studentattendance($att_dates, $student['student_session_id']);
            }

            $start_year = ($datemonth == 12) ? $Next_year : $start_year;
        }

        $data["session_year_start"] = $session_year_start;
        $data["session_year_end"]   = $session_year_end;
        $data["resultlist"]         = $res;

        /* echo "<pre>";
        print_r($data); die; */

        $this->load->view('layout/header', $data);
        $this->load->view('student/studentShow', $data);
        $this->load->view('layout/footer', $data);
    }

    public function class_update($id)
    {
        if (!$this->rbac->hasPrivilege('student', 'can_edit')) {
            access_denied();
        }

        $data['title'] = 'Class Update';
        $student = $this->student_model->get($id);
        $studentSession = $this->student_model->getStudentSession($id);
        $data['student'] = $student;
        $data['session'] = $studentSession['session'];
        $class = $this->class_model->get();
        $data['classlist'] = $class;
        $this->load->model('account_department_model');
        $this->load->model('session_model');
        $data['account_departments'] = $this->account_department_model->get();
        $data['sessionlist'] = $this->session_model->getAllSession();

        $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('section_id', $this->lang->line('section'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('session_id', $this->lang->line('session'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('roll_no', $this->lang->line('roll_number'), 'trim|required|xss_clean');

        if ($this->form_validation->run() == false) {
            $this->load->view('layout/header', $data);
            $this->load->view('student/class_update', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $class_id = $this->input->post('class_id');
            $section_id = $this->input->post('section_id');
            $student_id = $this->input->post('student_id');
            $account_department_id = $this->input->post('account_department_id');
            $selected_session_id = $this->input->post('session_id');
            $roll_no = $this->input->post('roll_no', true);
            $same_class = $this->input->post('same_class');
            $current_date_time = date('Y-m-d H:i:s');


            $generatedAdmissionNumber = $this->student_model->generateAdmissionNumber($class_id, $selected_session_id);

            $data_new = array(
                'student_id' => $student_id,
                'class_id' => $class_id,
                'section_id' => $section_id,
                'session_id' => $selected_session_id,
                'admission_no' => $generatedAdmissionNumber,
                'roll_no' => $roll_no,
                'account_department_id' => $account_department_id,
                'status' => 1,
                'updated_at' => $current_date_time,
                'created_at' => $current_date_time
            );

            // Preserve the old class row for fee/history links and make it
            // inactive. A fresh row keeps new class fees tied to the new class.
            $this->db->where('student_id', $student_id)
                ->where('status', 1)
                ->update('student_session', ['status' => 0, 'updated_at' => $current_date_time]);

            $this->db->insert('student_session', $data_new);
            $new_student_session_id = $this->db->insert_id();

            if ($new_student_session_id) {

                $this->db->where('id', $student_id);
                $this->db->update('students', ['admission_no' => $generatedAdmissionNumber]);

                // Get IDs of old fees already collected for this student in this session
                $collected_ids_result = $this->db->select('student_fees_management_id')
                    ->where('student_id', $student_id)
                    ->where('session_id', $selected_session_id)
                    ->get('student_fees_collections')
                    ->result_array();
                $collected_sfm_ids = array_column($collected_ids_result, 'student_fees_management_id');

                // Set old unpaid fees for this session to status=0
                $this->db->where('student_id', $student_id);
                $this->db->where('session_id', $selected_session_id);
                $this->db->where('status', 1);
                if (!empty($collected_sfm_ids)) {
                    $this->db->where_not_in('id', $collected_sfm_ids);
                }
                $this->db->update('student_fees_management', ['status' => 0]);

                $new_fees = $this->student_model->get_fees_by_class_id_for_class_update($class_id, $selected_session_id);

                foreach ($new_fees as $fee) {
                    $fee_data = array(
                        'student_id' => $student_id,
                        'student_session_id' => $new_student_session_id,
                        'class_id' => $class_id,
                        'session_id' => $fee['session_id'],
                        'feetype_id' => $fee['feetype_id'],
                        'original_fees' => $fee['original_fees'],
                        'tuition_fees' => $fee['tuition_fees'],
                        'meal_charges' => $fee['meal_charges'],
                        'discounted_fees' => $fee['discounted_fees'],
                        'is_monthly' => $fee['is_monthly'],
                        'is_skipped' => isset($fee['is_skipped']) ? $fee['is_skipped'] : 0,
                        'added_by' => $this->customlib->getStaffID(),
                        'created_at' => date('Y-m-d H:i:s'),
                    );
                    $this->db->insert('student_fees_management', $fee_data);
                }

                /* $this->db->where('student_id', $student_id);
                $this->db->update('student_fees_collections', ['status' => 0]); */
            }

            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('update_message') . '</div>');
            redirect('student/edit/' . $student_id);
        }
    }

    public function delete($id)
    {
        if ($this->input->method(true) !== 'POST') {
            show_error('Method Not Allowed', 405);
        }

        if (!$this->canHardDeleteStudents()) {
            access_denied();
        }

        $id          = (int) $id;
        $confirmation = trim($this->input->post('confirm_reg_no', true));
        $student      = $this->student_model->getHardDeleteEligibility($id);

        if (empty($student) || $confirmation !== (string) $id) {
            $this->session->set_flashdata('error_msg', 'Deletion cancelled: the Reg no. confirmation did not match.');
        } elseif ($student['collected_fees'] > 0) {
            $this->session->set_flashdata('error_msg', 'Deletion is blocked because this student has collected fees.');
        } elseif ($this->student_model->hardDelete($id)) {
            $this->session->set_flashdata('success_msg', 'Student and related records were permanently deleted.');
        } else {
            $this->session->set_flashdata('error_msg', 'Student could not be deleted. No data was removed.');
        }

        redirect('student/search');
    }

    public function doc_delete($id, $student_id)
    {
        $this->student_model->doc_delete($id);
        $this->session->set_flashdata('msg', $this->lang->line('delete_message'));
        redirect('student/view/' . $student_id);
    }

    public function create()
    {
        /* if (isset($_POST['class_id'])) {

            echo "<pre>";

            print_r($_POST);
            die;
        } */

        if (!$this->rbac->hasPrivilege('student', 'can_add')) {
            access_denied();
        }

        $this->load->model('account_department_model');
        $data['account_departments'] = $this->account_department_model->get();

        $this->load->model('govtschool_model');
        $data['govtschoollist'] = $this->govtschool_model->get();

        $data["month"] = $this->customlib->getMonthDropdown();
        $this->session->set_userdata('top_menu', 'Student Information');
        $this->session->set_userdata('sub_menu', 'student/create');
        $genderList                    = $this->customlib->getGender();
        $data['genderList']            = $genderList;
        $data['sch_setting']           = $this->sch_setting_detail;
        $data['title']                 = 'Add Student';
        $data['title_list']            = 'Recently Added Student';
        $data['adm_auto_insert']       = $this->sch_setting_detail->adm_auto_insert;
        $data["student_categorize"]    = 'class';
        $session                       = $this->setting_model->getCurrentSession();
        $data['feesessiongroup_model'] = $this->feesessiongroup_model->getFeesByGroup();
        $data['transport_fees']        = $this->transportfee_model->getSessionFees($session);
        $student_result                = $this->student_model->getRecentRecord();
        $data['studentlist']           = $student_result;
        $class                         = $this->class_model->get('', $classteacher = 'yes');
        $data['sessionList'] = $this->session_model->getAllSession();

        $data['classlist']    = $class;
        $userdata             = $this->customlib->getUserData();
        $category             = $this->category_model->get();
        $data['categorylist'] = $category;
        $houses               = $this->student_model->gethouselist();
        $data['houses']       = $houses;
        $data["bloodgroup"]   = $this->blood_group;
        $hostelList           = $this->hostel_model->get();
        $data['hostelList']   = $hostelList;
        $vehroute_result      = $this->vehroute_model->getRouteVehiclesList();

        $data['vehroutelist'] = $vehroute_result;
        $custom_fields        = $this->customfield_model->getByBelong('students');

        foreach ($custom_fields as $custom_fields_key => $custom_fields_value) {
            if ($custom_fields_value['validation']) {
                $custom_fields_id   = $custom_fields_value['id'];
                $custom_fields_name = $custom_fields_value['name'];
                $this->form_validation->set_rules("custom_fields[students][" . $custom_fields_id . "]", $custom_fields_name, 'trim|required');
            }
        }

        $this->form_validation->set_rules('first_doc', $this->lang->line('image'), 'callback_handle_uploadfordoc[first_doc]');
        $this->form_validation->set_rules('second_doc', $this->lang->line('image'), 'callback_handle_uploadfordoc[second_doc]');
        $this->form_validation->set_rules('fourth_doc', $this->lang->line('image'), 'callback_handle_uploadfordoc[fourth_doc]');
        $this->form_validation->set_rules('fifth_doc', $this->lang->line('image'), 'callback_handle_uploadfordoc[fifth_doc]');
        $this->form_validation->set_rules('firstname', $this->lang->line('first_name'), "trim|required|xss_clean|regex_match[/^[A-Za-z .'-]+$/]");
        $this->form_validation->set_rules('middlename', $this->lang->line('middle_name'), "trim|xss_clean|regex_match[/^[A-Za-z .'-]+$/]");
        $this->form_validation->set_rules('gender', $this->lang->line('gender'), 'trim|required|xss_clean');
        /* $this->form_validation->set_rules('dob', $this->lang->line('date_of_birth'), 'trim|required|xss_clean'); */
        $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('section_id', $this->lang->line('section'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('religion', $this->lang->line('religion'), 'trim|required|xss_clean');

        if ($this->sch_setting_detail->roll_no) {
            $this->form_validation->set_rules(
                'roll_no',
                $this->lang->line('roll_number'),
                array(
                    'trim',
                    'required',
                    'xss_clean',
                    array('check_roll_no_exists', array($this->student_model, 'check_roll_no_exists')),
                )
            );
        }

        if ($this->sch_setting_detail->guardian_name) {
            $this->form_validation->set_rules('guardian_name', $this->lang->line('guardian_name'), "trim|required|xss_clean|regex_match[/^[A-Za-z .'-]+$/]");
            $this->form_validation->set_rules('guardian_is', $this->lang->line('guardian'), 'trim|required|xss_clean');
        }

        if ($this->sch_setting_detail->guardian_phone) {
            $this->form_validation->set_rules('guardian_phone', $this->lang->line('guardian_phone'), 'trim|required|xss_clean|regex_match[/^[0-9]{10}$/]');
        }

        $this->form_validation->set_rules('guardian_relation', $this->lang->line('guardian_relation'), "trim|xss_clean|regex_match[/^[A-Za-z .'-]+$/]");
        $this->form_validation->set_rules('guardian_occupation', $this->lang->line('guardian_occupation'), "trim|xss_clean|regex_match[/^[A-Za-z .'-]+$/]");
        $this->form_validation->set_rules('father_name', $this->lang->line('father_name'), "trim|xss_clean|regex_match[/^[A-Za-z .'-]+$/]");
        $this->form_validation->set_rules('father_phone', $this->lang->line('father_phone'), 'trim|xss_clean|regex_match[/^[0-9]{10}$/]');
        $this->form_validation->set_rules('father_occupation', $this->lang->line('father_occupation'), "trim|xss_clean|regex_match[/^[A-Za-z .'-]+$/]");
        $this->form_validation->set_rules('mother_name', $this->lang->line('mother_name'), "trim|xss_clean|regex_match[/^[A-Za-z .'-]+$/]");
        $this->form_validation->set_rules('mother_phone', $this->lang->line('mother_phone'), 'trim|xss_clean|regex_match[/^[0-9]{10}$/]');
        $this->form_validation->set_rules('mother_occupation', $this->lang->line('mother_occupation'), "trim|xss_clean|regex_match[/^[A-Za-z .'-]+$/]");
        $this->form_validation->set_rules('aadhaar_no', 'Aadhaar No', 'trim|xss_clean|regex_match[/^[0-9]{12}$/]');
        $this->form_validation->set_rules('govt_school_id', 'Govt. School ID', 'trim|xss_clean|regex_match[/^[A-Za-z0-9]{1,20}$/]');
        $this->form_validation->set_rules('bank_account_no', $this->lang->line('bank_account_number'), 'trim|xss_clean|regex_match[/^[0-9]{9,18}$/]');
        $this->form_validation->set_rules('ifsc_code', $this->lang->line('ifsc_code'), 'trim|xss_clean|regex_match[/^[A-Za-z]{4}0[A-Za-z0-9]{6}$/]');

        $this->form_validation->set_rules(
            'email',
            $this->lang->line('email'),
            array(
                'valid_email',
                array('check_student_email_exists', array($this->student_model, 'check_student_email_exists')),
            )
        );


        $this->form_validation->set_rules(
            'mobileno',
            $this->lang->line('mobile_no'),
            array(
                'xss_clean',
                'regex_match[/^[0-9]{10}$/]',
                // array('check_student_mobile_exists', array($this->student_model, 'check_student_mobile_no_exists')),
            )
        );

        $sibling_id         = $this->input->post('sibling_id');
        if ($sibling_id > 0) {
        } else {
            $this->form_validation->set_rules(
                'guardian_email',
                $this->lang->line('guardian_email'),
                array(
                    'valid_email',
                    array('check_guardian_email_exists', array($this->student_model, 'check_guardian_email_exists')),
                )
            );
        }

        if (!$this->sch_setting_detail->adm_auto_insert) {
            $this->form_validation->set_rules('admission_no', $this->lang->line('admission_no'), 'trim|required|xss_clean|is_unique[students.admission_no]');
        }

        $this->form_validation->set_rules('file', $this->lang->line('image'), 'callback_handle_upload[file]');

        $transport_feemaster_id = $this->input->post('transport_feemaster_id');
        if (!empty($transport_feemaster_id)) {
            $this->form_validation->set_rules('vehroute_id', $this->lang->line('route_list'), 'trim|required|xss_clean');
            $this->form_validation->set_rules('route_pickup_point_id', $this->lang->line('pickup_point'), 'trim|required|xss_clean');
            $this->form_validation->set_rules('transport_feemaster_id[]', $this->lang->line('fees_month'), 'trim|required|xss_clean');
        }

        if ($this->form_validation->run() == false) {
            $this->load->view('layout/header', $data);
            $this->load->view('student/studentCreate', $data);
            $this->load->view('layout/footer', $data);
        } else {

            $custom_field_post  = $this->input->post("custom_fields[students]");
            $custom_value_array = array();
            if (!empty($custom_field_post)) {

                foreach ($custom_field_post as $key => $value) {
                    $check_field_type = $this->input->post("custom_fields[students][" . $key . "]");
                    $field_value      = is_array($check_field_type) ? implode(",", $check_field_type) : $check_field_type;
                    $array_custom     = array(
                        'belong_table_id' => 0,
                        'custom_field_id' => $key,
                        'field_value'     => $field_value,
                    );
                    $custom_value_array[] = $array_custom;
                }
            }

            $selected_session_id   = $this->input->post('selected_session_id');
            $class_id              = $this->input->post('class_id');
            $section_id            = $this->input->post('section_id');
            $fees_discount         = $this->input->post('fees_discount');
            $route_pickup_point_id = $this->input->post('route_pickup_point_id');
            $vehroute_id           = $this->input->post('vehroute_id');
            if (empty($vehroute_id)) {
                $vehroute_id = null;
            }
            $hostel_room_id = $this->input->post('hostel_room_id');

            if (empty($route_pickup_point_id)) {
                $route_pickup_point_id = null;
            }

            if (empty($hostel_room_id)) {
                $hostel_room_id = 0;
            }

            $data_insert = array(
                'firstname'         => ucwords(strtolower(trim($this->input->post('firstname')))),
                'rte'               => $this->input->post('rte'),
                'state'             => $this->input->post('state'),
                'city'              => $this->input->post('city'),
                'pincode'           => $this->input->post('pincode'),
                'cast'              => $this->input->post('cast'),
                'previous_school'   => $this->input->post('previous_school'),
                'dob'               => $this->customlib->dateFormatToYYYYMMDD($this->input->post('dob')),
                'current_address'   => $this->input->post('current_address'),
                'permanent_address' => $this->input->post('permanent_address'),
                'adhar_no'          => $this->input->post('adhar_no'),
                'samagra_id'        => $this->input->post('samagra_id'),
                'bank_account_no'   => $this->input->post('bank_account_no'),
                'bank_name'         => $this->input->post('bank_name'),
                'ifsc_code'         => $this->input->post('ifsc_code'),
                'guardian_email'    => $this->input->post('guardian_email'),
                'gender'            => $this->input->post('gender'),
                'guardian_name'     => $this->input->post('guardian_name'),
                'guardian_relation' => $this->input->post('guardian_relation'),
                'guardian_phone'    => $this->input->post('guardian_phone'),
                'guardian_address'  => $this->input->post('guardian_address'),
                'hostel_room_id'    => $hostel_room_id,
                'note'              => $this->input->post('note'),
                'is_active'         => 'yes',
            );

            if ($this->sch_setting_detail->guardian_occupation) {
                $data_insert['guardian_occupation'] = $this->input->post('guardian_occupation');
            }

            $house             = $this->input->post('house');
            $blood_group       = $this->input->post('blood_group');
            $measurement_date  = $this->input->post('measure_date');
            $roll_no           = $this->input->post('roll_no');
            $lastname          = $this->input->post('lastname');
            $middlename        = $this->input->post('middlename');
            $category_id       = $this->input->post('category_id');
            $religion          = $this->input->post('religion');
            $mobileno          = $this->input->post('mobileno');
            $email             = $this->input->post('email');
            $admission_date    = $this->input->post('admission_date');
            $height            = $this->input->post('height');
            $weight            = $this->input->post('weight');
            $father_name       = $this->input->post('father_name');
            $father_phone      = $this->input->post('father_phone');
            $father_occupation = $this->input->post('father_occupation');
            $mother_name       = $this->input->post('mother_name');
            $mother_phone      = $this->input->post('mother_phone');
            $mother_occupation = $this->input->post('mother_occupation');
            $aadhaar_no = $this->input->post('aadhaar_no');
            $govt_school = $this->input->post('govt_school');
            $govt_school_id = $this->input->post('govt_school_id');
            $recommendationNumber = $this->input->post('recommendationNumber');

            if ($this->sch_setting_detail->guardian_name) {
                $data_insert['guardian_is'] = $this->input->post('guardian_is');
            }
            if (isset($measurement_date)) {
                $data_insert['measurement_date'] = $this->customlib->dateFormatToYYYYMMDD($this->input->post('measure_date'));
            }
            if (isset($house)) {
                $data_insert['school_house_id'] = $this->input->post('house');
            }
            if (isset($blood_group)) {
                $data_insert['blood_group'] = $this->input->post('blood_group');
            }
            if (isset($lastname)) {
                $data_insert['lastname'] = ucwords(strtolower(trim($lastname)));
            }
            if (isset($middlename)) {
                $data_insert['middlename'] = ucwords(strtolower(trim($middlename)));
            }
            if (isset($category_id)) {
                $data_insert['category_id'] = $this->input->post('category_id');
            }
            if (isset($religion)) {
                $data_insert['religion'] = $this->input->post('religion');
            }
            if (isset($mobileno)) {
                $data_insert['mobileno'] = $this->input->post('mobileno');
            }
            if (isset($email)) {
                $data_insert['email'] = $this->input->post('email');
            }
            if (isset($admission_date)) {
                $data_insert['admission_date'] = $this->customlib->dateFormatToYYYYMMDD($this->input->post('admission_date'));
            }
            if (isset($height)) {
                $data_insert['height'] = $this->input->post('height');
            }
            if (isset($weight)) {
                $data_insert['weight'] = $this->input->post('weight');
            }
            if (isset($father_name)) {
                $data_insert['father_name'] = $this->input->post('father_name');
            }
            if (isset($father_phone)) {
                $data_insert['father_phone'] = $this->input->post('father_phone');
            }
            if (isset($father_occupation)) {
                $data_insert['father_occupation'] = $this->input->post('father_occupation');
            }
            if (isset($mother_name)) {
                $data_insert['mother_name'] = $this->input->post('mother_name');
            }
            if (isset($mother_phone)) {
                $data_insert['mother_phone'] = $this->input->post('mother_phone');
            }
            if (isset($mother_occupation)) {
                $data_insert['mother_occupation'] = $this->input->post('mother_occupation');
            }
            if (isset($aadhaar_no)) {
                $data_insert['aadhaar_no'] = $aadhaar_no;
            }
            if (isset($govt_school)) {
                $data_insert['govt_school'] = $govt_school;
            }
            if (isset($govt_school_id)) {
                $data_insert['govt_school_id'] = $govt_school_id;
            }
            if (isset($recommendationNumber)) {
                $data_insert['recommendationNumber'] = $this->input->post('recommendationNumber');
            }

            $fee_session_group_id = $this->input->post('fee_session_group_id');

            $insert                            = true;
            $data_setting                      = array();
            $data_setting['id']                = $this->sch_setting_detail->id;
            $data_setting['adm_auto_insert']   = $this->sch_setting_detail->adm_auto_insert;
            $data_setting['adm_update_status'] = $this->sch_setting_detail->adm_update_status;
            $admission_no                      = 0;

            /** modified by Sakil on 05032024 */

            $generatedAdmissionNumber = $this->student_model->generateAdmissionNumber($class_id, $selected_session_id);
            //echo $generatedAdmissionNumber; die;

            if ($generatedAdmissionNumber) {
                $admission_no = $generatedAdmissionNumber;
            }

            $data_insert['admission_no'] = $admission_no;

            /** end of modification */

            if (empty($_FILES["file"])) {
                if ($this->input->post('gender') == 'Female') {
                    $data_insert['image'] = 'uploads/student_images/default_female.jpg';
                } else {
                    $data_insert['image'] = 'uploads/student_images/default_male.jpg';
                }
            }

            if (isset($_FILES["recommendationFile"]) && !empty($_FILES['recommendationFile']['name'])) {
                $img_name             = $this->media_storage->fileupload("recommendationFile", "./uploads/student_recommendations/");
                $data_insert['recommendationFile'] = "uploads/student_recommendations/" . $img_name;
            }

            if (isset($_FILES["file"]) && !empty($_FILES['file']['name'])) {
                $img_name             = $this->media_storage->fileupload("file", "./uploads/student_images/");
                $data_insert['image'] = "uploads/student_images/" . $img_name;
            }

            if (isset($_FILES["father_pic"]) && !empty($_FILES['father_pic']['name'])) {
                $img_name                  = $this->media_storage->fileupload("father_pic", "./uploads/student_images/");
                $data_insert['father_pic'] = "uploads/student_images/" . $img_name;
            }

            if (isset($_FILES["mother_pic"]) && !empty($_FILES['mother_pic']['name'])) {
                $img_name                  = $this->media_storage->fileupload("mother_pic", "./uploads/student_images/");
                $data_insert['mother_pic'] = "uploads/student_images/" . $img_name;
            }

            if (isset($_FILES["guardian_pic"]) && !empty($_FILES['guardian_pic']['name'])) {
                $img_name                    = $this->media_storage->fileupload("guardian_pic", "./uploads/student_images/");
                $data_insert['guardian_pic'] = "uploads/student_images/" . $img_name;
            }

            if ($insert) {

                $insert_id = $this->student_model->add($data_insert, $data_setting);

                $current_user_id = $this->session->userdata['admin']['id'];
                $current_user_name = $this->session->userdata['admin']['username'];
                $current_date_time = date('Y-m-d H:i:s');

                $log_data = array(
                    'id' => $insert_id,
                    'admission_no' => $data_insert['admission_no'],
                    'roll_no' => $roll_no,
                    'admission_date' => $data_insert['admission_date'],
                    'firstname' => $data_insert['firstname'],
                    'lastname' => $data_insert['lastname'],
                    'mobileno' => $data_insert['mobileno'],
                    'dob' => $data_insert['dob'],
                    'current_address' => $data_insert['current_address'],
                    'adhar_no' => $data_insert['admission_no'],
                    'father_name' => $data_insert['father_name'],
                    'father_phone' => $data_insert['father_phone'],
                    'guardian_name' => $data_insert['guardian_name'],
                    'recommendationNumber' => $data_insert['recommendationNumber'],
                    'created_by' => $current_user_id,
                    'created_at' => $current_date_time
                );


                $action = 1; //Adding
                $user_id = $current_user_id;
                $user_name = $current_user_name;
                log_students($log_data, $action, $user_id, $user_name);


                $data_new = array(
                    'student_id'            => $insert_id,
                    'class_id'              => $class_id,
                    'section_id'            => $section_id,
                    'session_id'            => $selected_session_id,
                    'admission_no'          => $admission_no,
                    'account_department_id' => $this->input->post('account_department_id')
                );
                if (isset($roll_no)) {
                    $data_new['roll_no'] = $roll_no;
                }

                $student_session_id     = $this->student_model->add_student_session($data_new);

                /** fees insert to student fees management table */
                $student_data = $this->input->post();

                // Extract fees information from the student data
                $session_ids = $student_data['session_id'];
                $feetype_ids = $student_data['feetype_id'];
                $original_fees = $student_data['original_fees'];
                $tuition_fees = $student_data['tuition_fees'];
                $meal_charges = $student_data['meal_charges'];
                $discounted_fees = $student_data['discounted_fees'];
                $is_monthly = $student_data['is_monthly'];
                $is_skipped = isset($student_data['is_skipped']) ? $student_data['is_skipped'] : array();


                // Loop through the fees data and insert into the student_fees_management table
                for ($i = 0; $i < count($feetype_ids); $i++) {
                    //$discpounded_amount = $discounted_fees[$i] - $original_fees[$i];

                    $update_log = [];
                    // Prepare update log data
                    $update_log[] = array(
                        "added_by" => $current_user_id,
                        "date" => $current_date_time,
                        "amount" => $discounted_fees[$i]
                    );

                    // Insert fee data
                    $fee_data = array(
                        'student_id' => $insert_id, // Assuming you have the student_id available
                        'student_session_id' => $student_session_id, // Assuming you have the student_id available
                        'class_id' => $student_data['class_id'],
                        'session_id' => $session_ids[$i],
                        'feetype_id' => $feetype_ids[$i],
                        'original_fees' => $original_fees[$i],
                        'tuition_fees' => $tuition_fees[$i],
                        'meal_charges' => $meal_charges[$i],
                        'discounted_fees' => $discounted_fees[$i],
                        'is_monthly' => $is_monthly[$i],
                        'is_skipped' => isset($is_skipped[$i]) ? $is_skipped[$i] : 0,
                        'added_by' => $current_user_id,
                        //'update_log' => serialize($update_log), // Serialize update log array
                    );

                    // Insert the fee data into the student_fees_management table
                    $this->db->insert('student_fees_management', $fee_data);
                    $sfm_id = $this->db->insert_id();

                    $sfm_log_data = array_merge(array('id' => $sfm_id), $fee_data);

                    $action = 1; //Adding
                    $user_id = $current_user_id;
                    $user_name = $current_user_name;
                    log_student_fees_management($sfm_log_data, $action, $user_id, $user_name);
                }
                /* echo "<pre>";
                print_r($fee_data);
                die; */
                /** end of fees insert */

                if (!empty($custom_value_array)) {
                    $this->customfield_model->insertRecord($custom_value_array, $insert_id);
                }


                $user_password = $this->role->get_random_password($chars_min = 6, $chars_max = 6, $use_upper_case = false, $include_numbers = true, $include_special_chars = false);

                $sibling_id         = $this->input->post('sibling_id');
                $data_student_login = array(
                    'username' => $this->student_login_prefix . $insert_id,
                    'password' => $user_password,
                    'user_id'  => $insert_id,
                    'role'     => 'student',
                    'lang_id'  => $this->sch_setting_detail->lang_id,
                );

                $this->user_model->add($data_student_login);

                if ($sibling_id > 0) {
                    $student_sibling = $this->student_model->get($sibling_id);
                    $update_student  = array(
                        'id'        => $insert_id,
                        'parent_id' => $student_sibling['parent_id'],
                    );
                    $student_sibling = $this->student_model->add($update_student);
                } else {
                    $parent_password   = $this->role->get_random_password($chars_min = 6, $chars_max = 6, $use_upper_case = false, $include_numbers = true, $include_special_chars = false);
                    $temp              = $insert_id;
                    $data_parent_login = array(
                        'username' => $this->parent_login_prefix . $insert_id,
                        'password' => $parent_password,
                        'user_id'  => 0,
                        'role'     => 'parent',
                        'childs'   => $temp,
                    );
                    $ins_parent_id  = $this->user_model->add($data_parent_login);
                    $update_student = array(
                        'id'        => $insert_id,
                        'parent_id' => $ins_parent_id,
                    );
                    $this->student_model->add($update_student);
                }

                $upload_dir_path  = $this->customlib->getFolderPath() . './uploads/student_documents/' . $insert_id . '/';
                $upload_directory = './uploads/student_documents/' . $insert_id . '/';
                if (!is_dir($upload_dir_path) && !mkdir($upload_dir_path)) {
                    die("Error creating folder $upload_dir_path");
                }

                if (isset($_FILES["first_doc"]) && !empty($_FILES['first_doc']['name'])) {

                    $first_title = $this->input->post('first_title');
                    $imp         = $this->media_storage->fileupload("first_doc", $upload_directory);
                    $data_img    = array('student_id' => $insert_id, 'title' => $first_title, 'doc' => $imp);
                    $this->student_model->adddoc($data_img);
                }

                if (isset($_FILES["second_doc"]) && !empty($_FILES['second_doc']['name'])) {
                    $second_title = $this->input->post('second_title');
                    $imp          = $this->media_storage->fileupload("second_doc", $upload_directory);
                    $data_img     = array('student_id' => $insert_id, 'title' => $second_title, 'doc' => $imp);
                    $this->student_model->adddoc($data_img);
                }

                if (isset($_FILES["fourth_doc"]) && !empty($_FILES['fourth_doc']['name'])) {
                    $fourth_title = $this->input->post('fourth_title');
                    $imp          = $this->media_storage->fileupload("fourth_doc", $upload_directory);
                    $data_img     = array('student_id' => $insert_id, 'title' => $fourth_title, 'doc' => $imp);
                    $this->student_model->adddoc($data_img);
                }

                if (isset($_FILES["fifth_doc"]) && !empty($_FILES['fifth_doc']['name'])) {
                    $fifth_title = $this->input->post('fifth_title');
                    $imp         = $this->media_storage->fileupload("fifth_doc", $upload_directory);
                    $data_img    = array('student_id' => $insert_id, 'title' => $fifth_title, 'doc' => $imp);
                    $this->student_model->adddoc($data_img);
                }

                $sender_details = array('student_id' => $insert_id, 'student_phone' => $this->input->post('mobileno'), 'guardian_phone' => $this->input->post('guardian_phone'), 'student_email' => $this->input->post('email'), 'guardian_email' => $this->input->post('guardian_email'), 'student_session_id' => $student_session_id);

                $this->mailsmsconf->mailsms('student_admission', $sender_details);

                $student_login_detail = array('id' => $insert_id, 'credential_for' => 'student', 'first_name' => $this->input->post('firstname'), 'last_name' => $this->input->post('lastname'), 'username' => $this->student_login_prefix . $insert_id, 'password' => $user_password, 'contact_no' => $this->input->post('mobileno'), 'email' => $this->input->post('email'), 'admission_no' => $data_insert['admission_no'], 'student_session_id' => $student_session_id);

                $this->mailsmsconf->mailsms('student_login_credential', $student_login_detail);

                if ($sibling_id > 0) {
                } else {
                    $parent_login_detail = array('id' => $insert_id, 'credential_for' => 'parent', 'username' => $this->parent_login_prefix . $insert_id, 'password' => $parent_password, 'contact_no' => $this->input->post('guardian_phone'), 'email' => $this->input->post('guardian_email'), 'admission_no' => $data_insert['admission_no'], 'student_session_id' => $student_session_id);
                    $this->mailsmsconf->mailsms('student_login_credential', $parent_login_detail);
                }

                $this->session->set_flashdata('msg', '<div class="alert alert-success">' . $this->lang->line('success_message') . '</div>');
                //redirect('student/edit/' . $insert_id);
                redirect('student/create');
            } else {

                $data['error_message'] = $this->lang->line('admission_no') . ' ' . $admission_no . ' ' . $this->lang->line('already_exists');
                $this->load->view('layout/header', $data);
                $this->load->view('student/studentCreate', $data);
                $this->load->view('layout/footer', $data);
            }
        }
    }

    public function get_fees_by_class_id()
    {
        $student_data = [];
        // Retrieve class_id and session_id from POST data
        $class_id = $this->input->post('class_id');
        $session_id = $this->input->post('session_id'); // New: Get session_id

        $student_id = 0;
        if (isset($_POST['student_id'])) {
            $student_id = $this->input->post('student_id');
        }

        // Query the database to fetch fees for the selected class ID and session ID
        $this->db->select('fees_master_class_wise.is_monthly, fees_master_class_wise.session_id, fees_master_class_wise.tuition_fees, fees_master_class_wise.meal_charges, fees_master_class_wise.fees_amount as original_fees, fees_master_class_wise.fees_amount as discounted_fees, sessions.session, feetype.id as feetype_id, feetype.type');
        $this->db->from('fees_master_class_wise');
        $this->db->join('feetype', 'feetype.id = fees_master_class_wise.feetype_id');
        $this->db->join('sessions', 'sessions.id = fees_master_class_wise.session_id');
        $this->db->where('fees_master_class_wise.class_id', $class_id);
        if ($session_id) {
            $this->db->where('fees_master_class_wise.session_id', $session_id);
        }
        $this->db->order_by('fees_master_class_wise.fees_order', 'ASC');
        $master_fees_query = $this->db->get();
        $masterFees = $master_fees_query->result_array();

        // A class fee can be duplicated in fees_master_class_wise (usually
        // after a fee setup was copied more than once). The editor identifies
        // a fee by its session and fee type, so render only one canonical row.
        $masterFees = $this->uniqueFeesBySessionAndType($masterFees);

        $studentFees = [];
        if ($student_id > 0) {
            // Query the database to fetch fees already assigned to the student (Student Fees)
            $this->db->select('student_fees_management.*, sessions.session, feetype.type');
            $this->db->from('student_fees_management');
            $this->db->join('feetype', 'feetype.id = student_fees_management.feetype_id');
            $this->db->join('sessions', 'sessions.id = student_fees_management.session_id');
            $this->db->where('student_fees_management.student_id', $student_id);
            $this->db->where('student_fees_management.class_id', $class_id);
            if ($session_id) {
                $this->db->where('student_fees_management.session_id', $session_id);
            }
            $student_fees_query = $this->db->get();
            $studentFees = $student_fees_query->result_array();
            $studentFees = $this->uniqueFeesBySessionAndType($studentFees);
        }

        $mergedFees = [];
        foreach ($masterFees as $m_fee) {
            $found_in_student_fees = false;
            foreach ($studentFees as $s_fee) {
                // Check if the fee type and session match
                if ($m_fee['feetype_id'] == $s_fee['feetype_id'] && $m_fee['session_id'] == $s_fee['session_id']) {
                    // This fee is already added to the student, use student's data
                    $mergedFees[] = array_merge($s_fee, ['is_added' => true]);
                    $found_in_student_fees = true;
                    break;
                }
            }
            if (!$found_in_student_fees) {
                // This fee is from master but not yet added to student
                $mergedFees[] = array_merge($m_fee, ['is_added' => false]);
            }
        }

        $feesData = $mergedFees;

        // Prepare the response data
        if ($feesData) {
            $response = array(
                'success' => true,
                'feesData' => $feesData
            );
        } else {
            // No fees found for the selected class
            $response = array(
                'success' => false,
                'message' => 'No fees found for the selected class.'
            );
        }

        // Send JSON response
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }

    /** Keep one fee row per session and fee type for the student fee editor. */
    private function uniqueFeesBySessionAndType($fees)
    {
        $unique = [];
        foreach ($fees as $fee) {
            $key = (int) $fee['session_id'] . ':' . (int) $fee['feetype_id'];
            if (!isset($unique[$key])) {
                $unique[$key] = $fee;
            }
        }
        return array_values($unique);
    }

    public function update_discounted_fees()
    {
        // Retrieve data from POST request
        $feetype_id = $this->input->post('feetype_id');
        $student_id = $this->input->post('student_id');
        $class_id = $this->input->post('class_id');
        $discounted_fees = $this->input->post('discounted_fees');
        $is_monthly = $this->input->post('is_monthly');
        $is_skipped = $this->input->post('is_skipped');

        // Retrieve existing update_log for the given record
        $this->db->select('update_log');
        $this->db->where(array(
            'feetype_id' => $feetype_id,
            'student_id' => $student_id,
            'class_id' => $class_id
        ));
        $query = $this->db->get('student_fees_management');
        $existing_update_log = $query->row()->update_log;

        // Prepare update information
        $current_user_id = $this->session->userdata('admin')['id'];
        $current_date_time = date('Y-m-d H:i:s');

        $update_info = array(
            'added_by' => $current_user_id,
            'date' => $current_date_time,
            'amount' => $discounted_fees
        );
        $updated_update_log = [];
        // Append the new update information to existing update_log
        $updated_update_log_arr = unserialize($existing_update_log);
        if (!empty($updated_update_log_arr)) {
            foreach ($updated_update_log_arr as $updated_update_log_val) {
                $updated_update_log[] =  $updated_update_log_val;
            }
            $updated_update_log[] = $update_info;
        } else {
            $updated_update_log[] = $update_info;
        }


        // Update discounted fees and update_log in student_fees_management table
        $data = array(
            'discounted_fees' => $discounted_fees,
            'is_monthly' => $is_monthly,
            'is_skipped' => $is_skipped,
            'update_log' => serialize($updated_update_log)
        );
        $this->db->where(array(
            'feetype_id' => $feetype_id,
            'student_id' => $student_id,
            'class_id' => $class_id
        ));
        $this->db->update('student_fees_management', $data);

        // Check if the update was successful
        if ($this->db->affected_rows() > 0) {
            $response = array('success' => true);
        } else {
            $response = array('success' => false);
        }

        // Send JSON response
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }



    public function create_doc()
    {
        $this->form_validation->set_rules('first_title', $this->lang->line('title'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('first_doc', $this->lang->line('document'), 'callback_handle_uploadcreate_doc');

        if ($this->form_validation->run() == false) {
            $msg = array(
                'first_title' => form_error('first_title'),
                'first_doc'   => form_error('first_doc'),
            );
            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
        } else {
            $student_id = $this->input->post('student_id');
            if (isset($_FILES["first_doc"]) && !empty($_FILES['first_doc']['name'])) {
                $upload_dir_path = $this->customlib->getFolderPath() . './uploads/student_documents/' . $student_id . '/';

                if (!is_dir($upload_dir_path) && !mkdir($upload_dir_path)) {
                    die("Error creating folder $upload_dir_path");
                }

                $fileInfo    = pathinfo($_FILES["first_doc"]["name"]);
                $first_title = $this->input->post('first_title');
                $imp         = $this->media_storage->fileupload("first_doc", './uploads/student_documents/' . $student_id . '/');
                $data_img    = array('student_id' => $student_id, 'title' => $first_title, 'doc' => $imp);
                $this->student_model->adddoc($data_img);
            }

            $msg   = $this->lang->line('success_message');
            $array = array('status' => 'success', 'error' => '', 'message' => $msg);
        }
        echo json_encode($array);
    }

    public function handle_uploadcreate_doc()
    {
        $image_validate = $this->config->item('file_validate');
        $result         = $this->filetype_model->get();
        if (isset($_FILES["first_doc"]) && !empty($_FILES['first_doc']['name'])) {

            $file_type = $_FILES["first_doc"]['type'];
            $file_size = $_FILES["first_doc"]["size"];
            $file_name = $_FILES["first_doc"]["name"];

            $allowed_extension = array_map('trim', array_map('strtolower', explode(',', $result->file_extension)));
            $allowed_mime_type = array_map('trim', array_map('strtolower', explode(',', $result->file_mime)));
            $ext               = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mtype = finfo_file($finfo, $_FILES['first_doc']['tmp_name']);
            finfo_close($finfo);

            if (!in_array($mtype, $allowed_mime_type)) {
                $this->form_validation->set_message('handle_uploadcreate_doc', $this->lang->line('file_type_not_allowed'));
                return false;
            }

            if (!in_array($ext, $allowed_extension) || !in_array($file_type, $allowed_mime_type)) {
                $this->form_validation->set_message('handle_uploadcreate_doc', $this->lang->line('extension_not_allowed'));
                return false;
            }
            if ($file_size > $result->file_size) {
                $this->form_validation->set_message('handle_uploadcreate_doc', $this->lang->line('file_size_shoud_be_less_than') . number_format($result->file_size / 1048576, 2) . " MB");
                return false;
            }

            return true;
        } else {
            $this->form_validation->set_message('handle_uploadcreate_doc', $this->lang->line('the_document_field_is_required'));
            return false;
        }
        return true;
    }

    public function handle_father_upload()
    {
        $image_validate = $this->config->item('image_validate');
        $result         = $this->filetype_model->get();
        if (isset($_FILES["father_pic"]) && !empty($_FILES['father_pic']['name'])) {

            $file_type = $_FILES["father_pic"]['type'];
            $file_size = $_FILES["father_pic"]["size"];
            $file_name = $_FILES["father_pic"]["name"];

            $allowed_extension = array_map('trim', array_map('strtolower', explode(',', $result->image_extension)));
            $allowed_mime_type = array_map('trim', array_map('strtolower', explode(',', $result->image_mime)));
            $ext               = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

            if ($files = @getimagesize($_FILES['father_pic']['tmp_name'])) {

                if (!in_array($files['mime'], $allowed_mime_type)) {
                    $this->form_validation->set_message('handle_father_upload', $this->lang->line('file_type_not_allowed'));
                    return false;
                }

                if (!in_array($ext, $allowed_extension) || !in_array($file_type, $allowed_mime_type)) {
                    $this->form_validation->set_message('handle_father_upload', $this->lang->line('extension_not_allowed'));
                    return false;
                }
                if ($file_size > $result->image_size) {
                    $this->form_validation->set_message('handle_father_upload', $this->lang->line('file_size_shoud_be_less_than') . number_format($result->image_size / 1048576, 2) . " MB");
                    return false;
                }
            } else {
                $this->form_validation->set_message('handle_father_upload', $this->lang->line('file_type_extension_error_uploading_image'));
                return false;
            }

            return true;
        }
        return true;
    }

    public function handle_mother_upload()
    {
        $image_validate = $this->config->item('image_validate');
        $result         = $this->filetype_model->get();
        if (isset($_FILES["mother_pic"]) && !empty($_FILES['mother_pic']['name'])) {

            $file_type = $_FILES["mother_pic"]['type'];
            $file_size = $_FILES["mother_pic"]["size"];
            $file_name = $_FILES["mother_pic"]["name"];

            $allowed_extension = array_map('trim', array_map('strtolower', explode(',', $result->image_extension)));
            $allowed_mime_type = array_map('trim', array_map('strtolower', explode(',', $result->image_mime)));
            $ext               = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

            if ($files = @getimagesize($_FILES['mother_pic']['tmp_name'])) {

                if (!in_array($files['mime'], $allowed_mime_type)) {
                    $this->form_validation->set_message('handle_mother_upload', $this->lang->line('file_type_not_allowed'));
                    return false;
                }

                if (!in_array($ext, $allowed_extension) || !in_array($file_type, $allowed_mime_type)) {
                    $this->form_validation->set_message('handle_mother_upload', $this->lang->line('extension_not_allowed'));
                    return false;
                }
                if ($file_size > $result->image_size) {
                    $this->form_validation->set_message('handle_mother_upload', $this->lang->line('file_size_shoud_be_less_than') . number_format($result->image_size / 1048576, 2) . " MB");
                    return false;
                }
            } else {
                $this->form_validation->set_message('handle_mother_upload', $this->lang->line('file_type_extension_error_uploading_image'));
                return false;
            }

            return true;
        }
        return true;
    }

    public function handle_guardian_upload()
    {

        $image_validate = $this->config->item('image_validate');
        $result         = $this->filetype_model->get();
        if (isset($_FILES["guardian_pic"]) && !empty($_FILES['guardian_pic']['name'])) {

            $file_type = $_FILES["guardian_pic"]['type'];
            $file_size = $_FILES["guardian_pic"]["size"];
            $file_name = $_FILES["guardian_pic"]["name"];

            $allowed_extension = array_map('trim', array_map('strtolower', explode(',', $result->image_extension)));
            $allowed_mime_type = array_map('trim', array_map('strtolower', explode(',', $result->image_mime)));
            $ext               = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

            if ($files = @getimagesize($_FILES['guardian_pic']['tmp_name'])) {

                if (!in_array($files['mime'], $allowed_mime_type)) {
                    $this->form_validation->set_message('handle_guardian_upload', $this->lang->line('file_type_not_allowed'));
                    return false;
                }

                if (!in_array($ext, $allowed_extension) || !in_array($file_type, $allowed_mime_type)) {
                    $this->form_validation->set_message('handle_guardian_upload', $this->lang->line('extension_not_allowed'));
                    return false;
                }
                if ($file_size > $result->image_size) {
                    $this->form_validation->set_message('handle_guardian_upload', $this->lang->line('file_size_shoud_be_less_than') . number_format($result->image_size / 1048576, 2) . " MB");
                    return false;
                }
            } else {
                $this->form_validation->set_message('handle_guardian_upload', $this->lang->line('file_type_extension_error_uploading_image'));
                return false;
            }

            return true;
        }
        return true;
    }

    public function sendpassword()
    {
        $student_login_detail = array('id' => $this->input->post('student_id'), 'credential_for' => 'student', 'username' => $this->input->post('username'), 'password' => $this->input->post('password'), 'contact_no' => $this->input->post('contact_no'), 'email' => $this->input->post('email'), 'admission_no' => $this->input->post('admission_no'), 'student_session_id' => $this->input->post('student_session_id'));

        $msg = $this->mailsmsconf->mailsms('student_login_credential', $student_login_detail);
    }

    public function send_parent_password()
    {
        $parent_login_detail = array('id' => $this->input->post('student_id'), 'credential_for' => 'parent', 'username' => $this->input->post('username'), 'password' => $this->input->post('password'), 'contact_no' => $this->input->post('contact_no'), 'email' => $this->input->post('email'), 'admission_no' => $this->input->post('admission_no'), 'student_session_id' => $this->input->post('student_session_id'));

        $msg = $this->mailsmsconf->mailsms('student_login_credential', $parent_login_detail);
    }

    public function import()
    {
        if (!$this->rbac->hasPrivilege('import_student', 'can_view')) {
            access_denied();
        }
        $data['title']      = $this->lang->line('import_student');
        $data['title_list'] = $this->lang->line('recently_added_student');
        $session            = $this->setting_model->getCurrentSession();
        $class              = $this->class_model->get('', $classteacher = 'yes');
        $data['classlist']  = $class;
        $userdata           = $this->customlib->getUserData();

        $category = $this->category_model->get();

        $fields = array('admission_no', 'roll_no', 'firstname', 'middlename', 'lastname', 'gender', 'dob', 'category_id', 'religion', 'cast', 'mobileno', 'email', 'admission_date', 'blood_group', 'school_house_id', 'height', 'weight', 'measurement_date', 'father_name', 'father_phone', 'father_occupation', 'mother_name', 'mother_phone', 'mother_occupation', 'guardian_is', 'guardian_name', 'guardian_relation', 'guardian_email', 'guardian_phone', 'guardian_occupation', 'guardian_address', 'current_address', 'permanent_address', 'bank_account_no', 'bank_name', 'ifsc_code', 'adhar_no', 'samagra_id', 'rte', 'previous_school', 'note');

        $data["fields"]       = $fields;
        $data['categorylist'] = $category;
        $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('section_id', $this->lang->line('section'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('file', $this->lang->line('image'), 'callback_handle_csv_upload');
        if ($this->form_validation->run() == false) {
            $this->load->view('layout/header', $data);
            $this->load->view('student/import', $data);
            $this->load->view('layout/footer', $data);
        } else {

            $student_categorize = 'class';
            if ($student_categorize == 'class') {
                $section = 0;
            } else if ($student_categorize == 'section') {

                $section = $this->input->post('section_id');
            }
            $class_id   = $this->input->post('class_id');
            $section_id = $this->input->post('section_id');

            $session = $this->setting_model->getCurrentSession();
            if (isset($_FILES["file"]) && !empty($_FILES['file']['name'])) {
                $ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
                if ($ext == 'csv') {
                    $file = $_FILES['file']['tmp_name'];
                    $this->load->library('CSVReader');
                    $result = $this->csvreader->parse_file($file);

                    if (!empty($result)) {
                        $rowcount = 0;
                        for ($i = 1; $i <= count($result); $i++) {

                            $student_data[$i] = array();
                            $n                = 0;
                            foreach ($result[$i] as $key => $value) {

                                $student_data[$i][$fields[$n]] = $this->encoding_lib->toUTF8($result[$i][$key]);

                                $student_data[$i]['is_active'] = 'yes';

                                if (date('Y-m-d', strtotime($result[$i]['date_of_birth'])) === $result[$i]['date_of_birth']) {
                                    $student_data[$i]['dob'] = date('Y-m-d', strtotime($result[$i]['date_of_birth']));
                                } else {
                                    $student_data[$i]['dob'] = null;
                                }

                                if (date('Y-m-d', strtotime($result[$i]['measurement_date'])) === $result[$i]['measurement_date']) {
                                    $student_data[$i]['measurement_date'] = date('Y-m-d', strtotime($result[$i]['measurement_date']));
                                } else {
                                    $student_data[$i]['measurement_date'] = '';
                                }

                                if (date('Y-m-d', strtotime($result[$i]['admission_date'])) === $result[$i]['admission_date']) {
                                    $student_data[$i]['admission_date'] = date('Y-m-d', strtotime($result[$i]['admission_date']));
                                } else {
                                    $student_data[$i]['admission_date'] = null;
                                }
                                $n++;
                            }

                            $roll_no                           = $student_data[$i]["roll_no"];
                            $adm_no                            = $student_data[$i]["admission_no"];
                            $mobile_no                         = $student_data[$i]["mobileno"];
                            $email                             = $student_data[$i]["email"];
                            $guardian_phone                    = $student_data[$i]["guardian_phone"];
                            $guardian_email                    = $student_data[$i]["guardian_email"];
                            $data_setting                      = array();
                            $data_setting['id']                = $this->sch_setting_detail->id;
                            $data_setting['adm_auto_insert']   = $this->sch_setting_detail->adm_auto_insert;
                            $data_setting['adm_update_status'] = $this->sch_setting_detail->adm_update_status;
                            //-------------------------

                            if ($this->sch_setting_detail->adm_auto_insert) {
                                if ($this->sch_setting_detail->adm_update_status) {
                                    $last_student                     = $this->student_model->lastRecord();
                                    $last_admission_digit             = str_replace($this->sch_setting_detail->adm_prefix, "", $last_student->admission_no);
                                    $admission_no                     = $this->sch_setting_detail->adm_prefix . sprintf("%0" . $this->sch_setting_detail->adm_no_digit . "d", $last_admission_digit + 1);
                                    $student_data[$i]["admission_no"] = $admission_no;
                                } else {
                                    $admission_no                     = $this->sch_setting_detail->adm_prefix . $this->sch_setting_detail->adm_start_from;
                                    $student_data[$i]["admission_no"] = $admission_no;
                                }

                                $admission_no_exists = $this->student_model->check_adm_exists($admission_no);
                                if ($admission_no_exists) {
                                    $insert = "";
                                } else {
                                    $insert_id = $this->student_model->add($student_data[$i], $data_setting);
                                }
                            } else {

                                if ($this->form_validation->is_unique($adm_no, 'students.admission_no')) {
                                    $insert_id = $this->student_model->add($student_data[$i], $data_setting);
                                } else {
                                    $insert_id = "";
                                }
                            }

                            //-------------------------
                            if (!empty($insert_id)) {
                                $data_new = array(
                                    'student_id' => $insert_id,
                                    'class_id'   => $class_id,
                                    'section_id' => $section_id,
                                    'session_id' => $session,
                                );

                                $student_session_id = $this->student_model->add_student_session($data_new);
                                $user_password = $this->role->get_random_password($chars_min = 6, $chars_max = 6, $use_upper_case = false, $include_numbers = true, $include_special_chars = false);
                                $sibling_id    = $this->input->post('sibling_id');

                                $data_student_login = array(
                                    'username' => $this->student_login_prefix . $insert_id,
                                    'password' => $user_password,
                                    'user_id'  => $insert_id,
                                    'role'     => 'student',
                                );

                                $this->user_model->add($data_student_login);
                                $parent_password = $this->role->get_random_password($chars_min = 6, $chars_max = 6, $use_upper_case = false, $include_numbers = true, $include_special_chars = false);

                                $temp              = $insert_id;
                                $data_parent_login = array(
                                    'username' => $this->parent_login_prefix . $insert_id,
                                    'password' => $parent_password,
                                    'user_id'  => $insert_id,
                                    'role'     => 'parent',
                                    'childs'   => $temp,
                                );

                                $ins_id         = $this->user_model->add($data_parent_login);
                                $update_student = array(
                                    'id'        => $insert_id,
                                    'parent_id' => $ins_id,
                                );

                                $this->student_model->add($update_student);
                                $sender_details = array('student_id' => $insert_id, 'contact_no' => $guardian_phone, 'email' => $guardian_email);
                                $this->mailsmsconf->mailsms('student_admission', $sender_details);

                                $student_login_detail = array('id' => $insert_id, 'credential_for' => 'student', 'username' => $this->student_login_prefix . $insert_id, 'password' => $user_password, 'contact_no' => $mobile_no, 'email' => $email, 'admission_no' => $admission_no);
                                $this->mailsmsconf->mailsms('student_login_credential', $student_login_detail);

                                $parent_login_detail = array('id' => $insert_id, 'credential_for' => 'parent', 'username' => $this->parent_login_prefix . $insert_id, 'password' => $parent_password, 'contact_no' => $guardian_phone, 'email' => $guardian_email, 'admission_no' => $admission_no);

                                $this->mailsmsconf->mailsms('student_login_credential', $parent_login_detail);

                                $data['csvData'] = $result;
                                $this->session->set_flashdata('msg', '<div class="alert alert-success text-center">' . $this->lang->line('students_imported_successfully') . '</div>');

                                $rowcount++;
                                $this->session->set_flashdata('msg', '<div class="alert alert-success text-center">' . $this->lang->line('total') . ' ' . count($result) . $this->lang->line('records_found_in_csv_file_total') . $rowcount . ' ' . $this->lang->line('records_imported_successfully') . '</div>');
                            } else {

                                $this->session->set_flashdata('msg', '<div class="alert alert-danger text-center">' . $this->lang->line('record_already_exist') . '</div>');
                            }
                        }
                    } else {

                        $this->session->set_flashdata('msg', '<div class="alert alert-danger text-center">' . $this->lang->line('no_record_found') . '</div>');
                    }
                } else {

                    $this->session->set_flashdata('msg', '<div class="alert alert-danger text-center">' . $this->lang->line('please_upload_csv_file_only') . '</div>');
                }
            }

            redirect('student/import');
        }
    }


    public function exportformat()
    {
        $this->load->helper('download');

        // Define the fields, consistent with the bulk_upload method
        $fields = array(
            'roll_no' => "Roll NO",
            'firstname' => "First Name",
            'aadhaar_no' => "Adhar Number",
            'govt_school' => "Govt School",
            'govt_school_id' => "Banglar Shiksha ID",
            'dob' => "Date Of Birth",
            'gender' => "Gender",
            'father_name' => "Father Name",
            'father_phone' => "Father Phone",
            'mobileno' => "Mobile NO",
            'father_occupation' => "Father Occupation",
            'mother_name' => "Mother Name",
            'guardian_occupation' => "Guardian Occupation",
            'guardian_address' => "Guardian Address",
        );

        // Prepare sample data - consistent with the view's sample data structure for demonstration
        $sample_data_row = array(
            'roll_no' => '="101"',
            'firstname' => 'Jahangir',
            'aadhaar_no' => '="123456789012"',
            'govt_school' => 'ABC Public School',
            'govt_school_id' => '="GS12345"',
            'dob' => '2008-05-10', // YYYY-MM-DD
            'gender' => 'Male',
            'father_name' => 'Mafuj Molla',
            'father_phone' => '="0987654321"',
            'mobileno' => '="9123456780"',
            'father_occupation' => 'Engineer',
            'mother_name' => 'Rokeya Molla',
            'guardian_occupation' => 'Engineer',
            'guardian_address' => '123 Main St',
        );

        $output = fopen('php://temp', 'w'); // Open a temporary file in memory
        fputcsv($output, array_values($fields)); // Write the header row
        fputcsv($output, array_values($sample_data_row)); // Write the sample data row
        rewind($output); // Rewind the file pointer

        $csv_data = stream_get_contents($output); // Get the contents of the temporary file
        fclose($output); // Close the temporary file

        $name = 'import_student_sample_file.csv';
        force_download($name, $csv_data);
    }

    public function bulk_upload()
    {
        // log_message('debug', 'Entering bulk_upload method in Student controller.');
        if (!$this->rbac->hasPrivilege('import_student', 'can_view')) {
            access_denied();
        }
        $data['title']      = $this->lang->line('bulk_upload_student');
        $data['title_list'] = $this->lang->line('recently_added_student');
        $session            = $this->setting_model->getCurrentSession();
        $class              = $this->class_model->get('', $classteacher = 'yes');
        $data['classlist']  = $class;
        $this->load->model('session_model'); // Load session model
        $data['sessionList'] = $this->session_model->getAllSession(); // Fetch all sessions
        $this->load->model('account_department_model'); // Load account department model
        $data['account_departments'] = $this->account_department_model->get(); // Fetch account departments
        $userdata           = $this->customlib->getUserData();

        $category = $this->category_model->get();

        $fields = array(
            'roll_no',
            'firstname',
            'aadhaar_no',
            'govt_school',
            'govt_school_id',
            'dob',
            'gender',
            'father_name',
            'father_phone',
            'mobileno',
            'father_occupation',
            'mother_name',
            'guardian_occupation',
            'guardian_address',
        );

        $data["fields"]       = $fields;
        $data['categorylist'] = $category;
        $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('section_id', $this->lang->line('section'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('session_id', $this->lang->line('session'), 'trim|required|xss_clean'); // Add validation for session_id
        $this->form_validation->set_rules('account_department_id', $this->lang->line('account_department'), 'trim|required|xss_clean'); // Add validation for account_department_id
        $this->form_validation->set_rules('file', $this->lang->line('file'), 'callback_handle_csv_upload'); // Changed 'image' to 'file' for correctness
        if ($this->form_validation->run() == false) {
            $this->load->view('layout/header', $data);
            $this->load->view('student/bulk_upload', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $student_categorize = 'class';
            if ($student_categorize == 'class') {
                $section = 0;
            } else if ($student_categorize == 'section') {
                $section = $this->input->post('section_id');
            }
            $class_id   = $this->input->post('class_id');
            $section_id = $this->input->post('section_id');
            $selected_session_id = $this->input->post('session_id'); // Get selected session ID
            $account_department_id = $this->input->post('account_department_id'); // Get selected account department ID

            $csv_to_db_map = array(
                'Roll NO' => 'roll_no',
                'First Name' => 'firstname',
                'Adhar Number' => 'aadhaar_no',
                'Govt School' => 'govt_school',
                'Banglar Shiksha ID' => 'govt_school_id',
                'Date Of Birth' => 'dob',
                'Gender' => 'gender',
                'Father Name' => 'father_name',
                'Father Phone' => 'father_phone',
                'Mobile NO' => 'mobileno',
                'Father Occupation' => 'father_occupation',
                'Mother Name' => 'mother_name',
                'Guardian Occupation' => 'guardian_occupation',
                'Guardian Address' => 'guardian_address',
            );

            $csv_header_aliases = array(
                'roll_no' => array('roll no', 'roll_no', 'rollno', 'rn'),
                'firstname' => array('first name', 'firstname', 'first_name', 'student name', 'name'),
                'aadhaar_no' => array('adhar number', 'aadhaar number', 'aadhaar no', 'aadhaar_no', 'aadhaar', 'aadhar no', 'aadhar number'),
                'govt_school' => array('govt school', 'government school', 'govt_school'),
                'govt_school_id' => array('banglar shiksha id', 'govt school id', 'govt_school_id', 'bs id'),
                'dob' => array('date of birth', 'dob', 'birth date', 'birth_date'),
                'gender' => array('gender', 'sex'),
                'father_name' => array('father name', 'father_name'),
                'father_phone' => array('father phone', 'father_phone', 'father mobile', 'father mobile no', 'father contact'),
                'mobileno' => array('mobile no', 'mobile', 'mobileno', 'mobile_no', 'phone', 'contact no'),
                'father_occupation' => array('father occupation', 'father_occupation'),
                'mother_name' => array('mother name', 'mother_name'),
                'guardian_occupation' => array('guardian occupation', 'guardian_occupation'),
                'guardian_address' => array('guardian address', 'guardian_address', 'address'),
            );

            $normalize_header = function ($header) {
                $header = strtolower(trim((string)$header));
                $header = preg_replace('/\s+/', ' ', $header);
                return str_replace(array('-', '.'), ' ', $header);
            };

            $session = $this->setting_model->getCurrentSession();
            if (isset($_FILES["file"]) && !empty($_FILES['file']['name'])) {
                $ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
                if ($ext == 'csv') {
                    $file = $_FILES['file']['tmp_name'];
                    $this->load->library('CSVReader');
                    $result = $this->csvreader->parse_file($file);

                    // Convert Excel-style scientific notation into plain digit strings for ID/phone fields.
                    $normalize_numeric_csv_value = function ($value) {
                        $value = trim((string)$value);
                        if ($value === '') {
                            return $value;
                        }

                        $value = str_replace(',', '', $value);

                        // Convert values like 2.18053E+11 => 218053000000 without float precision issues.
                        if (preg_match('/^([+-]?)(\d+(?:\.\d+)?)[eE]([+-]?\d+)$/', $value, $matches)) {
                            $sign = $matches[1];
                            $mantissa = $matches[2];
                            $exponent = (int)$matches[3];

                            $parts = explode('.', $mantissa, 2);
                            $int_part = $parts[0];
                            $frac_part = isset($parts[1]) ? $parts[1] : '';
                            $digits = $int_part . $frac_part;
                            $frac_len = strlen($frac_part);

                            if ($exponent >= 0) {
                                if ($exponent >= $frac_len) {
                                    $plain = $digits . str_repeat('0', $exponent - $frac_len);
                                } else {
                                    $plain = substr($digits, 0, strlen($digits) - ($frac_len - $exponent));
                                }
                            } else {
                                // For our use-case these fields are integer identifiers, so treat negative exponent as non-integer.
                                $plain = $value;
                            }

                            if ($sign === '-' && $plain !== '' && $plain !== '0') {
                                $plain = '-' . $plain;
                            }

                            return $plain;
                        }

                        // Clean integer-like decimals: 332538000000.0 => 332538000000
                        if (preg_match('/^\d+\.0+$/', $value)) {
                            return preg_replace('/\.0+$/', '', $value);
                        }

                        return $value;
                    };

                    if (!empty($result)) {
                        $rowcount = 0;
                        $skipped_duplicate_count = 0;
                        $seen_csv_duplicates = array();
                        foreach ($result as $row_num => $row) { // Iterate through rows, starting from the second row (index 1) for actual data
                            if ($row_num == 0) continue; // Skip header row if it exists

                            $student_data_row = array();

                            $normalized_row = array();
                            foreach ($row as $header_key => $header_value) {
                                $normalized_row[$normalize_header($header_key)] = $header_value;
                            }

                            foreach ($csv_to_db_map as $csv_header => $db_field) {
                                $csv_value = isset($row[$csv_header]) ? $row[$csv_header] : '';
                                if ($csv_value === '' && isset($csv_header_aliases[$db_field])) {
                                    foreach ($csv_header_aliases[$db_field] as $header_alias) {
                                        $normalized_alias = $normalize_header($header_alias);
                                        if (isset($normalized_row[$normalized_alias])) {
                                            $csv_value = $normalized_row[$normalized_alias];
                                            break;
                                        }
                                    }
                                }
                                // Strip Excel string formatting if present: ="value" -> value
                                if (preg_match('/^=".*"$/', $csv_value)) {
                                    $csv_value = substr($csv_value, 2, -1);
                                }
                                if (in_array($db_field, array('father_phone', 'mobileno', 'aadhaar_no', 'govt_school_id'))) {
                                    $csv_value = $normalize_numeric_csv_value($csv_value);
                                }
                                $student_data_row[$db_field] = $this->encoding_lib->toUTF8($csv_value);
                            }

                            // Handle specific date fields
                            if (!empty($student_data_row['dob'])) {
                                $student_data_row['dob'] = date('Y-m-d', strtotime(str_replace('/', '-', $student_data_row['dob'])));
                            } else {
                                $student_data_row['dob'] = null;
                            }

                            if (!empty($student_data_row['measurement_date'])) {
                                $student_data_row['measurement_date'] = date('Y-m-d', strtotime(str_replace('/', '-', $student_data_row['measurement_date'])));
                            } else {
                                $student_data_row['measurement_date'] = null;
                            }
                            // admission_date is auto-generated below, so no need to map from CSV here

                            // Skip duplicate rows inside the same CSV first.
                            $csv_firstname = strtolower(trim((string)$student_data_row['firstname']));
                            $csv_father_name = strtolower(trim((string)$student_data_row['father_name']));
                            $csv_dob = trim((string)$student_data_row['dob']);
                            $duplicate_key = $class_id . '|' . $selected_session_id . '|' . $section_id . '|' . $account_department_id . '|' . $csv_firstname . '|' . $csv_dob . '|' . $csv_father_name;
                            if ($csv_firstname === '' || $csv_father_name === '' || $csv_dob === '') {
                                $duplicate_key .= '|row:' . $row_num;
                            }

                            if (isset($seen_csv_duplicates[$duplicate_key])) {
                                $skipped_duplicate_count++;
                                continue;
                            }
                            $seen_csv_duplicates[$duplicate_key] = true;

                            // Skip rows already existing in database.
                            $is_duplicate_in_db = false;
                            if (
                                $csv_firstname !== '' &&
                                $csv_father_name !== '' &&
                                $csv_dob !== ''
                            ) {
                                $name_dob_father_exists = $this->db
                                    ->select('ss.id')
                                    ->from('students s')
                                    ->join('student_session ss', 'ss.student_id = s.id')
                                    ->where('ss.class_id', $class_id)
                                    ->where('ss.section_id', $section_id)
                                    ->where('ss.session_id', $selected_session_id)
                                    ->where('ss.account_department_id', $account_department_id)
                                    ->where('LOWER(TRIM(s.firstname)) = ' . $this->db->escape($csv_firstname), null, false)
                                    ->where('s.dob', $csv_dob)
                                    ->where('LOWER(TRIM(s.father_name)) = ' . $this->db->escape($csv_father_name), null, false)
                                    ->limit(1)
                                    ->get()
                                    ->num_rows();
                                if ($name_dob_father_exists > 0) {
                                    $is_duplicate_in_db = true;
                                }
                            }

                            if ($is_duplicate_in_db) {
                                $skipped_duplicate_count++;
                                continue;
                            }


                            // Auto-generate admission_no and admission_date
                            $generatedAdmissionNumber = $this->student_model->generateAdmissionNumber($class_id, $selected_session_id); // Use selected_session_id
                            $student_data_row['admission_no']   = $generatedAdmissionNumber;
                            $student_data_row['admission_date'] = date('Y-m-d'); // Current date for admission

                            $student_data_row['is_active'] = 'yes';
                            // Add other default fields if necessary

                            $data_setting = array();
                            $data_setting['id'] = $this->sch_setting_detail->id;
                            $data_setting['adm_auto_insert'] = $this->sch_setting_detail->adm_auto_insert;
                            $data_setting['adm_update_status'] = $this->sch_setting_detail->adm_update_status;

                            // Check if admission_no exists (though it's auto-generated, good to have a safeguard)
                            $admission_no_exists = $this->student_model->check_adm_exists($student_data_row['admission_no']);
                            if ($admission_no_exists) {
                                $insert_id = ""; // Do not insert if admission_no already exists
                            } else {
                                $insert_id = $this->student_model->add($student_data_row, $data_setting);
                            }

                            if (!empty($insert_id)) {
                                $data_new = array(
                                    'student_id' => $insert_id,
                                    'class_id'   => $class_id,
                                    'section_id' => $section_id,
                                    'session_id' => $selected_session_id, // Use selected_session_id
                                    'roll_no' => isset($student_data_row['roll_no']) ? $student_data_row['roll_no'] : null,
                                    'admission_no' => $student_data_row['admission_no'],
                                    'account_department_id' => $account_department_id // Add account_department_id
                                );

                                $student_session_id = $this->student_model->add_student_session($data_new);

                                // Define current user and date/time for logging fees
                                $current_user_id = $this->customlib->getStaffID(); // Assuming customlib has a method to get staff ID
                                $current_user_name = $this->customlib->getUserData()['username']; // Assuming customlib has a method to get staff username
                                $current_date_time = date('Y-m-d H:i:s');

                                // Get fees for the class and session
                                $new_fees = $this->student_model->get_fees_by_class_id_for_class_update($class_id, $selected_session_id);

                                // Insert fees into student_fees_management
                                if (!empty($new_fees)) {
                                    foreach ($new_fees as $fee) {
                                        $fee_data = array(
                                            'student_id' => $insert_id,
                                            'student_session_id' => $student_session_id,
                                            'class_id' => $class_id,
                                            'session_id' => $fee['session_id'],
                                            'feetype_id' => $fee['feetype_id'],
                                            'original_fees' => $fee['original_fees'],
                                            'tuition_fees' => $fee['tuition_fees'],
                                            'meal_charges' => $fee['meal_charges'],
                                            'discounted_fees' => $fee['discounted_fees'],
                                            'is_monthly' => $fee['is_monthly'],
                                            'added_by' => $current_user_id,
                                            'created_at' => $current_date_time,
                                        );
                                        $this->db->insert('student_fees_management', $fee_data);
                                    }
                                }

                                // Generate passwords for student and parent
                                $user_password = $this->role->get_random_password($chars_min = 6, $chars_max = 6, $use_upper_case = false, $include_numbers = true, $include_special_chars = false);
                                $parent_password = $this->role->get_random_password($chars_min = 6, $chars_max = 6, $use_upper_case = false, $include_numbers = true, $include_special_chars = false);

                                // Student login
                                $data_student_login = array(
                                    'username' => $this->student_login_prefix . $insert_id,
                                    'password' => $user_password,
                                    'user_id'  => $insert_id,
                                    'role'     => 'student',
                                    'lang_id'  => $this->sch_setting_detail->lang_id,
                                );
                                $this->user_model->add($data_student_login);

                                // Parent login (assuming a default parent structure for bulk upload if not provided)
                                $data_parent_login = array(
                                    'username' => $this->parent_login_prefix . $insert_id, // Link to student ID for now
                                    'password' => $parent_password,
                                    'user_id'  => 0, // Will be updated after parent record is inserted
                                    'role'     => 'parent',
                                    'childs'   => $insert_id,
                                );
                                $ins_parent_id = $this->user_model->add($data_parent_login);

                                // Update student with parent_id
                                $update_student = array(
                                    'id'        => $insert_id,
                                    'parent_id' => $ins_parent_id,
                                );
                                $this->student_model->add($update_student); // This is effectively an update

                                $rowcount++;
                            } else {
                                // Handle case where student admission_no already exists or insertion failed
                            }
                        }

                        if ($rowcount > 0) {
                            $duplicate_note = '';
                            if ($skipped_duplicate_count > 0) {
                                $duplicate_note = ' | Skipped duplicate records: ' . $skipped_duplicate_count;
                            }
                            $this->session->set_flashdata('msg', '<div class="alert alert-success text-center">' . $this->lang->line('total') . ' ' . count($result) . ' ' . $this->lang->line('records_found_in_csv_file_total') . $rowcount . ' ' . $this->lang->line('records_imported_successfully') . $duplicate_note . '</div>');
                        } elseif ($skipped_duplicate_count > 0) {
                            $this->session->set_flashdata('msg', '<div class="alert alert-warning text-center">No new records imported. Skipped duplicate records: ' . $skipped_duplicate_count . '</div>');
                        } else {
                            $this->session->set_flashdata('msg', '<div class="alert alert-danger text-center">' . $this->lang->line('no_record_found') . '</div>');
                        }
                    } else {
                        $this->session->set_flashdata('msg', '<div class="alert alert-danger text-center">' . $this->lang->line('no_record_found') . '</div>');
                    }
                } else {
                    $this->session->set_flashdata('msg', '<div class="alert alert-danger text-center">' . $this->lang->line('please_upload_csv_file_only') . '</div>');
                }
            }

            redirect('student/bulk_upload');
        }
    }

    public function handle_csv_upload()
    {
        $error = "";
        if (isset($_FILES["file"]) && !empty($_FILES['file']['name'])) {
            $allowedExts = array('csv');
            $mimes       = array(
                'text/csv',
                'text/plain',
                'application/csv',
                'text/comma-separated-values',
                'application/excel',
                'application/vnd.ms-excel',
                'application/vnd.msexcel',
                'text/anytext',
                'application/octet-stream',
                'application/txt'
            );
            $temp      = explode(".", $_FILES["file"]["name"]);
            $extension = end($temp);
            if ($_FILES["file"]["error"] > 0) {
                $error .= "Error opening the file<br />";
            }
            if (!in_array($_FILES['file']['type'], $mimes)) {
                $error .= "Error opening the file<br />";
                $this->form_validation->set_message('handle_csv_upload', $this->lang->line('file_type_not_allowed'));
                return false;
            }
            if (!in_array($extension, $allowedExts)) {
                $error .= "Error opening the file<br />";
                $this->form_validation->set_message('handle_csv_upload', $this->lang->line('extension_not_allowed'));
                return false;
            }
            if ($error == "") {
                return true;
            }
        } else {
            $this->form_validation->set_message('handle_csv_upload', $this->lang->line('please_select_file'));
            return false;
        }
    }

    public function edit($id)
    {
        if (!$this->rbac->hasPrivilege('student', 'can_edit')) {
            access_denied();
        }
        $data['title']                 = $this->lang->line('edit_student');
        $data['id']                    = $id;
        $student                       = $this->student_model->get($id);
        $this->load->model('account_department_model');
        $data['account_departments'] = $this->account_department_model->get();

        $this->load->model('govtschool_model');
        $data['govtschoollist'] = $this->govtschool_model->get();

        /* echo "<pre>";
        print_r($student);
        die; */

        $genderList                    = $this->customlib->getGender();
        $data['student']               = $student;
        $data["month"]                 = $this->customlib->getMonthDropdown();
        //$data['feesessiongroup_model'] = $this->feesessiongroup_model->getFeesByGroupByStudent($student['student_session_id']);

        $data['adm_auto_insert'] = $this->sch_setting_detail->adm_auto_insert;
        $data['genderList']      = $genderList;
        $session                 = $this->setting_model->getCurrentSession();
        //$data['transport_fees']  = $this->studenttransportfee_model->getTransportFeeByStudentSession($student['student_session_id'], $student['route_pickup_point_id']);
        //$vehroute_result         = $this->vehroute_model->getRouteVehiclesList();
        //$data['vehroutelist']    = $vehroute_result;
        $class                   = $this->class_model->get();
        $setting_result          = $this->setting_model->get();

        $data['sessionList'] = $this->session_model->getAllSession();

        $data["student_categorize"] = 'class';
        $data['classlist']          = $class;
        $category                   = $this->category_model->get();
        $data['categorylist']       = $category;
        $hostelList                 = $this->hostel_model->get();
        $data['hostelList']         = $hostelList;
        $houses                     = $this->student_model->gethouselist();
        $data['houses']             = $houses;
        $data["bloodgroup"]         = $this->blood_group;
        $siblings                   = $this->student_model->getMySiblings($student['parent_id'], $student['id']);
        $data['siblings']           = $siblings;
        $data['siblings_counts']    = count($siblings);
        $custom_fields              = $this->customfield_model->getByBelong('students');
        $data['sch_setting']        = $this->sch_setting_detail;

        foreach ($custom_fields as $custom_fields_key => $custom_fields_value) {
            if ($custom_fields_value['validation']) {
                $custom_fields_id   = $custom_fields_value['id'];
                $custom_fields_name = $custom_fields_value['name'];
                $this->form_validation->set_rules("custom_fields[students][" . $custom_fields_id . "]", $custom_fields_name, 'trim|required');
            }
        }

        $this->form_validation->set_rules('firstname', $this->lang->line('first_name'), "trim|required|xss_clean|regex_match[/^[A-Za-z .'-]+$/]");
        $this->form_validation->set_rules('middlename', $this->lang->line('middle_name'), "trim|xss_clean|regex_match[/^[A-Za-z .'-]+$/]");
        /* $this->form_validation->set_rules('dob', $this->lang->line('date_of_birth'), 'trim|required|xss_clean'); */
        $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('section_id', $this->lang->line('section'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('gender', $this->lang->line('gender'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('religion', $this->lang->line('religion'), 'trim|required|xss_clean');

        if ($this->sch_setting_detail->roll_no) {
            $this->form_validation->set_rules(
                'roll_no',
                $this->lang->line('roll_number'),
                array(
                    'trim',
                    'required',
                    'xss_clean',
                    array('check_roll_no_exists', array($this->student_model, 'check_roll_no_exists')),
                )
            );
        }

        if ($this->sch_setting_detail->guardian_name) {
            $this->form_validation->set_rules('guardian_name', $this->lang->line('guardian_name'), "trim|required|xss_clean|regex_match[/^[A-Za-z .'-]+$/]");
            $this->form_validation->set_rules('guardian_is', $this->lang->line('guardian'), 'trim|required|xss_clean');
        }

        if ($this->sch_setting_detail->guardian_phone) {
            $this->form_validation->set_rules('guardian_phone', $this->lang->line('guardian_phone'), 'trim|required|xss_clean|regex_match[/^[0-9]{10}$/]');
        }

        $this->form_validation->set_rules('guardian_relation', $this->lang->line('guardian_relation'), "trim|xss_clean|regex_match[/^[A-Za-z .'-]+$/]");
        $this->form_validation->set_rules('guardian_occupation', $this->lang->line('guardian_occupation'), "trim|xss_clean|regex_match[/^[A-Za-z .'-]+$/]");
        $this->form_validation->set_rules('father_name', $this->lang->line('father_name'), "trim|xss_clean|regex_match[/^[A-Za-z .'-]+$/]");
        $this->form_validation->set_rules('father_phone', $this->lang->line('father_phone'), 'trim|xss_clean|regex_match[/^[0-9]{10}$/]');
        $this->form_validation->set_rules('father_occupation', $this->lang->line('father_occupation'), "trim|xss_clean|regex_match[/^[A-Za-z .'-]+$/]");
        $this->form_validation->set_rules('mother_name', $this->lang->line('mother_name'), "trim|xss_clean|regex_match[/^[A-Za-z .'-]+$/]");
        $this->form_validation->set_rules('mother_phone', $this->lang->line('mother_phone'), 'trim|xss_clean|regex_match[/^[0-9]{10}$/]');
        $this->form_validation->set_rules('mother_occupation', $this->lang->line('mother_occupation'), "trim|xss_clean|regex_match[/^[A-Za-z .'-]+$/]");
        $this->form_validation->set_rules('aadhaar_no', 'Aadhaar No', 'trim|xss_clean|regex_match[/^[0-9]{12}$/]');
        $this->form_validation->set_rules('govt_school_id', 'Govt. School ID', 'trim|xss_clean|regex_match[/^[A-Za-z0-9]{1,20}$/]');
        $this->form_validation->set_rules('bank_account_no', $this->lang->line('bank_account_number'), 'trim|xss_clean|regex_match[/^[0-9]{9,18}$/]');
        $this->form_validation->set_rules('ifsc_code', $this->lang->line('ifsc_code'), 'trim|xss_clean|regex_match[/^[A-Za-z]{4}0[A-Za-z0-9]{6}$/]');

        $this->form_validation->set_rules(
            'email',
            $this->lang->line('email'),
            array(
                'valid_email',
                array('check_student_email_exists', array($this->student_model, 'check_student_email_exists')),
            )
        );

        $this->form_validation->set_rules(
            'mobileno',
            $this->lang->line('mobile_no'),
            array(
                'xss_clean',
                'regex_match[/^[0-9]{10}$/]',
                // array('check_student_mobile_exists', array($this->student_model, 'check_student_mobile_no_exists')),
            )
        );

        if (!$this->sch_setting_detail->adm_auto_insert) {

            $this->form_validation->set_rules('admission_no', $this->lang->line('admission_no'), array('required', array('check_admission_no_exists', array($this->student_model, 'valid_student_admission_no'))));
        }

        $this->form_validation->set_rules('file', $this->lang->line('image'), 'callback_handle_upload[file]');

        $this->form_validation->set_rules('father_pic', $this->lang->line('image'), 'callback_handle_father_upload[father_pic]');
        $this->form_validation->set_rules('mother_pic', $this->lang->line('image'), 'callback_handle_mother_upload[mother_pic]');
        $this->form_validation->set_rules('guardian_pic', $this->lang->line('image'), 'callback_handle_guardian_upload[guardian_pic]');

        $transport_feemaster_id = $this->input->post('transport_feemaster_id');

        if (!empty($transport_feemaster_id)) {
            $this->form_validation->set_rules('vehroute_id', $this->lang->line('route_list'), 'trim|required|xss_clean');
            $this->form_validation->set_rules('route_pickup_point_id', $this->lang->line('pickup_point'), 'trim|required|xss_clean');
            $this->form_validation->set_rules('transport_feemaster_id[]', $this->lang->line('fees_month'), 'trim|required|xss_clean');
        }

        if ($this->form_validation->run() == false) {
            $this->load->view('layout/header', $data);
            $this->load->view('student/studentEdit', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $student_data = $this->input->post();

            /* echo "<pre>";
            print_r($student_data);
            die; */
            $current_user_id = $this->session->userdata['admin']['id'];
            $current_user_name = $this->session->userdata['admin']['username'];
            $current_date_time = date('Y-m-d H:i:s');
            /** FEES UPDATE */
            if ($this->input->post('enable_fee_edit')) {

                $sfm_ids = $student_data['sfm_id'];
                $student_id = $student_data['student_id'];
                $student_session_id = $student_data['student_session_id'];
                $class_id = $student_data['class_id'];
                $session_ids = $student_data['session_id'];
                $feetype_ids = $student_data['feetype_id'];
                $original_fees = $student_data['original_fees'];
                $tuition_fees = $student_data['tuition_fees'];
                $meal_charges = $student_data['meal_charges'];
                $discounted_fees = $student_data['discounted_fees'];
                $is_monthly = $student_data['is_monthly'];
                $is_skipped = isset($student_data['is_skipped']) ? $student_data['is_skipped'] : array();

                $current_user_id = $this->session->userdata['admin']['id'];
                $current_user_name = $this->session->userdata['admin']['username'];
                $current_date_time = date('Y-m-d H:i:s');

                for ($i = 0; $i < count($feetype_ids); $i++) {
                    $sfm_id = $sfm_ids[$i];

                    $fee_data_to_save = array(
                        'student_id' => $student_id,
                        'student_session_id' => $student_session_id,
                        'class_id' => $class_id,
                        'session_id' => $session_ids[$i],
                        'feetype_id' => $feetype_ids[$i],
                        'original_fees' => $original_fees[$i],
                        'tuition_fees' => $tuition_fees[$i],
                        'meal_charges' => $meal_charges[$i],
                        'discounted_fees' => $discounted_fees[$i],
                        'is_monthly' => $is_monthly[$i],
                        'is_skipped' => isset($is_skipped[$i]) ? $is_skipped[$i] : 0,
                        'added_by' => $current_user_id,
                    );

                    // Logic for update_log (as it exists in the original code, but needs careful handling)
                    $update_log_entry = [
                        "added_by" => $current_user_id,
                        "date" => $current_date_time,
                        "amount" => $discounted_fees[$i]
                    ];

                    if ($sfm_id == 0) {
                        // This is a new fee, insert it
                        $fee_data_to_save['created_at'] = $current_date_time;
                        // For new entries, initialize update_log with the current entry
                        $fee_data_to_save['update_log'] = serialize([$update_log_entry]);
                        $this->db->insert('student_fees_management', $fee_data_to_save);
                        $inserted_sfm_id = $this->db->insert_id();

                        $sfm_log_data = array_merge(array('id' => $inserted_sfm_id), $fee_data_to_save);
                        log_student_fees_management($sfm_log_data, 1, $current_user_id, $current_user_name); // Action 1 for Adding

                    } else {
                        // This is an existing fee, update it
                        // Retrieve existing update_log for the given record
                        $this->db->select('update_log');
                        $this->db->where('id', $sfm_id);
                        $query = $this->db->get('student_fees_management');
                        $existing_update_log_row = $query->row();
                        $existing_update_log = [];
                        if ($existing_update_log_row && !empty($existing_update_log_row->update_log)) {
                            $existing_update_log = unserialize($existing_update_log_row->update_log);
                        }

                        $existing_update_log[] = $update_log_entry;
                        $fee_data_to_save['update_log'] = serialize($existing_update_log);

                        $this->db->where('id', $sfm_id);
                        $this->db->update('student_fees_management', $fee_data_to_save);

                        $sfm_log_data = array_merge(array('id' => $sfm_id), $fee_data_to_save);
                        log_student_fees_management($sfm_log_data, 2, $current_user_id, $current_user_name); // Action 2 for Updating
                    }
                }
            }
            /** END OF FEES UPDATE */

            $prev_fees_group   = array();
            $fee_session_group = array();

            $custom_field_post = $this->input->post("custom_fields[students]");
            if (isset($custom_field_post)) {
                $custom_value_array = array();
                foreach ($custom_field_post as $key => $value) {
                    $check_field_type = $this->input->post("custom_fields[students][" . $key . "]");
                    $field_value      = is_array($check_field_type) ? implode(",", $check_field_type) : $check_field_type;
                    $array_custom     = array(
                        'belong_table_id' => $id,
                        'custom_field_id' => $key,
                        'field_value'     => $field_value,
                    );
                    $custom_value_array[] = $array_custom;
                }
                $this->customfield_model->updateRecord($custom_value_array, $id, 'students');
            }
            $student_id = $this->input->post('student_id');
            $student    = $this->student_model->get($student_id);

            $sibling_id            = $this->input->post('sibling_id');
            $siblings_counts       = $this->input->post('siblings_counts');
            $siblings              = $this->student_model->getMySiblings($student['parent_id'], $student_id);
            $total_siblings        = count($siblings);
            $class_id              = $this->input->post('class_id');
            $section_id            = $this->input->post('section_id');
            $hostel_room_id        = $this->input->post('hostel_room_id');
            $fees_discount         = $this->input->post('fees_discount');
            $route_pickup_point_id = $this->input->post('route_pickup_point_id');

            if (empty($route_pickup_point_id)) {
                $route_pickup_point_id = null;
            }

            if (empty($hostel_room_id)) {
                $hostel_room_id = 0;
            }
            $vehroute_id = $this->input->post('vehroute_id');
            if (empty($vehroute_id)) {
                $vehroute_id = null;
            }
            $data = array(
                'id'                => $id,
                'firstname'         => $this->input->post('firstname'),
                'rte'               => $this->input->post('rte'),
                'state'             => $this->input->post('state'),
                'city'              => $this->input->post('city'),
                'pincode'           => $this->input->post('pincode'),
                'cast'              => $this->input->post('cast'),
                'previous_school'   => $this->input->post('previous_school'),
                'dob'               => $this->customlib->dateFormatToYYYYMMDD($this->input->post('dob')),
                'current_address'   => $this->input->post('current_address'),
                'permanent_address' => $this->input->post('permanent_address'),
                'adhar_no'          => $this->input->post('adhar_no'),
                'samagra_id'        => $this->input->post('samagra_id'),
                'bank_account_no'   => $this->input->post('bank_account_no'),
                'bank_name'         => $this->input->post('bank_name'),
                'ifsc_code'         => $this->input->post('ifsc_code'),
                'guardian_email'    => $this->input->post('guardian_email'),
                'gender'            => $this->input->post('gender'),
                'guardian_name'     => $this->input->post('guardian_name'),
                'guardian_relation' => $this->input->post('guardian_relation'),
                'guardian_phone'    => $this->input->post('guardian_phone'),
                'guardian_address'  => $this->input->post('guardian_address'),
                'hostel_room_id'    => $hostel_room_id,
                'note'              => $this->input->post('note'),
                'is_active'         => 'yes',
            );
            if ($this->sch_setting_detail->guardian_occupation) {
                $data['guardian_occupation'] = $this->input->post('guardian_occupation');
            }
            $house             = $this->input->post('house');
            $blood_group       = $this->input->post('blood_group');
            $measurement_date  = $this->input->post('measure_date');
            $roll_no           = $this->input->post('roll_no');
            $lastname          = $this->input->post('lastname');
            $middlename        = $this->input->post('middlename');
            $category_id       = $this->input->post('category_id');
            $religion          = $this->input->post('religion');
            $mobileno          = $this->input->post('mobileno');
            $email             = $this->input->post('email');
            $admission_date    = $this->input->post('admission_date');
            $height            = $this->input->post('height');
            $weight            = $this->input->post('weight');
            $father_name       = $this->input->post('father_name');
            $father_phone      = $this->input->post('father_phone');
            $father_occupation = $this->input->post('father_occupation');
            $mother_name       = $this->input->post('mother_name');
            $mother_phone      = $this->input->post('mother_phone');
            $mother_occupation = $this->input->post('mother_occupation');
            $aadhaar_no = $this->input->post('aadhaar_no');
            $govt_school = $this->input->post('govt_school');
            $govt_school_id = $this->input->post('govt_school_id');
            $recommendationNumber = $this->input->post('recommendationNumber');

            if (isset($aadhaar_no)) {
                $data['aadhaar_no'] = $aadhaar_no;
            }
            if (isset($govt_school)) {
                $data['govt_school'] = $govt_school;
            }
            if (isset($govt_school_id)) {
                $data['govt_school_id'] = $govt_school_id;
            }

            if ($this->sch_setting_detail->guardian_name) {
                $data['guardian_is'] = $this->input->post('guardian_is');
            }

            if (isset($measurement_date)) {
                $data['measurement_date'] = $this->customlib->dateFormatToYYYYMMDD($this->input->post('measure_date'));
            }

            if (isset($house)) {
                $data['school_house_id'] = $this->input->post('house');
            }

            if (isset($blood_group)) {
                $data['blood_group'] = $this->input->post('blood_group');
            }

            if (isset($lastname)) {
                $data['lastname'] = $this->input->post('lastname');
            }

            if (isset($middlename)) {
                $data['middlename'] = $this->input->post('middlename');
            }

            if (isset($category_id)) {
                $data['category_id'] = $this->input->post('category_id');
            }

            if (isset($religion)) {
                $data['religion'] = $this->input->post('religion');
            }

            if (isset($mobileno)) {
                $data['mobileno'] = $this->input->post('mobileno');
            }

            if (isset($email)) {
                $data['email'] = $this->input->post('email');
            }

            if (isset($admission_date)) {
                $data['admission_date'] = $this->customlib->dateFormatToYYYYMMDD($this->input->post('admission_date'));
            }

            if (isset($height)) {
                $data['height'] = $this->input->post('height');
            }

            if (isset($weight)) {
                $data['weight'] = $this->input->post('weight');
            }

            if (isset($father_name)) {
                $data['father_name'] = $this->input->post('father_name');
            }

            if (isset($father_phone)) {
                $data['father_phone'] = $this->input->post('father_phone');
            }

            if (isset($father_occupation)) {
                $data['father_occupation'] = $this->input->post('father_occupation');
            }

            if (isset($mother_name)) {
                $data['mother_name'] = $this->input->post('mother_name');
            }

            if (isset($mother_phone)) {
                $data['mother_phone'] = $this->input->post('mother_phone');
            }

            if (isset($mother_occupation)) {
                $data['mother_occupation'] = $this->input->post('mother_occupation');
            }

            if (isset($recommendationNumber)) {
                $data['recommendationNumber'] = $this->input->post('recommendationNumber');
            }

            if (empty($_FILES["file"])) {
                if ($this->input->post('gender') == 'Female') {
                    $data['image'] = 'uploads/student_images/default_female.jpg';
                } else {
                    $data['image'] = 'uploads/student_images/default_male.jpg';
                }
            }

            if (isset($_FILES["recommendationFile"]) && !empty($_FILES['recommendationFile']['name'])) {
                $img_name             = $this->media_storage->fileupload("recommendationFile", "./uploads/student_recommendations/");
                $data['recommendationFile'] = "uploads/student_recommendations/" . $img_name;
            } else {
                $data['recommendationFile'] = $student['recommendationFile'];
            }

            if (isset($_FILES["file"]) && !empty($_FILES['file']['name'])) {
                $img_name      = $this->media_storage->fileupload("file", "./uploads/student_images/");
                $data['image'] = "uploads/student_images/" . $img_name;
            }

            if (isset($_FILES["father_pic"]) && !empty($_FILES['father_pic']['name'])) {
                $img_name           = $this->media_storage->fileupload("father_pic", "./uploads/student_images/");
                $data['father_pic'] = "uploads/student_images/" . $img_name;
            }

            if (isset($_FILES["mother_pic"]) && !empty($_FILES['mother_pic']['name'])) {
                $img_name           = $this->media_storage->fileupload("mother_pic", "./uploads/student_images/");
                $data['mother_pic'] = "uploads/student_images/" . $img_name;
            }

            if (isset($_FILES["guardian_pic"]) && !empty($_FILES['guardian_pic']['name'])) {
                $img_name             = $this->media_storage->fileupload("guardian_pic", "./uploads/student_images/");
                $data['guardian_pic'] = "uploads/student_images/" . $img_name;
            }

            if ($this->input->post('prev_fees_group')) {
                $prev_fees_group = $this->input->post('prev_fees_group');
            }

            if ($this->input->post('fee_session_group_id')) {
                $fee_session_group = $this->input->post('fee_session_group_id');
            }

            $this->student_model->add($data);

            $log_data = array(
                'id' => $id,
                //'admission_no' => $data['admission_no'],
                'roll_no' => $roll_no,
                'admission_date' => $data['admission_date'],
                'firstname' => $data['firstname'],
                'lastname' => $data['lastname'],
                'mobileno' => $data['mobileno'],
                'dob' => $data['dob'],
                'current_address' => $data['current_address'],
                'aadhaar_no' => $data['aadhaar_no'],
                'govt_school' => $data['govt_school'],
                'govt_school_id' => $data['govt_school_id'],
                'adhar_no' => $data['adhar_no'],
                'father_name' => $data['father_name'],
                'father_phone' => $data['father_phone'],
                'guardian_name' => $data['guardian_name'],
                'recommendationNumber' => $data['recommendationNumber'],
                'recommendationFile' => $data['recommendationFile'],
                'created_by' => $current_user_id,
                'created_at' => $current_date_time
            );





            $action = 2; //update
            $user_id = $current_user_id;
            $user_name = $current_user_name;
            log_students($log_data, $action, $user_id, $user_name);

            $selected_session_id   = $this->input->post('selected_session_id');

            $data_new = array(
                'student_id'            => $id,
                'class_id'              => $class_id,
                'section_id'            => $section_id,
                'session_id'            => $selected_session_id,
                'fees_discount'         => $fees_discount,
                'route_pickup_point_id' => $route_pickup_point_id,
                'vehroute_id'           => $vehroute_id,
                'account_department_id' => $this->input->post('account_department_id'),
                'recommendationNumber' => $recommendationNumber
            );
            if (isset($roll_no)) {
                $data_new['roll_no'] = $roll_no;
            }

            $insert_id = $this->student_model->add_student_session($data_new);

            $delete_fee_session_group = array_diff($prev_fees_group, $fee_session_group);
            $add_fee_session_group    = array_diff($fee_session_group, $prev_fees_group);

            $student_session_id     = $this->input->post('student_session_id');
            $transport_feemaster_id = $this->input->post('transport_feemaster_id');
            $this->studentfeemaster_model->assign_bulk_fees($add_fee_session_group, $student_session_id, $delete_fee_session_group);

            if (!empty($transport_feemaster_id)) {
                $trns_data_insert = array();
                foreach ($transport_feemaster_id as $transport_feemaster_key => $transport_feemaster_value) {
                    $trns_data_insert[] = array(
                        'student_session_id'     => $student_session_id,
                        'route_pickup_point_id'  => $route_pickup_point_id,
                        'transport_feemaster_id' => $transport_feemaster_value,

                    );
                }

                $student_session_is = $this->studenttransportfee_model->update($trns_data_insert, $student_session_id);
            } else {
                $student_session_is = $this->studenttransportfee_model->update([], $student_session_id);
            }

            if (isset($siblings_counts) && ($total_siblings == $siblings_counts)) {
                //if there is no change in sibling
            } else if (!isset($siblings_counts) && $sibling_id == 0 && $total_siblings > 0) {
                // add for new parent
                $parent_password = $this->role->get_random_password($chars_min = 6, $chars_max = 6, $use_upper_case = false, $include_numbers = true, $include_special_chars = false);

                $data_parent_login = array(
                    'username' => $this->parent_login_prefix . $student_id . "_1",
                    'password' => $parent_password,
                    'user_id'  => "",
                    'role'     => 'parent',
                );

                $update_student = array(
                    'id'        => $student_id,
                    'parent_id' => 0,
                );
                $ins_id = $this->user_model->addNewParent($data_parent_login, $update_student);
            } else if ($sibling_id != 0) {
                //join to student with new parent
                $student_sibling = $this->student_model->get($sibling_id);
                $update_student  = array(
                    'id'        => $student_id,
                    'parent_id' => $student_sibling['parent_id'],
                );
                $student_sibling = $this->student_model->add($update_student);
            } else {
            }

            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('update_message') . '</div>');
            redirect('student/edit/' . $id);
        }
    }

    public function bulkdelete()
    {
        $this->session->set_userdata('top_menu', 'Student Information');
        $this->session->set_userdata('sub_menu', 'bulkdelete');
        $class                   = $this->class_model->get();
        $data['classlist']       = $class;
        $data['adm_auto_insert'] = $this->sch_setting_detail->adm_auto_insert;
        $data['sch_setting']     = $this->sch_setting_detail;
        $data['fields']          = $this->customfield_model->get_custom_fields('students', 1);
        if ($this->input->server('REQUEST_METHOD') == 'POST') {
            $class   = $this->input->post('class_id');
            $section = $this->input->post('section_id');
            $search  = $this->input->post('search');

            $data['searchby']    = "filter";
            $data['class_id']    = $this->input->post('class_id');
            $data['section_id']  = $this->input->post('section_id');
            $data['search_text'] = $this->input->post('search_text');
            $resultlist          = $this->student_model->searchByClassSection($class, $section);
            $data['resultlist']  = $resultlist;
        } else {
            $resultlist          = $this->student_model->searchByClassSection("", "");
            $data['resultlist']  = $resultlist;
        }
        $this->load->view('layout/header', $data);
        $this->load->view('student/bulkdelete', $data);
        $this->load->view('layout/footer', $data);
    }

    public function bulkreligion()
    {
        if (!$this->rbac->hasPrivilege('student', 'can_edit')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Student Information');
        $this->session->set_userdata('sub_menu', 'bulkreligion');
        $class               = $this->class_model->get();
        $data['classlist']   = $class;
        $data['sch_setting'] = $this->sch_setting_detail;
        $session_id          = $this->setting_model->getCurrentSession();

        if ($this->input->server('REQUEST_METHOD') == 'POST') {
            $class   = $this->input->post('class_id');
            $section = $this->input->post('section_id');

            $data['searchby']   = "filter";
            $data['class_id']   = $this->input->post('class_id');
            $data['section_id'] = $this->input->post('section_id');
            $resultlist          = $this->student_model->searchByClassSection($class, $section, $session_id);
            $data['resultlist']  = $resultlist;
        } else {
            $resultlist          = $this->student_model->searchByClassSection("", "", $session_id);
            $data['resultlist']  = $resultlist;
        }
        $this->load->view('layout/header', $data);
        $this->load->view('student/bulkreligion', $data);
        $this->load->view('layout/footer', $data);
    }

    public function ajax_update_religion()
    {
        if (!$this->rbac->hasPrivilege('student', 'can_edit')) {
            $array = array('status' => 0, 'error' => array(), 'message' => 'Access denied.');
            echo json_encode($array);
            return;
        }

        $this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules('student[]', $this->lang->line('student'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('religion', $this->lang->line('religion'), 'trim|required|xss_clean');

        if ($this->form_validation->run() == false) {

            $msg = array(
                'student[]' => form_error('student[]'),
                'religion'  => form_error('religion'),
            );
            $array = array('status' => 0, 'error' => $msg, 'message' => '');
        } else {
            $students = $this->input->post('student');
            $religion = $this->input->post('religion');

            $this->student_model->bulkUpdateReligion($students, $religion);

            $array = array('status' => 1, 'error' => '', 'message' => $this->lang->line('update_message'));
        }
        echo json_encode($array);
    }

    public function search()
    {
        if (!$this->rbac->hasPrivilege('student', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Student Information');
        $this->session->set_userdata('sub_menu', 'student/search');
        $data['title']           = 'Student Search';
        $data['adm_auto_insert'] = $this->sch_setting_detail->adm_auto_insert;
        $data['sch_setting']     = $this->sch_setting_detail;
        $data['fields']          = $this->customfield_model->get_custom_fields('students', 1);
        $class                   = $this->class_model->get();
        $data['classlist']       = $class;
        $genderList                    = $this->customlib->getGender();
        $data['genderList']            = $genderList;

        $data['currentSession'] = $this->setting_model->getCurrentSession();

        $data['sessionlist'] = $this->session_model->get();
        $data['can_hard_delete_students'] = $this->canHardDeleteStudents();

        /* print_r($data['currentSession']);
        die; */

        $this->load->view('layout/header', $data);
        $this->load->view('student/studentSearch', $data);
        $this->load->view('layout/footer', $data);
    }

    public function ajaxsearch()
    {
        $search_type = $this->input->post('search_type');
        if ($search_type == "search_filter") {
            $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required|xss_clean');
        }

        if ($this->form_validation->run() == false && $search_type == "search_filter") {
            $error = array();
            if ($search_type == "search_filter") {
                $error['class_id'] = form_error('class_id');
            }

            $array = array('status' => 0, 'error' => $error);
            echo json_encode($array);
        } else {
            $search_type = $this->input->post('search_type');
            $search_text = $this->input->post('search_text');
            $class_id    = $this->input->post('class_id');
            $section_id  = $this->input->post('section_id');
            $params      = array('class_id' => $class_id, 'section_id' => $section_id, 'search_type' => $search_type, 'search_text' => $search_text);
            $array       = array('status' => 1, 'error' => '', 'params' => $params);
            echo json_encode($array);
        }
    }





    public function getByClassAndSection()
    {
        $class      = $this->input->get('class_id');
        $section    = $this->input->get('section_id');
        $resultlist = $this->student_model->searchByClassSection($class, $section);
        foreach ($resultlist as $key => $value) {
            $resultlist[$key]['full_name'] = $this->customlib->getFullName($value['firstname'], $value['middlename'], $value['lastname'], $this->sch_setting_detail->middlename, $this->sch_setting_detail->lastname);
            # code...
        }
        echo json_encode($resultlist);
    }

    public function getByClassAndSectionExcludeMe()
    {
        $class      = $this->input->get('class_id');
        $section    = $this->input->get('section_id');
        $student_id = $this->input->get('current_student_id');
        $resultlist = $this->student_model->searchByClassSectionWithoutCurrent($class, $section, $student_id);

        foreach ($resultlist as $key => $value) {
            $resultlist[$key]['full_name'] = $this->customlib->getFullName($value['firstname'], $value['middlename'], $value['lastname'], $this->sch_setting_detail->middlename, $this->sch_setting_detail->lastname);
            # code...
        }

        echo json_encode($resultlist);
    }

    public function getStudentRecordByID()
    {
        $student_id = $this->input->get('student_id');
        $resultlist = $this->student_model->get($student_id);

        foreach ($resultlist as $key => $value) {

            $resultlist['full_name'] = $this->customlib->getFullName($resultlist['firstname'], $resultlist['middlename'], $resultlist['lastname'], $this->sch_setting_detail->middlename, $this->sch_setting_detail->lastname);
        }

        echo json_encode($resultlist);
    }

    public function uploadimage($id)
    {
        $data['title'] = 'Add Image';
        $data['id']    = $id;
        $this->load->view('layout/header', $data);
        $this->load->view('student/uploadimage', $data);
        $this->load->view('layout/footer', $data);
    }

    public function doupload($id)
    {
        $config = array(
            'upload_path'   => "./uploads/student_images/",
            'allowed_types' => "gif|jpg|png|jpeg|df",
            'overwrite'     => true,
        );
        $config['file_name'] = $id . ".jpg";
        $this->upload->initialize($config);
        $this->load->library('upload', $config);
        if ($this->upload->do_upload()) {
            $data        = array('upload_data' => $this->upload->data());
            $upload_data = $this->upload->data();
            $data_record = array('id' => $id, 'image' => $upload_data['file_name']);
            $this->setting_model->add($data_record);
            $this->load->view('upload_success', $data);
        } else {
            $error = array('error' => $this->upload->display_errors());

            $this->load->view('file_view', $error);
        }
    }

    public function getlogindetail()
    {
        if (!$this->rbac->hasPrivilege('student_login_credential_report', 'can_view')) {
            access_denied();
        }
        $student_id   = $this->input->post('student_id');
        $examSchedule = $this->user_model->getStudentLoginDetails($student_id);

        foreach ($examSchedule as $key => $value) {
            $examSchedule[$key]->role = $this->lang->line(strtolower($value->role));
        }
        echo json_encode($examSchedule);
    }

    public function getUserLoginDetails()
    {
        $studentid = $this->input->post("student_id");
        $result    = $this->user_model->getUserLoginDetails($studentid);
        echo json_encode($result);
    }

    public function disablestudentslist()
    {
        if (!$this->rbac->hasPrivilege('disable_student', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Student Information');
        $this->session->set_userdata('sub_menu', 'student/disablestudentslist');
        $class                   = $this->class_model->get();
        $data['classlist']       = $class;
        $data["resultlist"]      = array();
        $data['adm_auto_insert'] = $this->sch_setting_detail->adm_auto_insert;
        $data['sch_setting']     = $this->sch_setting_detail;
        $userdata                = $this->customlib->getUserData();
        $carray                  = array();
        $reason_list             = array();
        if (!empty($data["classlist"])) {
            foreach ($data["classlist"] as $ckey => $cvalue) {

                $carray[] = $cvalue["id"];
            }
        }

        $button = $this->input->post('search');
        if ($this->input->server('REQUEST_METHOD') == "GET") {
        } else {
            $class       = $this->input->post('class_id');
            $section     = $this->input->post('section_id');
            $search      = $this->input->post('search');
            $search_text = $this->input->post('search_text');
            if (isset($search)) {
                if ($search == 'search_filter') {
                    $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required|xss_clean');
                    if ($this->form_validation->run() == false) {
                    } else {
                        $data['searchby']   = "filter";
                        $data['class_id']   = $this->input->post('class_id');
                        $data['section_id'] = $this->input->post('section_id');

                        $data['search_text'] = $this->input->post('search_text');
                        $resultlist          = $this->student_model->disablestudentByClassSection($class, $section);
                        $data['resultlist']  = $resultlist;
                    }
                } else if ($search == 'search_full') {
                    $data['searchby'] = "text";

                    $data['search_text'] = trim($this->input->post('search_text'));
                    $resultlist          = $this->student_model->disablestudentFullText($search_text);
                    $data['resultlist']  = $resultlist;
                }
            }
        }

        $disable_reason = $this->disable_reason_model->get();

        foreach ($disable_reason as $key => $value) {
            $id               = $value['id'];
            $reason_list[$id] = $value;
        }

        $data['disable_reason'] = $reason_list;

        $this->load->view("layout/header", $data);
        $this->load->view("student/disablestudents", $data);
        $this->load->view("layout/footer", $data);
    }

    public function disablestudent($id)
    {
        $data = array('is_active' => "no", 'disable_at' => date("Y-m-d"));
        $this->student_model->disableStudent($id, $data);
        redirect("student/view/" . $id);
    }

    public function enablestudent($id)
    {
        $data = array('is_active' => "yes");
        $this->student_model->disableStudent($id, $data);
        echo "0";
    }

    public function savemulticlass()
    {
        $student_id       = '';
        $message          = "";
        $duplicate_record = 0;
        $this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules('student_id', $this->lang->line('student_id'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('row_count[]', 'row_count[]', 'trim|required|xss_clean');

        if ($this->input->server('REQUEST_METHOD') == 'POST') {
            $total_rows = $this->input->post('row_count[]');
            if (!empty($total_rows)) {

                foreach ($total_rows as $key_rowcount => $row_count) {

                    $this->form_validation->set_rules('class_id_' . $row_count, $this->lang->line('class'), 'trim|required|xss_clean');

                    $this->form_validation->set_rules('section_id_' . $row_count, $this->lang->line('section'), 'trim|required|xss_clean');
                }
            }
        }

        if ($this->form_validation->run() == false) {

            $msg = array(
                'student_id'  => form_error('student_id'),
                'row_count[]' => form_error('row_count[]'),
            );

            if ($this->input->server('REQUEST_METHOD') == 'POST') {
                if (!empty($total_rows)) {

                    $total_rows = $this->input->post('row_count[]');
                    foreach ($total_rows as $key_rowcount => $row_count) {

                        $msg['class_id_' . $row_count]   = form_error('class_id_' . $row_count);
                        $msg['section_id_' . $row_count] = form_error('section_id_' . $row_count);
                    }
                }
            }
            if (!empty($msg)) {
                $message = $this->lang->line('something_went_wrong');
            }

            $array = array('status' => '0', 'error' => $msg, 'message' => $message);
        } else {

            $rowcount            = $this->input->post('row_count[]');
            $class_section_array = array();
            $duplicate_array     = array();
            foreach ($rowcount as $key_rowcount => $value_rowcount) {

                $array = array(
                    'class_id'   => $this->input->post('class_id_' . $value_rowcount),
                    'session_id' => $this->setting_model->getCurrentSession(),
                    'student_id' => $this->input->post('student_id'),
                    'section_id' => $this->input->post('section_id_' . $value_rowcount),
                );

                $class_section_array[] = $array;
                $duplicate_array[]     = $this->input->post('class_id_' . $value_rowcount) . "-" . $this->input->post('section_id_' . $value_rowcount);
            }

            foreach (array_count_values($duplicate_array) as $val => $c) {

                if ($c > 1) {
                    $duplicate_record = 1;
                    break;
                }
            }
            if ($duplicate_record) {

                $array = array('status' => 0, 'error' => '', 'message' => $this->lang->line('duplicate_entry'));
            } else {
                $this->studentsession_model->add($class_section_array, $this->input->post('student_id'));

                $array = array('status' => 1, 'error' => '', 'message' => $this->lang->line('success_message'));
            }
        }
        echo json_encode($array);
    }

    public function disable_reason()
    {
        $this->form_validation->set_rules('reason', $this->lang->line('reason'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('disable_date', $this->lang->line('date'), 'trim|required|xss_clean');

        if ($this->form_validation->run() == false) {

            $msg = array(
                'reason'       => form_error('reason'),
                'disable_date' => form_error('disable_date'),
            );

            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
        } else {

            $data = array(
                'dis_reason' => $this->input->post('reason'),
                'dis_note'   => $this->input->post('note'),
                'id'         => $this->input->post('student_id'),
                'disable_at' => $this->customlib->dateFormatToYYYYMMDD($this->input->post('disable_date')),
                'is_active'  => 'no',
            );

            $this->student_model->add($data);

            $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('success_message'));
        }
        echo json_encode($array);
    }

    public function ajax_delete()
    {
        $this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules('student[]', $this->lang->line('student'), 'trim|required|xss_clean');

        if ($this->form_validation->run() == false) {

            $msg = array(
                'student[]' => form_error('student[]'),
            );
            $array = array('status' => 0, 'error' => $msg, 'message' => '');
        } else {
            $students = $this->input->post('student');

            foreach ($students as $student_key => $student_value) {
            }

            $this->student_model->bulkdelete($students);

            $array = array('status' => 1, 'error' => '', 'message' => $this->lang->line('delete_message'));
        }
        echo json_encode($array);
    }

    public function profilesetting()
    {
        if (!$this->rbac->hasPrivilege('student_profile_update', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'System Settings');
        $this->session->set_userdata('sub_menu', 'System Settings/profilesetting');
        $data                    = array();
        $data['result']          = $this->setting_model->getSetting();
        $data['fields']          = get_student_editable_fields();
        $data['inserted_fields'] = $this->student_edit_field_model->get();
        $data['result']          = $this->setting_model->getSetting();
        $this->form_validation->set_rules('student_profile_edit', $this->lang->line('student_profile_update'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == true) {
            $data_record = array(
                'id'                   => $this->input->post('sch_id'),
                'student_profile_edit' => $this->input->post('student_profile_edit'),
            );
            $this->setting_model->add($data_record);
            $this->session->set_flashdata('msg', '<div class="alert alert-left">' . $this->lang->line('update_message') . '</div>');
            redirect('student/profilesetting');
        }
        $data['sch_setting_detail'] = $this->sch_setting_detail;
        $data['custom_fields']      = $this->onlinestudent_model->getcustomfields();
        $this->load->view("layout/header");
        $this->load->view("student/profilesetting", $data);
        $this->load->view("layout/footer");
    }

    public function changeprofilesetting()
    {
        $this->form_validation->set_rules('name', $this->lang->line('student'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('status', $this->lang->line('status'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {

            $msg = array(
                'status' => form_error('status'),
                'name'   => form_error('name'),
            );

            $array = array('status' => '0', 'error' => $msg, 'msg' => $this->lang->line('something_went_wrong'));
        } else {
            $insert = array(
                'name'   => $this->input->post('name'),
                'status' => $this->input->post('status'),
            );
            $this->student_edit_field_model->add($insert);
            $array = array('status' => '1', 'error' => '', 'msg' => $this->lang->line('success_message'));
        }

        echo json_encode($array);
    }

    /**
     * This function is used to view bulk mail page
     */
    public function bulkmail()
    {
        if (!$this->rbac->hasPrivilege('login_credentials_send', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Communicate');
        $this->session->set_userdata('sub_menu', 'bulk_mail');
        $class                    = $this->class_model->get();
        $data['classlist']        = $class;
        $data['sch_setting']      = $this->sch_setting_detail;
        $data['bulkmailto']       = $this->customlib->bulkmailto();
        $data['notificationtype'] = $this->customlib->bulkmailnotificationtype();
        $data['fields']           = $this->customfield_model->get_custom_fields('students', 1);
        if ($this->input->server('REQUEST_METHOD') == "GET") {
            $this->load->view('layout/header', $data);
            $this->load->view('student/bulkmail', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $class   = $this->input->post('class_id');
            $section = $this->input->post('section_id');

            $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required|xss_clean');
            if ($this->form_validation->run() == false) {
            } else {
                $data['class_id']   = $this->input->post('class_id');
                $data['section_id'] = $this->input->post('section_id');
                $resultlist         = $this->student_model->searchByClassSection($class, $section);
                $data['resultlist'] = $resultlist;
            }

            $this->load->view('layout/header', $data);
            $this->load->view('student/bulkmail', $data);
            $this->load->view('layout/footer', $data);
        }
    }

    /**
     * This function is used to send bulk mail to student and Parent
     */
    public function sendbulkmail()
    {
        $this->form_validation->set_rules('student[]', $this->lang->line('student'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('message_to', $this->lang->line('message_to'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('notification_type', $this->lang->line('notification_type'), 'trim|required|xss_clean');

        if ($this->form_validation->run() == false) {
            $msg = array(
                'student[]'         => form_error('student[]'),
                'message_to'        => form_error('message_to'),
                'notification_type' => form_error('notification_type'),
            );
            $array = array('status' => 0, 'error' => $msg, 'message' => '');
        } else {
            $students          = $this->input->post('student');
            $message_to        = $this->input->post('message_to');
            $notification_type = $this->input->post('notification_type');

            foreach ($students as $students_value) {

                $student_detail = $this->user_model->student_information($students_value);

                if (($message_to == 1 && $notification_type == 1) || ($message_to == 1 && $notification_type == 3) || ($message_to == 3 && $notification_type == 3)) {

                    $sender_details = array('student_id' => $students_value, 'contact_no' => $student_detail[0]->mobileno, 'email' => $student_detail[0]->email, 'student_session_id' => $student_detail[0]->student_session_id);

                    $this->mailsmsconf->bulkmailsms('student_admission', $sender_details);
                }

                if (($message_to == 1 && $notification_type == 2) || ($message_to == 1 && $notification_type == 3) || ($message_to == 3 && $notification_type == 3) || ($message_to == 3 && $notification_type == 2)) {

                    $student_login_detail = array('display_name' => $student_detail[0]->firstname . ' ' . $student_detail[0]->lastname, 'id' => $students_value, 'credential_for' => 'student', 'username' => $student_detail[0]->username, 'password' => $student_detail[0]->password, 'contact_no' => $student_detail[0]->mobileno, 'email' => $student_detail[0]->email, 'student_session_id' => $student_detail[0]->student_session_id, 'admission_no' => $student_detail[0]->admission_no);

                    $this->mailsmsconf->bulkmailsms('student_login_credential', $student_login_detail);
                }

                if (($message_to == 2 && $notification_type == 1) || ($message_to == 2 && $notification_type == 3) || ($message_to == 3 && $notification_type == 3) || ($message_to == 3 && $notification_type == 1)) {

                    $sender_details = array('student_id' => $students_value, 'contact_no' => $student_detail[0]->guardian_phone, 'email' => $student_detail[0]->guardian_email, 'student_session_id' => $student_detail[0]->student_session_id);

                    $this->mailsmsconf->bulkmailsms('student_admission', $sender_details);
                }

                if (($message_to == 2 && $notification_type == 2) || ($message_to == 2 && $notification_type == 3) || ($message_to == 3 && $notification_type == 3) || ($message_to == 3 && $notification_type == 2)) {

                    $parent_detail = $this->user_model->read_single_child($student_detail[0]->parent_id);

                    $parent_login_detail = array('display_name' => $student_detail[0]->firstname . ' ' . $student_detail[0]->lastname, 'id' => $students_value, 'credential_for' => 'parent', 'username' => $parent_detail->username, 'password' => $parent_detail->password, 'contact_no' => $student_detail[0]->guardian_phone, 'email' => $student_detail[0]->guardian_email, 'student_session_id' => $student_detail[0]->student_session_id, 'admission_no' => $student_detail[0]->admission_no);

                    $this->mailsmsconf->bulkmailsms('student_login_credential', $parent_login_detail);
                }
            }

            $array = array('status' => 1, 'error' => '', 'message' => $this->lang->line('message_sent_successfully'));
        }
        echo json_encode($array);
    }

    public function dtstudentlist()
    {
        $currency_symbol = $this->customlib->getSchoolCurrencyFormat();
        $class           = $this->input->post('class_id');
        $section         = $this->input->post('section_id');
        $session_id      = !empty($this->input->post('session_id')) ? $this->input->post('session_id') : $this->setting_model->getCurrentSession();
        $gender          = $this->input->post('gender');
        $search_text     = $this->input->post('search_text');
        $search_type     = $this->input->post('srch_type');
        $classlist       = $this->class_model->get();
        $classlist       = $classlist;
        $carray          = array();
        if (!empty($classlist)) {
            foreach ($classlist as $ckey => $cvalue) {
                $carray[] = $cvalue["id"];
            }
        }


        $sch_setting = $this->sch_setting_detail;

        if ($search_type == "search_filter") {
            $resultlist = $this->student_model->searchdtByClassSection($class, $section, $session_id, $gender);
        } elseif ($search_type == "search_full") {
            $resultlist = $this->student_model->searchFullText($search_text, $session_id);
        }

        $students = array();
        $students = json_decode($resultlist);

        $dt_data = array();
        $fields  = $this->customfield_model->get_custom_fields('students', 1);
        $is_superadmin = $this->canHardDeleteStudents();
        /* echo "<pre>";
        print_r($students->data);
        die; */
        if (!empty($students->data)) {
            foreach ($students->data as $student_key => $student) {

                $editbtn    = '';
                $deletebtn  = '';
                $viewbtn    = '';
                $collectbtn = '';

                $online_icon = $student->is_online
                    ? "<i class='fa fa-wifi text-success' title='Online Register'></i>"
                    : "<i class='fa fa-plug text-danger' title='Offline Register'></i>";

                $student_name = $this->customlib->getFullName($student->firstname, $student->middlename, $student->lastname, $sch_setting->middlename, $sch_setting->lastname);

                $viewbtn = "<a href='" . base_url("student/view/" . $student->id) . "' class='btn btn-primary btn-xs btn-rounded mr-3' data-toggle='tooltip' title='" . $this->lang->line('view') . "'><i class='fa fa-eye'></i></a>";

                $editbtn = "";
                if ($this->rbac->hasPrivilege('student', 'can_edit')) {
                    $editbtn = "<a href='" . base_url("student/edit/" . $student->id) . "' class='btn btn-warning btn-xs btn-rounded mr-3' data-toggle='tooltip' title='" . $this->lang->line('edit') . "'><i class='fa fa-pencil'></i></a>";
                }

                $collectbtn = "";
                if ($this->module_lib->hasActive('fees_collection') && $this->rbac->hasPrivilege('collect_fees', 'can_add')) {
                    $collectbtn = "<a href='" . base_url("studentfee/addfees/" . $student->id . "?session_id=" . $student->session_id) . "' class='btn btn-success btn-xs btn-rounded' data-toggle='tooltip' title='" . $this->lang->line('add_fees') . "'><i class='fa fa-money'></i></a>";
                }

                if ($is_superadmin && (float) $student->collected_fees === 0.0) {
                    $deletebtn = "<button type='button' class='btn btn-danger btn-xs btn-rounded hard-delete-student' data-student-id='" . (int) $student->id . "' data-reg-no='" . (int) $student->id . "' data-student-name='" . html_escape($student_name) . "' title='Permanently delete student' data-toggle='tooltip'><i class='fa fa-trash'></i></button>";
                }


                $row   = array();
                $row[] = $student->id;

                $row[] = "<a href='" . base_url("student/view/" . $student->id) . "'>" . $online_icon . " " . $student_name . "</a>";

                $class_section = $student->class;
                if (!empty($student->section)) {
                    $class_section .= " (" . $student->section . ")";
                }
                $row[] = $class_section;
                $row[] = $student->roll_no;
                if ($sch_setting->father_name) {
                    $row[] = $student->father_name;
                }

                $row[] = !empty($student->govt_school) ? $student->govt_school : "";
                $row[] = $student->govt_school_id;

                $row[] = $this->customlib->dateformat($student->dob);

                /* if (!empty($student->gender)) {
                    $row[] = $this->lang->line(strtolower($student->gender));
                } else {
                    $row[] = '';
                } */

                /* if ($sch_setting->category) {
                    $row[] = $student->category;
                } */
                //if ($sch_setting->mobile_no) {
                $row[] = $student->mobileno;
                /* $row[] = $student->is_online ? "Yes" : "No"; */
                //}

                foreach ($fields as $fields_key => $fields_value) {

                    $custom_name   = $fields_value->name;
                    $display_field = $student->$custom_name;
                    if ($fields_value->type == "link") {
                        $display_field = "<a href=" . $student->$custom_name . " target='_blank'>" . $student->$custom_name . "</a>";
                    }
                    $row[] = $display_field;
                }

                $row[] = $viewbtn . '' . $editbtn . '' . $collectbtn . '' . $deletebtn;

                $dt_data[] = $row;
            }
        }
        $sch_setting         = $this->sch_setting_detail;
        $student_detail_view = $this->load->view('student/_searchDetailView', array('sch_setting' => $sch_setting, 'students' => $students), true);
        $json_data           = array(
            "draw"                => intval($students->draw),
            "recordsTotal"        => intval($students->recordsTotal),
            "recordsFiltered"     => intval($students->recordsFiltered),
            "data"                => $dt_data,
            "student_detail_view" => $student_detail_view,
        );

        echo json_encode($json_data);
    }

    //datatable function to check search parameter validation
    public function searchvalidation()
    {
        $class_id   = $this->input->post('class_id');
        $section_id = $this->input->post('section_id');
        $gender = $this->input->post('gender');
        $session_id = $this->input->post('session_id');
        $only_recommendation = $this->input->post('only_recommendation');

        $srch_type   = $this->input->post('search_type');
        $search_text = $this->input->post('search_text');

        if ($srch_type == 'search_filter') {

            /* $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required|xss_clean');
            if ($this->form_validation->run() == true) { */

            $params = array('srch_type' => $srch_type, 'class_id' => $class_id, 'section_id' => $section_id, 'session_id' => $session_id, 'gender' => $gender);
            $array  = array('status' => 1, 'error' => '', 'params' => $params);
            echo json_encode($array);
            /* } else {

                $error             = array();
                $error['class_id'] = form_error('class_id');
                $array             = array('status' => 0, 'error' => $error);
                echo json_encode($array);
            } */
        } else {
            $params = array('srch_type' => 'search_full', 'class_id' => $class_id, 'section_id' => $section_id, 'session_id' => $session_id, 'gender' => $gender, 'search_text' => $search_text);
            $array  = array('status' => 1, 'error' => '', 'params' => $params);
            echo json_encode($array);
        }
    }

    public function getStudentByClassSection()
    {
        $data                 = array();
        $cls_section_id       = $this->input->post('cls_section_id');
        $data['fields']       = $this->customfield_model->get_custom_fields('students', 1);
        $student_list         = $this->student_model->getStudentBy_class_section_id($cls_section_id);
        $data['student_list'] = $student_list;
        $data['sch_setting']  = $this->sch_setting_detail;
        $page                 = $this->load->view('reports/_getStudentByClassSection', $data, true);
        echo json_encode(array('status' => 1, 'page' => $page));
    }

    public function handle_upload($str, $var)
    {
        $image_validate = $this->config->item('image_validate');
        $result         = $this->filetype_model->get();
        if (isset($_FILES[$var]) && !empty($_FILES[$var]['name'])) {

            $file_type = $_FILES[$var]['type'];
            $file_size = $_FILES[$var]["size"];
            $file_name = $_FILES[$var]["name"];

            $allowed_extension = array_map('trim', array_map('strtolower', explode(',', $result->image_extension)));
            $allowed_mime_type = array_map('trim', array_map('strtolower', explode(',', $result->image_mime)));
            $ext               = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

            if ($files = @getimagesize($_FILES[$var]['tmp_name'])) {

                if (!in_array($files['mime'], $allowed_mime_type)) {
                    $this->form_validation->set_message('handle_upload', $this->lang->line('file_type_not_allowed'));
                    return false;
                }

                if (!in_array($ext, $allowed_extension) || !in_array($file_type, $allowed_mime_type)) {
                    $this->form_validation->set_message('handle_upload', $this->lang->line('extension_not_allowed'));
                    return false;
                }

                if ($file_size > $result->image_size) {
                    $this->form_validation->set_message('handle_upload', $this->lang->line('file_size_shoud_be_less_than') . number_format($result->image_size / 1048576, 2) . " MB");
                    return false;
                }
            } else {

                $this->form_validation->set_message('handle_upload', $this->lang->line('file_type_not_allowed_or_extension_not_allowed'));
                return false;
            }

            return true;
        }
        return true;
    }

    public function handle_uploadfordoc($str, $var)
    {
        $image_validate = $this->config->item('file_validate');
        $result         = $this->filetype_model->get();
        if (isset($_FILES[$var]) && !empty($_FILES[$var]['name'])) {

            $file_type = $_FILES[$var]['type'];
            $file_size = $_FILES[$var]["size"];
            $file_name = $_FILES[$var]["name"];

            $allowed_extension = array_map('trim', array_map('strtolower', explode(',', $result->file_extension)));
            $allowed_mime_type = array_map('trim', array_map('strtolower', explode(',', $result->file_mime)));
            $ext               = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mtype = finfo_file($finfo, $_FILES[$var]['tmp_name']);
            finfo_close($finfo);

            if (!in_array($mtype, $allowed_mime_type)) {
                $this->form_validation->set_message('handle_uploadfordoc', $this->lang->line('file_type_not_allowed'));
                return false;
            }

            if (!in_array($ext, $allowed_extension) || !in_array($file_type, $allowed_mime_type)) {
                $this->form_validation->set_message('handle_uploadfordoc', $this->lang->line('extension_not_allowed'));
                return false;
            }

            if ($file_size > $result->file_size) {
                $this->form_validation->set_message('handle_uploadfordoc', $this->lang->line('file_size_shoud_be_less_than') . number_format($result->file_size / 1048576, 2) . " MB");
                return false;
            }

            return true;
        }
    }

    public function countAttendance($session_year_start, $student_session_id)
    {
        $attendencetypes = $this->attendencetype_model->getAttType();

        $record = array();
        foreach ($attendencetypes as $type_key => $type_value) {
            $record[$type_value['id']] = 0;
        }

        for ($i = 1; $i <= 12; $i++) {
            $start_month        = date('Y-m-d', strtotime($session_year_start));
            $end_month          = date('Y-m-t', strtotime($session_year_start));
            $session_year_start = date('Y-m-d', strtotime('+1 month', strtotime($session_year_start)));

            $attendences = $this->stuattendence_model->student_attendence_bw_date($start_month, $end_month, $student_session_id);
            if (!empty($attendences)) {
                foreach ($attendences as $attendence_key => $attendence_value) {

                    $record[$attendence_value->attendence_type_id] += 1;
                }
            }
        }

        return $record;
    }

    public function startmonthandend()
    {
        $startmonth = $this->setting_model->getStartMonth();
        if ($startmonth == 1) {
            $endmonth = 12;
        } else {
            $endmonth = $startmonth - 1;
        }
        return array($startmonth, $endmonth);
    }

    public function getAdmissionNoByGuardianEmail()
    {
        $student_id =   $_POST['student_id'];
        $guardian_email =   $_POST['guardian_email'];

        $student_admission_no = $this->student_model->getAdmissionNoByGuardianEmail($student_id, $guardian_email);

        if (!empty($student_admission_no['guardian_email'])) {

            echo "This Guardian Email is already exists due to " . $student_admission_no['firstname'] . " " . $student_admission_no['middlename'] . " " . $student_admission_no['lastname'] . " (" . $student_admission_no['admission_no'] . ") and their siblings guardian email, if this student is also sibling then add as sibling";
        } else {
            echo "";
        }
    }

    public function getAdmissionNoByGuardianPhone()
    {
        $student_id =   $_POST['student_id'];
        $guardian_phone =   $_POST['guardian_phone'];

        $student_admission_no = $this->student_model->getAdmissionNoByGuardianPhone($student_id, $guardian_phone);

        if (!empty($student_admission_no['guardian_phone'])) {

            echo "This Guardian Phone is already exists due to " . $student_admission_no['firstname'] . " " . $student_admission_no['middlename'] . " " . $student_admission_no['lastname'] . " (" . $student_admission_no['admission_no'] . ") and their siblings guardian phone, if this student is also sibling then add as sibling";
        } else {
            echo "";
        }
    }

    public function admission_online()
    {
        $this->load->model('AdmissionModel');

        $data['classlist'] = $this->class_model->get();
        $data['sessionlist'] = $this->session_model->get();

        $class_id = $this->input->post('class_id');
        $session_id = $this->input->post('session_id');
        if ($session_id === null) {
            $session_id = $this->setting_model->getCurrentSession();
        }
        $status = $this->input->post('status');
        $from_date = $this->input->post('from_date');
        $to_date = $this->input->post('to_date');

        $data['admission_list'] = $this->AdmissionModel->getOnlineAdmissions($class_id, $session_id, $status, $from_date, $to_date); // Get the online admissions data
        $data['title']  = 'Student Online Admission List';

        $this->load->view('student/admission_online', $data); // Pass data to the view
    }

    public function onlineStudentsview($id)
    {
        $this->load->model('AdmissionModel');
        $data['student_details'] = $this->AdmissionModel->getOnlineAdmissionById($id);
        $data['title'] = 'Student Details';
        $this->load->view('layout/header', $data);
        $this->load->view('student/view', $data);
        $this->load->view('layout/footer', $data);
    }

    public function approveOnlineAdmission($id)
    {
        $this->load->model('AdmissionModel');
        $this->AdmissionModel->updateOnlineAdmissionStatus($id, 'approved');
        $this->session->set_flashdata('msg', '<div class="alert alert-success">Student admission approved successfully!</div>');
        redirect('student/admission_online');
    }

    public function rejectOnlineAdmission($id)
    {
        $this->load->model('AdmissionModel');
        $this->AdmissionModel->updateOnlineAdmissionStatus($id, 2);
        $this->session->set_flashdata('msg', '<div class="alert alert-danger">Student admission rejected successfully!</div>');
        redirect('student/admission_online');
    }

    public function addApprovedStudent($online_admission_id)
    {
        if (!$this->rbac->hasPrivilege('student', 'can_add')) {
            access_denied();
        }

        $this->load->model('AdmissionModel');
        $online_admission_data = $this->AdmissionModel->getOnlineAdmissionById($online_admission_id);

        if (empty($online_admission_data)) {
            $this->session->set_flashdata('error', 'Online admission record not found.');
            redirect('student/admission_online');
        }

        /* echo "<pre>";
        print_r($online_admission_data);
        die; */

        // Call a private helper method to handle the actual student creation
        $insert_id = $this->_createStudentFromOnlineAdmission($online_admission_data);

        if ($insert_id) {
            // Update the online admission status to 'completed' or 'admitted'
            $this->AdmissionModel->updateOnlineAdmissionStatus($online_admission_id, 1);
            $this->session->set_flashdata('msg', '<div class="alert alert-success">Student admitted successfully!</div>');
            redirect('student/edit/' . $insert_id); // Redirect to the newly created student's profile
        } else {
            $this->session->set_flashdata('error', 'Failed to admit student.');
            redirect('student/onlineStudentsview/' . $online_admission_id);
        }
    }

    private function _createStudentFromOnlineAdmission($online_admission_data)
    {
        $this->load->model('student_model');
        $this->load->model('user_model');
        $this->load->model('setting_model');
        $this->load->model('feesessiongroup_model');
        $this->load->model('transportfee_model');
        $this->load->model('category_model');
        $this->load->model('hostel_model');
        $this->load->model('vehroute_model');
        $this->load->model('customfield_model');
        $this->load->model('account_department_model');

        $data_insert = array(
            'firstname'         => $online_admission_data['firstName'],
            'lastname'          => $online_admission_data['lastName'],
            'dob'               => $online_admission_data['dob'],
            'gender'            => $online_admission_data['gender'],
            'blood_group'       => $online_admission_data['bloodGroup'],
            'email'             => $online_admission_data['email'],
            'mobileno'          => $online_admission_data['phone'],
            'current_address'   => $online_admission_data['address'],
            'city'              => $online_admission_data['city'],
            'pincode'           => $online_admission_data['pincode'],
            'guardian_name'     => $online_admission_data['guardianName'],
            'guardian_relation' => $online_admission_data['relation'],
            'guardian_phone'    => $online_admission_data['guardianPhone'],
            'guardian_email'    => $online_admission_data['guardianEmail'],
            'guardian_address'  => $online_admission_data['guardianAddress'],
            'is_active'         => 'yes',
            'is_online'         => 1,
            'admission_date'    => date('Y-m-d'), // Set admission date to today
        );

        if ($online_admission_data['relation'] == 'Father') {
            $data_insert['father_name'] = $online_admission_data['guardianName'];
        }
        // if ($online_admission_data['relation'] == 'Mother') {
        //     $data_insert['mother_name'] = $online_admission_data['guardianName'];
        // }

        // Handle default image
        if (empty($online_admission_data['image'])) {
            if ($online_admission_data['gender'] == 'Female') {
                $data_insert['image'] = 'uploads/student_images/default_female.jpg';
            } else {
                $data_insert['image'] = 'uploads/student_images/default_male.jpg';
            }
        } else {
            $data_insert['image'] = $online_admission_data['image']; // Assuming image path is stored in online_admission_data
        }

        // Admission Number Generation
        $selected_session_id = $online_admission_data['academicYear'];
        $class_id = $online_admission_data['classApplied']; // Assuming classApplied is the class_id
        $section_id = 1;

        $generatedAdmissionNumber = $this->student_model->generateAdmissionNumber($class_id, $selected_session_id);
        $data_insert['admission_no'] = $generatedAdmissionNumber;

        $data_setting = array(
            'id'                   => $this->sch_setting_detail->id,
            'adm_auto_insert'      => $this->sch_setting_detail->adm_auto_insert,
            'adm_update_status'    => $this->sch_setting_detail->adm_update_status,
        );

        // Insert Student Record
        $insert_id = $this->student_model->add($data_insert, $data_setting);

        if ($insert_id) {
            // Log Student Activity
            $current_user_id = $this->session->userdata['admin']['id'];
            $current_user_name = $this->session->userdata['admin']['username'];
            $current_date_time = date('Y-m-d H:i:s');

            $log_data = array(
                'id' => $insert_id,
                'admission_no' => $data_insert['admission_no'],
                'firstname' => $data_insert['firstname'],
                'lastname' => $data_insert['lastname'],
                'mobileno' => $data_insert['mobileno'],
                'dob' => $data_insert['dob'],
                'created_by' => $current_user_id,
                'created_at' => $current_date_time
            );
            log_students($log_data, 1, $current_user_id, $current_user_name); // 1 for Adding

            // Create Student Session
            $data_new_session = array(
                'student_id'            => $insert_id,
                'class_id'              => $class_id,
                'section_id'            => $section_id,
                'session_id'            => $selected_session_id,
                'admission_no'          => $generatedAdmissionNumber,
                'account_department_id' => 0
            );
            $student_session_id = $this->student_model->add_student_session($data_new_session);

            // Fee Management (simplified for now, assuming default fees for the class)
            $new_fees = $this->student_model->get_fees_by_class_id_for_class_update($class_id, $selected_session_id);
            if (!empty($new_fees)) {
                foreach ($new_fees as $fee) {
                    $fee_data = array(
                        'student_id'         => $insert_id,
                        'student_session_id' => $student_session_id,
                        'class_id'           => $class_id,
                        'session_id'         => $fee['session_id'],
                        'feetype_id'         => $fee['feetype_id'],
                        'original_fees'      => $fee['original_fees'],
                        'tuition_fees'       => $fee['tuition_fees'],
                        'meal_charges'       => $fee['meal_charges'],
                        'discounted_fees'    => $fee['discounted_fees'],
                        'is_monthly'         => $fee['is_monthly'],
                        'is_skipped'         => isset($fee['is_skipped']) ? $fee['is_skipped'] : 0,
                        'added_by'           => $current_user_id,
                        'created_at'         => $current_date_time,
                    );
                    $this->db->insert('student_fees_management', $fee_data);
                    $sfm_id = $this->db->insert_id();
                    $sfm_log_data = array_merge(array('id' => $sfm_id), $fee_data);
                    log_student_fees_management($sfm_log_data, 1, $current_user_id, $current_user_name);
                }
            }

            // User Login Creation
            $user_password = $this->role->get_random_password($chars_min = 6, $chars_max = 6, $use_upper_case = false, $include_numbers = true, $include_special_chars = false);
            $data_student_login = array(
                'username' => $this->student_login_prefix . $insert_id,
                'password' => $user_password,
                'user_id'  => $insert_id,
                'role'     => 'student',
                'lang_id'  => $this->sch_setting_detail->lang_id,
            );
            $this->user_model->add($data_student_login);

            $parent_password   = $this->role->get_random_password($chars_min = 6, $chars_max = 6, $use_upper_case = false, $include_numbers = true, $include_special_chars = false);
            $data_parent_login = array(
                'username' => $this->parent_login_prefix . $insert_id,
                'password' => $parent_password,
                'user_id'  => 0, // Will be updated after parent is added
                'role'     => 'parent',
                'childs'   => $insert_id,
            );
            $ins_parent_id  = $this->user_model->add($data_parent_login);
            $update_student = array(
                'id'        => $insert_id,
                'parent_id' => $ins_parent_id,
            );
            $this->student_model->add($update_student); // Update student with parent_id

            // Send Notifications
            $sender_details = array(
                'student_id'         => $insert_id,
                'student_phone'      => $data_insert['mobileno'],
                'guardian_phone'     => $data_insert['guardian_phone'],
                'student_email'      => $data_insert['email'],
                'guardian_email'     => $data_insert['guardian_email'],
                'student_session_id' => $student_session_id
            );
            $this->mailsmsconf->mailsms('student_admission', $sender_details);

            $student_login_detail = array(
                'id'                 => $insert_id,
                'credential_for'     => 'student',
                'first_name'         => $data_insert['firstname'],
                'last_name'          => $data_insert['lastname'],
                'username'           => $this->student_login_prefix . $insert_id,
                'password'           => $user_password,
                'contact_no'         => $data_insert['mobileno'],
                'email'              => $data_insert['email'],
                'admission_no'       => $data_insert['admission_no'],
                'student_session_id' => $student_session_id
            );
            $this->mailsmsconf->mailsms('student_login_credential', $student_login_detail);

            $parent_login_detail = array(
                'id'                 => $insert_id,
                'credential_for'     => 'parent',
                'username'           => $this->parent_login_prefix . $insert_id,
                'password'           => $parent_password,
                'contact_no'         => $data_insert['guardian_phone'],
                'email'              => $data_insert['guardian_email'],
                'admission_no'       => $data_insert['admission_no'],
                'student_session_id' => $student_session_id
            );
            $this->mailsmsconf->mailsms('student_login_credential', $parent_login_detail);

            return $insert_id;
        }
        return false;
    }

    public function get_all_filtered_students_json()
    {
        if (!$this->rbac->hasPrivilege('student_export', 'can_view')) {
            access_denied();
        }

        $search_type = $this->input->get('search_type');
        $class_id    = $this->input->get('class_id');
        $section_id  = $this->input->get('section_id');
        $session_id  = $this->input->get('session_id') ?: $this->setting_model->getCurrentSession();
        $gender      = $this->input->get('gender');
        $search_text = $this->input->get('search_text');

        $params = array(
            'search_type' => $search_type,
            'class_id' => $class_id,
            'section_id' => $section_id,
            'session_id' => $session_id,
            'gender' => $gender,
            'search_text' => $search_text
        );

        $students = $this->student_model->getFilteredStudents($params);

        $sch_setting = $this->sch_setting_detail;
        foreach ($students as &$student) {
            $student['student_name'] = $this->customlib->getFullName(
                $student['firstname'],
                $student['middlename'],
                $student['lastname'],
                $sch_setting->middlename,
                $sch_setting->lastname
            );

            // The spreadsheet export must contain dates only. Normalise both
            // fields here so a datetime value can never reach the XLSX writer.
            foreach (array('dob', 'admission_date') as $date_field) {
                if (!empty($student[$date_field]) && $student[$date_field] !== '0000-00-00') {
                    $student[$date_field] = date(
                        $this->customlib->getSchoolDateFormat(),
                        strtotime($student[$date_field])
                    );
                } else {
                    $student[$date_field] = '';
                }
            }
        }

        header('Content-Type: application/json');
        echo json_encode($students);
    }
}
