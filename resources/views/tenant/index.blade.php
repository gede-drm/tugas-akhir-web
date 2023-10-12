@extends('layout.layout')

@section('content')
    <h1>Tenant</h1>
    <div class="card mb-4">
        <div class="card-body">
            <a href="{{ route('tenant.add') }}" type="button" class="btn btn-primary">Tambah Tenant</a>
            @if (session('status'))
                <div class="alert alert-success alert-dismissible" role="alert">
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    {{ session('status') }}
                </div>
            @endif
            <div class="mt-4">
                <table id="tenantTable" class="table table-striped table-hover text-center">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Alamat</th>
                            <th>No. Telepon</th>
                            <th>Jenis</th>
                            <th>Jam Buka</th>
                            <th>Jam Tutup</th>
                            <th>Nama Bank</th>
                            <th>Nomor Rekening</th>
                            <th>Pemegang Rekening</th>
                            <th>Pengiriman</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tenants as $key => $unit)
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td><a href="#" type="button" class="btn btn-secondary">Edit</a></td>
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
            $('#tenantTable').DataTable({
                "scrollX": true,
            });
        });
    </script>
@endsection