<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Experiencecertificate extends Admin_Controller
{
    private $certificate_fields = array(
        'teacher_name'     => 'Teacher Name',
        'guardian_name'    => 'Guardian Name',
        'address'          => 'Address',
        'appointment_type' => 'Type of Appointment',
        'appointment_date' => 'Appointment Date',
        'joining_date'     => 'Joining Date',
        'working_since'    => 'Working Since',
        'subject'          => 'Subject',
        'category'         => 'Category',
        'duty_hours'       => 'Present Duty Hours',
        'post_type'        => 'Type of Post',
        'service_period'   => 'Service Period',
        'subjects_taught'  => 'Subjects Taught',
        'ref_no'           => 'Ref. No',
        'issue_date'       => 'Date',
    );

    private $header_fields = array(
        'header_left_text'   => 'Header Left Text',
        'header_center_text' => 'Header Center Text',
        'header_right_text'  => 'Header Right Text',
    );

    private $reference_fields = array(
        'reference_left_text'  => 'Reference Left Text',
        'reference_right_text' => 'Reference Right Text',
    );

    public function __construct()
    {
        parent::__construct();
        $this->load->library('media_storage');
        $this->load->model('filetype_model');
        $this->load->model('experiencecertificate_model');
    }

    public function index()
    {
        $this->require_certificate_access('can_view');
        $this->set_certificate_menu();

        if ($this->input->method(true) === 'POST') {
            $this->require_certificate_access('can_add');
            if ($this->validate_certificate()) {
                $background_image = '';
                if (!empty($_FILES['background_image']['name'])) {
                    $background_image = $this->media_storage->fileupload('background_image', './uploads/certificate/');
                }
                $record_id = $this->experiencecertificate_model->save($this->posted_certificate(), null, $background_image);
                if ($record_id) {
                    $this->session->set_flashdata('msg', '<div class="alert alert-success">Experience Certificate saved successfully.</div>');
                    redirect('admin/experiencecertificate');
                    return;
                }
                $this->session->set_flashdata('msg', '<div class="alert alert-danger">Unable to save Experience Certificate.</div>');
            }
        }

        $this->render_form();
    }

    public function edit($id)
    {
        $this->require_certificate_access('can_edit');
        $this->set_certificate_menu();
        $record = $this->experiencecertificate_model->getById($id);
        if (!$record) {
            show_404();
            return;
        }

        $fields = json_decode($record->certificate_text, true);
        if (!is_array($fields)) {
            show_error('Experience Certificate data is invalid.');
            return;
        }

        if ($this->input->method(true) === 'POST') {
            if ($this->validate_certificate()) {
                $old_background = $record->background_image;
                $background_image = $old_background;
                if ($this->input->post('remove_background_image')) {
                    $background_image = '';
                }
                if (!empty($_FILES['background_image']['name'])) {
                    $background_image = $this->media_storage->fileupload('background_image', './uploads/certificate/');
                }
                if ($this->experiencecertificate_model->save($this->posted_certificate(), $id, $background_image)) {
                    if ($old_background && $old_background !== $background_image) {
                        $this->media_storage->filedelete($old_background, 'uploads/certificate/');
                    }
                    $this->session->set_flashdata('msg', '<div class="alert alert-success">Experience Certificate updated successfully.</div>');
                    redirect('admin/experiencecertificate');
                    return;
                }
                $this->session->set_flashdata('msg', '<div class="alert alert-danger">Unable to update Experience Certificate.</div>');
            }
        }

        $this->render_form($fields, $id);
    }

    public function delete($id)
    {
        $this->require_certificate_access('can_delete');
        $this->set_certificate_menu();
        $record = $this->experiencecertificate_model->getById($id);
        if ($record && $this->experiencecertificate_model->remove($id)) {
            if (!empty($record->background_image)) {
                $this->media_storage->filedelete($record->background_image, 'uploads/certificate/');
            }
            $this->session->set_flashdata('msg', '<div class="alert alert-success">Experience Certificate deleted successfully.</div>');
        }
        redirect('admin/experiencecertificate');
    }

    public function print_certificate($id)
    {
        $this->require_certificate_access('can_view');
        $this->set_certificate_menu();
        $record = $this->experiencecertificate_model->getById($id);
        if (!$record) {
            show_404();
            return;
        }

        $data['title'] = 'Experience Certificate';
        $data['certificate'] = json_decode($record->certificate_text, true);
        if (!is_array($data['certificate'])) {
            show_error('Experience Certificate data is invalid.');
            return;
        }
        $data['certificate']['background_image'] = $record->background_image;
        $data['certificate']['header_height'] = $record->header_height;
        $data['certificate']['content_height'] = $record->content_height;
        $data['certificate']['footer_height'] = $record->footer_height;
        $data['certificate']['content_width'] = $record->content_width;
        $data['background_url'] = $record->background_image ? $this->media_storage->getImageURL('uploads/certificate/' . $record->background_image) : '';
        $data['default_body_text'] = $this->default_body_text();
        $this->load->view('admin/certificate/experiencecertificate_print', $data);
    }

    private function validate_certificate()
    {
        foreach ($this->certificate_fields as $field => $label) {
            $this->form_validation->set_rules($field, $label, 'trim|required|xss_clean');
        }
        $this->form_validation->set_rules('body_text', 'Body Text', 'trim|required|xss_clean');
        foreach (array('header_height', 'content_height', 'footer_height', 'content_width') as $field) {
            $this->form_validation->set_rules($field, ucwords(str_replace('_', ' ', $field)), 'trim|required|numeric');
        }
        $this->form_validation->set_rules('background_image', 'Background Image', 'callback_handle_background_upload');
        return $this->form_validation->run() !== false;
    }

    private function posted_certificate()
    {
        $fields = array();
        foreach ($this->certificate_fields as $field => $label) {
            $fields[$field] = $this->input->post($field, true);
        }
        foreach ($this->header_fields as $field => $label) {
            $fields[$field] = $this->input->post($field, true);
        }
        foreach ($this->reference_fields as $field => $label) {
            $fields[$field] = $this->input->post($field, true);
        }
        $fields['body_text'] = $this->input->post('body_text', true);
        foreach (array('header_height', 'content_height', 'footer_height', 'content_width') as $field) {
            $fields[$field] = $this->input->post($field, true);
        }
        return $fields;
    }

    private function render_form($edit_fields = null, $edit_id = null)
    {
        $data['title'] = 'Experience Certificate';
        $data['fields'] = $this->certificate_fields;
        $data['header_fields'] = $this->header_fields;
        $data['reference_fields'] = $this->reference_fields;
        $data['default_reference_left_text'] = 'Ref. No [ref_no]';
        $data['default_reference_right_text'] = 'Date : [issue_date]';
        $data['default_body_text'] = $this->default_body_text();
        $data['edit_certificate'] = $edit_fields;
        $data['edit_id'] = $edit_id;
        $data['background_image'] = $edit_id ? $this->experiencecertificate_model->getById($edit_id)->background_image : '';
        $data['certificate_list'] = $this->experiencecertificate_model->getList();

        $this->load->view('layout/header', $data);
        $this->load->view('admin/certificate/experiencecertificate_form', $data);
        $this->load->view('layout/footer', $data);
    }

    private function require_certificate_access($permission)
    {
        if (!$this->rbac->hasPrivilege('student_certificate', $permission)) {
            access_denied();
        }
    }

    private function set_certificate_menu()
    {
        $this->session->set_userdata('top_menu', 'Certificate');
        $this->session->set_userdata('sub_menu', 'admin/experiencecertificate');
    }

    private function default_body_text()
    {
        return "This is to certify that Mr. [teacher_name]\nS/O [guardian_name]\nOf [address]\nhad been given appointment letter for [appointment_type] on [appointment_date].\nHe had joined his post on [joining_date].\nHe is / was hard working, sincere, honest teacher. He has / had been efficiently working here in this institution since [working_since]. Being [category] teacher he has / had handled the students with care, love. He is not only efficient in teaching his subject [subject], or in doing any assigned work, but also a true friend, philosopher and guide to the students.\nPresent duty hours : [duty_hours]\nType of post : [post_type]\nService Period : [service_period]\nSubjects taught : [subjects_taught]\nI wish his success and a prosperous life.\n[reference_row]";
    }

    public function handle_background_upload()
    {
        if (empty($_FILES['background_image']['name'])) {
            return true;
        }
        if (!isset($_FILES['background_image']['error']) || $_FILES['background_image']['error'] !== UPLOAD_ERR_OK) {
            $this->form_validation->set_message('handle_background_upload', 'The background image could not be uploaded.');
            return false;
        }

        $filetype = $this->filetype_model->get();
        if (empty($filetype)) {
            $this->form_validation->set_message('handle_background_upload', 'File upload settings are not available.');
            return false;
        }

        $extension = strtolower(pathinfo($_FILES['background_image']['name'], PATHINFO_EXTENSION));
        $allowed_extensions = array_map('trim', array_map('strtolower', explode(',', $filetype->file_extension)));
        $allowed_mime_types = array_map('trim', array_map('strtolower', explode(',', $filetype->file_mime)));
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime_type = finfo_file($finfo, $_FILES['background_image']['tmp_name']);
        finfo_close($finfo);

        if (!in_array($extension, $allowed_extensions, true) || !in_array(strtolower($mime_type), $allowed_mime_types, true)) {
            $this->form_validation->set_message('handle_background_upload', $this->lang->line('file_type_not_allowed'));
            return false;
        }
        if ($_FILES['background_image']['size'] > $filetype->file_size) {
            $this->form_validation->set_message('handle_background_upload', $this->lang->line('file_size_shoud_be_less_than') . number_format($filetype->file_size / 1048576, 2) . ' MB');
            return false;
        }
        return true;
    }
}
