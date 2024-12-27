<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Daftar_poli extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->model('Daftar_poli_model');
        $this->load->model('Jadwal_periksa_model');

        // Periksa apakah sudah login sebagai pasien
        if (!$this->session->userdata('logged_in') || $this->session->userdata('role') != 'pasien') {
            redirect('login');
        }
    }

    public function index() {
        $id_pasien = $this->session->userdata('id');
        $data['no_rm'] = $this->session->userdata('no_rm');
        $data['poli'] = $this->Daftar_poli_model->get_all_poli(); // Perbaikan: Data poli melalui relasi
        $data['riwayat'] = $this->Daftar_poli_model->get_riwayat_by_pasien($id_pasien);

        $this->load->view('daftar_poli', $data);
    }

    // In Daftar_poli.php (Controller)

    public function tambah() {
        $this->form_validation->set_rules('id_jadwal', 'Jadwal', 'required');
        $this->form_validation->set_rules('keluhan', 'Keluhan', 'required');
    
        if ($this->form_validation->run() == FALSE) {
            echo json_encode(['status' => 'error', 'message' => validation_errors()]);
        } else {
            $id_jadwal = $this->input->post('id_jadwal');
            $keluhan = $this->input->post('keluhan');
            $id_pasien = $this->session->userdata('id');
    
            // Pastikan nomor antrean adalah kelanjutan dari antrean terakhir berdasarkan jadwal
            $next_no_antrian = $this->Daftar_poli_model->get_max_no_antrian($id_jadwal) + 1;
    
            // Data untuk dimasukkan
            $data = [
                'id_pasien' => $id_pasien,
                'id_jadwal' => $id_jadwal,
                'keluhan' => $keluhan,
                'status' => 'belum diperiksa',
                'no_antrian' => $next_no_antrian
            ];
    
            if ($this->Daftar_poli_model->insert_daftar_poli($data)) {
                echo json_encode(['status' => 'success', 'message' => 'Berhasil mendaftar ke poli']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Gagal mendaftar']);
            }
        }
    }

    public function get_jadwal_by_poli() {
        $id_poli = $this->input->post('id_poli');
        if (!$id_poli) {
            echo json_encode([]);
            return;
        }

        $jadwal = $this->Jadwal_periksa_model->get_jadwal_by_poli($id_poli);
        echo json_encode($jadwal);
    }

    public function get_detail() {
        $id = $this->input->post('id');
        if (!$id) {
            echo json_encode(null);
            return;
        }
    
        $this->load->model('Daftar_poli_model');
        $detail = $this->Daftar_poli_model->get_detail_by_id($id);
    
        echo json_encode($detail);
    }

    public function get_riwayat_detail() {
        $id_daftar_poli = $this->input->post('id');
        if (!$id_daftar_poli) {
            echo json_encode([]);
            return;
        }
    
        $this->load->model('Daftar_poli_model');
        $riwayat = $this->Daftar_poli_model->get_riwayat_detail($id_daftar_poli);
    
        if ($riwayat) {
            echo json_encode($riwayat);
        } else {
            echo json_encode([]);
        }
    }
    
    
}
