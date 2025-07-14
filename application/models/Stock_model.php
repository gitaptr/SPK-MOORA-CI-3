<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Stock_model extends CI_Model
{
    public function tampil()
    {
        $query = $this->db->get('stok_benih');
        return $query->result();
    }

    public function tampil_by_upr($id_upr)
    {
        $this->db->select('*');
        $this->db->from('stok_benih');
        $this->db->where('id_upr', $id_upr); // Tambahkan ini agar hanya data dengan id_upr yang cocok yang ditampilkan

        return $this->db->get()->result();
    }


    public function getTotal()
    {
        return $this->db->count_all('stok_benih');
    }

    public function insert($data = [], $require_pemijahan = false)
    {
        if ($require_pemijahan) {
            // Untuk pemijahan, wajib isi waktu_pemijahan
            if (empty($data['waktu_pemijahan']) || empty($data['jumlah'])) {
                return false;
            }
        } else {
            // Untuk input manual, cukup jumlah saja
            if (empty($data['jumlah'])) {
                return false;
            }
        }

        // Nilai default
        $defaults = [
            'tanggal' => date('Y-m-d'),
            'ukuran' => '0.25',
            'umur' => '1-3',
            'sumber' => 'Pemijahan'
        ];

        foreach ($defaults as $key => $value) {
            if (!isset($data[$key])) {
                $data[$key] = $value;
            }
        }

        if (empty($data['id_upr'])) {
            $data['id_upr'] = $this->session->userdata('id_upr');
        }

        return $this->db->insert('stok_benih', $data);
    }


    public function get_by_pemijahan($waktu_pemijahan)
    {
        $this->db->where('waktu_pemijahan', $waktu_pemijahan);
        return $this->db->get('hasilpmj')->row();
    }
    public function get_with_pemijahan($id_stok_benih)
    {
        $this->db->select('stok_benih.*, hasilpmj.jumlah_benih as jumlah_pemijahan');
        $this->db->from('stok_benih');
        $this->db->join('hasilpmj', 'stok_benih.waktu_pemijahan = hasilpmj.waktu_pemijahan', 'left');
        $this->db->where('stok_benih.id_stok_benih', $id_stok_benih);
        return $this->db->get()->row();
    }
    public function show($id_stok_benih)
    {
        $this->db->where('id_stok_benih', $id_stok_benih);
        return $this->db->get('stok_benih')->row();
    }

    public function update_by_pemijahan($waktu_pemijahan, $data = [])
    {
        return $this->db
            ->where('waktu_pemijahan', $waktu_pemijahan)
            ->update('stok_benih', $data);
    }

    public function get_by_waktu_pemijahan($waktu_pemijahan, $kolam, $id_upr)
    {
        return $this->db->get_where('stok_benih', [
            'waktu_pemijahan' => $waktu_pemijahan,
            'kolam' => $kolam,
            'id_upr' => $id_upr
        ])->row();
    }

    public function update($id_stok_benih, $data)
    {
        $this->db->where('id_stok_benih', $id_stok_benih);
        return $this->db->update('stok_benih', $data);
    }

    public function delete_by_waktu_pemijahan($waktu_pemijahan)
    {
        $this->db->where('waktu_pemijahan', $waktu_pemijahan);
        return $this->db->delete('stok_benih');
    }

    public function get_by_id($id)
    {
        return $this->db->get_where('stok_benih', ['id_stok_benih' => $id])->row();
    }

    // application/models/Stock_model.php


    public function delete($id_stok_benih)
    {
        $this->db->where('id_stok_benih', $id_stok_benih);
        $this->db->delete('stok_benih');
    }

    public function get_stok_for_report($id_upr)
    {
        $this->db->select('*');
        $this->db->from('stok_benih');
        $this->db->where('id_upr', $id_upr);
        $this->db->order_by('tanggal', 'DESC');

        return $this->db->get()->result();
    }

    public function get_total_jumlah_benih($id_upr)
    {
        $this->db->select_sum('jumlah');
        $this->db->where('id_upr', $id_upr);
        $query = $this->db->get('stok_benih');
        return $query->row()->jumlah;
    }
}
