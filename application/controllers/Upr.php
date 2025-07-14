<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Upr extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('pagination');
        $this->load->library('form_validation');
        $this->load->model('Upr_model');

        $id_user_level = $this->session->userdata('id_user_level');

        // Allow access for admin (level 1) and penyuluh (level 2) to specific functions
        if ($id_user_level != "1" && $id_user_level != "2") {
            echo '<script>alert("Anda tidak berhak mengakses halaman ini!"); window.location="' . base_url('Login/home') . '";</script>';
            exit;
        }
    }




    public function index()
    {
        $data = [
            'page' => "UPR",
            'list' => $this->Upr_model->tampil(),
            'wilayah' => $this->Upr_model->get_all_wilayah(),
            'penyuluh' => $this->Upr_model->get_all_penyuluh(),
            'total_upr' => $this->Upr_model->jumlah_upr(),
        ];
        $this->load->view('Upr/index', $data);
        $this->session->unset_userdata('message');
    }

    public function store()
    {
        $data = [
            'nama_upr' => $this->input->post('nama_upr'),
            'id_wilayah' => $this->input->post('id_wilayah'),
            'id_user' => $this->input->post('id_user'),
            'no_hp' => $this->input->post('no_hp'),
        ];

        $this->form_validation->set_rules('nama_upr', 'Nama UPR', 'required');
        $this->form_validation->set_rules('id_wilayah', 'Wilayah', 'required');
        $this->form_validation->set_rules('id_user', 'Penyuluh', 'required');
        $this->form_validation->set_rules('no_hp','No HP','required|numeric|regex_match[/^08[0-9]{8,10}$/]',
            [
                'regex_match' => 'Nomor HP harus diawali dengan 08 dan terdiri dari 10–12 digit.'
            ]
        );

        if ($this->form_validation->run() !== false) {
            $this->Upr_model->insert($data);
            $this->session->set_flashdata('message', '<div class="alert alert-success">Data berhasil disimpan!</div>');
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger">Validasi gagal!</div>');
        }
        redirect('upr');
    }



    public function get_upr_by_id($id)
    {
        $data = $this->Upr_model->getById($id);
        echo json_encode($data);
    }

    public function update()
    {
        $id = $this->input->post('id_upr');
        $data = [
            'nama_upr' => $this->input->post('nama_upr'),
            'id_wilayah' => $this->input->post('id_wilayah'),
            'id_user' => $this->input->post('id_user'),
            'no_hp' => $this->input->post('no_hp')
        ];

        $this->Upr_model->update($id, $data);
        redirect('Upr');
    }



    public function destroy($id_upr)
    {
        $this->Upr_model->delete($id_upr);
        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Data berhasil dihapus!</div>');
        redirect('upr');
    }
}
