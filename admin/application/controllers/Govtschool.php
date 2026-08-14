<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Govtschool extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('govtschool_model');
    }

    public function index()
    {
        if (!$this->rbac->hasPrivilege('govt_school', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Student Information');
        $this->session->set_userdata('sub_menu', 'govtschool/index');
        $data['title']          = 'Govt. School List';
        $data['govtschoollist'] = $this->govtschool_model->get();
        $this->load->view('layout/header', $data);
        $this->load->view('govtschool/govtschoolList', $data);
        $this->load->view('layout/footer', $data);
    }

    public function delete($id)
    {
        if (!$this->rbac->hasPrivilege('govt_school', 'can_delete')) {
            access_denied();
        }
        $this->govtschool_model->remove($id);
        $this->session->set_flashdata('msgdelete', '<div class="alert alert-success text-left">' . $this->lang->line('delete_message') . '</div>');
        redirect('govtschool/index');
    }

    public function create()
    {
        if (!$this->rbac->hasPrivilege('govt_school', 'can_add')) {
            access_denied();
        }
        $data['title']          = 'Add Govt. School';
        $data['govtschoollist'] = $this->govtschool_model->get();
        $this->form_validation->set_rules('name', 'Govt. School', 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {
            $this->load->view('layout/header', $data);
            $this->load->view('govtschool/govtschoolList', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $data = array(
                'name' => $this->input->post('name'),
            );
            $this->govtschool_model->add($data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>');
            redirect('govtschool/index');
        }
    }

    public function edit($id)
    {
        if (!$this->rbac->hasPrivilege('govt_school', 'can_edit')) {
            access_denied();
        }
        $data['title']          = 'Edit Govt. School';
        $data['govtschoollist'] = $this->govtschool_model->get();
        $data['id']             = $id;
        $data['govtschool']     = $this->govtschool_model->get($id);
        $this->form_validation->set_rules('name', 'Govt. School', 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {
            $this->load->view('layout/header', $data);
            $this->load->view('govtschool/govtschoolEdit', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $data = array(
                'id'   => $id,
                'name' => $this->input->post('name'),
            );
            $this->govtschool_model->add($data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('update_message') . '</div>');
            redirect('govtschool/index');
        }
    }

}
