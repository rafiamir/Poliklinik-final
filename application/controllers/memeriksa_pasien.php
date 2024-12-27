<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Memeriksa_pasien extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper(['url', 'form']);
        $this->load->model('Memeriksa_pasien_model');
        $this->load->model('Obat_model');
        $this->load->model('Daftar_poli_model');

        // Periksa apakah user adalah dokter
        if (!$this->session->userdata('logged_in') || $this->session->userdata('role') != 'dokter') {
            redirect('login');
        }
    }

    public function index() {
        $id_dokter = $this->session->userdata('id');
        
        // Ambil daftar pasien yang terdaftar untuk dokter ini
        $data['pasien'] = $this->Memeriksa_pasien_model->get_pasien_by_dokter($id_dokter);
        
        // Ambil daftar obat
        $data['obat'] = $this->Obat_model->get_all_obat();

        // Load view
        $this->load->view('memeriksa_pasien', $data);
    }

    public function simpan_pemeriksaan() {
        $id_daftar_poli = $this->input->post('idDaftarPoli');
        $tgl_periksa = $this->input->post('tglPeriksa');
        $catatan = $this->input->post('catatan');
        $totalHarga = str_replace(['Rp', '.', ','], '', $this->input->post('totalHarga'));
        $obat = $this->input->post('obat');

        // Validasi input
        if (!$id_daftar_poli || !$tgl_periksa || !$catatan || !$totalHarga) {
            echo json_encode(['status' => 'error', 'message' => 'Semua field harus diisi!']);
            return;
        }

        // Simpan ke tabel pemeriksaan
        $this->Memeriksa_pasien_model->simpan_pemeriksaan($id_daftar_poli, $tgl_periksa, $catatan, $totalHarga, $obat);

        echo json_encode(['status' => 'success', 'message' => 'Pemeriksaan berhasil disimpan!']);
    }

    public function get_riwayat_pemeriksaan($id_daftar_poli) {
        $riwayat = $this->Memeriksa_pasien_model->get_riwayat_pemeriksaan($id_daftar_poli);
        echo json_encode(['status' => 'success', 'data' => $riwayat]);
    }
}
