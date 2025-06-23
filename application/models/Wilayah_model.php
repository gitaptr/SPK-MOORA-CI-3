<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Wilayah_model extends CI_Model
{


    public function tampil()
    {
        $query = $this->db->get('wilayah');
        return $query->result();
    }

    public function insert($data = [])
    {
        // Debugging: Log data yang disimpan
        log_message('info', 'Data untuk disimpan: ' . print_r($data, true));
        return $this->db->insert('wilayah', $data);
    }

    public function show($id_wilayah)
    {
        $this->db->where('id_wilayah', $id_wilayah);
        $query = $this->db->get('wilayah');
        return $query->row();
    }
    public function get_all_wilayah()
    {
        $this->db->select('kode_wilayah'); // Ambil hanya kolom kode_wilayah
        $query = $this->db->get('wilayah'); // Tabel wilayah
        return $query->result(); // Kembalikan hasil sebagai array objek
    }

    // Memperbarui data
    public function update($id_wilayah, $data = [])
    {
        $this->db->where('id_wilayah', $id_wilayah);
        $result = $this->db->update('wilayah', $data);

        // Log query yang dijalankan untuk debugging
        log_message('info', 'Query Update: ' . $this->db->last_query());

        return $result;
    }

    public function delete($id_wilayah)
    {
        $this->db->where('id_wilayah', $id_wilayah);
        return $this->db->delete('wilayah');
    }

    public function jumlah_wilayahh()
    {

        return $this->db->count_all('wilayah');
    }
    public function jumlah_wilayah($tahun = null)
    {
        if ($tahun) {
            $this->db->where('YEAR(tanggal)', $tahun);
        }
        return $this->db->count_all('wilayah');
    }
    public function getById($id_wilayah)
    {
        return $this->db->get_where('wilayah', ['id_wilayah' => $id_wilayah])->row();
    }
}
