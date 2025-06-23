<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Sub_Kriteria_model extends CI_Model
{

    public function tampil()
    {
        $query = $this->db->get('sub_kriteria');
        return $query->result();
    }

    public function tampil_by_jenis_kelamin($jenis_kelamin = null)
    {
        if ($jenis_kelamin) {
            $this->db->where('jenis_kelamin', $jenis_kelamin);
        }
        $query = $this->db->get('sub_kriteria');
        return $query->result();
    }

    public function get_kriteria_by_jenis_kelamin($jenis_kelamin = null)
    {
        if ($jenis_kelamin) {
            $this->db->where('jenis_kelamin', $jenis_kelamin);
        }
        $query = $this->db->get('kriteria');
        return $query->result();
    }
    public function get_kriteria_by_id($id_kriteria)
{
    $this->db->where('id_kriteria', $id_kriteria);
    $query = $this->db->get('kriteria');
    return $query->row();
}
    public function getTotal()
    {
        return $this->db->count_all('sub_kriteria');
    }

    public function insert($data = [])
    {
        $result = $this->db->insert('sub_kriteria', $data);
        return $result;
    }

    public function show($id_sub_kriteria)
    {
        $this->db->where('id_sub_kriteria', $id_sub_kriteria);
        $query = $this->db->get('sub_kriteria');
        return $query->row();
    }

    public function update($id_sub_kriteria, $data)
    {
        log_message('debug', 'ID Sub Kriteria: ' . $id_sub_kriteria);
        $this->db->where('id_sub_kriteria', $id_sub_kriteria);
        return $this->db->update('sub_kriteria', $data);
    }
    public function delete($id_sub_kriteria)
    {
        $this->db->where('id_sub_kriteria', $id_sub_kriteria);
        $this->db->delete('sub_kriteria');
    }

    public function get_kriteria()
    {
        $query = $this->db->get('kriteria');
        return $query->result();
    }

    public function count_kriteria()
    {
        $query =  $this->db->query("SELECT id_kriteria,COUNT(deskripsi) AS jml_setoran FROM sub_kriteria GROUP BY id_kriteria")->result();
        return $query;
    }

    public function data_sub_kriteria($id_kriteria, $jenis_kelamin = null)
{
    $this->db->where('id_kriteria', $id_kriteria);
    if ($jenis_kelamin) {
        $this->db->where('jenis_kelamin', $jenis_kelamin);
    }
    $query = $this->db->get('sub_kriteria');
    return $query->result_array();
}
    public function jumlah_subkriteria()
    {
        return $this->db->count_all('sub_kriteria');
    }
}
    
    /* End of file Kategori_model.php */
