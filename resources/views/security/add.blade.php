@extends('layout.layout')

@section('content')
    <h1>Tenant</h1>
    <div class="card mb-4">
        <div class="card-body">
            <h4>Tambah Satpam</h4>
            @if ($errors->any())
                <div class="alert alert-danger" role="alert">
                    <p class="mb-0">Terjadi Kesalahan!</p>
                    @foreach ($errors->all() as $error)
                        <p class="mb-0">- {{ $error }}</p>
                    @endforeach
                </div>
            @endif
            <form action="{{ route('security.store') }}" method="post">
                @csrf
                <div class="row mb-3">
                    <div class="col-12">
                        <label for="txtSecurityName" class="form-label">ID Karyawan</label>
                        <input type="text" class="form-control" id="txtSecurityName" name="security_id"
                            placeholder="12354" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-12">
                        <label for="txtSecurityName" class="form-label">Nama Security</label>
                        <input type="text" class="form-control" id="txtSecurityName" name="security_name"
                            placeholder="Suparno" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-4">
                        <label for="txtUsername" class="form-label">Username</label>
                        <input type="text" class="form-control" id="txtUsername" name="username"
                            placeholder="suparnosecurity" required>
                    </div>
                    <div class="col-4">
                        <label for="txtPass" class="form-label">Password</label>
                        <input type="password" class="form-control" id="txtPass" name="password"
                            placeholder="••••••••" required>
                    </div>
                    <div class="col-4">
                        <label for="txtPass" class="form-label">Konfirmasi Password</label>
                        <input type="password" class="form-control" id="txtConfPass" name="conf_pass"
                            placeholder="••••••••" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="{{ route('security.index') }}"type="button" class="btn btn-secondary">Batal</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
