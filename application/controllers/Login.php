<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Login extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->model('Login_model');
        $this->load->model('User_model');
        $this->load->model('Wilayah_model');
        $this->load->model('Upr_model');
        $this->load->model('Kriteria_model');
        $this->load->model('Sub_Kriteria_model');
        $this->load->model('Kolam_model');
        $this->load->model('Induk_model');
        $this->load->model('Alternatif_model');
        $this->load->model('Pemijahan_model');
        $this->load->model('Hasilpmj_model');
        $this->load->model('Perhitungan_model'); 
    }

    public function index()
    {
        if ($this->Login_model->logged_id()) {
            redirect('Login/home');
        } else {
            $this->load->view('login');
        }
    }
    public function login()
    {
        $username = $this->input->post('username');
        $password = md5($this->input->post('password')); // Gunakan MD5 untuk hashing

        $user = $this->Login_model->login($username, $password);

        if ($user) {
            // Simpan id_upr ke dalam session
            $session_data = [
                'id_user' => $user->id_user,
                'username' => $user->username,
                'id_user_level' => $user->id_user_level,
                'id_upr' => $user->id_upr, // Ambil langsung dari user
                'id_wilayah' => ($user->id_user_level == 2) ? $user->id_wilayah : 0, // Tambahkan ini
                'status' => 'Logged'
            ];

            $this->session->set_userdata($session_data);
            redirect('Login/home');
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger">Username atau password salah!</div>');
            redirect('login');
        }
    }

    public function logout()
    {
        $this->session->sess_destroy();
        redirect('login');
    }

    public function home()
{
    $this->load->model('Wilayah_model'); 
    $this->load->model('Induk_model'); 
    $this->load->model('Kolam_model');

    $id_upr = $this->session->userdata('id_upr');
    $id_user_level = $this->session->userdata('id_user_level');

    if ($id_user_level == 3) {
        $total_kolam = $this->Kolam_model->jumlah_kolam_by_upr($id_upr);
        $total_alternatif = $this->Alternatif_model->jumlah_alternatif_by_upr($id_upr);
        $total_luas_kolam = $this->Kolam_model->total_luas_kolam_by_upr($id_upr);
    } else  {
        $total_kolam = $this->Kolam_model->jumlah_kolam();
        $total_alternatif = $this->Alternatif_model->jumlah_alternatif();
        $total_luas_kolam = $this->Kolam_model->jumlah_kolam();
    }

    // Ambil jumlah induk berdasarkan jenis kelamin 
    $induk_per_ikan = $this->Induk_model->jumlah_induk_per_jenis($id_upr);

    $tahun = $this->input->get('tahun');

    $data = [
        'page' => "Dashboard",
        'total_wilayah' => $this->Wilayah_model->jumlah_wilayah($tahun),
        'total_upr' => $this->Upr_model->jumlah_upr(),
        'total_user' => $this->User_model->jumlah_user(),
        'total_kriteria' => $this->Kriteria_model->jumlah_kriteria(),
        'total_subkriteria' => $this->Sub_Kriteria_model->jumlah_subkriteria(),
        'total_kolam' => $total_kolam,
        'total_alternatif' => $total_alternatif,
        'total_luas_kolam' => $total_luas_kolam,
        'induk_per_ikan' => $induk_per_ikan,
    ];
    
    $this->load->view('admin/index', $data);
}

public function get_card_data($tahun)
{
    $this->load->model('Wilayah_model');
    $this->load->model('Upr_model');
    $this->load->model('User_model');

    $data = [
        'total_wilayah' => $this->Wilayah_model->jumlah_wilayah($tahun),
        'total_upr' => $this->Upr_model->jumlah_upr($tahun),
        'total_user' => $this->User_model->jumlah_user($tahun),
    ];

    echo json_encode($data);
}
   
}

/* End of file Login.php */