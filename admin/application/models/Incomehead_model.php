<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Incomehead_model extends My_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * This funtion takes id as a parameter and will fetch the record.
     * If id is not provided, then it will fetch all the records form the table.
     * @param int $id
     * @return mixed
     */
    public function get($id = null)
    {
        $this->db->select('income_head.*, income_groups.title as group_title')
            ->from('income_head')
            ->join('income_groups', 'income_groups.id = income_head.group_id', 'left');

        if ($id != null) {
            $this->db->where('income_head.id', $id);
        } else {
            $this->db->order_by('income_head.id');
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
        $this->db->delete('income_head');

        $message   = DELETE_RECORD_CONSTANT . " On  income head   id " . $id;
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
            return $id;
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
            $this->db->update('income_head', $data);
            $message   = UPDATE_RECORD_CONSTANT . " On  income head   id " . $data['id'];
            $action    = "Update";
            $record_id = $return_value = $data['id'];
        } else {
            $this->db->insert('income_head', $data);
            $return_value = $this->db->insert_id();
            $message      = INSERT_RECORD_CONSTANT . " On  income head   id " . $return_value;
            $action       = "Insert";
            $record_id    = $return_value;
        }
        $this->log($message, $record_id, $action);
        //======================Code End==============================
        $this->db->trans_complete(); # Completing transaction
        /* Optional */
        if ($this->db->trans_status() === false) {
            # Something went wrong.
            $this->db->trans_rollback();
            return false;
        } else {
            return $return_value;
        }
    }

    public function getDatatableIncomeGroup()
    {
        $sql = "SELECT * FROM income_groups";
        $this->datatables->query($sql)
            ->searchable('title, descriptions')
            ->orderable('title')
            ->sort('id', 'asc');
        return $this->datatables->generate('json');
    }

    public function getDatatableIncomeHead()
    {
        $sql = "SELECT income_head.*, income_groups.title AS group_title FROM `income_head` LEFT JOIN income_groups ON income_head.group_id = income_groups.id";
        $this->datatables->query($sql)
            ->searchable('income_head.exp_category, income_groups.title') // Add searchable columns
            ->orderable('income_head.id, income_head.exp_category, income_groups.title') // Add orderable columns
            ->sort('income_head.id', 'asc');
        return $this->datatables->generate('json');
    }


    public function addGroup($data)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        if (isset($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('income_groups', $data);
            $message   = UPDATE_RECORD_CONSTANT . " On  income group id " . $data['id'];
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
            $this->db->insert('income_groups', $data);
            $id        = $this->db->insert_id();
            $message   = INSERT_RECORD_CONSTANT . " On  income group id " . $id;
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
        return $this->db->get_where('income_groups', array('id' => $id))->row_array();
    }

    public function deleteGroup($id)
    {
        return $this->db->delete('income_groups', array('id' => $id));
    }

    public function getIncomeGroups($id = null)
    {
        $this->db->select()->from('income_groups');
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
}
