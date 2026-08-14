<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Accounts_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
        // Additional initialization code if needed
    }

    public function addPaymentMethod($data)
    {

        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->insert('payment_methods', $data);
        $query     = $this->db->insert_id();
        $message   = INSERT_RECORD_CONSTANT . " On Payment Method id " . $query;
        $action    = "Insert";
        $record_id = $query;
        $this->log($message, $record_id, $action);
        //======================Code End==============================

        $this->db->trans_complete(); # Completing transaction
        /* Optional */

        if ($this->db->trans_status() === false) {
            # Something went wrong.
            $this->db->trans_rollback();
            return false;
        } else {
            //return $return_value;
        }
        return $query;
    }

    public function updatePaymentMethod($id, $paymentMethodData)
    {

        // Update balances in the database
        $this->db->trans_start();

        $this->db->where('id', $id);
        $this->db->update('payment_methods', $paymentMethodData);

        $this->db->trans_complete();

        return $this->db->trans_status();
    }

    public function update_transaction($table_id, $transaction_for_table, $trans_data)
    {
        // Begin transaction
        $this->db->trans_start();

        // Check if the record exists
        $this->db->select('id');
        $this->db->from('transactions');
        $this->db->where('table_id', $table_id);
        $this->db->where('transaction_for_table', $transaction_for_table);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {

            // Get the transaction ID of the existing record
            $transaction_id = $query->row()->id;

            // Perform the update
            $this->db->where('table_id', $table_id);
            $this->db->where('transaction_for_table', $transaction_for_table);
            $this->db->update('transactions', $trans_data);

            $this->db->trans_complete();

            // Return both the transaction status and the transaction ID
            if ($this->db->trans_status()) {
                return $transaction_id;
            }
        }

        $this->db->trans_complete();

        // If the record does not exist or the transaction fails
        return  null;
    }


    public function removePaymentMethod($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('payment_methods');
    }

    public function getPaymentMethods()
    {
        $this->db->select('payment_methods.id, payment_methods.title, payment_methods.code, payment_methods.status, payment_method_type.id AS method_type_id, payment_method_type.title AS method_type');
        $this->db->from('payment_methods');
        $this->db->join('payment_method_type', 'payment_method_type.id = payment_methods.method_type_id', 'left');
        $query = $this->db->get();
        $paymentMethods = $query->result_array();

        // Initialize $data as an empty array
        $data = [];

        foreach ($paymentMethods as $method) {
            $balanceData = $this->getBalanceByTransDate($method['id']);

            $data[] = array(
                'id' => $method['id'],
                'title' => $method['title'],
                'current_balance' => $balanceData['balance'],
                'code' => $method['code'],
                'method_type_id' => $method['method_type_id'],
                'method_type' => $method['method_type'],
                'status' => $method['status'],
            );
        }

        return $data;
    }


    public function getPaymentMethodsDetails($id)
    {
        $this->db->select('*');
        $this->db->from('payment_methods');
        $this->db->where('id', $id);
        $query = $this->db->get();
        return $query->row_array();
    }

    public function getAccountBalances()
    {
        // Your logic for retrieving account balances goes here
    }

    public function getStatements()
    {
        $this->db->select('transactions.amount, transactions.trans_type, transactions.balance, payment_methods.title AS payment_method_name, staff.name AS trans_by_name, transactions.descriptions, transactions.trans_date, transactions.created_at');
        $this->db->from('transactions');
        $this->db->join('payment_methods', 'transactions.payment_method_id = payment_methods.id', 'left');
        $this->db->join('staff', 'transactions.trans_by = staff.id', 'left');
        $this->db->where('transactions.status', 1);
        $this->db->order_by('transactions.id', 'DESC'); // Order by transaction date in descending order
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getFilteredStatements($payment_method_id, $from_date, $to_date)
    {
        $this->db->select('transactions.id as trans_id, transactions.amount, transactions.trans_type, transactions.transaction_for_table, transactions.balance, payment_methods.title AS payment_method_name, staff.name as trans_by_name, transactions.descriptions, transactions.trans_date, transactions.created_at');
        $this->db->from('transactions');
        $this->db->join('payment_methods', 'transactions.payment_method_id = payment_methods.id', 'left');
        $this->db->join('staff', 'transactions.trans_by = staff.id', 'left');

        // Apply filter conditions based on provided parameters
        if (!empty($payment_method_id)) {
            $this->db->where('transactions.payment_method_id', $payment_method_id);
        }
        if (!empty($from_date) && !empty($to_date)) {
            $this->db->where('transactions.trans_date >=', $from_date);
            $this->db->where('transactions.trans_date <=', $to_date);
        } elseif (!empty($from_date)) {
            $this->db->where('transactions.trans_date >=', $from_date);
        } elseif (!empty($to_date)) {
            $this->db->where('transactions.trans_date <=', $to_date);
        }
        $this->db->where('transactions.status', 1);

        $this->db->order_by('transactions.trans_date ASC, transactions.trans_type ASC'); // Order by transaction date in descending order
        $query = $this->db->get();
        return $query->result_array();
    }

    /** balance view by transaction date */
    public function getFilteredStatementsTransDate($payment_method_id = "", $from_date = "", $to_date = "")
    {
        $this->db->select('transactions.id as trans_id, transactions.amount, transactions.trans_type, transactions.transaction_for_table, transactions.balance, payment_methods.title AS payment_method_name, staff.name as trans_by_name, transactions.descriptions, transactions.trans_date, transactions.created_at');
        $this->db->from('transactions');
        $this->db->join('payment_methods', 'transactions.payment_method_id = payment_methods.id', 'left');
        $this->db->join('staff', 'transactions.trans_by = staff.id', 'left');

        // Apply filter conditions based on provided parameters
        if (!empty($payment_method_id)) {
            $this->db->where('transactions.payment_method_id', $payment_method_id);
        }
        if (!empty($from_date) && !empty($to_date)) {
            $this->db->where('transactions.trans_date >=', $from_date);
            $this->db->where('transactions.trans_date <=', $to_date);
        } elseif (!empty($from_date)) {
            $this->db->where('transactions.trans_date >=', $from_date);
        } elseif (!empty($to_date)) {
            $this->db->where('transactions.trans_date <=', $to_date);
        }
        $this->db->where('transactions.status', 1);

        $this->db->order_by('transactions.trans_date ASC, transactions.id ASC');

        //$this->db->order_by('transactions.created_at', 'ASC'); // Order by create_at date in ascending order

        $query = $this->db->get();
        return $query->result_array();
    }

    /** balance view by transaction date */
    public function getBalanceByTransDate($payment_method_id, $from_date = "", $to_date = "")
    {
        $this->db->select("
                SUM(
                    CASE 
                        WHEN trans_type = 1 THEN amount
                        WHEN trans_type = 2 THEN -amount
                        WHEN trans_type = 3 THEN 
                            CASE 
                                WHEN transaction_for_table IN ('expenses', 'staff_payslip', 'staff_loans') THEN amount
                                WHEN transaction_for_table IN ('income', 'student_fees_collections') THEN -amount
                                ELSE 0
                            END
                        ELSE 0
                    END
                ) AS balance", false);

        $this->db->from('transactions');

        if (!empty($from_date) && !empty($to_date)) {
            $this->db->where('transactions.trans_date >=', $from_date);
            $this->db->where('transactions.trans_date <=', $to_date);
        } elseif (!empty($from_date)) {
            $this->db->where('transactions.trans_date >=', $from_date);
        } elseif (!empty($to_date)) {
            $this->db->where('transactions.trans_date <=', $to_date);
        }
        if ($payment_method_id) {
            $this->db->where('payment_method_id', $payment_method_id);
        }
        $this->db->where('transactions.status', 1);
        $query = $this->db->get();
        $result = $query->row();

        // Return both balance and payment method name
        return ['balance' => $result->balance ?? 0];
    }


    // Add this method to your Accounts_model.php
    public function transferFunds($amount, $source_method_id, $target_method_id, $note, $ft_id, $trans_date)
    {
        // Update balances in the database
        $this->db->trans_start();

        $trans_by = $this->session->userdata['admin']['id'];

        // If $trans_date is null, use the current date
        $trans_date = ($trans_date) ? $trans_date : date('Y-m-d H:i:s');

        // Insert transaction for source method (debit)
        $this->db->insert('transactions', array(
            'amount' => $amount,
            'trans_type' => 2, // Debit
            'payment_method_id' => $source_method_id,
            'descriptions' => $note,
            'transaction_for_table' => 'fund_transfers',
            'table_id' => $ft_id,
            'trans_by' => $trans_by, // Assuming the transaction is done by the system
            'trans_date' => $trans_date
        ));

        // Add a 1-second delay
        sleep(1);

        // Insert transaction for target method (credit)
        $this->db->insert('transactions', array(
            'amount' => $amount,
            'trans_type' => 1, // Credit
            'payment_method_id' => $target_method_id,
            'descriptions' => $note,
            'transaction_for_table' => 'fund_transfers',
            'table_id' => $ft_id,
            'trans_by' => $trans_by, // Assuming the transaction is done by the system
            'trans_date' => $trans_date
        ));

        $this->db->trans_complete();

        return $this->db->trans_status(); // Return true if transaction successful, false otherwise
    }

    public function add_funds($amount, $payment_method_id, $note, $trans_date, $transaction_for_table = "", $table_id = 0, $status = 1)
    {
        // Fetch current balance
        $payment_method = $this->db->get_where('payment_methods', array('id' => $payment_method_id))->row_array();
        $current_balance = $payment_method['current_balance'];

        // Calculate new balance after adding funds
        $new_balance = $current_balance + $amount;

        // Update balance in the database
        $this->db->trans_start();

        $this->db->where('id', $payment_method_id);
        $this->db->update('payment_methods', array('current_balance' => $new_balance));

        $created_at = date('Y-m-d H:i:s');
        // Insert transaction (credit)
        $trans_by = $this->session->userdata['admin']['id'];
        $this->db->insert('transactions', array(
            'amount' => $amount,
            'trans_type' => 1, // Credit
            'balance' => $new_balance,
            'payment_method_id' => $payment_method_id,
            'descriptions' => $note,
            'transaction_for_table' => $transaction_for_table,
            'table_id' => $table_id,
            'trans_by' => $trans_by,
            'trans_date' => $trans_date,
            'created_at' => $created_at,
            'status' => $status
        ));

        $this->db->trans_complete();

        return $this->db->trans_status(); // Return true if transaction successful, false otherwise
    }

    public function remove_funds($amount, $payment_method_id, $note, $trans_date, $transaction_for_table = "", $table_id = 0, $status = 1)
    {
        // Update balance in the database
        $this->db->trans_start();

        $created_at = date('Y-m-d H:i:s');
        // Insert transaction (debit)
        $trans_by = $this->session->userdata['admin']['id'];
        $this->db->insert('transactions', array(
            'amount' => $amount,
            'trans_type' => 2, // Debit
            'payment_method_id' => $payment_method_id,
            'descriptions' => $note,
            'transaction_for_table' => $transaction_for_table,
            'table_id' => $table_id,
            'trans_by' => $trans_by,
            'trans_date' => $trans_date,
            'created_at' => $created_at,
            'status' => $status
        ));

        $this->db->trans_complete();

        return $this->db->trans_status(); // Return true if transaction successful, false otherwise
    }

    public function refund_funds($amount, $payment_method_id, $note, $trans_date, $transaction_for_table = "", $table_id = 0, $status = 1)
    {
        // Update balance in the database
        $this->db->trans_start();

        $created_at = date('Y-m-d H:i:s');
        // Insert transaction (debit)
        $trans_by = $this->session->userdata['admin']['id'];
        $this->db->insert('transactions', array(
            'amount' => $amount,
            'trans_type' => 2, // debit
            'payment_method_id' => $payment_method_id,
            'descriptions' => $note,
            'transaction_for_table' => $transaction_for_table,
            'table_id' => $table_id,
            'trans_by' => $trans_by,
            'trans_date' => $trans_date,
            'created_at' => $created_at,
            'status' => $status
        ));

        $this->db->trans_complete();

        return $this->db->trans_status(); // Return true if transaction successful, false otherwise
    }

    public function get_grouped_income_by_head($head_id, $mode, $date_from, $date_to)
    {
        // Determine payment method ID(s) based on mode
        $payment_method_id = null; // Default to null
        if ($mode == 'cash') {
            $payment_method_id = 1;
        } elseif ($mode == 'bank') {
            $payment_method_id = [2, 3, 4, 5];
        } elseif ($mode == 'fixed') {
            $payment_method_id = [8, 9];
        }

        // Select fields and join tables
        $this->db->select_sum('i.amount');
        $this->db->from('income i');
        $this->db->join('income_head ih', 'i.income_head_id = ih.id');
        $this->db->where([
            'i.is_refunded' => 0,
            'ih.id'         => $head_id
        ]);

        // Add payment method condition
        if (!is_null($payment_method_id)) {
            if (is_array($payment_method_id)) {
                $this->db->where_in('i.payment_method_id', $payment_method_id);
            } else {
                $this->db->where('i.payment_method_id', $payment_method_id);
            }
        }

        // Add date range condition
        $this->db->where('i.date >=', $date_from);
        $this->db->where('i.date <=', $date_to);

        // Return the result as an array
        $query = $this->db->get();
        return $query->row()->amount ?? 0;
    }

    public function get_grouped_income($date_from, $date_to)
    {
        $this->db->select("
        ig.id as group_id, 
        ig.title as group_title, 
        ih.income_category, 
        SUM(CASE WHEN t.trans_type = 1 THEN t.amount ELSE 0 END) - 
        SUM(CASE WHEN t.trans_type = 2 THEN t.amount ELSE 0 END) as total_amount");

        $this->db->from('transactions t');
        $this->db->join('income i', 't.table_id = i.id', 'left');
        $this->db->join('income_head ih', 'i.income_head_id = ih.id');
        $this->db->join('income_groups ig', 'ih.group_id = ig.id');

        // Apply conditions for the transactions table
        $this->db->where('t.transaction_for_table', 'income');
        $this->db->where('t.trans_date >=', $date_from);
        $this->db->where('t.trans_date <=', $date_to);

        // Group by and order by
        $this->db->group_by(['ih.group_id', 'i.income_head_id']);
        $this->db->order_by('ig.display_order, ih.income_category');

        return $this->db->get()->result_array();
    }

    public function get_grouped_expenses($date_from, $date_to)
    {
        $this->db->select("
        eg.id as group_id, 
        eg.title as group_title, 
        eh.exp_category, 
        SUM(CASE WHEN t.trans_type = 2 THEN t.amount ELSE 0 END) - 
        SUM(CASE WHEN t.trans_type = 1 THEN t.amount ELSE 0 END) as total_amount");

        $this->db->from('transactions t');
        $this->db->join('expenses e', 't.table_id = e.id', 'left');
        $this->db->join('expense_head eh', 'e.exp_head_id = eh.id', 'left');
        $this->db->join('expense_groups eg', 'eh.group_id = eg.id', 'left');

        // Apply conditions for the transactions table
        $this->db->where('t.transaction_for_table', 'expenses');
        $this->db->where('t.trans_date >=', $date_from);
        $this->db->where('t.trans_date <=', $date_to);

        // Group by and order by
        $this->db->group_by(['eh.group_id', 'e.exp_head_id']);
        $this->db->order_by('eg.title, eh.exp_category');

        return $this->db->get()->result_array();
    }

    public function get_refunded_amount($refund_from, $date_from, $date_to)
    {
        $this->db->select_sum('amount', 'total');
        $this->db->where('status', 1);

        if ($refund_from == 'expenses'):
            $this->db->where('trans_type', 1);
        elseif ($refund_from == 'income' || $refund_from == 'student_fees_collections'):
            $this->db->where('trans_type', 2);
        endif;

        $this->db->where('transaction_for_table', $refund_from);
        $this->db->where('trans_date >=', $date_from);
        $this->db->where('trans_date <=', $date_to);

        $query = $this->db->get('transactions');
        return $query->row()->total ?? 0;
    }

    public function get_refunded_amount_by_payment_id($refund_from, $payment_method_id, $date_from, $date_to)
    {
        $this->db->select_sum('amount', 'total');
        $this->db->where('status', 1);

        if ($refund_from == 'expenses'):
            $this->db->where('trans_type', 1);
        elseif ($refund_from == 'income' || $refund_from == 'student_fees_collections'):
            $this->db->where('trans_type', 2);
        endif;

        $this->db->where('payment_method_id', $payment_method_id);
        $this->db->where('transaction_for_table', $refund_from);
        $this->db->where('trans_date >=', $date_from);
        $this->db->where('trans_date <=', $date_to);

        $query = $this->db->get('transactions');
        // Print the actual MySQL query
        /* echo $this->db->last_query();
        echo '---------<br>'; */
        //die;
        return $query->row()->total ?? 0;
    }

    public function get_student_fees_total($is_admission, $date_from, $date_to)
    {
        $this->db->select('SUM(t.amount) as total_paid_amount');
        $this->db->from('transactions t');
        $this->db->join('student_fees_collections sfc', 't.table_id = sfc.id', 'left');

        // Conditions for transactions table
        $this->db->where('t.transaction_for_table', 'student_fees_collections');
        $this->db->where('t.trans_type', 1); // Assuming 1 is for fees collection
        $this->db->where('t.status', 1); // Assuming 1 is for fees collection

        // Conditions for student fees collections
        $this->db->where('sfc.is_refunded', 0);
        if ($is_admission) {
            $this->db->where('sfc.feetype_id', 1); // Admission fees
        } else {
            $this->db->where('sfc.feetype_id !=', 1); // Non-admission fees
        }
        //$this->db->where('sfc.feetype_id' !='', 1); // Admission fees
        $this->db->where('t.trans_date >=', $date_from);
        $this->db->where('t.trans_date <=', $date_to);

        // Execute query and return the result
        $query = $this->db->get();
        return $query->row()->total_paid_amount ?? 0;
    }

    public function get_staff_loan_given($date_from, $date_to)
    {
        $this->db->select_sum('loan_amount');
        $this->db->from('staff_loans');
        $this->db->where('loan_date >=', $date_from);
        $this->db->where('loan_date <=', $date_to);
        $this->db->where('status', 1);
        return $this->db->get()->row()->loan_amount ?? 0;
    }

    public function get_staff_loan_repayments($date_from, $date_to)
    {
        $this->db->select_sum('amount');
        $this->db->from('staff_loan_payments');
        $this->db->where('payment_date >=', $date_from);
        $this->db->where('payment_date <=', $date_to);
        $this->db->where('status', 1);
        return $this->db->get()->row()->amount ?? 0;
    }

    public function get_hosteller_admission($date_from, $date_to)
    {
        $this->db->select('SUM(sfc.paid_amount) as total_paid_amount');
        $this->db->from('student_fees_collections sfc');
        $this->db->join('student_session ss', 'ss.id = sfc.student_session_id', 'left');
        $this->db->where('sfc.status', 1);
        $this->db->where('sfc.is_refunded', 0);
        $this->db->where('ss.account_department_id', 3);
        $this->db->where_in('sfc.feetype_id', [1, 2]); // Admission + Re-Admission
        $this->db->where('sfc.collection_date >=', $date_from);
        $this->db->where('sfc.collection_date <=', $date_to);
        return $this->db->get()->row()->total_paid_amount ?? 0;
    }

    public function get_hosteller_tution($date_from, $date_to)
    {
        $this->db->select('SUM(sfc.paid_amount) as total_paid_amount');
        $this->db->from('student_fees_collections sfc');
        $this->db->join('student_session ss', 'ss.id = sfc.student_session_id', 'left');
        $this->db->where('sfc.status', 1);
        $this->db->where('sfc.is_refunded', 0);
        $this->db->where('ss.account_department_id', 3);
        $this->db->where_not_in('sfc.feetype_id', [1, 2]); // Exclude Admission + Re-Admission
        $this->db->where('sfc.collection_date >=', $date_from);
        $this->db->where('sfc.collection_date <=', $date_to);
        return $this->db->get()->row()->total_paid_amount ?? 0;
    }

    public function get_primary_admission($date_from, $date_to)
    {
        $this->db->select('SUM(sfc.paid_amount) as total_paid_amount');
        $this->db->from('student_fees_collections sfc');
        $this->db->join('student_session ss', 'ss.id = sfc.student_session_id', 'left');
        $this->db->where('sfc.status', 1);
        $this->db->where('sfc.is_refunded', 0);
        $this->db->where('ss.account_department_id', 1);
        $this->db->where_in('sfc.feetype_id', [1, 2]); // Admission + Re-Admission
        $this->db->where('sfc.collection_date >=', $date_from);
        $this->db->where('sfc.collection_date <=', $date_to);
        return $this->db->get()->row()->total_paid_amount ?? 0;
    }

    public function get_primary_tution($date_from, $date_to)
    {
        $this->db->select('SUM(sfc.paid_amount) as total_paid_amount');
        $this->db->from('student_fees_collections sfc');
        $this->db->join('student_session ss', 'ss.id = sfc.student_session_id', 'left');
        $this->db->where('sfc.status', 1);
        $this->db->where('sfc.is_refunded', 0);
        $this->db->where('ss.account_department_id', 1);
        $this->db->where_not_in('sfc.feetype_id', [1, 2]); // Exclude Admission + Re-Admission
        $this->db->where('sfc.collection_date >=', $date_from);
        $this->db->where('sfc.collection_date <=', $date_to);
        return $this->db->get()->row()->total_paid_amount ?? 0;
    }

    public function department_wise_fees($date_from, $date_to, $feecategory_id)
    {
        $this->db->select('
        ad.id as department_id,
        ad.name as department_name,
        SUM(t.amount) as total_paid_amount
        ');

        $this->db->from('transactions t');
        $this->db->join('student_fees_collections sfc', 't.table_id = sfc.id', 'left');
        $this->db->join('student_session ss', 'sfc.student_id = ss.student_id AND `sfc`.`session_id` = `ss`.`session_id`', 'left');
        $this->db->join('feetype tf', 'sfc.feetype_id = tf.id', 'left');
        $this->db->join('account_departments ad', 'ss.account_department_id = ad.id', 'left');

        // Common conditions
        $this->db->where('t.transaction_for_table', 'student_fees_collections');
        $this->db->where('t.trans_type', 1);
        $this->db->where('t.status', 1);
        $this->db->where('sfc.is_refunded', 0);
        $this->db->where('tf.feecategory_id', $feecategory_id);
        $this->db->where('t.trans_date >=', $date_from);
        $this->db->where('t.trans_date <=', $date_to);

        // Group by department
        $this->db->group_by('ad.id');

        // Execute
        $query = $this->db->get();
        return $query->result();
    }

    //Balance Sheet :: Received section => Primary Section Tuition fees, Admission fees, Development Fees and Others

    /**
     * 1 = primary_tuition_fees
     * 2 = primary_admission_fees
     * 3 = primary_development_fees
     * 4 = primary_others_income
     */


    public function fees_collection_account_departments($date_from, $date_to, $ac_depertment_id)
    {
        $this->db->select('
        fg.name AS fee_group_name,
        SUM(sfc.paid_amount) AS total_paid_amount
        ');
        $this->db->from('student_fees_collections sfc');
        $this->db->join('student_session ss', 'sfc.student_session_id = ss.id', 'left');
        $this->db->join('feetype ft', 'sfc.feetype_id = ft.id', 'left');
        $this->db->join('fee_groups fg', 'fg.id = ft.feecategory_id', 'left');

        // Filters
        $this->db->where('ss.account_department_id', $ac_depertment_id);
        $this->db->where('sfc.is_refunded', 0);
        $this->db->where('sfc.collection_date >=', $date_from);
        $this->db->where('sfc.collection_date <=', $date_to);

        // Group and order
        $this->db->group_by(['fg.id', 'fg.name']);
        $this->db->order_by('fg.name', 'ASC');

        $query = $this->db->get();
        return $query->result();
    }

    public function general_income($date_from, $date_to)
    {
        // 1️⃣ Main income query
        $this->db->select("
        ih.id AS income_head_id,
        ih.income_category AS income_category_name,
        SUM(i.amount) AS total_amount
        ");
        $this->db->from('income i');
        $this->db->join('income_head ih', 'i.income_head_id = ih.id', 'left');
        $this->db->where('i.is_refunded', 0);
        $this->db->where('i.date >=', $date_from);
        $this->db->where('i.date <=', $date_to);
        $this->db->group_by(['ih.id', 'ih.income_category']);
        $income_query = $this->db->get_compiled_select();

        // 2️⃣ Staff loan payment query
        $this->db->select("
        0 AS income_head_id,
        'Staff Loan Payment' AS income_category_name,
        SUM(amount) AS total_amount
        ");
        $this->db->from('staff_loan_payments');
        $this->db->where('status', 1);
        $this->db->where('payment_date >=', $date_from);
        $this->db->where('payment_date <=', $date_to);
        $loan_query = $this->db->get_compiled_select();

        // 3️⃣ Combine both
        $final_query = $this->db->query("({$income_query}) UNION ALL ({$loan_query}) ORDER BY income_category_name ASC");

        return $final_query->result();
    }

    public function department_expenses($date_from, $date_to)
    {
        // 1️⃣ Main expense query (with expense category)
        $this->db->select("
        e.account_department_id,
        d.name AS department_name,
        ec.title AS expense_category_name,
        eh.exp_category AS expense_head_name,
        SUM(e.amount) AS total_amount
        ");
        $this->db->from('expenses e');
        $this->db->join('expense_head eh', 'e.exp_head_id = eh.id', 'left');
        $this->db->join('expense_groups ec', 'eh.group_id = ec.id', 'left');
        $this->db->join('account_departments d', 'e.account_department_id = d.id', 'left');
        $this->db->where('e.is_refunded', 0);
        $this->db->where('e.date >=', $date_from);
        $this->db->where('e.date <=', $date_to);
        $this->db->group_by(['e.account_department_id', 'ec.id', 'eh.id']);
        $this->db->order_by('d.id', 'ASC');
        $this->db->order_by('ec.title', 'ASC');
        $this->db->order_by('eh.exp_category', 'ASC');

        $query = $this->db->get();
        return $query->result();
    }

    public function department_salary_paid($date_from, $date_to)
    {
        $this->db->select("
        d.id AS department_id,
        d.name AS department_name,
        SUM(sp.net_salary) AS total_salary_paid
        ");
        $this->db->from('staff_payslip sp');
        $this->db->join('staff s', 's.id = sp.staff_id', 'left');
        $this->db->join('account_departments d', 'd.id = s.account_department_id', 'left');
        $this->db->where('sp.status', 'paid');
        $this->db->where('sp.payment_date >=', $date_from);
        $this->db->where('sp.payment_date <=', $date_to);
        $this->db->group_by(['d.id', 'd.name']);
        $this->db->order_by('d.id', 'ASC');

        $query = $this->db->get();
        return $query->result();
    }

    public function get_secondary_admission($date_from, $date_to)
    {
        $this->db->select('SUM(sfc.paid_amount) as total_paid_amount');
        $this->db->from('student_fees_collections sfc');
        $this->db->join('student_session ss', 'ss.id = sfc.student_session_id', 'left');
        $this->db->where('sfc.status', 1);
        $this->db->where('sfc.is_refunded', 0);
        $this->db->where('ss.account_department_id', 2);
        $this->db->where_in('sfc.feetype_id', [1, 2]); // Admission + Re-Admission
        $this->db->where('sfc.collection_date >=', $date_from);
        $this->db->where('sfc.collection_date <=', $date_to);
        return $this->db->get()->row()->total_paid_amount ?? 0;
    }

    public function get_secondary_tution($date_from, $date_to)
    {
        $this->db->select('SUM(sfc.paid_amount) as total_paid_amount');
        $this->db->from('student_fees_collections sfc');
        $this->db->join('student_session ss', 'ss.id = sfc.student_session_id', 'left');
        $this->db->where('sfc.status', 1);
        $this->db->where('sfc.is_refunded', 0);
        $this->db->where('ss.account_department_id', 2);
        $this->db->where_not_in('sfc.feetype_id', [1, 2]); // Exclude Admission + Re-Admission
        $this->db->where('sfc.collection_date >=', $date_from);
        $this->db->where('sfc.collection_date <=', $date_to);
        return $this->db->get()->row()->total_paid_amount ?? 0;
    }

    public function get_fees_by_all_departments($date_from, $date_to)
    {
        // Ledger-based: net gross collections (trans_type=1) against refunds
        // (trans_type=2) per department/feetype within the period. This keeps the
        // balance sheet in sync with the actual cash ledger / closing balance,
        // including refunds of fees that were collected in a prior period.
        $this->db->select('
            ad.id as dept_id,
            ad.name as dept_name,
            SUM(CASE WHEN sfc.feetype_id IN (1, 2) THEN (CASE WHEN t.trans_type = 1 THEN t.amount ELSE -t.amount END) ELSE 0 END) as admission_total,
            SUM(CASE WHEN sfc.feetype_id NOT IN (1, 2) THEN (CASE WHEN t.trans_type = 1 THEN t.amount ELSE -t.amount END) ELSE 0 END) as tuition_total
        ', false);
        $this->db->from('transactions t');
        $this->db->join('student_fees_collections sfc', 't.table_id = sfc.id', 'left');
        $this->db->join('student_session ss', 'ss.id = sfc.student_session_id', 'left');
        $this->db->join('account_departments ad', 'ss.account_department_id = ad.id', 'left');
        $this->db->where('t.transaction_for_table', 'student_fees_collections');
        $this->db->where('t.status', 1);
        $this->db->where('t.trans_date >=', $date_from);
        $this->db->where('t.trans_date <=', $date_to);
        $this->db->where('ss.account_department_id IS NOT NULL', null, false);
        $this->db->group_by('ad.id, ad.name');
        $this->db->order_by('ad.id', 'ASC');
        return $this->db->get()->result_array();
    }

    public function get_income_ledger($date_from, $date_to, $department_id = null, $income_head = null)
    {
        $result = [];

        // 1. Admission / Re-Admission — grouped by date + head + department
        if (empty($income_head) || $income_head == 'admission_fees' || $income_head == 're_admission') {
            $this->db->select('
                DATE(sfc.approved_date) as transaction_date,
                CASE WHEN sfc.feetype_id = 1 THEN "Admission Fees" ELSE "Re-Admission" END as head,
                CASE WHEN sfc.feetype_id = 1 THEN "Admission Fees" ELSE "Re-Admission" END as particulars,
                SUM(sfc.paid_amount) as total_amount,
                SUM(CASE WHEN pm.method_type_id = 1 THEN sfc.paid_amount ELSE 0 END) as cash_amount,
                SUM(CASE WHEN pm.method_type_id IN(2,4) THEN sfc.paid_amount ELSE 0 END) as bank_amount,
                ad.name as department_name
            ', false);
            $this->db->from('student_fees_collections sfc');
            $this->db->join('student_session ss', 'ss.id = sfc.student_session_id', 'left');
            $this->db->join('payment_methods pm', 'pm.id = sfc.payment_method_id', 'left');
            $this->db->join('account_departments ad', 'ss.account_department_id = ad.id', 'left');
            $this->db->where('sfc.is_refunded', 0);
            $this->db->where('sfc.status', 1);
            if ($income_head == 'admission_fees') {
                $this->db->where('sfc.feetype_id', 1);
            } elseif ($income_head == 're_admission') {
                $this->db->where('sfc.feetype_id', 2);
            } else {
                $this->db->where_in('sfc.feetype_id', [1, 2]);
            }
            $this->db->where('DATE(sfc.approved_date) >=', $date_from);
            $this->db->where('DATE(sfc.approved_date) <=', $date_to);
            if ($department_id) {
                $this->db->where('ss.account_department_id', $department_id);
            }
            $this->db->group_by('DATE(sfc.approved_date), sfc.feetype_id, ss.account_department_id');
            $this->db->order_by('transaction_date ASC, ss.account_department_id ASC');
            $result = array_merge($result, $this->db->get()->result_array());
        }

        // 2. Tuition Fees — grouped by date + department
        if (empty($income_head) || $income_head == 'tuition_fees') {
            $this->db->select('
                DATE(sfc.approved_date) as transaction_date,
                "Tuition Fees" as head,
                "Tuition Fees" as particulars,
                SUM(sfc.paid_amount) as total_amount,
                SUM(CASE WHEN pm.method_type_id = 1 THEN sfc.paid_amount ELSE 0 END) as cash_amount,
                SUM(CASE WHEN pm.method_type_id IN(2,4) THEN sfc.paid_amount ELSE 0 END) as bank_amount,
                ad.name as department_name
            ', false);
            $this->db->from('student_fees_collections sfc');
            $this->db->join('student_session ss', 'ss.id = sfc.student_session_id', 'left');
            $this->db->join('payment_methods pm', 'pm.id = sfc.payment_method_id', 'left');
            $this->db->join('account_departments ad', 'ss.account_department_id = ad.id', 'left');
            $this->db->where('sfc.is_refunded', 0);
            $this->db->where('sfc.status', 1);
            $this->db->where_not_in('sfc.feetype_id', [1, 2]);
            $this->db->where('DATE(sfc.approved_date) >=', $date_from);
            $this->db->where('DATE(sfc.approved_date) <=', $date_to);
            if ($department_id) {
                $this->db->where('ss.account_department_id', $department_id);
            }
            $this->db->group_by('DATE(sfc.approved_date), ss.account_department_id');
            $this->db->order_by('transaction_date ASC, ss.account_department_id ASC');
            $result = array_merge($result, $this->db->get()->result_array());
        }

        // 3. Staff Loan — grouped by date + department
        if (empty($income_head) || $income_head == 'staff_loan') {
            $this->db->select('
                staff_loan_payments.payment_date as transaction_date,
                "Staff Loan" as head,
                "Staff Loan Repayment" as particulars,
                SUM(staff_loan_payments.amount) as total_amount,
                SUM(CASE WHEN pm.method_type_id = 1 THEN staff_loan_payments.amount ELSE 0 END) as cash_amount,
                SUM(CASE WHEN pm.method_type_id IN(2,4) THEN staff_loan_payments.amount ELSE 0 END) as bank_amount,
                ad.name as department_name
            ', false);
            $this->db->from('staff_loan_payments');
            $this->db->join('payment_methods pm', 'pm.id = staff_loan_payments.payment_method_id', 'left');
            $this->db->join('staff_loans sl', 'sl.id = staff_loan_payments.staff_loan_id', 'left');
            $this->db->join('staff s', 's.id = sl.staff_id', 'left');
            $this->db->join('account_departments ad', 's.account_department_id = ad.id', 'left');
            $this->db->where('staff_loan_payments.payment_date >=', $date_from);
            $this->db->where('staff_loan_payments.payment_date <=', $date_to);
            if ($department_id) {
                $this->db->where('s.account_department_id', $department_id);
            }
            $this->db->group_by('staff_loan_payments.payment_date, s.account_department_id');
            $this->db->order_by('transaction_date ASC');
            $result = array_merge($result, $this->db->get()->result_array());
        }

        // 4. Others (General Income) — grouped by date + income_head + department
        if (empty($income_head) || $income_head == 'others') {
            $this->db->select('
                income.date as transaction_date,
                ih.income_category as head,
                ih.income_category as particulars,
                SUM(income.amount) as total_amount,
                SUM(CASE WHEN pm.method_type_id = 1 THEN income.amount ELSE 0 END) as cash_amount,
                SUM(CASE WHEN pm.method_type_id IN(2,4) THEN income.amount ELSE 0 END) as bank_amount,
                ad.name as department_name
            ', false);
            $this->db->from('income');
            $this->db->join('payment_methods pm', 'pm.id = income.payment_method_id', 'left');
            $this->db->join('income_head ih', 'ih.id = income.income_head_id', 'left');
            $this->db->join('account_departments ad', 'income.account_department_id = ad.id', 'left');
            $this->db->where('income.is_refunded', 0);
            $this->db->where('income.date >=', $date_from);
            $this->db->where('income.date <=', $date_to);
            if ($department_id) {
                $this->db->where('income.account_department_id', $department_id);
            }
            $this->db->group_by('income.date, income.income_head_id, income.account_department_id');
            $this->db->order_by('income.date ASC, income.income_head_id ASC');
            $result = array_merge($result, $this->db->get()->result_array());
        }

        // Sort by date then department
        usort($result, function ($a, $b) {
            $cmp = strtotime($a['transaction_date']) - strtotime($b['transaction_date']);
            if ($cmp !== 0) return $cmp;
            return strcmp($a['department_name'] ?? '', $b['department_name'] ?? '');
        });

        return $result;
    }

    public function get_payroll_total($date_from, $date_to)
    {
        $this->db->select_sum('amount');
        $this->db->where('trans_type', 2);
        $this->db->where('transaction_for_table', 'staff_payslip');
        $this->db->where('status', 1);
        $this->db->where('trans_date >=', $date_from);
        $this->db->where('trans_date <=', $date_to);
        $query = $this->db->get('transactions');
        $total_paid_sal = $query->row()->amount ?? 0;

        return $total_paid_sal;
    }

    public function get_refunded_payroll($date_from, $date_to)
    {
        $this->db->select_sum('amount');
        $this->db->where('trans_type', 1);
        $this->db->where('transaction_for_table', 'staff_payslip');
        $this->db->where('status', 1);
        $this->db->where('trans_date >=', $date_from);
        $this->db->where('trans_date <=', $date_to);
        $query = $this->db->get('transactions');
        $total_return_sal = $query->row()->amount ?? 0;

        return $total_return_sal;
    }

    public function get_refunded_payroll_payment_id($payment_method_id, $date_from, $date_to)
    {
        $this->db->select_sum('amount');
        $this->db->where('trans_type', 1);
        $this->db->where('transaction_for_table', 'staff_payslip');
        $this->db->where('status', 1);
        $this->db->where('payment_method_id', $payment_method_id);
        $this->db->where('trans_date >=', $date_from);
        $this->db->where('trans_date <=', $date_to);
        $query = $this->db->get('transactions');
        $total_return_sal = $query->row()->amount ?? 0;

        return $total_return_sal;
    }

    /** for Cashbook - 09.03.2025 */

    public function get_admissions_fees($date_from, $date_to)
    {
        $this->db->select('
        DATE(student_fees_collections.collection_date) as collection_date, 
        SUM(CASE WHEN payment_methods.method_type_id = 1 THEN student_fees_collections.paid_amount ELSE 0 END) as total_cash_admission,
        SUM(CASE WHEN payment_methods.method_type_id = 2 THEN student_fees_collections.paid_amount ELSE 0 END) as total_bank_admission');

        $this->db->from('student_fees_collections');
        $this->db->join('payment_methods', 'payment_methods.id = student_fees_collections.payment_method_id', 'left');

        // Exclude refunded and inactive status
        $this->db->where('student_fees_collections.is_refunded', 0);
        $this->db->where('student_fees_collections.status', 1);

        // Filter by feetype_id = 1 (admission fees only)
        $this->db->where('student_fees_collections.feetype_id', 1);

        // Date filter
        $this->db->where('student_fees_collections.collection_date >=', $date_from);
        $this->db->where('student_fees_collections.collection_date <=', $date_to);

        // Group by date
        $this->db->group_by('DATE(student_fees_collections.collection_date)');
        $this->db->order_by('student_fees_collections.collection_date', 'ASC');

        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_re_admissions_fees($date_from, $date_to)
    {
        $this->db->select('
        DATE(student_fees_collections.collection_date) as collection_date, 
        SUM(CASE WHEN payment_methods.method_type_id = 1 THEN student_fees_collections.paid_amount ELSE 0 END) as total_cash_re_admission,
        SUM(CASE WHEN payment_methods.method_type_id = 2 THEN student_fees_collections.paid_amount ELSE 0 END) as total_bank_re_admission');

        $this->db->from('student_fees_collections');
        $this->db->join('payment_methods', 'payment_methods.id = student_fees_collections.payment_method_id', 'left');

        // Exclude refunded and inactive status
        $this->db->where('student_fees_collections.is_refunded', 0);
        $this->db->where('student_fees_collections.status', 1);

        // Filter by feetype_id = 2 (Re-Admission fees only)
        $this->db->where('student_fees_collections.feetype_id', 2);

        // Date filter
        $this->db->where('student_fees_collections.collection_date >=', $date_from);
        $this->db->where('student_fees_collections.collection_date <=', $date_to);

        // Group by date
        $this->db->group_by('DATE(student_fees_collections.collection_date)');
        $this->db->order_by('student_fees_collections.collection_date', 'ASC');

        $query = $this->db->get();
        return $query->result_array();
    }


    public function get_student_fees($date_from, $date_to)
    {
        $this->db->select('
        DATE(student_fees_collections.collection_date) as collection_date, 
        SUM(CASE WHEN payment_methods.method_type_id = 1 THEN student_fees_collections.paid_amount ELSE 0 END) as total_cash_student_fees,
        SUM(CASE WHEN payment_methods.method_type_id = 2 THEN student_fees_collections.paid_amount ELSE 0 END) as total_bank_student_fees');

        $this->db->from('student_fees_collections');
        $this->db->join('payment_methods', 'payment_methods.id = student_fees_collections.payment_method_id', 'left');

        // Exclude refunded and inactive status
        $this->db->where('student_fees_collections.is_refunded', 0);
        $this->db->where('student_fees_collections.status', 1);

        // Exclude Admission (feetype_id = 1) and Re-Admission (feetype_id = 2)
        $this->db->where_not_in('student_fees_collections.feetype_id', [1, 2]);

        // Date filter
        $this->db->where('student_fees_collections.collection_date >=', $date_from);
        $this->db->where('student_fees_collections.collection_date <=', $date_to);

        // Group by date
        $this->db->group_by('DATE(student_fees_collections.collection_date)');
        $this->db->order_by('student_fees_collections.collection_date', 'ASC');

        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_other_income($date_from, $date_to)
    {
        $this->db->select('
        income.id,
        income.invoice_no,
        income.name,
        income.date AS collection_date,
        income.amount,
        income.note,
        payment_methods.title AS payment_method,
        payment_methods.method_type_id AS payment_method_type,
        income_head.income_category AS income_category');

        $this->db->from('income');
        $this->db->join('payment_methods', 'payment_methods.id = income.payment_method_id', 'left');
        $this->db->join('income_head', 'income_head.id = income.income_head_id', 'left');

        // Exclude refunded and inactive status
        $this->db->where('income.is_refunded', 0);
        $this->db->where('income.status', 1);

        // Date filter
        $this->db->where('income.date >=', $date_from);
        $this->db->where('income.date <=', $date_to);

        // Order by date
        $this->db->order_by('income.date', 'ASC');

        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_all_receipts($date_from, $date_to)
    {
        // Step 1: Fetch Admission Fees
        $this->db->select('
        DATE(sfc.approved_date) as collection_date, 
        "Admission Fees" as particulars,
        "Admission Fees" as head,
        SUM(CASE WHEN pm.method_type_id = 1 THEN sfc.paid_amount ELSE 0 END) as cash_amount,
        SUM(CASE WHEN pm.method_type_id = 2 THEN sfc.paid_amount ELSE 0 END) as bank_amount,
        ad.name as department_name
        ');
        $this->db->from('student_fees_collections sfc');
        $this->db->join('payment_methods pm', 'pm.id = sfc.payment_method_id', 'left');
        $this->db->join('student_session ss', 'sfc.student_id = ss.student_id AND `sfc`.`session_id` = `ss`.`session_id`', 'left');
        $this->db->join('account_departments ad', 'ss.account_department_id = ad.id', 'left');
        // Gross collections (refunded ones included here; the refund is shown on the payments side)
        $this->db->where('sfc.status', 1);
        $this->db->where('DATE(sfc.approved_date) >=', $date_from);
        $this->db->where('DATE(sfc.approved_date) <=', $date_to);
        $this->db->where_in('sfc.feetype_id', [1, 2]);
        $this->db->group_by('DATE(sfc.approved_date), ad.id');
        /* echo $this->db->get_compiled_select();
        exit; */
        $admission_fees = $this->db->get()->result_array();


        // Add VN for each date
        foreach ($admission_fees as &$row) {
            $row['vn'] = $this->get_vn($row['collection_date']);
        }

        // Step 3: Fetch Student Fees
        $this->db->select('
        DATE(sfc.approved_date) as collection_date, 
        "Student Fees" as particulars,
        "Tution Fees" as head,
        SUM(CASE WHEN pm.method_type_id = 1 THEN sfc.paid_amount ELSE 0 END) as cash_amount,
        SUM(CASE WHEN pm.method_type_id = 2 THEN sfc.paid_amount ELSE 0 END) as bank_amount,
        ad.name as department_name
        ');
        $this->db->from('student_fees_collections sfc');
        $this->db->join('payment_methods pm', 'pm.id = sfc.payment_method_id', 'left');
        $this->db->join('student_session ss', 'sfc.student_id = ss.student_id AND `sfc`.`session_id` = `ss`.`session_id`', 'left');
        $this->db->join('account_departments ad', 'ss.account_department_id = ad.id', 'left');
        // Gross collections (refunded ones included here; the refund is shown on the payments side)
        $this->db->where('sfc.status', 1);
        $this->db->where_not_in('sfc.feetype_id', [1, 2]);
        $this->db->where('DATE(sfc.approved_date) >=', $date_from);
        $this->db->where('DATE(sfc.approved_date) <=', $date_to);
        $this->db->group_by('DATE(sfc.approved_date), ad.id');
        $student_fees = $this->db->get()->result_array();

        foreach ($student_fees as &$row) {
            $row['vn'] = $this->get_vn($row['collection_date']);
        }

        // Step 4: Fetch Other Income
        $this->db->select('
            DATE(i.date) as collection_date,
            ih.income_category as head,
            GROUP_CONCAT(CONCAT(i.name, " (", i.invoice_no, ")", " - ", i.note) SEPARATOR "\n") as particulars,
            pm.title as payment_method,
            SUM(IF(pm.method_type_id = 1, i.amount, 0)) as cash_amount,
            SUM(IF(pm.method_type_id = 2, i.amount, 0)) as bank_amount,
            GROUP_CONCAT(i.invoice_no ORDER BY i.invoice_no) as vn,
            ad.name as department_name
        ');
        $this->db->from('income i');
        $this->db->join('payment_methods pm', 'pm.id = i.payment_method_id', 'left');
        $this->db->join('income_head ih', 'ih.id = i.income_head_id', 'left');
        $this->db->join('account_departments ad', 'i.account_department_id = ad.id', 'left');
        // Gross income (refunded ones included here; the refund is shown on the payments side)
        $this->db->where('i.status', 1);
        $this->db->where('i.date >=', $date_from);
        $this->db->where('i.date <=', $date_to);
        $this->db->group_by(['i.income_head_id', 'i.date', 'i.payment_method_id', 'ad.id']);
        $other_income = $this->db->get()->result_array();

        // Step 5: Fetch Fund Transfer Transactions
        $this->db->select('
        DATE(transactions.trans_date) as collection_date,
        CONCAT(transactions.descriptions, " to<br> ", payment_methods.title) as particulars,
        "Contra" as head,
        IF(payment_methods.method_type_id = 1, transactions.amount, 0) as cash_amount,
        IF(payment_methods.method_type_id = 2, transactions.amount, 0) as bank_amount,
        "Contra" as department_name
        ');
        $this->db->from('transactions');
        $this->db->join('payment_methods', 'payment_methods.id = transactions.payment_method_id', 'left');
        $this->db->where('transactions.trans_type', 1); // Only Receipts
        $this->db->where('transactions.transaction_for_table', 'fund_transfers');
        $this->db->where('transactions.status', 1);
        $this->db->where('transactions.trans_date >=', $date_from);
        $this->db->where('transactions.trans_date <=', $date_to);
        $transactions = $this->db->get()->result_array();

        foreach ($transactions as &$row) {
            $row['vn'] = '-';
        }

        // ============================
        // 6. Staff Loan Repayment
        // ============================-
        $this->db->select('
            slp.payment_date as collection_date,
            "Staff Loan Repayment" as head,
            CONCAT("Loan Repayment - ", slp.note) as particulars,
            pm.title as payment_method,
            IF(pm.method_type_id = 1, slp.amount, 0) as cash_amount,
            IF(pm.method_type_id = 2, slp.amount, 0) as bank_amount,
            CONCAT("SLP", slp.staff_loan_id) as vn,
            ad.name as department_name
        ');
        $this->db->from('staff_loan_payments slp');
        $this->db->join('payment_methods pm', 'pm.id = slp.payment_method_id', 'left');
        $this->db->join('staff_loans sl', 'sl.id = slp.staff_loan_id', 'left');
        $this->db->join('staff s', 's.id = sl.staff_id', 'left');
        $this->db->join('account_departments ad', 's.account_department_id = ad.id', 'left');
        $this->db->where('slp.status', 1);
        $this->db->where('slp.payment_date >=', $date_from);
        $this->db->where('slp.payment_date <=', $date_to);
        $loan_repayments = $this->db->get()->result_array();


        // ✅ Merge All and Sort
        $all_data = array_merge($admission_fees, $student_fees, $other_income, $transactions, $loan_repayments);
        usort($all_data, function ($a, $b) {
            return strtotime($a['collection_date']) - strtotime($b['collection_date']);
        });

        return $all_data;
    }

    public function get_vn($date)
    {
        $this->db->select('payment_hash');
        $this->db->from('student_fees_collections');
        $this->db->where('DATE(approved_date)', $date);
        $this->db->where('status', 1);
        $this->db->where_in('feetype_id', [1, 2]);
        $this->db->order_by('payment_hash', 'asc');
        $hashes = array_column($this->db->get()->result_array(), 'payment_hash');

        if (empty($hashes)) return '-';

        $vn_groups = [];
        $start = $prev = $hashes[0];

        for ($i = 1; $i < count($hashes); $i++) {
            $current = $hashes[$i];
            if ((int)$current === (int)$prev + 1) {
                $prev = $current;
            } else {
                $vn_groups[] = $start == $prev ? $start : "$start - $prev";
                $start = $prev = $current;
            }
        }

        $vn_groups[] = $start == $prev ? $start : "$start - $prev";

        return implode(', ', $vn_groups);
    }

    public function get_all_payments($date_from, $date_to)
    {
        $payments = [];

        // ============================
        // 1. Staff Salary
        // ============================
        $this->db->select('
            sp.payment_date as transaction_date,
            SUM(sp.net_salary) as amount,
            pm.title as payment_method,
            "" as particulars,
            "Staff Salary" as head,
            SUM(CASE WHEN pm.method_type_id = 1 THEN sp.net_salary ELSE 0 END) as cash_amount,
            SUM(CASE WHEN pm.method_type_id = 2 THEN sp.net_salary ELSE 0 END) as bank_amount,
            GROUP_CONCAT(sp.id ORDER BY sp.id) as vn,
            ad.name as department_name
        ');
        $this->db->from('staff_payslip sp');
        $this->db->join('payment_methods pm', 'pm.id = sp.payment_mode', 'left');
        $this->db->join('staff s', 's.id = sp.staff_id', 'left');
        $this->db->join('account_departments ad', 's.account_department_id = ad.id', 'left');
        $this->db->where('sp.status', 'paid');
        $this->db->where('sp.payment_date >=', $date_from);
        $this->db->where('sp.payment_date <=', $date_to);
        $this->db->group_by('sp.payment_date, pm.title, ad.id');
        $staff_salary = $this->db->get()->result_array();

        foreach ($staff_salary as &$row) {
            $row['vn'] = $this->format_id_range($row['vn']);
        }

        // ============================
        // 2. Expenses
        // ============================
        $this->db->select('
        e.date as transaction_date,
        e.amount as amount,
        pm.title as payment_method,
        eh.exp_category as head,
        CONCAT(e.name, " (", e.invoice_no, ") - ", e.note) as particulars,
        IF(pm.method_type_id = 1, e.amount, 0) as cash_amount,
        IF(pm.method_type_id = 2, e.amount, 0) as bank_amount,
        e.invoice_no as vn,
        ad.name as department_name
        ');
        $this->db->from('expenses e');
        $this->db->join('payment_methods pm', 'pm.id = e.payment_method_id', 'left');
        $this->db->join('expense_head eh', 'eh.id = e.exp_head_id', 'left');
        $this->db->join('account_departments ad', 'e.account_department_id = ad.id', 'left');
        $this->db->where('e.is_refunded', 0);
        $this->db->where('e.status', 1);
        $this->db->where('e.date >=', $date_from);
        $this->db->where('e.date <=', $date_to);
        $other_expenses = $this->db->get()->result_array();

        // ============================
        // 3. Transactions (fund_transfers table reference)
        // ============================
        $this->db->select('
        DATE(transactions.trans_date) as transaction_date,
        CONCAT(transactions.descriptions, " from<br> ", SUBSTRING(payment_methods.title, 1, 20)) as particulars,
        "Contra" as head,
        transactions.amount as amount,
        payment_methods.title as payment_method,
        IF(payment_methods.method_type_id = 1, transactions.amount, 0) as cash_amount,
        IF(payment_methods.method_type_id = 2, transactions.amount, 0) as bank_amount,
        "-" as vn,
        "Contra" as department_name
        ');
        $this->db->from('transactions');
        $this->db->join('payment_methods', 'payment_methods.id = transactions.payment_method_id', 'left');
        $this->db->where('transactions.trans_type', 2); // Only Payments
        $this->db->where('transactions.transaction_for_table', 'fund_transfers');
        $this->db->where('transactions.status', 1);
        $this->db->where('transactions.trans_date >=', $date_from);
        $this->db->where('transactions.trans_date <=', $date_to);
        $transactions = $this->db->get()->result_array();

        // ============================
        // 4. Staff Loan Issued
        // ============================
        $this->db->select('
            sl.loan_date as transaction_date,
            sl.loan_amount as amount,
            pm.title as payment_method,
            CONCAT("Loan Issued - ", sl.description) as particulars,
            "Staff Loan" as head,
            IF(pm.method_type_id = 1, sl.loan_amount, 0) as cash_amount,
            IF(pm.method_type_id = 2, sl.loan_amount, 0) as bank_amount,
            CONCAT("SL", sl.id) as vn,
            ad.name as department_name
        ');
        $this->db->from('staff_loans sl');
        $this->db->join('payment_methods pm', 'pm.id = sl.payment_method_id', 'left');
        $this->db->join('staff s', 's.id = sl.staff_id', 'left');
        $this->db->join('account_departments ad', 's.account_department_id = ad.id', 'left');
        $this->db->where('sl.status', 1);
        $this->db->where('sl.loan_date >=', $date_from);
        $this->db->where('sl.loan_date <=', $date_to);
        $loan_issues = $this->db->get()->result_array();


        // ============================
        // 5. Income & Fees Refunds (cash/bank paid back out)
        // ============================
        $this->db->select('
            DATE(t.trans_date) as transaction_date,
            t.amount as amount,
            pm.title as payment_method,
            CASE
                WHEN t.transaction_for_table = "student_fees_collections" THEN "Fees Refund"
                WHEN t.transaction_for_table = "income" THEN "Income Refund"
                ELSE "Refund"
            END as head,
            t.descriptions as particulars,
            IF(pm.method_type_id = 1, t.amount, 0) as cash_amount,
            IF(pm.method_type_id = 2, t.amount, 0) as bank_amount,
            "-" as vn,
            "" as department_name
        ', false);
        $this->db->from('transactions t');
        $this->db->join('payment_methods pm', 'pm.id = t.payment_method_id', 'left');
        $this->db->where('t.trans_type', 2); // Debit (money out)
        $this->db->where_in('t.transaction_for_table', ['student_fees_collections', 'income']);
        $this->db->where('t.status', 1);
        $this->db->where('t.trans_date >=', $date_from);
        $this->db->where('t.trans_date <=', $date_to);
        $refunds = $this->db->get()->result_array();

        // ============================
        // 6. Merge & Sort
        // ============================
        $all_payments = array_merge($staff_salary, $other_expenses, $transactions, $loan_issues, $refunds);
        usort($all_payments, function ($a, $b) {
            return strtotime($a['transaction_date']) - strtotime($b['transaction_date']);
        });

        return $all_payments;
    }

    private function format_id_range($id_string)
    {
        $ids = array_map('intval', explode(',', $id_string));
        sort($ids);

        $ranges = [];
        $start = $end = null;

        foreach ($ids as $i => $id) {
            if ($start === null) {
                $start = $end = $id;
            } elseif ($id == $end + 1) {
                $end = $id;
            } else {
                $ranges[] = ($start == $end) ? $start : "$start - $end";
                $start = $end = $id;
            }
        }

        if ($start !== null) {
            $ranges[] = ($start == $end) ? $start : "$start - $end";
        }

        return implode(', ', $ranges);
    }


    /** End of Cashbook methods */
    /** Ledger */

    /* public function getHeadEntries($date_from, $date_to, $head_id = null)
    {
        $this->db->select('
        expenses.date as transaction_date,
        SUM(expenses.amount) as total_amount,
        payment_methods.title as payment_method,
        expense_head.exp_category as head,
        GROUP_CONCAT(CONCAT(expenses.name, " (", expenses.invoice_no, ") - ", expenses.note) SEPARATOR "<br>") as particulars,
        SUM(IF(payment_methods.method_type_id = 1, expenses.amount, 0)) as cash_amount,
        SUM(IF(payment_methods.method_type_id = 2, expenses.amount, 0)) as bank_amount
        ');

        $this->db->from('expenses');
        $this->db->join('payment_methods', 'payment_methods.id = expenses.payment_method_id', 'left');
        $this->db->join('expense_head', 'expense_head.id = expenses.exp_head_id', 'left');

        $this->db->where('expenses.is_refunded', 0);
        $this->db->where('expenses.status', 1);
        $this->db->where('expenses.date >=', $date_from);
        $this->db->where('expenses.date <=', $date_to);

        if ($head_id === 'staff_loan') {
            // Custom query for Staff Loan ledger
            $this->db->reset_query(); // clear previous query chain
            $this->db->select('
                staff_loans.loan_date as transaction_date,
                staff_loans.loan_amount as total_amount,
                payment_methods.title as payment_method,
                "Staff Loan" as head,
                CONCAT(staff.name, " (", staff.id, ") - ", staff_loans.description) as particulars,
                IF(payment_methods.method_type_id = 1, staff_loans.loan_amount, 0) as cash_amount,
                IF(payment_methods.method_type_id = 2, staff_loans.loan_amount, 0) as bank_amount
            ');
            $this->db->from('staff_loans');
            $this->db->join('payment_methods', 'payment_methods.id = staff_loans.payment_method_id', 'left');
            $this->db->join('staff', 'staff.id = staff_loans.staff_id', 'left');
            $this->db->where('staff_loans.status', 1);
            $this->db->where('staff_loans.loan_date >=', $date_from);
            $this->db->where('staff_loans.loan_date <=', $date_to);

            return $this->db->get()->result_array();
        } else {
            // Default: expense head
            $this->db->where('expenses.is_refunded', 0);
            $this->db->where('expenses.status', 1);
            $this->db->where('expenses.date >=', $date_from);
            $this->db->where('expenses.date <=', $date_to);

            if (!empty($head_id)) {
                $this->db->where('expenses.exp_head_id', $head_id);
            }

            $this->db->group_by(['expenses.exp_head_id', 'expenses.date', 'expenses.payment_method_id']);

            return $this->db->get()->result_array();
        }
    } */

    public function getHeadEntries($date_from, $date_to, $head_id = null, $department_id = null) // Modified
    {
        $results = [];

        // ========================
        // CASE 1: Staff Loan Only
        // ========================
        if ($head_id === 'staff_loan') {
            $this->db->reset_query();
            $this->db->select('
                staff_loans.loan_date as transaction_date,
                staff_loans.loan_amount as total_amount,
                payment_methods.title as payment_method,
                "Staff Loan" as head,
                CONCAT(staff.name, " (", staff.id, ") - ", staff_loans.description) as particulars,
                IF(payment_methods.method_type_id = 1, staff_loans.loan_amount, 0) as cash_amount,
                IF(payment_methods.method_type_id = 2, staff_loans.loan_amount, 0) as bank_amount,
                ad.name as department_name
            ', false);
            $this->db->from('staff_loans');
            $this->db->join('payment_methods', 'payment_methods.id = staff_loans.payment_method_id', 'left');
            $this->db->join('staff', 'staff.id = staff_loans.staff_id', 'left');
            $this->db->join('account_departments ad', 'staff.account_department_id = ad.id', 'left');
            $this->db->where('staff_loans.status', 1);
            $this->db->where('staff_loans.loan_date >=', $date_from);
            $this->db->where('staff_loans.loan_date <=', $date_to);
            if ($department_id) { // New
                $this->db->where('staff.account_department_id', $department_id);
            }

            return $this->db->get()->result_array();
        }

        // ========================
        // CASE 2: Expenses (All or filtered by head) — individual entries, no grouping
        // ========================
        $this->db->select('
            expenses.date as transaction_date,
            expenses.amount as total_amount,
            payment_methods.title as payment_method,
            expense_head.exp_category as head,
            CONCAT(expenses.name, IF(IFNULL(expenses.invoice_no, "") != "", CONCAT(" (", expenses.invoice_no, ")"), ""), IF(IFNULL(expenses.note, "") != "", CONCAT(" - ", expenses.note), "")) as particulars,
            IF(payment_methods.method_type_id = 1, expenses.amount, 0) as cash_amount,
            IF(payment_methods.method_type_id = 2, expenses.amount, 0) as bank_amount,
            ad.name as department_name
        ', false);
        $this->db->from('expenses');
        $this->db->join('payment_methods', 'payment_methods.id = expenses.payment_method_id', 'left');
        $this->db->join('expense_head', 'expense_head.id = expenses.exp_head_id', 'left');
        $this->db->join('account_departments ad', 'expenses.account_department_id = ad.id', 'left');
        $this->db->where('expenses.is_refunded', 0);
        $this->db->where('expenses.status', 1);
        $this->db->where('expenses.date >=', $date_from);
        $this->db->where('expenses.date <=', $date_to);

        if (!empty($head_id)) {
            $this->db->where('expenses.exp_head_id', $head_id);
        }
        if ($department_id) {
            $this->db->where('expenses.account_department_id', $department_id);
        }

        $this->db->order_by('expenses.date ASC, expenses.exp_head_id ASC');
        $expense_results = $this->db->get()->result_array();

        // ========================
        // CASE 3: Append Staff Loans if head_id is empty (all heads)
        // ========================
        if (empty($head_id)) {
            $this->db->reset_query();
            $this->db->select('
                staff_loans.loan_date as transaction_date,
                staff_loans.loan_amount as total_amount,
                payment_methods.title as payment_method,
                "Staff Loan" as head,
                CONCAT(staff.name, " (", staff.id, ") - ", staff_loans.description) as particulars,
                IF(payment_methods.method_type_id = 1, staff_loans.loan_amount, 0) as cash_amount,
                IF(payment_methods.method_type_id = 2, staff_loans.loan_amount, 0) as bank_amount,
                ad.name as department_name
            ', false);
            $this->db->from('staff_loans');
            $this->db->join('payment_methods', 'payment_methods.id = staff_loans.payment_method_id', 'left');
            $this->db->join('staff', 'staff.id = staff_loans.staff_id', 'left');
            $this->db->join('account_departments ad', 'staff.account_department_id = ad.id', 'left');
            $this->db->where('staff_loans.status', 1);
            $this->db->where('staff_loans.loan_date >=', $date_from);
            $this->db->where('staff_loans.loan_date <=', $date_to);
            if ($department_id) { // New
                $this->db->where('staff.account_department_id', $department_id);
            }

            $staff_loan_results = $this->db->get()->result_array();

            // Merge both
            $results = array_merge($expense_results, $staff_loan_results);
        } else {
            $results = $expense_results;
        }

        // ========================
        // Sort by Date
        // ========================
        usort($results, function ($a, $b) {
            return strtotime($a['transaction_date']) - strtotime($b['transaction_date']);
        });

        return $results;
    }


    public function getLedgerEntries($date_from, $date_to, $supplier_id = null)
    {
        // ============================
        // 1. Get Opening Balance (Before 'From Date')
        // ============================
        $opening_balance_query = $this->db->query(
            "
        SELECT 
            SUM(e.amount) - SUM(p.amount) AS opening_balance
        FROM suppliers s
        LEFT JOIN expenses e ON e.supplier_id = s.id AND e.date < ?
        LEFT JOIN purchase_order p ON p.supplier_id = s.id AND p.date < ?
        WHERE s.id = ?",
            [$date_from, $date_from, $supplier_id]
        );

        $opening_balance = $opening_balance_query->row()->opening_balance ?? 0;

        // ============================
        // 2. Get Purchase Orders (Cr) - Grouped by Date
        // ============================
        $this->db->select('
        p.date AS transaction_date,
        p.amount AS amount,
        CONCAT("By Purchase - ", s.name) AS particulars,
        "" AS invoice_no,
        CAST(NULL AS DECIMAL(10,2)) AS dr,
        p.amount AS cr');
        $this->db->from('purchase_order p');
        $this->db->join('suppliers s', 'p.supplier_id = s.id', 'left');
        $this->db->where('p.supplier_id !=', 0);
        $this->db->where('p.date >=', $date_from);
        $this->db->where('p.date <=', $date_to);

        if (!empty($supplier_id)) {
            $this->db->where('p.supplier_id', $supplier_id);
        }

        $purchase_orders = $this->db->get()->result_array();

        // ============================
        // 3. Get Expenses (Dr) - Grouped by Date
        // ============================
        $this->db->select('
        e.date AS transaction_date,
        e.amount AS amount,
        CONCAT("To ", pm.title) AS particulars,
        e.invoice_no AS invoice_no,
        e.amount AS dr,
        CAST(NULL AS DECIMAL(10,2)) AS cr');
        $this->db->from('expenses e');
        $this->db->join('payment_methods pm', 'e.payment_method_id = pm.id', 'left');
        $this->db->where('e.supplier_id !=', 0);
        $this->db->where('e.date >=', $date_from);
        $this->db->where('e.date <=', $date_to);
        $this->db->where('e.is_refunded', 0);
        $this->db->where('e.status', 1);

        if (!empty($supplier_id)) {
            $this->db->where('e.supplier_id', $supplier_id);
        }

        $expenses = $this->db->get()->result_array();

        // ============================
        // 4. Merge Both Arrays
        // ============================
        $all_transactions = array_merge($purchase_orders, $expenses);

        // ============================
        // 5. Sort by Date
        // ============================
        usort($all_transactions, function ($a, $b) {
            return strtotime($a['transaction_date']) - strtotime($b['transaction_date']);
        });

        // ============================
        // 6. Group by Date
        // ============================
        $grouped_transactions = [];
        foreach ($all_transactions as $transaction) {
            $date = $transaction['transaction_date'];
            $grouped_transactions[$date][] = $transaction;
        }

        // ============================
        // 7. Return Opening Balance & Grouped Results
        // ============================
        return [
            'opening_balance' => $opening_balance,
            'ledger_entries'  => $grouped_transactions
        ];
    }
    /** End of Ledger */

    public function get_fix_deposites()
    {
        return $this->db->get('fix_deposites')->result_array();
    }

    public function get_fix_deposite_by_id($id)
    {
        return $this->db->where('id', $id)->get('fix_deposites')->row_array();
    }

    public function add_fix_deposite($data)
    {
        return $this->db->insert('fix_deposites', $data);
    }

    public function update_fix_deposite($id, $data)
    {
        return $this->db->where('id', $id)->update('fix_deposites', $data);
    }

    public function delete_fix_deposite($id)
    {
        return $this->db->where('id', $id)->delete('fix_deposites');
    }

    public function get_assets()
    {
        $this->db->select('assets.*, sessions.session');
        $this->db->from('assets');
        $this->db->join('sessions', 'sessions.id = assets.session_id', 'left');
        return $this->db->get()->result_array();
    }

    public function get_asset_by_id($id)
    {
        $this->db->select('assets.*, sessions.session');
        $this->db->from('assets');
        $this->db->join('sessions', 'sessions.id = assets.session_id', 'left');
        $this->db->where('assets.id', $id);
        return $this->db->get()->row();
    }

    public function add_asset($data)
    {
        $this->db->insert('assets', $data);
    }

    public function update_asset($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update('assets', $data);
    }

    public function delete_asset($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('assets');
    }

        public function getAllFundTransfers($from_method = null, $to_method = null, $start_date = null, $end_date = null)
        {
            $this->db->select('ft.*,
            pm1.title as from_method,
            pm2.title as to_method
            ');
            $this->db->from('fund_transfers ft');
            $this->db->join('payment_methods pm1', 'pm1.id = ft.from_payment_method_id', 'left');
            $this->db->join('payment_methods pm2', 'pm2.id = ft.to_payment_method_id', 'left');
    
            if ($from_method) {
                $this->db->where('ft.from_payment_method_id', $from_method);
            }
    
            if ($to_method) {
                $this->db->where('ft.to_payment_method_id', $to_method);
            }
    
            if ($start_date && $end_date) {
                $this->db->where('ft.transfer_date >=', $start_date);
                $this->db->where('ft.transfer_date <=', $end_date);
            }
    
            $this->db->order_by('ft.transfer_date', 'DESC');
            return $this->db->get()->result_array();
        }
    public function getFundTransferById($id)
    {
        $this->db->select('*')
            ->from('fund_transfers')
            ->where('id', $id);

        return $this->db->get()->row_array();
    }

    public function get_grouped_income_by_department($date_from, $date_to, $department_id = null)
    {
        $this->db->select("
        ig.id as group_id, 
        ig.title as group_title, 
        ih.income_category, 
        SUM(CASE WHEN t.trans_type = 1 THEN t.amount ELSE 0 END) - 
        SUM(CASE WHEN t.trans_type = 2 THEN t.amount ELSE 0 END) as total_amount");

        $this->db->from('transactions t');
        $this->db->join('income i', 't.table_id = i.id', 'left');
        $this->db->join('income_head ih', 'i.income_head_id = ih.id');
        $this->db->join('income_groups ig', 'ih.group_id = ig.id');

        // Apply conditions for the transactions table
        $this->db->where('t.transaction_for_table', 'income');
        $this->db->where('t.trans_date >=', $date_from);
        $this->db->where('t.trans_date <=', $date_to);
        if ($department_id) {
            $this->db->where('i.account_department_id', $department_id);
        }

        // Group by and order by
        $this->db->group_by(['ih.group_id', 'i.income_head_id']);
        $this->db->order_by('ig.display_order, ih.income_category');

        return $this->db->get()->result_array();
    }

    public function get_grouped_expenses_by_department($date_from, $date_to, $department_id = null)
    {
        $this->db->select("
        eg.id as group_id, 
        eg.title as group_title, 
        eh.exp_category, 
        SUM(CASE WHEN t.trans_type = 2 THEN t.amount ELSE 0 END) - 
        SUM(CASE WHEN t.trans_type = 1 THEN t.amount ELSE 0 END) as total_amount");

        $this->db->from('transactions t');
        $this->db->join('expenses e', 't.table_id = e.id', 'left');
        $this->db->join('expense_head eh', 'e.exp_head_id = eh.id', 'left');
        $this->db->join('expense_groups eg', 'eh.group_id = eg.id', 'left');

        // Apply conditions for the transactions table
        $this->db->where('t.transaction_for_table', 'expenses');
        $this->db->where('t.trans_date >=', $date_from);
        $this->db->where('t.trans_date <=', $date_to);
        if ($department_id) {
            $this->db->where('e.account_department_id', $department_id);
        }

        // Group by and order by
        $this->db->group_by(['eh.group_id', 'e.exp_head_id']);
        $this->db->order_by('eg.title, eh.exp_category');

        return $this->db->get()->result_array();
    }

    public function get_student_fees_total_by_department($is_admission, $date_from, $date_to, $department_id = null)
    {
        $this->db->select('SUM(t.amount) as total_paid_amount');
        $this->db->from('transactions t');
        $this->db->join('student_fees_collections sfc', 't.table_id = sfc.id', 'left');
        $this->db->join('student_session ss', 'sfc.student_id = ss.student_id AND `sfc`.`session_id` = `ss`.`session_id`', 'left');

        // Conditions for transactions table
        $this->db->where('t.transaction_for_table', 'student_fees_collections');
        $this->db->where('t.trans_type', 1); // Assuming 1 is for fees collection
        $this->db->where('t.status', 1); // Assuming 1 is for fees collection

        // Conditions for student fees collections
        $this->db->where('sfc.is_refunded', 0);
        if ($is_admission) {
            $this->db->where('sfc.feetype_id', 1); // Admission fees
        } else {
            $this->db->where('sfc.feetype_id !=', 1); // Non-admission fees
        }
        $this->db->where('t.trans_date >=', $date_from);
        $this->db->where('t.trans_date <=', $date_to);
        if ($department_id) {
            $this->db->where('ss.account_department_id', $department_id);
        }

        // Execute query and return the result
        $query = $this->db->get();
        return $query->row()->total_paid_amount ?? 0;
    }

    public function get_payroll_total_by_department($date_from, $date_to, $department_id = null)
    {
        $this->db->select_sum('amount');
        $this->db->from('transactions t');
        $this->db->join('staff_payslip sp', 't.table_id = sp.id', 'left');
        $this->db->join('staff s', 'sp.staff_id = s.id', 'left');
        $this->db->where('t.trans_type', 2);
        $this->db->where('t.transaction_for_table', 'staff_payslip');
        $this->db->where('t.status', 1);
        $this->db->where('t.trans_date >=', $date_from);
        $this->db->where('t.trans_date <=', $date_to);
        if ($department_id) {
            $this->db->where('s.account_department_id', $department_id);
        }
        $query = $this->db->get();
        $total_paid_sal = $query->row()->amount ?? 0;

        return $total_paid_sal;
    }

    public function get_refunded_payroll_by_department($date_from, $date_to, $department_id = null)
    {
        $this->db->select_sum('amount');
        $this->db->from('transactions t');
        $this->db->join('staff_payslip sp', 't.table_id = sp.id', 'left');
        $this->db->join('staff s', 'sp.staff_id = s.id', 'left');
        $this->db->where('t.trans_type', 1);
        $this->db->where('t.transaction_for_table', 'staff_payslip');
        $this->db->where('t.status', 1);
        $this->db->where('t.trans_date >=', $date_from);
        $this->db->where('t.trans_date <=', $date_to);
        if ($department_id) {
            $this->db->where('s.account_department_id', $department_id);
        }
        $query = $this->db->get();
        $total_return_sal = $query->row()->amount ?? 0;

        return $total_return_sal;
    }
}
