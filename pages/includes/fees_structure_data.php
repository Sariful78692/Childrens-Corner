<?php

require_once __DIR__ . '/../../config/app_env.php';

if (!function_exists('get_fee_structure_sessions')) {
    function get_fee_structure_sessions()
    {
        $sessions = array();
        $conn = app_db_connect();
        if ($conn === null) {
            return $sessions;
        }

        $result = $conn->query("SELECT id, session FROM sessions ORDER BY id DESC");
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $sessions[] = $row;
            }
            $result->free();
        }
        $conn->close();

        return $sessions;
    }
}

if (!function_exists('get_fee_structure_current_session_id')) {
    function get_fee_structure_current_session_id()
    {
        $conn = app_db_connect();
        if ($conn === null) {
            return 0;
        }

        $current_session_id = 0;
        $result = $conn->query("SELECT session_id FROM sch_settings ORDER BY id ASC LIMIT 1");
        if ($result) {
            $row = $result->fetch_assoc();
            if (!empty($row['session_id'])) {
                $current_session_id = (int) $row['session_id'];
            }
            $result->free();
        }

        if ($current_session_id <= 0) {
            $result = $conn->query("SELECT id FROM sessions ORDER BY id DESC LIMIT 1");
            if ($result) {
                $row = $result->fetch_assoc();
                if (!empty($row['id'])) {
                    $current_session_id = (int) $row['id'];
                }
                $result->free();
            }
        }

        $conn->close();

        return $current_session_id;
    }
}

if (!function_exists('get_fee_structure_classes')) {
    function get_fee_structure_classes($session_id = null)
    {
        $classes = array();
        $conn = app_db_connect();
        if ($conn === null) {
            return $classes;
        }

        if (empty($session_id)) {
            $session_id = get_fee_structure_current_session_id();
        }

        $sql = "SELECT DISTINCT classes.id, classes.class, classes.class_order
                FROM fees_master_class_wise fmcw
                INNER JOIN classes ON classes.id = fmcw.class_id
                WHERE fmcw.session_id = " . (int) $session_id . "
                ORDER BY classes.class_order ASC, classes.class ASC";

        $result = $conn->query($sql);
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $classes[] = $row;
            }
            $result->free();
        }

        $conn->close();

        return $classes;
    }
}

if (!function_exists('get_fee_structure_rows')) {
    function get_fee_structure_rows($session_id = null, $class_id = null)
    {
        $rows = array();
        $conn = app_db_connect();
        if ($conn === null) {
            return $rows;
        }

        if (empty($session_id)) {
            $session_id = get_fee_structure_current_session_id();
        }

        $sql = "SELECT 
                    fmcw.id,
                    fmcw.session_id,
                    fmcw.class_id,
                    fmcw.feetype_id,
                    fmcw.fees_amount,
                    fmcw.tuition_fees,
                    fmcw.meal_charges,
                    fmcw.is_monthly,
                    fmcw.fees_order,
                    sessions.session,
                    classes.class,
                    feetype.type
                FROM fees_master_class_wise fmcw
                INNER JOIN sessions ON sessions.id = fmcw.session_id
                INNER JOIN classes ON classes.id = fmcw.class_id
                INNER JOIN feetype ON feetype.id = fmcw.feetype_id
                WHERE fmcw.session_id = " . (int) $session_id;

        if (!empty($class_id)) {
            $sql .= " AND fmcw.class_id = " . (int) $class_id;
        }

        $sql .= " ORDER BY classes.class_order ASC, fmcw.fees_order ASC, fmcw.id ASC";

        if ($result = $conn->query($sql)) {
            while ($row = $result->fetch_assoc()) {
                $rows[] = $row;
            }
            $result->free();
        }

        $conn->close();

        return $rows;
    }
}
