@extends('layout.layout')

@section('content')
    <h1>Tenant</h1>
    <div class="card mb-4">
        <div class="card-body">
            @if (session('status'))
                <div class="alert alert-success alert-dismissible mb-2" role="alert">
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    {{ session('status') }}
                </div>
            @endif
            <a href="{{ route('tenant.add') }}" type="button" class="btn btn-primary">Tambah Tenant</a>
            <div class="mt-4">
                <table id="tenantTable" class="table table-striped table-hover nowrap text-center">
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
                        @foreach ($tenants as $key => $tenant)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $tenant->name }}</td>
                                <td>{{ $tenant->address }}</td>
                                <td>{{ $tenant->phone_number }}</td>
                                @if($tenant->type=='product')
                                <td>Barang</td>
                                @else
                                <td>Jasa</td>
                                @endif
                                <td>{{ $tenant->service_hour_start }}</td>
                                <td>{{ $tenant->service_hour_end }}</td>
                                <td>{{ $tenant->bank_name }}</td>
                                <td>{{ $tenant->bank_account }}</td>
                                <td>{{ $tenant->account_holder }}</td>
                                @if ($tenant->delivery == 1)
                                    <td>Ya</td>
                                @else
                                    <td>Tidak</td>
                                @endif
                                @if ($tenant->delivery == 'open')
                                    <td>Buka</td>
                                @else
                                    <td>Tutup</td>
                                @endif
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
