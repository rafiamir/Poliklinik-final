<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Riwayat_pasien_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function get_pasien_by_dokter($id_dokter) {
        $this->db->select('p.id AS id_pasien, p.nama, p.alamat, p.no_ktp, p.no_hp, p.no_rm');
        $this->db->from('pasien p');
        $this->db->join('daftar_poli dp', 'dp.id_pasien = p.id');
        $this->db->join('jadwal_periksa jp', 'dp.id_jadwal = jp.id');
        $this->db->join('dokter d', 'jp.id_dokter = d.id');
        $this->db->where('d.id', $id_dokter);
        $this->db->group_by('p.id'); // Menghindari duplikasi pasien
        return $this->db->get()->result_array();
    }
    

    public function get_detail_riwayat_by_pasien($id_pasien, $id_dokter) {
        $this->db->select('periksa.tgl_periksa, pasien.nama AS nama_pasien, dokter.nama AS nama_dokter, 
                           daftar_poli.keluhan, periksa.catatan, periksa.biaya_periksa, GROUP_CONCAT(obat.nama_obat SEPARATOR ", ") AS nama_obat');
        $this->db->from('daftar_poli');
        $this->db->join('periksa', 'periksa.id_daftar_poli = daftar_poli.id');
        $this->db->join('detail_periksa', 'detail_periksa.id_periksa = periksa.id');
        $this->db->join('obat', 'detail_periksa.id_obat = obat.id');
        $this->db->join('pasien', 'daftar_poli.id_pasien = pasien.id');
        $this->db->join('jadwal_periksa', 'jadwal_periksa.id = daftar_poli.id_jadwal');
        $this->db->join('dokter', 'jadwal_periksa.id_dokter = dokter.id');
        $this->db->where('daftar_poli.id_pasien', $id_pasien);
        $this->db->where('jadwal_periksa.id_dokter', $id_dokter); // Tambahkan filter dokter
        $this->db->where('daftar_poli.status', 'sudah diperiksa');
        $this->db->group_by('periksa.id'); // Grup berdasarkan pemeriksaan
        $this->db->order_by('periksa.tgl_periksa', 'DESC');
        return $this->db->get()->result_array();
    }
      
    
    
}
