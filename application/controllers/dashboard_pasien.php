<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard_pasien extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('url');  // Memuat URL helper
        $this->load->model('Poli_model');  // Memuat model Poli
        $this->load->model('Dokter_model');  // Memuat model Dokter
        $this->load->model('Jadwal_periksa_model');  // Memuat model Jadwal Periksa
        $this->load->model('Daftar_poli_model');  // Memuat model Daftar Poli

        // Periksa session login
        if (!$this->session->userdata('logged_in')) {
            redirect('login');
        }
    }

    public function index() {
        // Pastikan $data diinisialisasi
        $data = [];

        // Ambil data pasien untuk ditampilkan di dashboard (opsional)
        $data['nama_pasien'] = $this->session->userdata('nama');  // Nama pasien dari session

        // Menampilkan dashboard pasien
        $this->load->view('dashboard_pasien', $data);
    }

    // Method untuk memuat halaman daftar poli menggunakan AJAX
    public function load_kelola_poli() {
        $data = [];
        $id_pasien = $this->session->userdata('id');
        $data['no_rm'] = $this->session->userdata('no_rm');
        $data['poli'] = $this->Poli_model->get_all_poli(); // Ambil data poli
        $data['riwayat'] = $this->Daftar_poli_model->get_riwayat_by_pasien($id_pasien);

        $this->load->view('daftar_poli', $data);
    }

    public function get_jadwal_by_poli() {
        $id_poli = $this->input->post('id_poli');
        $jadwal = $this->Jadwal_periksa_model->get_jadwal_by_poli($id_poli);
        echo json_encode($jadwal);
    }    
}
