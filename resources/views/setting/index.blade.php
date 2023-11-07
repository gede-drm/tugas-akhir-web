@extends('layout.layout')

@section('content')
    <h1>Setting</h1>
    <div class="card mb-4">
        <div class="card-body">
            @if (session('status'))
                <div class="alert alert-success alert-dismissible mb-2" role="alert">
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    {{ session('status') }}
                </div>
            @endif
            <form action="{{ route('setting.update') }}" method="post">
                @csrf
                <div class="row mb-3">
                    <div class="col-4">
                        <label for="txtSettingName" class="form-label">Durasi Shift Satpam</label>
                        <div class="input-group">
                            <input type="number" class="form-control" id="txtSettingName" name="security_shift_duration"
                                value={{ $settingSecurity->value }} min="0" max="24" required>
                                <span class="input-group-text">Jam</span>
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
