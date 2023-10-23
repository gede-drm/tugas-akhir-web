@extends('layout.layout')

@section('content')
    <div class="row">
        <div class="col">
            <h1>Tenant</h1>
        </div>
        <div class="col text-end">
            <a href="{{ route('tenant.add') }}" type="button" class="btn btn-primary">Tambah Tenant</a>
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
            <h4>Tenant Aktif (Kerjasama Aktif)</h4>
            <table id="activeTenantTable" class="table table-striped table-hover nowrap text-center">
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
                        <th>Buka/Tutup</th>
                        <th>Status Kerjasama</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($activeTenants as $key => $aTenant)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $aTenant->name }}</td>
                            <td>{{ $aTenant->address }}</td>
                            <td>{{ $aTenant->phone_number }}</td>
                            @if ($aTenant->type == 'product')
                                <td>Barang</td>
                            @else
                                <td>Jasa</td>
                            @endif
                            <td>{{ $aTenant->service_hour_start }}</td>
                            <td>{{ $aTenant->service_hour_end }}</td>
                            <td>{{ $aTenant->bank_name }}</td>
                            <td>{{ $aTenant->bank_account }}</td>
                            <td>{{ $aTenant->account_holder }}</td>
                            @if ($aTenant->delivery == 1)
                                <td>Ya</td>
                            @else
                                <td>Tidak</td>
                            @endif
                            @if ($aTenant->status == 'open')
                                <td>Buka</td>
                            @else
                                <td>Tutup</td>
                            @endif
                            <td>Aktif</td>
                            <td><a href="{{ route('tenant.edit', $aTenant->id) }}" type="button"
                                    class="btn btn-secondary">Edit</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <h4>Tenant Nonaktif</h4>
            <table id="nonactiveTenantTable" class="table table-striped table-hover text-center">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Alamat</th>
                        <th>No. Telepon</th>
                        <th>Jenis</th>
                        <th>Status Kerjasama</th>
                        <th>Aktifkan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($nonactiveTenants as $key => $naTenant)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $naTenant->name }}</td>
                            <td>{{ $naTenant->address }}</td>
                            <td>{{ $naTenant->phone_number }}</td>
                            @if ($naTenant->type == 'product')
                                <td>Barang</td>
                            @else
                                <td>Jasa</td>
                            @endif
                            <td><span class="badge bg-danger">Tidak Aktif</td>
                            <td>
                                <form action="{{ route('tenant.activate') }}" method="post"
                                    onclick="if(!confirm('Apakah anda yakin untuk mengaktifkan kembali tenant ini?')) return false;">
                                    @csrf
                                    <input type="hidden" name="tenant_id" value="{{ $naTenant->id }}">
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
            $('#activeTenantTable').DataTable({
                "scrollX": true,
            });
            $('#nonactiveTenantTable').DataTable({});
        });
    </script>
@endsection
