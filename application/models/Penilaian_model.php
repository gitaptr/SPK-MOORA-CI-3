<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Penilaian_model extends CI_Model
{

    public function tambah_penilaian($id_alternatif, $id_kriteria, $nilai, $id_upr)
    {
        $query = $this->db->simple_query("INSERT INTO penilaian VALUES (DEFAULT, '$id_alternatif', '$id_kriteria', $nilai, '$id_upr');");
        return $query;
    }

    public function edit_penilaian($id_alternatif, $id_kriteria, $nilai, $id_upr)
    {
        $query = $this->db->simple_query("UPDATE penilaian SET nilai=$nilai WHERE id_alternatif='$id_alternatif' AND id_kriteria='$id_kriteria' AND id_upr='$id_upr';");
        return $query;
    }

    public function get_kriteria()
    {
        return $this->db->get('kriteria')->result();
    }

    public function get_kriteria_by_gender($jenis_kelamin) {
        $this->db->where('jenis_kelamin', $jenis_kelamin);
        return $this->db->get('kriteria')->result();
    }

    public function get_sub_kriteria_by_gender($id_kriteria, $jenis_kelamin) {
        $this->db->where('id_kriteria', $id_kriteria);
        $this->db->where('jenis_kelamin', $jenis_kelamin);
        return $this->db->get('sub_kriteria')->result();
    }

    public function get_all_waktu_pemijahan_by_upr($id_upr)
    {
        $this->db->where('id_upr', $id_upr);
        $this->db->where('status', 0); // Tambahkan kondisi status = 0
        return $this->db->get('pemijahan')->result();
    }


    public function get_alternatif_by_waktu_pemijahan($id_pemijahan, $id_upr)
    {
        $this->db->where('id_pemijahan', $id_pemijahan);
        $this->db->where('id_upr', $id_upr);
        return $this->db->get('alternatif')->result();
    }

    public function exists_for_upr($id_pemijahan, $id_upr)
    {
        $this->db->where('id_pemijahan', $id_pemijahan);
        $this->db->where('id_upr', $id_upr);
        return $this->db->count_all_results('pemijahan') > 0;
    }

    public function data_penilaian($id_alternatif, $id_kriteria)
    {
        $this->db->select('nilai');
        $this->db->from('penilaian');
        $this->db->where('id_alternatif', $id_alternatif);
        $this->db->where('id_kriteria', $id_kriteria);
        return $this->db->get()->row_array();
    }

    public function untuk_tombol($id_alternatif)
    {
        return $this->db->where('id_alternatif', $id_alternatif)->count_all_results('penilaian');
    }

    public function data_sub_kriteria($id_kriteria)
    {
        $this->db->where('id_kriteria', $id_kriteria);
        return $this->db->get('sub_kriteria')->result_array();
    }
}
