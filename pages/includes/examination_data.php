<?php

require_once __DIR__ . '/../../config/app_env.php';

if (!function_exists('get_examination_page_data')) {
    function get_examination_page_data()
    {
        $data = array(
            'routines' => array(),
            'rules' => array(),
        );

        $conn = app_db_connect();
        if ($conn === null) {
            return $data;
        }

        if ($routine_result = $conn->query("SELECT class_name, routine_title, routine_file, created_at FROM examination_routine WHERE status = 1 ORDER BY id DESC")) {
            while ($row = $routine_result->fetch_assoc()) {
                $data['routines'][] = $row;
            }
            $routine_result->free();
        }

        if ($rule_result = $conn->query("SELECT rule_content, created_at FROM examination_rules ORDER BY id DESC")) {
            while ($row = $rule_result->fetch_assoc()) {
                $data['rules'][] = $row;
            }
            $rule_result->free();
        }

        $conn->close();

        return $data;
    }
}
