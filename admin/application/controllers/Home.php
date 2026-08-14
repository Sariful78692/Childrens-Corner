<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Home extends CI_Controller
{
    public function index()
    {
        $this->load->view('inc/header');
        $this->load->view('index');
        $this->load->view('inc/footer');
    }
    public function online_application()
    {
        $this->load->model('setting_model');
        $this->load->model('class_model');
        $this->load->model('session_model');

        $data['classes'] = $this->class_model->getAll();
        $data['sessions'] = $this->session_model->getAllSession();

        // $this->load->view('inc/header');
        $this->load->view('admission_form', $data);
        // $this->load->view('inc/footer');
    }

    public function apply()
    {
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->library('session');
        $this->load->model('AdmissionModel');
        $this->load->database(); // Added this line to load the database library

        $this->load->model('setting_model');
        $this->load->model('class_model');
        $this->load->model('session_model');

        $data['classes'] = $this->class_model->getAll();
        $data['sessions'] = $this->session_model->getAllSession();

        $this->form_validation->set_rules('firstName', 'First Name', 'trim|required|min_length[2]|max_length[50]');
        $this->form_validation->set_rules('lastName', 'Last Name', 'trim|required|alpha|min_length[2]|max_length[50]');
        $this->form_validation->set_rules('dob', 'Date of Birth', 'required|callback_valid_dob');
        $this->form_validation->set_rules('gender', 'Gender', 'required|in_list[Male,Female,Other,"Prefer not to say"]');
        //$this->form_validation->set_rules('bloodGroup', 'Blood Group', 'trim|max_length[10]');
        //$this->form_validation->set_rules('category', 'Category', 'trim|max_length[50]');
        $this->form_validation->set_rules('classApplied', 'Class Applying For', 'required');
        $this->form_validation->set_rules('academicYear', 'Academic Year', 'required');
        //$this->form_validation->set_rules('previousSchool', 'Previous School', 'trim|max_length[100]');
        //$this->form_validation->set_rules('guardianName', 'Parent/Guardian Name', 'trim|required|min_length[2]|max_length[100]');
        //$this->form_validation->set_rules('relation', 'Relation', 'required');
        $this->form_validation->set_rules('phone', 'Mobile Number', 'required|numeric|exact_length[10]');
        //$this->form_validation->set_rules('email', 'Email', 'trim|valid_email');
        //$this->form_validation->set_rules('altPhone', 'Alternate Phone', 'numeric|exact_length[10]');
        //$this->form_validation->set_rules('address', 'Address', 'trim|required|max_length[255]');
        //$this->form_validation->set_rules('city', 'City/Town', 'trim|required|min_length[2]|max_length[100]');
        //$this->form_validation->set_rules('pincode', 'PIN Code', 'required|numeric|exact_length[6]');

        $this->form_validation->set_rules('agree', 'Declaration', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('inc/header');
            $this->load->view('admission_form', $data);
            $this->load->view('inc/footer');
        } else {
            $upload_path = FCPATH . 'uploads/admission_form_file/';
            if (!is_dir($upload_path)) {
                mkdir($upload_path, 0777, TRUE);
            }

            $config['upload_path'] = $upload_path;
            $config['allowed_types'] = 'gif|jpg|jpeg|png|pdf';
            $config['max_size'] = 2048; // 2MB

            $this->load->library('upload', $config);

            $photo_name = '';
            $birth_cert_name = '';
            $transfer_cert_name = '';

            if (!empty($_FILES['photo']['name'])) {
                if ($this->upload->do_upload('photo')) {
                    $photo_data = $this->upload->data();
                    $photo_name = $photo_data['file_name'];
                } else {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', 'Photo upload failed: ' . $error);
                    redirect('home/online_application');
                }
            }

            if (!empty($_FILES['birthCert']['name'])) {
                if ($this->upload->do_upload('birthCert')) {
                    $birth_cert_data = $this->upload->data();
                    $birth_cert_name = $birth_cert_data['file_name'];
                } else {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', 'Birth Certificate upload failed: ' . $error);
                    redirect('home/online_application');
                }
            }

            if (!empty($_FILES['transferCert']['name'])) {
                if ($this->upload->do_upload('transferCert')) {
                    $transfer_cert_data = $this->upload->data();
                    $transfer_cert_name = $transfer_cert_data['file_name'];
                } else {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', 'Transfer Certificate upload failed: ' . $error);
                    redirect('home/online_application');
                }
            }

            $data = array(
                'firstName' => $this->input->post('firstName'),
                'lastName' => $this->input->post('lastName'),
                'dob' => $this->input->post('dob'),
                'gender' => $this->input->post('gender'),
                'bloodGroup' => $this->input->post('bloodGroup'),
                'category' => $this->input->post('category'),
                'classApplied' => $this->input->post('classApplied'),
                'academicYear' => $this->input->post('academicYear'),
                'previousSchool' => $this->input->post('previousSchool'),
                'guardianName' => $this->input->post('guardianName'),
                'relation' => $this->input->post('relation'),
                'phone' => $this->input->post('phone'),
                'email' => $this->input->post('email'),
                'altPhone' => $this->input->post('altPhone'),
                'address' => $this->input->post('address'),
                'city' => $this->input->post('city'),
                'pincode' => $this->input->post('pincode'),
                // 'agree' => $this->input->post('agree'),
                // 'student_photo' => $photo_name,
                // 'birth_certificate' => $birth_cert_name,
                // 'transfer_certificate' => $transfer_cert_name,
                // 'created_at' => $this->input->post('application_date')
            );

            if ($this->AdmissionModel->apply($data)) {
                $this->session->set_flashdata('success', 'Your application has been submitted successfully! We will contact you soon.');
            } else {
                $this->session->set_flashdata('error', 'There was an error submitting your application. Please try again.');
            }
            redirect('home/online_application');
        }
    }

    public function valid_dob($dob)
    {
        $date_parts = explode('-', $dob);
        if (count($date_parts) == 3 && checkdate($date_parts[1], $date_parts[2], $date_parts[0])) {
            $birth_date = new DateTime($dob);
            $today = new DateTime();
            if ($birth_date > $today) {
                $this->form_validation->set_message('valid_dob', 'The {field} must be a past date.');
                return FALSE;
            }
            return TRUE;
        } else {
            $this->form_validation->set_message('valid_dob', 'The {field} field must be a valid date.');
            return FALSE;
        }
    }
}
