@extends('layout.layout')

@section('content')
    <div class="row">
        <div class="col">
            <h1>Unit</h1>
        </div>
        <div class="col text-end">
            <a href="{{ route('unit.add') }}" type="button" class="btn btn-primary">Tambah Unit</a>
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
            <h4>Unit Aktif</h4>
            <table id="activeUnitTable" class="table table-striped table-hover text-center">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tower</th>
                        <th>Nomor Unit</th>
                        <th>Nama Pemilik</th>
                        <th>Nama Penghuni</th>
                        <th>Nomor HP Penghuni</th>
                        <th>Edit</th>
                        <th>Nonaktifkan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($activeUnits as $key => $aUnit)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $aUnit->tower->name }}</td>
                            <td>{{ $aUnit->unit_no }}</td>
                            <td>{{ $aUnit->owner_name }}</td>
                            <td>{{ $aUnit->holder_name }}</td>
                            <td>{{ $aUnit->holder_ph_number }}</td>
                            <td><a href="{{ route('unit.edit', $aUnit->id) }}" type="button"
                                    class="btn btn-secondary">Edit</a></td>
                            <td>
                                <form action="{{ route('unit.deactivate') }}" method="post"
                                    onclick="if(!confirm('Apakah anda yakin untuk menonaktifkan unit ini?')) return false;">
                                    @csrf
                                    <input type="hidden" name="unit_id" value="{{ $aUnit->id }}">
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
            <h4>Unit Nonaktif</h4>
            <table id="nonActiveUnitTable" class="table table-striped table-hover text-center">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tower</th>
                        <th>Nomor Unit</th>
                        <th>Nama Pemilik</th>
                        <th>Nama Penghuni</th>
                        <th>Nomor HP Penghuni</th>
                        <th>Aktifkan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($nonactiveUnits as $key => $naUnit)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $naUnit->tower->name }}</td>
                            <td>{{ $naUnit->unit_no }}</td>
                            <td>{{ $naUnit->owner_name }}</td>
                            <td>{{ $naUnit->holder_name }}</td>
                            <td>{{ $naUnit->holder_ph_number }}</td>
                            <td>
                                <form action="{{ route('unit.activate') }}" method="post"
                                    onclick="if(!confirm('Apakah anda yakin untuk mengaktifkan kembali unit ini?')) return false;">
                                    @csrf
                                    <input type="hidden" name="unit_id" value="{{ $naUnit->id }}">
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
            $('#activeUnitTable').DataTable();
            $('#nonActiveUnitTable').DataTable();
        });
    </script>
@endsection
