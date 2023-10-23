@extends('layout.layout')

@section('content')
    <div class="row">
        <div class="col">
            <h1>Satpam</h1>
        </div>
        <div class="col text-end">
            <a href="{{ route('security.add') }}" type="button" class="btn btn-primary">Tambah Satpam</a>
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
            <h4>Satpam Aktif</h4>
            <table id="activeSecurityTable" class="table table-striped table-hover text-center">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>ID Karyawan</th>
                        <th>Nama Lengkap</th>
                        <th>Username</th>
                        <th>Nonaktifkan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($activeSecurities as $key => $aSecurity)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $aSecurity->employeeid }}</td>
                            <td>{{ $aSecurity->name }}</td>
                            <td>{{ $aSecurity->user->username }}</td>
                            <td>
                                <form action="{{ route('security.deactivate') }}" method="post"
                                    onclick="if(!confirm('Apakah anda yakin untuk menonaktifkan satpam ini?')) return false;">
                                    @csrf
                                    <input type="hidden" name="satpam_id" value="{{ $aSecurity->id }}">
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
            <h4>Satpam Nonaktif</h4>
            <table id="nonactiveSecurityTable" class="table table-striped table-hover text-center">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>ID Karyawan</th>
                        <th>Nama Lengkap</th>
                        <th>Username</th>
                        <th>Aktifkan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($nonactiveSecurities as $key => $naSecurity)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $naSecurity->employeeid }}</td>
                            <td>{{ $naSecurity->name }}</td>
                            <td>{{ $naSecurity->user->username }}</td>
                            <td>
                                <form action="{{ route('security.activate') }}" method="post"
                                    onclick="if(!confirm('Apakah anda yakin untuk mengaktifkan kembali satpam ini?')) return false;">
                                    @csrf
                                    <input type="hidden" name="satpam_id" value="{{ $naSecurity->id }}">
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
            $('#activeSecurityTable').DataTable({});
            $('#nonactiveSecurityTable').DataTable({});
        });
    </script>
@endsection
