<?php
// CONTROLLER: Jadwal_periksa.php

defined('BASEPATH') OR exit('No direct script access allowed');

class Jadwal_periksa extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Jadwal_periksa_model');
        $this->load->library('form_validation');
        $this->load->library('session');

        // Periksa apakah sudah login sebagai dokter
        if (!$this->session->userdata('logged_in') || $this->session->userdata('role') != 'dokter') {
            redirect('login');
        }
    }

    public function index()
{
    $id_dokter = $this->session->userdata('id'); // Ambil ID dokter dari session
    if (!$id_dokter) {
        // Jika ID dokter tidak ditemukan di session, redirect ke login
        redirect('login');
    }

    // Ambil jadwal hanya untuk dokter yang login
    $data['jadwal'] = $this->Jadwal_periksa_model->get_jadwal_by_dokter($id_dokter);

    // Load view dengan data yang sudah difilter
    $this->load->view('jadwal_periksa', $data);
}


public function tambah() {
    $this->form_validation->set_rules('id_dokter', 'Dokter', 'required');
    $this->form_validation->set_rules('hari', 'Hari', 'required');
    $this->form_validation->set_rules('jam_mulai', 'Jam Mulai', 'required');
    $this->form_validation->set_rules('jam_selesai', 'Jam Selesai', 'required');

    if ($this->form_validation->run() == FALSE) {
        echo json_encode(['status' => 'error', 'message' => validation_errors()]);
    } else {
        $data = array(
            'id_dokter' => $this->input->post('id_dokter'),
            'hari' => $this->input->post('hari'),
            'jam_mulai' => $this->input->post('jam_mulai'),
            'jam_selesai' => $this->input->post('jam_selesai'),
            'status' => $this->input->post('status') ?: 'tidak aktif' // Gunakan "tidak aktif" jika status kosong
        );

        if ($this->Jadwal_periksa_model->insert_jadwal($data)) {
            echo json_encode(['status' => 'success', 'message' => 'Jadwal berhasil ditambahkan']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Gagal menambahkan jadwal']);
        }
    }
}

public function edit($id) {
    $this->form_validation->set_rules('hari', 'Hari', 'required');
    $this->form_validation->set_rules('jam_mulai', 'Jam Mulai', 'required');
    $this->form_validation->set_rules('jam_selesai', 'Jam Selesai', 'required');

    if ($this->form_validation->run() == FALSE) {
        echo json_encode(['status' => 'error', 'message' => validation_errors()]);
    } else {
        $data = array(
            'hari' => $this->input->post('hari'),
            'jam_mulai' => $this->input->post('jam_mulai'),
            'jam_selesai' => $this->input->post('jam_selesai'),
            'status' => $this->input->post('status') ?: 'tidak aktif' // Gunakan "tidak aktif" jika status kosong
        );

        if ($this->Jadwal_periksa_model->update_jadwal($id, $data)) {
            echo json_encode(['status' => 'success', 'message' => 'Jadwal berhasil diperbarui']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Gagal memperbarui jadwal']);
        }
    }
}


    public function hapus($id)
    {
        $this->Jadwal_periksa_model->delete_jadwal($id);
        echo json_encode(['status' => 'success', 'message' => 'Jadwal berhasil dihapus']);
    }

    public function get($id)
    {
        $data = $this->Jadwal_periksa_model->get_jadwal_by_id($id);
        if ($data) {
            echo json_encode(['status' => 'success', 'data' => $data]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Data tidak ditemukan']);
        }
    }
}