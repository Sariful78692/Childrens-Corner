<?php
defined('BASEPATH') or exit('No direct script access allowed');

class AdmissionModel extends CI_Model
{

    public function apply($data)
    {
        // Insert data into the 'admissions' table
        return $this->db->insert('admissions', $data);
    }

    public function getOnlineAdmissions($class_id = null, $session_id = null, $status = null, $from_date = null, $to_date = null)
    {
        $this->db->select('admissions.*, classes.class as className, sessions.session as academicYearTitle');
        $this->db->from('admissions');
        $this->db->join('classes', 'classes.id = admissions.classApplied', 'left');
        $this->db->join('sessions', 'sessions.id = admissions.academicYear', 'left');
        $this->db->where('admissions.is_deleted', 0);

        if ($class_id) {
            $this->db->where('admissions.classApplied', $class_id);
        }
        if ($session_id) {
            $this->db->where('admissions.academicYear', $session_id);
        }
        if ($status !== null && $status !== '') {
            $this->db->where('admissions.status', $status);
        }
        if ($from_date) {
            $this->db->where('admissions.created_at >=', $from_date);
        }
        if ($to_date) {
            $this->db->where('admissions.created_at <=', $to_date);
        }

        $query = $this->db->get();
        return $query->result();
    }

    public function getOnlineAdmissionById($id)
    {
        $this->db->select('admissions.*, classes.class as className, sessions.session as academicYearTitle');
        $this->db->from('admissions');
        $this->db->join('classes', 'classes.id = admissions.classApplied', 'left');
        $this->db->join('sessions', 'sessions.id = admissions.academicYear', 'left');
        $this->db->where('admissions.id', $id);
        $query = $this->db->get();
        return $query->row_array();
    }

    public function updateOnlineAdmissionStatus($id, $status)
    {
        $this->db->where('id', $id);
        $this->db->update('admissions', array('status' => $status));
        return $this->db->affected_rows();
    }
}
