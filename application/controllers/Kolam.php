<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Kolam extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Kolam_model');

        if (!$this->session->userdata('id_user_level')) {
            redirect('Login/home');
        }
    }

    public function index()
    {
        $id_upr = $this->session->userdata('id_upr');
        $id_user_level = $this->session->userdata('id_user_level');
        $id_wilayah = $this->session->userdata('id_wilayah');

        if ($id_user_level == 3) {
            $list = $this->Kolam_model->tampil_by_upr($id_upr);
        } elseif ($id_user_level == 2) {
            $list = $this->Kolam_model->tampil_by_wilayah($id_wilayah);
        } else {
            $list = $this->Kolam_model->tampil();
        }

        $data = [
            'page' => "Kolam",
            'list' => $list
        ];

        $this->load->view('kolam/index', $data);
    }

    public function store()
    {
        $id_upr = $this->session->userdata('id_upr');
        $id_user_level = $this->session->userdata('id_user_level');

        // Cek apakah user memiliki level 3 (UPR)
        if ($id_user_level != 3) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger">Anda tidak memiliki izin untuk menambahkan data kolam!</div>');
            redirect('kolam');
            return;
        }

        // Cek apakah id_upr tidak kosong
        if (!$id_upr) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger">Kesalahan: ID UPR tidak ditemukan!</div>');
            redirect('kolam');
            return;
        }

        $data = [
            'kode_kolam' => $this->input->post('kode_kolam'),
            'luas_kolam' => $this->input->post('luas_kolam'),
            'kapasitas' => $this->input->post('kapasitas'),
            'id_upr' => $id_upr
        ];

        $this->Kolam_model->insert($data);
        $this->session->set_flashdata('message', '<div class="alert alert-success">Data kolam berhasil ditambahkan!</div>');
        redirect('kolam');
    }

    public function show()
    {
        $id_kolam = $this->input->get('id_kolam');
        $data = $this->Kolam_model->show($id_kolam);
        echo json_encode($data); // Kirim data sebagai JSON
    }

    // Memperbarui data
    public function update()
    {
        $id_kolam = $this->input->post('id_kolam');
        $data = [
            'kode_kolam' => $this->input->post('kode_kolam'),
            'luas_kolam' => $this->input->post('luas_kolam'),
            'kapasitas' => $this->input->post('kapasitas')
        ];

        // Log data untuk debugging
        log_message('info', 'Data ID: ' . $id_kolam);
        log_message('info', 'Data untuk update: ' . print_r($data, true));

        $this->form_validation->set_rules('kode_kolam', 'Kode Kolam', 'required');
        $this->form_validation->set_rules('luas_kolam', 'Luas Kolam', 'required|integer');
        $this->form_validation->set_rules('kapasitas', 'Kapasitas', 'required|integer');

        if ($this->form_validation->run() !== false) {
            $result = $this->Kolam_model->update($id_kolam, $data);

            if ($result) {
                log_message('info', 'Update berhasil.');
                $this->session->set_flashdata('message', '<div class="alert alert-success">Data berhasil diperbarui!</div>');
            } else {
                log_message('error', 'Update gagal.');
                $this->session->set_flashdata('message', '<div class="alert alert-danger">Gagal memperbarui data!</div>');
            }
        } else {
            log_message('error', 'Validasi gagal: ' . validation_errors());
            $this->session->set_flashdata('message', '<div class="alert alert-danger">Validasi gagal: ' . validation_errors() . '</div>');
        }

        redirect('kolam');
    }


    public function destroy($id_kolam)
    {
        $this->Kolam_model->delete($id_kolam);
        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Data berhasil dihapus!</div>');
        redirect('kolam');
    }

    public function grafik_kolam_per_upr($tahun = null)
    {
        $id_wilayah = $this->session->userdata('id_wilayah');
        $data = $this->Kolam_model->get_kolam_luas_per_upr($id_wilayah, $tahun);
    
        echo json_encode($data);
    }
}
