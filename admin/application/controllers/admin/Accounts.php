<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Accounts extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('customlib');
        $this->load->library('media_storage');
        $this->load->model("module_model");
        $this->load->library('form_validation');
        $this->load->model("accounts_model");
        $this->load->model("expense_model");
        $this->load->model("staff_model");
        $this->sch_setting_detail = $this->setting_model->getSetting();
    }

    public function account_balances()
    {
        if (!$this->rbac->hasPrivilege('account_balances', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Accounts');
        $this->session->set_userdata('sub_menu', 'admin/accounts/account_balances');
        $data = array();

        $data['sch_setting']   = $this->sch_setting_detail;
        $data['title']         = 'Account Balances';

        //$data['statements'] = null;

        $paymentMethods = $this->accounts_model->getPaymentMethods();
        $data['statements'] = []; // Initialize statements array
        $totalBalance = 0; // Initialize total balance

        if ($this->input->post()) {

            $from_date = $this->input->post('from_date');
            $to_date = $this->input->post('to_date');

            if (!empty($paymentMethods)) {
                foreach ($paymentMethods as $paymentMethod) {
                    $balanceData = $this->accounts_model->getBalanceByTransDate($paymentMethod['id'], $from_date, $to_date);

                    // Add each payment method name and balance to statements
                    $data['statements'][] = [
                        'payment_method_id' => $paymentMethod['id'],
                        'payment_method_name' => $paymentMethod['title'],
                        'balance' => $balanceData['balance']
                    ];

                    // Accumulate the total balance
                    $totalBalance += $balanceData['balance'];
                }
            }

            /* echo "<pre>";
            print_r($data['statements']);
            die; */

            $data['from_date'] = $from_date;
            $data['to_date'] = $to_date;
        } else {

            if (!empty($paymentMethods)) {
                foreach ($paymentMethods as $paymentMethod) {
                    $balanceData = $this->accounts_model->getBalanceByTransDate($paymentMethod['id']);

                    // Add each payment method name and balance to statements
                    $data['statements'][] = [
                        'payment_method_id' => $paymentMethod['id'],
                        'payment_method_name' => $paymentMethod['title'],
                        'balance' => $balanceData['balance']
                    ];

                    // Accumulate the total balance
                    $totalBalance += $balanceData['balance'];
                }
            }

            $data['from_date'] = null;
            $data['to_date'] = null;
        }

        $data['total_balance'] = $totalBalance; // Pass total balance to view

        //$data['paymentMethods'] = $this->accounts_model->getPaymentMethods();
        $this->load->view('layout/header', $data);
        $this->load->view('admin/accounts/account_balances', $data);
        $this->load->view('layout/footer', $data);
    }

    public function payment_methods()
    {
        if (!$this->rbac->hasPrivilege('payment_methods', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Payment Methods');
        $this->session->set_userdata('sub_menu', 'admin/accounts/payment_methods');
        $data = array();

        $data['sch_setting']   = $this->sch_setting_detail;
        $data['title']         = 'Payment Methods';
        $data['paymentMethods'] = $this->accounts_model->getPaymentMethods();
        /*  print_r($data['paymentMethods']);
        die; */
        $id = "";
        if (isset($_GET['action']) && $_GET['action'] != "" && isset($_GET['id']) && $_GET['id'] != "") {
            $action = $_GET['action'];
            $id = $_GET['id'];
            //echo $id;
            //die;
            if ($action === 'edit') {
                $data['paymentMethodsDetails'] = $this->accounts_model->getPaymentMethodsDetails($id);
                /* print_r($data['paymentMethodsDetails']);
                die; */
            } elseif ($action === 'delete') {
                $this->accounts_model->removePaymentMethod($id);

                $this->session->set_flashdata('msg', '<div class="alert alert-danger">Payment method removed successfully.</div>');
                redirect('admin/accounts/payment_methods');
            }
        }

        // Handle form submission
        if ($this->input->post()) {

            $this->load->library('form_validation');
            $this->form_validation->set_rules('title', 'Title', 'required');
            $this->form_validation->set_rules('code', 'Code', 'required');

            if ($this->form_validation->run() == true) {
                $id = $this->input->post('id');
                $paymentMethodData = array(
                    'title' => $this->input->post('title'),
                    'code' => $this->input->post('code'),
                    // Add other fields as needed
                );
                /* echo $id;
                die; */
                if ($id != "") {
                    $this->accounts_model->updatePaymentMethod($id, $paymentMethodData);

                    $this->session->set_flashdata('msg', '<div class="alert alert-success">Payment method updated successfully.</div>');
                    redirect('admin/accounts/payment_methods');
                } else {
                    $paymentMethodId = $this->accounts_model->addPaymentMethod($paymentMethodData);
                }

                if ($paymentMethodId) {
                    $this->session->set_flashdata('msg', '<div class="alert alert-success">Payment method added successfully.</div>');
                    redirect('admin/accounts/payment_methods');
                } else {
                    $data['error_message'] = 'Error adding payment method.';
                }
            }
        }

        $this->load->view('layout/header', $data);
        $this->load->view('admin/accounts/payment_methods', $data);
        $this->load->view('layout/footer', $data);
    }


    public function statements()
    {

        if (!$this->rbac->hasPrivilege('statements', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Accounts');
        $this->session->set_userdata('sub_menu', 'admin/accounts/statements');

        $data['sch_setting']   = $this->sch_setting_detail;
        $data['title']         = 'Statements';
        // Fetch payment methods for the dropdown
        $data['payment_methods'] = $this->accounts_model->getPaymentMethods();
        $opening_balance = 0;

        // Check if the form is submitted
        if ($this->input->post()) {
            // Get filter options from the form
            $payment_method_id = $this->input->post('payment_method');
            $from_date = $this->input->post('from_date');
            $to_date = $this->input->post('to_date');
        } else {
            $to_date = date('Y-m-d');
            $from_date = date('Y-m-d', strtotime('-7 days'));
            $payment_method_id = null;
        }

        if (!empty($from_date)) {
            $previous_date = date('Y-m-d', strtotime($from_date . ' -1 day'));
            $opening_balance_data = $this->accounts_model->getBalanceByTransDate($payment_method_id, "", $previous_date);
            $opening_balance = $opening_balance_data['balance'];
        }

        // Fetch statements based on filter options
        $data['statements'] = $this->accounts_model->getFilteredStatements($payment_method_id, $from_date, $to_date);

        // Pass filter values to the view
        $data['selected_payment_method'] = $payment_method_id;
        $data['from_date'] = $from_date;
        $data['to_date'] = $to_date;
        $data['opening_balance'] = $opening_balance;


        $this->load->view('layout/header', $data);
        $this->load->view('admin/accounts/statements', $data);
        $this->load->view('layout/footer', $data);
    }

    public function statements_by_pm($payment_method_id = "")
    {

        if (!$this->rbac->hasPrivilege('statements', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Accounts');
        $this->session->set_userdata('sub_menu', 'admin/accounts/statements_by_pm');

        $data['sch_setting']   = $this->sch_setting_detail;
        $data['title']         = 'Statements';
        $data['payment_methods_details'] = $this->accounts_model->getPaymentMethodsDetails($payment_method_id);
        $data['payment_method_id'] = $payment_method_id;

        $opening_balance = 0;

        if ($this->input->post()) {
            $from_date = $this->input->post('from_date');
            $to_date = $this->input->post('to_date');
        } else {
            $to_date = date('Y-m-d');
            $from_date = date('Y-m-d', strtotime('-7 days'));
        }

        if (!empty($from_date)) {
            $previous_date = date('Y-m-d', strtotime($from_date . ' -1 day'));
            $opening_balance_data = $this->accounts_model->getBalanceByTransDate($payment_method_id, "", $previous_date);
            $opening_balance = $opening_balance_data['balance'];
        }

        $data['statements'] = $this->accounts_model->getFilteredStatementsTransDate($payment_method_id, $from_date, $to_date);
        $data['from_date'] = $from_date;
        $data['to_date'] = $to_date;
        $data['opening_balance'] = $opening_balance;

        $this->load->view('layout/header', $data);
        $this->load->view('admin/accounts/statements_payment_method', $data);
        $this->load->view('layout/footer', $data);
    }

    public function transfer_funds()
    {
        if (!$this->rbac->hasPrivilege('transfer_funds', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Accounts');
        $this->session->set_userdata('sub_menu', 'admin/accounts/account_balances');

        $data = array();

        $data['sch_setting']     = $this->sch_setting_detail;
        $data['title']           = 'Transfer Funds';
        $data['paymentMethods']  = $this->accounts_model->getPaymentMethods();

        // Get filter values from POST request
        $from_method = $this->input->post('from_method');
        $to_method   = $this->input->post('to_method');
        $start_date  = $this->input->post('start_date');
        $end_date    = $this->input->post('end_date');

        // Pass filter values to the view
        $data['from_method'] = $from_method;
        $data['to_method']   = $to_method;
        $data['start_date']  = $start_date;
        $data['end_date']    = $end_date;

        // Get transfer history with filters
        $data['transfer_history'] = $this->accounts_model->getAllFundTransfers($from_method, $to_method, $start_date, $end_date);

        if ($this->input->post('form_type') === 'transfer_form') {
            $this->form_validation->set_rules('amount', 'Amount', 'required|numeric');
            $this->form_validation->set_rules('source_method', 'Source Method', 'required');
            $this->form_validation->set_rules('target_method', 'Target Method', 'required');

            if ($this->form_validation->run() == true) {
                $amount = $this->input->post('amount');
                $source_method_id = $this->input->post('source_method');
                $target_method_id = $this->input->post('target_method');
                $note = $this->input->post('note');
                $trans_date = $this->input->post('trans_date');
                $admin_data = $this->session->userdata('admin');

                // Insert into fund_transfers table
                $insert_data = array(
                    'from_payment_method_id' => $source_method_id,
                    'to_payment_method_id'   => $target_method_id,
                    'amount'                 => $amount,
                    'transfer_date'          => date('Y-m-d', strtotime($trans_date)),
                    'description'            => $note,
                    'created_by'             => $admin_data['id'],
                    'created_at'             => date('Y-m-d H:i:s'),
                );
                $this->db->insert('fund_transfers', $insert_data);
                $ft_id = $this->db->insert_id(); // ✅ Get last inserted ID
                // Perform the transfer
                $transfer_result = $this->accounts_model->transferFunds($amount, $source_method_id, $target_method_id, $note, $ft_id, $trans_date);


                if ($transfer_result) {
                    $this->session->set_flashdata('msg', 'Funds transferred successfully.');
                    redirect('admin/accounts/transfer_funds');
                } else {
                    $this->session->set_flashdata('msg', 'Failed to transfer funds.');
                }
            }
        }

        $this->load->view('layout/header', $data);
        $this->load->view('admin/accounts/transfer_funds', $data);
        $this->load->view('layout/footer', $data);
    }

    public function edit_fund_transfer($id = null)
    {
        if (!$this->rbac->hasPrivilege('transfer_funds', 'can_edit')) {
            access_denied();
        }

        if (!$id) {
            show_404();
        }

        $data['edit_transfer'] = $this->accounts_model->getFundTransferById($id);

        if (empty($data['edit_transfer'])) {
            show_404();
        }

        $this->form_validation->set_rules('amount', 'Amount', 'required|numeric');
        $this->form_validation->set_rules('source_method', 'Source Method', 'required');
        $this->form_validation->set_rules('target_method', 'Target Method', 'required');

        if ($this->form_validation->run() == true) {
            $amount = $this->input->post('amount');
            $source_method_id = $this->input->post('source_method');
            $target_method_id = $this->input->post('target_method');
            $note = $this->input->post('note');
            $trans_date = $this->input->post('trans_date');
            $admin_data = $this->session->userdata('admin');

            // Update fund_transfers table
            $update_data = array(
                'from_payment_method_id' => $source_method_id,
                'to_payment_method_id'   => $target_method_id,
                'amount'                 => $amount,
                'transfer_date'          => date('Y-m-d', strtotime($trans_date)),
                'description'            => $note,
                'created_by'             => $admin_data['id'],
                'updated_at'             => date('Y-m-d H:i:s'),
            );
            $this->db->where('id', $id)->update('fund_transfers', $update_data);

            // Remove old transactions (both debit and credit) for this transfer
            $this->db->where('transaction_for_table', 'fund_transfers')
                ->where('table_id', $id)
                ->delete('transactions');

            // Re-insert updated transactions
            $transfer_result = $this->accounts_model->transferFunds($amount, $source_method_id, $target_method_id, $note, $id, $trans_date);

            if ($transfer_result) {
                $this->session->set_flashdata('msg', 'Funds transferred successfully.');
                redirect('admin/accounts/transfer_funds');
            } else {
                $this->session->set_flashdata('msg', 'Failed to transfer funds.');
            }
        }

        // Load common data
        $data['title'] = 'Edit Fund Transfer';
        $data['paymentMethods'] = $this->accounts_model->getPaymentMethods();
        $data['transfer_history'] = $this->accounts_model->getAllFundTransfers();

        $this->load->view('layout/header', $data);
        $this->load->view('admin/accounts/transfer_funds', $data);
        $this->load->view('layout/footer', $data);
    }

    public function delete_fund_transfer($id = null)
    {
        if (!$this->rbac->hasPrivilege('transfer_funds', 'can_delete')) {
            access_denied();
        }

        if ($this->input->method(TRUE) !== 'POST' || empty($id)) {
            show_404();
        }

        if (empty($this->accounts_model->getFundTransferById($id))) {
            show_404();
        }

        if ($this->accounts_model->deleteFundTransfer($id)) {
            $this->session->set_flashdata('msg', 'Fund transfer deleted successfully.');
        } else {
            $this->session->set_flashdata('error', 'Failed to delete fund transfer.');
        }

        redirect('admin/accounts/transfer_funds');
    }



    public function balance_sheet_old()
    {
        if (!$this->rbac->hasPrivilege('balance_sheet', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Accounts');
        $this->session->set_userdata('sub_menu', 'admin/accounts/balance_sheet');

        // Get date range input
        $date_from = $this->input->post('date_from');
        $date_to = $this->input->post('date_to');

        // Default date range
        if (!$date_from) {
            $date_from = date('Y') . '-01-01';
        }
        if (!$date_to) {
            $date_to = date('Y-m-d');
        }

        // Fetch income and expense data
        $head_id = 3; // FOR OPENING BALANCE(CASH or BANK)
        $data['income_details_opening_cash_balance'] = $this->accounts_model->get_grouped_income_by_head($head_id, 'cash', $date_from, $date_to);
        $data['income_details_opening_bank_balance'] = $this->accounts_model->get_grouped_income_by_head($head_id, 'bank', $date_from, $date_to);
        $fixed_head_id = 9; // FOR OPENING BALANCE(FIXED DEPOSIT)
        $data['income_details_opening_fix_deposit_balance'] = $this->accounts_model->get_grouped_income_by_head($fixed_head_id, 'fixed', $date_from, $date_to);

        $data['income_details'] = $this->accounts_model->get_grouped_income($date_from, $date_to);
        $data['expense_details'] = $this->accounts_model->get_grouped_expenses($date_from, $date_to);

        /* echo "<pre>";
        print_r($data['income_details']);
        die; */

        /* $data['student_fees_collections_refund'] = $this->accounts_model->get_refunded_amount('student_fees_collections', $date_from, $date_to);
        $data['income_refunded_amount'] = $this->accounts_model->get_refunded_amount('income', $date_from, $date_to);
        $data['expense_refunded_amount'] = $this->accounts_model->get_refunded_amount('expenses', $date_from, $date_to); */

        $data['student_fees_collections_refund'] = 0;
        $data['income_refunded_amount'] = 0;
        $data['expense_refunded_amount'] = 0;

        /* echo $data['expense_refunded_amount'];
        die; */

        /* echo "<pre>";
        print_r($data['income_details_opening_cash_balance']);
        echo "===";
        print_r($data['income_details_opening_bank_balance']);
        die; */

        /** getting opening balance */
        // Get the previous date of $date_to
        $previous_date = date('Y-m-d', strtotime($date_from . ' -1 day'));

        $data['opening_balance'] = []; // Initialize statements array
        $totalBalance = 0; // Initialize total balances

        $paymentMethods = $this->accounts_model->getPaymentMethods();

        if (!empty($paymentMethods)) {
            foreach ($paymentMethods as $paymentMethod) {
                $balanceData = $this->accounts_model->getBalanceByTransDate($paymentMethod['id'], "", $previous_date);

                // Add each payment method name and balance to statements
                $data['opening_balance'][] = [
                    'payment_method_id' => $paymentMethod['id'],
                    'payment_method_name' => $paymentMethod['title'],
                    'method_type_id' => $paymentMethod['method_type_id'],
                    'method_type' => $paymentMethod['method_type'],
                    'balance' => $balanceData['balance']
                ];

                // Accumulate the total balance
                $totalBalance += $balanceData['balance'];
            }
        }

        $data['total_opening_balance'] = $totalBalance; // Pass total balance to view

        /** end of getting opening balance */

        /** getting closing balance */
        $data['closing_balance'] = []; // Initialize statements array
        $total_closing_balance = 0; // Initialize total balances

        if (!empty($paymentMethods)) {
            foreach ($paymentMethods as $paymentMethod) {
                $balanceData = $this->accounts_model->getBalanceByTransDate($paymentMethod['id'], "", $date_to);

                // Add each payment method name and balance to statements
                $data['closing_balance'][] = [
                    'payment_method_id' => $paymentMethod['id'],
                    'payment_method_name' => $paymentMethod['title'],
                    'method_type_id' => $paymentMethod['method_type_id'],
                    'method_type' => $paymentMethod['method_type'],
                    'balance' => $balanceData['balance']
                ];

                // Accumulate the total balance
                $total_closing_balance += $balanceData['balance'];
            }
        }

        $data['total_closing_balance'] = $total_closing_balance; // Pass total balance to view
        /** end of getting closing balance */

        /* echo "<pre>";
        //print_r($paymentMethods);
        print_r($data['opening_balance']);
        print_r($data['total_opening_balance']);
        print_r($data['closing_balance']);
        print_r($data['total_closing_balance']);
        die; */

        // Fetch student fees collection total
        $data['student_fees_total_admission_fees'] = $this->accounts_model->get_student_fees_total(1, $date_from, $date_to);
        $data['student_fees_total_without_admission_fees'] = $this->accounts_model->get_student_fees_total(0, $date_from, $date_to);
        //$data['student_fees_total_without_admission_fees'] = 0;

        // Fetch staff payroll total
        $data['payroll_total'] = $this->accounts_model->get_payroll_total($date_from, $date_to);
        $data['payroll_refunded'] = $this->accounts_model->get_refunded_payroll($date_from, $date_to);
        $data['date_from'] = $date_from;
        $data['date_to'] = $date_to;

        // Load view
        $this->load->view('layout/header', $data);
        $this->load->view('admin/accounts/balance_sheets', $data);
        $this->load->view('layout/footer', $data);
    }

    public function balance_sheet_new()
    {
        if (!$this->rbac->hasPrivilege('balance_sheet', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Accounts');
        $this->session->set_userdata('sub_menu', 'admin/accounts/balance_sheet');

        // Get date range input
        $date_from = $this->input->post('date_from') ?: $this->input->get('date_from');
        $date_to = $this->input->post('date_to') ?: $this->input->get('date_to');

        // Default date range
        if (!$date_from) {
            $date_from = date('Y') . '-01-01';
        }
        if (!$date_to) {
            $date_to = date('Y-m-d');
        }

        // Fetch income and expense data
        $head_id = 3; // FOR OPENING BALANCE(CASH or BANK)
        $data['income_details_opening_cash_balance'] = $this->accounts_model->get_grouped_income_by_head($head_id, 'cash', $date_from, $date_to);
        $data['income_details_opening_bank_balance'] = $this->accounts_model->get_grouped_income_by_head($head_id, 'bank', $date_from, $date_to);
        $fixed_head_id = 9; // FOR OPENING BALANCE(FIXED DEPOSIT)
        $data['income_details_opening_fix_deposit_balance'] = $this->accounts_model->get_grouped_income_by_head($fixed_head_id, 'fixed', $date_from, $date_to);

        $data['income_details'] = $this->accounts_model->get_grouped_income($date_from, $date_to);
        $data['expense_details'] = $this->accounts_model->get_grouped_expenses($date_from, $date_to);

        $data['student_fees_collections_refund'] = 0;
        $data['income_refunded_amount'] = 0;
        $data['expense_refunded_amount'] = 0;

        $previous_date = date('Y-m-d', strtotime($date_from . ' -1 day'));

        $data['opening_balance'] = []; // Initialize statements array
        $totalBalance = 0; // Initialize total balances

        $paymentMethods = $this->accounts_model->getPaymentMethods();

        if (!empty($paymentMethods)) {
            foreach ($paymentMethods as $paymentMethod) {
                $balanceData = $this->accounts_model->getBalanceByTransDate($paymentMethod['id'], "", $previous_date);

                // Add each payment method name and balance to statements
                $data['opening_balance'][] = [
                    'payment_method_id' => $paymentMethod['id'],
                    'payment_method_name' => $paymentMethod['title'],
                    'method_type_id' => $paymentMethod['method_type_id'],
                    'method_type' => $paymentMethod['method_type'],
                    'balance' => $balanceData['balance']
                ];

                // Accumulate the total balance
                $totalBalance += $balanceData['balance'];
            }
        }

        $data['total_opening_balance'] = $totalBalance; // Pass total balance to view

        /** end of getting opening balance */

        /** getting closing balance */
        $data['closing_balance'] = []; // Initialize statements array
        $total_closing_balance = 0; // Initialize total balances

        if (!empty($paymentMethods)) {
            foreach ($paymentMethods as $paymentMethod) {
                $balanceData = $this->accounts_model->getBalanceByTransDate($paymentMethod['id'], "", $date_to);

                // Add each payment method name and balance to statements
                $data['closing_balance'][] = [
                    'payment_method_id' => $paymentMethod['id'],
                    'payment_method_name' => $paymentMethod['title'],
                    'method_type_id' => $paymentMethod['method_type_id'],
                    'method_type' => $paymentMethod['method_type'],
                    'balance' => $balanceData['balance']
                ];

                // Accumulate the total balance
                $total_closing_balance += $balanceData['balance'];
            }
        }

        $data['total_closing_balance'] = $total_closing_balance; // Pass total balance to view
        /** end of getting closing balance */

        // Fetch student fees collection total
        // Fetch fees by account department dynamically
        $data['fees_by_department'] = $this->accounts_model->get_fees_by_all_departments($date_from, $date_to);

        // Fetch staff payroll total
        $data['payroll_total'] = $this->accounts_model->get_payroll_total($date_from, $date_to);
        $data['payroll_refunded'] = $this->accounts_model->get_refunded_payroll($date_from, $date_to);
        $data['date_from'] = $date_from;
        $data['date_to'] = $date_to;
        // Gross loans given to staff (for Payments column)
        $data['total_staff_loan'] = $this->accounts_model->get_staff_loan_given($date_from, $date_to);
        // Repayments received from staff (for Received column)
        $data['staff_loan_repayments'] = $this->accounts_model->get_staff_loan_repayments($date_from, $date_to);
        $data['sch_setting'] = $this->sch_setting_detail;

        if ($this->input->get('export') === 'excel') {
            $this->load->view('admin/accounts/balance_sheet_new_excel', $data);
            return;
        }

        // Load view
        $this->load->view('layout/header', $data);
        $this->load->view('admin/accounts/balance_sheets_new', $data);
        $this->load->view('layout/footer', $data);
    }

    public function balance_sheet()
    {
        if (!$this->rbac->hasPrivilege('balance_sheet', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Accounts');
        $this->session->set_userdata('sub_menu', 'admin/accounts/balance_sheet');

        // --- Date Range ---
        $date_from = $this->input->post('date_from') ?: date('Y') . '-01-01';
        $date_to   = $this->input->post('date_to') ?: date('Y-m-d');
        $previous_date = date('Y-m-d', strtotime($date_from . ' -1 day'));

        // --- Opening Balance ---
        $paymentMethods = $this->accounts_model->getPaymentMethods();
        $data['opening_balance'] = [];
        $total_opening_balance = 0;

        foreach ($paymentMethods as $method) {
            $balanceData = $this->accounts_model->getBalanceByTransDate($method['id'], "", $previous_date);
            $data['opening_balance'][] = [
                'payment_method_id'   => $method['id'],
                'payment_method_name' => $method['title'],
                'method_type_id'      => $method['method_type_id'],
                'method_type'         => $method['method_type'],
                'balance'             => $balanceData['balance']
            ];
            $total_opening_balance += $balanceData['balance'];
        }
        $data['total_opening_balance'] = $total_opening_balance;

        // --- Closing Balance ---
        $data['closing_balance'] = [];
        $total_closing_balance = 0;
        foreach ($paymentMethods as $method) {
            $balanceData = $this->accounts_model->getBalanceByTransDate($method['id'], "", $date_to);
            $data['closing_balance'][] = [
                'payment_method_id'   => $method['id'],
                'payment_method_name' => $method['title'],
                'method_type_id'      => $method['method_type_id'],
                'method_type'         => $method['method_type'],
                'balance'             => $balanceData['balance']
            ];
            $total_closing_balance += $balanceData['balance'];
        }
        $data['total_closing_balance'] = $total_closing_balance;

        // --- Income Sections ---
        $data['primary_fees_collection']   = $this->accounts_model->fees_collection_account_departments($date_from, $date_to, 1);
        $data['secondary_fees_collection'] = $this->accounts_model->fees_collection_account_departments($date_from, $date_to, 2);
        $data['hostel_fees_collection']    = $this->accounts_model->fees_collection_account_departments($date_from, $date_to, 3);
        $data['general_income']            = $this->accounts_model->general_income($date_from, $date_to);

        // --- Expense Sections ---
        $data['department_expenses']   = $this->accounts_model->department_expenses($date_from, $date_to);
        $data['department_salary_paid'] = $this->accounts_model->department_salary_paid($date_from, $date_to);

        // --- Payroll and Loans ---
        $data['payroll_total']      = $this->accounts_model->get_payroll_total($date_from, $date_to);
        $data['payroll_refunded']   = $this->accounts_model->get_refunded_payroll($date_from, $date_to);
        $data['total_staff_loan']   = $this->staff_model->get_total_loans($date_from, $date_to);
        $data['staff_loan_by_department'] = $this->staff_model->get_total_loans_by_all_department($date_from, $date_to);

        // --- Meta Info ---
        $data['date_from'] = $date_from;
        $data['date_to']   = $date_to;

        // --- Load View ---
        $this->load->view('layout/header', $data);
        $this->load->view('admin/accounts/balance_sheets2', $data);
        $this->load->view('layout/footer');
    }


    public function balance_sheet_print()
    {
        /* print_r($_POST);
        die; */
        // Get date range input
        $date_from = $this->input->post('date_from');
        $date_to = $this->input->post('date_to');

        // Default date range
        if (!$date_from) {
            $date_from = date('Y') . '-01-01';
        }
        if (!$date_to) {
            $date_to = date('Y-m-d');
        }

        // Fetch income and expense data
        $head_id = 3; // FOR OPENING BALANCE(CASH or BANK)
        $data['income_details_opening_cash_balance'] = $this->accounts_model->get_grouped_income_by_head($head_id, 'cash', $date_from, $date_to);
        $data['income_details_opening_bank_balance'] = $this->accounts_model->get_grouped_income_by_head($head_id, 'bank', $date_from, $date_to);
        $fixed_head_id = 9; // FOR OPENING BALANCE(FIXED DEPOSIT)
        $data['income_details_opening_fix_deposit_balance'] = $this->accounts_model->get_grouped_income_by_head($fixed_head_id, 'fixed', $date_from, $date_to);

        $data['income_details'] = $this->accounts_model->get_grouped_income($date_from, $date_to);
        $data['expense_details'] = $this->accounts_model->get_grouped_expenses($date_from, $date_to);

        $data['student_fees_collections_refund'] = 0;
        $data['income_refunded_amount'] = 0;
        $data['expense_refunded_amount'] = 0;

        $previous_date = date('Y-m-d', strtotime($date_from . ' -1 day'));

        $data['opening_balance'] = []; // Initialize statements array
        $totalBalance = 0; // Initialize total balances

        $paymentMethods = $this->accounts_model->getPaymentMethods();

        if (!empty($paymentMethods)) {
            foreach ($paymentMethods as $paymentMethod) {
                $balanceData = $this->accounts_model->getBalanceByTransDate($paymentMethod['id'], "", $previous_date);

                // Add each payment method name and balance to statements
                $data['opening_balance'][] = [
                    'payment_method_id' => $paymentMethod['id'],
                    'payment_method_name' => $paymentMethod['title'],
                    'method_type_id' => $paymentMethod['method_type_id'],
                    'method_type' => $paymentMethod['method_type'],
                    'balance' => $balanceData['balance']
                ];

                // Accumulate the total balance
                $totalBalance += $balanceData['balance'];
            }
        }

        $data['total_opening_balance'] = $totalBalance; // Pass total balance to view

        /** end of getting opening balance */

        /** getting closing balance */
        $data['closing_balance'] = []; // Initialize statements array
        $total_closing_balance = 0; // Initialize total balances

        if (!empty($paymentMethods)) {
            foreach ($paymentMethods as $paymentMethod) {
                $balanceData = $this->accounts_model->getBalanceByTransDate($paymentMethod['id'], "", $date_to);

                // Add each payment method name and balance to statements
                $data['closing_balance'][] = [
                    'payment_method_id' => $paymentMethod['id'],
                    'payment_method_name' => $paymentMethod['title'],
                    'method_type_id' => $paymentMethod['method_type_id'],
                    'method_type' => $paymentMethod['method_type'],
                    'balance' => $balanceData['balance']
                ];

                // Accumulate the total balance
                $total_closing_balance += $balanceData['balance'];
            }
        }

        $data['total_closing_balance'] = $total_closing_balance;

        // Fetch fees by account department dynamically
        $data['fees_by_department'] = $this->accounts_model->get_fees_by_all_departments($date_from, $date_to);

        // Fetch staff payroll total
        $data['payroll_total'] = $this->accounts_model->get_payroll_total($date_from, $date_to);
        $data['payroll_refunded'] = $this->accounts_model->get_refunded_payroll($date_from, $date_to);

        // Gross loans given (Payments side) and repayments received (Received side)
        $data['total_staff_loan'] = $this->accounts_model->get_staff_loan_given($date_from, $date_to);
        $data['staff_loan_repayments'] = $this->accounts_model->get_staff_loan_repayments($date_from, $date_to);

        $data['date_from'] = $date_from;
        $data['date_to'] = $date_to;

        /* echo "<pre>";
        print_r($data);
        die; */
        $page = $this->load->view('print/printBalanceSheet', $data, true);
        /* echo $page;
        die; */
        echo json_encode(array('status' => 1, 'page' => $page));
    }

    public function cashbook()
    {
        if (!$this->rbac->hasPrivilege('cashbook', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Accounts');
        $this->session->set_userdata('sub_menu', 'admin/accounts/cashbook');

        // Get date range input
        $date_from = $this->input->post('date_from');
        $date_to = $this->input->post('date_to');

        // Default date range
        if (!$date_from) {
            $date_from = date('Y-m') . '-01';
        }
        if (!$date_to) {
            $date_to = date('Y-m-d');
        }

        $previous_date = date('Y-m-d', strtotime($date_from . ' -1 day'));

        $data['opening_balance'] = [
            'cash' => 0,
            'bank' => 0
        ]; // Initialize opening balances for Cash and Bank
        $data['bank_opening_balances'] = [];
        $data['bank_closing_balances'] = [];

        $paymentMethods = $this->accounts_model->getPaymentMethods();

        if (!empty($paymentMethods)) {
            foreach ($paymentMethods as $paymentMethod) {
                $balanceData = $this->accounts_model->getBalanceByTransDate($paymentMethod['id'], "", $previous_date);

                // Separate balances for Cash and Bank
                if ($paymentMethod['method_type_id'] == 1) {
                    // Cash Balance
                    $data['opening_balance']['cash'] += $balanceData['balance'];
                }

                if ($paymentMethod['method_type_id'] == 2) {
                    // Bank Balance
                    $data['opening_balance']['bank'] += $balanceData['balance'];
                    $data['bank_opening_balances'][] = [
                        'name' => $paymentMethod['title'],
                        'balance' => $balanceData['balance'],
                    ];

                    $closingBalanceData = $this->accounts_model->getBalanceByTransDate($paymentMethod['id'], "", $date_to);
                    $data['bank_closing_balances'][] = [
                        'name' => $paymentMethod['title'],
                        'balance' => $closingBalanceData['balance'],
                    ];
                }
            }
        }

        $total_receipts = $this->accounts_model->get_all_receipts($date_from, $date_to);
        $total_payments = $this->accounts_model->get_all_payments($date_from, $date_to);

        foreach ($total_receipts as &$receipt) {
            if (isset($receipt['department_name']) && $receipt['department_name']) {
                if ($receipt['department_name'] == 'General') {
                    $receipt['head'] = $receipt['head'];
                } else {
                    $receipt['head'] = $receipt['department_name'][0] . ' ' . $receipt['head'];
                }
            }
        }

        foreach ($total_payments as &$payment) {
            if (isset($payment['department_name']) && $payment['department_name']) {
                if ($payment['department_name'] == 'General') {
                    $payment['head'] = $payment['head'];
                } else {
                    $payment['head'] = $payment['department_name'][0] . ' ' . $payment['head'];
                }
            }
        }

        $data['total_receipts'] = $total_receipts;
        $data['total_payments'] = $total_payments;

        $data['date_from'] = $date_from;
        $data['date_to'] = $date_to;
        $data['sch_setting'] = $this->sch_setting_detail;

        // Load view
        $this->load->view('layout/header', $data);
        $this->load->view('admin/accounts/cashbook', $data);
        $this->load->view('layout/footer', $data);
    }

    public function cashbook_pdf()
    {
        if (!$this->rbac->hasPrivilege('cashbook', 'can_view')) {
            access_denied();
        }

        // Get date range input
        $date_from = $this->input->get('date_from');
        $date_to = $this->input->get('date_to');

        // Default date range
        if (!$date_from) {
            $date_from = date('Y-m') . '-01';
        }
        if (!$date_to) {
            $date_to = date('Y-m-d');
        }

        $previous_date = date('Y-m-d', strtotime($date_from . ' -1 day'));

        $data['opening_balance'] = [
            'cash' => 0,
            'bank' => 0
        ]; // Initialize opening balances for Cash and Bank
        $data['bank_opening_balances'] = [];
        $data['bank_closing_balances'] = [];

        $paymentMethods = $this->accounts_model->getPaymentMethods();

        if (!empty($paymentMethods)) {
            foreach ($paymentMethods as $paymentMethod) {
                $balanceData = $this->accounts_model->getBalanceByTransDate($paymentMethod['id'], "", $previous_date);

                // Separate balances for Cash and Bank
                if ($paymentMethod['method_type_id'] == 1) {
                    // Cash Balance
                    $data['opening_balance']['cash'] += $balanceData['balance'];
                }

                if ($paymentMethod['method_type_id'] == 2) {
                    // Bank Balance
                    $data['opening_balance']['bank'] += $balanceData['balance'];
                    $data['bank_opening_balances'][] = [
                        'name' => $paymentMethod['title'],
                        'balance' => $balanceData['balance'],
                    ];

                    $closingBalanceData = $this->accounts_model->getBalanceByTransDate($paymentMethod['id'], "", $date_to);
                    $data['bank_closing_balances'][] = [
                        'name' => $paymentMethod['title'],
                        'balance' => $closingBalanceData['balance'],
                    ];
                }
            }
        }

        $total_receipts = $this->accounts_model->get_all_receipts($date_from, $date_to);
        $total_payments = $this->accounts_model->get_all_payments($date_from, $date_to);

        foreach ($total_receipts as &$receipt) {
            if (isset($receipt['department_name']) && $receipt['department_name']) {
                if ($receipt['department_name'] == 'General') {
                    $receipt['head'] = $receipt['head'];
                } else {
                    $receipt['head'] = $receipt['department_name'][0] . ' ' . $receipt['head'];
                }
            }
        }

        foreach ($total_payments as &$payment) {
            if (isset($payment['department_name']) && $payment['department_name']) {
                if ($payment['department_name'] == 'General') {
                    $payment['head'] = $payment['head'];
                } else {
                    $payment['head'] = $payment['department_name'][0] . ' ' . $payment['head'];
                }
            }
        }

        $data['total_receipts'] = $total_receipts;
        $data['total_payments'] = $total_payments;

        $data['date_from'] = $date_from;
        $data['date_to'] = $date_to;
        $data['sch_setting'] = $this->sch_setting_detail;

        $this->load->view('admin/accounts/cashbook_pdf', $data);
    }

    public function ledger()
    {
        if (!$this->rbac->hasPrivilege('ledger', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Ledger');
        $this->session->set_userdata('sub_menu', 'admin/accounts/ledger');

        // Get input values
        $date_from = $this->input->post('date_from') ?: $this->input->get('date_from') ?: date('Y') . '-01-01';
        $date_to = $this->input->post('date_to') ?: $this->input->get('date_to') ?: date('Y-m-d');
        $head_id = $this->input->post('head_id') ?: $this->input->get('head_id');
        $department_id = $this->input->post('department_id') ?: $this->input->get('department_id'); // New

        // Store values for the view
        $data['date_from'] = $date_from;
        $data['date_to'] = $date_to;
        $data['head_id'] = $head_id;
        $data['department_id'] = $department_id; // New

        $this->load->model('account_department_model'); // New
        $data['departments'] = $this->account_department_model->get(); // New

        $headlists = $this->expensehead_model->get();
        $new_headlists = [];

        foreach ($headlists as $key => $headlist) {
            $new_headlists[$headlist['id']] = $headlist['exp_category'];
        }

        // Add custom ledger head for Staff Loan
        $custom_staff_loan_key = 'staff_loan';
        $new_headlists[$custom_staff_loan_key] = 'Staff Loan';


        $data['headlist'] = $new_headlists;

        // Fetch ledger data
        $data['head_data'] = $this->accounts_model->getHeadEntries($date_from, $date_to, $head_id, $department_id); // Modified
        $data['sch_setting'] = $this->sch_setting_detail;

        if ($this->input->get('export') === 'excel') {
            $this->load->view('admin/accounts/ledger_excel', $data);
            return;
        }

        // Load views
        $this->load->view('layout/header', $data);
        $this->load->view('admin/accounts/ledger', $data);
        $this->load->view('layout/footer', $data);
    }

    public function ledger_pdf()
    {
        if (!$this->rbac->hasPrivilege('ledger', 'can_view')) {
            access_denied();
        }

        // Get input values
        $date_from = $this->input->get('date_from') ?? date('Y') . '-01-01';
        $date_to = $this->input->get('date_to') ?? date('Y-m-d');
        $head_id = $this->input->get('head_id');
        $department_id = $this->input->get('department_id');

        // Store values for the view
        $data['date_from'] = $date_from;
        $data['date_to'] = $date_to;
        $data['head_id'] = $head_id;
        $data['department_id'] = $department_id;

        $this->load->model('account_department_model');
        $data['departments'] = $this->account_department_model->get();
        $data['department_name'] = "";
        if ($department_id) {
            $department = $this->account_department_model->get($department_id);
            if ($department) {
                $data['department_name'] = $department['name'];
            }
        }

        $headlists = $this->expensehead_model->get();
        $new_headlists = [];

        foreach ($headlists as $key => $headlist) {
            $new_headlists[$headlist['id']] = $headlist['exp_category'];
        }

        // Add custom ledger head for Staff Loan
        $custom_staff_loan_key = 'staff_loan';
        $new_headlists[$custom_staff_loan_key] = 'Staff Loan';


        $data['headlist'] = $new_headlists;

        // Fetch ledger data
        $data['head_data'] = $this->accounts_model->getHeadEntries($date_from, $date_to, $head_id, $department_id);
        $data['sch_setting'] = $this->sch_setting_detail;

        // Load views
        $this->load->view('admin/accounts/ledger_pdf', $data);
    }


    public function income_ledger()
    {
        if (!$this->rbac->hasPrivilege('income_ledger', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Income Ledger');
        $this->session->set_userdata('sub_menu', 'admin/accounts/income_ledger');

        $date_from = $this->input->post('date_from') ?: $this->input->get('date_from') ?: date('Y') . '-01-01';
        $date_to = $this->input->post('date_to') ?: $this->input->get('date_to') ?: date('Y-m-d');
        $department_id = $this->input->post('department_id') ?: $this->input->get('department_id');
        $income_head = $this->input->post('income_head') ?: $this->input->get('income_head');

        $data['date_from'] = $date_from;
        $data['date_to'] = $date_to;
        $data['department_id'] = $department_id;
        $data['income_head'] = $income_head;

        $this->load->model('account_department_model');
        $data['departments'] = $this->account_department_model->get();

        // Load model and fetch head-wise data
        $this->load->model('accounts_model');
        $head_data = $this->accounts_model->get_income_ledger($date_from, $date_to, $department_id, $income_head);

        // Sort data by transaction_date
        usort($head_data, function($a, $b) {
            return strtotime($a['transaction_date']) - strtotime($b['transaction_date']);
        });

        $data['head_data'] = $head_data;
        $data['sch_setting'] = $this->sch_setting_detail;

        if ($this->input->get('export') === 'excel') {
            $this->load->view('admin/accounts/income_ledger_excel', $data);
            return;
        }

        $this->load->view('layout/header', $data);
        $this->load->view('admin/accounts/income_ledger', $data);
        $this->load->view('layout/footer', $data);
    }

    public function income_ledger_pdf()
    {
        if (!$this->rbac->hasPrivilege('income_ledger', 'can_view')) {
            access_denied();
        }

        $date_from = $this->input->get('date_from') ?? date('Y') . '-01-01';
        $date_to = $this->input->get('date_to') ?? date('Y-m-d');
        $department_id = $this->input->get('department_id');
        $income_head = $this->input->get('income_head');

        $data['date_from'] = $date_from;
        $data['date_to'] = $date_to;
        $data['department_id'] = $department_id;
        $data['income_head'] = $income_head;

        $this->load->model('account_department_model');
        $data['departments'] = $this->account_department_model->get();

        // Load model and fetch head-wise data
        $this->load->model('accounts_model');
        $head_data = $this->accounts_model->get_income_ledger($date_from, $date_to, $department_id, $income_head);

        // Sort data by transaction_date
        usort($head_data, function($a, $b) {
            return strtotime($a['transaction_date']) - strtotime($b['transaction_date']);
        });

        $data['head_data'] = $head_data;
        $data['sch_setting'] = $this->sch_setting_detail;

        $this->load->view('admin/accounts/income_ledger_pdf', $data);
    }


    public function ledger_supplier_wise()
    {
        if (!$this->rbac->hasPrivilege('ledger', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Ledger');
        $this->session->set_userdata('sub_menu', 'admin/accounts/ledger');

        // Get input values
        $date_from = $this->input->post('date_from') ?? date('Y') . '-01-01';
        $date_to = $this->input->post('date_to') ?? date('Y-m-d');
        $supplier_id = $this->input->post('supplier_id');

        // Store values for the view
        $data['date_from'] = $date_from;
        $data['date_to'] = $date_to;
        $data['supplier_id'] = $supplier_id;

        // Get supplier list
        $data['suppliers'] = $this->expense_model->get_suppliers();

        // Fetch ledger data
        $ledger_data = $this->accounts_model->getLedgerEntries($date_from, $date_to, $supplier_id);

        // Extract opening balance and ledger entries
        $opening_balance = $ledger_data['opening_balance'];
        $ledger_entries = $ledger_data['ledger_entries'];

        // Calculate closing balance
        $total_dr = 0;
        $total_cr = 0;

        foreach ($ledger_entries as $entries) {
            foreach ($entries as $entry) {
                $total_dr += $entry['dr'] ?? 0;
                $total_cr += $entry['cr'] ?? 0;
            }
        }

        $closing_balance = $opening_balance + $total_dr - $total_cr;

        // Pass data to view
        $data['ledger_entries'] = $ledger_entries;
        $data['opening_balance'] = $opening_balance;
        $data['closing_balance'] = $closing_balance;

        // Load views
        $this->load->view('layout/header', $data);
        $this->load->view('admin/accounts/ledger', $data);
        $this->load->view('layout/footer', $data);
    }

    public function fix_deposite($id = null)
    {
        if (!$this->rbac->hasPrivilege('fix_deposite', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Fix Deposite');
        $this->session->set_userdata('sub_menu', 'admin/accounts/fix_deposite');

        $data['fix_deposites'] = $this->accounts_model->get_fix_deposites();

        $this->load->view('layout/header', $data);
        $this->load->view('admin/accounts/fix_deposite', $data);
        $this->load->view('layout/footer', $data);
    }

    public function add_fix_deposite($id = null)
    {
        if (!$this->rbac->hasPrivilege('fix_deposite', 'can_add')) {
            access_denied();
        }

        // Fetch existing data if editing
        $data['fix_deposite'] = (!empty($id)) ? $this->accounts_model->get_fix_deposite_by_id($id) : null;
        $data['fix_deposites'] = $this->accounts_model->get_fix_deposites();
        // Form validation rules
        $this->form_validation->set_rules('name', 'Name', 'required');
        $this->form_validation->set_rules('customer_number', 'Customer Number', 'required');
        $this->form_validation->set_rules('debit_acc_no', 'Debit Account No', 'required');
        $this->form_validation->set_rules('acc_no', 'Account No', 'required');
        $this->form_validation->set_rules('tenure', 'Tenure', 'required|integer');
        $this->form_validation->set_rules('interest', 'Interest Rate', 'required|numeric');
        $this->form_validation->set_rules('prcpl_amount', 'Principal Amount', 'required|numeric');
        $this->form_validation->set_rules('deposite_date', 'Deposit Date', 'required');
        $this->form_validation->set_rules('maturity_date', 'Maturity Date', 'required');
        $this->form_validation->set_rules('maturity_value', 'Maturity Value', 'required|numeric');

        if ($this->form_validation->run() == false) {
            $this->session->set_flashdata('msg', validation_errors());


            $this->load->view('layout/header', $data);
            $this->load->view('admin/accounts/fix_deposite', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $post_fex_id = $this->input->post('id');
            $formData = [
                'ref_no' => $this->input->post('ref_no'),
                'name' => $this->input->post('name'),
                'customer_number' => $this->input->post('customer_number'),
                'debit_acc_no' => $this->input->post('debit_acc_no'),
                'acc_no' => $this->input->post('acc_no'),
                'tenure' => $this->input->post('tenure'),
                'interest' => $this->input->post('interest'),
                'prcpl_amount' => $this->input->post('prcpl_amount'),
                'deposite_date' => $this->input->post('deposite_date'),
                'maturity_date' => $this->input->post('maturity_date'),
                'maturity_value' => $this->input->post('maturity_value'),
                'status' => $this->input->post('status') ?? 1,
            ];

            if (!empty($post_fex_id)) {
                // Update existing record
                $this->accounts_model->update_fix_deposite($post_fex_id, $formData);
                $this->session->set_flashdata('msg', 'Fix Deposite updated successfully');
            } else {
                // Insert new record
                $this->accounts_model->add_fix_deposite($formData);
                $this->session->set_flashdata('msg', 'Fix Deposite added successfully');
            }

            redirect('admin/accounts/fix_deposite');
        }
    }

    public function delete_fix_deposite($id)
    {
        if (!$this->rbac->hasPrivilege('fix_deposite', 'can_delete')) {
            access_denied();
        }

        $this->accounts_model->delete_fix_deposite($id);
        $this->session->set_flashdata('msg', 'Fix Deposite deleted successfully');
        redirect('admin/accounts/fix_deposite');
    }

    public function assets($id = null)
    {
        if (!$this->rbac->hasPrivilege('assets', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Assets');
        $this->session->set_userdata('sub_menu', 'admin/accounts/assets');

        $data['assets'] = $this->accounts_model->get_assets();
        $data['sessionList'] = $this->session_model->getAllSession();
        $data['selected_data'] = !empty($id) ? $this->accounts_model->get_asset_by_id($id) : null;

        $this->load->view('layout/header', $data);
        $this->load->view('admin/accounts/assets', $data);
        $this->load->view('layout/footer', $data);
    }

    public function add_asset()
    {
        if (!$this->rbac->hasPrivilege('assets', 'can_add')) {
            access_denied();
        }

        $data['sessionList'] = $this->session_model->getAllSession();
        $data['assets'] = $this->accounts_model->get_assets();

        $this->form_validation->set_rules('title', 'Title', 'required');
        $this->form_validation->set_rules('selected_session_id', 'Session', 'required');
        $this->form_validation->set_rules('amount', 'Amount', 'required|numeric');

        if ($this->form_validation->run() == false) {
            $this->session->set_flashdata('msg', validation_errors());

            $id = $this->input->post('id');
            $data['selected_data'] = !empty($id) ? $this->accounts_model->get_asset_by_id($id) : null;

            $this->load->view('layout/header', $data);
            $this->load->view('admin/accounts/assets', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $formData = [
                'title' => $this->input->post('title'),
                'session_id' => $this->input->post('selected_session_id'),
                'amount' => $this->input->post('amount'),
                'descriptions' => $this->input->post('descriptions'),
                'status' => $this->input->post('status') ?? 1,
            ];

            if (!empty($this->input->post('id'))) {
                $this->accounts_model->update_asset($this->input->post('id'), $formData);
                $this->session->set_flashdata('msg', 'Asset updated successfully');
            } else {
                $this->accounts_model->add_asset($formData);
                $this->session->set_flashdata('msg', 'Asset added successfully');
            }

            redirect('admin/accounts/assets');
        }
    }

    public function delete_asset($id)
    {
        if (!$this->rbac->hasPrivilege('assets', 'can_delete')) {
            access_denied();
        }

        $this->accounts_model->delete_asset($id);
        $this->session->set_flashdata('msg', 'Asset deleted successfully');
        redirect('admin/accounts/assets');
    }

    public function account_departments($id = null)
    {
        if (!$this->rbac->hasPrivilege('account_departments', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Accounts');
        $this->session->set_userdata('sub_menu', 'admin/accounts/account_departments');
        $this->load->model('account_department_model');

        $data['account_departments'] = $this->account_department_model->get();
        $data['selected_data'] = !empty($id) ? $this->account_department_model->get($id) : null;

        $this->form_validation->set_rules('name', 'Name', 'required');

        if ($this->form_validation->run() == false) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/accounts/account_departments', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $formData = [
                'name' => $this->input->post('name'),
                'description' => $this->input->post('descriptions'),
            ];

            if (!empty($this->input->post('id'))) {
                $formData['id'] = $this->input->post('id');
            }

            $this->account_department_model->add($formData);

            if (!empty($this->input->post('id'))) {
                $this->session->set_flashdata('msg', '<div class="alert alert-success">Department updated successfully</div>');
            } else {
                $this->session->set_flashdata('msg', '<div class="alert alert-success">Department added successfully</div>');
            }

            redirect('admin/accounts/account_departments');
        }
    }

    public function account_departments_delete($id)
    {
        if (!$this->rbac->hasPrivilege('account_departments', 'can_delete')) {
            access_denied();
        }
        $this->load->model('account_department_model');
        $this->account_department_model->remove($id);
        $this->session->set_flashdata('msg', '<div class="alert alert-success">Department deleted successfully</div>');
        redirect('admin/accounts/account_departments');
    }

    public function cashbook2()
    {
        if (!$this->rbac->hasPrivilege('cashbook', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Accounts');
        $this->session->set_userdata('sub_menu', 'admin/accounts/cashbook2');

        // Get date range input
        $date_from = $this->input->post('date_from');
        $date_to = $this->input->post('date_to');

        // Default date range
        if (!$date_from) {
            $date_from = date('Y-m') . '-01';
        }
        if (!$date_to) {
            $date_to = date('Y-m-d');
        }

        $previous_date = date('Y-m-d', strtotime($date_from . ' -1 day'));

        $data['opening_balance'] = [
            'cash' => 0,
            'bank' => 0
        ]; // Initialize opening balances for Cash and Bank

        $paymentMethods = $this->accounts_model->getPaymentMethods();

        if (!empty($paymentMethods)) {
            foreach ($paymentMethods as $paymentMethod) {
                $balanceData = $this->accounts_model->getBalanceByTransDate($paymentMethod['id'], "", $previous_date);

                // Separate balances for Cash and Bank
                if ($paymentMethod['method_type_id'] == 1) {
                    // Cash Balance
                    $data['opening_balance']['cash'] += $balanceData['balance'];
                }

                if ($paymentMethod['method_type_id'] == 2) {
                    // Bank Balance
                    $data['opening_balance']['bank'] += $balanceData['balance'];
                }
            }
        }

        $total_receipts = $this->accounts_model->get_all_receipts($date_from, $date_to);
        $total_payments = $this->accounts_model->get_all_payments($date_from, $date_to);

        $receipts_by_department = [];
        foreach ($total_receipts as $receipt) {
            $department = $receipt['department_name'] ?? 'Default';
            $receipts_by_department[$department][] = $receipt;
        }

        $payments_by_department = [];
        foreach ($total_payments as $payment) {
            $department = $payment['department_name'] ?? 'Default';
            $payments_by_department[$department][] = $payment;
        }

        $data['receipts_by_department'] = $receipts_by_department;
        $data['payments_by_department'] = $payments_by_department;

        $data['date_from'] = $date_from;
        $data['date_to'] = $date_to;

        // Load view
        $this->load->view('layout/header', $data);
        $this->load->view('admin/accounts/cashbook2', $data);
        $this->load->view('layout/footer', $data);
    }

    public function income_ledger2()
    {
        if (!$this->rbac->hasPrivilege('income_ledger', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Income Ledger');
        $this->session->set_userdata('sub_menu', 'admin/accounts/income_ledger2');

        $date_from = $this->input->post('date_from') ?? date('Y') . '-01-01';
        $date_to = $this->input->post('date_to') ?? date('Y-m-d');
        $department_id = $this->input->post('department_id');

        $data['date_from'] = $date_from;
        $data['date_to'] = $date_to;
        $data['department_id'] = $department_id;

        $this->load->model('account_department_model');
        $data['departments'] = $this->account_department_model->get();

        // Load model and fetch head-wise data
        $this->load->model('accounts_model');
        $head_data = $this->accounts_model->get_income_ledger($date_from, $date_to, $department_id);

        $head_data_by_department = [];
        foreach ($head_data as $entry) {
            $department = $entry['department_name'] ?? 'Default';
            $head_data_by_department[$department][] = $entry;
        }

        $data['head_data_by_department'] = $head_data_by_department;

        $this->load->view('layout/header', $data);
        $this->load->view('admin/accounts/income_ledger2', $data);
        $this->load->view('layout/footer', $data);
    }

    public function balance_sheet_department_wise()
    {
        if (!$this->rbac->hasPrivilege('balance_sheet', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Accounts');
        $this->session->set_userdata('sub_menu', 'admin/accounts/balance_sheet_department_wise');

        // Get date range input
        $date_from = $this->input->post('date_from');
        $date_to = $this->input->post('date_to');
        $department_id = $this->input->post('department_id');

        // Default date range
        if (!$date_from) {
            $date_from = date('Y') . '-01-01';
        }
        if (!$date_to) {
            $date_to = date('Y-m-d');
        }

        $data['date_from'] = $date_from;
        $data['date_to'] = $date_to;
        $data['department_id'] = $department_id;

        $this->load->model('account_department_model');
        $data['departments'] = $this->account_department_model->get();

        // Fetch income and expense data
        $data['income_details'] = $this->accounts_model->get_grouped_income_by_department($date_from, $date_to, $department_id);
        $data['expense_details'] = $this->accounts_model->get_grouped_expenses_by_department($date_from, $date_to, $department_id);

        $data['student_fees_total_admission_fees'] = $this->accounts_model->get_student_fees_total_by_department(1, $date_from, $date_to, $department_id);
        $data['student_fees_total_without_admission_fees'] = $this->accounts_model->get_student_fees_total_by_department(0, $date_from, $date_to, $department_id);

        // Fetch staff payroll total
        $data['payroll_total'] = $this->accounts_model->get_payroll_total_by_department($date_from, $date_to, $department_id);
        $data['payroll_refunded'] = $this->accounts_model->get_refunded_payroll_by_department($date_from, $date_to, $department_id);

        //total loan taken by staff
        $this->load->model('staff_model');
        $data['total_staff_loan'] = $this->staff_model->get_total_loans_by_department($date_from, $date_to, $department_id);

        // Opening and closing balances will remain overall totals for now.
        $previous_date = date('Y-m-d', strtotime($date_from . ' -1 day'));
        $data['opening_balance'] = [];
        $totalBalance = 0;
        $paymentMethods = $this->accounts_model->getPaymentMethods();
        if (!empty($paymentMethods)) {
            foreach ($paymentMethods as $paymentMethod) {
                $balanceData = $this->accounts_model->getBalanceByTransDate($paymentMethod['id'], "", $previous_date);
                $data['opening_balance'][] = [
                    'payment_method_id' => $paymentMethod['id'],
                    'payment_method_name' => $paymentMethod['title'],
                    'method_type_id' => $paymentMethod['method_type_id'],
                    'method_type' => $paymentMethod['method_type'],
                    'balance' => $balanceData['balance']
                ];
                $totalBalance += $balanceData['balance'];
            }
        }
        $data['total_opening_balance'] = $totalBalance;

        $data['closing_balance'] = [];
        $total_closing_balance = 0;
        if (!empty($paymentMethods)) {
            foreach ($paymentMethods as $paymentMethod) {
                $balanceData = $this->accounts_model->getBalanceByTransDate($paymentMethod['id'], "", $date_to);
                $data['closing_balance'][] = [
                    'payment_method_id' => $paymentMethod['id'],
                    'payment_method_name' => $paymentMethod['title'],
                    'method_type_id' => $paymentMethod['method_type_id'],
                    'method_type' => $paymentMethod['method_type'],
                    'balance' => $balanceData['balance']
                ];
                $total_closing_balance += $balanceData['balance'];
            }
        }
        $data['total_closing_balance'] = $total_closing_balance;

        // Load view
        $this->load->view('layout/header', $data);
        $this->load->view('admin/accounts/balance_sheet_department_wise', $data);
        $this->load->view('layout/footer', $data);
    }
}
