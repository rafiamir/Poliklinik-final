<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Detail_periksa_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function insert_detail_periksa($data) {
        $this->db->insert('detail_periksa', $data);
    }

    public function get_obat_by_periksa($id_periksa) {
        $this->db->select('obat.id, obat.nama_obat, obat.harga');
        $this->db->from('detail_periksa');
        $this->db->join('obat', 'detail_periksa.id_obat = obat.id');
        $this->db->where('detail_periksa.id_periksa', $id_periksa);
        return $this->db->get()->result_array();
    }
    
    public function delete_by_periksa($id_periksa) {
        $this->db->where('id_periksa', $id_periksa);
        $this->db->delete('detail_periksa');
        return $this->db->affected_rows();
    }
    
}