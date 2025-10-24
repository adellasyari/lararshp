@extends('layouts.app')
@section('content')

<div id="struktur-organisasi" class="page-section">
    <h2 class="page-title">Struktur Organisasi</h2>
    <div class="org-chart-box">
        <div class="org-level-1">
            <div class="org-person">
                <img src="{{ asset('asset/Direktur.jpg')}}" alt="Direktur" class="org-person-img">
                <div class="org-person-name org-person-name-l1">Dr. drh. Susi Taufanilawaty, M.Si., drh.</div>
                <div class="org-person-title org-person-title-l1">Direktur</div>
            </div>
        </div>
        <div class="org-level-2">
            <div class="org-person">
                <img src="{{ asset('asset/WakilDirektur1.jpg')}}" alt="Wakil Direktur 1" class="org-person-img">
                <div class="org-person-name org-person-name-l2">Dr. Noefiarno Trisakti, M.Si., drh.</div>
                <div class="org-person-title org-person-title-l2">Wakil Direktur 1</div>
            </div>
            <div class="org-person">
                <img src="{{ asset('asset/WakilDirektur2.jpg')}}" alt="Wakil Direktur 2" class="org-person-img">
                <div class="org-person-name org-person-name-l2">Dr. Mimy Sasmita S., M.Med., drh.</div>
                <div class="org-person-title org-person-title-l2">Wakil Direktur 2</div>
            </div>
        </div>
    </div>
</div>

@endsection