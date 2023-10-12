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
            <form action="{{ route('unit.store') }}" method="post">
                @csrf
                <div class="row mb-3">
                    <div class="col-12">
                        <label for="cbTower" class="form-label">Tower</label>
                        <select class="form-select" id="cbTower" name="tower" aria-label="Default select example"
                            required>
                            <option value="" disabled selected>Pilih Tower</option>
                            @foreach ($towers as $tower)
                                <option value="{{ $tower->id }}">{{ $tower->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-12">
                        <label for="txtUnitNo" class="form-label">Nomor Unit & Username</label>
                        <input type="text" class="form-control" id="txtUnitNo" name="unit_no"
                            placeholder="C01-02" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-12">
                        <label for="txtHolderName" class="form-label">Nama Penghuni</label>
                        <input type="text" class="form-control" id="txtHolderName" name="holder_name"
                            placeholder="Jason Tan" required>
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
