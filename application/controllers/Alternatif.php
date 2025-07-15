<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Alternatif extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library(['pagination', 'form_validation']);
        $this->load->model(['Alternatif_model', 'Kolam_model', 'Pemijahan_model']);

        // Cek level pengguna
        if ($this->session->userdata('id_user_level') != "3") {
            echo "<script>alert('Anda tidak berhak mengakses halaman ini!'); window.location='" . base_url("Login/home") . "';</script>";
            exit;
        }
    }
    public function index()
    {
        $id_upr = $this->session->userdata('id_upr');
        $selected_id_pemijahan = $this->session->userdata('id_pemijahan');
    
        $data = [
            'page' => 'Alternatif',
            'kolam_list' => $this->Kolam_model->tampil_by_upr($id_upr),
            'list' => $selected_id_pemijahan
                ? $this->Alternatif_model->get_by_waktu_pemijahan($selected_id_pemijahan)
                : [], // Jika tidak ada id_pemijahan, kirim array kosong
            'pemijahan_list' => $this->Pemijahan_model->get_pemijahan_by_upr($id_upr),
            'selected_id_pemijahan' => $selected_id_pemijahan
        ];
    
        $this->load->view('alternatif/index', $data);
        $this->session->unset_userdata('message');
    }

public function set_waktu_pemijahan()
{
    $id_upr = $this->session->userdata('id_upr');
    $id_pemijahan = $this->input->post('id_pemijahan');

    if ($id_pemijahan == "") {
        // Jika "--Semua Waktu Pemijahan--" dipilih, hapus session id_pemijahan
        $this->session->unset_userdata('id_pemijahan');
        $this->session->set_flashdata('message', '<div class="alert alert-success">Menampilkan semua waktu pemijahan.</div>');
    } elseif ($this->Pemijahan_model->exists_for_upr($id_pemijahan, $id_upr)) {
        // Jika waktu pemijahan valid dipilih, set session id_pemijahan
        $this->session->set_userdata('id_pemijahan', $id_pemijahan);
        $this->session->set_flashdata('message', '<div class="alert alert-success">Waktu pemijahan berhasil dipilih!</div>');
    } else {
        // Jika waktu pemijahan tidak valid
        $this->session->set_flashdata('message', '<div class="alert alert-danger">Pilih waktu pemijahan yang sesuai dengan UPR Anda!</div>');
    }

    redirect('alternatif');
}
public function store()
{
    if (!$this->session->userdata('id_pemijahan')) {
        $this->session->set_flashdata('message', '<div class="alert alert-danger">Pilih waktu pemijahan terlebih dahulu sebelum menambahkan data alternatif.</div>');
        redirect('alternatif');
        return;
    }

    $this->form_validation->set_rules('nama', 'Nama Alternatif', 'required');
    $this->form_validation->set_rules('kolam', 'Kolam', 'required');
    $this->form_validation->set_rules('jenis_kelamin', 'Jenis Kelamin', 'required');

    if ($this->form_validation->run()) {
        $data = [
            'nama' => $this->input->post('nama'),
            'kolam' => $this->input->post('kolam'),
            'jenis_kelamin' => $this->input->post('jenis_kelamin'), // Tambahkan jenis kelamin
            'id_pemijahan' => $this->session->userdata('id_pemijahan'),
            'id_upr' => $this->session->userdata('id_upr') // Tambahkan id_upr
        ];

        if ($this->Alternatif_model->insert($data)) {
            $this->session->set_flashdata('message', '<div class="alert alert-success">Data alternatif berhasil disimpan!</div>');
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger">Gagal menyimpan data alternatif!</div>');
        }
    } else {
        $this->session->set_flashdata('message', '<div class="alert alert-danger">' . validation_errors() . '</div>');
    }

    redirect('alternatif');
}


    public function get_alternatif_by_id($id_alternatif)
    {
        $data = $this->Alternatif_model->show($id_alternatif);
        echo json_encode($data);
    }

    public function update()
{
    $id_alternatif = $this->input->post('id_alternatif');
    $this->form_validation->set_rules('nama', 'Nama Alternatif', 'required');
    $this->form_validation->set_rules('kolam', 'Kolam', 'required');
    $this->form_validation->set_rules('jenis_kelamin', 'Jenis Kelamin', 'required');

    if ($this->form_validation->run()) {
        $data = [
            'nama' => $this->input->post('nama'),
            'kolam' => $this->input->post('kolam'),
            'jenis_kelamin' => $this->input->post('jenis_kelamin'), // Tambahkan jenis kelamin
        ];

        if ($this->Alternatif_model->exists($id_alternatif)) {
            if ($this->Alternatif_model->update($id_alternatif, $data)) {
                $this->session->set_flashdata('message', '<div class="alert alert-success">Data alternatif berhasil diperbarui!</div>');
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger">Gagal memperbarui data alternatif!</div>');
            }
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger">Data alternatif tidak ditemukan!</div>');
        }
    } else {
        $this->session->set_flashdata('message', '<div class="alert alert-danger">' . validation_errors() . '</div>');
    }

    redirect('alternatif');
}


    public function destroy($id_alternatif)
    {
        if ($this->Alternatif_model->exists($id_alternatif)) {
            if ($this->Alternatif_model->delete($id_alternatif)) {
                $this->session->set_flashdata('message', '<div class="alert alert-success">Data alternatif berhasil dihapus!</div>');
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger">Gagal menghapus data alternatif!</div>');
            }
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger">Data alternatif tidak ditemukan!</div>');
        }

        redirect('alternatif');
    }
}
