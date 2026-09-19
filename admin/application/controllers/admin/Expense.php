<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Expense extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('Customlib');
        $this->load->library('media_storage');
        $this->config->load('app-config');
        $this->load->library("datatables");
        $this->load->model("accounts_model");
    }

    public function index()
    {
        if (!$this->rbac->hasPrivilege('expense', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Expenses');
        $this->load->model('account_department_model');
        $data['account_departments'] = $this->account_department_model->get();

        $this->load->model('account_department_model');
        $data['account_departments'] = $this->account_department_model->get();

        $this->session->set_userdata('sub_menu', 'expense/index');

        $data['title']      = 'Add Expense';
        $data['title_list'] = 'Recent Expenses';

        // Form Validation Rules
        $this->form_validation->set_rules('exp_head_id', $this->lang->line('expense_head'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('account_department_id', "Account Department", 'trim|required|numeric|xss_clean');
        $this->form_validation->set_rules('amount', $this->lang->line('amount'), 'trim|required|numeric|xss_clean');
        $this->form_validation->set_rules('name', $this->lang->line('name'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('date', $this->lang->line('date'), 'trim|required|callback_valid_expense_date|xss_clean');
        $this->form_validation->set_rules('documents', $this->lang->line('documents'), 'callback_handle_upload');

        if ($this->form_validation->run()) {
            $img_name = $this->media_storage->fileupload("documents", "./uploads/school_expense/");

            $trans_date = date('Y-m-d H:i:s');
            $admin_data = $this->session->userdata('admin');

            $expense_date = $this->parseExpenseDate($this->input->post('date'));

            $data = [
                'exp_head_id'       => $this->input->post('exp_head_id'),
                'account_department_id' => $this->input->post('account_department_id'),
                'name'              => $this->input->post('name'),
                'account_department_id' => $this->input->post('account_department_id'),
                'date'              => $expense_date,
                'amount'            => convertCurrencyFormatToBaseAmount($this->input->post('amount')),
                'supplier_id'       => $this->input->post('supplier_id'),
                'payment_method_id' => $this->input->post('payment_method_id'),
                'invoice_no'        => $this->input->post('invoice_no'),
                'note'              => $this->input->post('description'),
                'created_by'        => $admin_data['id'],
                'documents'         => $img_name,
                'created_at'        => $trans_date,
            ];

            // Add expense
            $expense_id = $this->expense_model->add($data);

            if ($expense_id) {
                // Remove funds
                $expense_title = get_expense_title($data['exp_head_id']);
                $note = 'Paid to ' . $data['name'] . ' for ' . $expense_title;

                $funds_removed = $this->accounts_model->remove_funds(
                    $data['amount'],
                    $data['payment_method_id'],
                    $note,
                    $expense_date,
                    "expenses",
                    $expense_id
                );

                if ($funds_removed) {
                    log_expenses(array_merge(['id' => $expense_id], $data), 1, $admin_data['id'], $admin_data['username']);

                    $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>');
                } else {
                    $this->session->set_flashdata('msg', '<div class="alert alert-danger text-left">Error in removing funds.</div>');
                }
            }

            redirect('admin/expense/index');
        }

        $data['paymentMethods'] = $this->accounts_model->getPaymentMethods();
        $data['expenselist']    = $this->expense_model->get();
        $data['expheadlist']    = $this->expensehead_model->get();
        $data['suppliers']      = $this->expense_model->get_suppliers();

        $this->load->view('layout/header', $data);
        $this->load->view('admin/expense/expenseList', $data);
        $this->load->view('layout/footer', $data);
    }


    public function refund($expense_id)
    {
        if (!$this->rbac->hasPrivilege('expense', 'can_delete') || !$this->rbac->hasPrivilege('expense', 'can_edit')) {
            access_denied();
        }

        $expense = $this->expense_model->get($expense_id);
        $refund_date = $this->parseExpenseDate($this->input->post('refund_date'));
        $refund_note = trim($this->input->post('refund_note'));
        $payment_date = isset($expense['date']) ? substr($expense['date'], 0, 10) : null;
        $payment_date_display = $this->formatExpenseDate($payment_date);
        $today = date('Y-m-d');

        if (empty($expense) || $expense['is_refunded'] == 1 || $refund_date === null || $refund_note === '' || $payment_date_display === '' || $refund_date < $payment_date || $refund_date > $today) {
            $this->session->set_flashdata('msg', '<div class="alert alert-danger text-left">The refund date must be between the original payment date and today.</div>');
            redirect('admin/expense/index');
        }

        $data = array(
            'id'          => $expense_id,
            'is_refunded' => 1,
            'refund_date' => $refund_date,
            'refund_note' => $refund_note
        );

        if ($this->expense_model->add($data)) {

            $amount = $expense['amount'];
            $payment_method_id = $expense['payment_method_id'];
            $note = 'Expense refunded from ' . $expense['name'];
            $note .= "<br>Refund Note: $refund_note";
            //$trans_date = date('Y-m-d H:i:s');

            $current_user_id = $this->session->userdata['admin']['id'];
            $current_user_name = $this->session->userdata['admin']['username'];

            $refund = $this->accounts_model->add_funds($amount, $payment_method_id, $note, $refund_date, "expenses", $expense_id);
            if ($refund) {
                $log_data = $expense;

                $action = 4; //Refund
                $user_id = $current_user_id;
                $user_name = $current_user_name;
                log_expenses($log_data, $action, $user_id, $user_name);
            }
        }

        $this->session->set_flashdata('msg', '<div class="alert alert-danger text-left">Refund successfully!</div>');
        redirect('admin/expense/index');
    }


    public function download($id)
    {
        $result = $this->expense_model->get($id);
        $this->media_storage->filedownload($result['documents'], "./uploads/school_expense");
    }

    public function handle_upload()
    {
        $image_validate = $this->config->item('file_validate');
        $result         = $this->filetype_model->get();
        if (isset($_FILES["documents"]) && !empty($_FILES['documents']['name'])) {
            $file_type         = $_FILES["documents"]['type'];
            $file_size         = $_FILES["documents"]["size"];
            $file_name         = $_FILES["documents"]["name"];
            $allowed_extension = array_map('trim', array_map('strtolower', explode(',', $result->file_extension)));
            $allowed_mime_type = array_map('trim', array_map('strtolower', explode(',', $result->file_mime)));
            $ext               = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

            if ($files = filesize($_FILES['documents']['tmp_name'])) {

                if (!in_array($file_type, $allowed_mime_type)) {
                    $this->form_validation->set_message('handle_upload', 'File Type Not Allowed');
                    return false;
                }
                if (!in_array($ext, $allowed_extension) || !in_array($file_type, $allowed_mime_type)) {
                    $this->form_validation->set_message('handle_upload', 'Extension Not Allowed');
                    return false;
                }
                if ($file_size > $result->file_size) {
                    $this->form_validation->set_message('handle_upload', $this->lang->line('file_size_shoud_be_less_than') . number_format($result->file_size / 1048576, 2) . " MB");
                    return false;
                }
            } else {
                $this->form_validation->set_message('handle_upload', "File Type / Extension Error Uploading  Image");
                return false;
            }

            return true;
        }
        return true;
    }

    public function view($id)
    {
        if (!$this->rbac->hasPrivilege('expense', 'can_view')) {
            access_denied();
        }
        $data['title']   = 'Fees Master List';
        $expense         = $this->expense_model->get($id);
        $data['expense'] = $expense;
        $data['expense_date'] = $this->formatExpenseDate($expense['date']);
        $this->load->view('layout/header', $data);
        $this->load->view('expense/expenseShow', $data);
        $this->load->view('layout/footer', $data);
    }

    public function getByFeecategory()
    {
        $feecategory_id = $this->input->get('feecategory_id');
        $data           = $this->feetype_model->getTypeByFeecategory($feecategory_id);
        echo json_encode($data);
    }

    public function getStudentCategoryFee()
    {
        $type     = $this->input->post('type');
        $class_id = $this->input->post('class_id');
        $data     = $this->expense_model->getTypeByFeecategory($type, $class_id);
        if (empty($data)) {
            $status = 'fail';
        } else {
            $status = 'success';
        }
        $array = array('status' => $status, 'data' => $data);
        echo json_encode($array);
    }

    public function delete($id)
    {
        if (!$this->rbac->hasPrivilege('expense', 'can_delete')) {
            access_denied();
        }

        $row = $this->expense_model->get($id);
        if ($row['documents'] != '') {
            $this->media_storage->filedelete($row['documents'], "uploads/school_expense/");
        }

        $this->expense_model->remove($id);
        redirect('admin/expense/index');
    }

    public function create()
    {
        if (!$this->rbac->hasPrivilege('expense', 'can_add')) {
            access_denied();
        }
        $data['title'] = 'Add Fees Master';
        $this->form_validation->set_rules('expense', $this->lang->line('fees_master'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {
            $this->load->view('layout/header', $data);
            $this->load->view('expense/expenseCreate', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $data = array(
                'expense' => $this->input->post('expense'),
            );
            $this->expense_model->add($data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>');
            redirect('expense/index');
        }
    }

    public function edit($id)
    {
        $current_user_id = $this->session->userdata['admin']['id'];
        $current_user_name = $this->session->userdata['admin']['username'];

        if (!$this->rbac->hasPrivilege('expense', 'can_edit')) {
            access_denied();
        }

        $data['paymentMethods'] = $this->accounts_model->getPaymentMethods();
        $data['id']      = $id;
        $this->load->model('account_department_model');
        $data['account_departments'] = $this->account_department_model->get();
        $this->load->model('account_department_model');
        $data['account_departments'] = $this->account_department_model->get();
        $expense         = $this->expense_model->get($id);
        $data['expense'] = $expense;
        $data['expense_date'] = $this->formatExpenseDate($expense['date']);
        $expense_result      = $this->expense_model->get();
        $data['expenselist'] = $expense_result;
        $expnseHead          = $this->expensehead_model->get();
        $data['expheadlist'] = $expnseHead;
        $data['suppliers']      = $this->expense_model->get_suppliers();

        $this->form_validation->set_rules('exp_head_id', $this->lang->line('expense_head'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('documents', $this->lang->line('documents'), 'callback_handle_upload');
        $this->form_validation->set_rules('amount', $this->lang->line('amount'), 'trim|required|numeric|xss_clean');
        $this->form_validation->set_rules('name', $this->lang->line('name'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('date', $this->lang->line('date'), 'trim|required|callback_valid_expense_date|xss_clean');
        if ($this->form_validation->run() == false) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/expense/expenseEdit', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $expense_date = $this->parseExpenseDate($this->input->post('date'));
            $data = array(
                'id'          => $id,
                'exp_head_id' => $this->input->post('exp_head_id'),
                'account_department_id' => $this->input->post('account_department_id'),
                'supplier_id'       => $this->input->post('supplier_id'),
                'name'        => $this->input->post('name'),
                'invoice_no'  => $this->input->post('invoice_no'),
                'date'        => $expense_date,
                'amount'      => convertCurrencyFormatToBaseAmount($this->input->post('amount')),
                'payment_method_id'  => $this->input->post('payment_method_id'),
                'note'        => $this->input->post('description'),
            );

            if (isset($_FILES["documents"]) && $_FILES['documents']['name'] != '' && (!empty($_FILES['documents']['name']))) {

                $img_name = $this->media_storage->fileupload("documents", "./uploads/school_expense/");
            } else {
                $img_name = $expense['documents'];
            }


            $data['documents'] = $img_name;

            if (isset($_FILES["documents"]) && $_FILES['documents']['name'] != '' && (!empty($_FILES['documents']['name']))) {
                $this->media_storage->filedelete($expense['documents'], "uploads/school_expense");
            }

            $expense_id = $this->expense_model->add($data);

            if ($expense_id) {
                $id = $this->input->post('exp_head_id');
                $expense_title = get_expense_title($id);

                $amount = $this->input->post('amount');
                $payment_method_id = $this->input->post('payment_method_id');
                $note = 'Paid to ' . $this->input->post('name') . ' for ' . $expense_title;

                $trans_data = array(
                    'amount' => $amount,
                    'payment_method_id' => $payment_method_id,
                    'descriptions' => $note,
                    'trans_date' => $expense_date,
                    'trans_by' => $current_user_id
                );

                $addExpense = $this->accounts_model->update_transaction($expense_id, "expenses", $trans_data);

                if ($addExpense) {
                    $log_data = array_merge(array('id' => $expense_id), $data);

                    $action = 2; //Updating
                    $user_id = $current_user_id;
                    $user_name = $current_user_name;
                    log_expenses($log_data, $action, $user_id, $user_name);
                }
            }

            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('update_message') . '</div>');
            redirect('admin/expense/index');
        }
    }

    public function expenseSearch()
    {
        if (!$this->rbac->hasPrivilege('search_expense', 'can_view')) {
            access_denied();
        }
        $data['searchlist']  = $this->customlib->get_searchtype();
        $data['search_type'] = '';
        $this->session->set_userdata('top_menu', 'Expenses');
        $this->session->set_userdata('sub_menu', 'expense/expensesearch');
        $data['title'] = 'Search Expense';
        $this->load->view('layout/header', $data);
        $this->load->view('admin/expense/expenseSearch', $data);
        $this->load->view('layout/footer', $data);
    }
    public function getexpenselist()
    {
        $m               = $this->expense_model->getexpenselist();
        $m               = json_decode($m);
        $currency_symbol = $this->customlib->getSchoolCurrencyFormat();
        $dt_data         = array();
        if (!empty($m->data)) {
            foreach ($m->data as $key => $value) {
                $editbtn   = '';
                $deletebtn = '';
                $documents = '';

                if ($this->rbac->hasPrivilege('expense', 'can_edit')) {
                    $editbtn = "<a href='" . base_url() . "admin/expense/edit/" . $value->id . "'   class='btn btn-default btn-xs'  data-toggle='tooltip' title='" . $this->lang->line('edit') . "'><i class='fa fa-pencil'></i></a>";
                }
                /* if ($this->rbac->hasPrivilege('expense', 'can_delete')) {
                    $deletebtn = '';
                    $deletebtn = "<a onclick='return confirm(" . '"' . $this->lang->line('delete_confirm') . '"' . ");' href='" . base_url() . "admin/expense/delete/" . $value->id . "' class='btn btn-default btn-xs' title='" . $this->lang->line('delete') . "' data-toggle='tooltip'><i class='fa fa-trash'></i></a>";
                } */

                if ($this->rbac->hasPrivilege('expense', 'can_delete') || $this->rbac->hasPrivilege('expense', 'can_edit')) {
                    $deletebtn = '';
                    if ($value->is_refunded == 1) {
                        $deletebtn = '<span class="badge">Refunded</span>';
                        $deletebtn .= '<p>' . $value->refund_note . ' on ' . $this->formatExpenseDate($value->refund_date) . '</p>';
                    } else {
                        //$deletebtn = "<a onclick='return confirm(" . '"Want to refund the amount?"' . "  )' href='" . base_url() . "admin/expense/refund/" . $value->id . "' class='btn btn-default btn-xs' title='Refund' data-toggle='tooltip'><i class='fa fa-undo'></i></a>";

                        $deletebtn = "<a href='javascript:void(0);' class='btn btn-default btn-xs refund-button' data-refund-id='" . $value->id . "' data-url='" . base_url() . "admin/expense/refund/" . $value->id . "' data-payment-date='" . substr($value->date, 0, 10) . "' data-payment-date-display='" . $this->formatExpenseDate($value->date) . "' title='Refund' data-toggle='tooltip'><i class='fa fa-undo'></i></a>";
                    }
                }

                if ($value->documents) {
                    $documents = "<a href='" . base_url() . "admin/expense/download/" . $value->id . "' class='btn btn-default btn-xs'  data-toggle='tooltip' title='" . $this->lang->line('download') . "'>
                         <i class='fa fa-download'></i> </a>";
                }
                $row   = array();
                $row[] = $value->id;
                $row[] = $value->name;
                $row[] = $value->supplier_name ?? 'Cash';

                if ($value->note == "") {
                    $row[] = $this->lang->line('no_description');
                } else {
                    $row[] = $value->note;
                }

                $row[]     = $value->invoice_no;
                $row[]     = $this->formatExpenseDate($value->date);
                $row[]     = $value->exp_category;
                $row[]     = $value->account_department_name;
                $row[]     = $currency_symbol . amountFormat($value->amount);
                $row[]     = get_payment_mode($value->payment_method_id);
                $row[]      = $value->made_by;
                $row[]     = $documents . ' ' . $editbtn . ' ' . $deletebtn;
                $dt_data[] = $row;
            }
        }

        $json_data = array(
            "draw"            => intval($m->draw),
            "recordsTotal"    => intval($m->recordsTotal),
            "recordsFiltered" => intval($m->recordsFiltered),
            "data"            => $dt_data,
        );
        echo json_encode($json_data);
    }

    /*-----------------function to check search validation for admission report ---*/

    public function search()
    {
        /*$button_type = $this->input->post('button_type');
         if ($button_type == "search_filter") {
            $this->form_validation->set_rules('search_type', $this->lang->line('search_type'), 'required|trim|xss_clean');
        } elseif ($button_type == "search_full") {
            $this->form_validation->set_rules('search_text', $this->lang->line('keyword'), 'required|trim|xss_clean');
        } */
        /* if ($this->form_validation->run() == false) {
            $error = array();
            if ($button_type == "search_filter") {
                $error['search_type'] = form_error('search_type');
            } elseif ($button_type == "search_full") {
                $error['search_text'] = form_error('search_text');
            }

            $array = array('status' => 0, 'error' => $error);
            echo json_encode($array);
        } else { */
        $button_type = $this->input->post('button_type');
        $search_text = $this->input->post('search_text');
        $date_from   = "";
        $date_to     = "";

        $search_type = $this->input->post('search_type');
        if ($search_type == 'period') {
            $date_from = $this->input->post('date_from');
            $date_to   = $this->input->post('date_to');
        }

        $button_type = ($button_type != "") ? $button_type : "search_filter";
        //$button_type = $button_type ?? "search_filter";

        $params = array('button_type' => $button_type, 'search_type' => $search_type, 'search_text' => $search_text, 'date_from' => $date_from, 'date_to' => $date_to);
        $array  = array('status' => 1, 'error' => '', 'params' => $params);
        echo json_encode($array);
        //}
    }

    public function getsearchexpenselist()
    {
        $search_type = $this->input->post('search_type');
        $button_type = $this->input->post('button_type');
        $search_text = $this->input->post('search_text');

        if ($button_type == 'search_filter') {
            if ($search_type != "") {

                if ($search_type == 'all') {

                    $dates = $this->customlib->get_betweendate('this_year');
                } else {
                    $dates = $this->customlib->get_betweendate($search_type);
                }
            } else {
                $dates       = $this->customlib->get_betweendate('this_year');
                $search_type = '';
            }

            $dateformat        = $this->customlib->getSchoolDateFormat();
            $date_from         = date('Y-m-d', strtotime($dates['from_date']));
            $date_to           = date('Y-m-d', strtotime($dates['to_date']));
            $data['exp_title'] = 'Expense Result From ' . date($dateformat, strtotime($date_from)) . " To " . date($dateformat, strtotime($date_to));
            $date_from         = date('Y-m-d', $this->customlib->dateYYYYMMDDtoStrtotime($date_from));
            $date_to           = date('Y-m-d', $this->customlib->dateYYYYMMDDtoStrtotime($date_to));
            $resultList        = $this->expense_model->search("", $date_from, $date_to);
        } else {

            $search_text = $this->input->post('search_text');
            $resultList  = $this->expense_model->search($search_text, "", "");
            $resultList  = $resultList;
        }

        $m               = json_decode($resultList);
        $currency_symbol = $this->customlib->getSchoolCurrencyFormat();
        $dt_data         = array();
        $grand_total     = 0;
        $current_user_id = $this->session->userdata['admin']['id'];
        if (!empty($m->data)) {
            foreach ($m->data as $key => $value) {
                $editbtn   = '';
                $deletebtn = '';
                $refundBtn = '';
                $documents = '';

                if ($this->rbac->hasPrivilege('expense', 'can_edit') && $current_user_id == 1 && $value->is_refunded != 1) {
                    $editbtn = "<a href='" . base_url() . "admin/expense/edit/" . $value->id . "'   class='btn btn-default btn-xs'  data-toggle='tooltip' title='" . $this->lang->line('edit') . "'><i class='fa fa-pencil'></i></a>";
                }
                /* if ($this->rbac->hasPrivilege('expense', 'can_delete')) {
                    $deletebtn = '';
                    $deletebtn = "<a onclick='return confirm(" . '"' . $this->lang->line('delete_confirm') . '"' . ");' href='" . base_url() . "admin/expense/delete/" . $value->id . "' class='btn btn-default btn-xs' title='" . $this->lang->line('delete') . "' data-toggle='tooltip'><i class='fa fa-trash'></i></a>";
                } */

                /* if ($value->is_refunded == 1) {
                    $refundBtn = '<span class="badge">Refunded</span>';
                } else {
                    $refundBtn = "<a onclick='return confirm(" . '"Want to refund the amount?"' . "  )' href='" . base_url() . "admin/expense/refund/" . $value->id . "' class='btn btn-default btn-xs' title='Refund' data-toggle='tooltip'><i class='fa fa-undo'></i></a>";
                } */

                if ($this->rbac->hasPrivilege('expense', 'can_delete') || $this->rbac->hasPrivilege('expense', 'can_edit')) {
                    $deletebtn = '';
                    if ($value->is_refunded == 1) {
                        $deletebtn = '<span class="badge">Refunded</span>';
                        $deletebtn .= '<p>' . $value->refund_note . ' on ' . $value->refund_date . '</p>';
                    } else {
                        //$deletebtn = "<a onclick='return confirm(" . '"Want to refund the amount?"' . "  )' href='" . base_url() . "admin/expense/refund/" . $value->id . "' class='btn btn-default btn-xs' title='Refund' data-toggle='tooltip'><i class='fa fa-undo'></i></a>";

                        $deletebtn = "<a href='javascript:void(0);' class='btn btn-default btn-xs refund-button' data-refund-id='" . $value->id . "' data-url='" . base_url() . "admin/expense/refund/" . $value->id . "' title='Refund' data-toggle='tooltip'><i class='fa fa-undo'></i></a>";
                    }
                }


                if ($value->documents) {
                    $documents = "<a href='" . base_url() . "admin/expense/download/" . $value->id . "' class='btn btn-default btn-xs'  data-toggle='tooltip' title='" . $this->lang->line('download') . "'>
                         <i class='fa fa-download'></i> </a>";
                }

                $is_refunded = $value->is_refunded;
                if ($is_refunded != 1) {
                    $grand_total += $value->amount;
                }
                $row   = array();
                $row[] = $value->id;
                $row[] = $this->formatExpenseDate($value->date);
                $row[] = $value->name;
                $row[] = $value->invoice_no;
                $row[] = $value->exp_category;
                $row[] = $value->account_department_name;
                $row[] = $value->note;
                $row[]     = get_payment_mode($value->payment_method_id);
                $row[]     = $currency_symbol . amountFormat($value->amount);
                $row[]     = $value->made_by;
                //$row[]     = $is_refunded ? "<span class='badge'>Refunded</span>" : "";
                $row[]     = $documents . ' ' . $editbtn . ' ' . $deletebtn . ' ' . $refundBtn;

                $dt_data[] = $row;
            }

            $footer_row[] = "";
            $footer_row[] = "";
            $footer_row[] = "";
            $footer_row[] = "";
            $footer_row[] = "";
            $footer_row[] = "";
            $footer_row[] = "";
            $footer_row[] = "";
            $footer_row[] = "<b style='font-weight:normal'>" . $this->lang->line('grand_total') . " :  " . ($currency_symbol . amountFormat($grand_total)) . "</b>";
            $footer_row[] = "";
            $footer_row[] = "";
            $dt_data[]    = $footer_row;
        }

        $json_data = array(
            "draw"            => intval($m->draw),
            "recordsTotal"    => intval($m->recordsTotal),
            "recordsFiltered" => intval($m->recordsFiltered),
            "data"            => $dt_data,
        );
        echo json_encode($json_data);
    }

    public function suppliers($id = null, $action = null)
    {
        if (!$this->rbac->hasPrivilege('suppliers', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Expenses');
        $this->session->set_userdata('sub_menu', 'expense/suppliers');
        $data['title'] = 'Manage Suppliers';
        $data['title_list'] = 'Recent Suppliers';

        // Handle DELETE request
        if ($action === 'delete' && !empty($id)) {
            if (!$this->rbac->hasPrivilege('suppliers', 'can_delete')) {
                access_denied();
            }

            $this->db->where('id', $id);
            $this->db->delete('suppliers');

            $this->session->set_flashdata('msg', '<div class="alert alert-success">Supplier deleted successfully.</div>');
            redirect('admin/expense/suppliers');
        }

        // Fetch supplier data if ID is provided (for editing)
        if (!empty($id)) {
            $data['supplier_data'] = $this->expense_model->get_supplier_by_id($id);
        }

        // Form validation
        $this->form_validation->set_rules('name', 'Name', 'trim|required|xss_clean');

        if ($this->form_validation->run() == false) {
            // If validation fails, reload form
        } else {
            $current_user_id = $this->session->userdata['admin']['id'];

            $supplier_data = [
                'name'       => $this->input->post('name'),
                'address'    => $this->input->post('address'),
                'phone'      => $this->input->post('phone'),
                'created_by' => $current_user_id, // Set created_by field
            ];

            // Check if it's an update or a new insert
            if (!empty($id) && $this->expense_model->get_supplier_by_id($id)) {
                // Update supplier
                $this->expense_model->update_supplier($id, $supplier_data);
                $this->session->set_flashdata('msg', '<div class="alert alert-success">Supplier updated successfully.</div>');
            } else {
                // Add new supplier
                $supplier_data['created_at'] = date('Y-m-d H:i:s'); // Add timestamp for new entries
                $this->expense_model->add_supplier($supplier_data);
                $this->session->set_flashdata('msg', '<div class="alert alert-success">Supplier added successfully.</div>');
            }

            redirect('admin/expense/suppliers');
        }

        // Get suppliers list
        $data['suppliers'] = $this->expense_model->get_suppliers();

        // Load the view
        $this->load->view('layout/header', $data);
        $this->load->view('admin/expense/suppliers', $data);
        $this->load->view('layout/footer', $data);
    }
    public function purchase_order($id = null, $action = null)
    {
        $this->session->set_userdata('top_menu', 'Expenses');
        $this->session->set_userdata('sub_menu', 'expense/purchase_order');
        $data['title'] = 'Manage Purchase Orders';
        $data['title_list'] = 'Recent Purchase Orders';

        // Handle DELETE request
        if ($action === 'delete' && !empty($id)) {
            if (!$this->rbac->hasPrivilege('purchase_order', 'can_delete')) {
                access_denied();
            }
            $this->expense_model->delete_purchase_order($id);
            $this->session->set_flashdata('msg', '<div class="alert alert-success">Purchase Order deleted successfully.</div>');
            redirect('admin/expense/purchase_order');
        }

        // Fetch purchase order data if ID is provided (for editing)
        if (!empty($id)) {
            $data['purchase_data'] = $this->expense_model->get_purchase_order_by_id($id);
        }

        // Form validation
        $this->form_validation->set_rules('supplier_id', 'Supplier', 'required');
        $this->form_validation->set_rules('amount', 'Amount', 'required|numeric');
        $this->form_validation->set_rules('date', 'Date', 'required');

        if ($this->form_validation->run() == false) {
        } else {
            $order_data = [
                'supplier_id' => $this->input->post('supplier_id'),
                'amount' => $this->input->post('amount'),
                'date' => $this->input->post('date')
            ];

            if (!empty($id)) {
                $this->expense_model->update_purchase_order($id, $order_data);
                $this->session->set_flashdata('msg', '<div class="alert alert-success">Purchase Order updated successfully.</div>');
            } else {
                $this->expense_model->add_purchase_order($order_data);
                $this->session->set_flashdata('msg', '<div class="alert alert-success">Purchase Order added successfully.</div>');
            }
            redirect('admin/expense/purchase_order');
        }

        // Get purchase orders and suppliers list
        $data['purchase_orders'] = $this->expense_model->get_purchase_orders();
        $data['suppliers'] = $this->expense_model->get_suppliers();

        // Load the view
        $this->load->view('layout/header', $data);
        $this->load->view('admin/expense/purchase_order', $data);
        $this->load->view('layout/footer', $data);
    }

    /**
     * Expense dates are entered in the UI as dd/mm/yyyy and stored as Y-m-d.
     */
    public function valid_expense_date($date)
    {
        if ($this->parseExpenseDate($date) === null) {
            $this->form_validation->set_message('valid_expense_date', 'The {field} must use the format dd/mm/yyyy.');
            return false;
        }

        return true;
    }

    private function parseExpenseDate($date)
    {
        $date = trim((string) $date);
        $parsed_date = DateTime::createFromFormat('!d/m/Y', $date);
        $errors = DateTime::getLastErrors();

        if ($parsed_date === false || ($errors !== false && ($errors['warning_count'] > 0 || $errors['error_count'] > 0))) {
            return null;
        }

        return $parsed_date->format('Y-m-d');
    }

    private function formatExpenseDate($date)
    {
        $date = substr((string) $date, 0, 10);
        $parsed_date = DateTime::createFromFormat('!Y-m-d', $date);
        $errors = DateTime::getLastErrors();

        if ($parsed_date === false || ($errors !== false && ($errors['warning_count'] > 0 || $errors['error_count'] > 0))) {
            return '';
        }

        return $parsed_date->format('d/m/Y');
    }
}
