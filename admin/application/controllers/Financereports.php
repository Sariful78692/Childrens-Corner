<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Financereports extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->time               = strtotime(date('d-m-Y H:i:s'));
        $this->payment_mode       = $this->customlib->payment_mode();
        $this->search_type        = $this->customlib->get_searchtype();
        $this->sch_setting_detail = $this->setting_model->getSetting();
        $this->load->library('media_storage');
        $this->load->model("module_model");
        $this->load->model("accounts_model");
    }

    public function finance()
    {
        $this->session->set_userdata('top_menu', 'Financereports');
        $this->session->set_userdata('sub_menu', 'Financereports/finance');
        $this->session->set_userdata('subsub_menu', '');
        $this->load->view('layout/header');
        $this->load->view('financereports/finance');
        $this->load->view('layout/footer');
    }

    public function reportduefees()
    {
        if (!$this->rbac->hasPrivilege('balance_fees_statement', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Reports');
        $this->session->set_userdata('sub_menu', 'Reports/finance');
        $this->session->set_userdata('subsub_menu', 'Reports/finance/reportduefees');
        $data                = array();
        $data['title']       = 'student fees';
        $class               = $this->class_model->get();
        $data['classlist']   = $class;
        $data['sch_setting'] = $this->sch_setting_detail;
        if ($this->input->server('REQUEST_METHOD') == "POST") {
            $date               = date('Y-m-d');
            $class_id           = $this->input->post('class_id');
            $section_id         = $this->input->post('section_id');
            $data['class_id']   = $class_id;
            $data['section_id'] = $section_id;
            $fees_dues          = $this->studentfeemaster_model->getStudentDueFeeTypesByDate($date, $class_id, $section_id);
            $students_list      = array();

            if (!empty($fees_dues)) {
                foreach ($fees_dues as $fee_due_key => $fee_due_value) {
                    $amount_paid = 0;

                    if (isJSON($fee_due_value->amount_detail)) {
                        $student_fees_array = json_decode($fee_due_value->amount_detail);
                        foreach ($student_fees_array as $fee_paid_key => $fee_paid_value) {
                            $amount_paid += ($fee_paid_value->amount + $fee_paid_value->amount_discount);
                        }
                    }
                    if ($amount_paid < $fee_due_value->fee_amount || ($amount_paid < $fee_due_value->amount && $fee_due_value->is_system)) {

                        $students_list[$fee_due_value->student_session_id]['admission_no']             = $fee_due_value->admission_no;
                        $students_list[$fee_due_value->student_session_id]['class_id']             = $fee_due_value->class_id;
                        $students_list[$fee_due_value->student_session_id]['section_id']             = $fee_due_value->section_id;
                        $students_list[$fee_due_value->student_session_id]['student_id']             = $fee_due_value->student_id;
                        $students_list[$fee_due_value->student_session_id]['roll_no']                  = $fee_due_value->roll_no;
                        $students_list[$fee_due_value->student_session_id]['admission_date']           = $fee_due_value->admission_date;
                        $students_list[$fee_due_value->student_session_id]['firstname']                = $fee_due_value->firstname;
                        $students_list[$fee_due_value->student_session_id]['middlename']               = $fee_due_value->middlename;
                        $students_list[$fee_due_value->student_session_id]['lastname']                 = $fee_due_value->lastname;
                        $students_list[$fee_due_value->student_session_id]['father_name']              = $fee_due_value->father_name;
                        $students_list[$fee_due_value->student_session_id]['image']                    = $fee_due_value->image;
                        $students_list[$fee_due_value->student_session_id]['mobileno']                 = $fee_due_value->mobileno;
                        $students_list[$fee_due_value->student_session_id]['email']                    = $fee_due_value->email;
                        $students_list[$fee_due_value->student_session_id]['state']                    = $fee_due_value->state;
                        $students_list[$fee_due_value->student_session_id]['city']                     = $fee_due_value->city;
                        $students_list[$fee_due_value->student_session_id]['pincode']                  = $fee_due_value->pincode;
                        $students_list[$fee_due_value->student_session_id]['class']                    = $fee_due_value->class;
                        $students_list[$fee_due_value->student_session_id]['section']                  = $fee_due_value->section;
                        $students_list[$fee_due_value->student_session_id]['fee_groups_feetype_ids'][] = $fee_due_value->fee_groups_feetype_id;
                    }
                }
            }

            if (!empty($students_list)) {
                foreach ($students_list as $student_key => $student_value) {
                    $students_list[$student_key]['fees_list'] = $this->studentfeemaster_model->studentDepositByFeeGroupFeeTypeArray($student_key, $student_value['fee_groups_feetype_ids']);
                    $students_list[$student_key]['transport_fees']       = array();
                    $student               = $this->student_model->getByStudentSession($student_value['student_id']);

                    if (!empty($student)) {
                        $route_pickup_point_id = $student['route_pickup_point_id'];
                        $student_session_id    = $student['student_session_id'];
                    } else {
                        $route_pickup_point_id = '';
                        $student_session_id    = '';
                    }

                    $transport_fees = [];
                    $module = $this->module_model->getPermissionByModulename('transport');

                    if ($module['is_active']) {
                        $transport_fees        = $this->studentfeemaster_model->getStudentTransportFees($student_session_id, $route_pickup_point_id);
                    }
                    $students_list[$student_key]['transport_fees']       = $transport_fees;
                }
            }

            $data['student_due_fee'] = $students_list;
        }

        $this->load->view('layout/header', $data);
        $this->load->view('financereports/reportduefees', $data);
        $this->load->view('layout/footer', $data);
    }

    public function printreportduefees()
    {
        $data                = array();
        $data['title']       = 'student fees';
        $class               = $this->class_model->get();
        $data['classlist']   = $class;
        $data['sch_setting'] = $this->sch_setting_detail;
        $date                = date('Y-m-d');
        $class_id            = $this->input->post('class_id');
        $section_id          = $this->input->post('section_id');
        $data['class_id']    = $class_id;
        $data['section_id']  = $section_id;
        $fees_dues           = $this->studentfeemaster_model->getStudentDueFeeTypesByDate($date, $class_id, $section_id);
        $students_list       = array();

        if (!empty($fees_dues)) {
            foreach ($fees_dues as $fee_due_key => $fee_due_value) {
                $amount_paid = 0;

                if (isJSON($fee_due_value->amount_detail)) {
                    $student_fees_array = json_decode($fee_due_value->amount_detail);
                    foreach ($student_fees_array as $fee_paid_key => $fee_paid_value) {
                        $amount_paid += ($fee_paid_value->amount + $fee_paid_value->amount_discount);
                    }
                }
                // if ($amount_paid < $fee_due_value->fee_amount) {
                if ($amount_paid < $fee_due_value->fee_amount || ($amount_paid < $fee_due_value->amount && $fee_due_value->is_system)) {
                    $students_list[$fee_due_value->student_session_id]['admission_no']             = $fee_due_value->admission_no;
                    $students_list[$fee_due_value->student_session_id]['class_id']             = $fee_due_value->class_id;
                    $students_list[$fee_due_value->student_session_id]['section_id']             = $fee_due_value->section_id;
                    $students_list[$fee_due_value->student_session_id]['student_id']             = $fee_due_value->student_id;
                    $students_list[$fee_due_value->student_session_id]['roll_no']                  = $fee_due_value->roll_no;
                    $students_list[$fee_due_value->student_session_id]['admission_date']           = $fee_due_value->admission_date;
                    $students_list[$fee_due_value->student_session_id]['firstname']                = $fee_due_value->firstname;
                    $students_list[$fee_due_value->student_session_id]['middlename']               = $fee_due_value->middlename;
                    $students_list[$fee_due_value->student_session_id]['lastname']                 = $fee_due_value->lastname;
                    $students_list[$fee_due_value->student_session_id]['father_name']              = $fee_due_value->father_name;
                    $students_list[$fee_due_value->student_session_id]['image']                    = $fee_due_value->image;
                    $students_list[$fee_due_value->student_session_id]['mobileno']                 = $fee_due_value->mobileno;
                    $students_list[$fee_due_value->student_session_id]['email']                    = $fee_due_value->email;
                    $students_list[$fee_due_value->student_session_id]['state']                    = $fee_due_value->state;
                    $students_list[$fee_due_value->student_session_id]['city']                     = $fee_due_value->city;
                    $students_list[$fee_due_value->student_session_id]['pincode']                  = $fee_due_value->pincode;
                    $students_list[$fee_due_value->student_session_id]['class']                    = $fee_due_value->class;
                    $students_list[$fee_due_value->student_session_id]['section']                  = $fee_due_value->section;
                    $students_list[$fee_due_value->student_session_id]['fee_groups_feetype_ids'][] = $fee_due_value->fee_groups_feetype_id;
                }
            }
        }

        if (!empty($students_list)) {
            foreach ($students_list as $student_key => $student_value) {
                $students_list[$student_key]['fees_list'] = $this->studentfeemaster_model->studentDepositByFeeGroupFeeTypeArray($student_key, $student_value['fee_groups_feetype_ids']);
                $students_list[$student_key]['transport_fees']       = array();
                $student               = $this->student_model->getByStudentSession($student_value['student_id']);

                $route_pickup_point_id = $student['route_pickup_point_id'];
                $student_session_id    = $student['student_session_id'];
                $transport_fees = [];
                $module = $this->module_model->getPermissionByModulename('transport');

                if ($module['is_active']) {

                    $transport_fees        = $this->studentfeemaster_model->getStudentTransportFees($student_session_id, $route_pickup_point_id);
                }
                $students_list[$student_key]['transport_fees']       = $transport_fees;
            }
        }
        $data['student_due_fee'] = $students_list;
        $page                    = $this->load->view('financereports/_printreportduefees', $data, true);
        echo json_encode(array('status' => 1, 'page' => $page));
    }

    public function reportdailycollection()
    {
        $this->session->set_userdata('top_menu', 'Reports');
        $this->session->set_userdata('sub_menu', 'Reports/finance');
        $this->session->set_userdata('subsub_menu', 'Reports/finance/reportdailycollection');

        $data = array();
        $data['title'] = 'Daily Collection Report';

        // Set default date range to today's date
        $today = date('Y-m-d');
        //$today = date('d/m/Y');
        $date_from = $this->input->post('date_from') ? $this->input->post('date_from') : $today;
        $date_to = $this->input->post('date_to') ? $this->input->post('date_to') : $today;


        $this->form_validation->set_rules('date_from', $this->lang->line('date_from'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('date_to', $this->lang->line('date_to'), 'trim|required|xss_clean');

        if ($this->form_validation->run() == true) {
            /* print_r($_POST);
            die; */
            //$date_from = date("Y-m-d", strtotime($date_from));
            //$date_to = date("Y-m-d", strtotime($date_to));
            //echo $date_from;
            //die;
            // Fetch data based on user input
            $feesData = $this->studentfeemaster_model->getFeesCollections($date_from, $date_to);
        } else {
            // Fetch today's data by default
            $feesData = $this->studentfeemaster_model->getFeesCollections($today, $today);
        }

        $data['fees_data'] = $feesData;
        $data['date_from'] = $date_from;
        $data['date_to'] = $date_to;

        $this->load->view('layout/header', $data);
        $this->load->view('financereports/reportdailycollection', $data);
        $this->load->view('layout/footer', $data);
    }


    public function feeCollectionStudentDeposit()
    {
        $data                 = array();
        $date                 = $this->input->post('date');
        $fees_id              = $this->input->post('fees_id');
        $fees_id_array        = explode(',', $fees_id);
        $fees_list            = $this->studentfeemaster_model->getFeesDepositeByIdArray($fees_id_array);
        $data['student_list'] = $fees_list;
        $data['date']         = $date;
        $data['sch_setting']  = $this->sch_setting_detail;
        $page                 = $this->load->view('financereports/_feeCollectionStudentDeposit', $data, true);
        echo json_encode(array('status' => 1, 'page' => $page));
    }

    public function reportbyname()
    {
        if (!$this->rbac->hasPrivilege('fees_statement', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Reports');
        $this->session->set_userdata('sub_menu', 'Reports/finance');
        $this->session->set_userdata('subsub_menu', 'Reports/finance/reportbyname');
        $data['title']       = 'student fees';
        $data['title']       = 'student fees';
        $class               = $this->class_model->get();
        $data['classlist']   = $class;
        $data['sch_setting'] = $this->sch_setting_detail;

        if ($this->input->server('REQUEST_METHOD') == "GET") {
            $this->load->view('layout/header', $data);
            $this->load->view('financereports/reportByName', $data);
            $this->load->view('layout/footer', $data);
        } else { {
                $data['student_due_fee'] = array();
                $class_id                = $this->input->post('class_id');
                $section_id              = $this->input->post('section_id');
                $student_id              = $this->input->post('student_id');
                $student_due_fee         = $this->studentfeemaster_model->getStudentFeesByClassSectionStudent($class_id, $section_id, $student_id);
                foreach ($student_due_fee as $key => $value) {
                    $transport_fees = array();
                    $student               = $this->student_model->getByStudentSession($value['student_id']);

                    if ($student) {
                        $route_pickup_point_id = $student['route_pickup_point_id'];
                        $student_session_id    = $student['student_session_id'];
                    } else {
                        $route_pickup_point_id = '';
                        $student_session_id    = '';
                    }
                    $transport_fees = [];
                    $module = $this->module_model->getPermissionByModulename('transport');

                    if ($module['is_active']) {

                        $transport_fees        = $this->studentfeemaster_model->getStudentTransportFees($student_session_id, $route_pickup_point_id);
                    }
                    $student_due_fee[$key]['transport_fees']         = $transport_fees;
                }

                $data['student_due_fee'] = $student_due_fee;
                $data['class_id']        = $class_id;
                $data['section_id']      = $section_id;
                $data['student_id']      = $student_id;
                $category                = $this->category_model->get();
                $data['categorylist']    = $category;
                $this->load->view('layout/header', $data);
                $this->load->view('financereports/reportByName', $data);
                $this->load->view('layout/footer', $data);
            }
        }
    }

    public function studentacademicreport()
    {
        if (!$this->rbac->hasPrivilege('balance_fees_report', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Reports');
        $this->session->set_userdata('sub_menu', 'Reports/finance');
        $this->session->set_userdata('subsub_menu', 'Reports/finance/studentacademicreport');
        $data['title']           = 'student fee';
        $data['payment_type']    = $this->customlib->getPaymenttype();
        $class                   = $this->class_model->get();
        $data['classlist']       = $class;
        $data['sch_setting']     = $this->sch_setting_detail;
        $data['adm_auto_insert'] = $this->sch_setting_detail->adm_auto_insert;
        $this->form_validation->set_rules('search_type', $this->lang->line('search_type'), 'trim|required|xss_clean');

        if ($this->form_validation->run() == false) {
            $data['student_due_fee'] = array();
            $data['resultarray']     = array();
            $data['feetype']     = "";
            $data['feetype_arr'] = array();
        } else {
            $student_Array = array();
            $search_type   = $this->input->post('search_type');
            $class_id   = $this->input->post('class_id');
            $section_id = $this->input->post('section_id');

            if (isset($class_id)) {
                $studentlist = $this->student_model->searchByClassSectionWithSession($class_id, $section_id);
            } else {
                $studentlist = $this->student_model->getStudents();
            }

            $student_Array = array();
            if (!empty($studentlist)) {
                foreach ($studentlist as $key => $eachstudent) {
                    $obj                = new stdClass();
                    $obj->name          = $this->customlib->getFullName($eachstudent['firstname'], $eachstudent['middlename'], $eachstudent['lastname'], $this->sch_setting_detail->middlename, $this->sch_setting_detail->lastname);
                    $obj->class         = $eachstudent['class'];
                    $obj->section       = $eachstudent['section'];
                    $obj->admission_no  = $eachstudent['admission_no'];
                    $obj->roll_no       = $eachstudent['roll_no'];
                    $obj->father_name   = $eachstudent['father_name'];
                    $student_session_id = $eachstudent['student_session_id'];
                    $student_total_fees = $this->studentfeemaster_model->getTransStudentFees($student_session_id);

                    if (!empty($student_total_fees)) {
                        $totalfee = 0;
                        $deposit  = 0;
                        $discount = 0;
                        $balance  = 0;
                        $fine     = 0;

                        foreach ($student_total_fees as $student_total_fees_key => $student_total_fees_value) {

                            if (!empty($student_total_fees_value->fees)) {
                                foreach ($student_total_fees_value->fees as $each_fee_key => $each_fee_value) {
                                    $totalfee = $totalfee + $each_fee_value->amount;

                                    if (isJSON($each_fee_value->amount_detail)) {
                                        $amount_detail = json_decode($each_fee_value->amount_detail);

                                        if (is_object($amount_detail) && !empty($amount_detail)) {
                                            foreach ($amount_detail as $amount_detail_key => $amount_detail_value) {
                                                $deposit  = $deposit + $amount_detail_value->amount;
                                                $fine     = $fine + $amount_detail_value->amount_fine;
                                                $discount = $discount + $amount_detail_value->amount_discount;
                                            }
                                        }
                                    }
                                }
                            }
                        }

                        $obj->totalfee     = $totalfee;
                        $obj->payment_mode = "N/A";
                        $obj->deposit      = $deposit;
                        $obj->fine         = $fine;
                        $obj->discount     = $discount;
                        $obj->balance      = $totalfee - ($deposit + $discount);
                    } else {

                        $obj->totalfee     = 0;
                        $obj->payment_mode = 0;
                        $obj->deposit      = 0;
                        $obj->fine         = 0;
                        $obj->balance      = 0;
                        $obj->discount     = 0;
                    }

                    if ($search_type == 'all') {
                        $student_Array[] = $obj;
                    } elseif ($search_type == 'balance') {
                        if ($obj->balance > 0) {
                            $student_Array[] = $obj;
                        }
                    } elseif ($search_type == 'paid') {
                        if ($obj->balance <= 0) {
                            $student_Array[] = $obj;
                        }
                    }
                }
            }

            $classlistdata[]         = array('result' => $student_Array);
            $data['student_due_fee'] = $student_Array;
            $data['resultarray']     = $classlistdata;
        }

        $this->load->view('layout/header', $data);
        $this->load->view('financereports/studentAcademicReport', $data);
        $this->load->view('layout/footer', $data);
    }

    public function overview()
    {
        $this->session->set_userdata('top_menu', 'Reports');
        $this->session->set_userdata('sub_menu', 'Reports/finance');
        $this->session->set_userdata('subsub_menu', 'Reports/finance/overview');

        $data['collect_by'] = $this->studentfeemaster_model->get_feesreceived_by();
        $data['paymentMethods'] = $this->accounts_model->getPaymentMethods();
        $payment_mode_id = null;
        $collected_by = null;

        // Check if the form has been submitted
        if ($this->input->server('REQUEST_METHOD') == 'POST') {
            $payment_mode_id = $this->input->post('payment_mode_id');
            $collected_by = $this->input->post('collect_by');
        }
        // Get date range from form input
        $date_from = $this->input->post('date_from');
        $date_to = $this->input->post('date_to');

        // Validate and set default date range if not provided
        if (!$date_from) {
            $date_from = date('Y') . '-01-01'; // Default to the first date of the current year
        }

        if (!$date_to) {
            $date_to = date('Y-m-d'); // Default to today's date
        }

        // Total Fees Collection
        $this->db->select_sum('amount', 'total');
        $this->db->where('status', 1);
        $this->db->where('trans_type', 1);
        $this->db->where('transaction_for_table', 'student_fees_collections');
        $this->db->where('trans_date >=', $date_from);
        $this->db->where('trans_date <=', $date_to);
        if ($payment_mode_id != null) {
            $this->db->where('payment_method_id', $payment_mode_id);
        }
        if ($collected_by != null) {
            $this->db->where('trans_by', $collected_by);
        }
        $query = $this->db->get('transactions');
        //echo $this->db->last_query(); die;

        $total_fees_collection = $query->row()->total;

        $this->db->select_sum('amount', 'total');
        $this->db->where('status', 1);
        $this->db->where('trans_type', 2);
        $this->db->where('transaction_for_table', 'student_fees_collections');
        $this->db->where('trans_date >=', $date_from);
        $this->db->where('trans_date <=', $date_to);
        if ($payment_mode_id != null) {
            $this->db->where('payment_method_id', $payment_mode_id);
        }
        if ($collected_by != null) {
            $this->db->where('trans_by', $collected_by);
        }
        $query = $this->db->get('transactions');

        $total_fees_collection_refunded = $query->row()->total;

        // Total Income
        $this->db->select_sum('amount', 'total');
        $this->db->where('status', 1);
        $this->db->where('trans_type', 1);
        $this->db->where('transaction_for_table', 'income');
        $this->db->where('trans_date >=', $date_from);
        $this->db->where('trans_date <=', $date_to);
        if ($payment_mode_id != null) {
            $this->db->where('payment_method_id', $payment_mode_id);
        }
        if ($collected_by != null) {
            $this->db->where('trans_by', $collected_by);
        }
        $query = $this->db->get('transactions');

        $total_income = $query->row()->total;

        $this->db->select_sum('amount', 'total');
        $this->db->where('status', 1);
        $this->db->where('trans_type', 2);
        $this->db->where('transaction_for_table', 'income');
        $this->db->where('trans_date >=', $date_from);
        $this->db->where('trans_date <=', $date_to);
        if ($payment_mode_id != null) {
            $this->db->where('payment_method_id', $payment_mode_id);
        }
        if ($collected_by != null) {
            $this->db->where('trans_by', $collected_by);
        }
        $query = $this->db->get('transactions');

        $total_income_refunded = $query->row()->total;

        // Total Expenses
        $this->db->select_sum('amount', 'total');
        $this->db->where('status', 1);
        $this->db->where('trans_type', 2);
        $this->db->where('transaction_for_table', 'expenses');
        $this->db->where('trans_date >=', $date_from);
        $this->db->where('trans_date <=', $date_to);
        if ($payment_mode_id != null) {
            $this->db->where('payment_method_id', $payment_mode_id);
        }
        if ($collected_by != null) {
            $this->db->where('trans_by', $collected_by);
        }
        $query = $this->db->get('transactions');

        $total_expenses = $query->row()->total;

        //caalculating total_expenses_refunded
        $this->db->select_sum('amount', 'total');
        $this->db->where('status', 1);
        $this->db->where('trans_type', 1);
        $this->db->where('transaction_for_table', 'expenses');
        $this->db->where('trans_date >=', $date_from);
        $this->db->where('trans_date <=', $date_to);
        if ($payment_mode_id != null) {
            $this->db->where('payment_method_id', $payment_mode_id);
        }
        if ($collected_by != null) {
            $this->db->where('trans_by', $collected_by);
        }
        $query = $this->db->get('transactions');

        $total_expenses_refunded = $query->row()->total;

        // Total Salary (Payroll)
        $this->db->select_sum('amount', 'total');
        $this->db->where('status', 1);
        $this->db->where('trans_type', 2);
        $this->db->where('transaction_for_table', 'staff_payslip');
        $this->db->where('trans_date >=', $date_from);
        $this->db->where('trans_date <=', $date_to);
        if ($payment_mode_id != null) {
            $this->db->where('payment_method_id', $payment_mode_id);
        }
        if ($collected_by != null) {
            $this->db->where('trans_by', $collected_by);
        }
        $query = $this->db->get('transactions');

        $total_salary = $query->row()->total;

        //caalculating total_salary_refunded
        $this->db->select_sum('amount', 'total');
        $this->db->where('status', 1);
        $this->db->where('trans_type', 1);
        $this->db->where('transaction_for_table', 'staff_payslip');
        $this->db->where('trans_date >=', $date_from);
        $this->db->where('trans_date <=', $date_to);
        if ($payment_mode_id != null) {
            $this->db->where('payment_method_id', $payment_mode_id);
        }
        if ($collected_by != null) {
            $this->db->where('trans_by', $collected_by);
        }
        $query = $this->db->get('transactions');

        $total_salary_refunded = $query->row()->total;

        // Total Loan for Staff
        $this->db->select_sum('amount', 'total');
        $this->db->where('status', 1);
        $this->db->where('trans_type', 2);
        $this->db->where('transaction_for_table', 'staff_loans');
        $this->db->where('trans_date >=', $date_from);
        $this->db->where('trans_date <=', $date_to);
        if ($payment_mode_id != null) {
            $this->db->where('payment_method_id', $payment_mode_id);
        }
        if ($collected_by != null) {
            $this->db->where('trans_by', $collected_by);
        }
        $query = $this->db->get('transactions');

        $total_loan_issued = $query->row()->total;

        //caalculating total_salary_refunded
        $this->db->select_sum('amount', 'total');
        $this->db->where('status', 1);
        $this->db->where('trans_type', 1);
        $this->db->where('transaction_for_table', 'staff_loans');
        $this->db->where('trans_date >=', $date_from);
        $this->db->where('trans_date <=', $date_to);
        if ($payment_mode_id != null) {
            $this->db->where('payment_method_id', $payment_mode_id);
        }
        if ($collected_by != null) {
            $this->db->where('trans_by', $collected_by);
        }
        $query = $this->db->get('transactions');

        $total_loan_recovered = $query->row()->total;

        // Calculate balance
        $balance = ($total_fees_collection + $total_income + $total_expenses_refunded + $total_salary_refunded + $total_loan_recovered) - ($total_expenses + $total_salary + $total_fees_collection_refunded + $total_income_refunded + $total_loan_issued);

        // Pass data to the view
        $data['total_fees_collection'] = $total_fees_collection;
        $data['total_income'] = $total_income;
        $data['total_expenses'] = $total_expenses;
        $data['total_salary'] = $total_salary;
        $data['total_loan_issued'] = $total_loan_issued;
        $data['total_fees_collection_refunded'] = $total_fees_collection_refunded;
        $data['total_income_refunded'] = $total_income_refunded;
        $data['total_expenses_refunded'] = $total_expenses_refunded;
        $data['total_salary_refunded'] = $total_salary_refunded;
        $data['total_loan_recovered'] = $total_loan_recovered;
        $data['balance'] = $balance;
        $data['date_from'] = $date_from;
        $data['date_to'] = $date_to;
        $data['payment_mode_id'] = $payment_mode_id;
        $data['collected_by'] = $collected_by;

        $data['sch_setting'] = $this->sch_setting_detail;
        $this->load->view('layout/header', $data);
        $this->load->view('financereports/overview', $data);
        $this->load->view('layout/footer', $data);
    }

    public function due_fees_old()
    {
        $this->session->set_userdata('top_menu', 'Reports');
        $this->session->set_userdata('sub_menu', 'Reports/finance');
        $this->session->set_userdata('subsub_menu', 'Reports/finance/due_fees');

        $data['feetypeList'] = $this->feetype_model->get();
        $data['classlist'] = $this->class_model->get();

        $date_from = $this->input->post('date_from') ?: date('Y') . '-01-01';
        $date_to   = $this->input->post('date_to') ?: date('Y-m-d');
        $class_id  = $this->input->post('class_id');
        $feetype_id = $this->input->post('feetype_id');

        $data['date_from'] = $date_from;
        $data['date_to']   = $date_to;

        // Subquery for total paid per student_fees_management.id
        $paid_subquery = "(SELECT student_fees_management_id, SUM(paid_amount) as paid_amount 
						FROM student_fees_collections 
						WHERE is_refunded = 0 
						GROUP BY student_fees_management_id) paid_sub";

        $this->db->select('
            s.id as student_id,
            s.admission_no,
            s.firstname,
            c.class,
            ss.session,
            GROUP_CONCAT(
                DISTINCT IF(
                    sfm.discounted_fees > IFNULL(paid_sub.paid_amount, 0),
                    ft.type,
                    NULL
                ) ORDER BY ft.type SEPARATOR ", "
            ) as fee_types,
            SUM(sfm.discounted_fees) as total_assigned,
            SUM(IFNULL(paid_sub.paid_amount, 0)) as total_paid,
            (SUM(sfm.discounted_fees) - SUM(IFNULL(paid_sub.paid_amount, 0))) as total_due
        ');

        $this->db->from('student_fees_management sfm');
        $this->db->join("{$paid_subquery}", 'paid_sub.student_fees_management_id = sfm.id', 'left');
        $this->db->join('students s', 'sfm.student_id = s.id', 'left');
        $this->db->join('classes c', 'sfm.class_id = c.id', 'left');
        $this->db->join('sessions ss', 'sfm.session_id = ss.id', 'left');
        $this->db->join('feetype ft', 'sfm.feetype_id = ft.id', 'left');

        $this->db->where('sfm.status', 1);

        if (!empty($class_id)) {
            $this->db->where('sfm.class_id', $class_id);
        }
        if (!empty($feetype_id)) {
            $this->db->where('sfm.feetype_id', $feetype_id);
        }

        $this->db->group_by('sfm.student_id, sfm.class_id, sfm.session_id');
        $this->db->having('total_due >', 0); // Only show records with due

        $data['due_fees'] = $this->db->get()->result_array();

        $data['sch_setting'] = $this->sch_setting_detail;
        $this->load->view('layout/header', $data);
        $this->load->view('financereports/due_fees', $data);
        $this->load->view('layout/footer', $data);
    }

    public function due_fees()
    {

        $this->session->set_userdata('top_menu', 'Reports');
        $this->session->set_userdata('sub_menu', 'Reports/finance');
        $this->session->set_userdata('subsub_menu', 'Reports/finance/due_fees');

        $data['title'] = "Due Fees";

        $data['feetypeList'] = $this->feetype_model->get();
        $data['classlist'] = $this->class_model->get();
        $data['sessionList'] = $this->session_model->getAllSession();

        $this->load->view('layout/header', $data);
        $this->load->view('financereports/due_fees', $data);
        $this->load->view('layout/footer', $data);
    }

    public function getdueparam()
    {
        $class_id = $this->input->post('class_id') ?? '';
        $feetype_id = $this->input->post('feetype_id') ?? '';
        $session_id = $this->input->post('session_id') ?? '';

        $params = [
            'class_id' => $class_id,
            'feetype_id' => $feetype_id,
            'session_id' => $session_id
        ];

        echo json_encode(['status' => 1, 'error' => '', 'params' => $params]);
    }

    public function dtDueFees()
    {
        $class_id   = $this->input->post('class_id') ?? '';
        $feetype_id = $this->input->post('feetype_id') ?? '';
        $session_id = $this->input->post('session_id') ?? '';

        $result = $this->studentfee_model->getDueFeesReport($class_id, $feetype_id, $session_id);
        $m = json_decode($result);
        $currency_symbol = $this->customlib->getSchoolCurrencyFormat();
        $dt_data = [];

        $total_fees = 0;
        $total_paid = 0;
        $total_due  = 0;

        if (!empty($m->data)) {
            foreach ($m->data as $value) {
                $total_fees += $value->total_assigned;
                $total_paid += $value->total_paid;
                $total_due  += $value->total_due;

                $action_link = '<a href="' . base_url("studentfee/addfees/" . $value->student_id) . '?session_id=' . $value->session_id . '" class="btn btn-sm btn-primary">₹</a>';

                $row = [];
                //$row[] = $value->student_id;
                $row[] = $value->reg_no;
                $student_name = !empty($value->student_name)
                    ? $value->student_name
                    : trim(($value->firstname ?? '') . ' ' . ($value->middlename ?? '') . ' ' . ($value->lastname ?? ''));
                $row[] = $student_name !== '' ? $student_name : '-';
                $class_section = $value->class;
                if (!empty($value->section)) {
                    $class_section .= " (" . $value->section . ")";
                }
                $row[] = $class_section;
                $row[] = $value->roll_no;
                $row[] = !empty($value->recommendationNumber) ? $value->recommendationNumber : "";
                $row[] = $value->session;
                $row[] = $value->fee_types;
                $row[] = $currency_symbol . amountFormat($value->total_assigned);
                $row[] = $currency_symbol . amountFormat($value->total_paid);
                $row[] = $currency_symbol . amountFormat($value->total_due);
                $row[] = $action_link;

                $dt_data[] = $row;
            }

            // Optional footer total row
            $dt_data[] = [
                "<b>Total</b>",
                "",
                "",
                "",
                "",
                "",
                "",
                "<b>{$currency_symbol}" . amountFormat($total_fees) . "</b>",
                "<b>{$currency_symbol}" . amountFormat($total_paid) . "</b>",
                "<b>{$currency_symbol}" . amountFormat($total_due) . "</b>",
                ""
            ];
        }

        $json_data = [
            "draw"            => intval($m->draw),
            "recordsTotal"    => intval($m->recordsTotal),
            "recordsFiltered" => intval($m->recordsFiltered),
            "data"            => $dt_data,
        ];

        echo json_encode($json_data);
    }

    public function onlinefees_report()
    {
        $this->session->set_userdata('top_menu', 'Reports');
        $this->session->set_userdata('sub_menu', 'Reports/finance');
        $this->session->set_userdata('subsub_menu', 'Reports/finance/onlinefees_report');
        $data['searchlist'] = $this->customlib->get_searchtype();
        $data['group_by']   = $this->customlib->get_groupby();

        if (isset($_POST['search_type']) && $_POST['search_type'] != '') {
            $dates               = $this->customlib->get_betweendate($_POST['search_type']);
            $data['search_type'] = $_POST['search_type'];
        } else {
            $dates               = $this->customlib->get_betweendate('this_year');
            $data['search_type'] = '';
        }

        $collection = array();
        $start_date = date('Y-m-d', strtotime($dates['from_date']));
        $end_date   = date('Y-m-d', strtotime($dates['to_date']));
        $this->form_validation->set_rules('search_type', $this->lang->line('search_type'), 'trim|required|xss_clean');

        if ($this->form_validation->run() == false) {
            $data['collectlist'] = array();
        } else {
            $data['collectlist'] = $this->studentfeemaster_model->getOnlineFeeCollectionReport($start_date, $end_date);
        }

        $data['sch_setting'] = $this->sch_setting_detail;
        $this->load->view('layout/header', $data);
        $this->load->view('financereports/onlineFeesReport', $data);
        $this->load->view('layout/footer', $data);
    }

    public function duefeesremark()
    {
        if (!$this->rbac->hasPrivilege('balance_fees_report_with_remark', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Reports');
        $this->session->set_userdata('sub_menu', 'Reports/finance');
        $this->session->set_userdata('subsub_menu', 'Reports/finance/duefeesremark');
        $data                = array();
        $data['title']       = 'student fees';
        $class               = $this->class_model->get();
        $data['classlist']   = $class;
        $data['sch_setting'] = $this->sch_setting_detail;
        $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('section_id', $this->lang->line('section'), 'trim|required|xss_clean');

        if ($this->form_validation->run() == true) {
            $date               = date('Y-m-d');
            $class_id           = $this->input->post('class_id');
            $section_id         = $this->input->post('section_id');
            $data['class_id']   = $class_id;
            $data['section_id'] = $section_id;
            $date               = date('Y-m-d');
            $student_due_fee    = $this->studentfee_model->getDueStudentFeesByDateClassSection($class_id, $section_id, $date);
            $students = array();
            if (!empty($student_due_fee)) {
                foreach ($student_due_fee as $student_due_fee_key => $student_due_fee_value) {

                    $amt_due = ($student_due_fee_value['is_system']) ? $student_due_fee_value['previous_balance_amount'] : $student_due_fee_value['amount'];

                    $a = json_decode($student_due_fee_value['amount_detail']);


                    if (!empty($a)) {
                        $amount          = 0;
                        $amount_discount = 0;
                        $amount_fine     = 0;

                        foreach ($a as $a_key => $a_value) {
                            $amount          = $amount + $a_value->amount;
                            $amount_discount = $amount_discount + $a_value->amount_discount;
                            $amount_fine     = $amount_fine + $a_value->amount_fine;
                        }
                        if ($amt_due <= ($amount + $amount_discount)) {
                            unset($student_due_fee[$student_due_fee_key]);
                        } else {

                            if (!array_key_exists($student_due_fee_value['student_session_id'], $students)) {
                                $students[$student_due_fee_value['student_session_id']] = $this->add_new_student($student_due_fee_value);
                            }

                            $students[$student_due_fee_value['student_session_id']]['fees'][] = array(
                                'is_system' => $student_due_fee_value['is_system'],
                                'amount'          => $amt_due,
                                'amount_deposite' => $amount,
                                'amount_discount' => $amount_discount,
                                'amount_fine'     => $amount_fine,
                                'fee_group'       => $student_due_fee_value['fee_group'],
                                'fee_type'        => $student_due_fee_value['fee_type'],
                                'fee_code'        => $student_due_fee_value['fee_code'],

                            );
                        }
                    } else {
                        $amount          = 0;
                        $amount_discount = 0;

                        if ($amt_due <= ($amount + $amount_discount)) {
                            unset($student_due_fee[$student_due_fee_key]);
                        } else {
                            if (!array_key_exists($student_due_fee_value['student_session_id'], $students)) {

                                $students[$student_due_fee_value['student_session_id']] = $this->add_new_student($student_due_fee_value);
                            }
                            $students[$student_due_fee_value['student_session_id']]['fees'][] = array(
                                'is_system' => $student_due_fee_value['is_system'],
                                'amount'          => $amt_due,
                                'amount_deposite' => 0,
                                'amount_discount' => 0,
                                'amount_fine'     => 0,
                                'fee_group'       => $student_due_fee_value['fee_group'],
                                'fee_type'        => $student_due_fee_value['fee_type'],
                                'fee_code'        => $student_due_fee_value['fee_code'],
                            );
                        }
                    }
                }
            }

            $data['student_remain_fees'] = $students;
        }
        $data['start_month'] = $this->sch_setting_detail->start_month;
        $this->load->view('layout/header', $data);
        $this->load->view('financereports/duefeesremark', $data);
        $this->load->view('layout/footer', $data);
    }

    public function add_new_student($student)
    {
        $new_student = array(
            'id'                 => $student['id'],
            'student_session_id' => $student['student_session_id'],
            'class'              => $student['class'],
            'section_id'         => $student['section_id'],
            'section'            => $student['section'],
            'admission_no'       => $student['admission_no'],
            'roll_no'            => $student['roll_no'],
            'admission_date'     => $student['admission_date'],
            'firstname'          => $student['firstname'],
            'middlename'         => $student['middlename'],
            'lastname'           => $student['lastname'],
            'image'              => $student['image'],
            'mobileno'           => $student['mobileno'],
            'email'              => $student['email'],
            'state'              => $student['state'],
            'city'               => $student['city'],
            'pincode'            => $student['pincode'],
            'religion'           => $student['religion'],
            'dob'                => $student['dob'],
            'current_address'    => $student['current_address'],
            'permanent_address'  => $student['permanent_address'],
            'category_id'        => $student['category_id'],
            'category'           => $student['category'],
            'adhar_no'           => $student['adhar_no'],
            'samagra_id'         => $student['samagra_id'],
            'bank_account_no'    => $student['bank_account_no'],
            'bank_name'          => $student['bank_name'],
            'ifsc_code'          => $student['ifsc_code'],
            'guardian_name'      => $student['guardian_name'],
            'guardian_relation'  => $student['guardian_relation'],
            'guardian_phone'     => $student['guardian_phone'],
            'guardian_address'   => $student['guardian_address'],
            'is_active'          => $student['is_active'],
            'father_name'        => $student['father_name'],
            'rte'                => $student['rte'],
            'gender'             => $student['gender'],

        );
        return $new_student;
    }

    public function printduefeesremark()
    {
        if (!$this->rbac->hasPrivilege('fees_statement', 'can_view')) {
            access_denied();
        }

        $date                = date('Y-m-d');
        $class_id            = $this->input->post('class_id');
        $section_id          = $this->input->post('section_id');
        $data['class_id']    = $class_id;
        $data['section_id']  = $section_id;
        $data['class']       = $this->class_model->get($class_id);
        $data['section']     = $this->section_model->get($section_id);
        $date                = date('Y-m-d');
        $data['sch_setting'] = $this->sch_setting_detail;
        $student_due_fee     = $this->studentfee_model->getDueStudentFeesByDateClassSection($class_id, $section_id, $date);

        $students = array();

        if (!empty($student_due_fee)) {
            foreach ($student_due_fee as $student_due_fee_key => $student_due_fee_value) {

                $amt_due = ($student_due_fee_value['is_system']) ? $student_due_fee_value['previous_balance_amount'] : $student_due_fee_value['amount'];

                $a = json_decode($student_due_fee_value['amount_detail']);
                if (!empty($a)) {
                    $amount          = 0;
                    $amount_discount = 0;
                    $amount_fine     = 0;

                    foreach ($a as $a_key => $a_value) {
                        $amount          = $amount + $a_value->amount;
                        $amount_discount = $amount_discount + $a_value->amount_discount;
                        $amount_fine     = $amount_fine + $a_value->amount_fine;
                    }
                    if ($amt_due <= ($amount + $amount_discount)) {
                        unset($student_due_fee[$student_due_fee_key]);
                    } else {

                        if (!array_key_exists($student_due_fee_value['student_session_id'], $students)) {
                            $students[$student_due_fee_value['student_session_id']] = $this->add_new_student($student_due_fee_value);
                        }

                        $students[$student_due_fee_value['student_session_id']]['fees'][] = array(
                            'is_system' => $student_due_fee_value['is_system'],
                            'amount'          => $amt_due,
                            'amount_deposite' => $amount,
                            'amount_discount' => $amount_discount,
                            'amount_fine'     => $amount_fine,
                            'fee_group'       => $student_due_fee_value['fee_group'],
                            'fee_type'        => $student_due_fee_value['fee_type'],
                            'fee_code'        => $student_due_fee_value['fee_code'],
                        );
                    }
                } else {
                    $amount          = 0;
                    $amount_discount = 0;

                    if ($amt_due <= ($amount + $amount_discount)) {
                        unset($student_due_fee[$student_due_fee_key]);
                    } else {
                        if (!array_key_exists($student_due_fee_value['student_session_id'], $students)) {
                            $students[$student_due_fee_value['student_session_id']] = $this->add_new_student($student_due_fee_value);
                        }
                        $students[$student_due_fee_value['student_session_id']]['fees'][] = array(
                            'is_system' => $student_due_fee_value['is_system'],
                            'amount'          => $amt_due,
                            'amount_deposite' => 0,
                            'amount_discount' => 0,
                            'amount_fine'     => 0,
                            'fee_group'       => $student_due_fee_value['fee_group'],
                            'fee_type'        => $student_due_fee_value['fee_type'],
                            'fee_code'        => $student_due_fee_value['fee_code'],
                        );
                    }
                }
            }
        }

        $data['student_remain_fees'] = $students;
        $page = $this->load->view('financereports/_printduefeesremark', $data, true);
        echo json_encode(array('status' => 1, 'page' => $page));
    }

    public function income()
    {
        $this->session->set_userdata('top_menu', 'Reports');
        $this->session->set_userdata('sub_menu', 'Reports/finance');
        $this->session->set_userdata('subsub_menu', 'Reports/finance/income');
        $data['searchlist'] = $this->customlib->get_searchtype();
        $this->load->view('layout/header', $data);
        $this->load->view('financereports/income', $data);
        $this->load->view('layout/footer', $data);
    }

    public function searchreportvalidation()
    {
        $this->form_validation->set_rules('search_type', $this->lang->line('search_type'), 'trim|required|xss_clean');

        if ($this->form_validation->run() == false) {
            $error = array();

            $error['search_type'] = form_error('search_type');

            $array = array('status' => 0, 'error' => $error);
            echo json_encode($array);
        } else {
            $search_type = $this->input->post('search_type');
            $date_from   = "";
            $date_to     = "";
            if ($search_type == 'period') {

                $date_from = $this->input->post('date_from');
                $date_to   = $this->input->post('date_to');
            }

            $params = array('search_type' => $search_type, 'date_from' => $date_from, 'date_to' => $date_to);
            $array  = array('status' => 1, 'error' => '', 'params' => $params);
            echo json_encode($array);
        }
    }

    public function getincomelistbydt()
    {
        $search_type = $this->input->post('search_type');
        $date_from   = $this->input->post('date_from');
        $date_to     = $this->input->post('date_to');

        if ($search_type == "") {
            $dates               = $this->customlib->get_betweendate('this_year');
            $data['search_type'] = '';
        } else {
            $dates               = $this->customlib->get_betweendate($_POST['search_type']);
            $data['search_type'] = $_POST['search_type'];
        }

        $start_date = date('Y-m-d', strtotime($dates['from_date']));
        $end_date   = date('Y-m-d', strtotime($dates['to_date']));

        $data['label'] = date($this->customlib->getSchoolDateFormat(), strtotime($start_date)) . " " . $this->lang->line('to') . " " . date($this->customlib->getSchoolDateFormat(), strtotime($end_date));

        $incomeList = $this->income_model->search("", $start_date, $end_date);

        $incomeList      = json_decode($incomeList);
        $currency_symbol = $this->customlib->getSchoolCurrencyFormat();
        $dt_data         = array();
        $grand_total     = 0;
        if (!empty($incomeList->data)) {
            foreach ($incomeList->data as $key => $value) {
                if (!isset($value->is_refunded) || $value->is_refunded != 1) {
                    $grand_total += $value->amount;
                }

                $row   = array();
                $row[] = $value->name;
                $row[] = $value->invoice_no;
                $row[] = $value->income_category;
                $row[] = date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($value->date));
                $row[] = $currency_symbol . amountFormat($value->amount);
                $dt_data[] = $row;
            }
            $footer_row   = array();
            $footer_row[] = "";
            $footer_row[] = "";
            $footer_row[] = "";
            $footer_row[] = "<b>" . $this->lang->line('grand_total') . "</b>";
            $footer_row[] = $currency_symbol . amountFormat($grand_total);
            $dt_data[]    = $footer_row;
        }

        $json_data = array(
            "draw"            => intval($incomeList->draw),
            "recordsTotal"    => intval($incomeList->recordsTotal),
            "recordsFiltered" => intval($incomeList->recordsFiltered),
            "data"            => $dt_data,
        );
        echo json_encode($json_data);
    }

    public function expense()
    {
        $this->session->set_userdata('top_menu', 'Reports');
        $this->session->set_userdata('sub_menu', 'Reports/finance');
        $this->session->set_userdata('subsub_menu', 'Reports/finance/expense');
        $data['searchlist']  = $this->customlib->get_searchtype();
        $data['date_type']   = $this->customlib->date_type();
        $data['date_typeid'] = '';

        $this->form_validation->set_rules('search_type', $this->lang->line('search_type'), 'trim|required|xss_clean');

        if ($this->form_validation->run() == false) {
            $dates               = $this->customlib->get_betweendate('this_year');
            $data['search_type'] = '';
        } else {
            $dates               = $this->customlib->get_betweendate($_POST['search_type']);
            $data['search_type'] = $_POST['search_type'];
        }

        $start_date = date('Y-m-d', strtotime($dates['from_date']));
        $end_date   = date('Y-m-d', strtotime($dates['to_date']));

        $data['label'] = date($this->customlib->getSchoolDateFormat(), strtotime($start_date)) . " " . $this->lang->line('to') . " " . date($this->customlib->getSchoolDateFormat(), strtotime($end_date));
        $this->load->view('layout/header', $data);
        $this->load->view('financereports/expense', $data);
        $this->load->view('layout/footer', $data);
    }

    public function getexpenselistbydt()
    {
        $search_type = $this->input->post('search_type');
        $date_from   = $this->input->post('date_from');
        $date_to     = $this->input->post('date_to');

        if ($search_type == "") {
            $dates               = $this->customlib->get_betweendate('this_year');
            $data['search_type'] = '';
        } else {
            $dates               = $this->customlib->get_betweendate($_POST['search_type']);
            $data['search_type'] = $_POST['search_type'];
        }

        $start_date = date('Y-m-d', strtotime($dates['from_date']));
        $end_date   = date('Y-m-d', strtotime($dates['to_date']));

        $data['label'] = date($this->customlib->getSchoolDateFormat(), strtotime($start_date)) . " " . $this->lang->line('to') . " " . date($this->customlib->getSchoolDateFormat(), strtotime($end_date));
        $expenseList   = $this->expense_model->search('', $start_date, $end_date);

        $m               = json_decode($expenseList);
        $currency_symbol = $this->customlib->getSchoolCurrencyFormat();
        $dt_data         = array();
        $grand_total     = 0;
        if (!empty($m->data)) {
            foreach ($m->data as $key => $value) {
                if (!isset($value->is_refunded) || $value->is_refunded != 1) {
                    $grand_total += $value->amount;
                }

                $row       = array();
                $row[]     = date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($value->date));
                $row[]     = $value->exp_category;
                $row[]     = $value->name;
                $row[]     = $value->invoice_no;
                $row[]     = $currency_symbol . amountFormat($value->amount);
                $dt_data[] = $row;
            }
            $footer_row[] = "";
            $footer_row[] = "";
            $footer_row[] = "";
            $footer_row[] = "<b>" . $this->lang->line('grand_total') . "</b>";
            $footer_row[] = "<b>" . $currency_symbol . amountFormat($grand_total) . "</b>";
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

    public function collection_report()
    {
        // Set session data for menu highlighting
        $this->session->set_userdata('top_menu', 'Reports');
        $this->session->set_userdata('sub_menu', 'Reports/finance');
        $this->session->set_userdata('subsub_menu', 'Reports/finance/collection_report');

        $data = array();
        $data['title'] = $this->lang->line('collection_report'); // Title for the report page

        // Fetch lists for dropdowns
        $data['collect_by'] = $this->studentfeemaster_model->get_feesreceived_by();
        $data['feetypeList'] = $this->feetype_model->get();
        $data['classlist'] = $this->class_model->get();
        $data['sessionList'] = $this->session_model->getAllSession();
        $this->load->model('account_department_model');
        $data['account_departments'] = $this->account_department_model->get();

        $data['paymentMethods'] = $this->accounts_model->getPaymentMethods();

        // Load the views
        $this->load->view('layout/header', $data);
        $this->load->view('financereports/collection_report', $data);
        $this->load->view('layout/footer', $data);
    }

    public function getcollectionportparam()
    {

        $date_from = $this->input->post('date_from') ? $this->input->post('date_from') : "";
        $date_to = $this->input->post('date_to') ? $this->input->post('date_to') : "";
        $class_id = $this->input->post('class_id');
        $feetype_id = $this->input->post('feetype_id');
        $session_id = $this->input->post('session_id');
        $payment_mode_id = $this->input->post('payment_mode_id');
        $collected_by = $this->input->post('collect_by');
        $account_department_id = $this->input->post('account_department_id');
        $gender = $this->input->post('gender');

        $status_filter = $this->input->post('status_filter');
        $params = array('date_from' => $date_from, 'date_to' => $date_to, 'class_id' => $class_id, 'feetype_id' => $feetype_id, 'session_id' => $session_id, 'payment_mode_id' => $payment_mode_id, 'collected_by' => $collected_by, 'account_department_id' => $account_department_id, 'gender' => $gender, 'status_filter' => $status_filter);
        $array  = array('status' => 1, 'error' => '', 'params' => $params);
        echo json_encode($array);
    }

    public function dtcollectionreport()
    {
        // Check if the form has been submitted
        if ($this->input->server('REQUEST_METHOD') == 'POST') {
            $date_from = $this->input->post('date_from') ? $this->input->post('date_from') : "";
            $date_to = $this->input->post('date_to') ? $this->input->post('date_to') : "";
            $class_id = $this->input->post('class_id');
            $feetype_id = $this->input->post('feetype_id');
            $session_id = $this->input->post('session_id');
            $payment_mode_id = $this->input->post('payment_mode_id');
            $collected_by = $this->input->post('collected_by');
            $account_department_id = $this->input->post('account_department_id');
            $gender = $this->input->post('gender');
            $status_filter = $this->input->post('status_filter');

            // Fetch filtered data based on user input
            $result = $this->studentfeemaster_model->searchFeesCollections($date_from, $date_to, $class_id, $feetype_id, $session_id, $payment_mode_id, $collected_by, $account_department_id, $gender, $status_filter);
        } else {
            // Fetch all data if no filters are applied
            $result = $this->studentfeemaster_model->searchFeesCollections();
        }

        //print_r($result); die;

        $m               = json_decode($result);
        $currency_symbol = $this->customlib->getSchoolCurrencyFormat();
        $dt_data         = array();

        $total_paid_amount = 0;

        if (!empty($m->data)) {
            $count = 1;

            foreach ($m->data as $key => $value) {

                $is_refunded = $value->is_refunded;
                if ($is_refunded != 1) {
                    $total_paid_amount += $value->paid_amount;
                }

                $row = array();

                //$row[]      = $value->id;
                $row[]      = $this->customlib->dateformat($value->approved_date);
                $row[]      = $value->account_department_name;
                $row[]      = $value->reg_id;
                $row[]      = $value->student_name;
                $class_section = $value->class_name;
                if (!empty($value->section_name)) {
                    $class_section .= " (" . $value->section_name . ")";
                }
                $row[]      = $class_section;
                $row[]      = $value->roll_no;
                $row[]      = !empty($value->recommendationNumber) ? $value->recommendationNumber : "";
                $row[]      = $value->feetype_name;
                $row[]      = $currency_symbol . amountFormat($value->paid_amount);
                $row[]      = $value->payment_mode_title;
                $row[]      = $value->payment_hash;
                $row[]      = $value->collected_by;
                $row[]      = $is_refunded ? "<span class='badge'>Refunded</span>" : $value->approved_by;

                $dt_data[]  = $row;
                $count++;
            }

            $footer_row[] = "";
            $footer_row[] = "";
            $footer_row[] = "";
            $footer_row[] = "";
            $footer_row[] = "";
            $footer_row[] = "";
            $footer_row[] = "";
            $footer_row[] = "<b>Total</b>";
            $footer_row[] = $currency_symbol . amountFormat($total_paid_amount);
            $footer_row[] = "";
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

    public function staff_advances()
    {
        $this->session->set_userdata('top_menu', 'Reports');
        $this->session->set_userdata('sub_menu', 'Reports/finance');
        $this->session->set_userdata('subsub_menu', 'Reports/finance/staff_advances');

        $data['title'] = "Staff Loan Report";
        $data['paymentMethods'] = $this->accounts_model->getPaymentMethods();
        $data['staffList'] = $this->staff_model->get(); // Fetch all staff members

        $this->load->view('layout/header', $data);
        $this->load->view('financereports/staff_loan_report', $data);
        $this->load->view('layout/footer', $data);
    }


    public function getloanparam()
    {
        $date_from = $this->input->post('date_from') ?? '';
        $date_to = $this->input->post('date_to') ?? '';
        $staff_id = $this->input->post('staff_id') ?? '';
        $payment_method_id = $this->input->post('payment_method_id') ?? '';

        $params = [
            'date_from' => $date_from,
            'date_to' => $date_to,
            'staff_id' => $staff_id,
            'payment_method_id' => $payment_method_id
        ];

        echo json_encode(['status' => 1, 'error' => '', 'params' => $params]);
    }

    public function dtloanreport()
    {
        $date_from = $this->input->post('date_from') ?? '';
        $date_to = $this->input->post('date_to') ?? '';
        $staff_id = $this->input->post('staff_id') ?? '';
        $payment_method_id = $this->input->post('payment_method_id') ?? '';

        $result = $this->staff_model->getStaffLoanReport($date_from, $date_to, $staff_id, $payment_method_id);

        $m               = json_decode($result);
        $currency_symbol = $this->customlib->getSchoolCurrencyFormat();
        $dt_data         = array();

        $total_paid_amount = 0;
        $total_loan_amount = 0;

        if (!empty($m->data)) {
            $count = 1;

            foreach ($m->data as $key => $value) {

                $total_paid_amount += $value->total_paid;
                $total_loan_amount += $value->loan_amount;

                $row = array();

                $row[]      = $this->customlib->dateformat($value->loan_date);
                $row[]      = $value->staff_name;
                $row[]      = $currency_symbol . amountFormat($value->loan_amount);
                $row[]      = $currency_symbol . amountFormat($value->total_paid);
                $row[]      = $value->payment_method;
                $row[]      = $value->description;
                $row[]      = $value->created_by;
                //$row[]      = $is_refunded ? "<span class='badge'>Refunded</span>" : "Credit";

                $dt_data[]  = $row;
                $count++;
            }

            $footer_row[] = "";
            $footer_row[] = "<b>Total</b>";
            $footer_row[] = $currency_symbol . amountFormat($total_loan_amount);
            $footer_row[] = $currency_symbol . amountFormat($total_paid_amount);
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

    public function staff_advance_payments()
    {
        $this->session->set_userdata('top_menu', 'Reports');
        $this->session->set_userdata('sub_menu', 'Reports/finance');
        $this->session->set_userdata('subsub_menu', 'Reports/finance/staff_advance_payments');

        $data['title'] = "Staff Loan Report";
        $data['paymentMethods'] = $this->accounts_model->getPaymentMethods();
        $data['staffList'] = $this->staff_model->get(); // Fetch all staff members

        $this->load->view('layout/header', $data);
        $this->load->view('financereports/staff_loanpayment_report', $data);
        $this->load->view('layout/footer', $data);
    }


    public function getloanpaymentsparam()
    {
        $date_from = $this->input->post('date_from') ?? '';
        $date_to = $this->input->post('date_to') ?? '';
        $staff_id = $this->input->post('staff_id') ?? '';
        $payment_method_id = $this->input->post('payment_method_id') ?? '';

        $params = [
            'date_from' => $date_from,
            'date_to' => $date_to,
            'staff_id' => $staff_id,
            'payment_method_id' => $payment_method_id
        ];

        echo json_encode(['status' => 1, 'error' => '', 'params' => $params]);
    }

    public function dtloanpaymentsreport()
    {
        $date_from = $this->input->post('date_from') ?? '';
        $date_to = $this->input->post('date_to') ?? '';
        $staff_id = $this->input->post('staff_id') ?? '';
        $payment_method_id = $this->input->post('payment_method_id') ?? '';

        $result = $this->staff_model->getStaffLoanPaymentReport($date_from, $date_to, $staff_id, $payment_method_id);

        $m               = json_decode($result);
        $currency_symbol = $this->customlib->getSchoolCurrencyFormat();
        $dt_data         = array();

        $total_paid_amount = 0;

        if (!empty($m->data)) {
            $count = 1;

            foreach ($m->data as $key => $value) {

                $total_paid_amount += $value->paid_amount;

                $row = array();

                $row[]      = $this->customlib->dateformat($value->payment_date);
                $row[]      = $value->staff_name;
                $row[]      = $currency_symbol . amountFormat($value->paid_amount);
                $row[]      = $value->payment_method;
                $row[]      = $value->note;
                $row[]      = $value->created_by;

                $dt_data[]  = $row;
                $count++;
            }

            $footer_row[] = "";
            $footer_row[] = "<b>Total</b>";
            $footer_row[] = $currency_symbol . amountFormat($total_paid_amount);
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

    public function payroll()
    {
        $this->session->set_userdata('top_menu', 'Reports');
        $this->session->set_userdata('sub_menu', 'Reports/finance');
        $this->session->set_userdata('subsub_menu', 'Reports/finance/payroll');

        $data['all_account_staff'] = $this->studentfeemaster_model->get_feesreceived_by();
        $data['paymentMethods'] = $this->accounts_model->getPaymentMethods();
        $data['roles'] = $this->role_model->get();
        $this->load->model('account_department_model');
        $data['account_departments'] = $this->account_department_model->get();

        $data['searchlist']  = $this->customlib->get_searchtype();
        $data['date_type']   = $this->customlib->date_type();
        $data['date_typeid'] = '';


        $this->load->view('layout/header', $data);
        $this->load->view('financereports/payroll', $data);
        $this->load->view('layout/footer', $data);
    }

    public function getpayrollreportparam()
    {
        $search_type = $this->input->post('search_type');
        $status_type        = $this->input->post('status_type');
        $role_id        = $this->input->post('role_id');

        $payment_mode_id = $this->input->post('payment_mode_id');
        $collected_by = $this->input->post('paid_by');
        $account_department_id = $this->input->post('account_department_id');

        $date_from = "";
        $date_to   = "";

        if ($search_type == 'period') {

            $date_from = $this->input->post('date_from');
            $date_to   = $this->input->post('date_to');
        }

        $params = array('search_type' => $search_type, 'status_type' => $status_type, 'role_id' => $role_id, 'date_from' => $date_from, 'date_to' => $date_to, 'payment_mode_id' => $payment_mode_id, 'paid_by' => $collected_by, 'account_department_id' => $account_department_id);
        $array  = array('status' => 1, 'error' => '', 'params' => $params);
        echo json_encode($array);
    }

    public function dtpayrollreport()
    {
        $status_type    = $this->input->post('status_type');
        $payment_mode_id    = $this->input->post('payment_mode_id');
        $account_department_id    = $this->input->post('account_department_id');
        $paid_by         = $this->input->post('paid_by');
        $role_id         = $this->input->post('role_id');

        if (isset($_POST['search_type']) && $_POST['search_type'] != '') {

            $dates               = $this->customlib->get_betweendate($_POST['search_type']);
            $data['search_type'] = $_POST['search_type'];
        } else {

            $dates               = $this->customlib->get_betweendate('this_year');
            $data['search_type'] = '';
        }

        $start_date = date('Y-m-d', strtotime($dates['from_date']));
        $end_date   = date('Y-m-d', strtotime($dates['to_date']));

        $data['label']        = date($this->customlib->getSchoolDateFormat(), strtotime($start_date)) . " " . $this->lang->line('to') . " " . date($this->customlib->getSchoolDateFormat(), strtotime($end_date));
        $data['payment_mode'] = $this->payment_mode;

        //$result              = $this->payroll_model->getbetweenpayrollReport($start_date, $end_date, $payment_mode_id, $paid_by, $role_id, $status_type);
        $result              = $this->payroll_model->searchpayroll($start_date, $end_date, $payment_mode_id, $paid_by, $role_id, $status_type, $account_department_id);

        $m               = json_decode($result);
        $currency_symbol = $this->customlib->getSchoolCurrencyFormat();
        $dt_data         = array();

        $basic = 0;
        $gross = 0;
        $net = 0;
        $earnings = 0;
        $deduction = 0;
        $tax = 0;
        $total_salary_paid = 0;

        if (!empty($m->data)) {
            $count = 1;

            foreach ($m->data as $key => $value) {

                $basic += $value->basic;
                $gross += $value->basic + $value->total_allowance - $value->total_deduction;
                $earnings += $value->total_allowance;
                $deduction += $value->total_deduction;

                if ($value->tax != '') {
                    $taxdata = $value->tax;
                } else {
                    $taxdata = 0;
                }
                $tax += $taxdata;
                $total = 0;
                $grd_total = 0;

                $row = array();
                //' ' . $value->surname .
                $popoverContent = '<span data-toggle="popover" class="detail_popover" data-original-title="" title="">
                    <a href="' . base_url() . 'admin/staff/profile/' . $value->staff_id . '">
                        ' . $value->name .  ' (' . $value->employee_id . ')
                    </a>
                </span><br>' . $value->user_type . '<br>' . $this->lang->line(strtolower($value->month)) . ' - ' . $value->year;

                //$row[]      = $value->id;
                $row[]      = $popoverContent;
                //$row[]      = $value->user_type;
                $row[]      = $currency_symbol . amountFormat($value->basic);
                $row[]      = $currency_symbol . amountFormat($value->total_allowance);
                $row[]      = $currency_symbol . amountFormat($value->total_deduction);

                $grossPrint = $value->basic + $value->total_allowance - $value->total_deduction;
                $row[]      = $currency_symbol . amountFormat($grossPrint);

                $gross_amount = $value->basic + $value->total_allowance - $value->total_deduction - $taxdata;
                $row[]      = $currency_symbol . amountFormat($gross_amount);

                $row[]      = strtoupper($value->status) . '<br>' . $value->payment_date;

                if ($value->status == 'paid') {
                    $salary_paid = $value->net_salary;
                    $total_salary_paid += $salary_paid;
                    $is_paid =  $currency_symbol . amountFormat($salary_paid);
                } else {
                    $is_paid =  '-';
                }
                //$row[]      = $value->payment_date;
                $row[]      = $is_paid;

                $row[] = substr($value->mode, 0, 10);

                $row[]      = $value->generated_by;
                $row[]      = $value->paid_by;

                $dt_data[]  = $row;
                $count++;
            }

            $grandtotal = $gross - $tax;

            $footer_row[] = "<b>Total</b> ";
            //$footer_row[] = "";
            $footer_row[] = "Basic: " . ($basic > 0) ? $currency_symbol . amountFormat($basic) : "";
            $footer_row[] = "Earning: " . ($earnings > 0) ? $currency_symbol . amountFormat($earnings) : "";
            $footer_row[] = "Deduction: " . ($deduction > 0) ? $currency_symbol . amountFormat($deduction) : "";
            $footer_row[] = "Gross: " . ($gross > 0) ? $currency_symbol . amountFormat($gross) : "";
            $footer_row[] = "Net Salary: " . ($grandtotal > 0) ? $currency_symbol . amountFormat($grandtotal) : "";
            $footer_row[] = "";
            //$footer_row[] = "";
            $footer_row[] = "Paid: " . ($total_salary_paid > 0) ? $currency_symbol . amountFormat($total_salary_paid) : "";
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

    public function incomegroup()
    {
        $this->session->set_userdata('top_menu', 'Reports');
        $this->session->set_userdata('sub_menu', 'Reports/finance');
        $this->session->set_userdata('subsub_menu', 'Reports/finance/incomegroup');

        $data['collect_by'] = $this->studentfeemaster_model->get_feesreceived_by();
        $data['paymentMethods'] = $this->accounts_model->getPaymentMethods();
        $this->load->model('account_department_model');
        $data['account_departments'] = $this->account_department_model->get();
        $payment_mode_id = null;
        $collected_by = null;

        $data['searchlist']  = $this->customlib->get_searchtype();
        $data['date_type']   = $this->customlib->date_type();
        $data['date_typeid'] = '';
        $data['headlist']    = $this->incomehead_model->get();
        $this->load->view('layout/header', $data);
        $this->load->view('financereports/incomegroup', $data);
        $this->load->view('layout/footer', $data);
    }

    public function dtincomegroupreport()
    {
        $search_type        = $this->input->post('search_type');
        $date_from          = $this->input->post('date_from');
        $date_to            = $this->input->post('date_to');
        $head               = $this->input->post('head');
        $payment_mode_id    = $this->input->post('payment_mode_id');
        $collect_by         = $this->input->post('collect_by');
        $account_department_id = $this->input->post('account_department_id');

        if (isset($search_type) && $search_type != '') {

            $dates               = $this->customlib->get_betweendate($search_type);
            $data['search_type'] = $_POST['search_type'];
        } else {

            $dates               = $this->customlib->get_betweendate('this_year');
            $data['search_type'] = '';
        }
        $data['head_id'] = $head_id = "";
        if (isset($_POST['head']) && $_POST['head'] != '') {
            $data['head_id'] = $head_id = $_POST['head'];
        }

        $start_date = date('Y-m-d', strtotime($dates['from_date']));
        $end_date   = date('Y-m-d', strtotime($dates['to_date']));

        $data['label']   = date($this->customlib->getSchoolDateFormat(), strtotime($start_date)) . " " . $this->lang->line('to') . " " . date($this->customlib->getSchoolDateFormat(), strtotime($end_date));
        $incomeList      = $this->income_model->searchincomegroup($start_date, $end_date, $head_id, $payment_mode_id, $collect_by, $account_department_id);
        $m               = json_decode($incomeList);
        $currency_symbol = $this->customlib->getSchoolCurrencyFormat();
        $dt_data         = array();
        $grand_total     = 0;

        if (!empty($m->data)) {
            $grd_total  = 0;
            $inchead_id = 0;
            $count      = 0;
            foreach ($m->data as $key => $value) {
                $income_head[$value->head_id][] = $value;
            }

            foreach ($m->data as $key => $value) {
                $inc_head_id  = $value->head_id;
                $total_amount = "<b>" . $value->amount . "</b>";
                //$grd_total += $value->amount;

                $is_refunded = $value->is_refunded;
                if ($is_refunded != 1) {
                    $grd_total += $value->amount;
                }

                $row = array();
                if ($inchead_id == $inc_head_id) {
                    $row[] = "";
                    $count++;
                } else {
                    $row[] = $value->income_category;
                    $count = 0;
                }
                //$row[]      = $value->id;
                $row[]      = $value->name;
                $row[]      = $value->note;
                $row[]      = date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($value->date));
                $row[]      = $value->invoice_no;
                $row[]      = $value->made_by;
                $row[]      = amountFormat($value->amount);
                $row[]      = $value->mode;
                $row[]      = $is_refunded ? "<span class='badge'>Refunded</span>" : "Credit";
                $dt_data[]  = $row;
                $inchead_id = $value->head_id;
                $sub_total  = 0;
                /* if ($count == (count($income_head[$value->head_id]) - 1)) {
                    foreach ($income_head[$value->head_id] as $inc_headkey => $inc_headvalue) {
                        $sub_total += $inc_headvalue->amount;
                    }
                    $amount_row   = array();
                    $amount_row[] = "";
                    $amount_row[] = "";
                    $amount_row[] = "";
                    $amount_row[] = "";
                    $amount_row[] = "";
                    $amount_row[] = "";
                    $amount_row[] = "<b>" . $this->lang->line('sub_total') . "</b>";
                    $amount_row[] = "<b>" . $currency_symbol . amountFormat($sub_total) . "</b>";
                    $dt_data[]    = $amount_row;
                } */
            }

            $grand_total  = "<b>" . $currency_symbol . amountFormat($grd_total) . "</b>";
            $footer_row   = array();
            $footer_row[] = "";
            $footer_row[] = "";
            $footer_row[] = "";
            $footer_row[] = "";
            $footer_row[] = "";
            $footer_row[] = "<b>" . $this->lang->line('total') . "</b>";
            $footer_row[] = $grand_total;
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

    public function getgroupreportparam()
    {
        $search_type = $this->input->post('search_type');
        $head        = $this->input->post('head');

        $payment_mode_id = $this->input->post('payment_mode_id');
        $collected_by = $this->input->post('collect_by');
        $account_department_id = $this->input->post('account_department_id');

        $date_from = "";
        $date_to   = "";

        if ($search_type == 'period') {

            $date_from = $this->input->post('date_from');
            $date_to   = $this->input->post('date_to');
        }

        $params = array('search_type' => $search_type, 'head' => $head, 'date_from' => $date_from, 'date_to' => $date_to, 'payment_mode_id' => $payment_mode_id, 'collected_by' => $collected_by, 'account_department_id' => $account_department_id);
        $array  = array('status' => 1, 'error' => '', 'params' => $params);
        echo json_encode($array);
    }

    public function expensegroup()
    {
        $this->session->set_userdata('top_menu', 'Reports');
        $this->session->set_userdata('sub_menu', 'Reports/finance');
        $this->session->set_userdata('subsub_menu', 'Reports/finance/expensegroup');

        $data['collect_by'] = $this->studentfeemaster_model->get_feesreceived_by();
        $data['paymentMethods'] = $this->accounts_model->getPaymentMethods();
        $this->load->model('account_department_model');
        $data['account_departments'] = $this->account_department_model->get();
        $payment_mode_id = null;
        $collected_by = null;

        $data['searchlist']  = $this->customlib->get_searchtype();
        $data['date_type']   = $this->customlib->date_type();
        $data['date_typeid'] = '';
        $data['headlist']    = $this->expensehead_model->get();
        $this->load->view('layout/header', $data);
        $this->load->view('financereports/expensegroup', $data);
        $this->load->view('layout/footer', $data);
    }

    public function dtexpensegroupreport()
    {
        $search_type = $this->input->post('search_type');
        $date_from   = $this->input->post('date_from');
        $date_to     = $this->input->post('date_to');
        $head        = $this->input->post('head');
        $payment_mode_id    = $this->input->post('payment_mode_id');
        $collect_by         = $this->input->post('collect_by');
        $account_department_id = $this->input->post('account_department_id');

        $data['date_type']   = $this->customlib->date_type();
        $data['date_typeid'] = '';

        if (isset($_POST['search_type']) && $_POST['search_type'] != '') {

            $dates               = $this->customlib->get_betweendate($_POST['search_type']);
            $data['search_type'] = $_POST['search_type'];
        } else {

            $dates               = $this->customlib->get_betweendate('this_year');
            $data['search_type'] = '';
        }

        $data['head_id'] = $head_id = "";
        if (isset($_POST['head']) && $_POST['head'] != '') {
            $data['head_id'] = $head_id = $_POST['head'];
        }

        $start_date = date('Y-m-d', strtotime($dates['from_date']));
        $end_date   = date('Y-m-d', strtotime($dates['to_date']));

        $data['label'] = date($this->customlib->getSchoolDateFormat(), strtotime($start_date)) . " " . $this->lang->line('to') . " " . date($this->customlib->getSchoolDateFormat(), strtotime($end_date));
        $result        = $this->expensehead_model->searchexpensegroup($start_date, $end_date, $head_id, $payment_mode_id, $collect_by, $account_department_id);

        $m               = json_decode($result);
        $currency_symbol = $this->customlib->getSchoolCurrencyFormat();
        $dt_data         = array();
        $grand_total     = 0;
        if (!empty($m->data)) {
            foreach ($m->data as $key => $value) {
                $expense_head[$value->exp_head_id][] = $value;
            }

            $grd_total  = 0;
            $exphead_id = 0;
            $count      = 0;
            foreach ($m->data as $key => $value) {

                $exp_head_id  = $value->exp_head_id;
                $total_amount = "<b>" . $value->total_amount . "</b>";
                //$grd_total += $value->total_amount;
                $is_refunded = $value->is_refunded;
                if ($is_refunded != 1) {
                    $grd_total += $value->amount;
                }
                $row = array();

                if ($exphead_id == $exp_head_id) {
                    $row[] = "";
                    $count++;
                } else {
                    $row[] = $value->exp_category;
                    $count = 0;
                }

                //$row[]      = $value->id;
                $row[]      = $value->name;
                $row[]      = $value->note;
                $row[]      = date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($value->date));
                $row[]      = $value->invoice_no;
                $row[]      = $value->made_by;
                $row[]      = amountFormat($value->amount);
                $row[]      = $value->mode;
                $row[]      = $is_refunded ? "<span class='badge'>Refunded</span>" : "Debit";
                $dt_data[]  = $row;
                $exphead_id = $value->exp_head_id;
                $sub_total  = 0;
                /* if ($count == (count($expense_head[$value->exp_head_id]) - 1)) {
                    foreach ($expense_head[$value->exp_head_id] as $exp_headkey => $exp_headvalue) {
                        $sub_total += $exp_headvalue->amount;
                    }
                    $amount_row   = array();
                    $amount_row[] = "";
                    $amount_row[] = "";
                    $amount_row[] = "";
                    $amount_row[] = "";
                    $amount_row[] = "<b>" . $this->lang->line('sub_total') . "</b>";
                    $amount_row[] = "<b>" . $currency_symbol . amountFormat($sub_total) . "</b>";
                    $dt_data[]    = $amount_row;
                } */
            }

            $grand_total  = "<b>" . $currency_symbol . amountFormat($grd_total) . "</b>";
            $footer_row   = array();
            $footer_row[] = "";
            $footer_row[] = "";
            $footer_row[] = "";
            $footer_row[] = "";
            $footer_row[] = "";
            $footer_row[] = "<b>" . $this->lang->line('total') . "</b>";
            $footer_row[] = $grand_total;
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

    public function onlineadmission()
    {
        if (!$this->rbac->hasPrivilege('online_admission', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Reports');
        $this->session->set_userdata('sub_menu', 'Reports/finance');
        $this->session->set_userdata('subsub_menu', 'Reports/finance/onlineadmission');
        $data['searchlist'] = $this->customlib->get_searchtype();
        $data['group_by']   = $this->customlib->get_groupby();

        if (isset($_POST['search_type']) && $_POST['search_type'] != '') {

            $dates               = $this->customlib->get_betweendate($_POST['search_type']);
            $data['search_type'] = $_POST['search_type'];
        } else {

            $dates               = $this->customlib->get_betweendate('this_year');
            $data['search_type'] = '';
        }

        $collection = array();
        $start_date = date('Y-m-d', strtotime($dates['from_date']));
        $end_date   = date('Y-m-d', strtotime($dates['to_date']));
        $this->form_validation->set_rules('search_type', $this->lang->line('search_type'), 'trim|required|xss_clean');

        if ($this->form_validation->run() == false) {

            $data['collectlist'] = array();
        } else {

            $data['collectlist'] = $this->onlinestudent_model->getOnlineAdmissionFeeCollectionReport($start_date, $end_date);
        }
        $data['sch_setting'] = $this->sch_setting_detail;
        $this->load->view('layout/header', $data);
        $this->load->view('financereports/onlineadmission', $data);
        $this->load->view('layout/footer', $data);
    }

    public function collection_report_by_collection_date()
    {
        // Set session data for menu highlighting
        $this->session->set_userdata('top_menu', 'Reports');
        $this->session->set_userdata('sub_menu', 'Reports/finance');
        $this->session->set_userdata('subsub_menu', 'Reports/finance/collection_report_by_collection_date');

        $data = array();
        $data['title'] = $this->lang->line('collection_report_by_collection_date'); // Title for the report page

        // Fetch lists for dropdowns
        $data['collect_by'] = $this->studentfeemaster_model->get_feesreceived_by();
        $data['feetypeList'] = $this->feetype_model->get();
        $data['classlist'] = $this->class_model->get();
        $data['sessionList'] = $this->session_model->getAllSession();
        $this->load->model('account_department_model');
        $data['account_departments'] = $this->account_department_model->get();

        $data['paymentMethods'] = $this->accounts_model->getPaymentMethods();

        // Load the views
        $this->load->view('layout/header', $data);
        $this->load->view('financereports/collection_report_by_collection_date', $data);
        $this->load->view('layout/footer', $data);
    }

    public function dtcollectionreportbycollectiondate()
    {
        // Check if the form has been submitted
        if ($this->input->server('REQUEST_METHOD') == 'POST') {
            $date_from = $this->input->post('date_from') ? $this->input->post('date_from') : "";
            $date_to = $this->input->post('date_to') ? $this->input->post('date_to') : "";
            $class_id = $this->input->post('class_id');
            $feetype_id = $this->input->post('feetype_id');
            $session_id = $this->input->post('session_id');
            $payment_mode_id = $this->input->post('payment_mode_id');
            $collected_by = $this->input->post('collected_by');
            $account_department_id = $this->input->post('account_department_id');
            $gender = $this->input->post('gender');
            $status_filter = $this->input->post('status_filter');

            // Fetch filtered data based on user input
            $result = $this->studentfeemaster_model->searchFeesCollectionsByCollectionDate($date_from, $date_to, $class_id, $feetype_id, $session_id, $payment_mode_id, $collected_by, $account_department_id, $gender, $status_filter);
        } else {
            // Fetch all data if no filters are applied
            $result = $this->studentfeemaster_model->searchFeesCollectionsByCollectionDate();
        }

        $m               = json_decode($result);
        $currency_symbol = $this->customlib->getSchoolCurrencyFormat();
        $dt_data         = array();

        $total_paid_amount = 0;

        if (!empty($m->data)) {
            $count = 1;

            foreach ($m->data as $key => $value) {

                $is_refunded = $value->is_refunded;
                if ($is_refunded != 1) {
                    $total_paid_amount += $value->paid_amount;
                }

                $row = array();

                $row[]      = $this->customlib->dateformat($value->collection_date);
                /* $row[]      = $value->account_department_name; */
                $row[]      = $value->reg_id;
                $row[]      = $value->student_name;
                $class_section = $value->class_name;
                if (!empty($value->section_name)) {
                    $class_section .= " (" . $value->section_name . ")";
                }
                $row[]      = $class_section;
                $row[]      = $value->roll_no;
                $row[]      = !empty($value->recommendationNumber) ? $value->recommendationNumber : "";
                $row[]      = $value->feetype_name;
                $row[]      = $currency_symbol . amountFormat($value->paid_amount);
                $row[]      = $value->payment_mode_title;
                $row[]      = $value->payment_hash;
                $row[]      = $value->collected_by;
                $row[]      = $is_refunded ? "<span class='badge'>Refunded</span>" : $value->approved_by;

                $dt_data[]  = $row;
                $count++;
            }

            $footer_row[] = "";
            $footer_row[] = "";
            $footer_row[] = "";
            $footer_row[] = "";
            $footer_row[] = "";
            $footer_row[] = "";
            $footer_row[] = "<b>Total</b>";
            $footer_row[] = $currency_symbol . amountFormat($total_paid_amount);
            $footer_row[] = "";
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
}
