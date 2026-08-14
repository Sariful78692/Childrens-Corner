<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

if (!function_exists('log_fees_setup')) {

    function log_fees_setup($data, $action, $user_id, $user_name)
    {
        $CI = &get_instance();
        $current_datetime = date("Y-m-d h:i");
        $insert_array = array(
            'fees_data' => json_encode($data),
            'action' => $action,
            'made_by_id' => $user_id,
            'made_by_name' => $user_name,
            'created_at' => $current_datetime
        );

        // Insert data into the 'log_fees_setup' table
        $CI->db->insert('log_fees_setup', $insert_array);
    }
}

if (!function_exists('log_income')) {

    function log_income($data, $action, $user_id, $user_name)
    {
        $CI = &get_instance();
        $current_datetime = date("Y-m-d h:i");
        $insert_array = array(
            'income_data' => json_encode($data),
            'action' => $action,
            'made_by_id' => $user_id,
            'made_by_name' => $user_name,
            'created_at' => $current_datetime
        );

        // Insert data into the 'log_income' table
        $CI->db->insert('log_income', $insert_array);
    }
}

if (!function_exists('log_expenses')) {

    function log_expenses($data, $action, $user_id, $user_name)
    {
        $CI = &get_instance();
        $current_datetime = date("Y-m-d h:i");
        $insert_array = array(
            'expense_data' => json_encode($data),
            'action' => $action,
            'made_by_id' => $user_id,
            'made_by_name' => $user_name,
            'created_at' => $current_datetime
        );

        // Insert data into the 'log_expenses' table
        $CI->db->insert('log_expenses', $insert_array);
    }
}

if (!function_exists('log_staff_payslip')) {

    function log_staff_payslip($data, $action, $user_id, $user_name)
    {
        $CI = &get_instance();
        $current_datetime = date("Y-m-d h:i");
        $insert_array = array(
            'payslip_data' => json_encode($data),
            'action' => $action,
            'made_by_id' => $user_id,
            'made_by_name' => $user_name,
            'created_at' => $current_datetime
        );

        // Insert data into the 'log_staff_payslip' table
        $CI->db->insert('log_staff_payslip', $insert_array);
    }
}


if (!function_exists('log_students')) {

    function log_students($data, $action, $user_id, $user_name)
    {
        $CI = &get_instance();
        $current_datetime = date("Y-m-d h:i");
        $insert_array = array(
            'student_data' => json_encode($data),
            'action' => $action,
            'made_by_id' => $user_id,
            'made_by_name' => $user_name,
            'created_at' => $current_datetime
        );

        // Insert data into the 'log_students' table
        $CI->db->insert('log_students', $insert_array);
    }
}

if (!function_exists('log_student_fees_management')) {

    function log_student_fees_management($data, $action, $user_id, $user_name)
    {
        $CI = &get_instance();
        $current_datetime = date("Y-m-d h:i");
        $insert_array = array(
            'student_fees_management_data' => json_encode($data),
            'action' => $action,
            'made_by_id' => $user_id,
            'made_by_name' => $user_name,
            'created_at' => $current_datetime
        );

        // Insert data into the 'log_student_fees_management' table
        $CI->db->insert('log_student_fees_management', $insert_array);
    }
}


if (!function_exists('log_student_fees_collections')) {

    function log_student_fees_collections($data, $action, $user_id, $user_name)
    {
        $CI = &get_instance();
        $current_datetime = date("Y-m-d h:i");
        $insert_array = array(
            'student_fees_collections_data' => json_encode($data),
            'action' => $action,
            'made_by_id' => $user_id,
            'made_by_name' => $user_name,
            'created_at' => $current_datetime
        );

        // Insert data into the 'log_student_fees_collections' table
        $CI->db->insert('log_student_fees_collections', $insert_array);
    }
}
