<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dokter extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Dokter_model');  // Memuat model Dokter_model
        $this->load->helper('url');
        $this->load->library('form_validation');
        $this->load->model('Daftar_poli_model');
        $this->load->model('Jadwal_periksa_model');
    }

    // Fungsi untuk menampilkan semua dokter
    public function index() {
        // Ambil semua dokter beserta nama poli
        $data['dokter'] = $this->Dokter_model->get_all_dokter();
        // Kirim data dokter ke view
        $this->load->view('kelola_dokter', $data);
    }

    // Fungsi untuk menambah dokter
    public function tambah() {
        if ($this->input->is_ajax_request()) {
            // Validasi input
            $this->form_validation->set_rules('nama', 'Nama', 'required');
            $this->form_validation->set_rules('no_hp', 'No HP', 'required|numeric');
            $this->form_validation->set_rules('id_poli', 'Poli', 'required');
            $this->form_validation->set_rules('alamat', 'Alamat', 'required');
            $this->form_validation->set_rules('password', 'Password', 'required');

            if ($this->form_validation->run() == FALSE) {
                $response = array('status' => 'error', 'message' => validation_errors());
                echo json_encode($response);
                return;
            }

            // Siapkan data untuk dimasukkan ke dalam database
            $data = array(
                'nama' => $this->input->post('nama'),
                'alamat' => $this->input->post('alamat'),
                'password' => password_hash($this->input->post('password'), PASSWORD_DEFAULT), // Menggunakan hash untuk password
                'no_hp' => $this->input->post('no_hp'),
                'id_poli' => $this->input->post('id_poli')
            );

            // Masukkan data dokter ke database
            $insert = $this->Dokter_model->insert_dokter($data);

            if ($insert) {
                $response = array('status' => 'success', 'message' => 'Dokter berhasil ditambahkan');
            } else {
                $response = array('status' => 'error', 'message' => 'Terjadi kesalahan saat menambahkan dokter');
            }

            echo json_encode($response);
            return;
        }
    }

    // Fungsi untuk mendapatkan data dokter berdasarkan ID (untuk form edit)
    public function get($id) {
        $dokter = $this->Dokter_model->get_dokter_by_id($id);
        
        if ($dokter) {
            // Jika data ditemukan, kirimkan sebagai response JSON
            echo json_encode([
                'status' => 'success',
                'data' => $dokter
            ]);
        } else {
            // Jika tidak ada data dokter ditemukan
            echo json_encode([
                'status' => 'error',
                'message' => 'Dokter tidak ditemukan'
            ]);
        }
    }

    // Fungsi untuk memperbarui data dokter
    public function edit() {
        if ($this->input->is_ajax_request()) {
            $id = $this->input->post('id');
            
            // Siapkan data yang akan diupdate
            $data = array(
                'nama' => $this->input->post('nama'),
                'alamat' => $this->input->post('alamat'),
                'no_hp' => $this->input->post('no_hp'),
                'id_poli' => $this->input->post('id_poli')
            );

            // Jika password diinputkan, maka ubah password
            $password = $this->input->post('password');
            if (!empty($password)) {
                $data['password'] = password_hash($password, PASSWORD_DEFAULT); // Update password jika diisi
            }

            if ($this->Dokter_model->update_dokter($id, $data)) {
                echo json_encode(['status' => 'success', 'message' => 'Data dokter berhasil diperbarui.']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Terjadi kesalahan, gagal memperbarui data.']);
            }
        }
    }

    // Fungsi untuk menghapus dokter (AJAX)
    public function hapus($id) {
        if ($this->input->is_ajax_request()) {
            $delete = $this->Dokter_model->delete_dokter($id);
            if ($delete) {
                $response = array('status' => 'success', 'message' => 'Dokter berhasil dihapus');
            } else {
                $response = array('status' => 'error', 'message' => 'Terjadi kesalahan saat menghapus dokter');
            }
            echo json_encode($response);
            return;
        }
    }

    // Fungsi untuk mengambil data poli (dropdown)
    public function get_poli() {
        $poli = $this->Dokter_model->get_all_poli();
        echo json_encode($poli);
    }

    
    
}
