<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Obat extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Obat_model');  // Memuat model Obat_model
        $this->load->helper('url');
    }

    public function index() {
        // Ambil semua obat dari model
        $data['obat'] = $this->Obat_model->get_all_obat();
        // Kirim data obat ke view
        $this->load->view('kelola_obat', $data);
    }

    // Fungsi untuk menambah obat
    public function tambah() {
        if ($this->input->is_ajax_request()) {
            // Validasi input
            $this->form_validation->set_rules('nama_obat', 'Nama Obat', 'required');
            $this->form_validation->set_rules('harga', 'Harga', 'required|numeric');
    
            if ($this->form_validation->run() == FALSE) {
                $response = array('status' => 'error', 'message' => validation_errors());
                echo json_encode($response);
                return;
            }

            // Siapkan data untuk dimasukkan ke dalam database
            $data = array(
                'nama_obat' => $this->input->post('nama_obat'),
                'kemasan' => $this->input->post('kemasan'),
                'harga' => $this->input->post('harga')
            );
    
            // Masukkan data obat ke database
            $insert = $this->Obat_model->insert_obat($data);
    
            if ($insert) {
                $response = array('status' => 'success', 'message' => 'Obat berhasil ditambahkan');
            } else {
                $response = array('status' => 'error', 'message' => 'Terjadi kesalahan saat menambahkan obat');
            }
    
            echo json_encode($response);
            return;
        }
    }

    // Fungsi untuk mendapatkan data obat berdasarkan ID (untuk form edit)
    public function get($id) {
        // Ambil data obat berdasarkan ID dari model
        $obat = $this->Obat_model->get_obat_by_id($id);
        
        if ($obat) {
            // Jika data ditemukan, kirimkan sebagai response JSON
            echo json_encode([
                'status' => 'success',
                'data' => $obat
            ]);
        } else {
            // Jika tidak ada data obat ditemukan
            echo json_encode([
                'status' => 'error',
                'message' => 'Obat tidak ditemukan'
            ]);
        }
    }

    // Fungsi untuk memperbarui data obat
    public function edit() {
        if ($this->input->is_ajax_request()) {
            $id = $this->input->post('id');
            $data = array(
                'nama_obat' => $this->input->post('nama_obat'),
                'kemasan' => $this->input->post('kemasan'),
                'harga' => $this->input->post('harga')
            );
    
            if ($this->Obat_model->update_obat($id, $data)) {
                echo json_encode(['status' => 'success', 'message' => 'Data obat berhasil diperbarui.']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Terjadi kesalahan, gagal memperbarui data.']);
            }
        }
    }

    // Fungsi untuk menghapus obat (AJAX)
    public function hapus($id) {
        if ($this->input->is_ajax_request()) {
            $delete = $this->Obat_model->delete_obat($id);
            if ($delete) {
                $response = array('status' => 'success', 'message' => 'Obat berhasil dihapus');
            } else {
                $response = array('status' => 'error', 'message' => 'Terjadi kesalahan saat menghapus obat');
            }
            echo json_encode($response);
            return;
        }
    }
}
