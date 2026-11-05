<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Experiencecertificate_model extends MY_Model
{
    public function getList()
    {
        return $this->db->select('id, certificate_name, certificate_text, background_image, header_height, content_height, footer_height, content_width')
            ->from('certificates')
            ->where('created_for', 3)
            ->where('status', 1)
            ->order_by('id', 'DESC')
            ->get()
            ->result();
    }

    public function getById($id)
    {
        return $this->db->from('certificates')
            ->where('id', $id)
            ->where('created_for', 3)
            ->where('status', 1)
            ->get()
            ->row();
    }

    public function save($fields, $id = null, $background_image = '')
    {
        $record = array(
            'certificate_name' => $fields['teacher_name'],
            'certificate_text' => json_encode($fields, JSON_UNESCAPED_UNICODE),
            'left_header' => '',
            'center_header' => '',
            'right_header' => '',
            'left_footer' => '',
            'center_footer' => '',
            'right_footer' => '',
            'created_for' => 3,
            'status' => 1,
            'background_image' => $background_image,
            'header_height' => (int) $fields['header_height'],
            'content_height' => (int) $fields['content_height'],
            'footer_height' => (int) $fields['footer_height'],
            'content_width' => (int) $fields['content_width'],
            'enable_student_image' => 0,
            'enable_image_height' => 0,
        );

        $this->db->trans_start();
        if ($id) {
            $this->db->where('id', $id)->where('created_for', 3)->update('certificates', $record);
            $record_id = $id;
            $action = 'Update';
        } else {
            $this->db->insert('certificates', $record);
            $record_id = $this->db->insert_id();
            $action = 'Insert';
        }
        $this->db->trans_complete();

        if ($this->db->trans_status() === false) {
            return false;
        }

        $this->log($action . ' Experience Certificate record ' . $record_id, $record_id, $action);
        return $record_id;
    }

    public function remove($id)
    {
        $this->db->where('id', $id)->where('created_for', 3)->delete('certificates');
        if ($this->db->affected_rows() > 0) {
            $this->log('Delete Experience Certificate record ' . $id, $id, 'Delete');
            return true;
        }
        return false;
    }
}
