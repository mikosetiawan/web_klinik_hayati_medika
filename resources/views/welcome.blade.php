<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Klinik Hayati Medika</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Custom CSS -->
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .hero-section {
            background: url({{ asset('assets/img/bg-healt.jpg') }}) no-repeat center center;
            background-size: cover;
            color: white;
            padding: 100px 0;
            text-align: center;
        }
        .feature-card {
            transition: transform 0.3s;
        }
        .feature-card:hover {
            transform: translateY(-10px);
        }
        .footer {
            background-color: #f8f9fa;
            padding: 20px 0;
        }
        .c-title {
            color: #497aaa;
        }
        .btn-healt {
            background-color: #497aaa;
            color: white;
        }
        .btn-healt:hover {
            background-color: #638db7
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light sticky-top">
        <div class="container">
            <a class="navbar-brand" href="#">
                <img src="{{ asset('assets/img/logo_klinik.png') }}" style="width: 80px;" alt="">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="#home">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#services">Layanan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#about">Tentang Kami</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#contact">Kontak</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="home" class="hero-section">
        <div class="container">
            <h1 class="c-title">Selamat Datang di Klinik Hayati Medika</h1>
            <p class="c-title">Pelayanan kesehatan terbaik untuk Anda dan keluarga</p>
            <a href="{{ route('register') }}" class="btn btn-healt btn-lg mt-3">Daftar Sekarang!</a>
        </div>
    </section>

    <!-- Services Section -->
    <section id="services" class="py-5">
        <div class="container">
            <h2 class="text-center mb-5">Layanan Kami</h2>
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="card feature-card">
                        <div class="card-body text-center">
                            <h5 class="card-title">Pemeriksaan Umum</h5>
                            <p class="card-text">Pemeriksaan kesehatan menyeluruh untuk semua usia.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card feature-card">
                        <div class="card-body text-center">
                            <h5 class="card-title">Konsultasi Dokter</h5>
                            <p class="card-text">Konsultasi dengan dokter spesialis berpengalaman.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card feature-card">
                        <div class="card-body text-center">
                            <h5 class="card-title">Laboratorium</h5>
                            <p class="card-text">Fasilitas laboratorium modern untuk diagnosa akurat.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-5">Tentang Kami</h2>
            <div class="row">
                <div class="col-md-6">
                    <p>Klinik Hayati Medika adalah penyedia layanan kesehatan terpercaya yang berkomitmen untuk memberikan perawatan terbaik bagi pasien. Dengan tim dokter dan tenaga medis profesional, kami siap melayani kebutuhan kesehatan Anda.</p>
                </div>
                <div class="col-md-6">
                    <p>Fasilitas kami dilengkapi dengan teknologi medis terkini untuk memastikan diagnosa yang akurat dan perawatan yang efektif. Kami percaya bahwa kesehatan adalah investasi terbaik untuk masa depan Anda.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h4>Informasi Kontak</h4>
                    <p><strong>Alamat:</strong> Jl. Sehat No. 123, Jakarta, Indonesia</p>
                    <p><strong>Telepon:</strong> (021) 1234-5678</p>
                    <p><strong>Email:</strong> info@hayatimedika.com</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer text-center">
        <div class="container">
            <p>&copy; {{ date('Y') }} Klinik Hayati Medika. All Rights Reserved.</p>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>