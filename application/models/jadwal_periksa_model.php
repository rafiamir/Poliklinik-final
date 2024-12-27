<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jadwal_periksa_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database(); // Memuat database
    }

    public function get_all_jadwal() {
        $this->db->select('jadwal_periksa.id, jadwal_periksa.hari, jadwal_periksa.jam_mulai, jadwal_periksa.jam_selesai, dokter.nama AS nama_dokter');
        $this->db->from('jadwal_periksa');
        $this->db->join('dokter', 'dokter.id = jadwal_periksa.id_dokter');
        return $this->db->get()->result_array();
    }

    public function get_jadwal_by_dokter($id_dokter) {
        $this->db->select('jadwal_periksa.id, dokter.nama AS nama_dokter, jadwal_periksa.hari, jadwal_periksa.jam_mulai, jadwal_periksa.jam_selesai, 
                           IFNULL(jadwal_periksa.status, "tidak aktif") AS status'); // Gunakan IFNULL untuk mengganti NULL menjadi "tidak aktif"
        $this->db->from('jadwal_periksa');
        $this->db->join('dokter', 'dokter.id = jadwal_periksa.id_dokter');
        $this->db->where('jadwal_periksa.id_dokter', $id_dokter);
        $query = $this->db->get();
        return $query->result_array();
    }

    // Ambil jadwal periksa berdasarkan poli
    public function get_jadwal_by_poli($id_poli) {
        $this->db->select('jadwal_periksa.id, jadwal_periksa.hari, jadwal_periksa.jam_mulai, dokter.nama AS nama_dokter');
        $this->db->from('jadwal_periksa');
        $this->db->join('dokter', 'jadwal_periksa.id_dokter = dokter.id');
        $this->db->join('poli', 'dokter.id_poli = poli.id');
        $this->db->where('poli.id', $id_poli);
        $this->db->where('jadwal_periksa.status', 'aktif');
        return $this->db->get()->result_array();
    } 
    
            // Fungsi untuk menambahkan jadwal periksa
        public function insert_jadwal($data) {
            $data['status'] = isset($data['status']) ? $data['status'] : 'aktif';
            return $this->db->insert('jadwal_periksa', $data);
        }

        // Fungsi untuk mengupdate jadwal periksa
        public function update_jadwal($id, $data) {
            $this->db->where('id', $id);
            return $this->db->update('jadwal_periksa', $data);
        }

        // Fungsi untuk mengambil jadwal periksa berdasarkan status
        public function get_jadwal_aktif() {
            $this->db->where('status', 'aktif');
            $query = $this->db->get('jadwal_periksa');
            return $query->result_array();
        }


    // Fungsi untuk menghapus jadwal periksa
    public function delete_jadwal($id) {
        $this->db->where('id', $id);
        return $this->db->delete('jadwal_periksa');
    }

    // Fungsi untuk mengambil jadwal periksa berdasarkan ID
    public function get_jadwal_by_id($id) {
        $this->db->where('id', $id);
        $query = $this->db->get('jadwal_periksa');
        return $query->row_array();
    }
}
