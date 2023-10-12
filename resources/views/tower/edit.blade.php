@extends('layout.layout')

@section('content')
    <h1>Tower</h1>
    <div class="card mb-4">
        <div class="card-body">
            <h4>Edit Tower</h4>
            @if ($errors->any())
                <div class="alert alert-danger" role="alert">
                    <p class="mb-0">Terjadi Kesalahan!</p>
                    @foreach ($errors->all() as $error)
                        <p class="mb-0">- {{ $error }}</p>
                    @endforeach
                </div>
            @endif
            <form action="{{ route('tower.update', $tower->id) }}" method="post">
                @method('put')
                @csrf
                <div class="row mb-3">
                    <div class="col-12">
                        <label for="txtTower" class="form-label">Nama Tower</label>
                        <input type="text" class="form-control" id="txtTower" name="tower"
                            value="{{ $tower->name }}" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="{{ route('tower.index') }}"type="button" class="btn btn-secondary">Batal</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
