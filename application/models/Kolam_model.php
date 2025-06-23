<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Kolam_model extends CI_Model {

    public function tampil()
    {
        return $this->db->get('kolam')->result();
    }

    public function tampil_by_upr($id_upr)
    {
        $this->db->where('id_upr', $id_upr);
        return $this->db->get('kolam')->result();
    }

    public function tampil_by_wilayah($id_wilayah)
    {
        $this->db->select('kolam.*');
        $this->db->from('kolam');
        $this->db->join('upr', 'kolam.id_upr = upr.id_upr');
        $this->db->where('upr.id_wilayah', $id_wilayah);
        return $this->db->get()->result();
    }

    public function insert($data)
{
    log_message('info', 'Data yang akan disimpan: ' . print_r($data, true)); // Debugging
    return $this->db->insert('kolam', $data);
}


    public function show($id_kolam)
    {
        $this->db->where('id_kolam', $id_kolam);
        return $this->db->get('kolam')->row();
    }

    public function update($id_kolam, $data)
    {
        $this->db->where('id_kolam', $id_kolam);
        return $this->db->update('kolam', $data);
    }

    public function delete($id_kolam)
    {
        $this->db->where('id_kolam', $id_kolam);
        return $this->db->delete('kolam');
    }

    public function get_all_kolam($id_upr)
    {
        $this->db->select('*');
        $this->db->from('kolam');
        $this->db->where('id_upr', $id_upr);
        return $this->db->get()->result();
    }

    public function get_kolam_by_upr($id_upr)
    {
        return $this->db->get_where('kolam', ['id_upr' => $id_upr])->result();
    }


    public function jumlah_kolam_by_upr($id_upr) {
        $this->db->where('id_upr', $id_upr);
        return $this->db->count_all_results('kolam');
    }

    public function get_kolam_luas_per_upr($id_wilayah, $tahun = null)
    {
        $this->db->select("upr.nama_upr, COUNT(kolam.id_kolam) as total_kolam, SUM(kolam.luas_kolam) as total_luas");
        $this->db->from('kolam');
        $this->db->join('upr', 'upr.id_upr = kolam.id_upr');
        $this->db->where('upr.id_wilayah', $id_wilayah);
    
        if ($tahun) {
            $this->db->where("YEAR(kolam.tanggal_input)", $tahun);
        }
    
        $this->db->group_by("upr.nama_upr");
        $this->db->order_by("total_kolam", "DESC");
    
        return $this->db->get()->result();
    }

public function total_luas_kolam_by_upr($id_upr)
{
    $this->db->select_sum('luas_kolam');
    $this->db->where('id_upr', $id_upr);
    $query = $this->db->get('kolam');
    return $query->row()->luas_kolam ?? 0;
}

    public function jumlah_kolam() {
        return $this->db->count_all('kolam');
    }
}
   

