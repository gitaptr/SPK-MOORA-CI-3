<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Hasilpmj extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('pagination');
        $this->load->library('form_validation');
        $this->load->model('Hasilpmj_model');
        $this->load->model('Pemijahan_model');
        $this->load->model('Induk_model');
        $this->load->model('Kolam_model');
        $this->load->model('Stock_model');
        $this->load->model('User_model'); // Tambahkan ini agar bisa mendapatkan data UPR

        $level = $this->session->userdata('id_user_level');
        $this->id_upr = $this->session->userdata('id_upr');
        $this->id_wilayah = $this->session->userdata('id_wilayah');

        if ($level != "2" && $level != "3") {
            redirect('Login/home');
        }

        $this->read_only = ($level == "2");
    }

    public function index()
    {
        $id_user_level = $this->session->userdata('id_user_level');
        $selected_upr = $this->input->get('upr_id');
        $id_upr = $this->session->userdata('id_upr');
        $id_wilayah = $this->session->userdata('id_wilayah');


        if ($id_user_level == 3) {
            $list_spk = $this->Hasilpmj_model->tampil_by_upr_grouped($id_upr);
            $list_manual_all = $this->Hasilpmj_model->tampil_manual_by_upr_grouped($id_upr);
        } elseif ($id_user_level == 2) {
            if ($selected_upr) {
                $list_spk = $this->Hasilpmj_model->tampil_by_upr_grouped($selected_upr);
                $list_manual_all = $this->Hasilpmj_model->tampil_manual_by_upr_grouped($selected_upr);
            } else {
                $list_spk = $this->Hasilpmj_model->tampil_by_wilayah_grouped($id_wilayah);
                $list_manual_all = $this->Hasilpmj_model->tampil_manual_by_wilayah_grouped($id_wilayah);
            }
            $data['upr_list'] = $this->User_model->get_upr_by_wilayah($id_wilayah);
        } else {
            $list_spk = $this->Hasilpmj_model->tampil_grouped();
            $list_manual_all = $this->Hasilpmj_model->tampil_manual_grouped();
        }

        // Ambil semua waktu pemijahan dari SPK
        $spk_waktu = array_map(function ($item) {
            return $item->waktu_pemijahan;
        }, $list_spk);

        // Filter data manual agar tidak duplicate waktu_pemijahan
        $list_manual = array_filter($list_manual_all, function ($item) use ($spk_waktu) {
            return !in_array($item->waktu_pemijahan, $spk_waktu);
        });

        // Gabungkan hasil akhir
        $data['list'] = array_merge($list_spk, $list_manual);


        $data['page'] = "Hasilpmj";
        $data['read_only'] = isset($this->read_only) ? $this->read_only : false;
        $data['selected_upr'] = $selected_upr;
        $data['id_user_level'] = $id_user_level;

        $this->session->unset_userdata('message');

        $this->load->view('hasilpmj/index', $data);
    }

    public function get_pemijahan_details()
    {
        $waktu_pemijahan = $this->input->post('waktu_pemijahan');

        if ($waktu_pemijahan) {
            $this->load->model('Pemijahan_model');
            $data = $this->Pemijahan_model->get_details_by_waktu($waktu_pemijahan);

            if ($data) {
                echo json_encode($data);
            } else {
                echo json_encode([]);
            }
        } else {
            echo json_encode([]);
        }
    }


    public function create()
    {
        if ($this->read_only) {
            show_error('Anda tidak memiliki hak akses untuk menambahkan data.', 403, 'Akses Ditolak');
        }

        $data['page'] = "Hasilpmj";
        $data['pemijahan_list'] = $this->Pemijahan_model->get_all_pemijahan_by_upr($this->id_upr);
        $data['alternatif_list'] = $this->Hasilpmj_model->get_alternatif_status_0(); // Ambil data alternatif status 0

        // Ambil data kolam berdasarkan id_upr menggunakan model
        $data['kolam_list'] = $this->Kolam_model->get_kolam_by_upr($this->id_upr);

        // Data untuk tab SPK (status 2)
        $data['pemijahan_list_spk'] = $this->Pemijahan_model->get_by_status(1);

        // Data untuk tab Manual (status 0)
        $data['pemijahan_list_manual'] = $this->Pemijahan_model->get_by_status(0);

        $this->load->view('hasilpmj/create', $data);
    }

    public function store()
    {
        $id_upr = $this->session->userdata('id_upr');
        $waktu_pemijahan = $this->input->post('waktu_pemijahan');

        // Validasi dasar
        if (empty($waktu_pemijahan)) {
            $this->session->set_flashdata('swal', [
                'type' => 'error',
                'title' => 'Gagal',
                'text' => 'Waktu pemijahan harus dipilih!'
            ]);
            redirect('hasilpmj/create');
        }

        $pemijahan = $this->Pemijahan_model->get_by_waktu($waktu_pemijahan);
        if (!$pemijahan) {
            $this->session->set_flashdata('swal', [
                'type' => 'error',
                'title' => 'Gagal',
                'text' => 'Data pemijahan tidak ditemukan.'
            ]);
            redirect('hasilpmj/create');
        }

        // Siapkan data dasar
        $base_data = [
            'waktu_pemijahan' => $waktu_pemijahan,
            'kolam' => $pemijahan->kolam,
            'metode_pemijahan' => $pemijahan->metode_pemijahan,
            'id_upr' => $id_upr,
            'created_at' => date('Y-m-d H:i:s')
        ];

        $has_spk = false;
        $has_manual = false;
        $total_benih = 0; // Total semua benih (SPK + Manual)

        try {
            $this->db->trans_begin();

            // Proses data SPK jika ada
            $jumlah_benih_spk = (int)$this->input->post('jumlah_benih_spk');
            if ($jumlah_benih_spk > 0) {
                // Validasi field SPK
                if (empty($this->input->post('jumlah_telur_spk'))) {
                    throw new Exception('Jumlah telur SPK harus diisi!');
                }
                if (empty($this->input->post('tingkat_netas_spk'))) {
                    throw new Exception('Tingkat penetasan SPK harus diisi!');
                }

                $data_spk = array_merge($base_data, [
                    'jumlah_telur' => (int)$this->input->post('jumlah_telur_spk'),
                    'tingkat_netas' => $this->input->post('tingkat_netas_spk'),
                    'jumlah_benih' => $jumlah_benih_spk,
                    'ket' => $this->input->post('keterangan_spk'),
                ]);

                if (!$this->Hasilpmj_model->insert($data_spk)) {
                    throw new Exception('Gagal menyimpan data hasil pemijahan SPK');
                }

                $has_spk = true;
                $total_benih += $jumlah_benih_spk;
            }

            // Proses data Manual jika ada
            $induk_nama = $this->input->post('induk_manual_nama');
            $jumlah_benih_manual = (int)$this->input->post('jumlah_benih_manual');
            $jumlah_induk_manual = count($induk_nama ?? []);

            if ($jumlah_induk_manual > 0) {
                // Validasi field Manual
                if ($jumlah_benih_manual <= 0) {
                    throw new Exception('Jumlah benih Manual harus lebih dari 0!');
                }
                if (empty($this->input->post('jumlah_telur_manual'))) {
                    throw new Exception('Jumlah telur Manual harus diisi!');
                }
                if (empty($this->input->post('tingkat_netas_manual'))) {
                    throw new Exception('Tingkat penetasan Manual harus diisi!');
                }

                $data_manual = array_merge($base_data, [
                    'jumlah_telur' => (int)$this->input->post('jumlah_telur_manual'),
                    'tingkat_netas' => $this->input->post('tingkat_netas_manual'),
                    'jumlah_benih' => $jumlah_benih_manual,
                    'ket' => $this->input->post('keterangan_manual'),
                ]);

                // Simpan setiap induk manual
                for ($i = 0; $i < $jumlah_induk_manual; $i++) {
                    $manual_data = [
                        'id_upr' => $data_manual['id_upr'],
                        'waktu_pemijahan' => $data_manual['waktu_pemijahan'],
                        'kolam' => $data_manual['kolam'],
                        'metode_pemijahan' => $data_manual['metode_pemijahan'],
                        'induk' => $induk_nama[$i] ?? '',
                        'kolam_induk' => $this->input->post('induk_manual_kolam')[$i] ?? '',
                        'jenis_kelamin' => $this->input->post('induk_manual_jenis_kelamin')[$i] ?? '',
                        'jumlah_telur' => $data_manual['jumlah_telur'],
                        'tingkat_netas' => $data_manual['tingkat_netas'],
                        'jumlah_benih' => $data_manual['jumlah_benih'],
                        'ket' => $data_manual['ket']
                    ];

                    if (!$this->Hasilpmj_model->insert_manual($manual_data)) {
                        throw new Exception('Gagal menyimpan data hasil pemijahan manual');
                    }
                }

                $has_manual = true;
                $total_benih += $jumlah_benih_manual;
            }

            // Validasi minimal harus ada data SPK atau Manual
            if (!$has_spk && !$has_manual) {
                throw new Exception('Minimal salah satu data (SPK atau Manual) harus diisi dengan benar!');
            }

            // Proses stok benih (total dari SPK dan Manual)
            if ($total_benih > 0) {
                $this->processStock($base_data, $total_benih);
            }

            // Update status pemijahan menjadi 2 (selesai)
            $this->Pemijahan_model->update_status_by_waktu($waktu_pemijahan, 2);

            $this->db->trans_commit();

            $this->session->set_flashdata('swal', [
                'type' => 'success',
                'title' => 'Berhasil',
                'text' => 'Data hasil pemijahan berhasil disimpan! Total benih: ' . $total_benih
            ]);
            redirect('hasilpmj');
        } catch (Exception $e) {
            $this->db->trans_rollback();
            $this->session->set_flashdata('swal', [
                'type' => 'error',
                'title' => 'Gagal',
                'text' => $e->getMessage()
            ]);
            redirect('hasilpmj/create');
        }
    }

    private function processStock($base_data, $total_benih)
    {
        // Cek apakah stok sudah ada untuk waktu pemijahan ini
        $existing_stock = $this->Stock_model->get_by_waktu_pemijahan(
            $base_data['waktu_pemijahan'],
            $base_data['kolam'], // Tambahkan ini
            $base_data['id_upr']   // Tambahkan ini
        );

        if ($existing_stock) {
            // Update stok yang ada dengan nilai yang benar
            $update_data = [
                'jumlah' => $total_benih,
                'created_at' => date('Y-m-d H:i:s')
            ];
            return $this->Stock_model->update($existing_stock->id_stok_benih, $update_data);
        } else {
            // Buat stok baru
            $data_stok = [
                'tanggal' => date('Y-m-d'),
                'waktu_pemijahan' => $base_data['waktu_pemijahan'],
                'jumlah' => $total_benih,
                'ukuran' => '0.25',
                'umur' => '1-3',
                'kolam' => $base_data['kolam'],
                'sumber' => 'Pemijahan - ' . date('d M Y', strtotime($base_data['waktu_pemijahan'])),
                'keterangan' => $base_data['ket'] ?? "", // Pastikan 'ket' ada di $base_data jika tidak, bisa error
                'id_upr' => $base_data['id_upr']
            ];
            return $this->Stock_model->insert($data_stok);
        }
    }


    public function detail($waktu_pemijahan = null)
    {
        // Ambil dari segment URL jika parameter tidak tersedia
        if ($waktu_pemijahan === null) {
            $waktu_pemijahan = $this->uri->segment(3);
        }

        // Ambil dari query string jika parameter tidak tersedia
        if ($this->input->get('waktu_pemijahan')) {
            $waktu_pemijahan = $this->input->get('waktu_pemijahan');
        }
        if (!$waktu_pemijahan) {
            show_error('Parameter waktu_pemijahan tidak tersedia.', 400, 'Bad Request');
        }

        // Ganti underscore ke titik (jika ada)
        $waktu_pemijahan = str_replace('_', '.', $waktu_pemijahan);

        // Validasi format tanggal
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $waktu_pemijahan)) {
            show_error('Format waktu pemijahan tidak valid.', 400, 'Bad Request');
        }

        // Ambil informasi user dari sesi
        $id_user_level = $this->session->userdata('id_user_level');
        $selected_upr = $this->input->get('upr_id') ?? $this->session->userdata('id_upr');


        // Ambil semua data yang diperlukan dari model
        $data['detail']         = $this->Hasilpmj_model->get_by_waktu_pemijahan($waktu_pemijahan);         // dari hasilpmj
        $data['manual']         = $this->Hasilpmj_model->get_manual_by_waktu_pemijahan($waktu_pemijahan);  // dari hasilpmj_manual
        $data['spk']            = $this->Hasilpmj_model->get_spk_by_waktu_pemijahan($waktu_pemijahan);     // dari hasil_moora
        $data['induk_spk']      = $this->Hasilpmj_model->get_induk_spk_by_waktu($waktu_pemijahan);         // induk dari spk
        $data['induk_manual']   = $this->Hasilpmj_model->get_induk_manual_by_waktu($waktu_pemijahan);      // induk dari manual

        $data['id_user_level']  = $id_user_level;
        $data['selected_upr']   = $selected_upr;
        $data['user_level']     = $id_user_level;
        $data['page']           = "Detail HasilPMJ";

        // Jika semua data kosong, tampilkan error
        if (
            empty($data['detail']) &&
            empty($data['manual']) &&
            empty($data['spk']) &&
            empty($data['induk_spk']) &&
            empty($data['induk_manual'])
        ) {
            show_error('Data tidak ditemukan.', 404, 'Not Found');
        }

        // Tentukan data utama untuk informasi umum pemijahan
        $data['item_pemijahan'] = !empty($data['spk']) ? $data['spk'] : $data['manual'];

        // Load view
        $this->load->view('hasilpmj/detail', $data);
    }


    public function get_induk_spk()
    {
        $waktu_pemijahan = $this->input->post('waktu_pemijahan');

        $this->load->model('Hasilpmj_model');
        $dataInduk = $this->Hasilpmj_model->get_induk_spk_by_waktu($waktu_pemijahan);

        $output = "";
        if (!empty($dataInduk)) {
            foreach ($dataInduk as $row) {
                $output .= "<tr>
                                <td>{$row->nama}</td>
                                <td>{$row->jenis_kelamin}</td>
                                <td>{$row->nilai}</td>
                            </tr>";
            }
        } else {
            $output = "<tr><td colspan='3' class='text-center'>Data tidak tersedia</td></tr>";
        }

        echo json_encode(['data' => $dataInduk, 'html' => $output]);
    }

    public function edit($waktu_pemijahan, $type = 'spk')
    {
        if ($this->read_only) {
            show_error('Anda tidak memiliki hak akses untuk mengedit data.', 403, 'Akses Ditolak');
        }

        // Ambil data pemijahan terkait
        $pemijahan = $this->Pemijahan_model->get_by_waktu($waktu_pemijahan);

        if (!$pemijahan) {
            show_error('Data pemijahan tidak ditemukan', 404, 'Not Found');
        }

        // Inisialisasi semua variabel yang dibutuhkan view
        $data = [
            'page' => "Edit Hasil Pemijahan",
            'type' => $type,
            'pemijahan_list' => $this->Pemijahan_model->get_all_pemijahan_by_upr($this->id_upr),
            'kolam_list' => $this->Kolam_model->get_kolam_by_upr($this->id_upr),
            'pemijahan_info' => $pemijahan,
            'hasilpmj' => null,
            'spk_data' => [
                'jumlah_telur' => 0,
                'tingkat_netas' => 0,
                'jumlah_benih' => 0,
                'ket' => ''
            ],
            'manual_data' => [
                'jumlah_telur' => 0,
                'tingkat_netas' => 0,
                'jumlah_benih' => 0,
                'ket' => ''
            ],
            'induk_spk' => [],
            'induk_manual' => []
        ];

        // Handle data SPK (baik type=spk atau gabungan)
        $spk_data = $this->Hasilpmj_model->get_spk_by_waktu_pemijahan($waktu_pemijahan);
        if ($spk_data) {
            $data['hasilpmj'] = (object) array_merge((array) $spk_data, [
                'kolam' => $pemijahan->kolam,
                'metode_pemijahan' => $pemijahan->metode_pemijahan
            ]);

            // Perbaikan: Pastikan spk_data sebagai array
            $data['spk_data'] = [
                'jumlah_telur' => $spk_data->jumlah_telur ?? 0,
                'tingkat_netas' => $spk_data->tingkat_netas ?? 0,
                'jumlah_benih' => $spk_data->jumlah_benih ?? 0,
                'ket' => $spk_data->ket ?? ''
            ];
        } else {
            $data['spk_data'] = [
                'jumlah_telur' => 0,
                'tingkat_netas' => 0,
                'jumlah_benih' => 0,
                'ket' => ''
            ];
        }

        // Handle data manual
        $manual_data = $this->Hasilpmj_model->get_manual_by_waktu($waktu_pemijahan);
        if (!empty($manual_data)) {
            $data['manual_data'] = [
                'jumlah_telur' => $manual_data[0]->jumlah_telur ?? 0,
                'tingkat_netas' => $manual_data[0]->tingkat_netas ?? 0,
                'jumlah_benih' => $manual_data[0]->jumlah_benih ?? 0,
                'ket' => $manual_data[0]->ket ?? '',
                'waktu_pemijahan' => $manual_data[0]->waktu_pemijahan ?? '',
                'kolam' => $manual_data[0]->kolam ?? '',
                'metode_pemijahan' => $manual_data[0]->metode_pemijahan ?? ''
            ];
        } else {
            $data['manual_data'] = [
                'jumlah_telur' => 0,
                'tingkat_netas' => 0,
                'jumlah_benih' => 0,
                'ket' => '',
                'waktu_pemijahan' => '',
                'kolam' => '',
                'metode_pemijahan' => ''
            ];
        }
        // Ambil data induk
        $data['induk_spk'] = $this->Hasilpmj_model->get_induk_spk_by_waktu($waktu_pemijahan);
        $data['induk_manual'] = $this->Hasilpmj_model->get_induk_manual_by_waktu($waktu_pemijahan);

        $this->load->view('hasilpmj/edit', $data);
    }

    public function update($waktu_pemijahan, $type = 'spk')
    {
        if ($this->read_only) {
            $this->session->set_flashdata('swal', [
                'type' => 'error',
                'title' => 'Akses Ditolak',
                'text' => 'Anda tidak memiliki izin untuk mengupdate data'
            ]);
            redirect('hasilpmj');
        }

        try {
            $this->db->trans_begin();

            $waktu_pemijahan = $this->input->post('waktu_pemijahan') ?: $waktu_pemijahan;

            if (!$waktu_pemijahan || strtotime($waktu_pemijahan) === false) {
                throw new Exception("Waktu pemijahan tidak valid.");
            }


            // Ambil data input
            $has_spk = $this->input->post('has_spk');
            $has_manual = $this->input->post('has_manual');

            $kolam = $this->input->post('kolam');
            $metode_pemijahan = $this->input->post('metode_pemijahan');
            $total_benih = 0;

            // Update data SPK jika ada
            if ($has_spk) {
                $data_spk = [
                    'jumlah_telur' => $this->input->post('spk_jumlah_telur'),
                    'tingkat_netas' => $this->input->post('spk_tingkat_netas'),
                    'jumlah_benih' => $this->input->post('spk_jumlah_benih'),
                    'ket' => $this->input->post('spk_keterangan'),
                    'kolam' => $kolam,
                    'metode_pemijahan' => $metode_pemijahan,
                    'updated_at' => date('Y-m-d H:i:s')
                ];

                $this->Hasilpmj_model->update_by_waktu($waktu_pemijahan, $data_spk);
                $total_benih += $data_spk['jumlah_benih'];
            }

            // Update data Manual jika ada
            if ($has_manual) {
                $data_manual = [
                    'jumlah_telur' => $this->input->post('manual_jumlah_telur'),
                    'tingkat_netas' => $this->input->post('manual_tingkat_netas'),
                    'jumlah_benih' => $this->input->post('manual_jumlah_benih'),
                    'ket' => $this->input->post('manual_keterangan'),
                    'kolam' => $kolam,
                    'metode_pemijahan' => $metode_pemijahan,
                    'updated_at' => date('Y-m-d H:i:s')
                ];

                $this->Hasilpmj_model->update_manual_by_waktu($waktu_pemijahan, $data_manual);
                $total_benih += $data_manual['jumlah_benih'];
            }

            // Update stok benih dengan total yang baru
            $this->update_stock($waktu_pemijahan, $total_benih, $kolam, '');

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $this->session->set_flashdata('swal', [
                    'type' => 'error',
                    'title' => 'Gagal',
                    'text' => 'Gagal memperbarui data hasil pemijahan'
                ]);
            } else {
                $this->db->trans_commit();
                $this->session->set_flashdata('swal', [
                    'type' => 'success',
                    'title' => 'Berhasil',
                    'text' => 'Data hasil pemijahan berhasil diperbarui'
                ]);
            }
        } catch (Exception $e) {
            $this->db->trans_rollback();
            $this->session->set_flashdata('swal', [
                'type' => 'error',
                'title' => 'Error',
                'text' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }

        redirect('hasilpmj');
    }

    private function update_stock($waktu_pemijahan, $jumlah_benih, $kolam, $keterangan)
    {
        if (!$waktu_pemijahan || strtotime($waktu_pemijahan) === false) {
            throw new Exception("Waktu pemijahan tidak valid.");
        }

        $id_upr = $this->session->userdata('id_upr');
        $existing_stock = $this->Stock_model->get_by_waktu_pemijahan($waktu_pemijahan, $kolam, $id_upr);

        $data_stok = [
            'jumlah' => $jumlah_benih,
            'kolam' => $kolam,
            'updated_at' => date('Y-m-d H:i:s')
        ];

        if ($existing_stock) {
            $this->Stock_model->update($existing_stock->id_stok_benih, $data_stok);
        } else {
            $data_stok['tanggal'] = date('Y-m-d');
            $data_stok['waktu_pemijahan'] = $waktu_pemijahan;
            $data_stok['ukuran'] = '1-3';
            $data_stok['umur'] = '4-10';
            $data_stok['sumber'] = 'Pemijahan - ' . date('d M Y', strtotime($waktu_pemijahan));
            $data_stok['id_upr'] = $id_upr;
            $data_stok['created_at'] = date('Y-m-d H:i:s');

            $this->Stock_model->insert($data_stok);
        }
    }

    public function destroy($waktu_pemijahan, $type = 'spk')
    {
        // Validasi user level
        if ($this->read_only) {
            $this->session->set_flashdata('swal', [
                'type' => 'error',
                'title' => 'Akses Ditolak',
                'text' => 'Anda tidak memiliki izin untuk menghapus data'
            ]);
            redirect('hasilpmj');
        }

        try {
            $this->db->trans_begin();

            // Hapus data SPK (hasilpmj)
            $this->Hasilpmj_model->delete_by_waktu($waktu_pemijahan);
            // Hapus data manual (hasilpmj_manual)
            $this->Hasilpmj_model->delete_manual_by_waktu($waktu_pemijahan);

            // Hapus stok
            $this->Stock_model->delete_by_waktu_pemijahan($waktu_pemijahan);

            // Update status pemijahan
            $pemijahan = $this->Pemijahan_model->get_by_waktu($waktu_pemijahan);
            if ($pemijahan) {
                $this->Pemijahan_model->update_status_by_waktu($waktu_pemijahan, 1);
            }

            $this->db->trans_commit();

            $this->session->set_flashdata('swal', [
                'type' => 'success',
                'title' => 'Berhasil',
                'text' => 'Data hasil pemijahan berhasil dihapus'
            ]);
        } catch (Exception $e) {
            $this->db->trans_rollback();

            $this->session->set_flashdata('swal', [
                'type' => 'error',
                'title' => 'Gagal',
                'text' => 'Gagal menghapus data: ' . $e->getMessage()
            ]);
        }

        redirect('hasilpmj');
    }

    public function cetak_laporan()
    {
        $id_user_level = $this->session->userdata('id_user_level');

        // Validasi user level
        if (!in_array($id_user_level, [2, 3])) {
            show_error('Anda tidak memiliki izin untuk mengakses halaman ini.', 403);
        }

        // Handle parameter UPR berdasarkan user level
        if ($id_user_level == 3) {
            // Untuk user level 3 (UPR), gunakan id_upr dari session
            $id_upr = $this->session->userdata('id_upr');
            if (empty($id_upr)) {
                show_error('ID UPR tidak ditemukan dalam session.', 400, 'Bad Request');
            }
        } else {
            // Untuk user level 2 (Admin Wilayah), wajib ada parameter upr_id
            $upr_id = $this->input->get('upr_id');
            if (empty($upr_id)) {
                show_error('Parameter UPR (upr_id) wajib disertakan.', 400, 'Bad Request');
            }
            $id_upr = $upr_id;
        }

        // Ambil data UPR
        $this->db->select('upr.*, wilayah.nama_wilayah');
        $this->db->from('upr');
        $this->db->join('wilayah', 'wilayah.id_wilayah = upr.id_wilayah', 'left');
        $this->db->where('upr.id_upr', $id_upr);
        $upr_info = $this->db->get()->row();

        if (!$upr_info) {
            show_error('Data UPR tidak ditemukan.', 404, 'Not Found');
        }

        $waktu_pemijahan = $this->input->get('waktu_pemijahan');
        if (empty($waktu_pemijahan)) {
            show_error('Parameter waktu_pemijahan wajib disertakan.', 400, 'Bad Request');
        }

        $metadata = $this->Hasilpmj_model->get_metadata_by_upr_and_waktu($id_upr, $waktu_pemijahan);

        // Siapkan data untuk view
        $data = [
            'upr_nama' => $upr_info->nama_upr,
            'upr_wilayah' => $upr_info->nama_wilayah,
            'metadata' => $metadata,
            'list' => $this->Hasilpmj_model->get_by_upr_and_waktu($id_upr, $waktu_pemijahan),
            'spk' => $this->Hasilpmj_model->get_spk_by_upr_and_waktu($id_upr, $waktu_pemijahan),
            'manual' => $this->Hasilpmj_model->get_manual_by_upr_and_waktu($id_upr, $waktu_pemijahan),
            'induk_spk' => $this->Hasilpmj_model->get_induk_spk_by_upr_and_waktu($id_upr, $waktu_pemijahan),
            'induk_manual' => $this->Hasilpmj_model->get_induk_manual_by_upr_and_waktu($id_upr, $waktu_pemijahan),

        ];

        $this->load->view('laporann', $data);
    }

    public function grafik_hasilpemijahan($tahun = null)
    {
        $id_upr = $this->session->userdata('id_upr');

        // Ambil data dari dua sumber: SPK dan Manual
        $data_spk = $this->Hasilpmj_model->get_hasilpemijahan_per_waktu($id_upr, $tahun);
        $data_manual = $this->Hasilpmj_model->get_hasilpemijahan_manual_per_waktu($id_upr, $tahun);

        $result = [];

        // Proses data SPK
        foreach ($data_spk as $row) {
            $waktu = $row->waktu_pemijahan;
            $metode = 'SPK';
            $total_netas = $row->total_netas;
            $total_telur = $row->total_telur;

            if (!isset($result[$metode])) {
                $result[$metode] = [];
            }
            $result[$metode][$waktu] = [
                "netas" => $total_netas,
                "telur" => $total_telur
            ];
        }

        // Proses data Manual
        foreach ($data_manual as $row) {
            $waktu = $row->waktu_pemijahan;
            $metode = 'Manual';
            $total_netas = $row->total_netas;
            $total_telur = $row->total_telur;

            if (!isset($result[$metode])) {
                $result[$metode] = [];
            }
            $result[$metode][$waktu] = [
                "netas" => $total_netas,
                "telur" => $total_telur
            ];
        }

        // Format akhir data
        $finalData = [];
        foreach ($result as $metode => $values) {
            $finalData[] = [
                "metode" => $metode,
                "netas" => $values,
                "telur" => $values
            ];
        }

        echo json_encode($finalData);
    }

    public function grafik_benih($tahun = null)
    {
        $id_upr = $this->session->userdata('id_upr');
        $data = $this->Hasilpmj_model->get_benih_per_bulan($id_upr, $tahun);

        echo json_encode($data); // Kirim data sebagai JSON ke frontend
    }
    public function grafik_benih_per_upr($tahun = null)
    {
        $id_wilayah = $this->session->userdata('id_wilayah'); // Ambil ID wilayah dari session
        $data = $this->Hasilpmj_model->get_total_benih_per_upr($id_wilayah, $tahun);
        echo json_encode($data);
    }
}
