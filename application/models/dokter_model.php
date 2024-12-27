<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dokter_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    // Fungsi untuk mendapatkan semua dokter
    public function get_all_dokter() {
        $this->db->select('dokter.id, dokter.nama, dokter.alamat, dokter.no_hp, dokter.id_poli, poli.nama_poli');
        $this->db->from('dokter');
        $this->db->join('poli', 'dokter.id_poli = poli.id');
        $query = $this->db->get();
        return $query->result();  // Mengembalikan hasil query
    }

    // Fungsi untuk menambah dokter
    public function insert_dokter($data) {
        // Pastikan alamat unik
        $this->db->where('alamat', $data['alamat']);
        $query = $this->db->get('dokter');
        if ($query->num_rows() > 0) {
            return false; // Jika alamat sudah ada
        }

        $this->db->insert('dokter', $data);
        return $this->db->insert_id(); // Mengembalikan ID dokter yang baru saja dimasukkan
    }

    // Fungsi untuk mendapatkan data dokter berdasarkan ID
    public function get_dokter_by_id($id) {
        $this->db->select('id, nama, alamat, no_hp, id_poli');
        $this->db->from('dokter');
        $this->db->where('id', $id);
        $query = $this->db->get();
        return $query->row();
    }

    // Fungsi untuk memperbarui data dokter
    public function update_dokter($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('dokter', $data);
    }

    // Fungsi untuk menghapus dokter
    public function delete_dokter($id) {
        return $this->db->delete('dokter', ['id' => $id]);
    }

    // Fungsi untuk mengambil daftar poli
    public function get_all_poli() {
        $query = $this->db->get('poli');
        return $query->result(); // Mengembalikan daftar poli
    }

    public function authenticate($nama, $password) {
        // Mencari dokter berdasarkan nama
        $this->db->where('nama', $nama);
        $query = $this->db->get('dokter');

        // Jika dokter ditemukan
        if ($query->num_rows() == 1) {
            $user = $query->row();
            // Verifikasi password
            if (password_verify($password, $user->password)) {
                return $user;
            }
        }
        return null; // Jika autentikasi gagal
    }
    public function getDokterByPoli($id_poli) {
        // Ambil dokter berdasarkan id_poli
        $this->db->where('id_poli', $id_poli);
        $query = $this->db->get('dokter'); // Asumsikan tabel dokter
    
        if ($query->num_rows() > 0) {
            return $query->result_array(); // Mengembalikan array data dokter
        } else {
            return []; // Mengembalikan array kosong jika tidak ada dokter
        }
    }
    
}
?>
