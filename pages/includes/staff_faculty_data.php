<?php

require_once __DIR__ . '/../../config/app_env.php';

if (!function_exists('get_teaching_faculty_data')) {
    function get_teaching_faculty_data()
    {
        $data = array();

        $conn = app_db_connect();
        if ($conn === null) {
            return $data;
        }

        $sql = "SELECT 
                    staff.id,
                    staff.name,
                    staff.surname,
                    staff.image,
                    staff.qualification,
                    staff.employee_id,
                    staff.gender,
                    staff_designation.designation,
                    roles.name AS user_type,
                    roles.id AS role_id
                FROM staff
                LEFT JOIN staff_roles ON staff_roles.staff_id = staff.id
                LEFT JOIN roles ON roles.id = staff_roles.role_id
                LEFT JOIN staff_designation ON staff_designation.id = staff.designation
                WHERE staff.is_active = 1
                  AND (roles.id IS NULL OR roles.id <> 7)
                ORDER BY staff.id ASC";

        if ($result = $conn->query($sql)) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            $result->free();
        }

        $conn->close();

        return $data;
    }
}

if (!function_exists('resolve_staff_image_url')) {
    function resolve_staff_image_url($image)
    {
        $site_base = defined('base_url') ? base_url : 'http://localhost/childrenscorner/';
        $default_image = $site_base . 'admin/uploads/staff_images/no_image.png';
        $remote_base = $site_base . 'admin/uploads/staff_images/';

        $image = trim((string) $image);
        if ($image === '') {
            return $default_image;
        }

        if (preg_match('#^https?://#i', $image)) {
            return $image;
        }

        $path = parse_url($image, PHP_URL_PATH);
        if (!empty($path)) {
            $image = $path;
        }

        $filename = basename($image);
        if ($filename === '' || $filename === 'no_image.png') {
            return $default_image;
        }

        return $remote_base . rawurlencode($filename);
    }
}
