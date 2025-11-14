@extends('layouts.site')
@section('content')

<div id="layanan-umum" class="page-section">
    <h2 class="page-title">Layanan Umum</h2>
    <div class="card services-box">
        <div class="service-item">
            <img src="{{ asset('asset/img/Hospital.jpg')}}" alt="Instalansi Rawat Inap" class="service-icon">
            <div class="service-title">Instalansi Rawat Inap</div>
        </div>
        <div class="service-item">
            <img src="{{ asset('asset/img/Farmasi.jpg')}}" alt="Farmasi" class="service-icon">
            <div class="service-title">Farmasi</div>
        </div>
        <div class="service-item">
            <img src="{{ asset('asset/img/Ambulance.jpg')}}" alt="Rawat Jalan" class="service-icon">
            <div class="service-title">Rawat Jalan</div>
        </div>
        <div class="service-item">
            <img src="{{ asset('asset/img/HospitalBed.jpg')}}" alt="Bedah" class="service-icon">
            <div class="service-title">Bedah</div>
        </div>
        <div class="service-item">
            <img src="{{ asset('asset/img/Kimia.jpg')}}" alt="Ultrasonografi" class="service-icon">
            <div class="service-title">Ultrasonografi</div>
        </div>
    </div>
</div>

<div class="location-container">
    <div class="card map-card">
        <iframe src="https://www.google.com/maps?q=Rumah+Sakit+Hewan+Pendidikan+Universitas+Airlangga+Kampus+Merr+C&output=embed" class="map-iframe" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
    </div>
    <div class="card address-card">
        <h3 class="address-card-title">Lokasi Rumah Sakit Hewan Pendidikan</h3>
        <div class="address-location">
            Universitas Airlangga Kampus Merr C
        </div>
        <div class="address-text">
            Jl. Dharmahusada Permai, Mulyorejo, Kec. Mulyorejo, Surabaya, Jawa Timur 60115, Indonesia
        </div>
    </div>
</div>

@endsection