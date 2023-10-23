@extends('layout.layout')

@section('content')
    <h1>Perizinan</h1>
    <div class="card mb-4">
        <div class="card-body">
            <h4>Perizinan Pending</h4>
            @if (session('status'))
                <div class="alert alert-success alert-dismissible mb-2" role="alert">
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    {{ session('status') }}
                </div>
            @endif
        </div>
    </div>
    <div class="card mb-4">
        <div class="card-body">
            <h4>Histori Perizinan</h4>
        </div>
    </div>
@endsection

@section('script')
@endsection
