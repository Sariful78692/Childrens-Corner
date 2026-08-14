<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Migrate_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
        // Your own constructor code
    }

    public function migrate_expenses()
    {
        $return_arr = [];
        $this->db->select('*');
        $query = $this->db->get('demo_malancha.expenses');
        $expenses = $query->result();
        $exp_count = 0;
        $trns_count = 0;
        foreach ($expenses as $expense) {
            // Prepare data for malancha.expense table
            $expense_data = array(
                'exp_head_id' => $expense->exp_head_id,
                'name' => $expense->name,
                'invoice_no' => $expense->invoice_no,
                'date' => $expense->date,
                'amount' => $expense->amount,
                'payment_method_id' => $expense->payment_method_id,
                'documents' => $expense->documents,
                'note' => $expense->note,
                'created_by' => $expense->created_by,
                'is_refunded' => $expense->is_refunded,
                'refund_date' => $expense->refund_date,
                'refund_note' => $expense->refund_note,
                'is_active' => $expense->is_active,
                'is_deleted' => $expense->is_deleted,
                'created_at' => $expense->created_at,
                'updated_at' => $expense->updated_at,
                'status' => $expense->status
            );

            // Insert into malancha.expense

            if ($this->db->insert('malancha.expenses', $expense_data)) {
                $exp_count++;
                $expense_id = $this->db->insert_id();
            } else {
                $return_arr[] = array('exp_count' => $exp_count, 'trns_count' => $trns_count);
                return $return_arr;
            }

            // Prepare data for malancha.transactions table
            $transaction_data = array(
                'amount' => $expense->amount,
                'trans_type' => 2,
                'payment_method_id ' => $expense->payment_method_id,
                'descriptions ' => $expense->note,
                'transaction_for_table ' => 'expenses',
                'table_id ' => $expense_id,
                'trans_by ' => $expense->created_by,
                'trans_date ' => $expense->date,
            );

            // Insert into malancha.transactions
            if ($this->db->insert('malancha.transactions', $transaction_data)) {
                $trns_count++;
            } else {
                $return_arr[] = array('exp_count' => $exp_count, 'trns_count' => $trns_count);
                return $return_arr;
            }

            if ($expense->is_refunded == 1) {
                $transaction__rfund_data = array(
                    'amount' => $expense->amount,
                    'trans_type' => 1,
                    'payment_method_id ' => $expense->payment_method_id,
                    'descriptions ' => 'Refund: ' . $expense->note,
                    'transaction_for_table ' => 'expenses',
                    'table_id ' => $expense_id,
                    'trans_by ' => $expense->created_by,
                    'trans_date ' => $expense->date,
                );
                // Insert into malancha.transactions
                if ($this->db->insert('malancha.transactions', $transaction__rfund_data)) {
                    $trns_count++;
                } else {
                    $return_arr[] = array('exp_count' => $exp_count, 'trns_count' => $trns_count);
                    return $return_arr;
                }
            }
        }
        return $return_arr;
    }

    public function migrate_income()
    {
        $return_arr = [];
        $this->db->select('*');
        $query = $this->db->get('demo_malancha.income');
        $expenses = $query->result();
        $exp_count = 0;
        $trns_count = 0;
        foreach ($expenses as $expense) {
            // Prepare data for malancha.expense table
            $expense_data = array(
                'income_head_id' => $expense->income_head_id,
                'name' => $expense->name,
                'invoice_no' => $expense->invoice_no,
                'date' => $expense->date,
                'amount' => $expense->amount,
                'payment_method_id' => $expense->payment_method_id,
                'documents' => $expense->documents,
                'note' => $expense->note,
                'created_by' => $expense->created_by,
                'is_refunded' => $expense->is_refunded,
                'refund_date' => $expense->refund_date,
                'refund_note' => $expense->refund_note,
                'is_active' => $expense->is_active,
                'is_deleted' => $expense->is_deleted,
                'created_at' => $expense->created_at,
                'updated_at' => $expense->updated_at,
                'status' => $expense->status
            );

            // Insert into malancha.expense

            if ($this->db->insert('malancha.income', $expense_data)) {
                $exp_count++;
                $expense_id = $this->db->insert_id();
            } else {
                $return_arr = array('inc_count' => $exp_count, 'trns_count' => $trns_count);
                return $return_arr;
            }

            // Prepare data for malancha.transactions table
            $transaction_data = array(
                'amount' => $expense->amount,
                'trans_type' => 1,
                'payment_method_id ' => $expense->payment_method_id,
                'descriptions ' => $expense->note,
                'transaction_for_table ' => 'income',
                'table_id ' => $expense_id,
                'trans_by ' => $expense->created_by,
                'trans_date ' => $expense->date,
            );

            // Insert into malancha.transactions
            if ($this->db->insert('malancha.transactions', $transaction_data)) {
                $trns_count++;
            } else {
                $return_arr = array('inc_count' => $exp_count, 'trns_count' => $trns_count);
                return $return_arr;
            }

            if ($expense->is_refunded == 1) {
                $transaction__rfund_data = array(
                    'amount' => $expense->amount,
                    'trans_type' => 2,
                    'payment_method_id ' => $expense->payment_method_id,
                    'descriptions ' => 'Refund: ' . $expense->note,
                    'transaction_for_table ' => 'income',
                    'table_id ' => $expense_id,
                    'trans_by ' => $expense->created_by,
                    'trans_date ' => $expense->date,
                );
                // Insert into malancha.transactions
                if ($this->db->insert('malancha.transactions', $transaction__rfund_data)) {
                    $trns_count++;
                } else {
                    $return_arr = array('inc_count' => $exp_count, 'trns_count' => $trns_count);
                    return $return_arr;
                }
            }
        }
        return $return_arr;
    }

    public function migrate_fund_transfers()
    {
        $return_arr = [];
        $this->db->select('*');
        $query = $this->db->get('demo_malancha.fund_transfers');
        $expenses = $query->result();
        $exp_count = 0;
        $trns_count = 0;
        foreach ($expenses as $expense) {
            // Prepare data for malancha.expense table
            $expense_data = array(
                'from_payment_method_id' => $expense->from_payment_method_id,
                'to_payment_method_id' => $expense->to_payment_method_id,
                'amount' => $expense->amount,
                'transfer_date' => $expense->transfer_date,
                'description' => $expense->description,
                'created_by' => $expense->created_by,
                'created_at' => $expense->created_at,
                'updated_at' => $expense->updated_at,
            );

            // Insert into malancha.fund_transfers

            if ($this->db->insert('malancha.fund_transfers', $expense_data)) {
                $exp_count++;
                $expense_id = $this->db->insert_id();
            } else {
                $return_arr = array('ft_count' => $exp_count, 'trns_count' => $trns_count);
                return $return_arr;
            }

            // Prepare data for malancha.transactions table
            $transaction_data = array(
                'amount' => $expense->amount,
                'trans_type' => 2,
                'payment_method_id ' => $expense->from_payment_method_id,
                'descriptions ' => $expense->description,
                'transaction_for_table ' => 'fund_transfers',
                'table_id ' => $expense_id,
                'trans_by ' => $expense->created_by,
                'trans_date ' => $expense->transfer_date
            );

            // Insert into malancha.transactions
            if ($this->db->insert('malancha.transactions', $transaction_data)) {
                $trns_count++;
            } else {
                $return_arr = array('ft_count' => $exp_count, 'trns_count' => $trns_count);
                return $return_arr;
            }

            $transaction__rfund_data = array(
                'amount' => $expense->amount,
                'trans_type' => 1,
                'payment_method_id ' => $expense->to_payment_method_id,
                'descriptions ' => $expense->description,
                'transaction_for_table ' => 'fund_transfers',
                'table_id ' => $expense_id,
                'trans_by ' => $expense->created_by,
                'trans_date ' => $expense->transfer_date
            );
            // Insert into malancha.transactions
            if ($this->db->insert('malancha.transactions', $transaction__rfund_data)) {
                $trns_count++;
            } else {
                $return_arr = array('ft_count' => $exp_count, 'trns_count' => $trns_count);
                return $return_arr;
            }
        }
        return $return_arr;
    }

    public function migrate_staff_payroll()
    {
        $return_arr = [];
        $this->db->select('*');
        $query = $this->db->get('demo_malancha.staff_payslip');
        $expenses = $query->result();
        $exp_count = 0;
        $trns_count = 0;
        foreach ($expenses as $expense) {
            // Prepare data for malancha.expense table
            $expense_data = array(
                'staff_id' => $expense->staff_id,
                'basic' => $expense->basic,
                'total_allowance' => $expense->total_allowance,
                'total_deduction' => $expense->total_deduction,
                'leave_deduction' => $expense->leave_deduction,
                'tax' => $expense->tax,
                'net_salary' => $expense->net_salary,
                'status' => $expense->status,
                'month' => $expense->month,
                'year' => $expense->year,
                'payment_mode' => $expense->payment_mode,
                'payment_date' => $expense->payment_date,
                'remark' => $expense->remark,
                'generated_by' => $expense->generated_by,
                'paid_by' => $expense->paid_by,
                'reverted_by' => $expense->reverted_by,
                'reverted_date' => $expense->reverted_date,
                'created_by' => $expense->created_by,
                'created_at' => $expense->created_at
            );

            // Insert into malancha.staff_payslip

            if ($this->db->insert('malancha.staff_payslip', $expense_data)) {
                $exp_count++;
                $expense_id = $this->db->insert_id();
            } else {
                $return_arr[] = array('ft_count' => $exp_count, 'trns_count' => $trns_count);
                return $return_arr;
            }

            if ($expense->status == 'generated') {
                continue;
            }

            // Prepare data for malancha.transactions table
            $transaction_data = array(
                'amount' => $expense->net_salary,
                'trans_type' => 2,
                'payment_method_id ' => $expense->payment_mode,
                'descriptions ' => "Salary for $expense->month , $expense->year , Staff ID: " . $expense->staff_id,
                'transaction_for_table ' => 'staff_payslip',
                'table_id ' => $expense_id,
                'trans_by ' => $expense->created_by,
                'trans_date ' => $expense->payment_date
            );

            // Insert into malancha.transactions
            if ($this->db->insert('malancha.transactions', $transaction_data)) {
                $trns_count++;
            } else {
                $return_arr[] = array('ft_count' => $exp_count, 'trns_count' => $trns_count);
                return $return_arr;
            }

            if ($expense->reverted_by != '') {

                $transaction__rfund_data = array(
                    'amount' => $expense->net_salary,
                    'trans_type' => 1,
                    'payment_method_id ' => $expense->payment_mode,
                    'descriptions ' => "Refund: Salary for $expense->month , $expense->year , Staff ID: " . $expense->staff_id,
                    'transaction_for_table ' => 'staff_payslip',
                    'table_id ' => $expense_id,
                    'trans_by ' => $expense->created_by,
                    'trans_date ' => $expense->payment_date
                );
                // Insert into malancha.transactions
                if ($this->db->insert('malancha.transactions', $transaction__rfund_data)) {
                    $trns_count++;
                } else {
                    $return_arr[] = array('ft_count' => $exp_count, 'trns_count' => $trns_count);
                    return $return_arr;
                }

                if ($expense->reverted_by != '' && $expense->status == 'paid') {
                    $transaction_data_reentry = array(
                        'amount' => $expense->net_salary,
                        'trans_type' => 2,
                        'payment_method_id ' => $expense->payment_mode,
                        'descriptions ' => "Salary for $expense->month , $expense->year , Staff ID: " . $expense->staff_id,
                        'transaction_for_table ' => 'staff_payslip',
                        'table_id ' => $expense_id,
                        'trans_by ' => $expense->created_by,
                        'trans_date ' => $expense->payment_date
                    );

                    // Insert into malancha.transactions
                    if ($this->db->insert('malancha.transactions', $transaction_data_reentry)) {
                        $trns_count++;
                    } else {
                        $return_arr[] = array('ft_count' => $exp_count, 'trns_count' => $trns_count);
                        return $return_arr;
                    }
                }
            }
        }
        return $return_arr;
    }
}
