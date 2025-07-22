<?php $this->load->view('layouts/header_admin'); ?>

<style>
    .modern-card {
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        transition: transform 0.2s;
    }

    .modern-card:hover {
        transform: translateY(-5px);
    }

    .card-header {
        padding: 15px;
        color: white;
        font-weight: bold;
        text-align: center;
    }

    .card-body {
        padding: 20px;
        text-align: center;
    }

    /* Style untuk dropdown tahun */
    #tahunSelect {
        width: 100px;
        /* Lebar dropdown */
        margin-left: auto;
        /* Posisikan ke kanan */
        display: inline-block;
        /* Agar tidak mengambil full width */
    }

    /* Style untuk container dropdown */
    .dropdown-container {
        display: flex;
        align-items: center;
    }
</style>


<?php if ($this->session->userdata('id_user_level') == '1'): ?>
    <div class="mb-4">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h5 mb-0 text-gray-800"><i class="fas fa-fw fa-home"></i> Dashboard</h1>
            <div class="dropdown-container">
                <select id="tahunSelect" class="form-control form-control-sm">
                    <!-- Opsi tahun akan diisi secara dinamis oleh JavaScript -->
                </select>
            </div>
        </div>

        <!-- Content Row -->
        <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            Selamat datang <span class="text-uppercase"><b><?= $this->session->username; ?>!</b></span> Anda bisa mengoperasikan sistem dengan wewenang tertentu melalui pilihan menu di samping.
        </div>
        <div class="row">



            <div class="col-xl-4 col-md-4 mb-4">
                <div class="card modern-card shadow">
                    <div class="card-header bg-primary">Jumlah Wilayah</div>
                    <div class="card-body">
                        <h5><?= isset($total_wilayah) ? $total_wilayah : 'Data tidak tersedia'; ?> </h5>
                    </div>
                </div>
            </div>

            <!-- Content Row -->
            <div class="col-xl-4 col-md-4 mb-4">
                <div class="card modern-card shadow">
                    <div class="card-header bg-success">Jumlah UPR</div>
                    <div class="card-body">
                        <h5><?= isset($total_upr) ? $total_upr : 'Data tidak tersedia'; ?> </h5>
                    </div>
                </div>
            </div>


            <div class="col-xl-4 col-md-4 mb-4">
                <div class="card modern-card shadow">
                    <div class="card-header bg-warning">Jumlah User</div>
                    <div class="card-body">
                        <h5> <?= isset($total_user) ? $total_user : 'Data tidak tersedia'; ?> </h5>
                    </div>
                </div>
            </div>

            <div class="col-xl-12 col-md-12 mb-4">
                <div class="card shadow h-100 py-2">
                    <div class="card-body">
                        <h5 class="text-center font-weight-bold"> Pertumbuhan User</h5>
                        <div id="userChart" style="width: 100%; height: 300px;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const tahunSelect = document.getElementById('tahunSelect');

        // Fungsi untuk mengisi dropdown tahun
        function populateYearDropdown() {
            const currentYear = new Date().getFullYear(); // Tahun saat ini
            const startYear = currentYear - 5; // Mulai dari 5 tahun yang lalu

            // Kosongkan dropdown terlebih dahulu
            tahunSelect.innerHTML = '';

            // Tambahkan opsi tahun dari startYear hingga currentYear
            for (let year = currentYear; year >= startYear; year--) {
                const option = document.createElement('option');
                option.value = year;
                option.textContent = year;
                tahunSelect.appendChild(option);
            }
        }

        // Panggil fungsi untuk mengisi dropdown tahun
        populateYearDropdown();
    });
</script>

