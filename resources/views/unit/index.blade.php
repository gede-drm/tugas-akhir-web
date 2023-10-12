@extends('layout.layout')

@section('content')
    <h1>Unit</h1>
    <div class="card mb-4">
        <div class="card-body">
            <a href="{{ route('unit.add') }}" type="button" class="btn btn-primary">Tambah Unit</a>
            @if (session('status'))
                <div class="alert alert-success alert-dismissible" role="alert">
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    {{ session('status') }}
                </div>
            @endif
            <div class="mt-4">
                <table id="unitTable" class="table table-striped table-hover text-center">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tower</th>
                            <th>Nomor Unit</th>
                            <th>Nama Pemegang</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($units as $key => $unit)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $unit->tower->name }}</td>
                                <td>{{ $unit->unit_no }}</td>
                                <td>{{ $unit->holder_name }}</td>
                                <td><a href="{{ route('unit.edit', $unit->id) }}" type="button" class="btn btn-secondary">Edit</a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            $('#unitTable').DataTable();
        });
    </script>
@endsection
