<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Income extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('form');
        $this->load->library('media_storage');
        $this->config->load('app-config');
        $this->load->library("datatables");
        $this->load->model("accounts_model");
    }

    public function index()
    {
        if (!$this->rbac->hasPrivilege('income', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Income');
        $this->load->model('account_department_model');
        $data['account_departments'] = $this->account_department_model->get();
        $this->session->set_userdata('sub_menu', 'income/index');
        $this->form_validation->set_rules('inc_head_id', $this->lang->line('income_head'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('account_department_id', "Account Department", 'trim|required|xss_clean');
        $this->form_validation->set_rules('amount', $this->lang->line('amount'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('name', $this->lang->line('name'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('income_date', $this->lang->line('date'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('documents', $this->lang->line('documents'), 'callback_handle_upload');
        if ($this->form_validation->run() == true) {

            $img_name = $this->media_storage->fileupload("documents", "./uploads/school_income/");


            $id = $this->input->post('inc_head_id');
            $expense_title = get_income_title($id);

            $amount = $this->input->post('amount');
            $payment_method_id = $this->input->post('payment_method_id');
            $note = 'Income from ' . $this->input->post('name') . ' for ' . $expense_title;
            $trans_date = date('Y-m-d H:i:s');

            $current_user_id = $this->session->userdata['admin']['id'];
            $current_user_name = $this->session->userdata['admin']['username'];

            $income_date = date('Y-m-d H:i:s', strtotime($this->input->post('income_date')));

            $data = array(
                'income_head_id' => $this->input->post('inc_head_id'),
                'account_department_id' => $this->input->post('account_department_id'),
                'name'        => $this->input->post('name'),
                'date'        => $income_date,
                'amount'      => convertCurrencyFormatToBaseAmount($amount),
                'payment_method_id'  => $payment_method_id,
                'invoice_no'  => $this->input->post('invoice_no'),
                'note'        => $this->input->post('description'),
                'created_by'  => $current_user_id,
                'created_at'  => $trans_date,
                'documents'   => $img_name,
            );
            $income_id = $this->income_model->add($data);
            if ($income_id) {
                $income_added = $this->accounts_model->add_funds($amount, $payment_method_id, $note, $income_date, "income", $income_id);

                if ($income_added) {
                    $log_data = array_merge(array('id' => $income_id), $data);

                    $action = 1; //Adding
                    $user_id = $current_user_id;
                    $user_name = $current_user_name;
                    log_income($log_data, $action, $user_id, $user_name);
                }
            }

            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>');
            redirect('admin/income/index');
        }

        $data['paymentMethods'] = $this->accounts_model->getPaymentMethods();

        $income_result       = $this->income_model->get();
        $data['incomelist']  = $income_result;
        $incomeHead          = $this->incomehead_model->get();
        $data['incheadlist'] = $incomeHead;
        $this->load->view('layout/header', $data);
        $this->load->view('admin/income/incomeList', $data);
        $this->load->view('layout/footer', $data);
    }

    public function download($id)
    {
        $income = $this->income_model->get($id);

        $this->media_storage->filedownload($income['documents'], "uploads/school_income");
    }

    public function view($id)
    {
        if (!$this->rbac->hasPrivilege('income', 'can_view')) {
            access_denied();
        }
        $data['title']  = 'Fees Master List';
        $income         = $this->income_model->get($id);
        $data['income'] = $income;
        $this->load->view('layout/header', $data);
        $this->load->view('income/incomeShow', $data);
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
        $data     = $this->income_model->getTypeByFeecategory($type, $class_id);
        if (empty($data)) {
            $status = 'fail';
        } else {
            $status = 'success';
        }
        $array = array('status' => $status, 'data' => $data);
        echo json_encode($array);
    }

    public function refund($income_id)
    {
        /* print_r($_POST);
        die; */
        if (!$this->rbac->hasPrivilege('income', 'can_delete') || !$this->rbac->hasPrivilege('income', 'can_edit')) {
            access_denied();
        }
        //$income_id = $_POST['income_id'];
        $refund_date = $_POST['refund_date'];
        $refund_note = $_POST['refund_note'];
        $data = array(
            'id'          => $income_id,
            'is_refunded' => 1,
            'refund_date' => $refund_date,
            'refund_note' => $refund_note
        );

        if ($this->income_model->add($data)) {

            $income  = $this->income_model->get($income_id);

            $amount = $income['amount'];
            $payment_method_id = $income['payment_method_id'];
            $note = 'Income refunded to ' . $income['name'] . ' (' . $income['income_category'] . ')';
            $note .= "<br>Refund Note: $refund_note";
            //$trans_date = date('Y-m-d H:i:s');

            $current_user_id = $this->session->userdata['admin']['id'];
            $current_user_name = $this->session->userdata['admin']['username'];


            $refund_funds = $this->accounts_model->refund_funds($amount, $payment_method_id, $note, $refund_date, "income", $income_id);
            if ($refund_funds) {
                $log_data = $income;

                $action = 4; //Refund
                $user_id = $current_user_id;
                $user_name = $current_user_name;
                log_income($log_data, $action, $user_id, $user_name);
            }
        }

        $this->session->set_flashdata('msg', '<div class="alert alert-danger text-left">Refund successfully!</div>');
        redirect('admin/income/index');
    }

    public function delete($id)
    {
        /* if (!$this->rbac->hasPrivilege('income', 'can_delete')) {
            access_denied();
        }
        $data['title'] = 'Fees Master List';
        $row           = $this->income_model->get($id);
        if ($row['documents'] != '') {
            $this->media_storage->filedelete($row['documents'], "uploads/school_income/");
        }

        $this->income_model->remove($id);
        redirect('admin/income/index'); */
        $this->session->set_flashdata('msg', '<div class="alert alert-danger text-left">Delete is disabled</div>');
        redirect('admin/income/index');
    }

    public function create()
    {
        $data['title'] = 'Add Fees Master';
        $this->form_validation->set_rules('income', $this->lang->line('fees_master'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {
            $this->load->view('layout/header', $data);
            $this->load->view('income/incomeCreate', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $data = array(
                'income' => $this->input->post('income'),
            );
            $this->income_model->add($data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>');
            redirect('income/index');
        }
    }

    public function handle_upload()
    {
        $image_validate = $this->config->item('file_validate');
        $result         = $this->filetype_model->get();
        if (isset($_FILES["documents"]) && !empty($_FILES['documents']['name'])) {

            $file_type = $_FILES["documents"]['type'];
            $file_size = $_FILES["documents"]["size"];
            $file_name = $_FILES["documents"]["name"];

            $allowed_extension = array_map('trim', array_map('strtolower', explode(',', $result->file_extension)));
            $allowed_mime_type = array_map('trim', array_map('strtolower', explode(',', $result->file_mime)));
            $ext               = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

            if ($files = filesize($_FILES['documents']['tmp_name'])) {

                if (!in_array($file_type, $allowed_mime_type)) {
                    $this->form_validation->set_message('handle_upload', $this->lang->line('file_type_not_allowed'));
                    return false;
                }

                if (!in_array($ext, $allowed_extension) || !in_array($file_type, $allowed_mime_type)) {
                    $this->form_validation->set_message('handle_upload', $this->lang->line('extension_not_allowed'));
                    return false;
                }
                if ($file_size > $result->file_size) {
                    $this->form_validation->set_message('handle_upload', $this->lang->line('file_size_shoud_be_less_than') . number_format($result->file_size / 1048576, 2) . " MB");
                    return false;
                }
            } else {
                $this->form_validation->set_message('handle_upload', $this->lang->line('file_type_extension_error_uploading_image'));
                return false;
            }

            return true;
        }
        return true;
    }

    public function edit($id)
    {

        $current_user_id = $this->session->userdata['admin']['id'];
        $current_user_name = $this->session->userdata['admin']['username'];

        //if (!$this->rbac->hasPrivilege('income', 'can_edit') || $current_user_id != 1) {
        if (!$this->rbac->hasPrivilege('income', 'can_edit')) {
            access_denied();
        }

        $data['paymentMethods'] = $this->accounts_model->getPaymentMethods();
        $data['id']          = $id;
        $this->load->model('account_department_model');
        $data['account_departments'] = $this->account_department_model->get();
        $income              = $this->income_model->get($id);
        $data['income']      = $income;
        $data['title_list']  = 'Fees Master List';
        $expnseHead          = $this->incomehead_model->get();
        $data['incheadlist'] = $expnseHead;
        $this->form_validation->set_rules('inc_head_id', $this->lang->line('income_head'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('account_department_id', 'Account Department', 'trim|required|xss_clean');
        $this->form_validation->set_rules('amount', $this->lang->line('amount'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('name', $this->lang->line('name'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('income_date', $this->lang->line('date'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('documents', $this->lang->line('documents'), 'callback_handle_upload');
        if ($this->form_validation->run() == false) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/income/incomeEdit', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $income_date = date('Y-m-d', strtotime($this->input->post('income_date')));
            $data = array(
                'id'          => $id,
                'income_head_id' => $this->input->post('inc_head_id'),
                'account_department_id' => $this->input->post('account_department_id'),
                'name'        => $this->input->post('name'),
                // income_date is an HTML date input (Y-m-d). Using the wrong
                // field and passing a formatted string to date() caused PHP 8
                // to throw a TypeError and return HTTP 500 on every edit.
                'date'        => $income_date,
                'amount'      => convertCurrencyFormatToBaseAmount($this->input->post('amount')),
                'payment_method_id'  => $this->input->post('payment_method_id'),
                'invoice_no'  => $this->input->post('invoice_no'),
                'note'        => $this->input->post('description'),
            );

            if (isset($_FILES["documents"]) && $_FILES['documents']['name'] != '' && (!empty($_FILES['documents']['name']))) {

                $img_name = $this->media_storage->fileupload("documents", "./uploads/school_income/");
            } else {
                $img_name = $income['documents'];
            }

            $data['documents'] = $img_name;

            if (isset($_FILES["documents"]) && $_FILES['documents']['name'] != '' && (!empty($_FILES['documents']['name']))) {
                if ($income['documents'] != '') {
                    $this->media_storage->filedelete($income['documents'], "uploads/school_income");
                }
            }

            $income_id = $this->income_model->add($data);

            if ($income_id) {

                $id = $this->input->post('inc_head_id');
                $income_title = get_income_title($id);

                $amount = $this->input->post('amount');
                $payment_method_id = $this->input->post('payment_method_id');
                $note = 'Income from ' . $this->input->post('name') . ' for ' . $income_title;

                $trans_data = array(
                    'amount' => $amount,
                    'payment_method_id' => $payment_method_id,
                    'descriptions' => $note,
                    'trans_date' => $income_date,
                    'trans_by' => $current_user_id
                );

                $addExpense = $this->accounts_model->update_transaction($income_id, "income", $trans_data);

                if ($addExpense) {
                    $log_data = array_merge(array('id' => $income_id), $data);

                    $action = 2; //Updating
                    $user_id = $current_user_id;
                    $user_name = $current_user_name;
                    log_income($log_data, $action, $user_id, $user_name);
                }
            }

            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>');
            redirect('admin/income/index');
        }
    }

    public function incomeSearch()
    {
        if (!$this->rbac->hasPrivilege('search_income', 'can_view')) {
            access_denied();
        }
        $this->load->model('account_department_model');
        $data['account_departments'] = $this->account_department_model->get();
        $data['searchlist'] = $this->customlib->get_searchtype();
        $this->session->set_userdata('top_menu', 'Income');
        $this->session->set_userdata('sub_menu', 'income/incomesearch');
        $data['search_type'] = '';
        $this->load->view('layout/header', $data);
        $this->load->view('admin/income/incomeSearch', $data);
        $this->load->view('layout/footer', $data);
    }

    public function getincomelist()
    {
        $m               = $this->income_model->getincomelist();
        $m               = json_decode($m);
        $currency_symbol = $this->customlib->getSchoolCurrencyFormat();
        $dt_data         = array();
        $total_amount = 0;
        $current_user_id = $this->session->userdata['admin']['id'];
        if (!empty($m->data)) {
            foreach ($m->data as $key => $value) {
                $editbtn     = '';
                $deletebtn   = '';
                $documents   = '';
                $inc_head_id = $value->income_head_id;
                $arr1        = str_split($inc_head_id);

                $is_refunded = $value->is_refunded;
                if ($is_refunded != 1) {
                    $total_amount += $value->amount;
                }

                $title = "<a href='#' tabindex='0' data-toggle='popover' class='detail_popover'>" . $value->name . "</a>  ";
                if ($value->documents) {
                    $documents = "<a href='" . base_url() . "admin/income/download/" . $value->id . "'   class='btn btn-default btn-xs'  data-toggle='tooltip' title='" . $this->lang->line('download') . "'><i class='fa fa-download'></i></a>";
                }
                ///$current_user_id == 1 && //Edited on 23092025
                if ($this->rbac->hasPrivilege('income', 'can_edit') && $value->is_refunded != 1) {
                    $editbtn = "<a href='" . base_url() . "admin/income/edit/" . $value->id . "'   class='btn btn-default btn-xs'  data-toggle='tooltip' title='" . $this->lang->line('edit') . "'><i class='fa fa-pencil'></i></a>";
                }
                /* if ($this->rbac->hasPrivilege('income', 'can_delete')) {
                    $deletebtn = '';
                    $deletebtn = "<a onclick='return confirm(" . '"' . $this->lang->line('delete_confirm') . '"' . "  )' href='" . base_url() . "admin/income/delete/" . $value->id . "' class='btn btn-default btn-xs' title='" . $this->lang->line('delete') . "' data-toggle='tooltip'><i class='fa fa-trash'></i></a>";
                }  */

                if ($this->rbac->hasPrivilege('income', 'can_delete') || $this->rbac->hasPrivilege('income', 'can_edit')) {
                    $deletebtn = '';
                    if ($value->is_refunded == 1) {
                        $deletebtn = '<span class="badge">Refunded</span>';
                        $deletebtn .= '<p>' . $value->refund_note . ' on ' . $value->refund_date . '</p>';
                    } else {
                        $deletebtn = "<a href='javascript:void(0);' class='btn btn-default btn-xs refund-button' data-refund-id='" . $value->id . "' data-url='" . base_url() . "admin/income/refund/" . $value->id . "' title='Refund' data-toggle='tooltip'><i class='fa fa-undo'></i></a>";
                    }
                }

                $row   = array();
                $row[] = $value->id;
                $row[]     = $this->customlib->dateformat($value->date);
                $row[] = $title;

                $row[]     = $value->invoice_no;
                $row[]     = $value->income_category;
                $row[]     = $value->account_department_name;

                if ($value->note == "") {
                    $row[] = $this->lang->line('no_description');
                } else {
                    $row[] = $value->note;
                }
                $row[]     = $currency_symbol . amountFormat($value->amount);
                $row[]     = get_payment_mode($value->payment_method_id);
                $row[]      = $value->made_by;
                $row[]     = $documents . ' ' . $editbtn . ' ' . $deletebtn;
                $dt_data[] = $row;
            }

            $footer_row   = array();
            $footer_row[] = "";
            $footer_row[] = "";
            $footer_row[] = "";
            $footer_row[] = "";
            $footer_row[] = "";
            $footer_row[] = "";
            $footer_row[] = "<b>" . $this->lang->line('grand_total') . " : ";
            $footer_row[] = $currency_symbol . amountFormat($total_amount) . "</b>";
            $footer_row[] = "";
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

    public function checkvalidation()
    {
        /* $button_type = $this->input->post('button_type');

        if ($button_type == "search_filter") {
            $this->form_validation->set_rules('search_type', $this->lang->line('search') . " " . $this->lang->line('type'), 'required|trim|xss_clean');
        } elseif ($button_type == "search_full") {
            $this->form_validation->set_rules('search_text', $this->lang->line('keyword'), 'required|trim|xss_clean');
        }

        if ($this->form_validation->run() == false) {
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

        $params = array('button_type' => $button_type, 'search_type' => $search_type, 'search_text' => $search_text, 'date_from' => $date_from, 'date_to' => $date_to);
        $array  = array('status' => 1, 'error' => '', 'params' => $params);
        echo json_encode($array);
        //}
    }

    public function getincomesearchlist()
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
            $data['exp_title'] = 'Income Result From ' . date($dateformat, strtotime($date_from)) . " To " . date($dateformat, strtotime($date_to));
            $date_from         = date('Y-m-d', $this->customlib->dateYYYYMMDDtoStrtotime($date_from));
            $date_to           = date('Y-m-d', $this->customlib->dateYYYYMMDDtoStrtotime($date_to));
            $resultList        = $this->income_model->search("", $date_from, $date_to);
        } else {

            $search_text = $this->input->post('search_text');
            $resultList  = $this->income_model->search($search_text, "", "");
            $resultList  = $resultList;
        }

        $m               = json_decode($resultList);
        $currency_symbol = $this->customlib->getSchoolCurrencyFormat();
        $dt_data         = array();
        $total_amount    = 0;
        /* print_r($m->data);
        die; */
        $current_user_id = $this->session->userdata['admin']['id'];
        if (!empty($m->data)) {
            foreach ($m->data as $key => $value) {

                $editbtn     = '';
                $deletebtn   = '';
                $documents   = '';
                if ($value->documents) {
                    $documents = "<a href='" . base_url() . "admin/income/download/" . $value->id . "'   class='btn btn-default btn-xs'  data-toggle='tooltip' title='" . $this->lang->line('download') . "'><i class='fa fa-download'></i></a>";
                }

                if ($this->rbac->hasPrivilege('income', 'can_edit') && $current_user_id == 1 && $value->is_refunded != 1) {
                    $editbtn = "<a href='" . base_url() . "admin/income/edit/" . $value->id . "'   class='btn btn-default btn-xs'  data-toggle='tooltip' title='" . $this->lang->line('edit') . "'><i class='fa fa-pencil'></i></a>";
                }
                /* if ($this->rbac->hasPrivilege('income', 'can_delete')) {
                    $deletebtn = '';
                    $deletebtn = "<a onclick='return confirm(" . '"' . $this->lang->line('delete_confirm') . '"' . "  )' href='" . base_url() . "admin/income/delete/" . $value->id . "' class='btn btn-default btn-xs' title='" . $this->lang->line('delete') . "' data-toggle='tooltip'><i class='fa fa-trash'></i></a>";
                }  */

                if ($this->rbac->hasPrivilege('income', 'can_delete') || $this->rbac->hasPrivilege('income', 'can_edit')) {
                    $deletebtn = '';
                    if ($value->is_refunded == 1) {
                        $deletebtn = '<span class="badge">Refunded</span>';
                        $deletebtn .= '<p>' . $value->refund_note . ' on ' . $value->refund_date . '</p>';
                    } else {
                        $deletebtn = "<a href='javascript:void(0);' class='btn btn-default btn-xs refund-button' data-refund-id='" . $value->id . "' data-url='" . base_url() . "admin/income/refund/" . $value->id . "' title='Refund' data-toggle='tooltip'><i class='fa fa-undo'></i></a>";
                    }
                }


                $is_refunded = $value->is_refunded;
                if ($is_refunded != 1) {
                    $total_amount += $value->amount;
                }

                $row        = array();
                $row[]      = $value->id;
                $row[]      = date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($value->date));
                $row[]      = $value->name;
                $row[]      = $value->invoice_no;
                $row[]      = $value->income_category;
                $row[]      = $value->account_department_name;
                $row[]      = $value->note;
                $row[]      = get_payment_mode($value->payment_method_id);
                $row[]      = $currency_symbol . amountFormat($value->amount);
                $row[]      = $value->made_by;
                $row[]      = $documents . ' ' . $editbtn . ' ' . $deletebtn;
                $dt_data[]  = $row;
            }
            $footer_row   = array();
            $footer_row[] = "";
            $footer_row[] = "";
            $footer_row[] = "";
            $footer_row[] = "";
            $footer_row[] = "";
            $footer_row[] = "";
            $footer_row[] = "";
            $footer_row[] = "";
            $footer_row[] = "<b>" . $this->lang->line('grand_total') . " : " . $currency_symbol . amountFormat($total_amount) . "</b>";
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
}
