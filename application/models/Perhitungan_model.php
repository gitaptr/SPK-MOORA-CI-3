<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Perhitungan_model extends CI_Model
{
    public function hitung_moora($id_pemijahan, $jenis_kelamin, $id_upr)
    {
        // Ambil data kriteria dan alternatif
        $kriterias = $this->get_kriteria_by_gender($jenis_kelamin);
        $alternatifs = $this->get_alternatif_by_pemijahan_and_gender($id_pemijahan, $jenis_kelamin);

        // 1. Matrix Keputusan (X)
        $matriks_x = array();
        foreach ($alternatifs as $alternatif) {
            foreach ($kriterias as $kriteria) {
                $data_nilai = $this->data_nilai($alternatif->id_alternatif, $kriteria->id_kriteria);
                $nilai = isset($data_nilai['nilai']) ? $data_nilai['nilai'] : 0;
                $matriks_x[$kriteria->id_kriteria][$alternatif->id_alternatif] = $nilai;
            }
        }

        // 2. Matriks Ternormalisasi (R)
        $matriks_r = array();
        foreach ($matriks_x as $id_kriteria => $penilaians) {
            $jumlah_kuadrat = 0;
            foreach ($penilaians as $penilaian) {
                $jumlah_kuadrat += pow($penilaian, 2);
            }
            $akar_kuadrat = sqrt($jumlah_kuadrat);

            foreach ($penilaians as $id_alternatif => $penilaian) {
                $matriks_r[$id_kriteria][$id_alternatif] = $penilaian / $akar_kuadrat;
            }
        }

        // 3. Matriks Normalisasi Terbobot
        $matriks_rb = array();
        foreach ($alternatifs as $alternatif) {
            foreach ($kriterias as $kriteria) {
                $bobot = $kriteria->bobot;
                $nilai_r = $matriks_r[$kriteria->id_kriteria][$alternatif->id_alternatif];
                $matriks_rb[$kriteria->id_kriteria][$alternatif->id_alternatif] = $bobot * $nilai_r;
            }
        }

        // 4. Hitung Nilai Yi
        $hasil = array();
        foreach ($alternatifs as $alternatif) {
            $total_max = 0;
            $total_min = 0;

            foreach ($kriterias as $kriteria) {
                $nilai_rb = $matriks_rb[$kriteria->id_kriteria][$alternatif->id_alternatif];

                if ($kriteria->jenis == 'Benefit') {
                    $total_max += $nilai_rb;
                } elseif ($kriteria->jenis == 'Cost') {
                    $total_min += $nilai_rb;
                }
            }

            $nilai_yi = $total_max - $total_min;

            $hasil[] = [
                'id_pemijahan' => $id_pemijahan,
                'id_upr' => $id_upr,
                'id_alternatif' => $alternatif->id_alternatif,
                'nilai' => $nilai_yi,
                'created_at' => date('Y-m-d H:i:s'),
                'jenis_kelamin' => $jenis_kelamin
            ];
        }

        return $hasil;
    }

    public function get_detail_historis($id_historis, $id_upr)
    {
        $this->db->select('
            h.id_historis, 
            h.id_alternatif, 
            h.id_pemijahan, 
            h.id_upr, 
            h.nilai, 
            h.created_at, 
            h.keterangan, 
            h.jenis_kelamin, 
            p.kolam, 
            p.waktu_pemijahan, 
            p.metode_pemijahan
        ');
        $this->db->from('historis h');
        $this->db->join('pemijahan p', 'h.id_pemijahan = p.id_pemijahan', 'left');
        $this->db->where('h.id_historis', $id_historis);
        $this->db->where('h.id_upr', $id_upr);

        $query = $this->db->get();

        return $query->num_rows() > 0 ? $query->row() : null;
    }

    public function get_data_terpilih($id_pemijahan, $id_upr)
    {
        $this->db->where('id_pemijahan', $id_pemijahan);
        $this->db->where('id_upr', $id_upr);
        $this->db->where('status_pilih', 1);
        $query = $this->db->get('hasil_moora');
        return $query->result();
    }

    public function get_alternatif_dari_historis($id_pemijahan, $id_upr)
    {
        $this->db->select('h.*, a.nama');
        $this->db->from('historis h');
        $this->db->join('alternatif a', 'h.id_alternatif = a.id_alternatif');
        $this->db->where('h.id_pemijahan', $id_pemijahan);
        $this->db->where('h.id_upr', $id_upr);
        return $this->db->get()->result();
    }

    // Dalam Perhitungan_model.php

    public function hapus_historis_by_pemijahan($id_pemijahan) // Ubah nama fungsi agar jelas
    {
        $this->db->where('id_pemijahan', $id_pemijahan); // KONDISI PENTING: Gunakan id_pemijahan
        return $this->db->delete('historis'); // Pastikan nama tabel ini benar ('historis' atau 'tabel_historis')
    }


    public function get_nilai_kriteria_by_pemijahan($id_pemijahan, $id_upr)
    {
        $this->db->select('
            a.id_alternatif,
            k.kode_kriteria,
            sk.nilai as nilai_sub_kriteria
        ');
        $this->db->from('alternatif a');
        $this->db->join('penilaian p', 'a.id_alternatif = p.id_alternatif');
        $this->db->join('kriteria k', 'p.id_kriteria = k.id_kriteria');
        $this->db->join('sub_kriteria sk', 'p.nilai = sk.id_sub_kriteria');
        $this->db->where('a.id_pemijahan', $id_pemijahan);
        $this->db->where('p.id_upr', $id_upr);

        $query = $this->db->get();

        $result = array();
        foreach ($query->result() as $row) {
            $result[$row->id_alternatif][$row->kode_kriteria] = $row->nilai_sub_kriteria;
        }
        return $result;
    }

    public function get_hasil_moora_by_pemijahan_and_status($id_pemijahan, $id_upr)
    {
        $this->db->select('hm.*, a.nama, a.jenis_kelamin');
        $this->db->from('hasil_moora hm');
        $this->db->join('alternatif a', 'hm.id_alternatif = a.id_alternatif');
        $this->db->where('hm.id_pemijahan', $id_pemijahan);
        $this->db->where('hm.id_upr', $id_upr);
        $this->db->order_by('hm.nilai', 'DESC');

        return $this->db->get()->result();
    }
    public function get_kriteria_dan_sub_kriteria($id_alternatif, $id_upr, $jenis_kelamin)
    {
        $this->db->select('k.kode_kriteria, k.keterangan, k.jenis, sk.nilai');
        $this->db->from('penilaian p');
        $this->db->join('kriteria k', 'p.id_kriteria = k.id_kriteria');
        $this->db->join('sub_kriteria sk', 'p.nilai = sk.id_sub_kriteria');
        $this->db->join('alternatif a', 'p.id_alternatif = a.id_alternatif');
        $this->db->where('p.id_alternatif', $id_alternatif);
        $this->db->where('p.id_upr', $id_upr);
        $this->db->where('a.jenis_kelamin', $jenis_kelamin);

        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_all_kriteria()
    {
        $this->db->select('kode_kriteria, keterangan, bobot, jenis_kelamin, jenis');
        $this->db->from('kriteria');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_hasil_perhitungan_by_pemijahan($id_pemijahan)
    {
        $this->db->select('historis.*, alternatif.nama, hasil_moora.status_pilih');
        $this->db->from('historis');
        $this->db->join('alternatif', 'alternatif.id_alternatif = historis.id_alternatif');
        $this->db->join('hasil_moora', 'hasil_moora.id_alternatif = historis.id_alternatif AND hasil_moora.id_pemijahan = historis.id_pemijahan'); // Join dengan hasil_moora
        $this->db->where('historis.id_pemijahan', $id_pemijahan);
        $this->db->where('hasil_moora.status_pilih', 1); // Ambil berdasarkan status_pilih dari hasil_moora
        $this->db->order_by('historis.nilai', 'DESC');

        return $this->db->get()->result();
    }


    public function get_nilai_kriteria_by_alternatif($id_alternatif, $id_upr)
    {
        $this->db->select('id_kriteria, nilai');
        $this->db->from('penilaian');
        $this->db->where('id_alternatif', $id_alternatif);
        $this->db->where('id_upr', $id_upr);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_sub_kriteria_by_id($id_sub_kriteria)
    {
        $this->db->select('nilai');
        $this->db->from('sub_kriteria');
        $this->db->where('id_sub_kriteria', $id_sub_kriteria);
        $query = $this->db->get();
        return $query->row_array();
    }

    public function get_kriteria()
    {
        $query = $this->db->get('kriteria');
        return $query->num_rows() > 0 ? $query->result() : [];
    }

    public function get_alternatif()
    {
        $query = $this->db->get('alternatif');
        return $query->num_rows() > 0 ? $query->result() : [];
    }

    public function get_alternatif_by_pemijahan_and_gender($id_pemijahan, $jenis_kelamin)
    {
        $this->db->where('id_pemijahan', $id_pemijahan);
        $this->db->where('jenis_kelamin', $jenis_kelamin);
        return $this->db->get('alternatif')->result();
    }

    public function get_kriteria_by_gender($jenis_kelamin)
    {
        $this->db->where('jenis_kelamin', $jenis_kelamin);
        return $this->db->get('kriteria')->result();
    }

    public function get_waktu_pemijahan()
    {
        $this->db->select('id_pemijahan, waktu_pemijahan');
        $this->db->from('pemijahan'); // Pastikan tabelnya benar
        $query = $this->db->get();
        return $query->result(); // Harus mengembalikan array objek
    }

    public function get_hasil_moora_by_pemijahan_and_gender($id_pemijahan, $id_upr, $jenis_kelamin)
    {
        $this->db->select('hasil_moora.*, alternatif.nama');
        $this->db->from('hasil_moora');
        $this->db->join('alternatif', 'alternatif.id_alternatif = hasil_moora.id_alternatif');
        $this->db->where('hasil_moora.id_pemijahan', $id_pemijahan);
        $this->db->where('hasil_moora.id_upr', $id_upr);
        $this->db->where('hasil_moora.jenis_kelamin', $jenis_kelamin); // Filter berdasarkan jenis kelamin
        $this->db->order_by('hasil_moora.nilai', 'DESC'); // Urutkan berdasarkan nilai tertinggi
        return $this->db->get()->result();
    }
    public function get_waktu_pemijahan_historis($id_upr)
    {
        $this->db->select('id_pemijahan, waktu_pemijahan');
        $this->db->from('pemijahan');
        $this->db->where('status', 1);
        $this->db->where('id_upr', $id_upr); // Tambahkan filter id_upr
        $query = $this->db->get();
        return $query->result();
    }

    public function get_waktu_pemijahan_by_upr($id_upr)
    {
        $this->db->select('*');
        $this->db->from('pemijahan');
        $this->db->where('id_upr', $id_upr);
        $this->db->where('status', 0); // Hanya ambil data dengan status 0
        return $this->db->get()->result();
    }


    public function get_historis_perhitungan_by_pemijahan($id_pemijahan)
    {
        $this->db->select('alternatif.nama, historis.nilai, historis.created_at');
        $this->db->from('historis');
        $this->db->join('alternatif', 'alternatif.id_alternatif = historis.id_alternatif');
        $this->db->where('historis.id_pemijahan', $id_pemijahan);

        $query = $this->db->get();
        log_message('debug', 'Query get_historis_perhitungan_by_pemijahan: ' . $this->db->last_query());

        return $query->result();
    }


    public function get_alternatif_by_pemijahan($id_pemijahan)
    {
        $this->db->select('id_alternatif, nama, id_pemijahan'); // Pastikan id_pemijahan diambil
        $this->db->from('alternatif');
        $this->db->where('id_pemijahan', $id_pemijahan);
        $query = $this->db->get();

        return $query->result(); // Gunakan result() agar mengembalikan objek
    }



    public function data_nilai($id_alternatif, $id_kriteria)
    {
        $query = $this->db->query(
            "SELECT * FROM penilaian 
            JOIN sub_kriteria 
            ON penilaian.nilai = sub_kriteria.id_sub_kriteria 
            WHERE penilaian.id_alternatif = ? 
            AND penilaian.id_kriteria = ?",
            [$id_alternatif, $id_kriteria]
        );

        return $query->num_rows() > 0 ? $query->row_array() : [];
    }

    public function simpan_hasil_moora($hasil, $id_pemijahan, $id_upr, $jenis_kelamin)
    {
        if (empty($id_upr) || empty($id_pemijahan) || empty($hasil) || empty($jenis_kelamin)) {
            log_message('error', 'Data tidak lengkap untuk menyimpan hasil MOORA');
            return false;
        }

        // Mulai transaksi
        $this->db->trans_start();

        // Hapus hanya data dengan jenis kelamin yang sama
        $this->db->where('id_pemijahan', $id_pemijahan);
        $this->db->where('id_upr', $id_upr);
        $this->db->where('jenis_kelamin', $jenis_kelamin);
        $this->db->delete('hasil_moora');

        // Simpan hasil MOORA baru
        $result = $this->db->insert_batch('hasil_moora', $hasil);

        // Cek apakah transaksi berhasil
        if ($this->db->trans_status() === FALSE) {
            // Rollback jika gagal
            $this->db->trans_rollback();
            log_message('error', 'Gagal menyimpan hasil MOORA: ' . $this->db->last_query());
            return false;
        } else {
            // Commit jika berhasil
            $this->db->trans_commit();
            return true;
        }
    }

    public function insert_hasil_moora($hasil_akhir = [])
    {
        if (!isset($hasil_akhir['id_alternatif']) || !isset($hasil_akhir['nilai']) || !isset($hasil_akhir['id_pemijahan']) || !isset($hasil_akhir['id_upr']) || !isset($hasil_akhir['jenis_kelamin'])) {
            log_message('error', 'Data hasil MOORA tidak valid: ' . json_encode($hasil_akhir));
            return false;
        }

        log_message('debug', 'Menyimpan hasil ke hasil_moora: ' . json_encode($hasil_akhir));
        return $this->db->insert('hasil_moora', $hasil_akhir);
    }


    public function get_historis_perhitungan()
    {
        $query = $this->db->query(
            "SELECT 
                a.nama AS nama, 
                h.nilai, 
                h.created_at, 
                RANK() OVER (PARTITION BY h.id_pemijahan ORDER BY h.nilai DESC) AS rank 
            FROM historis h 
            JOIN alternatif a ON h.id_alternatif = a.id_alternatif 
            ORDER BY h.id_pemijahan DESC, rank ASC"
        );
        return $query->num_rows() > 0 ? $query->result() : [];
    }

    public function get_historis_perhitungan_by_waktu($id_pemijahan, $id_upr)
    {
        $this->db->select('*');
        $this->db->from('historis');
        $this->db->where('id_pemijahan', $id_pemijahan);
        $this->db->where('id_upr', $id_upr); // Tambahkan filter id_upr
        $query = $this->db->get();
        return $query->result();
    }


    public function get_all_pemijahan()
    {
        return $this->db->get('pemijahan')->result_array();
    }


    public function get_hasil_moora($id_alternatif)
    {
        $this->db->select('*'); // Pastikan `id_pemijahan` diambil
        $this->db->from('hasil_moora');
        $this->db->where('id_alternatif', $id_alternatif);
        $query = $this->db->get();

        return $query->row(); // Pastikan data ada
    }


    public function get_hasil_moora_by_pemijahan($id_pemijahan, $id_upr)
    {
        $this->db->select('h.*, a.nama, a.jenis_kelamin');
        $this->db->from('historis h');
        $this->db->join('alternatif a', 'h.id_alternatif = a.id_alternatif', 'left');
        $this->db->where('h.id_pemijahan', $id_pemijahan);
        $this->db->where('h.id_upr', $id_upr);
        return $this->db->get()->result();
    }


    public function update_status_hasil_moora($id_pemijahan, $id_upr)
    {
        $this->db->set('status', 1);
        $this->db->where('id_pemijahan', $id_pemijahan);
        $this->db->where('id_upr', $id_upr);
        return $this->db->update('hasil_moora');
    }



    public function hapus_hasil_moora($id_pemijahan, $id_upr, $jenis_kelamin)
    {
        $this->db->where('id_pemijahan', $id_pemijahan);
        $this->db->where('id_upr', $id_upr);
        $this->db->where('jenis_kelamin', $jenis_kelamin); // Hanya hapus data untuk jenis kelamin yang dipilih
        return $this->db->delete('hasil_moora');
    }

    public function get_pemijahan_detail($id_pemijahan)
    {
        return $this->db->get_where('pemijahan', ['id_pemijahan' => $id_pemijahan])->row();
    }

    public function get_all_historis_by_pemijahan($id_upr)
    {
        return $this->db->select('h.id_pemijahan, p.waktu_pemijahan, h.keterangan, h.created_at')
            ->from('historis h')
            ->join('pemijahan p', 'h.id_pemijahan = p.id_pemijahan')
            ->where('h.id_upr', $id_upr)
            ->group_by('h.id_pemijahan') // Mengelompokkan berdasarkan id_pemijahan
            ->order_by('p.waktu_pemijahan', 'DESC')
            ->get()
            ->result();
    }


    public function get_historis_by_pemijahan($id_pemijahan)
    {
        $this->db->select('historis.*, alternatif.nama');
        $this->db->from('historis');
        $this->db->join('alternatif', 'alternatif.id_alternatif = historis.id_alternatif');
        $this->db->where('historis.id_pemijahan', $id_pemijahan);
        $this->db->order_by('historis.nilai', 'DESC');
        return $this->db->get()->result();
    }


    public function get_historis_by_id($id)
    {
        return $this->db->select('historis.*, pemijahan.waktu_pemijahan')
            ->from('historis')
            ->join('pemijahan', 'historis.id_pemijahan = pemijahan.id_pemijahan', 'left')
            ->where('historis.id_historis', $id)
            ->get()
            ->row();
    }


    public function get_hasil_perhitungan($id)
    {
        return $this->db->select('historis.*, alternatif.nama')
            ->from('historis') // 🔄 Ganti tabel hasil_perhitungan → hasil_moora
            ->join('alternatif', 'historis.id_alternatif = alternatif.id_alternatif', 'left')
            ->where('historis.id_historis', $id)
            ->get()
            ->result();
    }




    public function get_nilai_tertinggi_per_waktu($id_upr, $tahun = null)
    {
        $this->db->select('historis.id_pemijahan, 
        (SELECT nama FROM alternatif WHERE id_alternatif = historis.id_alternatif) as nama_alternatif, 
        pemijahan.waktu_pemijahan, 
        historis.nilai as total_nilai');
        $this->db->from('historis');
        $this->db->join('pemijahan', 'pemijahan.id_pemijahan = historis.id_pemijahan', 'left');
        $this->db->where('historis.id_upr', $id_upr);

        if ($tahun) {
            $this->db->where('YEAR(pemijahan.waktu_pemijahan)', $tahun);
        }

        $this->db->where('historis.nilai = (
        SELECT MAX(nilai) 
        FROM historis hm2 
        WHERE hm2.id_pemijahan = historis.id_pemijahan
    )');
        $this->db->group_by('pemijahan.waktu_pemijahan');
        $this->db->order_by('pemijahan.waktu_pemijahan', 'ASC');

        $query = $this->db->get();
        return $query->result();
    }
}
