<x-app-layout>
    {{-- CONTENT --}}
    <div class="row">
        <div class="col-lg-12 col-12">
            <h3>Halo, {{ auth()->user()->name }}!</h3>
            <div class="row">
                <div class="col-lg-3 col-6">
                    <div class="card">
                        <span class="mask bg-primary opacity-10 border-radius-lg"></span>
                        <div class="card-body p-3 position-relative">
                            <div class="row">
                                <div class="col-8 text-start">
                                    <div class="icon icon-shape bg-white shadow text-center border-radius-2xl">
                                        <i class="bi bi-person-circle text-dark text-gradient text-lg opacity-10"
                                            aria-hidden="true"></i>
                                    </div>
                                    <h5 class="text-white font-weight-bolder mb-0 mt-3">
                                        {{ $jumlahPasien }}
                                    </h5>
                                    <span class="text-white text-sm">Semua Pasien</span>
                                </div>
                                <div class="col-4">
                                    <div class="dropdown text-end mb-6">
                                        <a href="javascript:;" class="cursor-pointer" id="dropdownUsers1"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fa fa-ellipsis-h text-white"></i>
                                        </a>
                                        <ul class="dropdown-menu px-2 py-3" aria-labelledby="dropdownUsers1">
                                            <li><a class="dropdown-item border-radius-md"
                                                    href="{{ route('kelola-users.index') }}">Lihat Data</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="card">
                        <span class="mask bg-dark opacity-10 border-radius-lg"></span>
                        <div class="card-body p-3 position-relative">
                            <div class="row">
                                <div class="col-8 text-start">
                                    <div class="icon icon-shape bg-white shadow text-center border-radius-2xl">
                                        <i class="bi bi-person-check text-dark text-gradient text-lg opacity-10"
                                            aria-hidden="true"></i>
                                    </div>
                                    <h5 class="text-white font-weight-bolder mb-0 mt-3">
                                        {{ $jumlahDokter }}
                                    </h5>
                                    <span class="text-white text-sm">Dokter</span>
                                </div>
                                <div class="col-4">
                                    <div class="dropstart text-end mb-6">
                                        <a href="javascript:;" class="cursor-pointer" id="dropdownUsers2"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fa fa-ellipsis-h text-white"></i>
                                        </a>
                                        <ul class="dropdown-menu px-2 py-3" aria-labelledby="dropdownUsers2">
                                            <li><a class="dropdown-item border-radius-md"
                                                    href="{{ route('kelola-dokter.index') }}">Lihat Data</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="card">
                        <span class="mask bg-primary opacity-10 border-radius-lg"></span>
                        <div class="card-body p-3 position-relative">
                            <div class="row">
                                <div class="col-8 text-start">
                                    <div class="icon icon-shape bg-white shadow text-center border-radius-2xl">
                                        <i class="bi bi-person-circle text-dark text-gradient text-lg opacity-10"
                                            aria-hidden="true"></i>
                                    </div>
                                    <h5 class="text-white font-weight-bolder mb-0 mt-3">
                                        {{ $jumlahObat }}
                                    </h5>
                                    <span class="text-white text-sm">Obat</span>
                                </div>
                                <div class="col-4">
                                    <div class="dropdown text-end mb-6">
                                        <a href="javascript:;" class="cursor-pointer" id="dropdownUsers3"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fa fa-ellipsis-h text-white"></i>
                                        </a>
                                        <ul class="dropdown-menu px-2 py-3" aria-labelledby="dropdownUsers3">
                                            <li><a class="dropdown-item border-radius-md"
                                                    href="{{ route('kelola-obat.index') }}">Lihat Data</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="card">
                        <span class="mask bg-dark opacity-10 border-radius-lg"></span>
                        <div class="card-body p-3 position-relative">
                            <div class="row">
                                <div class="col-8 text-start">
                                    <div class="icon icon-shape bg-white shadow text-center border-radius-2xl">
                                        <i class="bi bi-heart-pulse text-dark text-gradient text-lg opacity-10"
                                            aria-hidden="true"></i>
                                    </div>
                                    <h5 class="text-white font-weight-bolder mb-0 mt-3">
                                        {{ $jumlahDiagnosa }}
                                    </h5>
                                    <span class="text-white text-sm">Diagnosa</span>
                                </div>
                                <div class="col-4">
                                    <div class="dropstart text-end mb-6">
                                        <a href="javascript:;" class="cursor-pointer" id="dropdownUsers4"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fa fa-ellipsis-h text-white"></i>
                                        </a>
                                        <ul class="dropdown-menu px-2 py-3" aria-labelledby="dropdownUsers4">
                                            <li><a class="dropdown-item border-radius-md"
                                                    href="{{ route('kelola-diagnosa.index') }}">Lihat Data</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-lg-7 mb-lg-0 mb-4">
            <div class="card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="d-flex flex-column h-100">
                                <p class="mb-1 pt-2 text-bold">Ayo</p>
                                <h5 class="font-weight-bolder">Jaga HEALTH</h5>
                                <p class="mb-5">Kesehatan dimulai dari kebiasaan kecil. Istirahat cukup, konsumsi
                                    makanan sehat, dan tetap aktif setiap hari untuk hidup yang lebih berkualitas.</p>
                            </div>
                        </div>
                        <div class="col-lg-5 ms-auto text-center mt-5 mt-lg-0">
                            <div class="bg-primary border-radius-lg h-100">
                                <img src="{{ asset('') }}assets/img/shapes/waves-white.svg"
                                    class="position-absolute h-100 w-50 top-0 d-lg-block d-none" alt="waves">
                                <div class="position-relative d-flex align-items-center justify-content-center h-100">
                                    <img class="w-100 position-relative z-index-2 pt-4"
                                        src="{{ asset('') }}assets/img/illustrations/rocket-white.png"
                                        alt="rocket">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="card h-100 p-3">
                <div class="overflow-hidden position-relative border-radius-lg bg-cover h-100"
                    style="background-image: url('../assets/img/ivancik.jpg');">
                    <span class="mask bg-gradient-dark"></span>
                    <div class="card-body position-relative z-index-1 d-flex flex-column h-100 p-3">
                        <h5 class="text-white font-weight-bolder mb-4 pt-2">Jaga Kesehatan Yah</h5>
                        <p class="text-white">Kesehatan adalah aset paling berharga. Mulailah hari dengan pola hidup
                            sehat, makan bergizi, dan rutin berolahraga untuk masa depan yang lebih baik.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>