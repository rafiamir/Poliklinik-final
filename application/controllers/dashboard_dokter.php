<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard_dokter extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('url');
        $this->load->helper('form');
        $this->load->model('Dokter_model');
        $this->load->model('Jadwal_periksa_model');
        $this->load->model('Daftar_poli_model');
        $this->load->model('Obat_model');
        $this->load->model('Periksa_model');
        $this->load->model('Detail_periksa_model');

        // Periksa session login
        if (!$this->session->userdata('logged_in') || $this->session->userdata('role') !== 'dokter') {
            redirect('login');
        }
    }

    public function index() {
        $data['dokter'] = $this->Dokter_model->get_all_dokter();
        $this->load->view('dashboard_dokter', $data);
    }

    public function jadwal_periksa() {
        $id_dokter = $this->session->userdata('id');
        if (!$id_dokter) {
            redirect('login');
        }

        $data['jadwal'] = $this->Jadwal_periksa_model->get_jadwal_by_dokter($id_dokter);
        $this->load->view('jadwal_periksa', $data);
    }

    public function pasien_periksa() {
        $id_dokter = $this->session->userdata('id');
        if (!$id_dokter) {
            redirect('login');
        }

        $data['pasien'] = $this->Daftar_poli_model->get_pasien_by_dokter($id_dokter);
        $this->load->view('pasien_periksa', $data);
    }

    public function get_obat() {
        $data = $this->Obat_model->get_all_obat();
        if ($data) {
            echo json_encode($data);
        } else {
            echo json_encode([]);
        }
    }
    

    public function simpan_periksa() {
        $id_daftar_poli = $this->input->post('id_daftar_poli');
        $tgl_periksa = $this->input->post('tgl_periksa');
        $catatan = $this->input->post('catatan');
        $total_harga = $this->input->post('total_harga');
        $obat = $this->input->post('obat');
    
        // Validasi input
        if (empty($id_daftar_poli) || empty($tgl_periksa) || empty($total_harga)) {
            echo json_encode(['status' => 'error', 'message' => 'Data tidak lengkap.']);
            return;
        }
    
        // Cek apakah ini adalah pemeriksaan baru atau update
        $existingPeriksa = $this->Periksa_model->get_periksa_by_daftar_poli($id_daftar_poli);
    
        if ($existingPeriksa) {
            // Update data pemeriksaan
            $dataPeriksa = [
                'tgl_periksa' => $tgl_periksa,
                'catatan' => $catatan,
                'biaya_periksa' => $total_harga
            ];
            $this->Periksa_model->update_periksa($existingPeriksa['id'], $dataPeriksa);
    
            // Hapus detail obat lama dan simpan ulang
            $this->Detail_periksa_model->delete_by_periksa($existingPeriksa['id']);
            foreach ($obat as $idObat) {
                $detailPeriksa = [
                    'id_periksa' => $existingPeriksa['id'],
                    'id_obat' => $idObat
                ];
                $this->Detail_periksa_model->insert_detail_periksa($detailPeriksa);
            }
        } else {
            // Simpan data pemeriksaan baru
            $dataPeriksa = [
                'id_daftar_poli' => $id_daftar_poli,
                'tgl_periksa' => $tgl_periksa,
                'catatan' => $catatan,
                'biaya_periksa' => $total_harga
            ];
            $idPeriksa = $this->Periksa_model->insert_periksa($dataPeriksa);
    
            // Simpan detail obat baru
            foreach ($obat as $idObat) {
                $detailPeriksa = [
                    'id_periksa' => $idPeriksa,
                    'id_obat' => $idObat
                ];
                $this->Detail_periksa_model->insert_detail_periksa($detailPeriksa);
            }
        }
    
        // Update status di tabel daftar_poli
        $this->Daftar_poli_model->update_status($id_daftar_poli, 'sudah diperiksa');
    
        echo json_encode(['status' => 'success', 'message' => 'Pemeriksaan berhasil disimpan.']);
    }
    
    public function get_detail_periksa() {
        $id_daftar_poli = $this->input->post('id_daftar_poli');
        $dataPeriksa = $this->Periksa_model->get_periksa_by_daftar_poli($id_daftar_poli);
        $dataObat = $this->Detail_periksa_model->get_obat_by_periksa($dataPeriksa['id']);
    
        if ($dataPeriksa) {
            echo json_encode([
                'tgl_periksa' => $dataPeriksa['tgl_periksa'],
                'catatan' => $dataPeriksa['catatan'],
                'total_harga' => $dataPeriksa['biaya_periksa'],
                'obat' => $dataObat
            ]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Data tidak ditemukan.']);
        }
    }
    
    public function profil() {
        $id_dokter = $this->session->userdata('id');
        $data['dokter'] = $this->Dokter_model->get_dokter_by_id($id_dokter); // Ambil data dokter yang sedang login
    
        $this->load->view('profil_dokter', $data); // Load view profil
    }
    
    public function update_profil() {
        $id_dokter = $this->session->userdata('id');
    
        // Ambil data dari form
        $nama = $this->input->post('nama');
        $alamat = $this->input->post('alamat');
        $no_hp = $this->input->post('no_hp');
    
        // Validasi input
        if (empty($nama) || empty($alamat) || empty($no_hp)) {
            echo json_encode(['status' => 'error', 'message' => 'Semua field harus diisi.']);
            return;
        }
    
        // Update data di database
        $update_data = [
            'nama' => $nama,
            'alamat' => $alamat,
            'no_hp' => $no_hp
        ];
    
        if ($this->Dokter_model->update_dokter($id_dokter, $update_data)) {
            echo json_encode(['status' => 'success', 'message' => 'Profil berhasil diperbarui.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Gagal memperbarui profil.']);
        }
    }
    
    
}
