<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Studentfee_model extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->current_session = $this->setting_model->getCurrentSession();
        $this->current_date = $this->setting_model->getDateYmd();
    }

    public function getPendingFeesCount($user_id = null)
    {
        $this->db->where('status', 2);
        if ($user_id !== null) {
            $this->db->where('collection_by', $user_id);
        }
        $this->db->from('student_fees_collections');
        return $this->db->count_all_results();
    }

    public function getStudentFeesArray($student_session_id, $ids = null)
    {
        $query = "SELECT feemasters.id as feemastersid, feemasters.amount as amount,IFNULL(student_fees.id, 'xxx') as invoiceno,IFNULL(student_fees.payment_mode, 'xxx') as payment_mode,IFNULL(student_fees.amount_discount, 'xxx') as discount,IFNULL(student_fees.amount_fine, 'xxx') as fine, IFNULL(student_fees.date, 'xxx') as date,feetype.type ,feecategory.category FROM feemasters LEFT JOIN (select student_fees.id,student_fees.payment_mode,student_fees.feemaster_id,student_fees.amount_fine,student_fees.amount_discount,student_fees.date,student_fees.student_session_id from student_fees , student_session where student_fees.student_session_id=student_session.id and student_session.id=" . $this->db->escape($student_session_id) . ") as student_fees ON student_fees.feemaster_id=feemasters.id LEFT JOIN feetype ON feemasters.feetype_id = feetype.id LEFT JOIN feecategory ON feetype.feecategory_id = feecategory.id where feemasters.id IN (" . $ids . ")";

        $query = $this->db->query($query);
        return $query->result_array();
    }

    public function getCollectBy()
    {
        return $this->db->select('id,name,surname')->from('staff')->get()->result_array();
    }

    public function getTotalCollectionBydate($date)
    {
        $sql = "SELECT sum(amount) as `amount`, SUM(amount_discount) as `amount_discount` ,SUM(amount_fine) as `amount_fine` FROM `student_fees` where date=" . $this->db->escape($date);
        $query = $this->db->query($sql);
        return $query->row();
    }

    public function getStudentFees($id = null)
    {
        $this->db->select('feecategory.category,student_fees.id as `invoiceno`,student_fees.date,student_fees.id,student_fees.amount,student_fees.amount_discount,student_fees.amount_fine,student_fees.created_at,feetype.type')->from('student_fees');
        $this->db->join('student_session', 'student_session.id = student_fees.student_session_id');
        $this->db->join('feemasters', 'feemasters.id = student_fees.feemaster_id');
        $this->db->join('feetype', 'feetype.id = feemasters.feetype_id');
        $this->db->join('feecategory', 'feetype.feecategory_id = feecategory.id');
        $this->db->where('student_session.student_id', $id);
        $this->db->where('student_session.session_id', $this->current_session);
        $this->db->order_by('student_fees.id');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getFeeByInvoice($id = null)
    {
        $this->db->select('feecategory.category,student_fees.date,student_fees.payment_mode,student_fees.id as `student_fee_id`,student_fees.amount,student_fees.amount_discount,student_fees.amount_fine,student_fees.created_at,classes.class,sections.section,feetype.type,students.id,students.admission_no , students.roll_no,students.admission_date,students.firstname,students.middlename,  students.lastname,students.image,    students.mobileno, students.email ,students.state ,   students.city , students.pincode ,     students.religion,students.dob ,students.current_address,    students.permanent_address,students.category_id,    students.adhar_no,students.samagra_id,students.bank_account_no,students.bank_name, students.ifsc_code , students.guardian_name, students.guardian_relation,students.guardian_phone,students.guardian_address,students.is_active ,students.created_at ,students.updated_at,students.rte')->from('student_fees');
        $this->db->join('student_session', 'student_session.id = student_fees.student_session_id');
        $this->db->join('feemasters', 'feemasters.id = student_fees.feemaster_id');
        $this->db->join('feetype', 'feetype.id = feemasters.feetype_id');
        $this->db->join('classes', 'student_session.class_id = classes.id');
        $this->db->join('feecategory', 'feetype.feecategory_id = feecategory.id');
        $this->db->join('sections', 'sections.id = student_session.section_id');
        $this->db->join('students', 'students.id = student_session.student_id');
        $this->db->where('student_fees.id', $id);
        $this->db->where('student_session.session_id', $this->current_session);
        $this->db->order_by('student_fees.id');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getTodayStudentFees()
    {
        $this->db->select('student_fees.date,student_fees.id,student_fees.amount,student_fees.amount_discount,student_fees.amount_fine,student_fees.created_at,classes.class,sections.section,students.firstname,students.middlename,students.lastname,students.admission_no,students.roll_no,students.dob,students.guardian_name,feetype.type')->from('student_fees');
        $this->db->join('student_session', 'student_session.id = student_fees.student_session_id');
        $this->db->join('feemasters', 'feemasters.id = student_fees.feemaster_id');
        $this->db->join('feetype', 'feetype.id = feemasters.feetype_id');
        $this->db->join('classes', 'student_session.class_id = classes.id');
        $this->db->join('sections', 'sections.id = student_session.section_id');
        $this->db->join('students', 'students.id = student_session.student_id');
        $this->db->where('student_fees.date', $this->current_date);
        $this->db->where('student_session.session_id', $this->current_session);
        $this->db->order_by('student_fees.id');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function remove($id, $sub_invoice)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->where('id', $id);
        $q = $this->db->get('student_fees_deposite');
        if ($q->num_rows() > 0) {
            $result = $q->row();
            $a = json_decode($result->amount_detail, true);
            unset($a[$sub_invoice]);
            if (!empty($a)) {
                $data['amount_detail'] = json_encode($a);
                $this->db->where('id', $id);
                $this->db->update('student_fees_deposite', $data);
                $message = UPDATE_RECORD_CONSTANT . " On student fees deposite id " . $id;
                $action = "Update";
                $record_id = $id;
                $this->log($message, $record_id, $action);
            } else {
                $this->db->where('id', $id);
                $this->db->delete('student_fees_deposite');
                $message = DELETE_RECORD_CONSTANT . " On student fees deposite id " . $id;
                $action = "Delete";
                $record_id = $id;
                $this->log($message, $record_id, $action);
            }
        }
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

    public function add($data)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        if (isset($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('student_fees', $data);
            $message = UPDATE_RECORD_CONSTANT . " On  student fees id " . $data['id'];
            $action = "Update";
            $record_id = $id = $data['id'];
            $this->log($message, $record_id, $action);
        } else {
            $this->db->insert('student_fees', $data);
            $id = $this->db->insert_id();
            $message = INSERT_RECORD_CONSTANT . " On student fees id " . $id;
            $action = "Insert";
            $record_id = $id;
            $this->log($message, $record_id, $action);
        }
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

    public function getMultipleDueFees($feegroups = array(), $fee_groups_feetypes = array(), $transport_groups_feetype_array = array(), $class_id = NULL, $section_id = NULL)
    {

        $module = $this->module_model->getPermissionByModulename('transport');
        if ($module['is_active']) {
            if (!empty($transport_groups_feetype_array)) {
                $this->db->select('`student_fees_deposite`.*,IFNULL(student_fees_deposite.amount_detail, 0) as amount_detail,0 as previous_balance_amount,route_pickup_point.fees as amount,students.firstname,students.middlename,students.lastname,student_session.class_id,classes.class,sections.section,student_session.section_id,student_session.student_id,"" as fee_group,"Transport Fees" as name, "Transport Fees" as `fee_type`, transport_feemaster.month as `fee_code`,0 as is_system,student_transport_fees.student_session_id,students.admission_no, `student_session`.`id` as `student_session_id`,0 as is_system,`students`.`id`, `classes`.`class`, `sections`.`id` AS `section_id`, `sections`.`section`, `students`.`id`, `students`.`admission_no`, `students`.`roll_no`, `students`.`admission_date`, `students`.`firstname`,`students`.`middlename`, `students`.`lastname`, `students`.`image`, `students`.`mobileno`, `students`.`email`, `students`.`state`, `students`.`city`, `students`.`pincode`, `students`.`religion`, `students`.`dob`, `students`.`current_address`, `students`.`permanent_address`, IFNULL(students.category_id, 0) as `category_id`, IFNULL(categories.category, "") as `category`, `students`.`adhar_no`, `students`.`samagra_id`, `students`.`bank_account_no`, `students`.`bank_name`, `students`.`ifsc_code`, `students`.`guardian_name`, `students`.`guardian_relation`, `students`.`guardian_phone`, `students`.`guardian_address`, `students`.`is_active`, `students`.`created_at`, `students`.`updated_at`, `students`.`father_name`, `students`.`rte`, `students`.`gender`')->from('student_transport_fees');

                $this->db->join('student_fees_deposite', 'student_transport_fees.id = `student_fees_deposite`.`student_transport_fee_id`', 'left');
                $this->db->join('transport_feemaster', '`student_transport_fees`.`transport_feemaster_id` = `transport_feemaster`.`id`', 'left');
                $this->db->join('student_session', 'student_session.id= `student_transport_fees`.`student_session_id`', 'INNER');
                $this->db->join('route_pickup_point', 'route_pickup_point.id = student_transport_fees.route_pickup_point_id');
                $this->db->join('classes', 'classes.id= student_session.class_id');
                $this->db->join('sections', 'sections.id= student_session.section_id');
                $this->db->join('students', 'students.id=student_session.student_id');
                $this->db->join('categories', '`students`.`category_id` = `categories`.`id`', 'left');
                $this->db->where('student_session.session_id', $this->current_session);
                $this->db->where_in('transport_feemaster.id', $transport_groups_feetype_array);
                $this->db->order_by('student_fees_deposite.id', 'desc');

                if ($class_id != null) {
                    $this->db->where('student_session.class_id', $class_id);
                }

                if ($section_id != null) {
                    $this->db->where('student_session.section_id', $section_id);
                }

                $query1        = $this->db->get();
                $result_value1 = $query1->result_array();
            } else {
                $result_value1 = array();
            }
        } else {
            $result_value1 = array();
        }

        $where_condition = array();
        if ($class_id != NULL) {
            $where_condition[] = " AND student_session.class_id=" . $class_id;
        }
        if ($section_id != NULL) {
            $where_condition[] = "student_session.section_id=" . $section_id;
        }

        $where_condition_string = implode(" AND ", $where_condition);
        if (!empty($feegroups)) {
            $query = "SELECT IFNULL(student_fees_deposite.id, 0) as student_fees_deposite_id, IFNULL(student_fees_deposite.fee_groups_feetype_id, 0) as fee_groups_feetype_id, IFNULL(student_fees_deposite.amount_detail, 0) as amount_detail, student_fees_master.id as `fee_master_id`, student_fees_master.amount as `fee_master_amount`,fee_groups_feetype.feetype_id ,fee_groups_feetype.amount,fee_groups_feetype.due_date, `classes`.`id` AS `class_id`, `student_session`.`id` as `student_session_id`, `students`.`id`, `classes`.`class`, `sections`.`id` AS `section_id`, `sections`.`section`, `students`.`id`, `students`.`admission_no`, `students`.`roll_no`, `students`.`admission_date`, `students`.`firstname`,`students`.`middlename`, `students`.`lastname`, `students`.`image`, `students`.`mobileno`, `students`.`email`, `students`.`state`, `students`.`city`, `students`.`pincode`, `students`.`religion`, `students`.`dob`, `students`.`current_address`, `students`.`permanent_address`, IFNULL(students.category_id, 0) as `category_id`, IFNULL(categories.category, '') as `category`, `students`.`adhar_no`, `students`.`samagra_id`, `students`.`bank_account_no`, `students`.`bank_name`, `students`.`ifsc_code`, `students`.`guardian_name`, `students`.`guardian_relation`, `students`.`guardian_phone`, `students`.`guardian_address`, `students`.`is_active`, `students`.`created_at`, `students`.`updated_at`, `students`.`father_name`, `students`.`rte`, `students`.`gender`,fee_groups.name as `fee_group` ,feetype.type as `fee_type`,feetype.code as `fee_code`,fee_groups.is_system FROM `student_fees_master` INNER JOIN fee_session_groups on fee_session_groups.id= student_fees_master.fee_session_group_id INNER JOIN fee_groups on fee_groups.id=fee_session_groups.fee_groups_id INNER JOIN fee_groups_feetype on fee_groups_feetype.fee_session_group_id=student_fees_master.fee_session_group_id and fee_groups_feetype.id in (" . $fee_groups_feetypes . ")INNER JOIN feetype on feetype.id=fee_groups_feetype.feetype_id LEFT JOIN student_fees_deposite on student_fees_deposite.student_fees_master_id=student_fees_master.id and student_fees_deposite.fee_groups_feetype_id=fee_groups_feetype.id INNER JOIN student_session on student_session.id=student_fees_master.student_session_id INNER JOIN students on students.id=student_session.student_id JOIN `classes` ON `student_session`.`class_id` = `classes`.`id` JOIN `sections` ON `sections`.`id` = `student_session`.`section_id` LEFT JOIN `categories` ON `students`.`category_id` = `categories`.`id` WHERE student_fees_master.fee_session_group_id in (" . $feegroups . ") AND 
            `students`.`is_active` = 'yes'  " . $where_condition_string . " ORDER BY student_fees_master.id asc";

            $query = $this->db->query($query);
            $result_value = $query->result_array();
        } else {
            $result_value = array();
        }

        if (empty($result_value)) {
            $result_value2 = $result_value1;
        } elseif (empty($result_value1)) {
            $result_value2 = $result_value;
        } else {
            $result_value2 = array_merge($result_value, $result_value1);
        }

        return $result_value2;
    }

    public function getDueStudentFeesByDateClassSection($class_id = NULL, $section_id = NULL, $date = NULL)
    {

        $where_condition = array();
        if ($class_id != NULL) {
            $where_condition[] = " AND student_session.class_id=" . $class_id;
        }
        if ($section_id != NULL) {
            $where_condition[] = "student_session.section_id=" . $section_id;
        }
        if ($date != NULL) {
            $where_condition[] = "fee_groups_feetype.due_date < " . $this->db->escape($date);
        }

        $where_condition_string = implode(" AND ", $where_condition);

        $query = "SELECT student_fees_master.amount as `previous_balance_amount`,IFNULL(student_fees_deposite.id, 0) as student_fees_deposite_id, IFNULL(student_fees_deposite.fee_groups_feetype_id, 0) as fee_groups_feetype_id, IFNULL(student_fees_deposite.amount_detail, 0) as amount_detail, student_fees_master.id as `fee_master_id`,fee_groups_feetype.feetype_id ,fee_groups_feetype.amount,fee_groups_feetype.due_date, `classes`.`id` AS `class_id`, `student_session`.`id` as `student_session_id`, `students`.`id`, `classes`.`class`, `sections`.`id` AS `section_id`, `sections`.`section`, `students`.`id`, `students`.`admission_no`, `students`.`roll_no`, `students`.`admission_date`, `students`.`firstname`,`students`.`middlename`, `students`.`lastname`, `students`.`image`, `students`.`mobileno`, `students`.`email`, `students`.`state`, `students`.`city`, `students`.`pincode`, `students`.`religion`, `students`.`dob`, `students`.`current_address`, `students`.`permanent_address`, IFNULL(students.category_id, 0) as `category_id`, IFNULL(categories.category, '') as `category`, `students`.`adhar_no`, `students`.`samagra_id`, `students`.`bank_account_no`, `students`.`bank_name`, `students`.`ifsc_code`, `students`.`guardian_name`, `students`.`guardian_relation`, `students`.`guardian_phone`, `students`.`guardian_address`, `students`.`is_active`, `students`.`created_at`, `students`.`updated_at`, `students`.`father_name`, `students`.`rte`, `students`.`gender`,fee_groups.name as `fee_group` ,feetype.type as `fee_type`,feetype.code as `fee_code` , fee_groups.is_system FROM `student_fees_master` INNER JOIN fee_session_groups on fee_session_groups.id= student_fees_master.fee_session_group_id INNER JOIN fee_groups on fee_groups.id=fee_session_groups.fee_groups_id INNER JOIN fee_groups_feetype on fee_groups_feetype.fee_session_group_id=student_fees_master.fee_session_group_id  INNER JOIN feetype on feetype.id=fee_groups_feetype.feetype_id LEFT JOIN student_fees_deposite on student_fees_deposite.student_fees_master_id=student_fees_master.id and student_fees_deposite.fee_groups_feetype_id=fee_groups_feetype.id INNER JOIN student_session on student_session.id=student_fees_master.student_session_id INNER JOIN students on students.id=student_session.student_id JOIN `classes` ON `student_session`.`class_id` = `classes`.`id` JOIN `sections` ON `sections`.`id` = `student_session`.`section_id` LEFT JOIN `categories` ON `students`.`category_id` = `categories`.`id` WHERE `students`.`is_active` = 'yes'  " . $where_condition_string . " ORDER BY student_fees_master.id asc";

        $query = $this->db->query($query);
        $result_value = $query->result_array();
        $result_value1 = array();
        $module = $this->module_model->getPermissionByModulename('transport');
        if ($module['is_active']) {
            $this->db->select('`student_fees_deposite`.*,IFNULL(student_fees_deposite.amount_detail, 0) as amount_detail,0 as previous_balance_amount,route_pickup_point.fees as amount,students.firstname,students.middlename,students.lastname,student_session.class_id,classes.class,sections.section,student_session.section_id,student_session.student_id,"" as fee_group,"Transport Fees" as name, "Transport Fees" as `fee_type`, transport_feemaster.month as `fee_code`,0 as is_system,student_transport_fees.student_session_id,students.admission_no, `student_session`.`id` as `student_session_id`,0 as is_system')->from('student_transport_fees');

            $this->db->join('student_fees_deposite', 'student_transport_fees.id = `student_fees_deposite`.`student_transport_fee_id`', 'left');
            $this->db->join('transport_feemaster', '`student_transport_fees`.`transport_feemaster_id` = `transport_feemaster`.`id`', 'left');
            $this->db->join('student_session', 'student_session.id= `student_transport_fees`.`student_session_id`', 'INNER');
            $this->db->join('route_pickup_point', 'route_pickup_point.id = student_transport_fees.route_pickup_point_id');

            $this->db->join('classes', 'classes.id= student_session.class_id');
            $this->db->join('sections', 'sections.id= student_session.section_id');
            $this->db->join('students', 'students.id=student_session.student_id');

            $this->db->where('student_session.session_id', $this->current_session);
            $this->db->order_by('student_fees_deposite.id', 'desc');

            if ($class_id != null) {
                $this->db->where('student_session.class_id', $class_id);
            }

            if ($section_id != null) {
                $this->db->where('student_session.section_id', $section_id);
            }
            if ($date != null) {
                $this->db->where('transport_feemaster.due_date <', ($date));
            }
            $query1        = $this->db->get();
            $result_value1 = $query1->result_array();
        } else {
            $result_value1 = array();
        }
        if (empty($result_value)) {
            $result_value2 = $result_value1;
        } elseif (empty($result_value1)) {
            $result_value2 = $result_value;
        } else {
            $result_value2 = array_merge($result_value, $result_value1);
        }

        return $result_value2;
    }

    public function getDueStudentFees($feegroup_id = null, $fee_groups_feetype_id = null, $class_id = NULL, $section_id = NULL)
    {
        $where_condition = array();
        if ($class_id != NULL) {
            $where_condition[] = " AND student_session.class_id=" . $class_id;
        }
        if ($section_id != NULL) {
            $where_condition[] = "student_session.section_id=" . $section_id;
        }

        $where_condition_string = implode(" AND ", $where_condition);

        $query = "SELECT IFNULL(student_fees_deposite.id, 0) as student_fees_deposite_id, IFNULL(student_fees_deposite.fee_groups_feetype_id, 0) as fee_groups_feetype_id, IFNULL(student_fees_deposite.amount_detail, 0) as amount_detail, student_fees_master.id as `fee_master_id`,fee_groups_feetype.feetype_id ,fee_groups_feetype.amount,fee_groups_feetype.due_date, `classes`.`id` AS `class_id`, `student_session`.`id` as `student_session_id`, `students`.`id`, `classes`.`class`, `sections`.`id` AS `section_id`, `sections`.`section`, `students`.`id`, `students`.`admission_no`, `students`.`roll_no`, `students`.`admission_date`, `students`.`firstname`,`students`.`middlename`, `students`.`lastname`, `students`.`image`, `students`.`mobileno`, `students`.`email`, `students`.`state`, `students`.`city`, `students`.`pincode`, `students`.`religion`, `students`.`dob`, `students`.`current_address`, `students`.`permanent_address`, IFNULL(students.category_id, 0) as `category_id`, IFNULL(categories.category, '') as `category`, `students`.`adhar_no`, `students`.`samagra_id`, `students`.`bank_account_no`, `students`.`bank_name`, `students`.`ifsc_code`, `students`.`guardian_name`, `students`.`guardian_relation`, `students`.`guardian_phone`, `students`.`guardian_address`, `students`.`is_active`, `students`.`created_at`, `students`.`updated_at`, `students`.`father_name`, `students`.`rte`, `students`.`gender` FROM `students` JOIN `student_session` ON `student_session`.`student_id` = `students`.`id` JOIN `classes` ON `student_session`.`class_id` = `classes`.`id` JOIN `sections` ON `sections`.`id` = `student_session`.`section_id` LEFT JOIN `categories` ON `students`.`category_id` = `categories`.`id` INNER JOIN student_fees_master on student_fees_master.student_session_id=student_session.id and student_fees_master.fee_session_group_id=" . $this->db->escape($feegroup_id) . " LEFT JOIN student_fees_deposite on student_fees_deposite.student_fees_master_id=student_fees_master.id and student_fees_deposite.fee_groups_feetype_id=" . $this->db->escape($fee_groups_feetype_id) . "  INNER JOIN fee_groups_feetype on fee_groups_feetype.id = " . $this->db->escape($fee_groups_feetype_id) . " WHERE `student_session`.`session_id` = " . $this->current_session . " AND 
            `students`.`is_active` = 'yes' " . $where_condition_string . " ORDER BY `students`.`id`";
        $query = $this->db->query($query);
        return $query->result_array();
    }

    public function getDueFeeBystudent($class_id = null, $section_id = null, $student_id = null)
    {
        $query = "SELECT feemasters.id as feemastersid, feemasters.amount as amount,IFNULL(student_fees.id, 'xxx') as invoiceno,IFNULL(student_fees.amount_discount, 'xxx') as discount,IFNULL(student_fees.amount_fine, 'xxx') as fine,IFNULL(student_fees.payment_mode, 'xxx') as payment_mode,IFNULL(student_fees.date, 'xxx') as date,feetype.type ,feecategory.category,student_fees.description FROM feemasters LEFT JOIN (select student_fees.id,student_fees.feemaster_id,student_fees.payment_mode,student_fees.amount_fine,student_fees.amount_discount,student_fees.date,student_fees.student_session_id,student_fees.description  from student_fees , student_session where student_fees.student_session_id=student_session.id and student_session.student_id=" . $this->db->escape($student_id) . " and student_session.class_id=" . $this->db->escape($class_id) . " and student_session.section_id=" . $this->db->escape($section_id) . ") as student_fees ON student_fees.feemaster_id=feemasters.id JOIN feetype ON feemasters.feetype_id = feetype.id JOIN feecategory ON feetype.feecategory_id = feecategory.id  where  feemasters.class_id=" . $this->db->escape($class_id) . " and feemasters.session_id=" . $this->db->escape($this->current_session);
        $query = $this->db->query($query);
        return $query->result_array();
    }

    public function getDueFeeBystudentSection($class_id = null, $section_id = null, $student_session_id = null)
    {
        $query = "SELECT feemasters.id as feemastersid, feemasters.amount as amount,IFNULL(student_fees.id, 'xxx') as invoiceno,IFNULL(student_fees.amount_discount, 'xxx') as discount,IFNULL(student_fees.amount_fine, 'xxx') as fine, IFNULL(student_fees.date, 'xxx') as date,feetype.type ,feecategory.category FROM feemasters LEFT JOIN (select student_fees.id,student_fees.feemaster_id,student_fees.amount_fine,student_fees.amount_discount,student_fees.date,student_fees.student_session_id from student_fees , student_session where student_fees.student_session_id=student_session.id and student_session.id=" . $this->db->escape($student_session_id) . " ) as student_fees ON student_fees.feemaster_id=feemasters.id LEFT JOIN feetype ON feemasters.feetype_id = feetype.id LEFT JOIN feecategory ON feetype.feecategory_id = feecategory.id  where  feemasters.class_id=" . $this->db->escape($class_id) . " and feemasters.session_id=" . $this->db->escape($this->current_session);
        $query = $this->db->query($query);
        return $query->result_array();
    }

    public function getFeesByClass($class_id = null, $section_id = null, $student_id = null)
    {
        $query = "SELECT feemasters.id as feemastersid, feemasters.amount as amount,IFNULL(student_fees.id, 'xxx') as invoiceno,IFNULL(student_fees.amount_discount, 'xxx') as discount,IFNULL(student_fees.amount_fine, 'xxx') as fine, IFNULL(student_fees.date, 'xxx') as date,feetype.type ,feecategory.category FROM feemasters LEFT JOIN (select student_fees.id,student_fees.feemaster_id,student_fees.amount_fine,student_fees.amount_discount,student_fees.date,student_fees.student_session_id from student_fees , student_session where student_fees.student_session_id=student_session.id and student_session.student_id=" . $this->db->escape($student_id) . " and student_session.class_id=" . $this->db->escape($class_id) . " and student_session.section_id=" . $this->db->escape($section_id) . ") as student_fees ON student_fees.feemaster_id=feemasters.id LEFT JOIN feetype ON feemasters.feetype_id = feetype.id LEFT JOIN feecategory ON feetype.feecategory_id = feecategory.id  where  feemasters.class_id=" . $this->db->escape($class_id) . " and feemasters.session_id=" . $this->db->escape($this->current_session);
        $query = $this->db->query($query);
        return $query->result_array();
    }

    public function getFeeBetweenDate($start_date, $end_date)
    {

        $this->db->select('student_fees.date,student_fees.id,student_fees.amount,student_fees.amount_discount,student_fees.amount_fine,student_fees.created_at,students.rte,classes.class,sections.section,students.firstname,students.middlename,students.lastname,students.admission_no,students.roll_no,students.dob,students.guardian_name,feetype.type')->from('student_fees');
        $this->db->join('student_session', 'student_session.id = student_fees.student_session_id');
        $this->db->join('feemasters', 'feemasters.id = student_fees.feemaster_id');
        $this->db->join('feetype', 'feetype.id = feemasters.feetype_id');
        $this->db->join('classes', 'student_session.class_id = classes.id');
        $this->db->join('sections', 'sections.id = student_session.section_id');
        $this->db->join('students', 'students.id = student_session.student_id');
        $this->db->where('student_fees.date >=', $start_date);
        $this->db->where('student_fees.date <=', $end_date);
        $this->db->where('student_session.session_id', $this->current_session);
        $this->db->order_by('student_fees.id');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getStudentTotalFee($class_id, $student_session_id)
    {
        $query = "SELECT a.totalfee,b.fee_deposit,b.payment_mode  FROM ( SELECT COALESCE(sum(amount),0) as totalfee FROM `feemasters` WHERE session_id =$this->current_session and class_id=" . $this->db->escape($class_id) . ") as a, (select COALESCE(sum(amount),0) as fee_deposit,payment_mode from student_fees WHERE student_session_id =" . $this->db->escape($student_session_id) . ") as b";
        $query = $this->db->query($query);
        return $query->row();
    }

    /**25062025 - Due Fees Report */
    /* public function getDueFeesReport($class_id = null, $feetype_id = null, $session_id = null)
    {
        $paid_subquery = "(SELECT student_fees_management_id, SUM(paid_amount) as paid_amount 
						FROM student_fees_collections 
						WHERE is_refunded = 0 
						GROUP BY student_fees_management_id) paid_sub";

        $this->datatables
            ->select('
                s.id as student_id,
                CONCAT(s.firstname, " (", s.admission_no, ")") as student,
                c.class,
                ss.session,
                GROUP_CONCAT(
                    DISTINCT IF(
                        sfm.discounted_fees > IFNULL(paid_sub.paid_amount, 0),
                        ft.type,
                        NULL
                    ) ORDER BY ft.type SEPARATOR ", "
                ) as fee_types,
                SUM(sfm.discounted_fees) as total_assigned,
                SUM(IFNULL(paid_sub.paid_amount, 0)) as total_paid,
                (SUM(sfm.discounted_fees) - SUM(IFNULL(paid_sub.paid_amount, 0))) as total_due,
                s.id as student_id_link
            ')
            ->from('student_fees_management sfm')
            ->join("$paid_subquery", 'paid_sub.student_fees_management_id = sfm.id', 'left')
            ->join('students s', 'sfm.student_id = s.id', 'left')
            ->join('classes c', 'sfm.class_id = c.id', 'left')
            ->join('sessions ss', 'sfm.session_id = ss.id', 'left')
            ->join('feetype ft', 'sfm.feetype_id = ft.id', 'left')
            ->group_by('sfm.student_id, sfm.class_id, sfm.session_id')
            ->having('total_due >', 0)
            ->searchable('s.firstname, s.admission_no, c.class, ss.session, ft.type');

        $this->datatables->where('sfm.status', 1);

        if (!empty($class_id)) {
            $this->datatables->where('sfm.class_id', $class_id);
        }
        if (!empty($feetype_id)) {
            $this->datatables->where('sfm.feetype_id', $feetype_id);
        }
        if (!empty($session_id)) {
            $this->datatables->where('sfm.session_id', $session_id);
        }

        return $this->datatables->generate('json');
    } */

    public function getStudentDueFee($student_id, $session_id)
    {
        $paid_subquery = "(SELECT student_fees_management_id, SUM(paid_amount) as paid_amount FROM student_fees_collections WHERE is_refunded = 0 AND status = 1 GROUP BY student_fees_management_id) paid_sub";

        $this->db->select('(SUM(sfm.discounted_fees) - SUM(IFNULL(paid_sub.paid_amount, 0))) as total_due');
        $this->db->from('student_fees_management sfm');
        $this->db->join($paid_subquery, 'paid_sub.student_fees_management_id = sfm.id', 'left');
        $this->db->where('sfm.student_id', $student_id);
        $this->db->where('sfm.session_id', $session_id);
        $this->db->where('sfm.status', 1);
        $query = $this->db->get();
        $result = $query->row();
        return $result->total_due;
    }

    public function getStudentTotalFeeAmount($student_id, $session_id)
    {
        $this->db->select_sum('discounted_fees', 'total_amount');
        $this->db->from('student_fees_management');
        $this->db->where('student_id', $student_id);
        $this->db->where('session_id', $session_id);
        $this->db->where('status', 1);

        return $this->db->get()->row()->total_amount;
    }

    public function getStudentConcessionAmount($student_session_id)
    {
        if (empty($student_session_id)) {
            return 0;
        }

        $details = $this->getConcessionStudentReportDetails($student_session_id);

        $total_discount_amount = 0;
        foreach ($details as $detail) {
            if ((int) $detail['is_skipped'] === 0) {
                $total_discount_amount += (float) $detail['discount_amount'];
            }
        }

        return $total_discount_amount;
    }

    public function getStudentFeePendingApproval($student_id, $session_id)
    {
        $this->db->select_sum('paid_amount', 'total_paid');
        $this->db->from('student_fees_collections');
        $this->db->where('student_id', $student_id);
        $this->db->where('session_id', $session_id);
        $this->db->where('is_refunded', 0);
        $this->db->where('status', 2);

        return $this->db->get()->row()->total_paid;
    }

    public function getDueFeesReport($class_id = null, $feetype_id = null, $session_id = null)
    {
        $paid_subquery = "(SELECT student_fees_management_id, SUM(paid_amount) as paid_amount FROM student_fees_collections WHERE is_refunded = 0 AND status = 1 GROUP BY student_fees_management_id) paid_sub";

        $due_fees_subquery = "(SELECT 
                                        sfm.student_id,
                                        sfm.class_id,
                                        sfm.session_id,
                                        SUM(sfm.discounted_fees) AS total_assigned,
                                        SUM(IFNULL(paid_sub.paid_amount, 0)) AS total_paid,
                                        (SUM(sfm.discounted_fees) - SUM(IFNULL(paid_sub.paid_amount, 0))) AS total_due
                                    FROM student_fees_management sfm
                                    LEFT JOIN $paid_subquery ON paid_sub.student_fees_management_id = sfm.id
                                    WHERE sfm.status = 1
                                    GROUP BY sfm.student_id, sfm.class_id, sfm.session_id
                                    HAVING total_due > 0
                                ) as due_summary";

        $this->datatables
            ->select('
                s.id as student_id,
                s.id as reg_no,
                s.firstname,
                s.middlename,
                s.lastname,
                CONCAT(s.firstname, " ", IFNULL(s.middlename, ""), " ", s.lastname) as student_name,
                c.class, ss.roll_no, ss.session_id, sections.section, ssn.session,
                IFNULL(ss.recommendationNumber, s.recommendationNumber) as recommendationNumber,
                GROUP_CONCAT(
                    DISTINCT IF(
                        sfm.discounted_fees > IFNULL(paid_sub.paid_amount, 0),
                        ft.type,
                        NULL
                    ) ORDER BY sfm.id ASC SEPARATOR ", "
                ) as fee_types,
                due_summary.total_assigned,
                due_summary.total_paid,
                due_summary.total_due,
                s.id as student_id_link
            ')
            ->searchable('s.id,s.firstname,s.admission_no')
            ->orderable('s.firstname')
            ->from('student_fees_management sfm')
            ->join("$paid_subquery", 'paid_sub.student_fees_management_id = sfm.id', 'left')
            ->join("$due_fees_subquery", 'sfm.student_id = due_summary.student_id AND sfm.class_id = due_summary.class_id AND sfm.session_id = due_summary.session_id', 'inner')
            ->join('students s', 'sfm.student_id = s.id', 'left')
            ->join('student_session ss', 'ss.student_id = s.id AND ss.session_id = sfm.session_id')
            ->join('classes c', 'sfm.class_id = c.id', 'left')
            ->join('sections', 'sections.id = ss.section_id')
            ->join('sessions ssn', 'sfm.session_id = ssn.id', 'left')
            ->join('feetype ft', 'sfm.feetype_id = ft.id', 'left')
            ->group_by('sfm.student_id, sfm.class_id, sfm.session_id');

        $this->datatables->where('sfm.status', 1);

        if (!empty($class_id)) {
            $this->datatables->where('sfm.class_id', $class_id);
        }
        if (!empty($feetype_id)) {
            $this->datatables->where_in('sfm.feetype_id', $feetype_id);
        }
        if (!empty($session_id)) {
            $this->datatables->where('sfm.session_id', $session_id);
        }

        return $this->datatables->generate('json');
    }

    public function getConcessionStudentReport($session_id = null, $class_id = null, $section_id = null, $free_type = '')
    {
        $admission_fee_subquery = "
            SELECT
                sfm_inner.student_session_id,
                CASE
                    WHEN SUM(CASE WHEN ft_inner.type = 'NEW ADMISSION' AND sfm_inner.discounted_fees > 0 THEN 1 ELSE 0 END) > 0 THEN 'NEW ADMISSION'
                    WHEN SUM(CASE WHEN ft_inner.type = 'RE- ADMISSION' AND sfm_inner.discounted_fees > 0 THEN 1 ELSE 0 END) > 0 THEN 'RE- ADMISSION'
                    ELSE NULL
                END AS admission_fee_type
            FROM student_fees_management sfm_inner
            LEFT JOIN feetype ft_inner ON ft_inner.id = sfm_inner.feetype_id
            GROUP BY sfm_inner.student_session_id
        ";
        $fee_mix_subquery = "
            SELECT
                sfm_mix.student_session_id,
                SUM(CASE WHEN sfm_mix.is_monthly = 0 THEN 1 ELSE 0 END) as total_admission_count,
                SUM(CASE WHEN sfm_mix.is_monthly = 1 THEN 1 ELSE 0 END) as total_monthly_count,
                SUM(CASE WHEN sfm_mix.is_monthly = 1 AND sfm_mix.is_skipped = 1 THEN 1 ELSE 0 END) as skipped_monthly_count,
                SUM(CASE WHEN sfm_mix.is_monthly = 1 AND sfm_mix.is_skipped = 0 THEN 1 ELSE 0 END) as active_monthly_count,
                SUM(CASE WHEN sfm_mix.is_monthly = 0 AND sfm_mix.is_skipped = 1 AND UPPER(ft_mix.type) LIKE '%NEW ADMISSION%' THEN 1 ELSE 0 END) as skipped_new_admission_count,
                SUM(CASE WHEN sfm_mix.is_monthly = 0 AND sfm_mix.is_skipped = 1 AND UPPER(ft_mix.type) LIKE '%RE- ADMISSION%' THEN 1 ELSE 0 END) as skipped_re_admission_count
            FROM student_fees_management sfm_mix
            LEFT JOIN feetype ft_mix ON ft_mix.id = sfm_mix.feetype_id
            GROUP BY sfm_mix.student_session_id
        ";

        $this->db->select('
            ses.id as session_id,
            ses.session as session_name,
            sfm.student_session_id,
            s.id as student_id,
            CONCAT(s.firstname, " ", IFNULL(s.middlename, ""), " ", s.lastname) as student_name,
            ss.roll_no,
            sec.id as section_id,
            sec.section,
            IFNULL(NULLIF(ss.recommendationNumber, ""), NULLIF(s.recommendationNumber, "")) as recommendation_number,
            c.id as class_id,
            c.class,
            s.gender,
            COALESCE(NULLIF(s.mobileno, ""), NULLIF(s.guardian_phone, "")) as phone,
            SUM(CASE WHEN sfm.is_skipped = 0 THEN (fmcw.fees_amount - sfm.discounted_fees) ELSE 0 END) as total_discount_amount,
            SUM(CASE WHEN sfm.is_skipped = 0 AND sfm.is_monthly = 1 THEN (fmcw.fees_amount - sfm.discounted_fees) ELSE 0 END) as monthly_discount_amount,
            SUM(CASE WHEN sfm.is_skipped = 0 AND sfm.is_monthly = 0 THEN (fmcw.fees_amount - sfm.discounted_fees) ELSE 0 END) as admission_discount_amount,
            IFNULL(fm.total_admission_count, 0) as total_admission_count,
            IFNULL(fm.total_monthly_count, 0) as total_monthly_count,
            IFNULL(fm.skipped_monthly_count, 0) as skipped_monthly_count,
            IFNULL(fm.active_monthly_count, 0) as active_monthly_count,
            IFNULL(fm.skipped_new_admission_count, 0) as skipped_new_admission_count,
            IFNULL(fm.skipped_re_admission_count, 0) as skipped_re_admission_count,
            GROUP_CONCAT(
                CASE
                    WHEN sfm.is_skipped = 0 THEN CONCAT(
                        ft.type,
                        ": ",
                        ROUND((fmcw.fees_amount - sfm.discounted_fees), 2)
                    )
                    ELSE NULL
                END
                ORDER BY sfm.is_monthly ASC, ft.id ASC
                SEPARATOR "<br>"
            ) as active_discount_breakdown
        ');
        $this->db->from('student_fees_management sfm');
        $this->db->join('fees_master_class_wise fmcw', 'fmcw.class_id = sfm.class_id AND fmcw.session_id = sfm.session_id AND fmcw.feetype_id = sfm.feetype_id', 'inner');
        $this->db->join('student_session ss', 'ss.id = sfm.student_session_id', 'inner');
        $this->db->join('students s', 's.id = sfm.student_id', 'inner');
        $this->db->join('classes c', 'c.id = sfm.class_id', 'left');
        $this->db->join('sections sec', 'sec.id = ss.section_id', 'left');
        $this->db->join('sessions ses', 'ses.id = sfm.session_id', 'left');
        $this->db->join('feetype ft', 'ft.id = sfm.feetype_id', 'left');
        $this->db->join('(' . $admission_fee_subquery . ') af', 'af.student_session_id = sfm.student_session_id', 'left', false);
        $this->db->join('(' . $fee_mix_subquery . ') fm', 'fm.student_session_id = sfm.student_session_id', 'left', false);
        $this->db->where('sfm.status', 1);
        $this->db->where_in('sfm.is_monthly', array(0, 1));
        $this->db->where_in('fmcw.is_monthly', array(0, 1));
        $this->db->where('(fmcw.status IS NULL OR fmcw.status = 1)', null, false);
        $this->db->where('(sfm.is_skipped = 1 OR sfm.discounted_fees < fmcw.fees_amount)', null, false);
        $this->db->where('(af.admission_fee_type IS NULL OR (af.admission_fee_type = "NEW ADMISSION" AND ft.type <> "RE- ADMISSION") OR (af.admission_fee_type = "RE- ADMISSION" AND ft.type <> "NEW ADMISSION"))', null, false);
        $this->db->where('COALESCE(NULLIF(ss.recommendationNumber, ""), NULLIF(s.recommendationNumber, "")) IS NOT NULL', null, false);

        if (!empty($session_id)) {
            $this->db->where('sfm.session_id', $session_id);
        }
        if (!empty($class_id)) {
            $this->db->where('sfm.class_id', $class_id);
        }
        if (!empty($section_id)) {
            $this->db->where('ss.section_id', $section_id);
        }

        $this->db->group_by('ses.id, ses.session, sfm.student_session_id, s.id, s.firstname, s.middlename, s.lastname, ss.roll_no, sec.id, sec.section, ss.recommendationNumber, s.recommendationNumber, c.id, c.class, s.gender, s.mobileno, s.guardian_phone');
        $this->db->order_by('ses.id', 'DESC');
        $this->db->order_by('c.id', 'ASC');
        $this->db->order_by('sec.id', 'ASC');
        $this->db->order_by('ss.roll_no', 'ASC');

        $result = $this->db->get()->result_array();

        foreach ($result as &$row) {
            $row['free_status'] = $this->getConcessionFreeStatusFromRow($row);
            $active_breakdown = trim((string) ($row['active_discount_breakdown'] ?? ''));

            if ($row['free_status'] === 'fully_free') {
                $row['discount_breakdown'] = 'Fully Free';
            } elseif ($row['free_status'] === 'admission_free') {
                $row['discount_breakdown'] = !empty($active_breakdown)
                    ? 'Admission Free<br>' . $active_breakdown
                    : 'Admission Free';
            } elseif ($row['free_status'] === 'monthly_free') {
                $row['discount_breakdown'] = !empty($active_breakdown)
                    ? 'Monthly Free<br>' . $active_breakdown
                    : 'Monthly Free';
            } else {
                $row['discount_breakdown'] = $active_breakdown;
            }
        }
        unset($row);

        return $this->filterConcessionAdmissionFeeRows($result, $free_type);
    }

    public function getConcessionStudentReportDetails($student_session_id)
    {
        $fee_mix_subquery = "
            SELECT
                sfm_mix.student_session_id,
                SUM(CASE WHEN sfm_mix.is_monthly = 0 THEN 1 ELSE 0 END) as total_admission_count,
                SUM(CASE WHEN sfm_mix.is_monthly = 1 THEN 1 ELSE 0 END) as total_monthly_count,
                SUM(CASE WHEN sfm_mix.is_monthly = 1 AND sfm_mix.is_skipped = 1 THEN 1 ELSE 0 END) as skipped_monthly_count,
                SUM(CASE WHEN sfm_mix.is_monthly = 0 AND sfm_mix.is_skipped = 1 AND UPPER(ft_mix.type) LIKE '%NEW ADMISSION%' THEN 1 ELSE 0 END) as skipped_new_admission_count,
                SUM(CASE WHEN sfm_mix.is_monthly = 0 AND sfm_mix.is_skipped = 1 AND UPPER(ft_mix.type) LIKE '%RE- ADMISSION%' THEN 1 ELSE 0 END) as skipped_re_admission_count
            FROM student_fees_management sfm_mix
            LEFT JOIN feetype ft_mix ON ft_mix.id = sfm_mix.feetype_id
            GROUP BY sfm_mix.student_session_id
        ";
        $this->db->select('
            ses.id as session_id,
            ses.session as session_name,
            sfm.student_session_id,
            s.id as student_id,
            CONCAT(s.firstname, " ", IFNULL(s.middlename, ""), " ", s.lastname) as student_name,
            ss.roll_no,
            sec.section,
            c.class,
            ss.admission_no,
            IFNULL(NULLIF(ss.recommendationNumber, ""), NULLIF(s.recommendationNumber, "")) as recommendation_number,
            COALESCE(NULLIF(s.mobileno, ""), NULLIF(s.guardian_phone, "")) as phone,
            ft.type as fee_type,
            sfm.is_monthly,
            sfm.is_skipped,
            IFNULL(fm.total_admission_count, 0) as total_admission_count,
            IFNULL(fm.total_monthly_count, 0) as total_monthly_count,
            IFNULL(fm.skipped_monthly_count, 0) as skipped_monthly_count,
            IFNULL(fm.skipped_new_admission_count, 0) as skipped_new_admission_count,
            IFNULL(fm.skipped_re_admission_count, 0) as skipped_re_admission_count,
            fmcw.fees_amount as standard_fee,
            sfm.discounted_fees as student_fee,
            CASE
                WHEN sfm.is_skipped = 1 THEN 0
                ELSE (fmcw.fees_amount - sfm.discounted_fees)
            END as discount_amount
        ');
        $this->db->from('student_fees_management sfm');
        $this->db->join('fees_master_class_wise fmcw', 'fmcw.class_id = sfm.class_id AND fmcw.session_id = sfm.session_id AND fmcw.feetype_id = sfm.feetype_id', 'inner');
        $this->db->join('student_session ss', 'ss.id = sfm.student_session_id', 'inner');
        $this->db->join('students s', 's.id = sfm.student_id', 'inner');
        $this->db->join('classes c', 'c.id = sfm.class_id', 'left');
        $this->db->join('sections sec', 'sec.id = ss.section_id', 'left');
        $this->db->join('sessions ses', 'ses.id = sfm.session_id', 'left');
        $this->db->join('feetype ft', 'ft.id = sfm.feetype_id', 'left');
        $this->db->join('(' . $fee_mix_subquery . ') fm', 'fm.student_session_id = sfm.student_session_id', 'left', false);
        $this->db->where('sfm.student_session_id', $student_session_id);
        $this->db->where('sfm.status', 1);
        $this->db->where_in('sfm.is_monthly', array(0, 1));
        $this->db->where_in('fmcw.is_monthly', array(0, 1));
        $this->db->where('(fmcw.status IS NULL OR fmcw.status = 1)', null, false);
        $this->db->where('(sfm.is_skipped = 1 OR sfm.discounted_fees < fmcw.fees_amount)', null, false);
        $this->db->where('COALESCE(NULLIF(ss.recommendationNumber, ""), NULLIF(s.recommendationNumber, "")) IS NOT NULL', null, false);
        $this->db->order_by('sfm.is_monthly', 'ASC');
        $this->db->order_by('ft.id', 'ASC');

        $result = $this->db->get()->result_array();

        return $this->filterConcessionAdmissionFeeRows($result);
    }

    private function filterConcessionAdmissionFeeRows(array $rows, $free_type = '')
    {
        $admission_fee_type = $this->detectConcessionAdmissionFeeType($rows);

        $filtered_rows = array();
        foreach ($rows as $row) {
            $row_fee_type = $this->normalizeFeeTypeLabel($row['fee_type'] ?? '');
            $row_free_status = $this->getConcessionFreeStatusFromRow($row);

            if (!empty($free_type) && $row_free_status !== $free_type) {
                continue;
            }

            if ($admission_fee_type !== null) {
                if ($admission_fee_type === 'NEW ADMISSION' && $row_fee_type === 'RE- ADMISSION') {
                    continue;
                }

                if ($admission_fee_type === 'RE- ADMISSION' && $row_fee_type === 'NEW ADMISSION') {
                    continue;
                }
            }

            $filtered_rows[] = $row;
        }

        return $filtered_rows;
    }

    private function getConcessionFreeStatusFromRow(array $row)
    {
        $total_monthly_count = (int) ($row['total_monthly_count'] ?? 0);
        $skipped_monthly_count = (int) ($row['skipped_monthly_count'] ?? 0);
        $skipped_new_admission_count = (int) ($row['skipped_new_admission_count'] ?? 0);
        $skipped_re_admission_count = (int) ($row['skipped_re_admission_count'] ?? 0);

        $fully_free = $skipped_new_admission_count > 0
            && $skipped_re_admission_count > 0
            && $total_monthly_count > 1
            && $skipped_monthly_count === $total_monthly_count;

        if ($fully_free) {
            return 'fully_free';
        }

        $monthly_free = $total_monthly_count > 0
            && $skipped_monthly_count === $total_monthly_count
            && $skipped_new_admission_count === 0
            && $skipped_re_admission_count === 0;

        if ($monthly_free) {
            return 'monthly_free';
        }

        if ($skipped_new_admission_count > 0 || $skipped_re_admission_count > 0) {
            return 'admission_free';
        }

        return '';
    }

    private function detectConcessionAdmissionFeeType(array $rows)
    {
        foreach ($rows as $row) {
            $row_fee_type = $this->normalizeFeeTypeLabel($row['fee_type'] ?? '');
            $student_fee = (float) ($row['student_fee'] ?? 0);

            if ($student_fee <= 0) {
                continue;
            }

            if ($row_fee_type === 'NEW ADMISSION') {
                return 'NEW ADMISSION';
            }

            if ($row_fee_type === 'RE- ADMISSION') {
                return 'RE- ADMISSION';
            }
        }

        return null;
    }

    private function normalizeFeeTypeLabel($fee_type)
    {
        $fee_type = strtoupper(trim((string) $fee_type));
        $fee_type = preg_replace('/\s+/', ' ', $fee_type);
        $fee_type = preg_replace('/\s*-\s*/', '- ', $fee_type);

        return $fee_type;
    }

    public function getPendingFees($date_from = '', $date_to = '', $class_id = '', $roll_no = '', $admission_no = '', $created_by_id = '', $collection_by_user_id = null)
    {
        $this->db->select('sfc.*, students.firstname, students.lastname, students.admission_no, students.roll_no, classes.class, sections.section, feetype.type as fee_type_name, sessions.session as session_name, CONCAT(staff.name, " ", staff.surname) as collected_by_name');
        $this->db->from('student_fees_collections sfc');
        $this->db->join('students', 'students.id = sfc.student_id');
        $this->db->join('classes', 'classes.id = sfc.class_id');
        $this->db->join('student_session', 'student_session.student_id = students.id AND student_session.session_id = sfc.session_id');
        $this->db->join('sections', 'sections.id = student_session.section_id');
        $this->db->join('feetype', 'feetype.id = sfc.feetype_id');
        $this->db->join('sessions', 'sessions.id = sfc.session_id');
        $this->db->join('staff', 'staff.id = sfc.collection_by', 'left'); // Join with staff table
        $this->db->where('sfc.status', 2);

        if (!empty($date_from)) {
            $this->db->where('sfc.collection_date >=', $date_from);
        }
        if (!empty($date_to)) {
            $this->db->where('sfc.collection_date <=', $date_to);
        }
        if (!empty($class_id)) {
            $this->db->where('sfc.class_id', $class_id);
        }
        if (!empty($roll_no)) {
            $this->db->where('students.roll_no', $roll_no);
        }
        if (!empty($admission_no)) {
            $this->db->where('students.admission_no', $admission_no);
        }
        if (!empty($created_by_id)) {
            $this->db->where('sfc.collection_by', $created_by_id);
        }
        if (!empty($collection_by_user_id)) {
            $this->db->where('sfc.collection_by', $collection_by_user_id);
        }

        $this->db->order_by('sfc.collection_date', 'asc');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function approveFee($id, $approved_date, $note)
    {
        $this->db->trans_start();
        $this->db->trans_strict(false);

        // Get fee details before updating status
        $this->db->where('id', $id);
        $query = $this->db->get('student_fees_collections');
        $fee_details = $query->row_array();

        if ($fee_details) {
            $approved_by = $this->session->userdata['admin']['id'];

            $this->db->where('id', $id);
            $this->db->update('student_fees_collections', [
                'status'        => 1,
                'approved_by'   => $approved_by, // or current user ID
                'approved_date' => $approved_date,
                'note' => $note
            ]);

            $message = UPDATE_RECORD_CONSTANT . " On student_fees_collections id " . $id . " status changed to approved.";
            $action = "Update";
            $record_id = $id;
            $this->log($message, $record_id, $action);

            // Load accounts_model if not already loaded (MY_Model might handle this, but explicit is safer)
            $this->load->model('accounts_model');

            // Add funds to the payment method
            $income_added = $this->accounts_model->add_funds(
                $fee_details['paid_amount'],
                $fee_details['payment_method_id'],
                'Payment approved for fee collection (ID: ' . $fee_details['payment_hash'] . ')',
                $approved_date, // Use the provided approved_date here
                'student_fees_collections',
                $id,
                1 // Status is now 1 (approved)
            );

            if (!$income_added) {
                // If adding funds failed, rollback the transaction
                $this->db->trans_rollback();
                return false;
            }
        } else {
            // Fee details not found, rollback
            $this->db->trans_rollback();
            return false;
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            return false;
        } else {
            return true;
        }
    }

    public function bulkApproveFees($fee_ids, $approved_date, $note)
    {
        $this->db->trans_start();
        $this->db->trans_strict(false);

        $this->load->model('accounts_model'); // Ensure accounts_model is loaded

        foreach ($fee_ids as $id) {
            // Get fee details before updating status
            $this->db->where('id', $id);
            $query = $this->db->get('student_fees_collections');
            $fee_details = $query->row_array();

            if ($fee_details) {

                $approved_by = $this->session->userdata['admin']['id'];

                $this->db->where('id', $id);
                $this->db->update('student_fees_collections', [
                    'status'        => 1,
                    'approved_by'   => $approved_by, // or current user ID
                    'approved_date' => $approved_date,
                    'note' => $note
                ]);

                $message = UPDATE_RECORD_CONSTANT . " On student_fees_collections id " . $id . " status changed to approved (bulk).";
                $action = "Update";
                $record_id = $id;
                $this->log($message, $record_id, $action);

                // Add funds to the payment method
                $income_added = $this->accounts_model->add_funds(
                    $fee_details['paid_amount'],
                    $fee_details['payment_method_id'],
                    'Payment approved for fee collection (ID: ' . $fee_details['payment_hash'] . ')',
                    $approved_date, // Use the provided approved_date here
                    'student_fees_collections',
                    $id,
                    1 // Status is now 1 (approved)
                );

                if (!$income_added) {
                    // If adding funds failed for any fee, rollback the entire transaction
                    $this->db->trans_rollback();
                    return false;
                }
            } else {
                // Fee details not found, rollback
                $this->db->trans_rollback();
                return false;
            }
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            return false;
        } else {
            return true;
        }
    }
    public function searchPendingFees($date_from = '', $date_to = '', $class_id = '', $created_by_id = '')
    {
        $this->datatables
            ->select('sfc.id, sfc.payment_hash, sfc.collection_date, students.id as reg_no, students.admission_no, students.firstname, students.lastname, students.roll_no, classes.class, sections.section, feetype.type as fee_type_name, sfc.paid_amount, CONCAT(staff.name, " ", staff.surname) as collected_by_name')
            ->from('student_fees_collections sfc')
            ->join('students', 'students.id = sfc.student_id')
            ->join('classes', 'classes.id = sfc.class_id')
            ->join('student_session', 'student_session.student_id = students.id AND student_session.session_id = sfc.session_id')
            ->join('sections', 'sections.id = student_session.section_id')
            ->join('feetype', 'feetype.id = sfc.feetype_id')
            ->join('staff', 'staff.id = sfc.collection_by', 'left')
            ->where('sfc.status', 2)
            ->searchable('sfc.payment_hash,students.admission_no,students.firstname,students.lastname,classes.class,feetype.type')
            ->orderable('sfc.collection_date,sfc.payment_hash, students.admission_no, students.firstname, classes.class, feetype.type, sfc.paid_amount');

        if (!empty($date_from)) {
            $this->datatables->where('sfc.collection_date >=', $date_from);
        }
        if (!empty($date_to)) {
            $this->datatables->where('sfc.collection_date <=', $date_to);
        }
        if (!empty($class_id)) {
            $this->datatables->where('sfc.class_id', $class_id);
        }
        if (!empty($created_by_id)) {
            $this->datatables->where('sfc.collection_by', $created_by_id);
        }

        return $this->datatables->generate('json');
    }
}
