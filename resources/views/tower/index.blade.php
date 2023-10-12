@extends('layout.layout')

@section('content')
    <h1>Tower</h1>
    <div class="card mb-4">
        <div class="card-body">
            @if (session('status'))
                <div class="alert alert-success alert-dismissible mb-2" role="alert">
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    {{ session('status') }}
                </div>
            @endif
            <a href="{{ route('tower.add') }}" type="button" class="btn btn-primary">Tambah Tower</a>
            <div class="mt-4">
                <table id="unitTable" class="table table-striped table-hover text-center">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Tower</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($towers as $key => $tower)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $tower->name }}</td>
                                <td><a href="{{ route('tower.edit', $tower->id) }}" type="button" class="btn btn-secondary">Edit</a></td>
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
