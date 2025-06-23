<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Induk extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('pagination');
        $this->load->library('form_validation');
        $this->load->model('Induk_model');
        $this->load->model('Kolam_model');
        $this->load->model('User_model');
        $this->load->model('Wilayah_model');

        // Dapatkan nama method yang sedang diakses
        $method = $this->router->fetch_method();

        // Jika bukan request ke API JSON, lakukan validasi akses
        $allowed_methods = ['grafik_induk_per_upr']; // Daftar method yang boleh diakses tanpa redirect
        if (!in_array($method, $allowed_methods) && $this->session->userdata('id_user_level') != "3") {
            echo json_encode(["error" => "Anda tidak berhak mengakses halaman ini"]);
            exit;
        }
    }


    public function index()
    {
        $id_upr = $this->session->userdata('id_upr');

        $data = [
            'page' => 'Induk',
            'list' => $this->Induk_model->tampil_by_upr($id_upr), // Ambil data induk ikan berdasarkan UPR
            'kolam_list' => $this->Kolam_model->tampil_by_upr($id_upr) // Ambil daftar kolam untuk dropdown
        ];

        // Debugging (Opsional) - Cek apakah data kolam dan induk diambil dengan benar
        log_message('info', 'Data Induk: ' . print_r($data['list'], true));
        log_message('info', 'Data Kolam: ' . print_r($data['kolam_list'], true));

        $this->load->view('Induk/index', $data);
        $this->session->unset_userdata('message');
    }


    public function store()
    {
        $data = [
            'jenis_kelamin' => $this->input->post('jenis_kelamin'),
            'jumlah' => $this->input->post('jumlah'),
            'id_kolam' => $this->input->post('kolam'), // Pastikan menggunakan id_kolam
            'id_upr' => $this->session->userdata('id_upr')
        ];


        $this->form_validation->set_rules('jenis_kelamin', 'Jenis Kelamin', 'required');
        $this->form_validation->set_rules('jumlah', 'Jumlah', 'required|integer');
        $this->form_validation->set_rules('kolam', 'Kolam', 'required');

        if ($this->form_validation->run() !== false) {
            $result = $this->Induk_model->insert($data);
            if ($result) {
                $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Data berhasil disimpan!</div>');
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Gagal menyimpan ke database!</div>');
            }
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Validasi gagal!</div>');
        }
        redirect('induk');
    }



    // Mendapatkan data berdasarkan ID untuk modal edit
    public function get_induk_by_id($id_induk)
    {
        $data = $this->Induk_model->show($id_induk);
        echo json_encode($data);
    }

    // Memperbarui data
    public function update()
    {
        $id_induk = $this->input->post('id_induk');
        $data = [
            'jenis_kelamin' => $this->input->post('jenis_kelamin'),
            'jumlah' => $this->input->post('jumlah'),
            'id_kolam' => $this->input->post('kolam'), // Pastikan menggunakan id_kolam
        ];

        $this->form_validation->set_rules('jenis_kelamin', 'Jenis Kelamin', 'required');
        $this->form_validation->set_rules('jumlah', 'Jumlah', 'required|integer');
        $this->form_validation->set_rules('kolam', 'Kolam', 'required');

        if ($this->form_validation->run() !== false) {
            $result = $this->Induk_model->update($id_induk, $data);
            if ($result) {
                $this->session->set_flashdata('message', '<div class="alert alert-success">Data berhasil diperbarui!</div>');
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger">Gagal memperbarui data!</div>');
            }
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger">Validasi gagal!</div>');
        }

        redirect('induk');
    }


    public function destroy($id_induk)
    {
        $this->Induk_model->delete($id_induk);
        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Data berhasil dihapus!</div>');
        redirect('induk');
    }

    public function grafik_induk_per_upr($tahun = null)
    {
        $id_wilayah = $this->session->userdata('id_wilayah');
        $data = $this->Induk_model->get_jumlah_induk_per_upr($id_wilayah, $tahun);

        echo json_encode($data);
    }
}
