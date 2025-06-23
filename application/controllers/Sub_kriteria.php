<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Sub_kriteria extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('pagination');
        $this->load->library('form_validation');
        $this->load->model('Sub_Kriteria_model');

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
        $jenis_kelamin = $this->input->get('jenis_kelamin'); // Ambil jenis kelamin dari URL

        $data = [
            'page' => "Sub Kriteria",
            'kriteria' => $this->Sub_Kriteria_model->get_kriteria_by_jenis_kelamin($jenis_kelamin), // Filter kriteria berdasarkan jenis kelamin
            'count_kriteria' => $this->Sub_Kriteria_model->count_kriteria(),
            'read_only' => $this->read_only,
            'selected_jenis_kelamin' => $jenis_kelamin // Simpan jenis kelamin yang dipilih
        ];

        $this->load->view('sub_kriteria/index', $data);
        $this->session->unset_userdata('message');
    }

    //menambahkan data ke database

    public function store()
    {
        $id_kriteria = $this->input->post('id_kriteria');
        $kriteria = $this->Sub_Kriteria_model->get_kriteria_by_id($id_kriteria);

        $data = [
            'id_kriteria' => $id_kriteria,
            'deskripsi' => $this->input->post('deskripsi'),
            'nilai' => $this->input->post('nilai'),
            'jenis_kelamin' => $kriteria->jenis_kelamin ?? null
        ];

        $this->form_validation->set_rules('id_kriteria', 'ID Kriteria', 'required');
        $this->form_validation->set_rules('deskripsi', 'Deskripsi', 'required');
        $this->form_validation->set_rules('nilai', 'Nilai', 'required');

        if ($this->form_validation->run() != false) {
            $result = $this->Sub_Kriteria_model->insert($data);
            if ($result) {
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Data berhasil disimpan!',
                    'jenis_kelamin' => $data['jenis_kelamin']
                ]);
                return;
            }
        }

        echo json_encode([
            'status' => 'error',
            'message' => validation_errors('<div class="alert alert-danger">', '</div>')
        ]);
    }
    public function update($id_sub_kriteria)
    {
        // Validasi input
        $this->form_validation->set_rules('id_kriteria', 'ID Kriteria', 'required');
        $this->form_validation->set_rules('deskripsi', 'Deskripsi', 'required');
        $this->form_validation->set_rules('nilai', 'Nilai', 'required');

        if ($this->form_validation->run() == false) {
            echo json_encode([
                'status' => 'error',
                'message' => validation_errors('<div class="alert alert-danger">', '</div>')
            ]);
            return;
        }

        // Jika validasi berhasil, update data
        $data = [
            'id_kriteria' => $this->input->post('id_kriteria'),
            'deskripsi' => $this->input->post('deskripsi'),
            'nilai' => $this->input->post('nilai')
        ];

        $result = $this->Sub_Kriteria_model->update($id_sub_kriteria, $data);

        if ($result) {
            // Ambil jenis kelamin dari data yang sudah ada
            $sub_kriteria = $this->Sub_Kriteria_model->show($id_sub_kriteria);
            $jenis_kelamin = $sub_kriteria->jenis_kelamin;

            echo json_encode([
                'status' => 'success',
                'message' => 'Data berhasil diupdate!',
                'jenis_kelamin' => $jenis_kelamin
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => '<div class="alert alert-danger">Gagal mengupdate data!</div>'
            ]);
        }
    }
    public function destroy($id_sub_kriteria)
    {
        $this->Sub_Kriteria_model->delete($id_sub_kriteria);
        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Data berhasil dihapus!</div>');
        redirect('sub_kriteria');
    }
}
