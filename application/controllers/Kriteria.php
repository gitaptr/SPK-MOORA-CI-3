<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Kriteria extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('pagination');
        $this->load->library('form_validation');
        $this->load->model('Kriteria_model');

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
        $this->read_only = ($level == "3");
    }


    public function index()
    {
        $jenis_kelamin = $this->input->get('jenis_kelamin');

        // Simpan jenis kelamin ke session
        if ($jenis_kelamin) {
            $this->session->set_userdata('selected_jenis_kelamin', $jenis_kelamin);
        } else {
            $this->session->unset_userdata('selected_jenis_kelamin');
        }

        $data['page'] = "Kriteria";
        $data['list'] = $this->Kriteria_model->tampil_by_jenis_kelamin($jenis_kelamin);
        $data['read_only'] = $this->read_only;
        $this->load->view('kriteria/index', $data);
        $this->session->unset_userdata('message');
    }

    //menampilkan view create
    public function create()
    {
        $data['page'] = "Kriteria";
        $data['selected_jenis_kelamin'] = $this->session->userdata('selected_jenis_kelamin');
        $this->load->view('kriteria/create', $data);
    }

    //menambahkan data ke database
    public function store()
    {
        // Ambil jenis kelamin dari session
        $jenis_kelamin = $this->session->userdata('selected_jenis_kelamin');

        // Validasi input
        $this->form_validation->set_rules('keterangan', 'Keterangan', 'required');
        $this->form_validation->set_rules('kode_kriteria', 'Kode Kriteria', 'required');
        $this->form_validation->set_rules('bobot', 'Bobot', 'required|numeric|greater_than[0]|less_than_equal_to[1]');
        $this->form_validation->set_rules('jenis', 'Jenis', 'required');

        if ($this->form_validation->run() == false) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">' . validation_errors() . '</div>');
            redirect('Kriteria/create');
            return;
        }

        // Ambil data dari form
        $kode_kriteria = $this->input->post('kode_kriteria');
        $bobot = round(floatval($this->input->post('bobot')), 6); // Pastikan float dan bulatkan hingga 6 desimal

        // Cek duplikasi kode
        if ($this->Kriteria_model->is_duplicate_kode($kode_kriteria)) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Kode Kriteria sudah digunakan!</div>');
            redirect('Kriteria/create');
            return;
        }

        // Validasi total bobot berdasarkan jenis kelamin
        $total_bobot = round(floatval($this->Kriteria_model->get_total_bobot_by_jenis_kelamin($jenis_kelamin)), 6);
        $total_setelah_tambah = round($total_bobot + $bobot, 6);

        if ($total_setelah_tambah > 1.0) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Total bobot kriteria tidak boleh lebih dari 1.0! (Saat ini: ' . $total_setelah_tambah . ')</div>');
            redirect('Kriteria/create');
            return;
        }

        // Data yang akan dimasukkan ke database
        $data = [
            'keterangan' => $this->input->post('keterangan'),
            'kode_kriteria' => $kode_kriteria,
            'bobot' => $bobot,
            'jenis' => $this->input->post('jenis'),
            'jenis_kelamin' => $jenis_kelamin, // Ambil dari session
        ];

        // Simpan ke database
        if ($this->Kriteria_model->insert($data)) {
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Data berhasil disimpan!</div>');
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Gagal menyimpan data!</div>');
        }

        // Redirect ke halaman yang sesuai dengan jenis kelamin yang dipilih
        if ($jenis_kelamin) {
            redirect('Kriteria?jenis_kelamin=' . $jenis_kelamin);
        } else {
            redirect('Kriteria');
        }
    }


    public function edit($id_kriteria)
    {
        $data['page'] = "Kriteria";
        $data['kriteria'] = $this->Kriteria_model->show($id_kriteria);
        $data['selected_jenis_kelamin'] = $this->session->userdata('selected_jenis_kelamin');
        $this->load->view('kriteria/edit', $data);
    }

    // Pada function update di controller Kriteria:
    public function update($id_kriteria)
    {
        // Ambil jenis kelamin dari session
        $jenis_kelamin = $this->session->userdata('selected_jenis_kelamin');

        // Validasi input
        $this->form_validation->set_rules('keterangan', 'Keterangan', 'required');
        $this->form_validation->set_rules('kode_kriteria', 'Kode Kriteria', 'required');
        $this->form_validation->set_rules('bobot', 'Bobot', 'required|numeric|greater_than[0]|less_than_equal_to[1]');
        $this->form_validation->set_rules('jenis', 'Jenis', 'required');

        // Cek duplikasi kode kriteria
        $kode_kriteria = $this->input->post('kode_kriteria');
        if ($this->Kriteria_model->is_duplicate_kode($kode_kriteria, $id_kriteria)) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger">Kode Kriteria sudah digunakan.</div>');
            redirect('Kriteria/edit/' . $id_kriteria);
            return;
        }

        // Cek total bobot dengan pembulatan untuk menghindari floating-point error
        $bobot = floatval($this->input->post('bobot')); // Konversi eksplisit ke float
        $bobot_lama = floatval($this->Kriteria_model->show($id_kriteria)->bobot);
        $total_bobot = round($this->Kriteria_model->get_total_bobot_by_jenis_kelamin($jenis_kelamin) - $bobot_lama + $bobot, 6); // Dibulatkan ke 6 desimal

        if ($total_bobot > 1.0) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger">Total bobot kriteria tidak boleh lebih dari 1.0. (Saat ini: ' . $total_bobot . ')</div>');
            redirect('Kriteria/edit/' . $id_kriteria);
            return;
        }

        if ($this->form_validation->run() == false) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger">Validasi gagal: ' . validation_errors() . '</div>');
            redirect('Kriteria/edit/' . $id_kriteria);
            return;
        }

        // Ambil data dari form
        $data = [
            'keterangan' => $this->input->post('keterangan'),
            'kode_kriteria' => $this->input->post('kode_kriteria'),
            'bobot' => $bobot, // Gunakan bobot yang telah dikonversi ke float
            'jenis' => $this->input->post('jenis'),
            'jenis_kelamin' => $jenis_kelamin, // Ambil dari session
        ];

        // Proses update ke database
        if ($this->Kriteria_model->update($id_kriteria, $data)) {
            $this->session->set_flashdata('message', '<div class="alert alert-success">Data berhasil diperbarui!</div>');
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger">Data gagal diperbarui. Silakan coba lagi.</div>');
        }

        // Redirect ke halaman yang sesuai dengan jenis kelamin yang dipilih
        if ($jenis_kelamin) {
            redirect('Kriteria?jenis_kelamin=' . $jenis_kelamin);
        } else {
            redirect('Kriteria');
        }
    }

    public function check_duplicate_kode()
    {
        $kode_kriteria = $this->input->get('kode_kriteria');
        $exclude_id = $this->input->get('exclude_id');
        $jenis_kelamin = $this->input->get('jenis_kelamin');

        $is_duplicate = $this->Kriteria_model->is_kode_exists($kode_kriteria, $exclude_id, $jenis_kelamin);

        echo json_encode(['is_duplicate' => $is_duplicate]);
    }

    public function get_total_bobot_by_jenis_kelamin()
    {
        $jenis_kelamin = $this->input->get('jenis_kelamin');
        $total_bobot = $this->Kriteria_model->get_total_bobot($jenis_kelamin);

        echo json_encode(['total_bobot' => $total_bobot]);
    }
    public function destroy($id_kriteria)
    {
        $jenis_kelamin = $this->input->get('jenis_kelamin');
        $this->Kriteria_model->delete($id_kriteria);
        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Data berhasil dihapus!</div>');

        redirect('Kriteria?jenis_kelamin=' . $jenis_kelamin);
    }
}
