<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Login_model extends CI_Model
{
    function logged_id()
    {
        return $this->session->userdata('id_user');
    }

    public function login($username, $password)
    {
        $this->db->select('*');
        $this->db->from('user');
        $this->db->where('username', $username);
        $this->db->where('password', $password);
        return $this->db->get()->row(); // hanya return, tidak redirect
    }


    // public function login($username, $password)
    // {
    //     $this->db->select('*');
    //     $this->db->from('user');
    //     $this->db->where('username', $username);
    //     $this->db->where('password', $password);

    //     $query = $this->db->get();
    //     $user = $query->row();

    //     if ($user) {
    //         // Tentukan id_upr hanya jika user level = 3, jika tidak, set 0
    //         $id_upr = ($user->id_user_level == 3) ? $user->id_upr : 0;

    //         // Ambil id_wilayah jika user adalah penyuluh (id_user_level = 2)
    //         $id_wilayah = ($user->id_user_level == 2) ? $user->id_wilayah : 0;

    //         // Simpan ke dalam session
    //         $session_data = [
    //             'id_user' => $user->id_user,
    //             'username' => $user->username,
    //             'id_user_level' => $user->id_user_level,
    //             'id_upr' => $id_upr, // Jika user level ≠ 3, id_upr = 0
    //             'id_wilayah' => $id_wilayah, // Tambahkan ini
    //             'status' => 'Logged'
    //         ];

    //         $this->session->set_userdata($session_data);
    //         redirect('Login/home');
    //     } else {
    //         $this->session->set_flashdata('message', '<div class="alert alert-danger">Username atau password salah!</div>');
    //         redirect('login');
    //     }
    // }
}
