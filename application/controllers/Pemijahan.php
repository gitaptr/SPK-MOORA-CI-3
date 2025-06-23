<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Pemijahan extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('pagination');
        $this->load->library('form_validation');
        $this->load->model('Pemijahan_model');
        $this->load->model('Kolam_model');
        $this->load->model('User_model'); // Pastikan ini ada
        $this->load->model('Wilayah_model');

        $level = $this->session->userdata('id_user_level');

        if ($level != "2" && $level != "3") {
            // Redirect jika level tidak sesuai
?>
            <script type="text/javascript">
                alert('Anda tidak berhak mengakses halaman ini!');
                window.location = '<?php echo base_url("Login/home"); ?>';
            </script>
<?php
        }

        // Tambahkan properti read_only untuk level 3
        $this->read_only = ($level == "2");
    }

    public function index()
    {
        // Get session data
        $id_upr = $this->session->userdata('id_upr');
        $id_user_level = $this->session->userdata('id_user_level');
        $id_wilayah = $this->session->userdata('id_wilayah');

        // Get selected UPR from dropdown filter
        $selected_upr = $this->input->get('upr_id');

        // Data retrieval logic
        if ($id_user_level == 3) {
            // Untuk level 3, gunakan id_upr dari session
            $list = $this->Pemijahan_model->tampil_by_upr($id_upr);
        } elseif ($id_user_level == 2) {
            // For penyuluh (level 2), filter by wilayah or selected UPR
            $list = $selected_upr
                ? $this->Pemijahan_model->tampil_by_upr($selected_upr) // <--- Ubah ini: Hapus $id_user_level
                : $this->Pemijahan_model->tampil_by_wilayah($id_wilayah);

            $data['upr_list'] = $this->User_model->get_upr_by_wilayah($id_wilayah);
        } else {
            $list = $this->Pemijahan_model->tampil();
        }

        $data = [
            'page' => "Pemijahan",
            'list' => $list,
            'read_only' => $this->read_only,
            'selected_upr' => $selected_upr,
            'user_level' => $id_user_level, // Ini sudah benar
            'id_user_level' => $id_user_level, // Tambahkan ini
            'upr_list' => $id_user_level == 2 ? $this->User_model->get_upr_by_wilayah($id_wilayah) : []
        ];

        $this->load->view('pemijahan/index', $data);
    }

    //menampilkan view create
    public function create()
    {
        if ($this->read_only) {
            show_error('Anda tidak memiliki hak akses untuk menambahkan data.', 403, 'Akses Ditolak');
        }

        $data['page'] = "Pemijahan";
        $id_upr = $this->session->userdata('id_upr');
        $id_user_level = $this->session->userdata('id_user_level');
        $data['kolam_list'] = $this->Kolam_model->get_all_kolam($id_upr);




        log_message('debug', 'Kolam List: ' . print_r($data['kolam_list'], true));

        $this->load->view('pemijahan/create', $data);
    }

    //menambahkan data ke database
    public function store()
    {
        $id_upr = $this->session->userdata('id_upr');

        if (!$id_upr) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger">Kesalahan: ID UPR tidak ditemukan!</div>');
            redirect('pemijahan');
            return;
        }

        $data = [
            'waktu_pemijahan' => date('Y-m-d', strtotime($this->input->post('waktu_pemijahan'))),
            'jumlah_indukk' => $this->input->post('jumlah_indukk'),
            'kolam' => $this->input->post('kolam'),
            'metode_pemijahan' => $this->input->post('metode_pemijahan'),
            'status' => 0, // Set default status menjadi 0 (belum diproses)
            'id_upr' => $id_upr  // Menyimpan id_upr saat insert
        ];

        $this->form_validation->set_rules('waktu_pemijahan', 'Waktu Pemijahan', 'required');
        $this->form_validation->set_rules('jumlah_indukk', 'Jumlah Induk', 'required');
        $this->form_validation->set_rules('kolam', 'Kolam', 'required');
        $this->form_validation->set_rules('metode_pemijahan', 'Metode Pemijahan', 'required');

        if ($this->form_validation->run() != false) {
            $result = $this->Pemijahan_model->insert($data);
            if ($result) {
                $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Data berhasil disimpan!</div>');
                redirect('Pemijahan');
            }
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Data gagal disimpan!</div>');
            redirect('Pemijahan/create');
        }
    }


    public function edit($id_pemijahan)
    {
        $id_upr = $this->session->userdata('id_upr');

        if ($this->read_only) {
            show_error('Anda tidak memiliki hak akses untuk mengedit data.', 403, 'Akses Ditolak');
        }

        $data['page'] = "Pemijahan";
        $data['pemijahan'] = $this->Pemijahan_model->show($id_pemijahan);
        $data['kolam_list'] = $this->Kolam_model->get_all_kolam($id_upr); // Data kolam untuk dropdown
        $data['selected_kolam'] = $data['pemijahan']->kolam;

        $this->load->view('pemijahan/edit', $data);
    }

    public function update($id_pemijahan)
    {
        if ($this->read_only) {
            show_error('Anda tidak memiliki hak akses untuk mengupdate data.', 403, 'Akses Ditolak');
        }
        $data = [
            'waktu_pemijahan' => date('Y-m-d', strtotime($this->input->post('waktu_pemijahan'))), // Gunakan format Y-m-d untuk MySQL
            'jumlah_indukk' => $this->input->post('jumlah_indukk'),
            'kolam' => $this->input->post('kolam'),
            'metode_pemijahan' => $this->input->post('metode_pemijahan'),
            'status' => $this->input->post('status')
        ];


        if ($this->Pemijahan_model->update($id_pemijahan, $data)) {
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Data berhasil diupdate!</div>');
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Gagal mengupdate data!</div>');
        }
        redirect('pemijahan');
    }

    public function destroy($id_pemijahan)
    {
        if ($this->read_only) {
            show_error('Anda tidak memiliki hak akses untuk menghapus data.', 403, 'Akses Ditolak');
        }

        if ($this->Pemijahan_model->delete($id_pemijahan)) {
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Data berhasil dihapus!</div>');
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Gagal menghapus data!</div>');
        }
        redirect('pemijahan');
    }


    public function cetak_laporan()
    {
        $id_user_level = $this->session->userdata('id_user_level');
        $id_upr = $this->session->userdata('id_upr'); // Ambil id_upr milik user (UPR)

        if ($id_user_level == 3) {
            // Ambil data UPR berdasarkan id_upr yang dimiliki user level 3
            $this->db->select('upr.*, wilayah.nama_wilayah');
            $this->db->from('upr');
            $this->db->join('wilayah', 'wilayah.id_wilayah = upr.id_wilayah', 'left');
            $this->db->where('upr.id_upr', $id_upr);
            $upr_info = $this->db->get()->row();

            $data['upr_nama'] = $upr_info->nama_upr ?? 'UPR Tidak Diketahui';
            $data['upr_wilayah'] = $upr_info->nama_wilayah ?? 'Wilayah Tidak Diketahui';

            // Ambil data pemijahan sesuai UPR user_level 3
            $data['list'] = $this->Pemijahan_model->tampil_by_upr($id_user_level, $id_upr);
        } elseif ($id_user_level == 2) {
            $upr_id = $this->input->get('upr_id');
            $this->db->select('upr.*, wilayah.nama_wilayah');
            $this->db->from('upr');
            $this->db->join('wilayah', 'wilayah.id_wilayah = upr.id_wilayah', 'left');
            $this->db->where('upr.id_upr', $upr_id);
            $upr_info = $this->db->get()->row();

            $data['upr_nama'] = $upr_info->nama_upr ?? 'UPR Tidak Diketahui';
            $data['upr_wilayah'] = $upr_info->nama_wilayah ?? 'Wilayah Tidak Diketahui';

            $data['list'] = $this->Pemijahan_model->get_by_upr($upr_id);
        } else {
            show_error('Anda tidak memiliki izin untuk mengakses halaman ini.', 403);
        }

        $this->load->view('laporannn', $data);
    }



    public function getGrafikPemijahan($tahun = null)
    {
        $id_upr = $this->session->userdata('id_upr'); // Ambil id_upr dari sesi pengguna yang login
        $this->load->model('Pemijahan_model');
        $data = $this->Pemijahan_model->getTotalPemijahanPerBulan($id_upr, $tahun);

        header('Content-Type: application/json'); // Pastikan format JSON
        echo json_encode($data ?: []); // Pastikan selalu mengembalikan array kosong jika tidak ada data
    }

    public function grafik_status_pemijahan($tahun = null)
    {
        $id_upr = $this->session->userdata('id_upr'); // Ambil ID UPR dari sesi pengguna
        $data = $this->Pemijahan_model->get_status_pemijahan($id_upr, $tahun);
        echo json_encode($data);
    }

    public function grafik_pemijahan_per_upr($tahun = null)
    {
        $id_wilayah = $this->session->userdata('id_wilayah');
        $data = $this->Pemijahan_model->get_total_pemijahan_per_upr($id_wilayah, $tahun);
        echo json_encode($data);
    }
}
