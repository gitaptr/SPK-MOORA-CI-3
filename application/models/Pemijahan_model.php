<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Pemijahan_model extends CI_Model
{

    public function tampil()
    {
        $query = $this->db->get('pemijahan');
        return $query->result();
    }

    // Dalam Pemijahan_model.php

    public function tampil_by_upr($id_upr_filter) 
    {
        $this->db->select('*');
        $this->db->from('pemijahan');

        if (!empty($id_upr_filter)) {
            $this->db->where('id_upr', $id_upr_filter);
        }

        return $this->db->get()->result();
    }


    public function tampil_by_wilayah($id_wilayah)
    {
        $this->db->select('pemijahan.*');
        $this->db->from('pemijahan');
        $this->db->join('upr', 'pemijahan.id_upr = upr.id_upr');
        $this->db->where('upr.id_wilayah', $id_wilayah);
        return $this->db->get()->result();
    }

    public function getTotal()
    {
        return $this->db->count_all('pemijahan');
    }

    public function get_details_by_waktu($waktu_pemijahan)
    {
        $this->db->from('pemijahan');
        $this->db->where('waktu_pemijahan', $waktu_pemijahan);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->row_array();
        } else {
            return null;
        }
    }

    public function get_by_waktu($waktu_pemijahan)
    {
        return $this->db->get_where('pemijahan', ['waktu_pemijahan' => $waktu_pemijahan])->row();
    }


    public function get_by_status($status)
    {
        $this->db->where('status', $status);
        $this->db->where('id_upr', $this->session->userdata('id_upr'));
        return $this->db->get('pemijahan')->result();
    }

    public function get_all_pemijahan()
    {
        $this->db->select('*');
        $this->db->from('pemijahan');
        $this->db->where_in('status', 0,); // Menyaring status 0 dan 1
        $query = $this->db->get();
        return $query->result();
    }


    public function get_waktu_pemijahan_by_id($id_pemijahan)
    {
        $this->db->select('id_pemijahan, waktu_pemijahan');
        $this->db->where('id_pemijahan', $id_pemijahan);
        return $this->db->get('pemijahan')->row(); // Mengembalikan satu baris data
    }


    public function insert($data = [])
    {
        $result = $this->db->insert('pemijahan', $data);
        return $result;
    }

    public function show($id_pemijahan)
    {
        $this->db->where('id_pemijahan', $id_pemijahan);
        return $this->db->get('pemijahan')->row(); // Mengembalikan satu baris data
    }


    public function get_data_pemijahan($id_upr, $id_user_level, $id_penyuluh)
    {
        $this->db->select('*');
        $this->db->from('pemijahan');

        if ($id_user_level == 2) { // Penyuluh hanya melihat UPR yang ditangani
            $this->db->join('user', 'pemijahan.id_upr = user.id_upr');
            $this->db->where('user.id_user_level', 3); // Hanya data milik UPR
            $this->db->where('user.id_penyuluh', $id_penyuluh); // Data sesuai penyuluh
        }

        if ($id_upr) {
            $this->db->where('pemijahan.id_upr', $id_upr);
        }

        return $this->db->get()->result();
    }

    public function get_all_pemijahan_by_upr($id_upr)
    {
        $this->db->select('*');
        $this->db->from('pemijahan');
        $this->db->where('id_upr', $id_upr);
        return $this->db->get()->result();
    }

    public function get_by_upr($upr_id)
    {
        return $this->db->get_where('pemijahan', ['id_upr' => $upr_id])->result();
    }


    public function update($id_pemijahan, $data = [])
    {
        $ubah = array(
            'waktu_pemijahan' => $data['waktu_pemijahan'],
            'jumlah_indukk'  => $data['jumlah_indukk'],
            'kolam' => $data['kolam'],
            'metode_pemijahan' => $data['metode_pemijahan'],
            'status' => $data['status']
        );

        $this->db->where('id_pemijahan', $id_pemijahan);
        $this->db->update('pemijahan', $ubah);
    }

    public function delete($id_pemijahan)
    {
        $this->db->where('id_pemijahan', $id_pemijahan);
        $result = $this->db->delete('pemijahan');

        if (!$result) {
            log_message('error', 'Gagal menghapus data: ' . json_encode($this->db->error()));
        } else {
            log_message('info', 'Data berhasil dihapus dengan ID: ' . $id_pemijahan);
        }

        return $result;
    }

    public function get_pemijahan_by_status($id_upr, $status)
    {
        $this->db->where('id_upr', $id_upr);
        $this->db->where('status', $status); // Hanya status 0
        return $this->db->get('pemijahan')->result();
    }


    public function get_pemijahan_by_upr($id_upr)
    {
        $this->db->select('*');
        $this->db->from('pemijahan');
        $this->db->where('id_upr', $id_upr);
        $this->db->where('status', 0); // Tambahkan filter status 0
        return $this->db->get()->result();
    }


    // Mengecek apakah waktu pemijahan milik UPR tertentu
    public function exists_for_upr($id_pemijahan, $id_upr)
    {
        $this->db->where('id_pemijahan', $id_pemijahan);
        $this->db->where('id_upr', $id_upr);
        return $this->db->count_all_results('pemijahan') > 0;
    }

    // public function update_status_by_waktu($waktu_pemijahan, $status)
    // {
    //     $this->db->where('waktu_pemijahan', $waktu_pemijahan);
    //     $this->db->where('status', 1); // Hanya update jika status awalnya 1
    //     return $this->db->update('pemijahan', ['status' => $status]);
    // }

    public function update_status_by_waktu($waktu_pemijahan, $status)
    {
        $this->db->where('waktu_pemijahan', $waktu_pemijahan);
        return $this->db->update('pemijahan', ['status' => $status]);
    }

    public function exists($id)
    {
        if (empty($id)) {
            return false;
        }
        return $this->db->where('id_pemijahan', $id)->count_all_results('pemijahan') > 0;
    }

    public function getTotalPemijahanPerBulan($id_upr, $tahun = null)
    {
        $this->db->select("YEAR(waktu_pemijahan) as tahun, MONTH(waktu_pemijahan) as bulan, COUNT(*) as total");
        $this->db->from("pemijahan");
        $this->db->where("id_upr", $id_upr);

        if ($tahun) {
            $this->db->where("YEAR(waktu_pemijahan)", $tahun);
        }

        $this->db->group_by(["tahun", "bulan"]);
        $this->db->order_by("tahun", "ASC");
        $this->db->order_by("bulan", "ASC");
        $query = $this->db->get();
        return $query->result();
    }

    public function get_status_pemijahan($id_upr, $tahun = null)
    {
        $sql = "
        SELECT 
            DATE_FORMAT(waktu_pemijahan, '%Y-%m') AS bulan, 
            status, 
            COUNT(*) as total
        FROM pemijahan
        WHERE id_upr = ?
    ";

        $params = [$id_upr];

        if ($tahun) {
            $sql .= " AND YEAR(waktu_pemijahan) = ?";
            $params[] = $tahun;
        }

        $sql .= " GROUP BY bulan, status ORDER BY bulan ASC";

        $query = $this->db->query($sql, $params);
        return $query->result();
    }


    public function get_total_pemijahan_per_upr($id_wilayah, $tahun = null)
    {
        $this->db->select("upr.nama_upr, COUNT(pemijahan.id_pemijahan) AS total_pemijahan");
        $this->db->from('pemijahan');
        $this->db->join('upr', 'upr.id_upr = pemijahan.id_upr');
        $this->db->where('upr.id_wilayah', $id_wilayah);

        if ($tahun) {
            $this->db->where("YEAR(pemijahan.waktu_pemijahan)", $tahun);
        }

        $this->db->group_by("upr.id_upr");
        $this->db->order_by("total_pemijahan", "DESC");

        return $this->db->get()->result();
    }
}
