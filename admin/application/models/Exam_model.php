<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Exam_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->current_session = $this->setting_model->getCurrentSession();
    }

    /**
     * This funtion takes id as a parameter and will fetch the record.
     * If id is not provided, then it will fetch all the records form the table.
     * @param int $id
     * @return mixed
     */
    public function get($id = null)
    {
        $this->db->select('exams.*, subjects.name as subject_name');
        $this->db->from('exams');
        $this->db->join('subjects', 'subjects.id = exams.subject_id', 'left');
        if ($id != null) {
            $this->db->where('exams.id', $id);
        } else {
            $this->db->order_by('exams.id');
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
        $this->db->where('id', $id);
        $this->db->delete('exams');
    }

    /**
     * This function will take the post data passed from the controller
     * If id is present, then it will do an update
     * else an insert. One function doing both add and edit.
     * @param $data
     */
    public function add($data)
    {
        $this->db->insert('exams', $data);
        return $this->db->insert_id();
    }

    public function update($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update('exams', $data);
    }

    public function add_exam_schedule($data)
    {
        $this->db->where('exam_id', $data['exam_id']);
        $this->db->where('teacher_subject_id', $data['teacher_subject_id']);
        $q = $this->db->get('exam_schedules');
        if ($q->num_rows() > 0) {
            $result = $q->row_array();
            $this->db->where('id', $result['id']);
            $this->db->update('exam_schedules', $data);
        } else {
            $this->db->insert('exam_schedules', $data);
        }
    }

    public function getByGroupAndClass($group_id, $class_id, $session_id = null)
    {
        $this->db->where(['group_id' => $group_id, 'class_id' => $class_id]);
        if ($session_id) {
            $this->db->where('session_id', $session_id);
        }
        return $this->db->get('exams')->result_array();
    }

    public function getByGroupClassAndTeacher($group_id, $class_id, $teacher_id, $session_id = null)
    {
        $this->db->where(['group_id' => $group_id, 'class_id' => $class_id]);
        $this->db->where('assign_teacher_id', $teacher_id);
        if ($session_id) {
            $this->db->where('session_id', $session_id);
        }
        return $this->db->get('exams')->result_array();
    }

    public function getClassesByGroup($group_id, $session_id = null)
    {
        $this->db->select('classes.id, classes.class');
        $this->db->from('exams');
        $this->db->join('classes', 'classes.id = exams.class_id');
        $this->db->where('exams.group_id', $group_id);
        if ($session_id) {
            $this->db->where('exams.session_id', $session_id);
        }
        $this->db->group_by('exams.class_id');
        return $this->db->get()->result_array();
    }

    public function get_subjects_by_exam($group_id, $session_id, $class_id, $teacher_id = '')
    {
        $this->db->select('subjects.id, subjects.name, exams.full_marks, exams.mark_submitted');
        $this->db->from('exams');
        $this->db->join('subjects', 'subjects.id = exams.subject_id');
        $this->db->where('exams.group_id', $group_id);
        $this->db->where('exams.session_id', $session_id);
        $this->db->where('exams.class_id', $class_id);
        if ($teacher_id) {
            $this->db->where('exams.assign_teacher_id', $teacher_id);
        }
        return $this->db->get()->result_array();
    }

    public function get_exam_details($exam_id)
    {
        $this->db->select('exams.*, subjects.name as subject_name, subjects.id as subject_id, staff.name as staff_name');
        $this->db->from('exams');
        $this->db->join('subjects', 'subjects.id = exams.subject_id', 'left');
        $this->db->join('staff', 'staff.id = exams.assign_teacher_id', 'left');
        $this->db->where('exams.id', $exam_id);
        return $this->db->get()->row_array();
    }

    public function update_submission_status($exam_id, $subject_id)
    {
        $this->db->where('id', $exam_id);
        $this->db->where('subject_id', $subject_id);
        $this->db->update('exams', ['mark_submitted' => 1]);
    }
}
