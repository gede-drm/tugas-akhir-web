@extends('layout.layout')

@section('content')
    <h1>Perizinan</h1>
    <div class="card mb-4">
        <div class="card-body">
            @if (session('status'))
                <div class="alert alert-success alert-dismissible mb-2" role="alert">
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    {{ session('status') }}
                </div>
            @endif
            <h4>Perizinan Pending</h4>
            <table id="pendingTable" class="table table-striped table-hover nowrap text-center">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal Pengajuan</th>
                        <th>Tanggal Pengerjaan</th>
                        <th>Unit Pemohon</th>
                        <th>Tenant Pemohon</th>
                        <th>Jumlah Pekerja</th>
                        <th>Detail</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pendingPermissions as $key => $pPermission)
                        <tr>
                            <td>{{ $pPermission->id }}</td>
                            <td>{{ $pPermission->proposal_date }}</td>
                            <td>{{ date('Y-m-d H:i', strtotime($pPermission->start_date)) }} s.d.
                                {{ date('Y-m-d H:i', strtotime($pPermission->end_date)) }}
                            </td>
                            <td>{{ $pPermission->serviceTransaction->unit->unit_no }}</td>
                            <td>{{ $pPermission->serviceTransaction->services[0]->tenant->name }}</td>
                            <td>{{ $pPermission->number_of_worker }}</td>
                            <td><a href="{{ route('permission.detail', $pPermission->id) }}" type="button" class="btn btn-info">Detail</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="card mb-4">
        <div class="card-body">
            <h4>Histori Perizinan</h4>
            <table id="historyTable" class="table table-striped table-hover nowrap text-center">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal Pengajuan</th>
                        <th>Tanggal Pengerjaan</th>
                        <th>Unit Pemohon</th>
                        <th>Tenant Pemohon</th>
                        <th>Jumlah Pekerja</th>
                        <th>Status</th>
                        <th>Detail</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($historyPermissions as $key => $hisPermission)
                        <tr>
                            <td>{{ $hisPermission->id }}</td>
                            <td>{{ $hisPermission->proposal_date }}</td>
                            <td>{{ date('Y-m-d H:i', strtotime($hisPermission->start_date)) }} s.d.
                                {{ date('Y-m-d H:i', strtotime($hisPermission->end_date)) }}
                            </td>
                            <td>{{ $hisPermission->serviceTransaction->unit->unit_no }}</td>
                            <td>{{ $hisPermission->serviceTransaction->services[0]->tenant->name }}</td>
                            <td>{{ $hisPermission->number_of_worker }}</td>
                            @if ($hisPermission->status == 'accept')
                                <td><span class="badge rounded-pill bg-success">Disetujui</span></td>
                            @else
                                <td><span class="badge rounded-pill bg-danger">Ditolak</span></td>
                            @endif
                            <td><a href="{{ route('permission.detail', $hisPermission->id) }}" type="button" class="btn btn-info">Detail</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $('#pendingTable').DataTable({
            scrollX: true
        });
        $('#historyTable').DataTable({
            scrollX: true
        });
    </script>
@endsection
