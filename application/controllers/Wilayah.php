<?php
    
    defined('BASEPATH') OR exit('No direct script access allowed');
    
    class Wilayah extends CI_Controller {
        public function __construct()
        {
            parent::__construct();
            $this->load->library('pagination');
            $this->load->library('form_validation');
            $this->load->model('Wilayah_model');
    
            if ($this->session->userdata('id_user_level') != "1") {
            ?>
                <script type="text/javascript">
                    alert('Anda tidak berhak mengakses halaman ini!');
                    window.location='<?= base_url("Login/home"); ?>';
                </script>
            <?php
            }
        }
    
        public function index()
        {
            $data = [
                'page' => "Wilayah",
                'list' => $this->Wilayah_model->tampil(),
                'total_wilayah' => $this->Wilayah_model->jumlah_wilayahh(),
            ];
            $this->load->view('wilayah/index', $data);
            $this->session->unset_userdata('message');
        }
    
        public function store()
        {
            // Debugging: Log data POST
            log_message('info', 'Data POST: ' . print_r($this->input->post(), true));
    
            $data = [
                'kode_wilayah' => $this->input->post('kode_wilayah'),
                'nama_wilayah' => $this->input->post('nama_wilayah'),
            ];
    
            // Validasi data
            $this->form_validation->set_rules('kode_wilayah', 'Kode Wilayah', 'required');
            $this->form_validation->set_rules('nama_wilayah', 'Nama Wilayah', 'required');
    
            if ($this->form_validation->run() !== false) {
                $result = $this->Wilayah_model->insert($data);
                if ($result) {
                    $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Data berhasil disimpan!</div>');
                } else {
                    log_message('error', 'Database insert failed.');
                    $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Gagal menyimpan ke database!</div>');
                }
            } else {
                log_message('error', 'Validation failed: ' . validation_errors());
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Validasi gagal!</div>');
            }
            redirect('wilayah');
        }
    
    
    
       public function show()
    {
        $id_wilayah = $this->input->get('id_wilayah');
        $data = $this->Wilayah_model->show($id_wilayah);
        echo json_encode($data); // Kirim data sebagai JSON
    }
    
       // Memperbarui data
       public function update()
       {
           $id_wilayah = $this->input->post('id_wilayah');
           $data = [
               'kode_wilayah' => $this->input->post('kode_wilayah'),
               'nama_wilayah' => $this->input->post('nama_wilayah'),
           ];
       
           // Log data untuk debugging
           log_message('info', 'Data ID: ' . $id_wilayah);
           log_message('info', 'Data untuk update: ' . print_r($data, true));
       
           $this->form_validation->set_rules('kode_wilayah', 'Kode Wilayah', 'required');
           $this->form_validation->set_rules('nama_wilayah', 'Nama Wilayah', 'required');
         
       
           if ($this->form_validation->run() !== false) {
               $result = $this->Wilayah_model->update($id_wilayah, $data);
       
               if ($result) {
                   log_message('info', 'Update berhasil.');
                   $this->session->set_flashdata('message', '<div class="alert alert-success">Data berhasil diperbarui!</div>');
               } else {
                   log_message('error', 'Update gagal.');
                   $this->session->set_flashdata('message', '<div class="alert alert-danger">Gagal memperbarui data!</div>');
               }
           } else {
               log_message('error', 'Validasi gagal: ' . validation_errors());
               $this->session->set_flashdata('message', '<div class="alert alert-danger">Validasi gagal: ' . validation_errors() . '</div>');
           }
       
           redirect('wilayah');
       }
       
     
    
        public function destroy($id_wilayah)
        {
            $this->Wilayah_model->delete($id_wilayah);
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Data berhasil dihapus!</div>');
            redirect('wilayah');
        }
    }
    