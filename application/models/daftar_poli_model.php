<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Daftar_poli_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    // Ambil semua poli (berdasarkan tabel poli)
    public function get_all_poli() {
        return $this->db->get('poli')->result_array();
    }

    public function insert_daftar_poli($data) {
        // Debug untuk melihat data yang dimasukkan
        log_message('debug', 'Data Inserted: ' . json_encode($data));
    
        return $this->db->insert('daftar_poli', $data);
    }    

    // Ambil riwayat daftar poli pasien tertentu
    public function get_riwayat_by_pasien($id_pasien) {
        $this->db->select('daftar_poli.id, poli.nama_poli, dokter.nama AS nama_dokter, 
                           jadwal_periksa.hari, jadwal_periksa.jam_mulai, daftar_poli.no_antrian, daftar_poli.status');
        $this->db->from('daftar_poli');
        $this->db->join('jadwal_periksa', 'jadwal_periksa.id = daftar_poli.id_jadwal');
        $this->db->join('dokter', 'jadwal_periksa.id_dokter = dokter.id');
        $this->db->join('poli', 'dokter.id_poli = poli.id');
        $this->db->where('daftar_poli.id_pasien', $id_pasien);
        $this->db->order_by('daftar_poli.id', 'DESC');
        return $this->db->get()->result_array();
    }

    // Daftar_poli_model.php

    public function get_max_no_antrian($id_jadwal) {
        // Cari nomor antrean tertinggi berdasarkan jadwal
        $this->db->select_max('no_antrian');
        $this->db->where('id_jadwal', $id_jadwal);
        $query = $this->db->get('daftar_poli');
        
        $result = $query->row_array();
        return $result['no_antrian'] ? $result['no_antrian'] : 0;
    }
    

    // Validasi tambahan pada model jika dibutuhkan
public function validate_antrian($id_jadwal, $next_no_antrian) {
    $this->db->where('id_jadwal', $id_jadwal);
    $this->db->where('no_antrian', $next_no_antrian);
    return $this->db->get('daftar_poli')->num_rows() === 0;
}

public function get_detail_by_id($id) {
    $this->db->select('poli.nama_poli, dokter.nama AS nama_dokter, jadwal_periksa.hari, 
                       jadwal_periksa.jam_mulai, jadwal_periksa.jam_selesai, daftar_poli.no_antrian');
    $this->db->from('daftar_poli');
    $this->db->join('jadwal_periksa', 'jadwal_periksa.id = daftar_poli.id_jadwal');
    $this->db->join('dokter', 'jadwal_periksa.id_dokter = dokter.id');
    $this->db->join('poli', 'dokter.id_poli = poli.id');
    $this->db->where('daftar_poli.id', $id);

    return $this->db->get()->row_array();
}

public function get_pasien_by_jadwal($id_jadwal) {
    $this->db->select('daftar_poli.no_antrian, pasien.nama AS nama_pasien, daftar_poli.keluhan, daftar_poli.status');
    $this->db->from('daftar_poli');
    $this->db->join('pasien', 'daftar_poli.id_pasien = pasien.id');
    $this->db->where('daftar_poli.id_jadwal', $id_jadwal);
    $this->db->order_by('daftar_poli.no_antrian', 'ASC');
    return $this->db->get()->result_array();
}

// Fungsi untuk mendapatkan pasien berdasarkan ID dokter
public function get_pasien_by_dokter($id_dokter) {
    $this->db->select('daftar_poli.id, daftar_poli.no_antrian, pasien.nama AS nama_pasien, 
                       daftar_poli.keluhan, daftar_poli.status');
    $this->db->from('daftar_poli');
    $this->db->join('jadwal_periksa', 'jadwal_periksa.id = daftar_poli.id_jadwal');
    $this->db->join('pasien', 'pasien.id = daftar_poli.id_pasien');
    $this->db->where('jadwal_periksa.id_dokter', $id_dokter);
    $this->db->order_by('daftar_poli.no_antrian', 'ASC');
    return $this->db->get()->result_array();
}



public function update_status($id_daftar_poli, $status) {
    $this->db->where('id', $id_daftar_poli);
    $this->db->update('daftar_poli', ['status' => $status]);
    return $this->db->affected_rows() > 0;
}


// In Daftar_poli_model.php
public function get_riwayat_by_id($id_daftar_poli) {
    $this->db->select('p.tgl_periksa, p.catatan, p.biaya_periksa, o.nama_obat');
    $this->db->from('periksa p');
    $this->db->join('detail_periksa dp', 'dp.id_periksa = p.id');
    $this->db->join('obat o', 'o.id = dp.id_obat');
    $this->db->where('p.id_daftar_poli', $id_daftar_poli);
    $query = $this->db->get();
    return $query->result(); // Return array of riwayat data
}

public function get_riwayat_detail($id_daftar_poli) {
    $this->db->select('periksa.tgl_periksa, periksa.catatan, periksa.biaya_periksa, obat.nama_obat, obat.harga');
    $this->db->from('periksa');
    $this->db->join('detail_periksa', 'detail_periksa.id_periksa = periksa.id');
    $this->db->join('obat', 'detail_periksa.id_obat = obat.id');
    $this->db->where('periksa.id_daftar_poli', $id_daftar_poli);
    $query = $this->db->get();

    if ($query->num_rows() > 0) {
        return $query->result_array(); // Mengembalikan daftar obat dan detail lainnya
    }

    return null;
}


}
