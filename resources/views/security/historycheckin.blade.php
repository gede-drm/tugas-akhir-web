@extends('layout.layout')

@section('content')
    <div class="row">
        <div class="col">
            <h1>Satpam</h1>
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
            <h4>History Checkin</h4>
            <div>
                <p class="mb-0">Filter</p>
                <form action="" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-2">
                            <div class="row mb-3">
                                <div class="col-12">
                                    <label for="txtStartDate" class="form-label mb-0">Tanggal Mulai</label>
                                    <input type="date" class="form-control form-control-sm" id="txtStartDate"
                                        name="start_date" value="{{ $start_date != null ? $start_date : '' }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="row mb-3">
                                <div class="col-12">
                                    <label for="txtEndDate" class="form-label mb-0">Tanggal Berakhir</label>
                                    <input type="date" class="form-control form-control-sm" id="txtEndDate"
                                        name="end_date" value="{{ $end_date != null ? $end_date : '' }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="row mb-3">
                                <div class="col-12">
                                    <label for="txtSecurityName" class="form-label mb-0">Nama Security</label>
                                    <input type="text" class="form-control form-control-sm" id="txtSecurityName"
                                        name="security_name" placeholder="Suparno"
                                        value="{{ $security_name != null ? $security_name : '' }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="row mb-3">
                                <div class="col-12">
                                    <label for="" class="mb-0"></label>
                                    <button type="submit" class="btn btn-primary btn-sm d-block">Cari</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <table id="historyTable" class="table table-striped table-hover nowrap text-center">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tower</th>
                        <th>Check In</th>
                        <th>Check Out</th>
                        <th>Satpam</th>
                        <th>Petugas In</th>
                        <th>Petugas Out</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($history as $key => $his)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $his->tower->name }}</td>
                            <td>{{ $his->check_in }}</td>
                            @if ($his->check_out != null)
                                <td>{{ $his->check_out }}</td>
                            @else
                                <td>--</td>
                            @endif
                            <td>{{ $his->security->name }} ({{ $his->security->employeeid }})</td>
                            <td>{{ $his->managementIn->username }}</td>
                            @if ($his->check_out < date('Y-m-d H:i:s'))
                                @if ($his->managementOut != null)
                                    <td>{{ $his->managementOut->username }}</td>
                                @else
                                    <td>Checkout Sistem</td>
                                @endif
                            @else
                                <td>Belum Check Out</td>
                            @endif
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
            $('#historyTable').DataTable({
                scrollX: true
            });
        });
    </script>
@endsection
