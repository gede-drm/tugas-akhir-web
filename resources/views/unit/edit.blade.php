@extends('layout.layout')

@section('content')
    <h1>Unit</h1>
    <div class="card mb-4">
        <div class="card-body">
            <h4>Tambah Unit</h4>
            @if ($errors->any())
                <div class="alert alert-danger" role="alert">
                    <p class="mb-0">Terjadi Kesalahan!</p>
                    @foreach ($errors->all() as $error)
                        <p class="mb-0">- {{ $error }}</p>
                    @endforeach
                </div>
            @endif
            <form action="{{ route('unit.update', $unit->id) }}" method="post">
                @csrf
                @method('PUT')
                <div class="row mb-3">
                    <div class="col-12">
                        <label for="cbTower" class="form-label">Tower</label>
                        <p class="fw-bold mb-0">{{ $unit->tower->name }}</p>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-12">
                        <label for="txtUnitNo" class="form-label">Nomor Unit & Username</label>
                        <p class="fw-bold mb-0">{{ $unit->user->username }}</p>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-12">
                        <label for="txtOwnerName" class="form-label">Nama Pemilik</label>
                        <input type="text" class="form-control" id="txtOwnerName" name="owner_name"
                            value="{{ $unit->owner_name }}" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-12">
                        <label for="txtHolderName" class="form-label">Nama Penghuni</label>
                        <input type="text" class="form-control" id="txtHolderName" name="holder_name"
                            value="{{ $unit->holder_name }}" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-12">
                        <label for="txtHolderPhoneNumber" class="form-label">Nomor HP Penghuni</label>
                        <input type="tel" class="form-control" id="txtHolderPhoneNumber" name="holder_ph_number" maxlength="15"
                            value="{{ $unit->holder_ph_number }}" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-12">
                        <label for="txtPass" class="form-label">Password</label>
                        <input type="password" class="form-control" id="txtPass" name="password"
                            placeholder="••••••••" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-12">
                        <label for="txtPass" class="form-label">Konfirmasi Password</label>
                        <input type="password" class="form-control" id="txtConfPass" name="conf_pass"
                            placeholder="••••••••" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="{{ route('unit.index') }}"type="button" class="btn btn-secondary">Batal</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection