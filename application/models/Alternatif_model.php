<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Alternatif_model extends CI_Model
{
    public function tampil()
    {
        $query = $this->db->get('alternatif');
        return $query->result();
    }

   

    public function insert($data = [])
    {
        if (!isset($data['id_upr']) || empty($data['id_upr'])) {
            log_message('error', 'Gagal menyimpan data alternatif: id_upr tidak ada');
            return false;
        }

        $this->db->insert('alternatif', $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            log_message('error', 'Gagal menyimpan data: ' . json_encode($this->db->error()));
            return false;
        }
    }




    public function show($id_alternatif)
    {
        $this->db->where('id_alternatif', $id_alternatif);
        $query = $this->db->get('alternatif');
        return $query->row();
    }

    // Memperbarui data
    public function update($id_alternatif, $data = [])
    {
        $this->db->where('id_alternatif', $id_alternatif);
        $result = $this->db->update('alternatif', $data);

        // Log query yang dijalankan untuk debugging
        log_message('info', 'Query Update: ' . $this->db->last_query());

        return $result;
    }

    public function delete($id_alternatif)
    {
        $this->db->where('id_alternatif', $id_alternatif);
        return $this->db->delete('alternatif');
    }

    public function get_by_waktu_pemijahan($id_pemijahan)
    {
        return $this->db->where('id_pemijahan', $id_pemijahan)
                        ->get('alternatif')
                        ->result();
    }
    public function exists($id)
    {
        if (empty($id)) {
            return false;
        }
        return $this->db->where('id_alternatif', $id)->count_all_results('alternatif') > 0;
    }

    public function jumlah_alternatif_by_upr($id_upr) {
        $this->db->where('id_upr', $id_upr);
        return $this->db->count_all_results('alternatif');
    }

    public function jumlah_alternatif() {
        return $this->db->count_all('alternatif');
    }
}
