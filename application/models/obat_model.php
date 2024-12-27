<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Obat_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    // Fungsi untuk mendapatkan semua obat
    public function get_all_obat() {
        $query = $this->db->get('obat');
        return $query->result();  // Mengembalikan hasil query
    }
    
    // Fungsi untuk menambahkan obat
    public function insert_obat($data) {
        $this->db->insert('obat', $data);
        return $this->db->insert_id(); // Mengembalikan ID obat yang baru saja dimasukkan
    }

    // Fungsi untuk mendapatkan obat berdasarkan ID
    public function get_obat_by_id($id) {
        return $this->db->get_where('obat', ['id' => $id])->row();
    }

    // Fungsi untuk memperbarui data obat
    public function update_obat($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('obat', $data);
    }

    // Fungsi untuk menghapus obat
    public function delete_obat($id) {
        return $this->db->delete('obat', ['id' => $id]);
    }

    // Fungsi untuk mendapatkan harga obat berdasarkan ID
    public function get_harga_obat($id_obat) {
        $obat = $this->db->get_where('obat', ['id' => $id_obat])->row();
        return ($obat) ? $obat->harga : 0;  // Menghindari error jika obat tidak ditemukan
    }
}
