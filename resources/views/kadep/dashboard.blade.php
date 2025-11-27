@extends('layouts.main')

@section('title', 'Dashboard Kepala Departemen')

@section('content')
<div class="container-fluid">

    <!-- Statistik Ringkas -->
    <div class="row">
        <div class="col-md-3 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Lab Tersedia</h4>
                    <div class="text-center">
                        <p class="font-weight-bold" style="font-size: 2rem;">{{ count($labTersedia) }}</p>
                        <small>Laboratorium</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Lab Sering Dipinjam</h4>
                    @if(isset($labSeringDipinjam) && $labSeringDipinjam->laboratorium)
                    <p class="text-center font-weight-bold" style="font-size: 1rem;">{{ $labSeringDipinjam->laboratorium->nama }}</p>
                    <p class="text-center">({{ $labSeringDipinjam->total }} kali)</p>
                    @else
                    <p class="text-center">Tidak ada data</p>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-3 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Total Peminjaman</h4>
                    <div class="text-center">
                        <p class="font-weight-bold" style="font-size: 2rem;">{{ $totalPeminjaman }}</p>
                        <small>Kali</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Total Alat Rusak</h4>
                    <div class="text-center">
                        <p class="font-weight-bold" style="font-size: 2rem;">{{ $alatRusak }}</p>
                        <small>Unit</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Grafik -->
    <div class="row">
        <div class="col-md-6 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Grafik Penggunaan Lab</h4>
                    <div style="position: relative; height: 300px; width: 100%;">
                        <canvas id="labUsageChart" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Grafik Penggunaan Alat</h4>
                    <div style="position: relative; height: 300px; width: 100%;">
                        <canvas id="alatUsageChart" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Tren Peminjaman Bulanan</h4>
                    <div style="position: relative; height: 300px; width: 100%;">
                        <canvas id="monthlyTrendChart" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // To prevent re-initialization bugs, store chart instances globally.
    window.myCharts = window.myCharts || {};

    function renderCharts() {
        // Data from controller
        const labUsageData = @json($labUsage ?? []);
        const alatUsageData = @json($alatUsage ?? []);
        const monthlyTrendData = @json($trenPeminjaman ?? []);

        // --- 1. Lab Usage Chart (Bar) ---
        if (labUsageData && typeof labUsageData === 'object') {
            const labUsageCtx = document.getElementById('labUsageChart').getContext('2d');
            if (window.myCharts.labUsageChart) {
                window.myCharts.labUsageChart.destroy();
            }
            window.myCharts.labUsageChart = new Chart(labUsageCtx, {
                type: 'bar',
                data: {
                    labels: Object.keys(labUsageData),
                    datasets: [{
                        label: 'Jumlah Peminjaman',
                        data: Object.values(labUsageData),
                        backgroundColor: 'rgba(54, 162, 235, 0.5)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    }
                }
            });
        }

        // --- 2. Alat Usage Chart (Bar) ---
        if (alatUsageData && typeof alatUsageData === 'object') {
            const alatUsageCtx = document.getElementById('alatUsageChart').getContext('2d');
            if (window.myCharts.alatUsageChart) {
                window.myCharts.alatUsageChart.destroy();
            }
            window.myCharts.alatUsageChart = new Chart(alatUsageCtx, {
                type: 'bar',
                data: {
                    labels: Object.keys(alatUsageData),
                    datasets: [{
                        label: 'Jumlah Peminjaman',
                        data: Object.values(alatUsageData),
                        backgroundColor: 'rgba(255, 99, 132, 0.5)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    }
                }
            });
        }

        // --- 3. Monthly Trend Chart (Line) ---
        const monthlyTrendCtx = document.getElementById('monthlyTrendChart').getContext('2d');
        const trendLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        const trendData = Array(12).fill(0);
        if (monthlyTrendData && typeof monthlyTrendData === 'object') {
            for (const [bulan, total] of Object.entries(monthlyTrendData)) {
                if (bulan >= 1 && bulan <= 12) {
                    trendData[bulan - 1] = total;
                }
            }
        }

        if (window.myCharts.monthlyTrendChart) {
            window.myCharts.monthlyTrendChart.destroy();
        }
        window.myCharts.monthlyTrendChart = new Chart(monthlyTrendCtx, {
            type: 'line',
            data: {
                labels: trendLabels,
                datasets: [{
                    label: 'Total Peminjaman',
                    data: trendData,
                    fill: false,
                    borderColor: 'rgb(75, 192, 192)',
                    tension: 0.1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        renderCharts();
    });
</script>
@endpush