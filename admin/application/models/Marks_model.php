<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Marks_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function add_marks($data)
    {
        $this->db->trans_start();
        $this->db->trans_strict(false);

        foreach ($data as $row) {
            $this->db->where('exam_id', $row['exam_id']);
            $this->db->where('student_id', $row['student_id']);
            $this->db->where('subject_id', $row['subject_id']);
            $q = $this->db->get('marks');

            if ($q->num_rows() > 0) {
                $this->db->where('id', $q->row()->id);
                $this->db->update('marks', $row);
            } else {
                $this->db->insert('marks', $row);
            }
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return true;
        }
    }

    public function get_student_marks($student_id, $group_id, $session_id, $class_id)
    {
        $this->db->where('group_id', $group_id);
        $this->db->where('session_id', $session_id);
        $this->db->where('class_id', $class_id);
        $this->db->where('student_id', $student_id);

        $query = $this->db->get('marks');
        $result = [];
        foreach ($query->result_array() as $row) {
            $result[$row['subject_id']] = $row['obtain_marks'];
        }
        return $result;
    }
}
