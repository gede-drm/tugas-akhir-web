@extends('layout.layout')

@section('content')
    <h1>Paket</h1>
    <div class="card mb-4">
        <div class="card-body">
            @if (session('status'))
                <div class="alert alert-success alert-dismissible mb-2" role="alert">
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    {{ session('status') }}
                </div>
            @endif
            <h4>Daftar Paket Masuk</h4>
            <table id="pkgTable" class="table table-striped table-hover text-center">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal Masuk</th>
                        <th>Unit</th>
                        <th>Deskripsi</th>
                        <th>Tanggal Pengambilan</th>
                        <th>Foto Paket</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($incomingPkgs as $key => $iPkg)
                        <tr>
                            <td>{{ $key+1 }}</td>
                            <td>{{ $iPkg->receive_date }} ({{ $iPkg->receivingSecurity->name }})</td>
                            <td>{{ $iPkg->unit->unit_no }}</td>
                            <td>{{ $iPkg->description }}</td>
                            @if($iPkg->pickup_date != null)
                            <td>{{ $iPkg->pickup_date }} ({{ $iPkg->pickupSecurity->name }})</td>
                            @else
                            <td>Belum diambil</td>
                            @endif
                            <td><button type="button" class="btn btn-info"
                                onclick="getPackagePhoto({{ $iPkg->id }})" data-bs-toggle="modal"
                                data-bs-target="#modalPackage">Lihat</button></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Modal Paket --}}
    <div class="modal fade" id="modalPackage" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Foto Paket</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="bodyModalPackage"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            $('#pkgTable').DataTable({});
        });

        function getPackagePhoto(package_id) {
            $('#bodyModalPackage').html("");
            $.ajax({
                type: 'POST',
                url: '{{ route('package.modalPhoto') }}',
                data: {
                    '_token': '<?php echo csrf_token(); ?>',
                    'package_id': package_id,
                },
                success: function(data) {
                    $('#bodyModalPackage').html(data.data);
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    alert('Terjadi Kesalahan, Coba lagi beberapa saat kemudian.\nError Message: ' +
                        errorThrown);
                }
            });
        }
    </script>
@endsection
