<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Incomehead extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
    }

    public function index()
    {
        if (!$this->rbac->hasPrivilege('income_head', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Income');
        $this->session->set_userdata('sub_menu', 'incomeshead/index');
        $data['title']        = 'Income Head List';
        $category_result      = $this->incomehead_model->get();
        /* echo "<pre>";
        print_r($category_result);
        die; */
        $data['categorylist'] = $category_result;
        $data['incomeGroups'] = $this->incomehead_model->getIncomeGroups();
        $this->load->view('layout/header', $data);
        $this->load->view('admin/incomehead/incomeheadList', $data);
        $this->load->view('layout/footer', $data);
    }

    public function view($id)
    {
        if (!$this->rbac->hasPrivilege('income_head', 'can_view')) {
            access_denied();
        }
        $data['title']    = $this->lang->line('income_head_list');
        $category         = $this->incomehead_model->get($id);
        $data['category'] = $category;
        $data['incomeGroups'] = $this->incomehead_model->getIncomeGroups();
        $this->load->view('layout/header', $data);
        $this->load->view('admin/incomehead/incomeheadShow', $data);
        $this->load->view('layout/footer', $data);
    }

    public function delete($id)
    {
        if (!$this->rbac->hasPrivilege('income_head', 'can_delete')) {
            access_denied();
        }
        $this->incomehead_model->remove($id);
        redirect('admin/incomehead/index');
    }

    public function create()
    {
        if (!$this->rbac->hasPrivilege('income_head', 'can_add')) {
            access_denied();
        }
        $data['title']        = 'Add Income Head';
        $category_result      = $this->incomehead_model->get();
        $data['categorylist'] = $category_result;
        $data['incomeGroups'] = $this->incomehead_model->getIncomeGroups();
        $this->form_validation->set_rules('incomehead', $this->lang->line('income_head'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/incomehead/incomeheadList', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $data = array(
                'income_category' => $this->input->post('incomehead'),
                'group_id' => $this->input->post('group_id'),
                'description'     => $this->input->post('description'),
            );
            $this->incomehead_model->add($data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>');
            redirect('admin/incomehead/index');
        }
    }

    public function edit($id)
    {
        if (!$this->rbac->hasPrivilege('income_head', 'can_edit')) {
            access_denied();
        }
        $data['title']        = 'Edit Income Head';
        $data['incomeGroups'] = $this->incomehead_model->getIncomeGroups();
        $category_result      = $this->incomehead_model->get();
        $data['categorylist'] = $category_result;
        $data['id']           = $id;
        $category             = $this->incomehead_model->get($id);
        $data['incomehead']   = $category;
        $this->form_validation->set_rules('incomehead', $this->lang->line('income_head'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/incomehead/incomeheadEdit', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $data = array(
                'id'              => $id,
                'income_category' => $this->input->post('incomehead'),
                'group_id' => $this->input->post('group_id'),
                'description'     => $this->input->post('description'),
            );
            $this->incomehead_model->add($data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success">' . $this->lang->line('update_message') . '</div>');
            redirect('admin/incomehead/index');
        }
    }

    public function group_list()
    {
        if (!$this->rbac->hasPrivilege('income_group', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Incomes');
        $this->session->set_userdata('sub_menu', 'incomehead/group_list');

        $action = $this->input->get('action'); // Get the action parameter
        $id = $this->input->get('id'); // Get the id parameter for edit or delete

        if ($action === 'edit' && $id) {
            // Fetch data for editing
            $data['group_item'] = $this->incomehead_model->getGroupById($id);
        } elseif ($action === 'delete' && $id) {
            // Perform delete action
            if ($this->incomehead_model->deleteGroup($id)) {
                $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">Group deleted successfully</div>');
            } else {
                $this->session->set_flashdata('msg', '<div class="alert alert-danger text-left">Failed to delete group</div>');
            }
            redirect('admin/incomehead/group_list');
        }

        $this->form_validation->set_rules('title', 'Title', 'trim|required|xss_clean');

        if ($this->form_validation->run() == false) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/incomehead/incomegroupList', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $group_id = $this->input->post('group_id');
            if ($group_id != '') {
                $form_data = array(
                    'id'       => $group_id,
                    'title'       => $this->input->post('title'),
                    'descriptions' => $this->input->post('description'),
                );
                $this->incomehead_model->addGroup($form_data);
                $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">Group updated successfully</div>');
            } else {

                $form_data = array(
                    'title'       => $this->input->post('title'),
                    'descriptions' => $this->input->post('description'),
                );
                // Add new group item
                $this->incomehead_model->addGroup($form_data);
                $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">Group added successfully</div>');
            }

            redirect('admin/incomehead/group_list');
        }
    }

    public function ajaxGroupSearch()
    {
        $income_head = $this->incomehead_model->getDatatableIncomeGroup();
        $income_head = json_decode($income_head);
        $dt_data      = array();

        if (!empty($income_head->data)) {

            foreach ($income_head->data as $exhead_key => $exhead_value) {
                $action = "";
                if ($this->rbac->hasPrivilege('income_head', 'can_edit')) {
                    $action .= "<a href='" . site_url('admin/incomehead/group_list/?action=edit&id=' . $exhead_value->id) . "' class='btn btn-default btn-xs'  data-toggle='tooltip' title='" . $this->lang->line('edit') . "'><i class='fa fa-pencil'></i></a>";
                }
                if ($this->rbac->hasPrivilege('income_head', 'can_delete')) {
                    $action .= "<a href='" . site_url('admin/incomehead/group_list/?action=delete&id=' . $exhead_value->id) . "' class='btn btn-default btn-xs'  data-toggle='tooltip' title='" . $this->lang->line('delete') . "' onclick='return confirm(" . '"' . $this->lang->line('delete_confirm') . '"' . ");'><i class='fa fa-remove'></i></a>";
                }
                $row           = array();

                $row[]     = $exhead_value->title;
                $row[]     = $exhead_value->descriptions;
                $row[]     = $action;
                $dt_data[] = $row;
            }
        }
        $json_data = array(
            "draw"            => intval($income_head->draw),
            "recordsTotal"    => intval($income_head->recordsTotal),
            "recordsFiltered" => intval($income_head->recordsFiltered),
            "data"            => $dt_data,
        );
        echo json_encode($json_data);
    }
}
