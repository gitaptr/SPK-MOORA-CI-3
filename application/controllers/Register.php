<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Register extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->model('Register_model');
    }

    public function index()
    {
        // Ambil data wilayah & UPR untuk ditampilkan di form
        $data['wilayah'] = $this->Register_model->get_all_wilayah();
        $data['upr'] = $this->Register_model->get_all_upr();
        $this->load->view('register', $data);
    }

    public function save()
    {
        $this->load->helper('security');

        $id_upr = $this->input->post('id_upr', TRUE);

        // Ambil nama UPR berdasarkan ID yang dipilih
        $upr = $this->db->get_where('upr', ['id_upr' => $id_upr])->row();

        $data = [
            'nama' => $upr ? $upr->nama_upr : NULL,  // Simpan nama UPR dari tabel UPR
            'username' => $this->input->post('username', TRUE),
            'password' => md5($this->input->post('password', TRUE)),
            'id_wilayah' => $this->input->post('id_wilayah', TRUE),
            'id_upr' => $id_upr,  // Simpan ID UPR
            'id_user_level' => 3,
            'status' => 'Pending'
        ];

        if (empty($data['nama'])) {
            $this->session->set_flashdata('error', 'Nama UPR tidak boleh kosong!');
            redirect('register');
        }

        $insert = $this->Register_model->save_user($data);

        if ($insert) {
            $this->session->set_flashdata('message', 'Registrasi berhasil!');
            redirect('register');
        } else {
            $this->session->set_flashdata('error', 'Registrasi gagal.');
            redirect('register');
        }
    }
}
