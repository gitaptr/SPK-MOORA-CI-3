<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Penilaian extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('pagination');
        $this->load->library('form_validation');
        $this->load->model('Penilaian_model');

        if ($this->session->userdata('id_user_level') != "3") {
?>
            <script type="text/javascript">
                alert('Anda tidak berhak mengakses halaman ini!');
                window.location = '<?php echo base_url("Login/home"); ?>'
            </script>
<?php
        }
    }

    public function index()
    {
        $id_upr = $this->session->userdata('id_upr'); // Ambil id_upr dari session
        $id_pemijahan = $this->input->get('id_pemijahan');

        $alternatif = $this->Penilaian_model->get_alternatif_by_waktu_pemijahan($id_pemijahan, $id_upr);
        foreach ($alternatif as $key) {
            $key->kriteria = $this->Penilaian_model->get_kriteria_by_gender($key->jenis_kelamin);
        }

        $data = [
            'page' => "Penilaian",
            'kriteria' => $this->Penilaian_model->get_kriteria(),
            'pemijahan_list' => $this->Penilaian_model->get_all_waktu_pemijahan_by_upr($id_upr),
            'id_pemijahan' => $id_pemijahan,
            'alternatif' => $alternatif,
        ];
        $this->load->view('penilaian/index', $data);
        $this->session->unset_userdata('message');
    }

    public function get_alternatif_by_waktu_pemijahan()
    {
        $id_pemijahan = $this->input->post('id_pemijahan');
        $id_upr = $this->session->userdata('id_upr');

        if (empty($id_pemijahan)) {
            echo json_encode([]);
            return;
        }

        $alternatifs = $this->Penilaian_model->get_alternatif_by_waktu_pemijahan($id_pemijahan, $id_upr);
        echo json_encode($alternatifs);
    }

    public function tambah_penilaian() {
        $id_alternatif = $this->input->post('id_alternatif');
        $id_kriteria = $this->input->post('id_kriteria');
        $nilai = $this->input->post('nilai');
        $id_pemijahan = $this->input->post('id_pemijahan');
        $id_upr = $this->session->userdata('id_upr');

        if (!$this->Penilaian_model->exists_for_upr($id_pemijahan, $id_upr)) {
            show_error("Waktu pemijahan tidak ditemukan untuk id_upr ini!", 403);
        }

        $i = 0;
        foreach ($nilai as $key) {
            $this->Penilaian_model->tambah_penilaian($id_alternatif, $id_kriteria[$i], $key, $id_upr);
            $i++;
        }

        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Data berhasil disimpan!</div>');
        redirect('penilaian?id_pemijahan=' . $id_pemijahan);
    }

    public function update_penilaian() {
        $id_alternatif = $this->input->post('id_alternatif');
        $id_kriteria = $this->input->post('id_kriteria');
        $nilai = $this->input->post('nilai');
        $id_upr = $this->session->userdata('id_upr');
        $id_pemijahan = $this->input->post('id_pemijahan');

        $i = 0;
        foreach ($nilai as $key) {
            $cek = $this->Penilaian_model->data_penilaian($id_alternatif, $id_kriteria[$i]);

            if ($cek == 0) {
                $this->Penilaian_model->tambah_penilaian($id_alternatif, $id_kriteria[$i], $key, $id_upr);
            } else {
                $this->Penilaian_model->edit_penilaian($id_alternatif, $id_kriteria[$i], $key, $id_upr);
            }
            $i++;
        }

        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Data berhasil diupdate!</div>');
        redirect('penilaian?id_pemijahan=' . $id_pemijahan);
    }
    
}
