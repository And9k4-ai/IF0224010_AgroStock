@extends('layouts.app')

@section('content')


<h3 class="mb-4">Dashboard AgroStock</h3>

<div class="row">
    <!-- Card Jumlah Barang -->
    <div class="col-md-3 mb-3">
        <div class="card text-bg-success">
            <div class="card-body">
                <h5>Jumlah Barang</h5>
                <h2>{{ $barang }}</h2>
            </div>
        </div>
    </div>

    <!-- Card Kategori -->
    <div class="col-md-3 mb-3">
        <div class="card text-bg-primary">
            <div class="card-body">
                <h5>Kategori</h5>
                <h2>{{ $kategori }}</h2>
            </div>
        </div>
    </div>

    <!-- Card Total Barang Masuk -->
    <div class="col-md-3 mb-3">
        <div class="card text-bg-warning">
            <div class="card-body">
                <h5>Total Barang Masuk</h5>
                <h2>{{ $masuk }}</h2>
            </div>
        </div>
    </div>

    <!-- Card Total Barang Keluar -->
    <div class="col-md-3 mb-3">
        <div class="card text-bg-danger">
            <div class="card-body">
                <h5>Total Barang Keluar</h5>
                <h2>{{ $keluar }}</h2>
            </div>
        </div>
    </div>
</div>

<!-- ==========================================
     CARD INTEGRASI API CUACA + BIGDATACLOUD (NO-KEY)
     ========================================== -->
<div class="row mt-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-success text-white d-flex justify-content-between align-items-center fw-bold">
                
                <span id="weatherTitle">🌤 Cuaca Hari Ini (Mencari Lokasi...)</span>
                <small id="currentCoords" class="badge bg-light text-success">Mencari koordinat...</small>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-3 border-end mb-2 mb-md-0">
                        <h6 class="text-muted">🌡 Suhu</h6>
                        <h3 id="temperature" class="mt-2">-</h3>
                    </div>
                    <div class="col-md-3 border-end mb-2 mb-md-0">
                        <h6 class="text-muted">💨 Kecepatan Angin</h6>
                        <h3 id="windspeed" class="mt-2">-</h3>
                    </div>
                    <div class="col-md-3 border-end mb-2 mb-md-0">
                        <h6 class="text-muted">🧭 Arah Angin</h6>
                        <h3 id="winddirection" class="mt-2">-</h3>
                    </div>
                    <div class="col-md-3">
                        <h6 class="text-muted">☀️ Kondisi</h6>
                        <h3 id="weathercode" class="mt-2">-</h3>
                    </div>
                </div>
                
                <!-- Waktu Update Otomatis -->
                <div class="text-end border-top mt-3 pt-2">
                    <p class="text-muted small mb-0">
                        🔄 Terakhir diperbarui: <span id="updateTime" class="fw-bold">-</span>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // ==========================================
    // INITIALIZATION & DYNAMIC GEOLOCATION CHECK
    // ==========================================
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            function(position) {
                let lat = position.coords.latitude;
                let lon = position.coords.longitude;
                loadWeatherAndLocation(lat, lon);
            },
            function() {
                console.warn("Akses lokasi ditolak browser. Menggunakan koordinat cadangan Sukoharjo/Karanganyar.");
                loadWeatherAndLocation(-7.6833, 110.8333);
            }
        );
    } else {
        loadWeatherAndLocation(-7.6833, 110.8333);
    }

    // ==========================================
    // WEATHER CODE TRANSLATOR 
    // ==========================================
    function weatherText(code){
        switch(code){
            case 0: return '☀ Cerah';
            case 1:
            case 2: return '🌤 Berawan';
            case 3: return '☁ Mendung';
            case 45:
            case 48: return '🌫 Berkabut';
            case 51:
            case 53:
            case 55: return '🌦 Gerimis';
            case 61:
            case 63:
            case 65: return '🌧 Hujan';
            case 80:
            case 81:
            case 82: return '🌦 Hujan Ringan';
            case 95: return '⛈ Hujan Badai';
            default: return 'Cuaca Tidak Diketahui';
        }
    }

    // ==========================================
    // FETCH WEATHER DATA & REVERSE GEOCODING API
    // ==========================================
    function loadWeatherAndLocation(latitude, longitude){
        
        document.getElementById('currentCoords').textContent = 
            `📍 Lat: ${latitude.toFixed(4)}, Lon: ${longitude.toFixed(4)}`;

        // 1. REVERSE GEOCODING:
        fetch(`https://api.bigdatacloud.net/data/reverse-geocode-client?latitude=${latitude}&longitude=${longitude}&localityLanguage=id`)
        .then(res => res.json())
        .then(geoData => {
            
            let namaLokasi = geoData.city || 
                             geoData.locality || 
                             geoData.principalSubdivision || 
                             geoData.localityInfo?.administrative?.[0]?.name || 
                             "Lokasi Tidak Diketahui";
            
            
            namaLokasi = namaLokasi.replace("Kabupaten ", "").replace("Kota ", "");
            
            document.getElementById('weatherTitle').textContent = `🌤 Cuaca Hari Ini di ${namaLokasi}`;
        })
        .catch(err => {
            console.error('Gagal memproses lokasi BigDataCloud:', err);
            document.getElementById('weatherTitle').textContent = `🌤 Cuaca Hari Ini`;
        });

        // 2. LIVE WEATHER FORECAST (OPEN-METEO API)
        fetch(`https://api.open-meteo.com/v1/forecast?latitude=${latitude}&longitude=${longitude}&current_weather=true`)
        .then(res => res.json())
        .then(data => {
            document.getElementById('temperature').textContent = data.current_weather.temperature + ' °C';
            document.getElementById('windspeed').textContent = data.current_weather.windspeed + ' km/h';
            document.getElementById('winddirection').textContent = data.current_weather.winddirection + '°';
            document.getElementById('weathercode').textContent = weatherText(data.current_weather.weathercode);

            const options = { year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit' };
            document.getElementById('updateTime').textContent = new Date().toLocaleString('id-ID', options) + ' WIB';
        })
        .catch(error => {
            console.error('Gagal mengambil data cuaca Open-Meteo:', error);
        });
    }
</script>
@endpush