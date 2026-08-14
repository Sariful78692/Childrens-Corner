<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Notice_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get($id = null) {
        if ($id != null) {
            $query = $this->db->get_where('notices', array('id' => $id));
            return $query->row_array();
        } else {
            $this->db->order_by('id', 'DESC');
            $query = $this->db->get('notices');
            return $query->result_array();
        }
    }

    public function add($data) {
        if (isset($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('notices', $data);
        } else {
            $this->db->insert('notices', $data);
            return $this->db->insert_id();
        }
    }

    public function remove($id) {
        $this->db->where('id', $id);
        $this->db->delete('notices');
    }

    public function changeStatus($id, $status) {
        $data = array('status' => $status);
        $this->db->where('id', $id);
        $this->db->update('notices', $data);
    }
}
