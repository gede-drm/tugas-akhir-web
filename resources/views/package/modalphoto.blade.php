<div class="row">
    <div class="col-12 text-center">
        <img src="{{ asset('packages/photos/' . $package->photo_url) }}" alt="" class="img img-fluid">
    </div>
</div>
<div class="row">
    <div class="col-12">
        <p class="mb-2 mt-2">Unit: {{ $package->unit->unit_no }}</p>
        <p class="mb-0 mt-0">Tanggal Masuk: {{ $package->receive_date }}</p>
        <p class="mb-0 mt-0">Petugas Penerima: {{ $package->receivingSecurity->name }} ({{  $package->receivingSecurity->employeeid  }})</p>
        @if ($package->pickup_date != null)
            <p class="mb-0 mt-2">Tanggal Pengambilan: {{ $package->pickup_date }}</p>
            <p class="mb-0 mt-0">Petugas Pengambilan: {{ $package->pickupSecurity->name }}</p>
        @else
            <p class="mb-0 mt-2">Tanggal Pengambilan: Belum diambil</p>
        @endif
        <p class="mb-0 mt-3 fw-bolder">Deskripsi Paket</p>
        <p class="mb-0 mt-0">{!! nl2br($package->description) !!}</p>
    </div>
</div>
