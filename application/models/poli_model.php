<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Poli_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function get_all_poli() {
        // Pastikan kolom deskripsi diambil
        $this->db->select('id, nama_poli, deskripsi');
        $query = $this->db->get('poli');
        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return [];
        }
    }
    

    public function insert_poli($data) {
        return $this->db->insert('poli', $data);
    }

    public function update_poli($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('poli', $data);
    }

    public function hapusPoli($id) {
        $this->db->where('id', $id);
        return $this->db->delete('poli');
    }

    public function get_poli_by_id($id) {
        $this->db->where('id', $id);
        $query = $this->db->get('poli');
        return $query->row_array();
    }
}
?>
