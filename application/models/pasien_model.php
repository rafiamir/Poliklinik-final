<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pasien_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function get_all_pasien() {
        $this->db->select('id, nama, alamat, no_ktp, no_hp, no_rm');
        $query = $this->db->get('pasien');
        return $query->result_array(); // Mengembalikan array asosiatif
    }    
    

    public function insert_pasien($data) {
        $this->db->insert('pasien', $data);
        return $this->db->insert_id(); // Mengembalikan ID pasien yang baru saja dimasukkan
    }

    public function get_pasien_by_id($id) {
        return $this->db->get_where('pasien', ['id' => $id])->row();
    }

    public function update_pasien($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('pasien', $data);
    }

    public function delete_pasien($id) {
        return $this->db->delete('pasien', ['id' => $id]);
    }

    public function generate_no_rm() {
        $this->db->select('no_rm');
        $this->db->from('pasien');
        $this->db->order_by('id', 'DESC');
        $this->db->limit(1);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            $last_no_rm = $query->row()->no_rm;
            $last_year = substr($last_no_rm, 0, 4);
            $last_month = substr($last_no_rm, 4, 2);
            $last_number = substr($last_no_rm, 7, 3);
        } else {
            $last_year = date('Y');
            $last_month = date('m');
            $last_number = '000';
        }

        if ($last_year == date('Y') && $last_month == date('m')) {
            $last_number = str_pad((int)$last_number + 1, 3, '0', STR_PAD_LEFT);
        } else {
            $last_number = '001';
        }

        return $last_year . $last_month . '-' . $last_number;
    }

    public function authenticate($nama, $password) {
        $this->db->where('nama', $nama);
        $query = $this->db->get('pasien');

        if ($query->num_rows() == 1) {
            $user = $query->row();
            if (password_verify($password, $user->password)) {
                return $user;
            }
        }
        return null;
    }
}
?>
