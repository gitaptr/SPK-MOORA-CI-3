<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Stock extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('pagination');
        $this->load->library('form_validation');
        $this->load->model('Stock_model');
        $this->load->model('Kolam_model');
        $this->load->model('Hasilpmj_model');

        if ($this->session->userdata('id_user_level') != "3") {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Anda tidak berhak mengakses halaman ini!</div>');
            redirect('Login/home');
        }
    }

    public function index()
    {
        $id_upr = $this->session->userdata('id_upr');

        $data['page'] = "Stock";
        $data['list'] = $this->Stock_model->tampil_by_upr($id_upr);
        $data['id_upr'] = $id_upr;        
        $this->load->view('Stock/index', $data); // Load view dan kirim data
        $this->session->unset_userdata('message');
    }

    //menampilkan view create
    public function create()
    {
        $data['page'] = "Stock";
        $id_upr = $this->session->userdata('id_upr');
        $data['kolam_list'] = $this->Kolam_model->get_kolam_by_upr($id_upr);
        $this->load->view('stock/create', $data);
    }

    //menambahkan data ke database
    public function store()
    {

        $id_upr = $this->session->userdata('id_upr');

        $data = [
            'tanggal' => $this->input->post('tanggal'),
            'jumlah' => $this->input->post('jumlah'),
            'ukuran' => $this->input->post('ukuran'),
            'kolam' => $this->input->post('kolam'),
            'umur' => $this->input->post('umur'),
            'sumber' => $this->input->post('sumber'),
            'keterangan' => $this->input->post('keterangan'),
            'id_upr'      => $id_upr,
        ];

        $this->form_validation->set_rules('tanggal', 'Tanggal', 'required');
        $this->form_validation->set_rules('jumlah', 'Jumlah', 'required');
        $this->form_validation->set_rules('ukuran', 'Ukuran', 'required');
        $this->form_validation->set_rules('kolam', 'Kolam', 'required');
        $this->form_validation->set_rules('umur', 'Umur', 'required');
        $this->form_validation->set_rules('sumber', 'Sumber', 'required');
        $this->form_validation->set_rules('keterangan', 'Keterangan', 'required');

        // validasi form
        $this->form_validation->set_rules('tanggal', 'Tanggal', 'required');
        // ... aturan validasi lainnya

        if ($this->form_validation->run()) {
            // insert ke model
            $result = $this->Stock_model->insert($data);
            if ($result) {
                $this->session->set_flashdata(
                    'message',
                    '<div class="alert alert-success">Data berhasil disimpan!</div>'
                );
                redirect('stock');
            } else {
                // kemungkinan duplikat atau error lain
                $this->session->set_flashdata(
                    'message',
                    '<div class="alert alert-danger">Gagal menyimpan data ke database!</div>'
                );
                redirect('stock/create');
            }
        } else {
            // kirim kembali data lama ke view agar tetap terisi
            $data['form_data'] = $data;
            $this->session->set_flashdata(
                'message',
                '<div class="alert alert-danger">Validasi gagal!</div>'
            );
            $data['kolam_list'] = $this->Kolam_model->get_kolam_by_upr($id_upr);
            $this->load->view('stock/create', $data);
        }
    }

    public function edit($id_stok_benih)
    {
        $data['page'] = "Stock";
        $data['stock'] = $this->Stock_model->get_with_pemijahan($id_stok_benih);
        $id_upr = $this->session->userdata('id_upr');
        $data['kolam_list'] = $this->Kolam_model->get_kolam_by_upr($id_upr); // Ambil semua data kolam
        $this->load->view('stock/edit', $data);
    }

    public function update($id_stok_benih)
    {
        // Start database transaction
        $this->db->trans_begin();

        try {
            // Ambil data stok untuk cek apakah dari pemijahan
            $stock = $this->Stock_model->get_by_id($id_stok_benih);

            // Siapkan data update
            $data = [
                'tanggal'    => $this->input->post('tanggal'),
                'ukuran'     => $this->input->post('ukuran'),
                'kolam'      => $this->input->post('kolam'),
                'umur'       => $this->input->post('umur'),
                'sumber'     => $this->input->post('sumber'),
                'keterangan' => $this->input->post('keterangan')
            ];

            // Hanya update jumlah jika stok BUKAN dari pemijahan
            if (empty($stock->waktu_pemijahan)) {
                $data['jumlah'] = $this->input->post('jumlah');
            }

            // Lakukan update
            $this->Stock_model->update($id_stok_benih, $data);

            // Jika dari pemijahan, update jumlah berdasarkan data pemijahan
            if (!empty($stock->waktu_pemijahan)) {
                $hasilpmj = $this->Hasilpmj_model->get_by_waktu($stock->waktu_pemijahan);

                if ($hasilpmj) {
                    // Pastikan nama kolom benar, misalnya 'jumlah'
                    $this->Stock_model->update($id_stok_benih, [
                        'jumlah' => $hasilpmj->jumlah_benih
                    ]);
                }
            }

            $this->db->trans_commit();
            $this->session->set_flashdata('message', '<div class="alert alert-success">Data berhasil diupdate!</div>');
        } catch (Exception $e) {
            $this->db->trans_rollback();
            $this->session->set_flashdata('message', '<div class="alert alert-danger">Gagal mengupdate data: ' . $e->getMessage() . '</div>');
        }

        redirect('stock');
    }

    public function destroy($id_stok_benih)
    {
        $stock = $this->Stock_model->show($id_stok_benih);
        if ($stock) {
            $this->Stock_model->delete($id_stok_benih);
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Data berhasil dihapus!</div>');
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Data tidak ditemukan!</div>');
        }
        redirect('stock');
    }

    public function cetak_laporan()
    {
        $id_user_level = $this->session->userdata('id_user_level');
        $id_upr = $this->session->userdata('id_upr');

        // Validasi akses
        if ($id_user_level != 3) {
            $id_upr = $this->session->userdata('id_upr');
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Anda tidak berhak mengakses halaman ini!</div>');
            redirect('Stock');
        }

        // Ambil data UPR
        $this->db->select('upr.*, wilayah.nama_wilayah');
        $this->db->from('upr');
        $this->db->join('wilayah', 'wilayah.id_wilayah = upr.id_wilayah', 'left');
        $this->db->where('upr.id_upr', $id_upr);
        $upr_info = $this->db->get()->row();

        // Siapkan data untuk view
        $data = [
            'upr_nama' => $upr_info->nama_upr ?? 'UPR Tidak Diketahui',
            'upr_wilayah' => $upr_info->nama_wilayah ?? 'Wilayah Tidak Diketahui',
            'list' => $this->Stock_model->get_stok_for_report($id_upr),
            'total_jumlah' => $this->Stock_model->get_total_jumlah_benih($id_upr)
        ];

        $this->load->view('laporanstok', $data);
    }
}
