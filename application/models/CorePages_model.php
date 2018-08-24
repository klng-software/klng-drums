<?php

class CorePages_model extends CI_Model {

    public function __construct() {

    }

    public function get_menu_items($slug = FALSE) {
        if ($slug === FALSE) {
            $this->db->select(array('page_name', 'page_description', 'page_path', 'slug'));
            $this->db->from('core_pages');
            $this->db->where(array('is_active'=>1, 'is_menu'=>1, 'deleted_at'=>NULL));
            $this->db->order_by('page_sort');
            $query = $this->db->get();
            return $query->result_array();
        }

        $query = $this->db->get_where('page_name', array('slug' => $slug, 'is_active'=>1, 'is_menu'=>1, 'deleted_at'=>NULL));
        
        return $query->row_array();
    }
    
    public function get_pages($slug = FALSE) {
        if ($slug === FALSE) {
            $this->db->select('page_name', 'page_description', 'page_path', 'slug');
            $this->db->from('core_pages');
            $this->db->where(array('is_active'=>1, 'deleted_at'=>NULL));
            $this->db->order_by('page_sort');
            $query = $this->db->get();
            return $query->result_array();
        }

        $query = $this->db->get_where('page_name', array('slug' => $slug, 'is_active'=>1, 'deleted_at'=>NULL));
        
        return $query->row_array();
    }

//    public function set_news() {
//        $this->load->helper('url');
//
//        $slug = url_title($this->input->post('title'), 'dash', TRUE);
//
//        $data = array(
//            'title' => $this->input->post('title'),
//            'slug' => $slug,
//            'text' => $this->input->post('text')
//        );
//
//        return $this->db->insert('news', $data);
//    }

}
