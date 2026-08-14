<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Examresult extends Admin_Controller
{
    const STATIC_MARKSHEET_TEMPLATE_NEW_2026 = 'new_design_2026';

    public $exam_type = array();

    public function __construct()
    {
        parent::__construct();

        $this->load->model('marks_model');
        $this->exam_type          = $this->config->item('exam_type');
        $this->attendence_exam    = $this->config->item('attendence_exam');
        $this->sch_setting_detail = $this->setting_model->getSetting();
        $this->load->model(array('marksdivision_model', 'marksdivision_model'));
        $this->load->library('mailsmsconf');
        $this->load->library('media_storage');
    }

    public function index()
    {
        if (!$this->rbac->hasPrivilege('exam_result', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Examinations');
        $this->session->set_userdata('sub_menu', 'Examinations/Examresult');
        $examgroup_result      = $this->examgroup_model->get();
        $data['examgrouplist'] = $examgroup_result;
        $class                 = $this->class_model->get();
        $data['title']         = 'Add Batch';
        $data['title_list']    = 'Recent Batch';
        $data['classlist']     = $class;
        $session               = $this->session_model->get();
        $data['sessionlist']   = $session;

        $this->form_validation->set_rules('session_id', $this->lang->line('session'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('exam_group_id', $this->lang->line('exam_group'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('section_id', $this->lang->line('section'), 'trim|required|xss_clean');

        if ($this->form_validation->run() == true) {
            $data['exam_group_id'] = $this->input->post('exam_group_id');
            $data['session_id'] = $this->input->post('session_id');
            $data['class_id'] = $this->input->post('class_id');
            $data['section_id'] = $this->input->post('section_id');

            if ($this->input->post('search')) {
                $data['subjects'] = $this->exam_model->get_subjects_by_exam($data['exam_group_id'], $data['session_id'], $data['class_id']);
                $data['students'] = $this->student_model->get_students_by_class_section($data['session_id'], $data['class_id'], $data['section_id']);

                foreach ($data['students'] as &$student) {
                    $student['marks'] = $this->marks_model->get_student_marks($student['id'], $data['exam_group_id'], $data['session_id'], $data['class_id']);
                }
            }
        }

        $data['sch_setting'] = $this->sch_setting_detail;
        $this->load->view('layout/header', $data);
        $this->load->view('admin/examresult/index', $data);
        $this->load->view('layout/footer', $data);
    }

    public function printCard()
    {
        $this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules('admitcard_template', $this->lang->line('template'), 'required|trim|xss_clean');
        $this->form_validation->set_rules('post_class_id', $this->lang->line('class'), 'required|trim|xss_clean');
        $this->form_validation->set_rules('post_session_id', $this->lang->line('session'), 'required|trim|xss_clean');
        $this->form_validation->set_rules('post_exam_group_id', $this->lang->line('exam_group'), 'required|trim|xss_clean');
        $this->form_validation->set_rules('exam_group_class_batch_exam_student_id[]', $this->lang->line('students'), 'required|trim|xss_clean');
        $data = array();

        if ($this->form_validation->run() == false) {
            $data = array(
                'admitcard_template'                     => form_error('admitcard_template'),
                'post_exam_group_id'                     => form_error('post_exam_group_id'),
                'post_class_id'                          => form_error('post_class_id'),
                'post_session_id'                        => form_error('post_session_id'),
                'exam_group_class_batch_exam_student_id' => form_error('exam_group_class_batch_exam_student_id'),
            );
            $array = array('status' => 0, 'error' => $data);
            echo json_encode($array);
        } else {
            $post_exam_group_id      = $this->input->post('post_exam_group_id');
            $post_class_id      = $this->input->post('post_class_id');
            $post_session_id      = $this->input->post('post_session_id');
            $students_array          = $this->input->post('exam_group_class_batch_exam_student_id');

            $data['admitcard']       = $this->admitcard_model->get($this->input->post('admitcard_template'));
            $data['exam_subjects']   = $this->examgroup_model->getExamSubjects($post_exam_group_id, $post_class_id, $post_session_id);
            $data['student_details'] = $this->examstudent_model->getStudentsAdmitCardByExamAndStudentID($students_array);
            /* echo "<pre>";
            print_r($data);
            die; */
            $data['sch_setting']     = $this->sch_setting_detail;
            if ($data['admitcard']->is_id_layout == 1) {
                $student_admit_cards = $this->load->view('admin/admitcard/_printadmitcard_idcard', $data, true);
            } else {
                $student_admit_cards = $this->load->view('admin/admitcard/_printadmitcard', $data, true);
            }

            $array                   = array('status' => '1', 'error' => '', 'page' => $student_admit_cards);
            echo json_encode($array);
        }
    }

    public function admitcard()
    {
        if (!$this->rbac->hasPrivilege('print_admit_card', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Examinations');
        $this->session->set_userdata('sub_menu', 'Examinations/examresult/admitcard');
        $examgroup_result      = $this->examgroup_model->get();
        $data['examgrouplist'] = $examgroup_result;
        $admitcard_result      = $this->admitcard_model->get();
        $data['admitcardlist'] = $admitcard_result;
        $class                 = $this->class_model->get();
        $data['title']         = 'Add Batch';
        $data['title_list']    = 'Recent Batch';
        $data['classlist']     = $class;
        $data['sessionlist'] = $this->session_model->getAllSession();
        $this->form_validation->set_rules('session_id', $this->lang->line('session'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('exam_group_id', $this->lang->line('exam_group'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('section_id', $this->lang->line('section'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('admitcard', $this->lang->line('admit_card_template'), 'trim|required|xss_clean');

        if ($this->form_validation->run() == false) {
        } else {
            $session_id              = $this->input->post('session_id');
            $exam_group_id              = $this->input->post('exam_group_id');
            $class_id                   = $this->input->post('class_id');
            $section_id              = $this->input->post('section_id');
            $exam_id              = $this->input->post('exam_id');
            $admitcard_template         = $this->input->post('admitcard');
            $data['admitcard_template'] = $admitcard_template;

            $data['studentList'] = $this->examgroupstudent_model->searchStudentByClassSectionSession($class_id, $section_id, $session_id);
            $data['studentList'] = array_map(function ($item) {
                return (object) $item;
            }, $data['studentList']);

            /* echo "<pre>";
            print_r($data['studentList']);
            die; */

            $data['session_id'] = $session_id;
            $data['exam_group_id'] = $exam_group_id;
            $data['class_id'] = $class_id;
            $data['section_id'] = $section_id;
            $data['exam_id'] = $exam_id;
        }
        $data['sch_setting'] = $this->sch_setting_detail;
        $this->load->view('layout/header', $data);
        $this->load->view('admin/examresult/admitcard', $data);
        $this->load->view('layout/footer', $data);
    }

    public function marksheet()
    {

        if (!$this->rbac->hasPrivilege('print_marksheet', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Examinations');
        $this->session->set_userdata('sub_menu', 'Examinations/examresult/marksheet');

        $marksheet_result      = $this->marksheet_model->get();
        $data['marksheetlist'] = $marksheet_result;
        $class                 = $this->class_model->get();
        $data['title']         = 'Add Batch';
        $data['title_list']    = 'Recent Batch';
        $data['examType']      = $this->exam_type;
        $data['classlist']     = $class;
        $session               = $this->session_model->get();
        $data['sessionlist']   = $session;
        $this->form_validation->set_rules('marksheet', $this->lang->line('marksheet_template'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('session_id', $this->lang->line('session'), 'trim|required|xss_clean');

        if ($this->form_validation->run() == true) {
            $session_id    = $this->input->post('session_id');
            $class_id      = $this->input->post('class_id');
            $section_id    = $this->input->post('section_id');

            $marksheet_template         = $this->input->post('marksheet');
            $data['marksheet_template'] = $marksheet_template;

            $data['studentList']        = $this->student_model->get_students_by_class_section($session_id, $class_id, $section_id);
            $data['session_id']         = $session_id;
            $data['class_id']           = $class_id;
            $data['section_id']         = $section_id;

            $student_ids = array_column($data['studentList'], 'id');
            $student_results = $this->examresult_model->getStudentOverallResultsByClassSession($student_ids, $session_id, $class_id, $section_id);
            $result_map = array();
            foreach ($student_results as $student_result) {
                $result_map[$student_result->id] = $student_result;
            }

            foreach ($data['studentList'] as &$student) {
                $student['total_overall_marks'] = isset($result_map[$student['id']]) ? $result_map[$student['id']]->total_overall_marks : 0;
            }

            usort($data['studentList'], function ($a, $b) {
                return $b['total_overall_marks'] <=> $a['total_overall_marks'];
            });
        }

        $data['sch_setting'] = $this->sch_setting_detail;
        $this->load->view('layout/header', $data);
        $this->load->view('admin/examresult/marksheet', $data);
        $this->load->view('layout/footer', $data);
    }

    public function pdftmarksheet()
    {
        $marksheet_template = $this->input->post('marksheet_template');
        $data               = $this->getMarksheetTemplateViewData($marksheet_template);

        $student_id  = $this->input->post('student_id');
        $session_id  = $this->input->post('post_exam_session_id');
        $class_id    = $this->input->post('post_exam_class_id');
        $section_id  = $this->input->post('post_exam_section_id');

        $data['full_student_marksheet_data'] = $this->examresult_model->getStudentOverallResultsByClassSession(array($student_id), $session_id, $class_id, $section_id);
        $data['sch_setting'] = $this->sch_setting_detail;
        if ($this->isStaticMarksheetTemplate($marksheet_template)) {
            $data = array_merge($data, $this->prepareNewDesign2026Data(array($student_id), $session_id, $class_id, $section_id, $data['full_student_marksheet_data']));
        }

        $html = $this->load->view($this->getMarksheetPrintView($marksheet_template), $data, true);

        $type = $this->input->post('type');
        $this->load->library('m_pdf');
        $mpdf       = $this->m_pdf->load($this->getMpdfLegalLandscapeConfig());
        $stylesheet = file_get_contents(base_url() . 'backend/pdf_style.css'); // external css
        if (!empty($data['template']) && $data['template']->background_img != "") {

            $mpdf->SetDefaultBodyCSS('background', "url('" . $this->customlib->getFolderPath() . "./uploads/marksheet/" . $data['template']->background_img . "')");
            $mpdf->SetDefaultBodyCSS('background-image-resize', 6);
        }
        $mpdf->WriteHTML($stylesheet, 1); // Writing style to pdf
        $mpdf->SetDisplayMode('fullpage');
        if ($this->isStaticMarksheetTemplate($marksheet_template)) {
            $mpdf->showWatermarkText = false;
        } else {
            $mpdf->SetWatermarkText($this->sch_setting_detail->name, .2);
            $mpdf->showWatermarkText = true;
        }
        $mpdf->autoScriptToLang  = true;
        $mpdf->baseScript        = 1;
        $mpdf->autoLangToFont    = true;
        $this->writeMpdfHtmlInChunks($mpdf, $html);
        $response = true;
        if ($type == "email") {
            $content = $mpdf->Output(random_string() . '.pdf', 'S');
            $student_value = !empty($data['full_student_marksheet_data']) ? $data['full_student_marksheet_data'][0] : null;
            if (!empty($student_value) && !empty($student_value->email)) {
                $student_name = $this->customlib->getFullName($student_value->firstname, $student_value->middlename, $student_value->lastname, $data['sch_setting']->middlename, $data['sch_setting']->lastname);
                $exam_name = !empty($student_value->exams) ? $student_value->exams[0]['exam_name'] : '';
                $sender_details = array(
                    'email' => $student_value->email,
                    'student_name' => $student_name,
                    'class' => $student_value->class,
                    'section' => $student_value->section,
                    'admission_no' => $student_value->admission_no,
                    'roll_no' => $student_value->roll_no,
                    'admit_card_roll_no' => $student_value->roll_no,
                    'dob' => $student_value->dob,
                    'guardian_name' => isset($student_value->guardian_name) ? $student_value->guardian_name : '',
                    'guardian_relation' => isset($student_value->guardian_relation) ? $student_value->guardian_relation : '',
                    'guardian_phone' => isset($student_value->guardian_phone) ? $student_value->guardian_phone : '',
                    'father_name' => $student_value->father_name,
                    'father_phone' => isset($student_value->father_phone) ? $student_value->father_phone : '',
                    'mother_name' => $student_value->mother_name,
                    'gender' => isset($student_value->gender) ? $student_value->gender : '',
                    'guardian_email' => isset($student_value->guardian_email) ? $student_value->guardian_email : '',
                    'exam' => $exam_name,
                );

                $this->mailsmsconf->mailsms('email_pdf_exam_marksheet', $sender_details, '', '', $content);
            } else {
                $response = false;
            }
        } elseif ($type == "download") {

            $content = $mpdf->Output(random_string() . '.pdf', 'I');
            return $content;
        }
        if ($response) {
            $array = array('status' => 1, 'message' => $this->lang->line('mail_sent_successfully'));
        } else {
            $array = array('status' => 0, 'message' => $this->lang->line('something_went_wrong'));
        }
        echo json_encode($array);
    }

    public function printmarksheet()
    {
        $this->form_validation->set_error_delimiters('', '');

        $this->form_validation->set_rules('post_exam_session_id', $this->lang->line('session'), 'required|trim|xss_clean');
        $this->form_validation->set_rules('post_exam_class_id', $this->lang->line('class'), 'required|trim|xss_clean');
        $this->form_validation->set_rules('exam_group_class_batch_exam_student_id[]', $this->lang->line('students'), 'required|trim|xss_clean');
        $data = array();

        if ($this->form_validation->run() == false) {
            $data = array(
                'post_exam_session_id'                   => form_error('post_exam_session_id'),
                'post_exam_class_id'                     => form_error('post_exam_class_id'),
                'exam_group_class_batch_exam_student_id' => form_error('exam_group_class_batch_exam_student_id'),
            );
            $array = array('status' => 0, 'error' => $data);
            echo json_encode($array);
        } else {
            $marksheet_template = $this->input->post('marksheet_template');
            $data               = $this->getMarksheetTemplateViewData($marksheet_template);

            $students_array         = $this->input->post('exam_group_class_batch_exam_student_id'); // These are exam_group_class_batch_exam_student_ids

            // Convert exam_group_class_batch_exam_student_ids to actual student_ids if needed
            // For now, let's assume we need to pass student_ids to the new model method.
            // We need to fetch student_ids from exam_group_class_batch_exam_student_id

            $student_ids_from_egcbesi = [];
            if (!empty($students_array)) {
                $this->load->model('examstudent_model');
                foreach ($students_array as $egcbesi) {
                    $exam_student = $this->examstudent_model->getExamStudentByID($egcbesi); // Corrected method call
                    if ($exam_student && isset($exam_student['student_id'])) {
                        $student_ids_from_egcbesi[] = $exam_student['student_id'];
                    }
                }
            }
            $student_ids_from_egcbesi = array_unique($student_ids_from_egcbesi);
            if (empty($student_ids_from_egcbesi)) {
                $student_ids_from_egcbesi = $students_array;
            }

            $post_exam_session_id = $this->input->post('post_exam_session_id');
            $post_exam_class_id   = $this->input->post('post_exam_class_id');
            $post_exam_section_id = $this->input->post('post_exam_section_id');
            $data['full_student_marksheet_data'] = $this->examresult_model->getStudentOverallResultsByClassSession($student_ids_from_egcbesi, $post_exam_session_id, $post_exam_class_id, $post_exam_section_id);

            $data['sch_setting']    = $this->sch_setting_detail;
            if ($this->isStaticMarksheetTemplate($marksheet_template)) {
                $data = array_merge($data, $this->prepareNewDesign2026Data($student_ids_from_egcbesi, $post_exam_session_id, $post_exam_class_id, $post_exam_section_id, $data['full_student_marksheet_data']));
            }

            $html                   = $this->load->view($this->getMarksheetPrintView($marksheet_template), $data, true);
            $this->load->library('m_pdf');

            $mpdf       = $this->m_pdf->load($this->getMpdfLegalLandscapeConfig());
            $stylesheet = file_get_contents(base_url() . 'backend/pdf_style.css'); // external css
            if (!empty($data['template']) && $data['template']->background_img != "") {

                $mpdf->SetDefaultBodyCSS('background', "url('" . $this->customlib->getFolderPath() . "./uploads/marksheet/" . $data['template']->background_img . "')");
                $mpdf->SetDefaultBodyCSS('background-image-resize', 6);
            }
            $mpdf->WriteHTML($stylesheet, 1); // Writing style to pdf
            $mpdf->SetDisplayMode('fullpage');
            if ($this->isStaticMarksheetTemplate($marksheet_template)) {
                $mpdf->showWatermarkText = false;
            } else {
                $mpdf->SetWatermarkText($this->sch_setting_detail->name, .2);
                $mpdf->showWatermarkText = true;
            }
            $mpdf->autoScriptToLang  = true;
            $mpdf->baseScript        = 1;
            $mpdf->autoLangToFont    = true;
            $this->writeMpdfHtmlInChunks($mpdf, $html);
            $response = true;

            $content = $mpdf->Output(random_string() . '.pdf', 'I');
            return $content;
        }
    }

    public function updaterank()
    {
        $exam_group_class_batch_exam_id         = $this->input->post('exam_group_class_batch_exam_id');
        $exam_group_class_batch_exam_student_id = $this->input->post('exam_group_class_batch_exam_student_id');
        if (!empty($exam_group_class_batch_exam_student_id)) {
            $exam_group_class_batch_exam_students = array();
            foreach ($exam_group_class_batch_exam_student_id as $exam_student_id_key => $exam_student_id_value) {
                $exam_group_class_batch_exam_students[] = array(
                    'id'   => $exam_student_id_value,
                    'rank' => $this->input->post('exam_group_class_batch_exam_student_id_' . $exam_student_id_value),
                );
            }
            $this->examresult_model->updaterank($exam_group_class_batch_exam_students, $exam_group_class_batch_exam_id);
        }

        $array = array('status' => '1', 'message' => $this->lang->line('update_message'));
        echo json_encode($array);
    }

    private function writeMpdfHtmlInChunks($mpdf, $html)
    {
        if (empty($html)) {
            return;
        }

        $chunks = preg_split('/<pagebreak\\s*\\/>/i', $html);
        $total_chunks = count($chunks);
        foreach ($chunks as $index => $chunk) {
            $trimmed = trim($chunk);
            if ($trimmed === '') {
                continue;
            }
            $mpdf->WriteHTML($trimmed, \Mpdf\HTMLParserMode::HTML_BODY);
            if ($index < ($total_chunks - 1)) {
                $mpdf->AddPage();
            }
        }
    }

    private function isStaticMarksheetTemplate($marksheet_template)
    {
        return $marksheet_template === self::STATIC_MARKSHEET_TEMPLATE_NEW_2026;
    }

    private function getMarksheetTemplateViewData($marksheet_template)
    {
        $data = array(
            'template'          => null,
            'marksheet_template' => $marksheet_template,
        );

        if (!$this->isStaticMarksheetTemplate($marksheet_template)) {
            $data['template'] = $this->marksheet_model->get($marksheet_template);
        }

        return $data;
    }

    private function getMarksheetPrintView($marksheet_template)
    {
        if ($this->isStaticMarksheetTemplate($marksheet_template)) {
            return 'admin/examresult/_printmarksheet_2026';
        }

        return 'admin/examresult/_printmarksheet';
    }

    private function prepareNewDesign2026Data($student_ids, $session_id, $class_id, $section_id = null, $selected_results = array())
    {
        $context = array(
            'new_design_2026_students' => array(),
        );

        if (empty($student_ids)) {
            return $context;
        }

        $all_student_ids = $student_ids;
        $class_scope_students = $this->student_model->get_students_by_class_section($session_id, $class_id, $section_id);
        if (!empty($class_scope_students)) {
            $all_student_ids = array_column($class_scope_students, 'id');
        }

        $all_results = $this->examresult_model->getStudentOverallResultsByClassSession($all_student_ids, $session_id, $class_id, $section_id);
        if (empty($selected_results)) {
            $selected_results = $this->examresult_model->getStudentOverallResultsByClassSession($student_ids, $session_id, $class_id, $section_id);
        }

        if (empty($all_results) || empty($selected_results)) {
            return $context;
        }

        $grade_rules = array();

        $first_exam_stats = $this->buildNewDesign2026ExamStats($all_results, $grade_rules);
        foreach ($selected_results as $student_result) {
            $context['new_design_2026_students'][] = $this->buildNewDesign2026StudentCard($student_result, $first_exam_stats, $grade_rules);
        }

        return $context;
    }

    private function buildNewDesign2026ExamStats($all_results, $grade_rules)
    {
        $stats = array(
            'subject_names' => array(),
            'exam_blocks' => array(
                0 => array('label' => '1st Term', 'subject_highest' => array()),
                1 => array('label' => '2nd Term', 'subject_highest' => array()),
                2 => array('label' => '3rd Term', 'subject_highest' => array()),
            ),
            'rank_map' => array(),
        );

        $rank_totals = array();
        foreach ($all_results as $student_result) {
            if (empty($student_result->exams)) {
                continue;
            }

            $student_total = 0;
            for ($exam_index = 0; $exam_index < 3; $exam_index++) {
                if (empty($student_result->exams[$exam_index])) {
                    continue;
                }

                $exam_data = $student_result->exams[$exam_index];
                if (!empty($exam_data['exam_name'])) {
                    $stats['exam_blocks'][$exam_index]['label'] = $exam_data['exam_name'];
                }

                foreach ($exam_data['subject_results'] as $subject_index => $subject_result) {
                    if (!isset($stats['subject_names'][$subject_index])) {
                        $stats['subject_names'][$subject_index] = $subject_result['subject_name'];
                    }

                    $subject_marks = (float) $subject_result['obtain_marks'];
                    if (!isset($stats['exam_blocks'][$exam_index]['subject_highest'][$subject_index]) || $subject_marks > $stats['exam_blocks'][$exam_index]['subject_highest'][$subject_index]) {
                        $stats['exam_blocks'][$exam_index]['subject_highest'][$subject_index] = $subject_marks;
                    }
                    $student_total += $subject_marks;
                }
            }

            $rank_totals[] = array(
                'student_id' => $student_result->id,
                'total'      => $student_total,
            );
        }

        usort($rank_totals, function ($left, $right) {
            if ($right['total'] == $left['total']) {
                return 0;
            }

            return ($right['total'] < $left['total']) ? -1 : 1;
        });

        $rank = 0;
        $previous_total = null;
        foreach ($rank_totals as $index => $rank_item) {
            if ($previous_total === null || $rank_item['total'] != $previous_total) {
                $rank = $index + 1;
                $previous_total = $rank_item['total'];
            }
            $stats['rank_map'][$rank_item['student_id']] = $rank;
        }

        return $stats;
    }

    private function buildNewDesign2026StudentCard($student_result, $stats, $grade_rules)
    {
        $subject_names     = array_values($stats['subject_names']);
        $blank_columns     = array_fill(0, count($subject_names), '');
        $rows              = array();
        $grand_total       = 0;
        $grand_total_max   = 0;

        for ($exam_index = 0; $exam_index < 3; $exam_index++) {
            $exam_data       = !empty($student_result->exams[$exam_index]) ? $student_result->exams[$exam_index] : array('subject_results' => array(), 'exam_name' => $stats['exam_blocks'][$exam_index]['label']);
            $subject_highest = array_values(isset($stats['exam_blocks'][$exam_index]['subject_highest']) ? $stats['exam_blocks'][$exam_index]['subject_highest'] : array());
            $full_marks      = array();
            $obtained_marks  = array();
            $subject_grades  = array();
            $exam_total      = 0;
            $exam_total_max  = 0;

            foreach ($subject_names as $subject_index => $subject_name) {
                $matched_subject = isset($exam_data['subject_results'][$subject_index]) ? $exam_data['subject_results'][$subject_index] : null;
                if (!empty($matched_subject)) {
                    $max_marks        = (float) $matched_subject['full_marks'];
                    $obtain_marks     = (float) $matched_subject['obtain_marks'];
                    $full_marks[]     = $this->formatMarksheetNumber($max_marks);
                    $obtained_marks[] = $this->formatMarksheetNumber($obtain_marks);
                    $subject_grades[] = $this->findTableGradeLabel($max_marks > 0 ? (($obtain_marks * 100) / $max_marks) : 0);
                    $exam_total      += $obtain_marks;
                    $exam_total_max  += $max_marks;
                } else {
                    $full_marks[]     = '';
                    $obtained_marks[] = '';
                    $subject_grades[] = '';
                }
            }

            $highest_values = array();
            foreach ($subject_names as $subject_index => $subject_name) {
                $highest_values[] = isset($subject_highest[$subject_index]) ? $this->formatMarksheetNumber($subject_highest[$subject_index]) : '';
            }
            $highest_total = array_sum(isset($stats['exam_blocks'][$exam_index]['subject_highest']) ? $stats['exam_blocks'][$exam_index]['subject_highest'] : array());

            if (!empty($exam_data['subject_results'])) {
                $grand_total += $exam_total;
                $grand_total_max += $exam_total_max;
            }

            $rows[] = array('label' => $stats['exam_blocks'][$exam_index]['label'], 'values' => $full_marks, 'total' => $exam_total_max > 0 ? $this->formatMarksheetNumber($exam_total_max) : '');
            $rows[] = array('label' => 'Highest Marks', 'values' => $highest_values, 'total' => $highest_total > 0 ? $this->formatMarksheetNumber($highest_total) : '');
            $rows[] = array('label' => 'Marks Obtained', 'values' => $obtained_marks, 'total' => $exam_total > 0 ? $this->formatMarksheetNumber($exam_total) : '');
            $rows[] = array('label' => 'Grade', 'values' => $subject_grades, 'total' => $exam_total_max > 0 ? $this->findTableGradeLabel(($exam_total * 100) / $exam_total_max) : '');
        }

        $percentage    = $grand_total_max > 0 ? (($grand_total * 100) / $grand_total_max) : 0;
        $photo_url     = !empty($student_result->image) ? $this->media_storage->getImageURL($student_result->image) : '';

        return array(
            'student'             => $student_result,
            'photo_url'           => $photo_url,
            'full_name'           => $this->customlib->getFullName($student_result->firstname, $student_result->middlename, $student_result->lastname, $this->sch_setting_detail->middlename, $this->sch_setting_detail->lastname),
            'registration_no'     => $student_result->id,
            'admission_no'        => $student_result->admission_no,
            'subject_names'       => $subject_names,
            'rows'                => $rows,
            'grand_total'         => $this->formatMarksheetNumber($grand_total),
            'grand_total_max'     => $this->formatMarksheetNumber($grand_total_max),
            'percentage'          => two_digit_float($percentage),
            'grade'               => $this->findFinalGradeLabel($percentage),
            'rank'                => isset($stats['rank_map'][$student_result->id]) ? $stats['rank_map'][$student_result->id] : '',
        );
    }

    private function findFinalGradeLabel($percentage)
    {
        if ($percentage >= 90) {
            return 'Star';
        }
        if ($percentage >= 80) {
            return 'Excellent';
        }
        if ($percentage >= 60) {
            return 'Very Good';
        }
        if ($percentage >= 45) {
            return 'Good';
        }
        if ($percentage >= 35) {
            return 'Satisfactory';
        }
        if ($percentage >= 30) {
            return 'Marginal';
        }

        return 'Disqualified';
    }

    private function findTableGradeLabel($percentage)
    {
        if ($percentage >= 90) {
            return 'AA';
        }
        if ($percentage >= 80) {
            return 'A+';
        }
        if ($percentage >= 60) {
            return 'A';
        }
        if ($percentage >= 45) {
            return 'B+';
        }
        if ($percentage >= 35) {
            return 'C';
        }
        if ($percentage >= 30) {
            return 'D';
        }

        return 'E';
    }

    private function formatMarksheetNumber($number)
    {
        $number = (float) $number;
        if (floor($number) == $number) {
            return (string) (int) $number;
        }

        return two_digit_float($number);
    }

    private function getMpdfLegalLandscapeConfig()
    {
        return array(
            'tempDir' => APPPATH . 'tmp',
            'mode' => 'utf-8',
            'default_font' => 'roboto',
            'margin_left' => 2,
            'margin_right' => 2,
            'margin_top' => 2,
            'margin_bottom' => 2,
            'format' => 'Legal',
            'orientation' => 'L',
        );
    }

    public function examrank()
    {
        $exam_id       = $this->input->post('exam_id');
        $studentList   = $this->examgroupstudent_model->searchExamStudentsByExam($exam_id);
        $exam_details  = $this->examgroup_model->getExamByID($exam_id);
        $exam_subjects = $this->batchsubject_model->getExamSubjects($exam_id);
        $subjectList   = $exam_subjects;

        if (!empty($studentList)) {
            foreach ($studentList as $student_key => $student_value) {
                $studentList[$student_key]->subject_results = $this->examresult_model->getStudentResultByExam($exam_id, $student_value->exam_group_class_batch_exam_student_id);
            }
        }
        $data['subjectList']  = $exam_subjects;
        $data['studentList']  = $studentList;
        $exam_grades          = $this->grade_model->getByExamType($exam_details->exam_group_type);
        $data['exam_grades']  = $exam_grades;
        $data['exam_details'] = $exam_details;
        $data['exam_id']      = $exam_id;
        $data['sch_setting']  = $this->sch_setting_detail;
        $page                 = $this->load->view('admin/examresult/_partialexamrank', $data, true);

        $array = array('status' => '1', 'page' => $page, 'exam_details' => $exam_details, 'message' => $this->lang->line('success_message'));
        echo json_encode($array);
    }

    public function getStudentByClassBatch()
    {
        $class_id            = $this->input->post('class_id');
        $section_id          = $this->input->post('section_id');
        $session_id          = $this->input->post('session_id');
        $data['studentList'] = $this->examgroupstudent_model->searchStudentByClassSectionSession($class_id, $section_id, $session_id);
        echo json_encode($data);
    }

    public function getClassesByExamGroup()
    {
        $exam_group_id = $this->input->post('exam_group_id');
        $data = $this->examgroup_model->getClassesByExamGroup($exam_group_id);
        echo json_encode($data);
    }

    public function getExamGroupByStudent()
    {
        $student_id = $this->input->post('student_id');
        $data['examgrouplist'] = $this->examgroup_model->getExamGroupByStudent($student_id);
        echo json_encode($data);
    }

    public function studentresult()
    {
        $this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules('exam_group_id', $this->lang->line('exam_group_id'), 'required|trim|xss_clean');
        $this->form_validation->set_rules('student_id', $this->lang->line('student_id'), 'required|trim|xss_clean');

        if ($this->form_validation->run() == false) {
            $data = array(
                'exam_group_id' => form_error('exam_group_id'),
                'student_id'    => form_error('student_id'),
            );
            $array = array('status' => 0, 'error' => $data);
            echo json_encode($array);
        } else {

            $student_id         = $this->input->post('student_id');
            $exam_group_id      = $this->input->post('exam_group_id');
            $exam_group_exam_id = $this->input->post('exam_id');

            $examresult  = array();
            $exam_grades = array();
            if ($exam_group_exam_id != "") {
                $examresult = $this->examgroup_model->getExamResultDetailStudent($exam_group_exam_id, $exam_group_id, $student_id);
                $data['examresult']  = $examresult;
                $exam_grades         = $this->grade_model->getByExamType($examresult->exam_type);
                $data['exam_grades'] = $exam_grades;
                $examresult          = $this->load->view('admin/examresult/_getExam', $data, true);
            } else {
                $exam_group         = $this->examgroup_model->get($exam_group_id);
                $data['exam_group'] = $exam_group;
                $exam_grades         = $this->grade_model->getByExamType($exam_group->exam_type);
                $data['exam_grades'] = $exam_grades;
                $exam_result              = $this->examgroup_model->getExamGroupExamsResultByStudentID($exam_group_id, $student_id);
                $data['examresult']       = $exam_result;
                $exam_connections         = $this->examgroup_model->getExamGroupConnection($exam_group_id);
                $data['exam_connections'] = $exam_connections;
                $examresult               = $this->load->view('admin/examresult/_getExamGroupResult', $data, true);
            }

            $data['exam_grades'] = $exam_grades;

            $array = array('status' => '1', 'result' => $examresult, 'message' => $this->lang->line('success_message'));
            echo json_encode($array);
        }
    }

    public function getStudentCurrentResult()
    {
        $this->form_validation->set_rules('student_session_id', $this->lang->line('student_id'), 'trim|required|xss_clean');

        if ($this->form_validation->run() == false) {
            $msg = array(
                'student_session_id' => form_error('student_session_id'),
            );

            $array = array('status' => 0, 'error' => $msg);
        } else {
            $student_session_id  = $this->input->post('student_session_id');
            $data['exam_grades'] = $this->grade_model->get();
            $exam_groups_attempt = $this->examgroup_model->getExamGroupByStudentSession($student_session_id);

            $data['exam_groups_attempt'] = $exam_groups_attempt;
            $examresult                  = $this->load->view('admin/examresult/_getExamGroupResult', $data, true);
            $array                       = array('status' => 1, 'error' => '', 'result' => $examresult);
        }
        echo json_encode($array);
    }

    public function generatemarksheet()
    {
        $this->form_validation->set_rules('exam_id', $this->lang->line('exam_id'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('check[]', $this->lang->line('students'), 'trim|required|xss_clean');

        if ($this->form_validation->run() == false) {

            $msg = array(
                'exam_id' => form_error('exam_id'),
                'check'   => form_error('check'),
            );

            $array = array('status' => 0, 'error' => $msg);
        } else {
            echo "<pre/>";
            $exam_id         = $this->input->post('exam_id');
            $students        = $this->input->post('check');
            $exam            = $this->examgroup_model->getExamByID($exam_id);
            $exam_id         = $exam->id;
            $students_result = array();
            if (!empty($students)) {
                foreach ($students as $student_key => $student_value) {
                    print_r($student_value);
                    exit();

                    $students_result[] = $this->examresult_model->getStudentExamResult($exam_id, $student_value);
                }
            }
            print_r($students_result);
            exit();
        }
        echo json_encode($array);
    }

    public function rankreport()
    {
        if (!$this->rbac->hasPrivilege('rank_report', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Reports');
        $this->session->set_userdata('sub_menu', 'Reports/examinations');
        $this->session->set_userdata('subsub_menu', 'Reports/examinations/rankreport');
        $examgroup_result      = $this->examgroup_model->get();
        $data['examgrouplist'] = $examgroup_result;

        $marksheet_result      = $this->marksheet_model->get();
        $data['marksheetlist'] = $marksheet_result;

        $class               = $this->class_model->get();
        $data['title']       = 'Add Batch';
        $data['title_list']  = 'Recent Batch';
        $data['examType']    = $this->exam_type;
        $data['classlist']   = $class;
        $session             = $this->session_model->get();
        $data['sessionlist'] = $session;
        $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('section_id', $this->lang->line('section'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('session_id', $this->lang->line('session'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('exam_group_id', $this->lang->line('exam_group'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('exam_id', $this->lang->line('exam'), 'trim|required|xss_clean');

        if ($this->form_validation->run() == true) {


            $exam_group_id = $this->input->post('exam_group_id');
            $exam_id       = $this->input->post('exam_id');
            $session_id    = $this->input->post('session_id');
            $class_id      = $this->input->post('class_id');
            $section_id    = $this->input->post('section_id');

            $marksheet_template         = $this->input->post('marksheet');
            $data['marksheet_template'] = $marksheet_template;
            $exam_details               = $this->examgroup_model->getExamByID($exam_id);

            $studentList = $this->examgroupstudent_model->searchExamStudents($exam_group_id, $exam_id, $class_id, $section_id, $session_id);

            $exam_subjects       = $this->batchsubject_model->getExamSubjects($exam_id);
            $data['subjectList'] = $exam_subjects;

            if (!empty($studentList)) {
                foreach ($studentList as $student_key => $student_value) {
                    $studentList[$student_key]->subject_results = $this->examresult_model->getStudentResultByExam($exam_id, $student_value->exam_group_class_batch_exam_student_id);
                }
            }

            $data['studentList'] = $studentList;

            $exam_grades           = $this->grade_model->getByExamType($exam_details->exam_group_type);
            $data['exam_grades']   = $exam_grades;
            $data['exam_details']  = $exam_details;
            $data['exam_id']       = $exam_id;
            $data['exam_group_id'] = $exam_group_id;
        }
        $data['sch_setting'] = $this->sch_setting_detail;
        $this->load->view('layout/header', $data);
        $this->load->view('admin/examresult/rankreport', $data);
        $this->load->view('layout/footer', $data);
    }

    public function examinations()
    {
        if (!$this->rbac->hasPrivilege('rank_report', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Reports');
        $this->session->set_userdata('sub_menu', 'Reports/examinations');
        $this->session->set_userdata('subsub_menu', '');
        $this->load->view('layout/header');
        $this->load->view('admin/examresult/examinations');
        $this->load->view('layout/footer');
    }
}
