<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cms_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getSyllabusList()
    {
        $this->db->select('syllabus.*');
        $this->db->from('syllabus');
        $this->db->order_by('syllabus.id', 'DESC');

        return $this->db->get()->result_array();
    }

    public function getSyllabus($id)
    {
        $this->db->select('syllabus.*');
        $this->db->from('syllabus');
        $this->db->where('syllabus.id', $id);
        return $this->db->get()->row_array();
    }

    public function addSyllabus($data)
    {
        if (isset($data['id']) && $data['id'] != '') {
            $this->db->where('id', $data['id']);
            $this->db->update('syllabus', $data);
            return $data['id'];
        } else {
            $this->db->insert('syllabus', $data);
            return $this->db->insert_id();
        }
    }

    public function deleteSyllabus($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('syllabus');
    }

    public function getCmsFiles()
    {
        $res = $this->db->get('cms_files')->result_array();
        $files = array();
        foreach($res as $row) {
            $files[$row['file_type']] = $row['file_path'];
        }
        return $files;
    }

    public function updateCmsFile($type, $path)
    {
        $this->db->where('file_type', $type);
        $query = $this->db->get('cms_files');
        if ($query->num_rows() > 0) {
            $this->db->where('file_type', $type);
            $this->db->update('cms_files', array('file_path' => $path));
        } else {
            $this->db->insert('cms_files', array('file_type' => $type, 'file_path' => $path));
        }
    }

    public function getExaminationRoutineList()
    {
        $this->db->select('examination_routine.*');
        $this->db->from('examination_routine');
        $this->db->order_by('examination_routine.id', 'DESC');

        return $this->db->get()->result_array();
    }

    public function getExaminationRoutine($id)
    {
        $this->db->select('examination_routine.*');
        $this->db->from('examination_routine');
        $this->db->where('examination_routine.id', $id);
        return $this->db->get()->row_array();
    }

    public function addExaminationRoutine($data)
    {
        if (isset($data['id']) && $data['id'] != '') {
            $this->db->where('id', $data['id']);
            $this->db->update('examination_routine', $data);
            return $data['id'];
        } else {
            $this->db->insert('examination_routine', $data);
            return $this->db->insert_id();
        }
    }

    public function deleteExaminationRoutine($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('examination_routine');
    }

    public function getExaminationRuleList()
    {
        $this->db->select('examination_rules.*');
        $this->db->from('examination_rules');
        $this->db->order_by('examination_rules.id', 'DESC');

        return $this->db->get()->result_array();
    }

    public function getExaminationRule($id)
    {
        $this->db->select('examination_rules.*');
        $this->db->from('examination_rules');
        $this->db->where('examination_rules.id', $id);
        return $this->db->get()->row_array();
    }

    public function addExaminationRule($data)
    {
        if (isset($data['id']) && $data['id'] != '') {
            $this->db->where('id', $data['id']);
            $this->db->update('examination_rules', $data);
            return $data['id'];
        } else {
            $this->db->insert('examination_rules', $data);
            return $this->db->insert_id();
        }
    }

    public function deleteExaminationRule($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('examination_rules');
    }
}
