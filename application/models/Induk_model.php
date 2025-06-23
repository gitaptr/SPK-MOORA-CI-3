<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Induk_model extends CI_Model
{
    public function tampil_by_upr($id_upr)
    {
        $this->db->select('induk.*, kolam.kode_kolam'); // Ambil kode_kolam juga
        $this->db->from('induk');
        $this->db->join('kolam', 'induk.id_kolam = kolam.id_kolam', 'left'); // Gabungkan dengan tabel kolam
        $this->db->where('induk.id_upr', $id_upr);
        return $this->db->get()->result();
    }


    public function tampil_by_wilayah($id_wilayah)
    {
        $this->db->select('induk.*');
        $this->db->from('induk');
        $this->db->join('upr', 'induk.id_upr = upr.id_upr');
        $this->db->where('upr.id_wilayah', $id_wilayah);
        return $this->db->get()->result();
    }


    public function insert($data = [])
    {
        return $this->db->insert('induk', $data); // Pastikan data yang dikirim berisi id_kolam
    }

    public function show($id_induk)
    {
        $this->db->where('id_induk', $id_induk);
        $query = $this->db->get('induk');
        return $query->row();
    }

    // Memperbarui data
    public function update($id_induk, $data = [])
    {
        $this->db->where('id_induk', $id_induk);
        $result = $this->db->update('induk', $data);

        // Log query yang dijalankan untuk debugging
        log_message('info', 'Query Update: ' . $this->db->last_query());

        return $result;
    }

    public function delete($id_induk)
    {
        $this->db->where('id_induk', $id_induk);
        return $this->db->delete('induk');
    }


    // Induk_model
    public function delete_by_hasilpmj($id_hasilpmj)
    {
        $this->db->where('id_hasilpmj', $id_hasilpmj);
        return $this->db->delete('induk');
    }

     public function jumlah_induk_per_jenis($id_upr)
    {
        $this->db->select("jenis_kelamin, SUM(jumlah) as total");
        $this->db->where('id_upr', $id_upr);
        $this->db->group_by(['jenis_kelamin']);
        return $this->db->get('induk')->result();
    }

    

    public function get_jumlah_induk_per_upr($id_wilayah, $tahun = null)
    {
        // nama kolom upr.nama_upr, induk.jenis_kelamin, dan SUM(induk.jumlah) AS total_induk
        $this->db->select('upr.nama_upr, induk.jenis_kelamin');
        // pakai select_sum supaya aliasnya pasti total_induk
        $this->db->select_sum('induk.jumlah', 'total_induk', false);

        $this->db->from('induk');
        $this->db->join('upr', 'upr.id_upr = induk.id_upr');
        $this->db->where('upr.id_wilayah', $id_wilayah);

        if ($tahun) {
            // false di akhir agar CI tidak men-escape fungsi YEAR()
            $this->db->where("YEAR(induk.tanggal_input)", $tahun, false);
        }

        // false di akhir agar CI tidak men-escape koma di GROUP BY
        $this->db->group_by('upr.nama_upr, induk.jenis_kelamin', false);
        $this->db->order_by('upr.nama_upr', 'ASC');

        return $this->db->get()->result();
    }
}