<!-- grafik user -->
<!-- Tambahkan library ECharts -->
<script src="https://cdn.jsdelivr.net/npm/echarts/dist/echarts.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const tahunSelect = document.getElementById('tahunSelect');

        function updateDashboard(tahun) {
            // Update card data
            fetch(`<?php echo base_url('Login/get_card_data/'); ?>${tahun}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('total_wilayah').innerText = data.total_wilayah;
                    document.getElementById('total_upr').innerText = data.total_upr;
                    document.getElementById('total_user').innerText = data.total_user;
                })
                .catch(error => console.error("Error:", error));

            // Update chart data
            fetch(`<?php echo base_url('User/get_user_growth_data/'); ?>${tahun}`)
                .then(response => response.json())
                .then(data => {
                    let labels = data.map(item => item.tanggal);
                    let values = data.map(item => item.jumlah);

                    let chartDom = document.getElementById("userChart");
                    let userChart = echarts.init(chartDom);

                    let option = {
                        tooltip: {
                            trigger: "axis"
                        },
                        xAxis: {
                            type: "category",
                            data: labels
                        },
                        yAxis: {
                            type: "value",
                            minInterval: 1
                        },
                        series: [{
                            name: "Jumlah User",
                            type: "line",
                            data: values,
                            smooth: true,
                            lineStyle: {
                                color: "blue"
                            },
                            areaStyle: {
                                color: "rgba(0, 0, 255, 0.2)"
                            },
                            symbol: "circle",
                            symbolSize: 8
                        }]
                    };

                    userChart.setOption(option);
                })
                .catch(error => console.error("Error:", error));
        }

        // Initial load with default year
        updateDashboard(tahunSelect.value);

        // Event listener for year change
        tahunSelect.addEventListener('change', function() {
            updateDashboard(this.value);
        });
    });
</script>

<?php if ($this->session->userdata('id_user_level') == '2'): ?>
    <div class="mb-4">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h5 mb-0 text-gray-800"><i class="fas fa-fw fa-home"></i> Dashboard</h1>
        </div>

         <!-- Content Row -->
        <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            Selamat datang <span class="text-uppercase"><b><?= $this->session->username; ?>!</b></span>. Anda bisa mengoperasikan sistem dengan wewenang tertentu melalui pilihan menu di samping.
        </div>

        <div class="col-12 mb-4">
            <div class="alert alert-info mb-0">
                <i class="fas fa-info-circle"></i> <strong>Langkah Penggunaan Aplikasi</strong>
                <ol class="mt-2 mb-0">
                    <li><strong>Isi Data Kriteria</strong> terlebih dahulu jika data Kriteria belum tersedia di sistem.</li>
                    <li><strong>Isi Data Sub Kriteria</strong> terlebih dahulu jika data Sub Kriteria belum tersedia di sistem.</li>
                    <li><strong>Data UPR</strong> untuk melihat rekap data kolam, induk, induk betina jantan dan benih yang dimiliki masing-masing UPR</li>
                    <li><strong>Data Pemijahan</strong> untuk melihat rekap data pemijahan yang telah dilakukan oleh masing-masing UPR</li>
                    <li><strong>Data Hasil Pemijahan</strong> untuk melihat rekap data hasil pemijahan yang telah dimiliki oleh masing-masing UPR</li>
                </ol>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-6 col-md-6 mb-4">
                <div class="card modern-card shadow">
                    <div class="card-header bg-primary">Jumlah Kriteria</div>
                    <div class="card-body">
                        <h5><?= isset($total_kriteria) ? $total_kriteria : 'Data tidak tersedia'; ?></h5>
                    </div>
                </div>
            </div>

            <div class="col-xl-6 col-md-6 mb-4">
                <div class="card modern-card shadow">
                    <div class="card-header bg-success">Jumlah Sub Kriteria</div>
                    <div class="card-body">
                        <h5><?= isset($total_subkriteria) ? $total_subkriteria : 'Data tidak tersedia'; ?></h5>
                    </div>
                </div>
            </div>

            <div class="col-xl-6 col-md-6 mb-4">
                <div class="card shadow h-100 py-2">
                    <div class="card-body">
                        <h5 class="text-center font-weight-bold"> Kolam UPR</h5>
                        <div id="grafikKolamUPR" style="width: 100%; height: 300px;"></div>
                    </div>
                </div>
            </div>

            <div class="col-xl-6 col-md-6 mb-4">
                <div class="card shadow h-100 py-2">
                    <div class="card-body">
                        <h5 class="text-center font-weight-bold"> Induk Ikan UPR</h5>
                        <div id="grafikInduk" style="width: 100%; height: 300px;"></div>
                    </div>
                </div>
            </div>

            <div class="col-xl-6 col-md-6 mb-6">
                <div class="card shadow h-100 py-2">
                    <div class="card-body">
                        <h5 class="text-center font-weight-bold"> Total Pemijahan UPR </h5>
                        <div id="grafikPemijahanUPR" style="width: 100%; height: 300px;"></div>
                    </div>
                </div>
            </div>

            <div class="col-xl-6 col-md-6 mb-6">
                <div class="card shadow h-100 py-2">
                    <div class="card-body">
                        <h5 class="text-center font-weight-bold"> Total Benih UPR</h5>
                        <div id="grafikBenihUPR" style="width: 100%; height: 300px;"></div>
                    </div>
                </div>
            </div>

        </div>
    </div>

<?php endif; ?>

<script>
    function updategrafikPemijahanUPR(year) {
        fetch(`<?= base_url('Pemijahan/grafik_pemijahan_per_upr/') ?>${year}`)
            .then(response => response.json())
            .then(data => {
                let chartDom = document.getElementById("grafikPemijahanUPR");
                let myChart = echarts.init(chartDom);

                if (data.length === 0) {
                    // Tampilkan pesan jika data kosong
                    let option = {
                        title: {
                            text: 'Tidak ada data untuk tahun ini',
                            left: 'center',
                            top: 'center',
                            textStyle: {
                                fontSize: 16,
                                fontWeight: 'bold'
                            }
                        },
                        xAxis: {
                            show: false
                        },
                        yAxis: {
                            show: false
                        },
                        series: []
                    };
                    myChart.setOption(option);
                    return;
                }

                // Kelompokkan data berdasarkan UPR
                let uprMap = {};
                data.forEach(item => {
                    if (!uprMap[item.nama_upr]) {
                        uprMap[item.nama_upr] = [];
                    }
                    uprMap[item.nama_upr].push({
                        bulan: item.bulan,
                        total_pemijahan: item.total_pemijahan
                    });
                });

                // Buat labels bulan (Januari - Desember)
                let bulanLabels = Array.from({
                    length: 12
                }, (_, i) => getMonthName(i + 1));

                // Buat series untuk setiap UPR
                let seriesData = Object.keys(uprMap).map(upr => {
                    let dataPerBulan = Array(12).fill(0); // Inisialisasi array untuk 12 bulan
                    uprMap[upr].forEach(item => {
                        dataPerBulan[item.bulan - 1] = item.total_pemijahan; // Isi data sesuai bulan
                    });

                    return {
                        name: upr,
                        type: 'bar',
                        data: dataPerBulan,
                        barWidth: '60%',
                        itemStyle: {
                            color: getRandomColor() // Warna acak untuk setiap UPR
                        },
                        label: {
                            show: true,
                            position: 'top'
                        }
                    };
                });

                let option = {
                    tooltip: {
                        trigger: "axis",
                        axisPointer: {
                            type: "shadow"
                        }
                    },
                    legend: {
                        bottom: 0,
                        data: Object.keys(uprMap) // Menampilkan legenda berdasarkan UPR
                    },
                    xAxis: {
                        type: "category",
                        data: bulanLabels,
                        axisLabel: {
                            fontSize: 12,
                            rotate: 25
                        }
                    },
                    yAxis: {
                        type: "value",
                        name: "Total Pemijahan",
                        minInterval: 1
                    },
                    series: seriesData
                };

                myChart.setOption(option);
            })
            .catch(error => console.error("Error fetching data:", error));
    }

    function updategrafikBenihUPR(year) {
        fetch(`<?= base_url('hasilpmj/grafik_benih_per_upr/') ?>${year}`)
            .then(response => response.json())
            .then(data => {
                let chartDom = document.getElementById("grafikBenihUPR");
                let myChart = echarts.init(chartDom);

                if (data.length === 0) {
                    // Tampilkan pesan jika data kosong
                    let option = {
                        title: {
                            text: 'Tidak ada data untuk tahun ini',
                            left: 'center',
                            top: 'center',
                            textStyle: {
                                fontSize: 16,
                                fontWeight: 'bold'
                            }
                        },
                        xAxis: {
                            show: false
                        },
                        yAxis: {
                            show: false
                        },
                        series: []
                    };
                    myChart.setOption(option);
                    return;
                }

                // Kelompokkan data berdasarkan UPR
                let uprMap = {};
                data.forEach(item => {
                    if (!uprMap[item.nama_upr]) {
                        uprMap[item.nama_upr] = [];
                    }
                    uprMap[item.nama_upr].push({
                        bulan: item.bulan,
                        total_benih: item.total_benih
                    });
                });

                // Buat labels bulan (Januari - Desember)
                let bulanLabels = Array.from({
                    length: 12
                }, (_, i) => getMonthName(i + 1));

                // Buat series untuk setiap UPR
                let seriesData = Object.keys(uprMap).map(upr => {
                    let dataPerBulan = Array(12).fill(0); // Inisialisasi array untuk 12 bulan
                    uprMap[upr].forEach(item => {
                        dataPerBulan[item.bulan - 1] = item.total_benih; // Isi data sesuai bulan
                    });

                    return {
                        name: upr,
                        type: 'bar',
                        data: dataPerBulan,
                        barWidth: '60%',
                        itemStyle: {
                            color: getRandomColor() // Warna acak untuk setiap UPR
                        },
                        label: {
                            show: true,
                            position: 'top'
                        }
                    };
                });

                let option = {
                    tooltip: {
                        trigger: "axis",
                        axisPointer: {
                            type: "shadow"
                        }
                    },
                    legend: {
                        bottom: 0,
                        data: Object.keys(uprMap) // Menampilkan legenda berdasarkan UPR
                    },
                    xAxis: {
                        type: "category",
                        data: bulanLabels,
                        axisLabel: {
                            fontSize: 12,
                            rotate: 25
                        }
                    },
                    yAxis: {
                        type: "value",
                        name: "Total Benih",
                        minInterval: 1
                    },
                    series: seriesData
                };

                myChart.setOption(option);
            })
            .catch(error => console.error("Error fetching data:", error));
    }

    function updategrafikKolamUPR(year) {
        fetch(`<?= base_url('kolam/grafik_kolam_per_upr/') ?>${year}`)
            .then(response => response.json())
            .then(data => {
                let chartDom = document.getElementById("grafikKolamUPR");
                let myChart = echarts.init(chartDom);

                if (!Array.isArray(data) || data.length === 0) {
                    // Tampilkan pesan jika data kosong
                    let option = {
                        title: {
                            text: 'Tidak ada data untuk tahun ini',
                            left: 'center',
                            top: 'center',
                            textStyle: {
                                fontSize: 16,
                                fontWeight: 'bold'
                            }
                        },
                        xAxis: {
                            show: false
                        },
                        yAxis: {
                            show: false
                        },
                        series: []
                    };
                    myChart.setOption(option);
                    return;
                }

                let labels = [];
                let jumlahKolam = [];
                let luasKolam = [];

                data.forEach(item => {
                    labels.push(item.nama_upr);
                    jumlahKolam.push(item.total_kolam);
                    luasKolam.push(item.total_luas);
                });

                let option = {
                    tooltip: {
                        trigger: "axis",
                        axisPointer: {
                            type: "shadow"
                        }
                    },
                    legend: {
                        top: "10%",
                        left: "center",
                        data: ["Jumlah Kolam", "Luas Kolam (m²)"]
                    },
                    xAxis: {
                        type: "category",
                        data: labels,
                        axisLabel: {
                            rotate: 30
                        }
                    },
                    yAxis: {
                        type: "value",
                        name: "Jumlah / Luas (m²)",
                        min: 0
                    },
                    series: [{
                            name: "Jumlah Kolam",
                            type: "bar",
                            data: jumlahKolam,
                            stack: "total",
                            barWidth: "30%",
                            itemStyle: {
                                color: "#3FCB36"
                            }
                        },
                        {
                            name: "Luas Kolam (m²)",
                            type: "bar",
                            data: luasKolam,
                            stack: "total",
                            barWidth: "30%",
                            itemStyle: {
                                color: "#FF9538"
                            }
                        }
                    ]
                };

                myChart.setOption(option);
            })
            .catch(error => console.error("Error fetching data:", error));
    }

    // Fungsi untuk mendapatkan nama bulan
    function getMonthName(monthNumber) {
        const months = ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Agu", "Sep", "Okt", "Nov", "Des"];
        return months[monthNumber - 1];
    };
</script>

<!-- Grafik Pemijahan UPR -->
<script src="https://cdn.jsdelivr.net/npm/echarts/dist/echarts.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        let chartDom = document.getElementById("grafikPemijahanUPR");
        let myChart = echarts.init(chartDom);

        fetch("<?= base_url('Pemijahan/grafik_pemijahan_per_upr') ?>")
            .then(response => response.json())
            .then(data => {
                let labels = [];
                let values = [];

                data.forEach(item => {
                    labels.push(item.nama_upr); // Nama UPR untuk sumbu X
                    values.push(item.total_pemijahan); // Total pemijahan untuk sumbu Y
                });

                let option = {
                    tooltip: {
                        trigger: "axis",
                        axisPointer: {
                            type: "shadow"
                        }
                    },
                    xAxis: {
                        type: "category",
                        data: labels,
                        axisLabel: {
                            fontSize: 12,
                            rotate: 25 // Rotasi teks agar tidak bertabrakan
                        }
                    },
                    yAxis: {
                        type: "value",
                        name: "Total Pemijahan",
                        minInterval: 1
                    },
                    series: [{
                        name: "Total Pemijahan",
                        type: "bar",
                        data: values,
                        barWidth: "60%",
                        itemStyle: {
                            color: "#673ab7"
                        },
                        label: {
                            show: true,
                            position: "top"
                        }
                    }]
                };

                myChart.setOption(option);
            })
            .catch(error => console.error("Error fetching data:", error));
    });
</script>

<!-- grafik total benih upr -->
<script src="https://cdn.jsdelivr.net/npm/echarts/dist/echarts.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        let chartDom = document.getElementById("grafikBenihUPR");
        let myChart = echarts.init(chartDom);

        fetch("<?= base_url('hasilpmj/grafik_benih_per_upr') ?>")
            .then(response => response.json())
            .then(data => {
                let seriesData = data.map(item => ({
                    name: item.nama_upr,
                    value: item.total_benih
                }));

                let option = {
                    tooltip: {
                        trigger: "item",
                        formatter: "{b}: {c} ({d}%)" // Menampilkan nama UPR, total benih, dan persentase di tooltip
                    },
                    legend: {
                        orient: "vertical",
                        left: "left"
                    },
                    series: [{
                        name: "Total Benih",
                        type: "pie",
                        radius: "55%", // Lingkaran penuh (bukan donut)
                        center: ["50%", "55%"],
                        data: seriesData,
                        label: {
                            show: true,
                            position: "inside", // Persentase di dalam lingkaran
                            formatter: "{d}%", // Menampilkan hanya persentase
                            fontSize: 14,
                            color: "#fff", // Warna teks agar kontras dengan background
                            fontWeight: "bold"
                        },
                        labelLine: {
                            show: true, // Garis tetap aktif untuk nama UPR di luar
                            length: 20,
                            length2: 10
                        },
                        emphasis: {
                            itemStyle: {
                                shadowBlur: 10,
                                shadowOffsetX: 0,
                                shadowColor: "rgba(0, 0, 0, 0.5)"
                            }
                        }
                    }]
                };

                myChart.setOption(option);
            })
            .catch(error => console.error("Error fetching data:", error));
    });
</script>

<!-- grafik induk upr -->
<script src="https://cdn.jsdelivr.net/npm/echarts/dist/echarts.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        let chartDom = document.getElementById("grafikInduk");
        let myChart = echarts.init(chartDom);

        fetch("<?= base_url('induk/grafik_induk_per_upr') ?>")
            .then(response => response.json())
            .then(data => {
                if (!Array.isArray(data) || data.length === 0) {
                    console.error("Data kosong atau error");
                    return;
                }

                let labels = []; // Nama UPR
                let datasetMap = {}; // Menyimpan data per jenis ikan + kelamin

                // Mengelompokkan data
                data.forEach(item => {
                    let upr = item.nama_upr;
                    let ikan = `(${item.jenis_kelamin})`;
                    let jumlah = parseInt(item.total_induk) || 0;

                    if (!labels.includes(upr)) labels.push(upr);
                    if (!datasetMap[ikan]) datasetMap[ikan] = [];

                    datasetMap[ikan].push({
                        upr,
                        jumlah
                    });
                });

                // Warna untuk setiap jenis ikan
                let colors = [
                    "#FF6384", "#36A2EB", "#FFCE56", "#4BC0C0", "#9966FF", "#FF9F40"
                ];

                // Membentuk dataset untuk grafik
                let seriesData = Object.keys(datasetMap).map((ikan, index) => {
                    return {
                        name: ikan,
                        type: "bar",
                        data: labels.map(upr => {
                            let found = datasetMap[ikan].find(d => d.upr === upr);
                            return found ? found.jumlah : 0;
                        }),
                        itemStyle: {
                            color: colors[index % colors.length] // Mengatur warna berdasarkan index
                        },
                        barWidth: "30%" // Menyesuaikan lebar bar
                    };
                });

                // Konfigurasi grafik ECharts
                let option = {
                    tooltip: {
                        trigger: "axis",
                        axisPointer: {
                            type: "shadow"
                        }
                    },
                    legend: {
                        top: "10%",
                        left: "center",
                        data: Object.keys(datasetMap) // Menampilkan legenda berdasarkan jenis ikan
                    },
                    xAxis: {
                        type: "category",
                        data: labels,
                        axisLabel: {
                            rotate: 30 // Memiringkan label sumbu X agar tidak tumpang tindih
                        }
                    },
                    yAxis: {
                        type: "value",
                        name: "Jumlah Induk",
                        min: 0
                    },
                    series: seriesData.map(s => ({
                        ...s,
                        stack: "total"
                    }))
                };

                // Menampilkan grafik
                myChart.setOption(option);
            })
            .catch(error => console.error("Error fetching data:", error));
    });
</script>

<!-- grafik kolam upr -->
<script src="https://cdn.jsdelivr.net/npm/echarts/dist/echarts.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        let chartDom = document.getElementById("grafikKolamUPR");
        let myChart = echarts.init(chartDom);

        fetch("<?= base_url('kolam/grafik_kolam_per_upr') ?>")
            .then(response => response.json())
            .then(data => {
                let labels = [];
                let jumlahKolam = [];
                let luasKolam = [];

                data.forEach(item => {
                    labels.push(item.nama_upr);
                    jumlahKolam.push(item.total_kolam);
                    luasKolam.push(item.total_luas);
                });

                let option = {
                    tooltip: {
                        trigger: "axis",
                        axisPointer: {
                            type: "shadow"
                        }
                    },
                    legend: {
                        top: "10%",
                        left: "center",
                        data: ["Jumlah Kolam", "Luas Kolam (m²)"]
                    },
                    xAxis: {
                        type: "category",
                        data: labels,
                        axisLabel: {
                            rotate: 30 // Memiringkan label sumbu X jika panjang
                        }
                    },
                    yAxis: {
                        type: "value",
                        name: "Jumlah / Luas (m²)",
                        min: 0
                    },
                    series: [{
                            name: "Jumlah Kolam",
                            type: "bar",
                            data: jumlahKolam,
                            stack: "total",
                            barWidth: "30%",
                            itemStyle: {
                                color: "#3FCB36"
                            }
                        },
                        {
                            name: "Luas Kolam (m²)",
                            type: "bar",
                            data: luasKolam,
                            stack: "total",
                            barWidth: "30%",
                            itemStyle: {
                                color: "#FF9538"
                            }
                        }
                    ]
                };

                myChart.setOption(option);
            })
            .catch(error => console.error("Error fetching data:", error));
    });
</script>

<?php if ($this->session->userdata('id_user_level') == '3'): ?>
    <div class="mb-4">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h5 mb-0 text-gray-800"><i class="fas fa-fw fa-home"></i> Dashboard</h1>
            <div class="dropdown-container">
                <select id="tahunSelect" class="form-control form-control-sm">
                    <!-- Opsi tahun akan diisi secara dinamis oleh JavaScript -->
                </select>
            </div>
        </div>

        <!-- Content Row -->
        <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            Selamat datang <span class="text-uppercase"><b><?= $this->session->username; ?>!</b></span> Anda bisa mengoperasikan sistem dengan wewenang tertentu melalui pilihan menu di samping.
        </div>
        <div class="row">

            <div class="col-12 mb-4">
                <div class="alert alert-info mb-0">
                    <i class="fas fa-info-circle"></i> <strong>Langkah Penggunaan Aplikasi</strong>
                    <ol class="mt-2 mb-0">
                        <li><strong>Isi Data Induk Ikan</strong> terlebih dahulu jika data Induk belum tersedia di sistem.</li>
                        <li><strong>Isi Data Kolam</strong> terlebih dahulu jika data kolam belum tersedia di sistem.</li>
                        <li><strong>Isi Data Pemijahan</strong> sebelum melakukan proses pemijahan.</li>
                        <li><strong>Tekan Menu SPK</strong> untuk memulai proses seleksi induk.</li>
                        <li><strong>Buka dan Baca Kriteria dan Sub Kriteria</strong> yang ada pada menu <em>Kriteria</em> dan <em>Sub Kriteria</em> di dalam menu SPK agar memahami dasar penilaian.</li>
                        <li><strong>Lanjutkan Proses SPK</strong> secara berurutan, mulai dari pengisian <em>Alternatif</em> hingga menghasilkan nilai akhir (ranking).</li>
                        <li><strong>Isi Data Hasil Pemijahan</strong> setelah proses pemijahan selesai dilakukan.</li>
                        <li><strong>Data Stok Benih</strong> akan tertambah secara otomatis setelah selesai pengisian <em>Data Hasil Pemijahan</em></li>
                    </ol>
                </div>
            </div>


            <div class="col-xl-4 col-md-4 mb-4">
                <div class="card modern-card shadow">
                    <div class="card-header bg-primary">Jumlah Kriteria & Sub Kriteria</div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-6">
                                <h6>Kriteria</h6>
                                <h5><?= isset($total_kriteria) ? $total_kriteria : 'Data tidak tersedia'; ?></h5>
                            </div>
                            <div class="col-6">
                                <h6>Sub Kriteria</h6>
                                <h5><?= isset($total_subkriteria) ? $total_subkriteria : 'Data tidak tersedia'; ?></h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-2 col-md-2 mb-2">
                <div class="card modern-card shadow">
                    <div class="card-header bg-success">Jumlah Kolam</div>
                    <div class="card-body">
                        <h5><?= isset($total_kolam) ? $total_kolam : 'Data tidak tersedia'; ?></h5>
                    </div>
                </div>
            </div>

            <div class="col-xl-2 col-md-2 mb-2">
                <div class="card modern-card shadow">
                    <div class="card-header bg-warning">Total Luas Kolam</div>
                    <div class="card-body">
                        <h5><?= isset($total_luas_kolam) ? number_format($total_luas_kolam, 2) . " m²" : 'Data tidak tersedia'; ?></h5>
                    </div>
                </div>
            </div>
            <?php
            // Hitung total
            $total_betina = 0;
            $total_jantan = 0;

            foreach ($induk_per_ikan as $induk) {
                if (!isset($induk->jenis_kelamin, $induk->total)) continue;

                $jenis = strtolower(trim($induk->jenis_kelamin));

                // Handle kemungkinan typo 'befina'
                if ($jenis === 'betina' || $jenis === 'befina') {
                    $total_betina += (int)$induk->total;
                } elseif ($jenis === 'jantan') {
                    $total_jantan += (int)$induk->total;
                }
            }
            ?>

            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card shadow h-100">
                    <div class="card-header bg-secondary text-white text-center font-weight-bold">
                        Jumlah Induk Ikan
                    </div>
                    <div class="card-body d-flex flex-column justify-content-center align-items-center">
                        <div class="row w-100">
                            <div class="col-6 text-center">
                                <h6 class="text-danger font-weight-bold">Induk Betina</h6>
                                <div style="font-size: 1.5rem;"><?= $total_betina ?></div>
                            </div>
                            <div class="col-6 text-center">
                                <h6 class="text-primary font-weight-bold">Induk Jantan</h6>
                                <div style="font-size: 1.5rem;"><?= $total_jantan ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="col-xl-4 col-md-4 mb-4">
                <div class="card shadow h-100 py-2">
                    <div class="card-body">
                        <h5 class="text-center font-weight-bold">Total Pemijahan per Bulan</h5>
                        <div id="grafikPemijahan" style="width: 100%; height: 300px;"></div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-md-4 mb-4">
                <div class="card shadow h-100 py-2">
                    <div class="card-body">
                        <h5 class="text-center font-weight-bold">Status Pemijahan</h5>
                        <div id="grafikStatusPemijahan" style="width: 100%; height: 300px;"></div>
                    </div>
                </div>
            </div>


            <div class="col-xl-4 col-md-4 mb-4">
                <div class="card shadow h-100 py-2">
                    <div class="card-body">
                        <h5 class="text-center font-weight-bold">Grafik Hasil Perhitungan</h5>
                        <div id="chartNilaiTertinggi" style="width: 100%; height: 300px;"></div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-md-4 mb-4">
                <div class="card shadow h-100 py-2">
                    <div class="card-body">
                        <h5 class="text-center font-weight-bold">Total Benih per Bulan</h5>
                        <div id="grafikBenih" style="width: 100%; height: 300px;"></div>
                    </div>
                </div>
            </div>

            <div class="col-xl-8 col-md-8 mb-4">
                <div class="card shadow h-100 py-2">
                    <div class="card-body">
                        <h5 class="text-center font-weight-bold">Grafik Hasil Pemijahan</h5>
                        <div id="grafikHasilPemijahan" style="width: 100%; height: 300px;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>


<script>
    document.addEventListener("DOMContentLoaded", function() {
        const tahunSelect = document.getElementById('tahunSelect');

        // Fungsi untuk mengisi dropdown tahun
        function populateYearDropdown() {
            const currentYear = new Date().getFullYear(); // Tahun saat ini
            const startYear = currentYear - 5; // Mulai dari 5 tahun yang lalu

            // Kosongkan dropdown terlebih dahulu
            tahunSelect.innerHTML = '<option value="">Pilih Tahun</option>';

            // Tambahkan opsi tahun dari startYear hingga currentYear
            for (let year = currentYear; year >= startYear; year--) {
                const option = document.createElement('option');
                option.value = year;
                option.textContent = year;
                tahunSelect.appendChild(option);
            }

            // Set tahun saat ini sebagai nilai default
            tahunSelect.value = currentYear;

            // Panggil fungsi updateCharts untuk menampilkan data tahun saat ini
            updateCharts(currentYear);
        }

        // Panggil fungsi untuk mengisi dropdown tahun
        populateYearDropdown();

        // Event listener untuk filter grafik berdasarkan tahun
        tahunSelect.addEventListener('change', function() {
            const selectedYear = this.value;
            if (selectedYear) {
                updateCharts(selectedYear); // Panggil fungsi updateCharts dengan tahun yang dipilih
            } else {
                console.error("Tahun tidak valid.");
            }
        });

        // Fungsi untuk memperbarui semua grafik berdasarkan tahun
        function updateCharts(year) {
            updateGrafikPemijahan(year);
            updatestatusPemijahanChart(year);
            updatechartNilaiTertinggi(year);
            updategrafikBenih(year);
            updateGrafikHasilPemijahan(year);
        }

        // Fungsi untuk memperbarui grafik pemijahan
        function updateGrafikPemijahan(year) {
            fetch(`<?= base_url('Pemijahan/getGrafikPemijahan/') ?>${year}`)
                .then(response => response.json())
                .then(data => {
                    console.log("Data Grafik Pemijahan:", data); // Debug: Lihat data yang diterima
                    let chartDom = document.getElementById('grafikPemijahan');
                    let myChart = echarts.init(chartDom);

                    let labels = [];
                    let values = [];

                    data.forEach(item => {
                        labels.push(getMonthName(item.bulan));
                        values.push(item.total);
                    });

                    let option = {
                        tooltip: {
                            trigger: "axis",
                            axisPointer: {
                                type: "shadow"
                            }
                        },
                        xAxis: {
                            type: "category",
                            data: labels,
                            axisLabel: {
                                fontSize: 12
                            }
                        },
                        yAxis: {
                            type: "value",
                            name: "Jumlah",
                            axisLabel: {
                                fontSize: 12
                            },
                            minInterval: 1
                        },
                        series: [{
                            name: "Total Pemijahan",
                            type: "bar",
                            data: values,
                            barWidth: "50%",
                            itemStyle: {
                                color: "#00FF7F",
                                borderRadius: [4, 4, 0, 0]
                            },
                            label: {
                                show: true,
                                position: "top",
                                fontSize: 12
                            }
                        }]
                    };

                    myChart.setOption(option);
                })
                .catch(error => {
                    console.error('Error fetching data:', error);
                });
        }


        function updatestatusPemijahanChart(year) {
            fetch(`<?= base_url('Pemijahan/grafik_status_pemijahan/') ?>${year}`)
                .then(response => response.json())
                .then(data => {
                    console.log("Data Grafik Status Pemijahan:", data); // Debug: Lihat data yang diterima

                    let chartDom = document.getElementById('grafikStatusPemijahan'); // Pastikan ID ini sesuai
                    let myChart = echarts.init(chartDom);

                    let bulanSet = new Set();
                    let groupedData = {
                        "Belum Diproses": {},
                        "Diproses": {},
                        "Selesai Diproses": {}
                    };

                    // Memproses data dari backend
                    data.forEach(item => {
                        let bulan = item.bulan;
                        let status = item.status == 0 ? "Belum Diproses" :
                            item.status == 1 ? "Diproses" :
                            "Selesai Diproses";

                        bulanSet.add(bulan);
                        if (!groupedData[status][bulan]) groupedData[status][bulan] = 0;
                        groupedData[status][bulan] += parseInt(item.total);
                    });

                    let bulanArray = Array.from(bulanSet).sort();
                    let statusLabels = ["Belum Diproses", "Diproses", "Selesai Diproses"];
                    let statusColors = ["#FFA500", "#FF4500", "#EEE8AA"]; // Merah, Kuning, Hijau

                    let seriesData = statusLabels.map((status, index) => ({
                        name: status,
                        type: 'bar',
                        stack: 'Total',
                        data: bulanArray.map(bulan => groupedData[status][bulan] || 0),
                        itemStyle: {
                            color: statusColors[index]
                        }
                    }));

                    let option = {
                        tooltip: {
                            trigger: 'axis',
                            axisPointer: {
                                type: 'shadow'
                            }
                        },
                        legend: {
                            bottom: 0,
                            data: statusLabels
                        },
                        xAxis: {
                            type: 'category',
                            data: bulanArray
                        },
                        yAxis: {
                            type: 'value'
                        },
                        series: seriesData
                    };

                    myChart.setOption(option);
                })
                .catch(error => console.error('Error fetching data:', error));
        }


        function updatechartNilaiTertinggi(year) {
            fetch(`<?= base_url('perhitungan/grafik_nilai_per_upr/') ?>${year}`)
                .then(response => response.json())
                .then(data => {

                    let chartDom = document.getElementById("chartNilaiTertinggi");
                    let myChart = echarts.init(chartDom);

                    let waktu_pemijahan = [];
                    let nilai = [];
                    let alternatif = [];

                    // Ambil data dari JSON
                    data.forEach(row => {
                        waktu_pemijahan.push(row.waktu_pemijahan); // Gunakan waktu pemijahan
                        nilai.push(row.total_nilai);
                        alternatif.push(row.nama_alternatif || "Tidak Diketahui");
                    });

                    let option = {

                        tooltip: {
                            trigger: "axis",
                            formatter: function(params) {
                                let item = params[0];
                                return `Waktu Pemijahan: ${item.name}<br>Alternatif: ${alternatif[item.dataIndex]}<br>Nilai: ${item.value}`;
                            }
                        },
                        xAxis: {
                            type: "category",
                            data: waktu_pemijahan, // Pakai waktu_pemijahan sebagai label X
                            axisLabel: {
                                fontSize: 12,
                                rotate: 25
                            }
                        },
                        yAxis: {
                            type: "value",
                            name: "Nilai",
                            minInterval: 1
                        },
                        series: [{
                            name: "Nilai Tertinggi",
                            type: "line",
                            data: nilai,
                            smooth: true,
                            lineStyle: {
                                color: "#0000CD",
                                width: 2
                            },
                            itemStyle: {
                                color: "#4169E1"
                            },
                            areaStyle: {
                                color: "rgba(0, 191, 255)"
                            },
                            label: {
                                show: true,
                                position: "top"
                            }
                        }]
                    };

                    myChart.setOption(option);
                })
                .catch(error => console.error("Error fetching data:", error));
        }

        function updategrafikBenih(year) {
            fetch(`<?= base_url('Hasilpmj/grafik_benih/') ?>${year}`)
                .then(response => response.json())
                .then(data => {
                    let chartDom = document.getElementById('grafikBenih');
                    let myChart = echarts.init(chartDom);

                    let dataChart = data.map(item => ({
                        name: item.bulan,
                        value: parseInt(item.total_benih)
                    }));

                    let option = {
                        tooltip: {
                            trigger: 'item',
                            formatter: '{b}: {c} ({d}%)' // Menampilkan nama bulan, total benih, dan persentase
                        },
                        legend: {
                            orient: 'vertical',
                            left: 'left'
                        },
                        color: ['#DB7093', '#F0E68C', '#87CEFA', '#90EE90', '#FFA07A', '#9370DB'], // Warna bervariasi
                        series: [{
                            name: 'Total Benih',
                            type: 'pie',
                            radius: '55%', // Menggunakan lingkaran penuh
                            center: ['50%', '55%'], // Posisi lebih seimbang
                            data: dataChart,
                            label: {
                                show: true,
                                position: 'inside', // Menampilkan persentase di dalam lingkaran
                                formatter: '{d}%', // Hanya menampilkan persentase
                                fontSize: 14,
                                color: '#fff', // Warna putih agar kontras dengan background
                                fontWeight: 'bold'
                            },
                            labelLine: {
                                show: true, // Garis tetap ada untuk legend di luar lingkaran
                                length: 20,
                                length2: 10
                            },
                            emphasis: {
                                itemStyle: {
                                    shadowBlur: 10,
                                    shadowOffsetX: 0,
                                    shadowColor: 'rgba(0, 0, 0, 0.5)'
                                }
                            }
                        }]
                    };

                    myChart.setOption(option);
                })
                .catch(error => console.error('Error fetching data:', error));
        }

        function updateGrafikHasilPemijahan(year) {
            fetch(`<?= base_url('Hasilpmj/grafik_hasilpemijahan/') ?>${year}`)
                .then(response => response.json())
                .then(data => {
                    console.log("Data Grafik Hasil Pemijahan:", data); // Debug: Lihat data yang diterima

                    let chartDom = document.getElementById('grafikHasilPemijahan');
                    let myChart = echarts.init(chartDom);

                    let labels = [];
                    let seriesTelur = {};
                    let seriesNetas = {};
                    let methods = new Set();

                    // Proses data dari server
                    data.forEach(item => {
                        Object.keys(item.netas).forEach(waktu => {
                            if (!labels.includes(waktu)) labels.push(waktu);
                            if (!seriesNetas[item.metode]) seriesNetas[item.metode] = {};
                            if (!seriesTelur[item.metode]) seriesTelur[item.metode] = {};
                            seriesNetas[item.metode][waktu] = item.netas[waktu].netas || 0;
                            seriesTelur[item.metode][waktu] = item.telur[waktu].telur || 0;
                            methods.add(item.metode);
                        });
                    });

                    // Urutkan label waktu
                    labels.sort();

                    // Warna untuk chart
                    let colors = ["#6495ED", "#91CC75", "#EE6666", "#FAC858"];
                    let chartSeries = [];
                    let colorIndex = 0;

                    // Buat series untuk setiap metode
                    methods.forEach(method => {
                        chartSeries.push({
                            name: `Jumlah Telur - ${method}`,
                            type: 'bar',
                            data: labels.map(waktu => seriesTelur[method][waktu] || 0),
                            barGap: 0,
                            color: colors[colorIndex % colors.length]
                        });
                        chartSeries.push({
                            name: `Tingkat Netas - ${method}`,
                            type: 'line',
                            yAxisIndex: 1,
                            data: labels.map(waktu => seriesNetas[method][waktu] || 0),
                            color: colors[colorIndex % colors.length],
                            smooth: true
                        });
                        colorIndex++;
                    });

                    // Konfigurasi chart
                    let option = {
                        tooltip: {
                            trigger: 'axis',
                            axisPointer: {
                                type: 'cross'
                            }
                        },
                        legend: {
                            bottom: 0
                        },
                        grid: {
                            left: '10%',
                            right: '10%',
                            bottom: '15%',
                            containLabel: true
                        },
                        xAxis: {
                            type: 'category',
                            data: labels,
                            axisLabel: {
                                rotate: 30
                            }
                        },
                        yAxis: [{
                                type: 'value',
                                name: 'Jumlah Benih'
                            },
                            {
                                type: 'value',
                                name: 'Tingkat Netas (%)',
                                position: 'right'
                            }
                        ],
                        series: chartSeries
                    };

                    // Set opsi chart
                    myChart.setOption(option);
                })
                .catch(error => {
                    console.error('Error fetching data:', error);
                });
        }

        // Fungsi untuk mendapatkan nama bulan
        function getMonthName(monthNumber) {
            const months = ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Agu", "Sep", "Okt", "Nov", "Des"];
            return months[monthNumber - 1];
        }
    });
</script>

<?php $this->load->view('layouts/footer_admin'); ?>