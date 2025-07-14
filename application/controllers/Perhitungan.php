<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Perhitungan extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('pagination');
        $this->load->library('form_validation');
        $this->load->library('session');
        $this->load->model('Perhitungan_model');
    }

    public function index()
    {
        if ($this->session->userdata('id_user_level') != "3") {
            $this->session->set_flashdata('error', 'Anda tidak berhak mengakses halaman ini!');
            redirect('Login/home');
        }

        $id_upr = $this->session->userdata('id_upr');
        $id_pemijahan = $this->input->get('id_pemijahan');
        $jenis_kelamin = $this->input->get('jenis_kelamin');

        $alternatifs = [];
        $matriks_x = [];
        $matriks_r = [];
        $matriks_rb = [];

        if ($id_pemijahan && $jenis_kelamin) {
            $alternatifs = $this->Perhitungan_model->get_alternatif_by_pemijahan_and_gender($id_pemijahan, $jenis_kelamin);
            $kriterias = $this->Perhitungan_model->get_kriteria_by_gender($jenis_kelamin);

            // Hitung matriks-matriks jika ada alternatif
            if (!empty($alternatifs)) {
                // 1. Matrix Keputusan (X)
                foreach ($alternatifs as $alternatif) {
                    foreach ($kriterias as $kriteria) {
                        $data_nilai = $this->Perhitungan_model->data_nilai($alternatif->id_alternatif, $kriteria->id_kriteria);
                        $nilai = isset($data_nilai['nilai']) ? $data_nilai['nilai'] : 0;
                        $matriks_x[$kriteria->id_kriteria][$alternatif->id_alternatif] = $nilai;
                    }
                }

                // 2. Matriks Ternormalisasi (R)
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
                foreach ($alternatifs as $alternatif) {
                    foreach ($kriterias as $kriteria) {
                        $bobot = $kriteria->bobot;
                        $nilai_r = $matriks_r[$kriteria->id_kriteria][$alternatif->id_alternatif];
                        $matriks_rb[$kriteria->id_kriteria][$alternatif->id_alternatif] = $bobot * $nilai_r;
                    }
                }
            }
        }

        $data = [
            'page' => "Perhitungan",
            'kriterias' => $kriterias ?? [],
            'alternatifs' => $alternatifs,
            'waktu_pemijahan' => $this->Perhitungan_model->get_waktu_pemijahan_by_upr($id_upr),
            'id_pemijahan' => $id_pemijahan,
            'jenis_kelamin' => $jenis_kelamin,
            'id_upr' => $id_upr,
            'matriks_x' => $matriks_x,
            'matriks_r' => $matriks_r,
            'matriks_rb' => $matriks_rb
        ];

        $this->load->view('Perhitungan/perhitungan', $data);
    }

    public function hitung_moora()
    {
        $id_pemijahan = $this->input->post('id_pemijahan');
        $jenis_kelamin = $this->input->post('jenis_kelamin');
        $id_upr = $this->session->userdata('id_upr');

        if (empty($id_upr) || empty($id_pemijahan) || empty($jenis_kelamin)) {
            $this->session->set_flashdata('error', 'Data tidak lengkap untuk melakukan perhitungan.');
            redirect('Perhitungan/index');
            return;
        }

        // Hitung menggunakan metode MOORA yang benar
        $hasil = $this->Perhitungan_model->hitung_moora($id_pemijahan, $jenis_kelamin, $id_upr);

        if ($this->Perhitungan_model->simpan_hasil_moora($hasil, $id_pemijahan, $id_upr, $jenis_kelamin)) {
            $this->session->set_flashdata('success', 'Perhitungan berhasil dilakukan dan disimpan.');
        } else {
            $this->session->set_flashdata('error', 'Terjadi kesalahan saat menyimpan hasil.');
        }

        redirect('Perhitungan/index?id_pemijahan=' . $id_pemijahan . '&jenis_kelamin=' . $jenis_kelamin);
    }



    public function hasil()
    {
        $id_pemijahan = $this->input->get('id_pemijahan');
        $jenis_kelamin = $this->input->get('jenis_kelamin');
        $id_upr = $this->session->userdata('id_upr');

        $hasil_moora = $this->Perhitungan_model->get_hasil_moora_by_pemijahan_and_gender($id_pemijahan, $id_upr, $jenis_kelamin);

        // Ambil nilai sub-kriteria untuk setiap alternatif
        foreach ($hasil_moora as $key => $value) {
            $nilai_kriteria = $this->Perhitungan_model->get_nilai_kriteria_by_alternatif($value->id_alternatif, $id_upr);
            foreach ($nilai_kriteria as $kriteria) {
                $sub_kriteria = $this->Perhitungan_model->get_sub_kriteria_by_id($kriteria['nilai']);
                $nilai_kriteria[$kriteria['id_kriteria']] = $sub_kriteria['nilai']; // atau 'deskripsi' jika ingin menampilkan deskripsi
            }
            $hasil_moora[$key]->nilai_kriteria = $nilai_kriteria;
        }

        // Urutkan hasil Moora berdasarkan nilai tertinggi
        usort($hasil_moora, function ($a, $b) {
            return $b->nilai <=> $a->nilai;
        });

        // Buat mapping ranking berdasarkan id_alternatif
        $rank_mapping = [];
        $rank = 1;
        foreach ($hasil_moora as $keys) {
            $rank_mapping[$keys->id_alternatif] = $rank;
            $rank++;
        }

        $data = [
            'page' => "Hasil",
            'hasil_moora' => $hasil_moora,
            'rank_mapping' => $rank_mapping, // Kirim ranking ke view
            'id_pemijahan' => $id_pemijahan,
            'jenis_kelamin' => $jenis_kelamin,
            'waktu_pemijahan' => $this->Perhitungan_model->get_waktu_pemijahan_by_upr($id_upr),
            'detail_pemijahan' => $this->Perhitungan_model->get_pemijahan_detail($id_pemijahan),
            'is_historis_exist' => $this->is_historis_exist($id_pemijahan),
            'kriterias' => $this->Perhitungan_model->get_kriteria_by_gender($jenis_kelamin)
        ];

        $this->load->view('Perhitungan/hasil', $data);
    }


    public function update_pilihan()
    {
        $id_hasil = $this->input->post('id_hasil');
        $status_pilih = $this->input->post('status_pilih');

        $this->db->where('id_hasil_moora', $id_hasil);
        $this->db->update('hasil_moora', ['status_pilih' => $status_pilih]);

        echo json_encode(["status" => "success"]);
    }

    public function cetak_laporan($id_pemijahan = null)
    {
        $id_upr = $this->session->userdata('id_upr');

        // Ambil detail pemijahan
        $detail_pemijahan = $this->Perhitungan_model->get_pemijahan_detail($id_pemijahan);

        // Ambil hasil MOORA yang DIPILIH (status_pilih = 1)
        $hasil_moora = $this->Perhitungan_model->get_hasil_moora_by_pemijahan_and_status($id_pemijahan, $id_upr);

        // Proses data kriteria untuk setiap alternatif
        foreach ($hasil_moora as $key => $value) {
            $kriteria_data = $this->Perhitungan_model->get_kriteria_dan_sub_kriteria(
                $value->id_alternatif,
                $id_upr,
                $value->jenis_kelamin
            );

            // Format data kriteria sesuai kebutuhan view
            $formatted_kriteria = [];
            foreach ($kriteria_data as $kriteria) {
                $formatted_kriteria[] = [
                    'kode_kriteria' => $kriteria['kode_kriteria'],
                    'keterangan' => $kriteria['keterangan'],
                    'nilai' => $kriteria['nilai']
                ];
            }
            $hasil_moora[$key]->kriteria_sub_kriteria = $formatted_kriteria;
        }

        // Pisahkan hasil berdasarkan jenis kelamin
        $hasil_jantan = [];
        $hasil_betina = [];

        foreach ($hasil_moora as $item) {
            if ($item->jenis_kelamin == 'Jantan') {
                $hasil_jantan[] = $item;
            } else if ($item->jenis_kelamin == 'Betina') {
                $hasil_betina[] = $item;
            }
        }

        // Urutkan berdasarkan nilai tertinggi
        usort($hasil_jantan, function ($a, $b) {
            return $b->nilai <=> $a->nilai;
        });
        usort($hasil_betina, function ($a, $b) {
            return $b->nilai <=> $a->nilai;
        });

        // Ambil semua kriteria untuk header tabel
        $all_kriteria = $this->Perhitungan_model->get_all_kriteria();
        $kriteria_list_jantan = [];
        $kriteria_list_betina = [];

        foreach ($all_kriteria as $row) {
            if ($row['jenis_kelamin'] == 'Jantan') {
                $kriteria_list_jantan[$row['kode_kriteria']] = [
                    'keterangan' => $row['keterangan'],
                    'kode' => $row['kode_kriteria'],
                    'bobot' => $row['bobot'],
                    'jenis' => $row['jenis'] // benefit/cost
                ];
            } else if ($row['jenis_kelamin'] == 'Betina') {
                $kriteria_list_betina[$row['kode_kriteria']] = [
                    'keterangan' => $row['keterangan'],
                    'kode' => $row['kode_kriteria'],
                    'bobot' => $row['bobot'],
                    'jenis' => $row['jenis'] // benefit/cost
                ];
            }
        }

        $data = [
            'detail_pemijahan' => $detail_pemijahan,
            'hasil_moora' => $hasil_moora,
            'kriteria_list_jantan' => $kriteria_list_jantan,
            'kriteria_list_betina' => $kriteria_list_betina,
            'hasil_jantan' => $hasil_jantan,
            'hasil_betina' => $hasil_betina
        ];

        $this->load->view('laporan', $data);
    }

    public function cetak_laporanh($id_pemijahan = null)
    {
        // Cek apakah id_pemijahan tersedia
        if (!$id_pemijahan) {
            show_error('ID Pemijahan tidak ditemukan.', 404);
        }

        $id_upr = $this->session->userdata('id_upr');

        // Ambil id_historis terbaru berdasarkan id_pemijahan dan id_upr
        $historis = $this->db
            ->where('id_pemijahan', $id_pemijahan)
            ->where('id_upr', $id_upr)
            ->order_by('created_at', 'DESC')
            ->limit(1)
            ->get('historis')
            ->row();

        if (!$historis) {
            show_error('Data historis tidak ditemukan.', 404);
        }

        // Ambil detail lengkap historis melalui model
        $detail_historis = $this->Perhitungan_model->get_detail_historis($historis->id_historis, $id_upr);


        // Ambil semua hasil MOORA berdasarkan id_pemijahan
        $hasil_moora = $this->Perhitungan_model->get_hasil_moora_by_pemijahan($id_pemijahan, $id_upr);

        // Proses data kriteria
        foreach ($hasil_moora as $key => $value) {
            $hasil_moora[$key]->kriteria_sub_kriteria =
                $this->Perhitungan_model->get_kriteria_dan_sub_kriteria(
                    $value->id_alternatif,
                    $id_upr,
                    $value->jenis_kelamin
                );
        }

        $data = [
            'detail_historis' => $detail_historis,
            'hasil_moora' => $hasil_moora,
            'kriterias_jantan' => $this->Perhitungan_model->get_kriteria_by_gender('Jantan'),
            'kriterias_betina' => $this->Perhitungan_model->get_kriteria_by_gender('Betina')
        ];

        $this->load->view('laporannnn', $data);
    }


    public function detail_historis($id_pemijahan)
    {
        $data['page'] = "Detail Historis";
        $id_upr = $this->session->userdata('id_upr');

        // Ambil data historis berdasarkan pemijahan
        $data['detail'] = $this->Perhitungan_model->get_historis_by_pemijahan($id_pemijahan);

        // Get pemijahan data
        $data['pemijahan'] = $this->db->get_where('pemijahan', ['id_pemijahan' => $id_pemijahan])->row();


        // Pastikan ada data historis sebelum mengambil id_historis
        if (!empty($data['detail'])) {
            $data['id_historis'] = $data['detail'][0]->id_historis;
        } else {
            $data['id_historis'] = null; // Hindari error jika data kosong
        }

        // Ambil hasil MOORA
        $hasil_moora = $this->Perhitungan_model->get_hasil_moora_by_pemijahan($id_pemijahan, $id_upr);

        foreach ($hasil_moora as $key => $value) {
            $hasil_moora[$key]->kriteria_sub_kriteria =
                $this->Perhitungan_model->get_kriteria_dan_sub_kriteria($value->id_alternatif, $id_upr, $value->jenis_kelamin);
        }

        // Ambil nilai kriteria per alternatif
        $data['nilai_kriteria'] = $this->Perhitungan_model->get_nilai_kriteria_by_pemijahan($id_pemijahan, $id_upr);

        // Ambil detail pemijahan
        $data['detail_pemijahan'] = $this->Perhitungan_model->get_pemijahan_detail($id_pemijahan);

        // Ambil data kriteria
        $data['kriterias_jantan'] = $this->Perhitungan_model->get_kriteria_by_gender('Jantan');
        $data['kriterias_betina'] = $this->Perhitungan_model->get_kriteria_by_gender('Betina');

        // Pisahkan data historis berdasarkan jenis kelamin
        $data['detail_jantan'] = array_filter($data['detail'], function ($item) {
            return $item->jenis_kelamin == 'Jantan';
        });
        $data['detail_betina'] = array_filter($data['detail'], function ($item) {
            return $item->jenis_kelamin == 'Betina';
        });

        // Pastikan data detail tidak kosong
        if (empty($data['detail'])) {
            show_404(); // Jika data kosong, tampilkan halaman 404
        }

        $this->load->view('perhitungan/detail_historis_view', $data);
    }


    public function simpan_historis()
    {
        $id_pemijahan = $this->input->post('id_pemijahan');
        $id_upr = $this->session->userdata('id_upr');

        if (!$id_pemijahan || !$id_upr) {
            echo json_encode(['status' => 'error', 'message' => 'Parameter tidak lengkap.']);
            return;
        }

        $pemijahan = $this->db->select('waktu_pemijahan')
            ->where('id_pemijahan', $id_pemijahan)
            ->get('pemijahan')
            ->row();

        $data_terpilih = $this->Perhitungan_model->get_data_terpilih($id_pemijahan, $id_upr);

        if (empty($data_terpilih)) {
            echo json_encode(['status' => 'error', 'message' => 'Tidak ada alternatif yang dipilih.']);
            return;
        }

        $waktu_pemijahan = date('d-m-Y', strtotime($pemijahan->waktu_pemijahan));
        $keterangan = "Hasil Perhitungan Per Waktu Pemijahan $waktu_pemijahan";

        // Buat array untuk insert_batch
        $data_historis = [];
        foreach ($data_terpilih as $data) {
            $data_historis[] = [
                'id_alternatif' => $data->id_alternatif,
                'id_pemijahan' => $id_pemijahan,
                'id_upr' => $id_upr,
                'nilai' => $data->nilai,
                'keterangan' => $keterangan,
                'jenis_kelamin' => $data->jenis_kelamin
            ];
        }

        $this->db->trans_start();

        try {
            $this->db->insert_batch('historis', $data_historis);

            // Update status pemijahan
            $this->db->set('status', 1);
            $this->db->where('id_pemijahan', $id_pemijahan);
            $this->db->update('pemijahan');

            // Hapus hasil MOORA
            $this->db->where('id_pemijahan', $id_pemijahan);
            $this->db->where('id_upr', $id_upr);
            $this->db->delete('hasil_moora');

            $this->db->trans_complete();

            if ($this->db->trans_status() === FALSE) {
                throw new Exception('Terjadi kesalahan saat menyimpan data historis.');
            }

            $this->session->unset_userdata('id_pemijahan');
            echo json_encode(['status' => 'success', 'message' => 'Data historis berhasil disimpan.']);
        } catch (Exception $e) {
            $this->db->trans_rollback();
            log_message('error', 'Error simpan_historis: ' . $e->getMessage());
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }



    public function reset_session_pemijahan()
    {
        $this->session->unset_userdata('id_pemijahan');
        echo json_encode(['status' => 'success']);
    }


    public function data_historis()
    {
        $id_upr = $this->session->userdata('id_upr');

        // Mengambil data historis yang sudah dikelompokkan berdasarkan id_pemijahan
        $historis = $this->Perhitungan_model->get_all_historis_by_pemijahan($id_upr);

        $data = [
            'page' => "Data Historis",
            'historis' => $historis
        ];

        $this->load->view('Perhitungan/data_historis', $data);
    }
    public function is_historis_exist($id_pemijahan)
    {
        $query = $this->db->get_where('historis', ['id_pemijahan' => $id_pemijahan]);
        return $query->num_rows() > 0;
    }

    public function cek_perubahan_data($id_pemijahan)
    {
        $this->load->model('Perhitungan_model');

        $is_changed = $this->Perhitungan_model->cek_perubahan($id_pemijahan);
        echo json_encode(['is_changed' => $is_changed]);
    }


    public function hapus_historis($id_pemijahan)
    {
        $this->db->trans_start();

        // Hapus semua data historis terkait pemijahan
        $this->db->where('id_pemijahan', $id_pemijahan);
        $delete_success = $this->db->delete('historis');

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE || !$delete_success) {
            $this->session->set_flashdata('error', 'Gagal menghapus data historis.');
        } else {
            $this->session->set_flashdata('success', 'Data historis berhasil dihapus.');
        }
        redirect('Perhitungan/data_historis');
    }



    public function grafik_nilai_per_upr($tahun = null)
    {
        $id_upr = $this->session->userdata('id_upr'); // Ambil ID UPR dari session
        $data = $this->Perhitungan_model->get_nilai_tertinggi_per_waktu($id_upr, $tahun);

        echo json_encode($data); // Kirim data sebagai JSON
    }
}
