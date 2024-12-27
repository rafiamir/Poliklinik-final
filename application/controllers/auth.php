<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Auth extends CI_Controller {
    
    public function logout() {
        // Menghancurkan sesi login
        $this->session->sess_destroy();
        
        // Mengarahkan ke halaman login setelah logout
        redirect('home'); // Ganti dengan URL login halaman yang sesuai
    }
}
