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
        $jenis_kelamin = $this->session->userdata('selected_jenis_kelamin');
        $data['selected_jenis_kelamin'] = $jenis_kelamin;

        // Tentukan prefix berdasarkan jenis kelamin
        $prefix = strtolower($jenis_kelamin) === 'betina' ? 'CB' : 'CJ';

        // Ambil semua kriteria berdasarkan jenis kelamin
        $existing_kriteria = $this->Kriteria_model->get_by_jenis_kelamin($jenis_kelamin);

        // Tentukan nomor terakhir
        $last_number = 0;
        foreach ($existing_kriteria as $kriteria) {
            if (strpos($kriteria->kode_kriteria, $prefix) === 0) {
                $number = intval(substr($kriteria->kode_kriteria, strlen($prefix)));
                if ($number > $last_number) {
                    $last_number = $number;
                }
            }
        }
        $next_number = $last_number + 1;
        $kode_kriteria = $prefix . $next_number;

        // Kirim ke view
        $data['kode_kriteria'] = $kode_kriteria;

        $this->load->view('kriteria/create', $data);
    }


    //menambahkan data ke database
    public function store()
    {
        // Ambil jenis kelamin dari session
        $jenis_kelamin = $this->session->userdata('selected_jenis_kelamin');

        // Validasi input
        $this->form_validation->set_rules('keterangan', 'Keterangan', 'required');
        $this->form_validation->set_rules('bobot', 'Bobot', 'required|numeric|greater_than[0]|less_than_equal_to[1]');
        $this->form_validation->set_rules('jenis', 'Jenis', 'required');

        $this->form_validation->set_message('less_than_equal_to', 'Bobot tidak boleh lebih dari 1.0');
        $this->form_validation->set_message('greater_than', 'Bobot harus lebih dari 0');
        $this->form_validation->set_message('numeric', 'Bobot harus berupa angka');

        if ($this->form_validation->run() == false) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">' . validation_errors() . '</div>');
            redirect('Kriteria/create');
            return;
        }

        // Ambil dan proses nilai bobot
        $bobot = round(floatval($this->input->post('bobot')), 6);

        // Tentukan prefix berdasarkan jenis kelamin
        $prefix = strtolower($jenis_kelamin) === 'betina' ? 'CB' : 'CJ';

        // Ambil semua kriteria yang sudah ada untuk jenis kelamin ini
        $existing_kriteria = $this->Kriteria_model->get_by_jenis_kelamin($jenis_kelamin);

        // Cari nomor urut tertinggi dari kode kriteria yang sudah ada
        $last_number = 0;
        foreach ($existing_kriteria as $kriteria) {
            if (strpos($kriteria->kode_kriteria, $prefix) === 0) {
                $number = intval(substr($kriteria->kode_kriteria, strlen($prefix)));
                if ($number > $last_number) {
                    $last_number = $number;
                }
            }
        }
        $next_number = $last_number + 1;
        $kode_kriteria = $prefix . $next_number;

        // Cek duplikasi kode (jika ada konflik tak terduga)
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

        // Siapkan data untuk disimpan
        $data = [
            'keterangan'     => $this->input->post('keterangan'),
            'kode_kriteria'  => $kode_kriteria,
            'bobot'          => $bobot,
            'jenis'          => $this->input->post('jenis'),
            'jenis_kelamin'  => $jenis_kelamin
        ];

        // Simpan ke database
        if ($this->Kriteria_model->insert($data)) {
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Data berhasil disimpan dengan kode <strong>' . $kode_kriteria . '</strong>!</div>');
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Gagal menyimpan data!</div>');
        }

        // Redirect sesuai jenis kelamin
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
        $jenis_kelamin = $this->session->userdata('selected_jenis_kelamin');

        $this->form_validation->set_rules('keterangan', 'Keterangan', 'required');
        $this->form_validation->set_rules('bobot', 'Bobot', 'required|numeric|greater_than[0]|less_than_equal_to[1]');
        $this->form_validation->set_rules('jenis', 'Jenis', 'required');

        // Ubah pesan validasi (jika ingin custom)
        $this->form_validation->set_message([
            'less_than_equal_to' => '{field} tidak boleh lebih dari 1.0',
            'greater_than'       => '{field} harus lebih dari 0',
            'numeric'            => '{field} harus berupa angka',
            'required'           => '{field} wajib diisi'
        ]);

        if ($this->form_validation->run() == false) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger">Validasi gagal: ' . validation_errors() . '</div>');
            redirect('Kriteria/edit/' . $id_kriteria);
            return;
        }

        $kode_kriteria = $this->input->post('kode_kriteria');
        $bobot         = floatval($this->input->post('bobot'));
        $bobot_lama    = floatval($this->Kriteria_model->show($id_kriteria)->bobot);

        // Cek duplikasi kode jika diganti secara manual (opsional, bisa dihapus jika read-only)
        if ($this->Kriteria_model->is_duplicate_kode($kode_kriteria, $id_kriteria)) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger">Kode Kriteria sudah digunakan.</div>');
            redirect('Kriteria/edit/' . $id_kriteria);
            return;
        }

        // Hitung ulang total bobot setelah perubahan
        $total_bobot = round($this->Kriteria_model->get_total_bobot_by_jenis_kelamin($jenis_kelamin) - $bobot_lama + $bobot, 6);

        if ($total_bobot > 1.0) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger">Total bobot kriteria tidak boleh lebih dari 1.0. (Saat ini: ' . $total_bobot . ')</div>');
            redirect('Kriteria/edit/' . $id_kriteria);
            return;
        }

        // Siapkan data update
        $data = [
            'keterangan'     => $this->input->post('keterangan'),
            'kode_kriteria'  => $kode_kriteria, // tetap sama, readonly
            'bobot'          => $bobot,
            'jenis'          => $this->input->post('jenis'),
            'jenis_kelamin'  => $jenis_kelamin
        ];

        // Lakukan update
        if ($this->Kriteria_model->update($id_kriteria, $data)) {
            $this->session->set_flashdata('message', '<div class="alert alert-success">Data berhasil diperbarui!</div>');
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger">Gagal memperbarui data.</div>');
        }

        redirect('Kriteria?jenis_kelamin=' . $jenis_kelamin);
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
