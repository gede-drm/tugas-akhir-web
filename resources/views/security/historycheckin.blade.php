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
                            @if($his->check_out != null)
                            <td>{{ $his->check_out }}</td>
                            @else
                            <td>--</td>
                            @endif
                            <td>{{ $his->security->name }} ({{ $his->security->employeeid }})</td>
                            <td>{{ $his->managementIn->username }}</td>
                            @if ($his->managementOut != null)
                                <td>{{ $his->managementOut->username }}</td>
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
