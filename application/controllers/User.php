<?php

defined('BASEPATH') or exit('No direct script access allowed');

class User extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('pagination');
        $this->load->library('form_validation');
        $this->load->model('User_model');

        if ($this->session->userdata('id_user_level') != "1") {
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
        $data = [
            'page' => "User",
            'list' => $this->User_model->tampil(),
            'user_level' => $this->User_model->user_level(),
            'total_user' => $this->User_model->jumlah_user(),

        ];
        $this->load->view('user/index', $data);
        $this->session->unset_userdata('message');
    }

    public function update_status($id_user, $status)
    {
        // Pastikan status valid
        if (in_array($status, ['Active', 'Pending', 'Rejected', 'Inactive'])) {
            $this->User_model->update_status($id_user, $status);
            $this->session->set_flashdata('message', '<div class="alert alert-success">Status pengguna berhasil diperbarui menjadi ' . $status . '.</div>');
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger">Status tidak valid.</div>');
        }
        redirect('User');
    }


    public function create()
    {
        $data['page'] = "User";
        $data['user_level'] = $this->User_model->user_level();
        $data['wilayah'] = $this->User_model->get_all_wilayah(); // Tambahkan data wilayah
        $this->load->view('User/create', $data);
    }

    public function store()
    {
        $id_user_level = $this->input->post('privilege');

        // Tentukan status otomatis berdasarkan level user
        $status = ($id_user_level == 2) ? 'Active' : 'Pending';

        $data = [
            'id_user_level' => $id_user_level,
            'nama' => $this->input->post('nama'),
            'username' => $this->input->post('username'),
            'password' => md5($this->input->post('password')),
            'id_wilayah' => $this->input->post('id_wilayah'),
            'status' => $status
        ];

        // Validasi input
        $this->form_validation->set_rules('privilege', 'Level User', 'required');
        $this->form_validation->set_rules('username', 'Username', 'required|is_unique[user.username]');
        $this->form_validation->set_rules('password', 'Password', 'required');

        // Jika privilege adalah Penyuluh, wilayah harus diisi
        if ($data['id_user_level'] == 2) { // Level Penyuluh
            $this->form_validation->set_rules('id_wilayah', 'Wilayah', 'required');
        }

        if ($this->form_validation->run() !== false) {
            // Simpan data ke database
            $result = $this->User_model->insert($data);
            if ($result) {
                $this->session->set_flashdata('message', '<div class="alert alert-success">Data berhasil disimpan!</div>');
                redirect('User'); // Redirect ke halaman user
            }
        } else {
            // Jika validasi gagal
            $data['page'] = "User";
            $data['user_level'] = $this->User_model->user_level();
            $data['wilayah'] = $this->User_model->get_all_wilayah(); // Pastikan data tetap dikirim ulang
            $this->session->set_flashdata('message', '<div class="alert alert-danger">Gagal menyimpan data!</div>');
            $this->load->view('User/create', $data); // Tampilkan ulang form dengan data
        }
    }

    public function show($id_user)
    {
        $User = $this->User_model->show($id_user);
        $user_level = $this->User_model->user_level();
        $data = [
            'page' => "User",
            'data' => $User,
            'user_level' => $user_level
        ];
        $this->load->view('user/show', $data);
    }

    public function edit($id_user)
    {
        $User = $this->User_model->show($id_user);
        $user_level = $this->User_model->user_level();
        $wilayah = $this->User_model->get_wilayah(); // Ambil data wilayah

        $data = [
            'page' => "User",
            'User' => $User,
            'user_level' => $user_level,
            'wilayah' => $wilayah // Kirim data wilayah ke view
        ];
        $this->load->view('user/edit', $data);
    }

    public function update($id_user)
    {
        $id_user = $this->input->post('id_user');
        $data = array(
            'page' => "User",
            'id_user_level' => $this->input->post('privilege'),
            'nama' => $this->input->post('nama'),
            'username' => $this->input->post('username'),
            'password' => md5($this->input->post('password')),
            'id_wilayah' => $this->input->post('id_wilayah') // Tambahkan wilayah
        );

        // Validasi untuk wilayah jika privilege adalah Penyuluh
        if ($data['id_user_level'] == 2 || $data['id_user_level'] == 3) {
            $this->form_validation->set_rules('id_wilayah', 'Wilayah', 'required');
        }

        if ($this->form_validation->run() !== false) {
            $this->User_model->update($id_user, $data);
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Data berhasil diupdate!</div>');
            redirect('User');
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Gagal mengupdate data!</div>');
            redirect('user/edit/' . $id_user);
        }
    }

    public function destroy($id_user)
    {
        $this->User_model->delete($id_user);
        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Data berhasil dihapus!</div>');
        redirect('User');
    }

    public function get_user_growth_data($tahun)
    {
        $data = $this->User_model->get_user_growth($tahun);
        echo json_encode($data);
    }

    public function get_kinerja_upr()
    {
        $id_penyuluh = $this->session->userdata('id_user'); // Ambil ID penyuluh login

        $this->load->model('User_model');
        $data = $this->User_model->get_kinerja_upr($id_penyuluh);

        echo json_encode($data);
    }
}
    
    /* End of file Kategori.php */
