@extends('layout.layout')

@section('content')
    <div class="row">
        <div class="col">
            <h1>Tower</h1>
        </div>
        <div class="col text-end"><a href="{{ route('tower.add') }}" type="button" class="btn btn-primary">Tambah Tower</a>
        </div>
    </div>
    <div class="card mb-4">
        <div class="card-body">
            @if (session('status'))
                <div class="alert alert-success alert-dismissible mb-2" role="alert">
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    {{ session('status') }}
                </div>
            @endif
            <h4>Tower Aktif</h4>
            <table id="unitTable" class="table table-striped table-hover text-center">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Tower</th>
                        <th>Edit</th>
                        <th>Nonaktifkan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($activeTowers as $key => $aTower)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $aTower->name }}</td>
                            <td><a href="{{ route('tower.edit', $aTower->id) }}" type="button"
                                    class="btn btn-secondary">Edit</a></td>
                            <td>
                                <form action="{{ route('tower.deactivate') }}" method="post"
                                    onclick="if(!confirm('Apakah anda yakin untuk menonaktifkan tower ini?')) return false;">
                                    @csrf
                                    <input type="hidden" name="tower_id" value="{{ $aTower->id }}">
                                    <button type="submit" class="btn btn-danger">Nonaktifkan</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <h4>Tower Nonaktif</h4>
            <table id="unitTable" class="table table-striped table-hover text-center">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Tower</th>
                        <th>Edit</th>
                        <th>Aktifkan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($nonactiveTowers as $key => $naTower)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $naTower->name }}</td>
                            <td><a href="{{ route('tower.edit', $naTower->id) }}" type="button"
                                    class="btn btn-secondary">Edit</a></td>
                            <td>
                                <form action="{{ route('tower.activate') }}" method="post"
                                    onclick="if(!confirm('Apakah anda yakin untuk mengaktifkan tower ini kembali?')) return false;">
                                    @csrf
                                    <input type="hidden" name="tower_id" value="{{ $naTower->id }}">
                                    <button type="submit" class="btn btn-success">Aktifkan</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
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
