<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Kriteria_model extends CI_Model
{

    public function tampil_by_jenis_kelamin($jenis_kelamin = null)
    {
        if ($jenis_kelamin) {
            $this->db->where('jenis_kelamin', $jenis_kelamin);
        }
        $query = $this->db->get('kriteria');
        return $query->result();
    }

    public function getTotal()
    {
        return $this->db->count_all('kriteria');
    }

    public function insert($data = [])
    {
        $result = $this->db->insert('kriteria', $data);

        if (!$result) {
            log_message('error', 'Query Insert Gagal: ' . $this->db->last_query());
        }

        return $result;
    }

    public function show($id_kriteria)
    {
        $this->db->where('id_kriteria', $id_kriteria);
        $query = $this->db->get('kriteria');
        return $query->row();
    }

    public function update($id_kriteria, $data = [])
    {
        $ubah = array(
            'keterangan' => $data['keterangan'],
            'kode_kriteria' => $data['kode_kriteria'],
            'bobot' => $data['bobot'],
            'jenis' => $data['jenis']
        );

        $this->db->where('id_kriteria', $id_kriteria);
        return $this->db->update('kriteria', $ubah); // Return TRUE jika berhasil, FALSE jika gagal
    }


    public function is_duplicate_kode($kode_kriteria, $exclude_id = null, $jenis_kelamin = null)
    {
        $this->db->where('kode_kriteria', $kode_kriteria);
        if ($exclude_id !== null) {
            $this->db->where('id_kriteria !=', $exclude_id);
        }
        if ($jenis_kelamin !== null) {
            $this->db->where('jenis_kelamin', $jenis_kelamin);
        }
        $query = $this->db->get('kriteria');
        return $query->num_rows() > 0;
    }

    public function get_total_bobot_by_jenis_kelamin($jenis_kelamin)
    {
        $this->db->select_sum('bobot');
        $this->db->where('jenis_kelamin', $jenis_kelamin);
        $query = $this->db->get('kriteria');
        return $query->row()->bobot ?? 0;
    }

    public function is_kode_exists($kode_kriteria,$jenis_kelamin, $exclude_id = null )
    {
        $this->db->where('kode_kriteria', $kode_kriteria);
        $this->db->where('jenis_kelamin', $jenis_kelamin);

        if ($exclude_id) {
            $this->db->where('id_kriteria !=', $exclude_id);
        }

        return $this->db->get('kriteria')->num_rows() > 0;
    }

    public function get_total_bobot($jenis_kelamin)
    {
        $this->db->select_sum('bobot');
        $this->db->where('jenis_kelamin', $jenis_kelamin);
        $result = $this->db->get('kriteria')->row();

        return $result->bobot ?? 0;
    }

    public function delete($id_kriteria)
    {
        $this->db->where('id_kriteria', $id_kriteria);
        $this->db->delete('kriteria');
    }

    public function jumlah_kriteria()
    {
        return $this->db->count_all('kriteria');
    }
}
