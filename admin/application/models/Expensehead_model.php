<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Expensehead_model extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function getDatatableExpenseGroup()
    {
        $sql = "SELECT * FROM expense_groups";
        $this->datatables->query($sql)
            ->searchable('title, descriptions')
            ->orderable('title')
            ->sort('id', 'asc');
        return $this->datatables->generate('json');
    }

    public function getDatatableExpenseHead()
    {
        $sql = "SELECT expense_head.*, expense_groups.title AS group_title FROM `expense_head` LEFT JOIN expense_groups ON expense_head.group_id = expense_groups.id";
        $this->datatables->query($sql)
            ->searchable('expense_head.exp_category, expense_groups.title') // Add searchable columns
            ->orderable('expense_head.id, expense_head.exp_category, expense_groups.title') // Add orderable columns
            ->sort('expense_head.id', 'asc');
        return $this->datatables->generate('json');
    }

    public function get($id = null)
    {
        $this->db->select('expense_head.*, expense_groups.title as group_title')
            ->from('expense_head')
            ->join('expense_groups', 'expense_groups.id = expense_head.group_id', 'left');

        if ($id != null) {
            $this->db->where('expense_head.id', $id);
        } else {
            $this->db->order_by('expense_head.id');
        }

        $query = $this->db->get();

        if ($id != null) {
            return $query->row_array();
        } else {
            return $query->result_array();
        }
    }


    public function getExpenseGroups($id = null)
    {
        $this->db->select()->from('expense_groups');
        if ($id != null) {
            $this->db->where('id', $id);
        } else {
            $this->db->order_by('id');
        }
        $query = $this->db->get();
        if ($id != null) {
            return $query->row_array();
        } else {
            return $query->result_array();
        }
    }

    /**
     * This function will delete the record based on the id
     * @param $id
     */
    public function remove($id)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->where('id', $id);
        $this->db->delete('expense_head');
        $message   = DELETE_RECORD_CONSTANT . " On  expense head id " . $id;
        $action    = "Delete";
        $record_id = $id;
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
    }

    /**
     * This function will take the post data passed from the controller
     * If id is present, then it will do an update
     * else an insert. One function doing both add and edit.
     * @param $data
     */
    public function add($data)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        if (isset($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('expense_head', $data);
            $message   = UPDATE_RECORD_CONSTANT . " On  expense head id " . $data['id'];
            $action    = "Update";
            $record_id = $data['id'];
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
        } else {
            $this->db->insert('expense_head', $data);
            $id        = $this->db->insert_id();
            $message   = INSERT_RECORD_CONSTANT . " On  expense head id " . $id;
            $action    = "Insert";
            $record_id = $id;
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
        }
    }

    public function addGroup($data)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        if (isset($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('expense_groups', $data);
            $message   = UPDATE_RECORD_CONSTANT . " On  expense group id " . $data['id'];
            $action    = "Update";
            $record_id = $data['id'];
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
        } else {
            $this->db->insert('expense_groups', $data);
            $id        = $this->db->insert_id();
            $message   = INSERT_RECORD_CONSTANT . " On  expense group id " . $id;
            $action    = "Insert";
            $record_id = $id;
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
        }
    }

    public function getGroupById($id)
    {
        return $this->db->get_where('expense_groups', array('id' => $id))->row_array();
    }

    public function deleteGroup($id)
    {
        return $this->db->delete('expense_groups', array('id' => $id));
    }

    public function searchexpensegroup($start_date, $end_date, $head_id = null, $payment_mode_id = null, $collected_by = null, $account_department_id = null)
    {
        $this->datatables
            ->select('expenses.id,expenses.date,expenses.name,expenses.note,expenses.invoice_no,expenses.amount, expense_head.exp_category,expenses.exp_head_id,expenses.amount as total_amount,expenses.is_refunded, staff.name as made_by, payment_methods.title as mode')
            ->searchable('expense_head.exp_category,expenses.id,expenses.name,expenses.note,expenses.date,expenses.invoice_no,expenses.amount')
            ->orderable('expense_head.exp_category,expenses.id,expenses.name,expenses.date,expenses.invoice_no')
            ->join('expense_head', 'expenses.exp_head_id = expense_head.id')
            ->join('staff', 'expenses.created_by = staff.id')
            ->join('payment_methods', 'expenses.payment_method_id = payment_methods.id')
            ->where('expenses.date >=', $start_date)
            ->where('expenses.date <=', $end_date)
            ->from('expenses');
        if ($payment_mode_id != null) {
            $this->datatables->where('expenses.payment_method_id', $payment_mode_id);
        }
        if ($collected_by != null) {
            $this->datatables->where('expenses.created_by', $collected_by);
        }
        if ($head_id != null) {
            $this->datatables->where('expenses.exp_head_id', $head_id);
        }
        if ($account_department_id != null) {
            $this->datatables->where('expenses.account_department_id', $account_department_id);
        }
        $this->datatables->sort('expenses.exp_head_id', 'desc');
        return $this->datatables->generate('json');
    }
}
