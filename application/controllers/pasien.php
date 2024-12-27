<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pasien extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Pasien_model');  // Memuat model Pasien_model
        $this->load->helper('url');
    }

    public function index() {
        // Ambil semua pasien dari model
        $data['pasien'] = $this->Pasien_model->get_all_pasien();
        // Kirim data pasien ke view
        $this->load->view('kelola_pasien', $data);
    }    

    // Fungsi untuk menampilkan form pendaftaran pasien
    public function register() {
        $this->load->view('register_pasien');  // Memuat view untuk pendaftaran pasien
    }

    public function tambah() {
        if ($this->input->is_ajax_request()) {
            // Validasi input
            $this->form_validation->set_rules('nama', 'Nama', 'required');
            $this->form_validation->set_rules('alamat', 'Alamat', 'required');
            $this->form_validation->set_rules('password', 'Password', 'required');
            $this->form_validation->set_rules('no_ktp', 'No. KTP', 'required');
            $this->form_validation->set_rules('no_hp', 'No. HP', 'required');
    
            if ($this->form_validation->run() == FALSE) {
                $response = array('status' => 'error', 'message' => validation_errors());
                echo json_encode($response);
                return;
            }
    
            // Generate no_rm (nomor rekam medis)
            $no_rm = $this->Pasien_model->generate_no_rm();
    
            // Siapkan data untuk dimasukkan ke dalam database
            $data = array(
                'nama' => $this->input->post('nama'),
                'alamat' => $this->input->post('alamat'),
                'password' => password_hash($this->input->post('password'), PASSWORD_BCRYPT),
                'no_ktp' => $this->input->post('no_ktp'),
                'no_hp' => $this->input->post('no_hp'),
                'no_rm' => $no_rm // Masukkan no_rm yang baru
            );
    
            // Masukkan data pasien ke database
            $insert = $this->Pasien_model->insert_pasien($data);
    
            if ($insert) {
                $response = array('status' => 'success', 'message' => 'Pasien berhasil ditambahkan');
            } else {
                $response = array('status' => 'error', 'message' => 'Terjadi kesalahan saat menambahkan pasien');
            }
    
            echo json_encode($response);
            return;
        }
    }

    // Fungsi untuk mendapatkan data pasien berdasarkan ID (untuk form edit)
    public function get($id) {
        // Ambil data pasien berdasarkan ID dari model
        $pasien = $this->Pasien_model->get_pasien_by_id($id);
        
        if ($pasien) {
            // Jika data ditemukan, kirimkan sebagai response JSON
            echo json_encode([
                'status' => 'success',
                'data' => $pasien
            ]);
        } else {
            // Jika tidak ada data pasien ditemukan
            echo json_encode([
                'status' => 'error',
                'message' => 'Pasien tidak ditemukan'
            ]);
        }
    }

    public function edit() {
        if ($this->input->is_ajax_request()) {
            $id = $this->input->post('id');
            $data = array(
                'nama' => $this->input->post('nama'),
                'alamat' => $this->input->post('alamat'),
                'no_ktp' => $this->input->post('no_ktp'),
                'no_hp' => $this->input->post('no_hp')
                // Tidak menyertakan 'no_rm' karena tidak boleh diubah
            );
    
            if ($this->Pasien_model->update_pasien($id, $data)) {
                echo json_encode(['status' => 'success', 'message' => 'Data pasien berhasil diperbarui.']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Terjadi kesalahan, gagal memperbarui data.']);
            }
        }
    }

    // Fungsi untuk menghapus pasien (AJAX)
    public function hapus($id) {
        if ($this->input->is_ajax_request()) {
            $delete = $this->Pasien_model->delete_pasien($id);
            if ($delete) {
                $response = array('status' => 'success', 'message' => 'Pasien berhasil dihapus');
            } else {
                $response = array('status' => 'error', 'message' => 'Terjadi kesalahan saat menghapus pasien');
            }
            echo json_encode($response);
            return;
        }
    }
}
