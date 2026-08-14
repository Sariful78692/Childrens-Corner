<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Stdtransfer extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model("classteacher_model");
        $this->sch_setting_detail = $this->setting_model->getSetting();
    }

    public function index()
    {
        if (!$this->rbac->hasPrivilege('promote_student', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Academics');
        $this->session->set_userdata('sub_menu', 'stdtransfer/index');
        $this->load->model('account_department_model');
        $data['account_departments'] = $this->account_department_model->get();
        $data['title']           = 'Exam Schedule';
        $class                   = $this->class_model->get('', $classteacher = 'yes');
        $data['classlist']       = $class;
        $userdata                = $this->customlib->getUserData();
        $data['sch_setting']     = $this->sch_setting_detail;
        $session_result          = $this->session_model->get();
        $data['sessionlist']     = $session_result;
        $this->form_validation->set_rules('session_id', $this->lang->line('promote_in_session'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('section_id', $this->lang->line('section'), 'trim|required|xss_clean');

        /* $this->form_validation->set_rules('class_promote_id', $this->lang->line('class'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('section_promote_id', $this->lang->line('section'), 'trim|required|xss_clean'); */
        if ($this->form_validation->run() == true) {

            $class                         = $this->input->post('class_id');
            $section                       = $this->input->post('section_id');
            $session                       = $this->input->post('session_id');
            $class_promote                 = $this->input->post('class_promote_id');
            $section_promote               = $this->input->post('section_promote_id');
            $data['class_post']            = $class;
            $data['section_post']          = $section;
            $data['class_promoted_post']   = $class_promote;
            $data['section_promoted_post'] = $section_promote;
            $data['session_promoted_post'] = $session;

            $resultlist = $this->student_model->searchNonPromotedStudents($class, $section, $session);

            $data['resultlist'] = $resultlist;
        }
        /* echo "<pre>";
        print_r($data);
        die; */


        $this->load->view('layout/header', $data);
        $this->load->view('admin/stdtransfer/stdtransfer', $data);
        $this->load->view('layout/footer', $data);
    }

    public function promote()
    {
        /* echo "<pre>";
        print_r($_POST);
        die; */
        $this->form_validation->set_rules('session_id', $this->lang->line('session'), 'required|trim|xss_clean');
        $this->form_validation->set_rules('class_promote_id', $this->lang->line('class'), 'required|trim|xss_clean');
        $this->form_validation->set_rules('section_promote_id', $this->lang->line('section'), 'required|trim|xss_clean');
        $this->form_validation->set_rules('student_list[]', $this->lang->line('student'), 'required|trim|xss_clean');
        if ($this->form_validation->run() == false) {
            $errors = array(
                'session_promote_id'         => form_error('session_promote_id'),
                'class_promote_id'   => form_error('class_promote_id'),
                'section_promote_id' => form_error('section_promote_id'),
                'student_list'       => form_error('student_list[]'),
            );
            echo json_encode(array('status' => 'fail', 'msg' => $errors));
            die;
        } else {
            $student_list    = $this->input->post('student_list');

            if (!empty($student_list) && isset($student_list)) {

                $session_id = $this->input->post('session_promote_id');
                $class_id = $this->input->post('class_promote_id');
                $section_id = $this->input->post('section_promote_id');
                $account_department_id = $this->input->post('account_department_promote_id');

                $fees = $this->student_model->get_fees_by_class_id_for_class_update($class_id, $session_id);
                if (empty($fees)) {
                    echo json_encode(array('status' => 'fail', 'msg' => "No fees found for this session and class!"));
                    die;
                }
                $count = 0;
                foreach ($student_list as $key => $value) {
                    $count++;
                    $student_id     = $value;

                    $this->db->where('student_id', $student_id);

                    $this->db->update('student_session', ['updated_at' => date('Y-m-d H:i:s'), 'status' => 0]);

                    $generatedAdmissionNumber = $this->student_model->generateAdmissionNumber($class_id, $session_id);

                    $data_new = array(
                        'student_id' => $student_id,
                        'class_id' => $class_id,
                        'section_id' => $section_id,
                        'session_id' => $session_id,
                        'admission_no' => $generatedAdmissionNumber,
                        'account_department_id' => $account_department_id,
                    );

                    $this->db->insert('student_session', $data_new);

                    $new_student_session_id = $this->db->insert_id();

                    if ($new_student_session_id) {

                        $this->db->where('id', $student_id);
                        $this->db->update('students', ['admission_no' => $generatedAdmissionNumber]);

                        foreach ($fees as $fee) {
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
                                'added_by' => $this->customlib->getStaffID(),
                                'created_at' => date('Y-m-d H:i:s'),
                            );
                            $this->db->insert('student_fees_management', $fee_data);
                        }
                    }
                }
            }

            echo json_encode(array('status' => 'success', 'msg' => "$count student has been promoted!"));
            die;
        }
    }
}
