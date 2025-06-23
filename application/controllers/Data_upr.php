<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Data_upr extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Upr_model');

        if ($this->session->userdata('id_user_level') != "2") {
            redirect('Login/home');
        }
    }

    public function index()
    {
        $id_wilayah = $this->session->userdata('id_wilayah');

        $data = [
            'page' => "Dashboard Penyuluh",
            'list_upr' => $this->Upr_model->get_upr_by_wilayah($id_wilayah)
        ];

        $this->load->view('data_upr', $data);
    }

   public function cetak_laporan()
{
    $id_wilayah = $this->session->userdata('id_wilayah');
    
    // Get wilayah details
    $this->load->model('Wilayah_model');
    $wilayah = $this->Wilayah_model->getById($id_wilayah);
    
    // Get UPR data
    $list_upr = $this->Upr_model->get_upr_by_wilayah($id_wilayah);
    
    // Calculate totals
    $totals = [
        'kolam' => 0,
        'betina' => 0,
        'jantan' => 0,
        'benih' => 0
    ];
    
    foreach ($list_upr as $upr) {
        $totals['kolam'] += $upr->jumlah_kolam;
        $totals['betina'] += $upr->jumlah_induk_betina;
        $totals['jantan'] += $upr->jumlah_induk_jantan;
        $totals['benih'] += $upr->jumlah_benih;
    }
    
    $data = [
        'wilayah' => $wilayah,
        'list_upr' => $list_upr,
        'totals' => $totals,
        'tanggal_cetak' => date('d F Y')
    ];
    
    $this->load->view('laporanupr', $data);
}
}
