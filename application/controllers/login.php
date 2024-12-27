<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper(array('form', 'url'));
        $this->load->library('session');
        $this->load->model('Pasien_model');
        $this->load->model('Dokter_model');
    }

    public function index() {
        $this->load->view('login');
    }

    public function authenticate() {
        $username = $this->input->post('username');
        $password = $this->input->post('password');

        if ($username == 'admin' && $password == 'admin') {
            $this->session->set_userdata([
                'logged_in' => true,
                'role' => 'admin',
                'username' => $username,
            ]);
            redirect('dashboard');
        } else {
            $user_pasien = $this->Pasien_model->authenticate($username, $password);
            $user_dokter = $this->Dokter_model->authenticate($username, $password);

            if ($user_pasien) {
                $this->session->set_userdata([
                    'logged_in' => true,
                    'role' => 'pasien',
                    'id' => $user_pasien->id,
                    'nama' => $user_pasien->nama,
                    'no_rm' => $user_pasien->no_rm,
                ]);
                redirect('dashboard_pasien');
            } elseif ($user_dokter) {
                $this->session->set_userdata([
                    'logged_in' => true,
                    'role' => 'dokter',
                    'id' => $user_dokter->id,
                    'nama' => $user_dokter->nama,
                ]);
                redirect('dashboard_dokter');
            } else {
                $this->session->set_flashdata('error', 'Username atau Password salah!');
                redirect('login');
            }
        }
    }

    public function logout() {
        $this->session->sess_destroy();
        redirect('login');
    }
}
?>
