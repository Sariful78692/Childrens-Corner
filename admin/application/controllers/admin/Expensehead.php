<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Expensehead extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        if (!$this->rbac->hasPrivilege('expense_head', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Expenses');
        $this->session->set_userdata('sub_menu', 'expenseshead/index');
        $data['title'] = 'Expense Head List';
        $data['expenseGroups'] = $this->expensehead_model->getExpenseGroups();
        $this->load->view('layout/header', $data);
        $this->load->view('admin/expensehead/expenseheadList', $data);
        $this->load->view('layout/footer', $data);
    }

    public function group_list()
    {
        if (!$this->rbac->hasPrivilege('expense_group', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Expenses');
        $this->session->set_userdata('sub_menu', 'expensehead/group_list');

        $action = $this->input->get('action'); // Get the action parameter
        $id = $this->input->get('id'); // Get the id parameter for edit or delete

        if ($action === 'edit' && $id) {
            // Fetch data for editing
            $data['group_item'] = $this->expensehead_model->getGroupById($id);
        } elseif ($action === 'delete' && $id) {
            // Perform delete action
            if ($this->expensehead_model->deleteGroup($id)) {
                $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">Group deleted successfully</div>');
            } else {
                $this->session->set_flashdata('msg', '<div class="alert alert-danger text-left">Failed to delete group</div>');
            }
            redirect('admin/expensehead/group_list');
        }

        $this->form_validation->set_rules('title', 'Title', 'trim|required|xss_clean');

        if ($this->form_validation->run() == false) {
            $data['title'] = 'Expense Head List';
            $data['expenseGroups'] = $this->expensehead_model->getExpenseGroups();
            $this->load->view('layout/header', $data);
            $this->load->view('admin/expensehead/expensegroupList', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $group_id = $this->input->post('group_id');
            if ($group_id != '') {
                $form_data = array(
                    'id'       => $group_id,
                    'title'       => $this->input->post('title'),
                    'descriptions' => $this->input->post('description'),
                );
                $this->expensehead_model->addGroup($form_data);
                $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">Group updated successfully</div>');
            } else {

                $form_data = array(
                    'title'       => $this->input->post('title'),
                    'descriptions' => $this->input->post('description'),
                );
                // Add new group item
                $this->expensehead_model->addGroup($form_data);
                $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">Group added successfully</div>');
            }

            redirect('admin/expensehead/group_list');
        }
    }

    public function ajaxGroupSearch()
    {
        $expense_head = $this->expensehead_model->getDatatableExpenseGroup();
        $expense_head = json_decode($expense_head);
        $dt_data      = array();

        if (!empty($expense_head->data)) {

            foreach ($expense_head->data as $exhead_key => $exhead_value) {
                $action = "";
                if ($this->rbac->hasPrivilege('expense_head', 'can_edit')) {
                    $action .= "<a href='" . site_url('admin/expensehead/group_list/?action=edit&id=' . $exhead_value->id) . "' class='btn btn-default btn-xs'  data-toggle='tooltip' title='" . $this->lang->line('edit') . "'><i class='fa fa-pencil'></i></a>";
                }
                if ($this->rbac->hasPrivilege('expense_head', 'can_delete')) {
                    $action .= "<a href='" . site_url('admin/expensehead/group_list/?action=delete&id=' . $exhead_value->id) . "' class='btn btn-default btn-xs'  data-toggle='tooltip' title='" . $this->lang->line('delete') . "' onclick='return confirm(" . '"' . $this->lang->line('delete_confirm') . '"' . ");'><i class='fa fa-remove'></i></a>";
                }
                $row           = array();

                $row[]     = $exhead_value->title;
                $row[]     = $exhead_value->descriptions;
                $row[]     = $action;
                $dt_data[] = $row;
            }
        }
        $json_data = array(
            "draw"            => intval($expense_head->draw),
            "recordsTotal"    => intval($expense_head->recordsTotal),
            "recordsFiltered" => intval($expense_head->recordsFiltered),
            "data"            => $dt_data,
        );
        echo json_encode($json_data);
    }

    public function ajaxSearch()
    {
        $expense_head = $this->expensehead_model->getDatatableExpenseHead();
        $expense_head = json_decode($expense_head);
        $dt_data      = array();

        if (!empty($expense_head->data)) {

            foreach ($expense_head->data as $exhead_key => $exhead_value) {
                $action = "";
                if ($this->rbac->hasPrivilege('expense_head', 'can_edit')) {
                    $action .= "<a href='" . site_url('admin/expensehead/edit/' . $exhead_value->id) . "' class='btn btn-default btn-xs'  data-toggle='tooltip' title='" . $this->lang->line('edit') . "'><i class='fa fa-pencil'></i></a>";
                }
                if ($this->rbac->hasPrivilege('expense_head', 'can_delete')) {
                    $action .= "<a href='" . site_url('admin/expensehead/delete/' . $exhead_value->id) . "' class='btn btn-default btn-xs'  data-toggle='tooltip' title='" . $this->lang->line('delete') . "' onclick='return confirm(" . '"' . $this->lang->line('delete_confirm') . '"' . ");'><i class='fa fa-remove'></i></a>";
                }
                $row           = array();

                $title = "<a href='#' data-toggle='popover' class='detail_popover'>" . $exhead_value->exp_category . "</a>";

                $row[]     = $title;
                $row[]     = $exhead_value->group_title;
                $row[]     = $exhead_value->description;
                $row[]     = $action;
                $dt_data[] = $row;
            }
        }
        $json_data = array(
            "draw"            => intval($expense_head->draw),
            "recordsTotal"    => intval($expense_head->recordsTotal),
            "recordsFiltered" => intval($expense_head->recordsFiltered),
            "data"            => $dt_data,
        );
        echo json_encode($json_data);
    }

    public function view($id)
    {
        if (!$this->rbac->hasPrivilege('expense_head', 'can_view')) {
            access_denied();
        }
        $data['title']    = $this->lang->line('expense_head_list');
        $category         = $this->expensehead_model->get($id);
        $data['category'] = $category;
        $this->load->view('layout/header', $data);
        $this->load->view('admin/expensehead/expenseheadShow', $data);
        $this->load->view('layout/footer', $data);
    }

    public function delete($id)
    {
        if (!$this->rbac->hasPrivilege('expense_head', 'can_delete')) {
            access_denied();
        }
        $data['title'] = 'Expense Head List';
        $this->expensehead_model->remove($id);
        redirect('admin/expensehead/index');
    }

    public function create()
    {
        if (!$this->rbac->hasPrivilege('expense_head', 'can_add')) {
            access_denied();
        }

        $data['expenseGroups'] = $this->expensehead_model->getExpenseGroups();
        $category_result      = $this->expensehead_model->get();
        $data['categorylist'] = $category_result;
        $this->form_validation->set_rules('expensehead', $this->lang->line('expense_head'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('expense_group', "Expense Group", 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/expensehead/expenseheadList', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $data = array(
                'exp_category' => $this->input->post('expensehead'),
                'group_id' => $this->input->post('expense_group'),
                'description'  => $this->input->post('description'),
            );
            $this->expensehead_model->add($data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>');
            redirect('admin/expensehead/index');
        }
    }

    public function edit($id)
    {
        if (!$this->rbac->hasPrivilege('expense', 'can_edit')) {
            access_denied();
        }

        $data['expenseGroups'] = $this->expensehead_model->getExpenseGroups();
        $data['title']        = 'Edit Expense Head';
        $category_result      = $this->expensehead_model->get();
        $data['categorylist'] = $category_result;
        $data['id']           = $id;
        $category             = $this->expensehead_model->get($id);
        $data['expensehead']  = $category;
        $this->form_validation->set_rules('expensehead', $this->lang->line('expense_head'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('group_id', "Expense Group", 'trim|required|xss_clean');

        if ($this->form_validation->run() == false) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/expensehead/expenseheadEdit', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $data = array(
                'id'           => $id,
                'exp_category' => $this->input->post('expensehead'),
                'group_id' => $this->input->post('group_id'),
                'description'  => $this->input->post('description'),
            );
            $this->expensehead_model->add($data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success">' . $this->lang->line('update_message') . '</div>');
            redirect('admin/expensehead/index');
        }
    }
}
