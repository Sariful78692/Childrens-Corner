<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Examresult_model extends CI_Model
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
        $this->db->select()->from('exam_results');
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
        $this->db->where('id', $id);
        $this->db->delete('exam_results');
    }

    /**
     * This function will take the post data passed from the controller
     * If id is present, then it will do an update
     * else an insert. One function doing both add and edit.
     * @param $data
     */
    public function add($data)
    {
        if (isset($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('exam_results', $data);
        } else {
            $this->db->insert('exam_results', $data);
            return $this->db->insert_id();
        }
    }

    public function add_exam_result($data)
    {
        $this->db->where('exam_schedule_id', $data['exam_schedule_id']);
        $this->db->where('student_id', $data['student_id']);
        $q      = $this->db->get('exam_results');
        $result = $q->row();
        if ($q->num_rows() > 0) {
            $this->db->where('id', $result->id);
            $this->db->update('exam_results', $data);
            if ($result->get_marks != $data['get_marks']) {
                return $result->id;
            }
        } else {
            $this->db->insert('exam_results', $data);
            $insert_id = $this->db->insert_id();
            return $insert_id;
        }
        return false;
    }

    public function get_exam_result($exam_schedule_id = null, $student_id = null)
    {
        $this->db->select()->from('exam_results');
        $this->db->where('exam_schedule_id', $exam_schedule_id);
        $this->db->where('student_id', $student_id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->row();
        } else {
            $obj             = new stdClass();
            $obj->attendence = 'pre';
            $obj->get_marks  = "0.00";
            return $obj;
        }
    }

    public function get_result($exam_schedule_id = null, $student_id = null)
    {
        $this->db->select()->from('exam_results');
        $this->db->where('exam_schedule_id', $exam_schedule_id);
        $this->db->where('student_id', $student_id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->row();
        } else {

        }
    }

    public function checkexamresultpreparebyexam($exam_id, $class_id, $section_id)
    {
        $query = $this->db->query("SELECT count(*) `counter` FROM `exam_results`,exam_schedules,student_session WHERE exam_results.exam_schedule_id=exam_schedules.id and student_session.student_id=exam_results.student_id and student_session.class_id=" . $this->db->escape($class_id) . " and student_session.section_id=" . $this->db->escape($section_id) . " and exam_schedules.session_id=" . $this->db->escape($this->current_session) . " and exam_schedules.exam_id=" . $this->db->escape($exam_id));
        if ($query->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
        return $query->result_array();
    }

    public function getStudentExamResultByStudent($exam_id, $student_id, $exam_schedule)
    {
        $sql = "SELECT exam_schedules.id as `exam_schedules_id`,exam_results.id as `exam_results_id`,exam_schedules.exam_id,exam_schedules.date_of_exam,exam_schedules.full_marks,exam_schedules.passing_marks,exam_results.student_id,exam_results.get_marks,students.firstname,students.middlename,students.lastname,students.guardian_phone,students.email ,exams.name as `exam_name` FROM `exam_schedules` INNER JOIN exams on exams.id=exam_schedules.exam_id INNER JOIN exam_results ON exam_results.exam_schedule_id=exam_schedules.id INNER JOIN students on students.id=exam_results.student_id WHERE exam_schedules.session_id =" . $this->db->escape($this->current_session) . " and exam_schedules.exam_id =" . $this->db->escape($exam_id) . " and exam_results.student_id =" . $this->db->escape($student_id) . " and exam_schedules.id in (" . $exam_schedule . ") ORDER BY `exam_results`.`id` ASC";

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function getExamResults($exam_id, $post_exam_group_id, $students)
    {
        $result           = array('exam_connection' => 0, 'students' => array(), 'exams' => array(), 'exam_connection_list' => array());
        $exam_connection  = false;
        $exam_connections = $this->examgroup_model->getExamGroupConnectionList($post_exam_group_id);
        if (!empty($exam_connections)) {
            $lastkey = key(array_slice($exam_connections, -1, 1, true));
            if ($exam_connections[$lastkey]->exam_group_class_batch_exams_id == $exam_id) {
                $exam_connection           = true;
                $result['exam_connection'] = 1;
            }
        }
        $result['exam_connection_list'] = $exam_connections;
        foreach ($students as $student_key => $student_value) {
            $student = $this->examstudent_model->getExamStudentByID($student_value);

            $student['exam_result'] = array();
            if ($exam_connection) {
                foreach ($exam_connections as $exam_connection_key => $exam_connection_value) {

                    $exam_group_class_batch_exam_student = $this->examstudent_model->getStudentByExamAndStudentID($student['student_id'], $exam_connection_value->exam_group_class_batch_exams_id);
                    if(!empty($exam_group_class_batch_exam_student)){
                        
                        $exam = $this->examgroup_model->getExamByID($exam_connection_value->exam_group_class_batch_exams_id);
    
                        $student['exam_result']['exam_roll_no_' . $exam_connection_value->exam_group_class_batch_exams_id] = $student['roll_no'];
    
                        $student['exam_result']['exam_result_' . $exam_connection_value->exam_group_class_batch_exams_id] = $this->getStudentResultByExam($exam_connection_value->exam_group_class_batch_exams_id, $exam_group_class_batch_exam_student->id);
    
                        $result['exams']['exam_' . $exam_connection_value->exam_group_class_batch_exams_id] = $exam;
                    }

                }
                $result['students'][] = $student;
            } else {
                $student['exam_roll_no'] = $student['roll_no'];
                $student['exam_result']  = $this->getStudentResultByExam($exam_id, $student['id']);
                $result['students'][]    = $student;
            }
        }

        return $result;
    }

    public function updaterank($update_array,$exam_group_class_batch_exam_id)
    {     
        if (!empty($update_array)) {
            $data_update = array('is_rank_generated' => 1);   
            $this->db->where('id', $exam_group_class_batch_exam_id);
            $this->db->update('exam_group_class_batch_exams', $data_update);
            $this->db->update_batch('exam_group_class_batch_exam_students', $update_array, 'id');
        }
       
    }

    public function getStudentResultByExam($exam_id, $student_id)
    {
        $sql   = "SELECT exam_group_class_batch_exam_subjects.*,exam_group_exam_results.id as `exam_group_exam_results_id`,exam_group_exam_results.attendence,exam_group_exam_results.get_marks,exam_group_exam_results.note,subjects.name,subjects.code,exam_group_class_batch_exam_students.rank FROM `exam_group_class_batch_exam_subjects` inner JOIN exam_group_exam_results on exam_group_exam_results.exam_group_class_batch_exam_subject_id=exam_group_class_batch_exam_subjects.id INNER JOIN exam_group_class_batch_exam_students on exam_group_exam_results.exam_group_class_batch_exam_student_id=exam_group_class_batch_exam_students.id and exam_group_class_batch_exam_students.id=" . $this->db->escape($student_id) . " INNER JOIN subjects on subjects.id=exam_group_class_batch_exam_subjects.subject_id WHERE exam_group_class_batch_exam_subjects.exam_group_class_batch_exams_id=" . $this->db->escape($exam_id);
        $query = $this->db->query($sql);
        return $query->result();
    }

    public function getStudentExamResults($exam_id, $post_exam_group_id, $exam_group_class_batch_exam_student_id, $student_id)
    {
        $student          = $this->examstudent_model->getExamStudentByID($exam_group_class_batch_exam_student_id);
        $result           = array('student' => $student, 'exam_connection' => 0, 'result' => array(), 'exams' => array(), 'exam_connection_list' => array());
        $exam_connection  = false;
        $exam_connections = $this->examgroup_model->getExamGroupConnectionList($post_exam_group_id);
        if (!empty($exam_connections)) {
            $lastkey = key(array_slice($exam_connections, -1, 1, true));
            if ($exam_connections[$lastkey]->exam_group_class_batch_exams_id == $exam_id) {
                $exam_connection           = true;
                $result['exam_connection'] = 1;
            }
        }
        $result['exam_connection_list'] = $exam_connections;
        if ($exam_connection) {
            $new_array = array();

            foreach ($exam_connections as $exam_connection_key => $exam_connection_value) {

                $exam_group_class_batch_exam_student = $this->examstudent_model->getStudentByExamAndStudentID($student_id, $exam_connection_value->exam_group_class_batch_exams_id);

                $exam = $this->examgroup_model->getExamByID($exam_connection_value->exam_group_class_batch_exams_id);

                if (!empty($exam_group_class_batch_exam_student->id)) {

                    $result['exam_result']['exam_roll_no_' . $exam_connection_value->exam_group_class_batch_exams_id] = $student['roll_no'];
                    $result['exam_result']['exam_result_' . $exam_connection_value->exam_group_class_batch_exams_id]
                    = $this->getStudentResultByExam($exam_connection_value->exam_group_class_batch_exams_id, $exam_group_class_batch_exam_student->id);

                }
                $result['exams']['exam_' . $exam_connection_value->exam_group_class_batch_exams_id] = $exam;
            }

        } else {

            $result['exam_connection_list']    = $exam_connections;
            $result['student']['exam_roll_no'] = $student['roll_no'];
            $result['result']                  = $this->getStudentResultByExam($exam_id, $exam_group_class_batch_exam_student_id);
        }

        return $result;
    }

    public function getStudentOverallExamGroupResults($exam_group_id, $student_ids, $session_id = null, $class_id = null)
    {
        if (empty($exam_group_id) || empty($student_ids)) {
            return array();
        }

        $student_ids = array_values(array_unique(array_filter($student_ids)));

        $this->db->select('id, exam, session_id');
        $this->db->from('exam_group_class_batch_exams');
        $this->db->where('exam_group_id', $exam_group_id);
        $this->db->order_by('id', 'asc');
        $exams = $this->db->get()->result_array();

        if (empty($exams)) {
            return $this->getStudentOverallExamGroupResultsFromMarks($exam_group_id, $student_ids, $session_id, $class_id);
        }

        $exam_ids = array_column($exams, 'id');
        if (empty($session_id)) {
            $session_id = !empty($exams[0]['session_id']) ? $exams[0]['session_id'] : $this->current_session;
        }

        $this->db->select('students.id, students.admission_no, students.roll_no, students.image, students.firstname, students.middlename, students.lastname, students.father_name, students.father_phone, students.mother_name, students.mother_phone, students.dob, students.email, students.gender, students.guardian_name, students.guardian_relation, students.guardian_phone, students.guardian_email, classes.class, sections.section, sessions.session');
        $this->db->from('students');
        $this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->join('classes', 'classes.id = student_session.class_id', 'left');
        $this->db->join('sections', 'sections.id = student_session.section_id', 'left');
        $this->db->join('sessions', 'sessions.id = student_session.session_id', 'left');
        $this->db->where_in('students.id', $student_ids);
        if (!empty($session_id)) {
            $this->db->where('student_session.session_id', $session_id);
        }
        if (!empty($class_id)) {
            $this->db->where('student_session.class_id', $class_id);
        }
        $students = $this->db->get()->result_array();

        if (empty($students)) {
            $this->db->select('students.id, students.admission_no, students.roll_no, students.image, students.firstname, students.middlename, students.lastname, students.father_name, students.father_phone, students.mother_name, students.mother_phone, students.dob, students.email, students.gender, students.guardian_name, students.guardian_relation, students.guardian_phone, students.guardian_email, classes.class, sections.section, sessions.session');
            $this->db->from('students');
            $this->db->join('student_session', 'student_session.student_id = students.id');
            $this->db->join('classes', 'classes.id = student_session.class_id', 'left');
            $this->db->join('sections', 'sections.id = student_session.section_id', 'left');
            $this->db->join('sessions', 'sessions.id = student_session.session_id', 'left');
            $this->db->where_in('students.id', $student_ids);
            $students = $this->db->get()->result_array();
        }

        $this->db->select('exam_group_class_batch_exam_subjects.id as exam_subject_id, exam_group_class_batch_exam_subjects.exam_group_class_batch_exams_id as exam_id, exam_group_class_batch_exam_subjects.max_marks, subjects.name as subject_name');
        $this->db->from('exam_group_class_batch_exam_subjects');
        $this->db->join('subjects', 'subjects.id = exam_group_class_batch_exam_subjects.subject_id', 'left');
        $this->db->where_in('exam_group_class_batch_exam_subjects.exam_group_class_batch_exams_id', $exam_ids);
        $this->db->order_by('exam_group_class_batch_exam_subjects.exam_group_class_batch_exams_id', 'asc');
        $exam_subjects = $this->db->get()->result_array();

        $subjects_by_exam = array();
        foreach ($exam_subjects as $subject_row) {
            $subjects_by_exam[$subject_row['exam_id']][] = $subject_row;
        }

        $this->db->select('id, exam_group_class_batch_exam_id, student_id');
        $this->db->from('exam_group_class_batch_exam_students');
        $this->db->where_in('exam_group_class_batch_exam_id', $exam_ids);
        $this->db->where_in('student_id', $student_ids);
        $exam_students = $this->db->get()->result_array();

        $exam_student_map = array();
        $exam_student_ids = array();
        foreach ($exam_students as $exam_student) {
            $exam_student_map[$exam_student['student_id']][$exam_student['exam_group_class_batch_exam_id']] = $exam_student['id'];
            $exam_student_ids[] = $exam_student['id'];
        }

        $marks_map = array();
        if (!empty($exam_student_ids)) {
            $this->db->select('exam_group_class_batch_exam_student_id, exam_group_class_batch_exam_subject_id, get_marks');
            $this->db->from('exam_group_exam_results');
            $this->db->where_in('exam_group_class_batch_exam_student_id', $exam_student_ids);
            $exam_results = $this->db->get()->result_array();

            foreach ($exam_results as $exam_result) {
                $marks_map[$exam_result['exam_group_class_batch_exam_student_id']][$exam_result['exam_group_class_batch_exam_subject_id']] = $exam_result['get_marks'];
            }
        }

        $final_results = array();
        foreach ($students as $student) {
            $student_object = (object) $student;
            $student_object->exams = array();
            $total_overall_marks = 0;

            foreach ($exams as $exam) {
                $exam_id = $exam['id'];
                $subject_results = array();
                $exam_student_id = isset($exam_student_map[$student['id']][$exam_id]) ? $exam_student_map[$student['id']][$exam_id] : null;

                $subjects = isset($subjects_by_exam[$exam_id]) ? $subjects_by_exam[$exam_id] : array();
                foreach ($subjects as $subject) {
                    $obtain_marks = 0;
                    if (!empty($exam_student_id) && isset($marks_map[$exam_student_id][$subject['exam_subject_id']])) {
                        $obtain_marks = $marks_map[$exam_student_id][$subject['exam_subject_id']];
                    }

                    $subject_results[] = array(
                        'subject_name' => $subject['subject_name'],
                        'full_marks' => $subject['max_marks'],
                        'obtain_marks' => $obtain_marks,
                    );

                    $total_overall_marks += (float) $obtain_marks;
                }

                $student_object->exams[] = array(
                    'exam_id' => $exam_id,
                    'exam_name' => $exam['exam'],
                    'subject_results' => $subject_results,
                );
            }

            $student_object->total_overall_marks = $total_overall_marks;
            $final_results[] = $student_object;
        }

        return $final_results;
    }

    public function getStudentOverallResultsByClassSession($student_ids, $session_id, $class_id, $section_id = null)
    {
        if (empty($student_ids) || empty($session_id) || empty($class_id)) {
            return array();
        }

        $student_ids = array_values(array_unique(array_filter($student_ids)));

        $marks_results = $this->getStudentOverallResultsByClassSessionFromMarks($student_ids, $session_id, $class_id, $section_id);
        if (!empty($marks_results)) {
            return $marks_results;
        }

        $this->db->distinct();
        $this->db->select('exam_group_class_batch_exams.id, exam_group_class_batch_exams.exam, exam_group_class_batch_exams.session_id');
        $this->db->from('exam_group_class_batch_exams');
        $this->db->join('exam_group_class_batch_exam_students', 'exam_group_class_batch_exam_students.exam_group_class_batch_exam_id = exam_group_class_batch_exams.id');
        $this->db->join('student_session', 'student_session.id = exam_group_class_batch_exam_students.student_session_id');
        $this->db->where('exam_group_class_batch_exams.session_id', $session_id);
        $this->db->where('student_session.class_id', $class_id);
        if (!empty($section_id)) {
            $this->db->where('student_session.section_id', $section_id);
        }
        $this->db->where_in('exam_group_class_batch_exam_students.student_id', $student_ids);
        $this->db->order_by('exam_group_class_batch_exams.id', 'asc');
        $exams = $this->db->get()->result_array();

        if (empty($exams)) {
            return array();
        }

        $exam_ids = array_column($exams, 'id');

        $this->db->select('students.id, students.admission_no, students.roll_no, students.image, students.firstname, students.middlename, students.lastname, students.father_name, students.father_phone, students.mother_name, students.mother_phone, students.dob, students.email, students.gender, students.guardian_name, students.guardian_relation, students.guardian_phone, students.guardian_email, classes.class, sections.section, sessions.session');
        $this->db->from('students');
        $this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->join('classes', 'classes.id = student_session.class_id', 'left');
        $this->db->join('sections', 'sections.id = student_session.section_id', 'left');
        $this->db->join('sessions', 'sessions.id = student_session.session_id', 'left');
        $this->db->where_in('students.id', $student_ids);
        $this->db->where('student_session.session_id', $session_id);
        $this->db->where('student_session.class_id', $class_id);
        if (!empty($section_id)) {
            $this->db->where('student_session.section_id', $section_id);
        }
        $students = $this->db->get()->result_array();

        if (empty($students)) {
            return array();
        }

        $this->db->select('exam_group_class_batch_exam_subjects.id as exam_subject_id, exam_group_class_batch_exam_subjects.exam_group_class_batch_exams_id as exam_id, exam_group_class_batch_exam_subjects.max_marks, subjects.name as subject_name');
        $this->db->from('exam_group_class_batch_exam_subjects');
        $this->db->join('subjects', 'subjects.id = exam_group_class_batch_exam_subjects.subject_id', 'left');
        $this->db->where_in('exam_group_class_batch_exam_subjects.exam_group_class_batch_exams_id', $exam_ids);
        $this->db->order_by('exam_group_class_batch_exam_subjects.exam_group_class_batch_exams_id', 'asc');
        $exam_subjects = $this->db->get()->result_array();

        $subjects_by_exam = array();
        foreach ($exam_subjects as $subject_row) {
            $subjects_by_exam[$subject_row['exam_id']][] = $subject_row;
        }

        $this->db->select('id, exam_group_class_batch_exam_id, student_id');
        $this->db->from('exam_group_class_batch_exam_students');
        $this->db->where_in('exam_group_class_batch_exam_id', $exam_ids);
        $this->db->where_in('student_id', $student_ids);
        $exam_students = $this->db->get()->result_array();

        $exam_student_map = array();
        $exam_student_ids = array();
        foreach ($exam_students as $exam_student) {
            $exam_student_map[$exam_student['student_id']][$exam_student['exam_group_class_batch_exam_id']] = $exam_student['id'];
            $exam_student_ids[] = $exam_student['id'];
        }

        $marks_map = array();
        if (!empty($exam_student_ids)) {
            $this->db->select('exam_group_class_batch_exam_student_id, exam_group_class_batch_exam_subject_id, get_marks');
            $this->db->from('exam_group_exam_results');
            $this->db->where_in('exam_group_class_batch_exam_student_id', $exam_student_ids);
            $exam_results = $this->db->get()->result_array();

            foreach ($exam_results as $exam_result) {
                $marks_map[$exam_result['exam_group_class_batch_exam_student_id']][$exam_result['exam_group_class_batch_exam_subject_id']] = $exam_result['get_marks'];
            }
        }

        $final_results = array();
        foreach ($students as $student) {
            $student_object = (object) $student;
            $student_object->exams = array();
            $total_overall_marks = 0;

            foreach ($exams as $exam) {
                $exam_id = $exam['id'];
                $subject_results = array();
                $exam_student_id = isset($exam_student_map[$student['id']][$exam_id]) ? $exam_student_map[$student['id']][$exam_id] : null;
                $subjects = isset($subjects_by_exam[$exam_id]) ? $subjects_by_exam[$exam_id] : array();

                foreach ($subjects as $subject) {
                    $obtain_marks = 0;
                    if (!empty($exam_student_id) && isset($marks_map[$exam_student_id][$subject['exam_subject_id']])) {
                        $obtain_marks = $marks_map[$exam_student_id][$subject['exam_subject_id']];
                    }

                    $subject_results[] = array(
                        'subject_name' => $subject['subject_name'],
                        'full_marks'   => $subject['max_marks'],
                        'obtain_marks' => $obtain_marks,
                    );

                    $total_overall_marks += (float) $obtain_marks;
                }

                $student_object->exams[] = array(
                    'exam_id'         => $exam_id,
                    'exam_name'       => $exam['exam'],
                    'subject_results' => $subject_results,
                );
            }

            $student_object->total_overall_marks = $total_overall_marks;
            $final_results[] = $student_object;
        }

        return $final_results;
    }

    private function getStudentOverallResultsByClassSessionFromMarks($student_ids, $session_id, $class_id, $section_id = null)
    {
        $this->db->select('exam_groups.id as exam_id, exam_groups.name as exam_name, MIN(exams.id) as sort_order');
        $this->db->from('exams');
        $this->db->join('exam_groups', 'exam_groups.id = exams.group_id', 'left');
        $this->db->where('exams.session_id', $session_id);
        $this->db->where('exams.class_id', $class_id);
        $this->db->group_by('exam_groups.id, exam_groups.name');
        $this->db->order_by('sort_order', 'asc');
        $exams = $this->db->get()->result_array();

        if (empty($exams)) {
            return array();
        }

        $exam_ids = array_column($exams, 'exam_id');

        $this->db->select('students.id, students.admission_no, student_session.roll_no, students.image, students.firstname, students.middlename, students.lastname, students.father_name, students.father_phone, students.mother_name, students.mother_phone, students.dob, students.email, students.gender, students.guardian_name, students.guardian_relation, students.guardian_phone, students.guardian_email, classes.class, sections.section, sessions.session');
        $this->db->from('students');
        $this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->join('classes', 'classes.id = student_session.class_id', 'left');
        $this->db->join('sections', 'sections.id = student_session.section_id', 'left');
        $this->db->join('sessions', 'sessions.id = student_session.session_id', 'left');
        $this->db->where_in('students.id', $student_ids);
        $this->db->where('student_session.session_id', $session_id);
        $this->db->where('student_session.class_id', $class_id);
        if (!empty($section_id)) {
            $this->db->where('student_session.section_id', $section_id);
        }
        $students = $this->db->get()->result_array();

        if (empty($students)) {
            return array();
        }

        $this->db->select('exams.group_id as exam_id, exams.subject_id, exams.full_marks, subjects.name as subject_name');
        $this->db->from('exams');
        $this->db->join('subjects', 'subjects.id = exams.subject_id', 'left');
        $this->db->where('exams.session_id', $session_id);
        $this->db->where('exams.class_id', $class_id);
        $this->db->where_in('exams.group_id', $exam_ids);
        $this->db->order_by('exams.id', 'asc');
        $exam_subjects = $this->db->get()->result_array();

        $subjects_by_exam = array();
        foreach ($exam_subjects as $subject_row) {
            $subjects_by_exam[$subject_row['exam_id']][] = $subject_row;
        }

        $this->db->select('student_id, group_id, subject_id, obtain_marks');
        $this->db->from('marks');
        $this->db->where('session_id', $session_id);
        $this->db->where('class_id', $class_id);
        $this->db->where_in('group_id', $exam_ids);
        $this->db->where_in('student_id', $student_ids);
        $marks = $this->db->get()->result_array();

        $marks_map = array();
        foreach ($marks as $mark_row) {
            $marks_map[$mark_row['student_id']][$mark_row['group_id']][$mark_row['subject_id']] = $mark_row['obtain_marks'];
        }

        $final_results = array();
        foreach ($students as $student) {
            $student_object = (object) $student;
            $student_object->exams = array();
            $total_overall_marks = 0;

            foreach ($exams as $exam) {
                $subject_results = array();
                $subjects = isset($subjects_by_exam[$exam['exam_id']]) ? $subjects_by_exam[$exam['exam_id']] : array();
                foreach ($subjects as $subject) {
                    $obtain_marks = 0;
                    if (isset($marks_map[$student['id']][$exam['exam_id']][$subject['subject_id']])) {
                        $obtain_marks = $marks_map[$student['id']][$exam['exam_id']][$subject['subject_id']];
                    }

                    $subject_results[] = array(
                        'subject_name' => $subject['subject_name'],
                        'full_marks'   => $subject['full_marks'],
                        'obtain_marks' => $obtain_marks,
                    );
                    $total_overall_marks += (float) $obtain_marks;
                }

                $student_object->exams[] = array(
                    'exam_id'         => $exam['exam_id'],
                    'exam_name'       => $exam['exam_name'],
                    'subject_results' => $subject_results,
                );
            }

            $student_object->total_overall_marks = $total_overall_marks;
            $final_results[] = $student_object;
        }

        return $final_results;
    }

    private function getStudentOverallExamGroupResultsFromMarks($exam_group_id, $student_ids, $session_id = null, $class_id = null)
    {
        if (empty($exam_group_id) || empty($student_ids)) {
            return array();
        }

        $student_ids = array_values(array_unique(array_filter($student_ids)));

        if (empty($session_id) || empty($class_id)) {
            $this->db->select('session_id, class_id');
            $this->db->from('exams');
            $this->db->where('group_id', $exam_group_id);
            $this->db->order_by('id', 'asc');
            $fallback_exam = $this->db->get()->row_array();
            if (!empty($fallback_exam)) {
                if (empty($session_id)) {
                    $session_id = $fallback_exam['session_id'];
                }
                if (empty($class_id)) {
                    $class_id = $fallback_exam['class_id'];
                }
            }
        }

        $this->db->select('students.id, students.admission_no, students.roll_no, students.image, students.firstname, students.middlename, students.lastname, students.father_name, students.father_phone, students.mother_name, students.mother_phone, students.dob, students.email, students.gender, students.guardian_name, students.guardian_relation, students.guardian_phone, students.guardian_email, classes.class, sections.section, sessions.session');
        $this->db->from('students');
        $this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->join('classes', 'classes.id = student_session.class_id', 'left');
        $this->db->join('sections', 'sections.id = student_session.section_id', 'left');
        $this->db->join('sessions', 'sessions.id = student_session.session_id', 'left');
        $this->db->where_in('students.id', $student_ids);
            if (!empty($session_id)) {
                $this->db->where('student_session.session_id', $session_id);
            }
            if (!empty($class_id)) {
                $this->db->where('student_session.class_id', $class_id);
            }
        if (!empty($class_id)) {
            $this->db->where('student_session.class_id', $class_id);
        }
        $students = $this->db->get()->result_array();

        if (empty($students)) {
            return array();
        }

        $this->db->select('exam_groups.name as exam_group_name');
        $this->db->from('exam_groups');
        $this->db->where('exam_groups.id', $exam_group_id);
        $exam_group = $this->db->get()->row_array();
        $exam_group_name = !empty($exam_group['exam_group_name']) ? $exam_group['exam_group_name'] : 'Exam Group';

        $this->db->select('exams.subject_id, exams.full_marks, subjects.name as subject_name');
        $this->db->from('exams');
        $this->db->join('subjects', 'subjects.id = exams.subject_id', 'left');
        $this->db->where('exams.group_id', $exam_group_id);
        if (!empty($session_id)) {
            $this->db->where('exams.session_id', $session_id);
        }
        if (!empty($class_id)) {
            $this->db->where('exams.class_id', $class_id);
        }
        $this->db->order_by('exams.id', 'asc');
        $exam_subjects = $this->db->get()->result_array();

        if (empty($exam_subjects)) {
            return array();
        }

        $marks_map = array();
        $this->db->select('student_id, subject_id, obtain_marks');
        $this->db->from('marks');
        $this->db->where('group_id', $exam_group_id);
        if (!empty($session_id)) {
            $this->db->where('session_id', $session_id);
        }
        if (!empty($class_id)) {
            $this->db->where('class_id', $class_id);
        }
        $this->db->where_in('student_id', $student_ids);
        $marks = $this->db->get()->result_array();

        foreach ($marks as $mark_row) {
            $marks_map[$mark_row['student_id']][$mark_row['subject_id']] = $mark_row['obtain_marks'];
        }

        $final_results = array();
        foreach ($students as $student) {
            $student_object = (object) $student;
            $student_object->exams = array();
            $total_overall_marks = 0;

            $subject_results = array();
            foreach ($exam_subjects as $subject) {
                $obtain_marks = 0;
                if (isset($marks_map[$student['id']][$subject['subject_id']])) {
                    $obtain_marks = $marks_map[$student['id']][$subject['subject_id']];
                }

                $subject_results[] = array(
                    'subject_name' => $subject['subject_name'],
                    'full_marks' => $subject['full_marks'],
                    'obtain_marks' => $obtain_marks,
                );

                $total_overall_marks += (float) $obtain_marks;
            }

            $student_object->exams[] = array(
                'exam_id' => $exam_group_id,
                'exam_name' => $exam_group_name,
                'subject_results' => $subject_results,
            );

            $student_object->total_overall_marks = $total_overall_marks;
            $final_results[] = $student_object;
        }

        return $final_results;
    }

}
