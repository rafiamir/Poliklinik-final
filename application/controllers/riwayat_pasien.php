<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Riwayat_pasien extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->model('Riwayat_pasien_model');
        $this->load->model('Pasien_model'); // Model untuk mengambil data pasien

        // Periksa apakah pengguna adalah dokter
        if (!$this->session->userdata('logged_in') || $this->session->userdata('role') !== 'dokter') {
            redirect('login');
        }
    }

    public function index() {
        $id_dokter = $this->session->userdata('id'); // Ambil ID dokter dari session
        $data['pasien'] = $this->Riwayat_pasien_model->get_pasien_by_dokter($id_dokter); // Hanya pasien terkait dokter
        $this->load->view('riwayat_pasien', $data);
    }
    
    public function get_detail_riwayat() {
        $id_pasien = $this->input->post('id_pasien');
        $id_dokter = $this->session->userdata('id'); // Ambil ID dokter dari session
        $data = $this->Riwayat_pasien_model->get_detail_riwayat_by_pasien($id_pasien, $id_dokter);
    
        if ($data) {
            echo json_encode($data);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Riwayat tidak ditemukan.']);
        }
    }
    
}
