<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Hasilpmj_model extends CI_Model
{
    public function tampil()
    {
        return $this->db->get('hasilpmj')->result();
    }

    public function tampil_by_upr($id_upr)
    {
        return $this->db->get_where('hasilpmj', ['id_upr' => $id_upr])->result();
        if ($id_user_level == 3) {
            $this->db->where('id_upr', $id_upr);
        }
    }

    public function tampil_by_wilayah_grouped($id_wilayah)
    {
        $this->db->select('hasilpmj.id_upr, waktu_pemijahan, kolam, metode_pemijahan, GROUP_CONCAT(id_hasilpmj) as id_hasilpmj');
        $this->db->from('hasilpmj');
        $this->db->join('upr', 'upr.id_upr = hasilpmj.id_upr');
        $this->db->where('upr.id_wilayah', $id_wilayah);
        $this->db->group_by('hasilpmj.id_upr, waktu_pemijahan, kolam, metode_pemijahan');
        return $this->db->get()->result();
    }


    public function tampil_grouped()
    {
        $this->db->select('waktu_pemijahan, kolam, metode_pemijahan, created_at, GROUP_CONCAT(id_hasilpmj) as id_hasilpmj');
        $this->db->from('hasilpmj');
        $this->db->group_by('waktu_pemijahan, kolam, metode_pemijahan, created_at');
        return $this->db->get()->result();
    }

    public function tampil_by_upr_grouped($id_upr)
    {
        $this->db->select('id_upr, waktu_pemijahan, kolam, metode_pemijahan, created_at, GROUP_CONCAT(id_hasilpmj) as id_hasilpmj');
        $this->db->from('hasilpmj');
        $this->db->where('id_upr', $id_upr);
        $this->db->group_by('waktu_pemijahan, kolam, metode_pemijahan, created_at'); // Hapus id_upr dari group_by
        return $this->db->get()->result();
    }

    public function tampil_by_wilayah($id_wilayah)
    {
        $this->db->select('hasilpmj.*');
        $this->db->from('hasilpmj');
        $this->db->join('user', 'user.id_upr = hasilpmj.id_upr');
        $this->db->where('user.id_wilayah', $id_wilayah);
        return $this->db->get()->result();
    }

    public function tampil_manual_by_upr_grouped($id_upr)
    {
        $this->db->select('id_upr, waktu_pemijahan, kolam, metode_pemijahan, created_at, GROUP_CONCAT(id_hasilpmj_manual) as id_hasilpmj_manual');
        $this->db->from('hasilpmj_manual');
        $this->db->where('id_upr', $id_upr);
        $this->db->group_by('waktu_pemijahan, kolam, metode_pemijahan, created_at');
        return $this->db->get()->result();
    }

    public function tampil_manual_by_wilayah_grouped($id_wilayah)
    {
        $this->db->select('hasilpmj_manual.id_upr, waktu_pemijahan, kolam, metode_pemijahan, created_at, GROUP_CONCAT(id_hasilpmj_manual) as id_hasilpmj_manual');
        $this->db->from('hasilpmj_manual');
        $this->db->join('upr', 'upr.id_upr = hasilpmj_manual.id_upr');
        $this->db->where('upr.id_wilayah', $id_wilayah);
        $this->db->group_by('hasilpmj_manual.id_upr, waktu_pemijahan, kolam, metode_pemijahan, created_at');
        return $this->db->get()->result();
    }

    public function tampil_manual_grouped()
    {
        $this->db->select('waktu_pemijahan, kolam, metode_pemijahan, created_at, GROUP_CONCAT(id_hasilpmj_manual) as id_hasilpmj_manual');
        $this->db->from('hasilpmj_manual');
        $this->db->group_by('waktu_pemijahan, kolam, metode_pemijahan, created_at');
        return $this->db->get()->result();
    }


    public function get_by_id($id_hasilpmj)
    {
        $this->db->where('id_hasilpmj', $id_hasilpmj);
        return $this->db->get('hasilpmj')->row();
    }

    public function get_manual_by_id($id_hasilpmj)
    {
        $this->db->where('id_hasilpmj', $id_hasilpmj);
        return $this->db->get('hasilpmj')->result();
    }


    public function get_spk_by_id($id_hasilpmj)
    {
        $this->db->where('id_hasilpmj', $id_hasilpmj);
        return $this->db->get('hasilpmj')->result();
    }


    public function get_by_waktu_pemijahan($waktu_pemijahan)
    {
        $this->db->where('waktu_pemijahan', $waktu_pemijahan);
        return $this->db->get('hasilpmj')->result();
    }


    public function get_by_waktu($waktu_pemijahan)
    {
        return $this->db->get_where('hasilpmj', ['waktu_pemijahan' => $waktu_pemijahan])->row();
    }
    public function get_spk_by_upr_and_waktu($id_upr, $waktu_pemijahan)
    {
        $this->db->from('hasilpmj');
        $this->db->where('id_upr', $id_upr);
        $this->db->where('waktu_pemijahan', $waktu_pemijahan);
        $this->db->order_by('id_hasilpmj', 'DESC'); // optional: kalau kamu ingin yang terbaru
        return $this->db->get()->row(); // hanya ambil SATU baris
    }



    public function get_manual_by_upr_and_waktu($id_upr, $waktu_pemijahan)
    {
        $this->db->from('hasilpmj_manual');
        $this->db->where('id_upr', $id_upr);
        $this->db->where('waktu_pemijahan', $waktu_pemijahan);
        return $this->db->get()->row();
    }


    public function get_induk_spk_by_upr_and_waktu($id_upr, $waktu_pemijahan)
    {
        $this->db->select('
        hasilpmj.jumlah_telur, 
        hasilpmj.tingkat_netas, 
        hasilpmj.jumlah_benih, 
        hasilpmj.ket as ket_hasilpmj, 
        historis.*, 
        alternatif.nama
    ');
        $this->db->from('hasilpmj');
        $this->db->join('pemijahan', 'hasilpmj.waktu_pemijahan = pemijahan.waktu_pemijahan', 'left');
        $this->db->join('historis', 'pemijahan.id_pemijahan = historis.id_pemijahan', 'left');
        $this->db->join('alternatif', 'historis.id_alternatif = alternatif.id_alternatif', 'left');
        $this->db->where('hasilpmj.id_upr', $id_upr);
        $this->db->where('hasilpmj.waktu_pemijahan', $waktu_pemijahan);

        return $this->db->get()->result();
    }

    public function get_induk_manual_by_upr_and_waktu($id_upr, $waktu_pemijahan)
    {
        $this->db->from('hasilpmj_manual');
        $this->db->where('id_upr', $id_upr);
        $this->db->where('waktu_pemijahan', $waktu_pemijahan);
        return $this->db->get()->result();
    }

    public function get_hasil_spk_by_waktu($waktu_pemijahan)
    {
        return $this->db->get_where('hasilpmj', ['waktu_pemijahan' => $waktu_pemijahan])->row();
    }


    public function delete_induk_manual_by_hasilpmj($id_hasilpmj)
    {
        return $this->db->delete('hasilpmj_manual', ['id_hasilpmj_manual' => $id_hasilpmj]);
    }


    public function delete_by_waktu_pemijahan($waktu_pemijahan)
    {
        $this->db->where('waktu_pemijahan', $waktu_pemijahan);
        $this->db->delete('hasilpmj_manual');
    }


    public function insert($data)
    {
        return $this->db->insert('hasilpmj', $data); // Simpan data ke tabel hasilpmj
    }

    public function insert_manual($data)
    {
        return $this->db->insert('hasilpmj_manual', $data); // Simpan data ke tabel hasilpmj_manual
    }

    public function get_manual_by_id_manual($id)
    {
        return $this->db->get_where('hasilpmj_manual', ['id_hasilpmj_manual' => $id])->row();
    }

    public function delete_manual($id)
    {
        return $this->db->delete('hasilpmj_manual', ['id_hasilpmj_manual' => $id]);
    }


    public function show($id_hasilpmj)
    {
        $this->db->where('id_hasilpmj', $id_hasilpmj);
        $query = $this->db->get('hasilpmj');
        return $query->row();
    }

    // Hasilpmj_model
    // Di Hasilpmj_model.php
    public function update_spk($id_hasilpmj, $data)
    {
        $this->db->where('id_hasilpmj', $id_hasilpmj);
        return $this->db->update('hasilpmj', $data);
    }

    public function update_manual_by_waktu($waktu_pemijahan, $data)
    {
        $this->db->where('waktu_pemijahan', $waktu_pemijahan);
        $this->db->where('kolam', $data['kolam']);
        $this->db->where('metode_pemijahan', $data['metode_pemijahan']);

        return $this->db->update('hasilpmj_manual', $data);
    }

    public function delete($id_hasilpmj)
    {
        $this->db->where('id_hasilpmj', $id_hasilpmj);
        $this->db->delete('hasilpmj');
    }

    // Update data SPK berdasarkan waktu pemijahan
    public function update_by_waktu($waktu_pemijahan, $data)
    {
        $this->db->where('waktu_pemijahan', $waktu_pemijahan);
        return $this->db->update('hasilpmj', $data);
    }

    // Hapus data SPK berdasarkan waktu pemijahan
    public function delete_by_waktu($waktu_pemijahan)
    {
        $this->db->where('waktu_pemijahan', $waktu_pemijahan);
        return $this->db->delete('hasilpmj');
    }

    // Hapus data manual berdasarkan waktu pemijahan
    public function delete_manual_by_waktu($waktu_pemijahan)
    {
        $this->db->where('waktu_pemijahan', $waktu_pemijahan);
        return $this->db->delete('hasilpmj_manual');
    }



    public function get_spk_by_waktu_pemijahan($waktu_pemijahan)
    {
        $this->db->select('hasilpmj.*, pemijahan.kolam, pemijahan.metode_pemijahan');
        $this->db->from('hasilpmj');
        $this->db->join('pemijahan', 'pemijahan.waktu_pemijahan = hasilpmj.waktu_pemijahan');
        $this->db->where('hasilpmj.waktu_pemijahan', $waktu_pemijahan);
        return $this->db->get()->row(); // Pastikan mengembalikan single row
    }


    public function getDetailById($id)
    {
        $this->db->where('id_hasilpmj', $id);
        $query = $this->db->get('hasilpmj'); // sesuaikan nama tabelnya
        return $query->row(); // hanya ambil satu baris sebagai object
    }

    public function get_manual_by_waktu($waktu_pemijahan)
    {
        $this->db->select('*');
        $this->db->from('hasilpmj_manual');
        $this->db->where('waktu_pemijahan', $waktu_pemijahan);
        return $this->db->get()->result();
    }

    public function get_manual_by_waktu_pemijahan($waktu_pemijahan)
    {
        $this->db->where('waktu_pemijahan', $waktu_pemijahan);
        return $this->db->get('hasilpmj_manual')->row(); // ambil 1 baris data manual
    }


    public function get_by_upr_and_waktu($id_upr, $waktu_pemijahan)
    {
        return $this->db->get_where('hasilpmj', [
            'id_upr' => $id_upr,
            'waktu_pemijahan' => $waktu_pemijahan
        ])->result();
    }

    public function get_metadata_by_upr_and_waktu($id_upr, $waktu_pemijahan)
{
    // Coba ambil dari SPK lebih dulu
    $data = $this->db->get_where('hasilpmj', [
        'id_upr' => $id_upr,
        'waktu_pemijahan' => $waktu_pemijahan
    ])->row();

    if ($data) return $data;

    // Jika tidak ada di SPK, ambil dari manual
    return $this->db->get_where('hasilpmj_manual', [
        'id_upr' => $id_upr,
        'waktu_pemijahan' => $waktu_pemijahan
    ])->row();
}

    public function get_hasilpemijahan_per_waktu($id_upr, $tahun = null)
    {
        $this->db->select("
            waktu_pemijahan, 
            metode_pemijahan, 
            SUM(tingkat_netas) as total_netas,
            SUM(jumlah_telur) as total_telur
        ");
        $this->db->where('id_upr', $id_upr);

        if ($tahun) {
            $this->db->where("YEAR(waktu_pemijahan)", $tahun);
        }

        $this->db->group_by(["waktu_pemijahan", "metode_pemijahan"]);
        $this->db->order_by("waktu_pemijahan", "ASC");

        return $this->db->get('hasilpmj')->result();
    }

    public function get_hasilpemijahan_manual_per_waktu($id_upr, $tahun = null)
    {
        $this->db->select("
        waktu_pemijahan, 
        'Manual' as metode_pemijahan, 
        SUM(tingkat_netas) as total_netas,
        SUM(jumlah_telur) as total_telur
    ");
        $this->db->where('id_upr', $id_upr);

        if ($tahun) {
            $this->db->where("YEAR(waktu_pemijahan)", $tahun);
        }

        $this->db->group_by(["waktu_pemijahan"]);
        $this->db->order_by("waktu_pemijahan", "ASC");

        return $this->db->get('hasilpmj_manual')->result();
    }


    public function get_pemijahan_by_upr($id_upr)
    {
        $this->db->where('id_upr', $id_upr);
        $query = $this->db->get('pemijahan');
        return $query->result();
    }

    public function get_induk_spk_by_waktu($waktu_pemijahan)
    {
        $this->db->select('historis.id_alternatif, historis.nilai, alternatif.nama, alternatif.jenis_kelamin');
        $this->db->from('historis');
        $this->db->join('alternatif', 'alternatif.id_alternatif = historis.id_alternatif');
        $this->db->join('pemijahan', 'pemijahan.id_pemijahan = historis.id_pemijahan');
        $this->db->where('pemijahan.waktu_pemijahan', $waktu_pemijahan);

        return $this->db->get()->result();
    }


    public function get_induk_manual_by_waktu($waktu_pemijahan)
    {
        $this->db->select('induk, kolam_induk, jenis_kelamin, jumlah_telur, tingkat_netas, jumlah_benih, ket');
        $this->db->from('hasilpmj_manual');
        $this->db->where('waktu_pemijahan', $waktu_pemijahan);
        return $this->db->get()->result();
    }



    public function get_alternatif_status_0()
    {
        $this->db->select('hasil_moora.id_alternatif, alternatif.nama, alternatif.jenis_kelamin');
        $this->db->from('hasil_moora');
        $this->db->join('alternatif', 'alternatif.id_alternatif = hasil_moora.id_alternatif'); // JOIN dengan tabel alternatif
        $this->db->where('hasil_moora.status_pilih', 0); // Filter berdasarkan status
        return $this->db->get()->result();
    }

    public function get_benih_per_bulan($id_upr, $tahun = null)
    {
        $filter_tahun_spk = $tahun ? "AND YEAR(waktu_pemijahan) = '$tahun'" : "";
        $filter_tahun_manual = $tahun ? "AND YEAR(waktu_pemijahan) = '$tahun'" : "";

        $final_query = "
        SELECT combined.bulan, SUM(combined.total_benih) as total_benih FROM (
            -- Data dari hasilpmj (langsung agregasi per bulan)
            SELECT DATE_FORMAT(waktu_pemijahan, '%Y-%m') as bulan, SUM(jumlah_benih) as total_benih 
            FROM hasilpmj
            WHERE id_upr = '$id_upr' $filter_tahun_spk
            GROUP BY DATE_FORMAT(waktu_pemijahan, '%Y-%m')

            UNION ALL

            -- Data dari hasilpmj_manual, ambil satu data saja per waktu_pemijahan
            SELECT DATE_FORMAT(waktu_pemijahan, '%Y-%m') as bulan, SUM(jumlah_benih) as total_benih 
            FROM (
                SELECT waktu_pemijahan, jumlah_benih
                FROM hasilpmj_manual
                WHERE id_upr = '$id_upr' $filter_tahun_manual
                GROUP BY waktu_pemijahan
            ) as manual_grouped
            GROUP BY DATE_FORMAT(waktu_pemijahan, '%Y-%m')
        ) as combined
        GROUP BY combined.bulan
        ORDER BY combined.bulan ASC
    ";

        return $this->db->query($final_query)->result();
    }



    public function get_total_benih_per_upr($id_wilayah, $tahun = null)
    {
        $tahun_filter_hasilpmj = $tahun ? "AND YEAR(h.waktu_pemijahan) = $tahun" : "";
        $tahun_filter_manual = $tahun ? "AND YEAR(hm.waktu_pemijahan) = $tahun" : "";

        $sql = "
        -- Ambil dari tabel hasilpmj
        SELECT u.nama_upr, SUM(h.jumlah_benih) AS total_benih
        FROM hasilpmj h
        JOIN upr u ON u.id_upr = h.id_upr
        WHERE u.id_wilayah = ?
        $tahun_filter_hasilpmj
        GROUP BY u.nama_upr

        UNION ALL

        -- Ambil dari hasilpmj_manual, hanya satu data per id_upr + waktu_pemijahan
        SELECT u.nama_upr, SUM(hm.jumlah_benih) AS total_benih
        FROM (
            SELECT MIN(id_hasilpmj_manual) AS id
            FROM hasilpmj_manual
            GROUP BY id_upr, waktu_pemijahan
        ) hm_sub
        JOIN hasilpmj_manual hm ON hm.id_hasilpmj_manual = hm_sub.id
        JOIN upr u ON u.id_upr = hm.id_upr
        WHERE u.id_wilayah = ?
        $tahun_filter_manual
        GROUP BY u.nama_upr
    ";

        $query = $this->db->query($sql, [$id_wilayah, $id_wilayah]);
        $results = $query->result();

        // Gabungkan total berdasarkan nama_upr
        $final = [];
        foreach ($results as $row) {
            if (!isset($final[$row->nama_upr])) {
                $final[$row->nama_upr] = $row->total_benih;
            } else {
                $final[$row->nama_upr] += $row->total_benih;
            }
        }

        // Format ulang untuk output grafik
        $output = [];
        foreach ($final as $nama => $total) {
            $output[] = (object)[
                'nama_upr' => $nama,
                'total_benih' => $total
            ];
        }

        return $output;
    }
}
