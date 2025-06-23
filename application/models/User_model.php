<?php

defined('BASEPATH') or exit('No direct script access allowed');

class User_model extends CI_Model
{

    public function tampil()
    {
        $this->db->select('user.*, wilayah.kode_wilayah');
        $this->db->from('user');
        $this->db->join('wilayah', 'user.id_wilayah = wilayah.id_wilayah', 'left'); // Join ke tabel wilayah
        $query = $this->db->get();
        return $query->result();
    }

    public function update_status($id_user, $status)
    {
        $this->db->where('id_user', $id_user);
        $this->db->update('user', ['status' => $status]);
    }

    public function getTotal()
    {
        return $this->db->count_all('user');
    }

    public function get_all_wilayah()
    {
        return $this->db->get('wilayah')->result(); // 'wilayah' adalah nama tabel
    }

    public function insert($data = [])
    {
        $result = $this->db->insert('user', $data);
        return $result;
    }

    public function show($id_user)
{
    $this->db->select('user.*, wilayah.nama_wilayah as nama_wilayah');
    $this->db->from('user');
    $this->db->join('wilayah', 'user.id_wilayah = wilayah.id_wilayah', 'left');
    $this->db->where('user.id_user', $id_user); // Filter berdasarkan id_user
    $query = $this->db->get();
    return $query->row(); 
}

    public function get_wilayah()
    {
        $query = $this->db->get('wilayah'); // Tabel wilayah
        return $query->result();
    }

    public function update($id_user, $data = [])
    {
        $ubah = array(
            'id_user_level' => $data['id_user_level'],
            'nama' => $data['nama'],
            'username' => $data['username'],
            'password' => $data['password'],
            'id_wilayah' => $data['id_wilayah'] // Update wilayah
        );

        $this->db->where('id_user', $id_user);
        $this->db->update('user', $ubah);
    }

    public function delete($id_user)
    {
        $this->db->where('id_user', $id_user);
        $this->db->delete('user');
    }

    public function get_user()
    {
        $query = $this->db->get('user');
        return $query->result();
    }
    public function user_level()
    {
        $query = $this->db->get('user_level');
        return $query->result();
    }

    public function get_user_data($id_user)
    {
        $this->db->select('id_user, id_wilayah');
        $this->db->from('user');
        $this->db->where('id_user', $id_user);
        return $this->db->get()->row();
    }

    public function get_upr_by_wilayah($id_wilayah)
    {
        $this->db->select('id_upr, nama AS nama_upr');
        $this->db->from('user');
        $this->db->where('id_user_level', 3); // Hanya user dengan level UPR
        $this->db->where('id_wilayah', $id_wilayah);
        return $this->db->get()->result();
    }

    public function jumlah_user()
    {
        return $this->db->count_all('user');
    }

    public function validate_upr_wilayah($id_upr, $id_wilayah)
    {
        return $this->db->where('id_upr', $id_upr)
            ->where('id_wilayah', $id_wilayah)
            ->count_all_results('upr') > 0;
    }

    public function get_user_growth($tahun = null)
    {
        $this->db->select("DATE(created_at) as tanggal, COUNT(id_user) as jumlah");
        $this->db->from("user");

        // Tambahkan kondisi WHERE jika tahun diberikan
        if ($tahun) {
            $this->db->where('YEAR(created_at)', $tahun);
        }

        $this->db->group_by("DATE(created_at)");
        $this->db->order_by("tanggal", "ASC");
        return $this->db->get()->result();
    }



    public function get_kinerja_upr($id_penyuluh)
    {
        $this->db->select('p.id_upr, p.waktu_pemijahan, h.jumlah_telur, h.jumlah_benih');
        $this->db->from('pemijahan p');
        $this->db->join('hasilpmj h', 'p.id_pemijahan = h.id_hasilpmj', 'left');
        $this->db->where('p.id_upr IN (
        SELECT u.id_user FROM user u 
        WHERE u.id_user_level = 3 
        AND u.id_wilayah = (SELECT id_wilayah FROM user WHERE id_user = ' . $id_penyuluh . ')
    )', NULL, FALSE);
        $this->db->order_by('p.waktu_pemijahan', 'ASC');

        $query = $this->db->get();
        return $query->result();
    }
}
    
    /* End of file Kategori_model.php */
