<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Register_model extends CI_Model {
    public function get_all_wilayah()
    {
        return $this->db->get('wilayah')->result_array();
    }

    public function get_all_upr()
    {
        return $this->db->get('upr')->result_array(); // Ambil daftar UPR yang sudah ada
    }

    public function save_user($data)
    {
        return $this->db->insert('user', $data);
    }
}
