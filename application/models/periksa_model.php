<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Periksa_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function insert_periksa($data) {
        $this->db->insert('periksa', $data);
        return $this->db->insert_id();
    }

    public function get_periksa_by_daftar_poli($id_daftar_poli) {
        return $this->db->get_where('periksa', ['id_daftar_poli' => $id_daftar_poli])->row_array();
    }
    
    public function update_periksa($id, $data) {
        $this->db->where('id', $id);
        $this->db->update('periksa', $data);
        return $this->db->affected_rows();
    }
    
    
}