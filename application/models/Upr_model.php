<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Upr_model extends CI_Model
{
    public function tampil()
    {
        $this->db->select('upr.*, wilayah.nama_wilayah as wilayah, user.nama as penyuluh');
        $this->db->from('upr');
        $this->db->join('wilayah', 'wilayah.id_wilayah = upr.id_wilayah', 'left');
        $this->db->join('user', 'user.id_user = upr.id_user', 'left');
        return $this->db->get()->result();
    }
    public function get_all_wilayah()
    {
        return $this->db->get('wilayah')->result();
    }

    public function get_all_penyuluh()
    {
        return $this->db->get_where('user', ['id_user_level' => 2])->result();
    }

    public function insert($data)
    {
        return $this->db->insert('upr', $data);
    }

    public function getById($id)
    {
        $this->db->where('id_upr', $id);
        return $this->db->get('upr')->row();
    }

    public function update($id, $data)
    {
        $this->db->where('id_upr', $id);
        return $this->db->update('upr', $data);
    }

    public function jumlah_upr()
    {
        return $this->db->count_all('upr');
    }

    public function delete($id_upr)
    {
        $this->db->where('id_upr', $id_upr);
        return $this->db->delete('upr');
    }

    public function get_upr_by_wilayah($id_wilayah)
    {
        // Subquery for kolam count
        $this->db->select('id_upr, COUNT(id_kolam) as kolam_count');
        $this->db->from('kolam');
        $this->db->group_by('id_upr');
        $subquery_kolam = $this->db->get_compiled_select();

        // Subquery for induk betina
        $this->db->select('id_upr, SUM(jumlah) as betina_count');
        $this->db->from('induk');
        $this->db->where('jenis_kelamin', 'Betina');
        $this->db->group_by('id_upr');
        $subquery_betina = $this->db->get_compiled_select();

        // Subquery for induk jantan
        $this->db->select('id_upr, SUM(jumlah) as jantan_count');
        $this->db->from('induk');
        $this->db->where('jenis_kelamin', 'Jantan');
        $this->db->group_by('id_upr');
        $subquery_jantan = $this->db->get_compiled_select();

        // Subquery for stok benih
        $this->db->select('id_upr, SUM(jumlah) as benih_count');
        $this->db->from('stok_benih');
        $this->db->group_by('id_upr');
        $subquery_benih = $this->db->get_compiled_select();

        // Main query
         $this->db->select('
        upr.id_upr,
        upr.nama_upr,
        IFNULL(k.kolam_count, 0) as jumlah_kolam,
        IFNULL(b.betina_count, 0) as jumlah_induk_betina,
        IFNULL(j.jantan_count, 0) as jumlah_induk_jantan,
        IFNULL(s.benih_count, 0) as jumlah_benih
    ');
    $this->db->from('upr');
    $this->db->join("($subquery_kolam) k", 'k.id_upr = upr.id_upr', 'left');
    $this->db->join("($subquery_betina) b", 'b.id_upr = upr.id_upr', 'left');
    $this->db->join("($subquery_jantan) j", 'j.id_upr = upr.id_upr', 'left');
    $this->db->join("($subquery_benih) s", 's.id_upr = upr.id_upr', 'left');

    if (!empty($id_wilayah)) {
        $this->db->where('upr.id_wilayah', $id_wilayah);
    }

    return $this->db->get()->result();
    }

      public function get_data_laporan_upr($id_upr)
    {
        $this->db->select('tanggal, jumlah_benih, ukuran, kolam, umur, sumber, keterangan');
        $this->db->from('benih');
        $this->db->where('id_upr', $id_upr);
        $this->db->order_by('tanggal', 'ASC');
        return $this->db->get()->result();
    }

    public function get_detail_upr($id_upr)
    {
        return $this->db->get_where('upr', ['id_upr' => $id_upr])->row();
    }
}
