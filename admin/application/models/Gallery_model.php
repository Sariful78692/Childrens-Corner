<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Gallery_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function getCategories() {
        return $this->db->get('gallery_categories')->result_array();
    }

    public function addCategory($data) {
        if (isset($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('gallery_categories', $data);
        } else {
            $this->db->insert('gallery_categories', $data);
        }
    }

    public function deleteCategory($id) {
        $this->db->where('id', $id);
        $this->db->delete('gallery_categories');
    }

    public function getPhotos() {
        $this->db->select('gallery_photos.*, gallery_categories.category_name');
        $this->db->from('gallery_photos');
        $this->db->join('gallery_categories', 'gallery_categories.id = gallery_photos.category_id');
        $this->db->order_by('gallery_photos.created_at', 'DESC');
        return $this->db->get()->result_array();
    }

    public function addPhoto($data) {
        $this->db->insert('gallery_photos', $data);
    }

    public function deletePhoto($id) {
        $this->db->where('id', $id);
        $this->db->delete('gallery_photos');
    }

    public function getPhoto($id) {
        $this->db->where('id', $id);
        return $this->db->get('gallery_photos')->row_array();
    }
}
