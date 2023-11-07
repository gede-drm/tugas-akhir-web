@extends('layout.layout')

@section('content')
    <h1>Satpam</h1>
    <div class="card mb-4">
        <div class="card-body">
            @if (session('status'))
                <div class="alert alert-success alert-dismissible mb-2" role="alert">
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    {{ session('status') }}
                </div>
            @endif
            <div class="row">
                <div class="col-6">
                    <h4>Check In/Out</h4>
                </div>
                <div class="col-6 text-end">
                    <a href="{{ route('security.checkinHistory') }}" type="button" class="btn btn-primary btn-sm">History
                        Check In/out</a>
                </div>
            </div>
            <h6>Tanggal Hari ini: <strong><span id="datetime"></span></strong></h6>
            <div class="mt-4">
                <table id="securityTable" class="table table-striped table-hover text-center">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Lengkap</th>
                            <th>Tower Checkin</th>
                            <th>Jam Checkin</th>
                            <th>Jam Checkout</th>
                            <th>Check</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($securities as $key => $security)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $security->name }}</td>

                                @if ($security->check != null)
                                    @if ($security->check->tower->name != null)
                                        <td>{{ $security->check->tower->name }}</td>
                                    @else
                                        <td>--</td>
                                    @endif

                                    @if ($security->check->check_in != null)
                                        <td>{{ date('H:i', strtotime($security->check->check_in)) }}</td>
                                    @else
                                        <td>--</td>
                                    @endif

                                    @if ($security->check->management_checkout_id == null)
                                        @if ($security->check->checkout != null)
                                            <td>{{ date('H:i', strtotime($security->check->check_out)) }}</td>
                                        @else
                                            <td>--</td>
                                        @endif
                                    @else
                                        <td>{{ date('H:i', strtotime($security->check->check_out)) }}</td>
                                    @endif
                                @else
                                    <td>--</td>
                                    <td>--</td>
                                    <td>--</td>
                                @endif

                                @if ($security->check == null)
                                    <td><button type="button" class="btn btn-success"
                                            onclick="getCheckIn({{ $security->id }})" data-bs-toggle="modal"
                                            data-bs-target="#modalCheckIn">Check In</button></td>
                                @else
                                    @if ($security->check->management_checkout_id == null)
                                        @if ($security->check->checkout != null)
                                            <td><button type="button" class="btn btn-success"
                                                    onclick="getCheckIn({{ $security->id }})" data-bs-toggle="modal"
                                                    data-bs-target="#modalCheckIn">Check In</button></td>
                                        @else
                                            <td>
                                                <form action="{{ route('security.storeCheckout') }}" method="post"
                                                    onclick="if(!confirm('Apakah anda yakin untuk melakukan checkout pada satpam ini?')) return false;">
                                                    @csrf
                                                    <input type="hidden" name="satpam_id" value="{{ $security->id }}">
                                                    <button type="submit" class="btn btn-danger">Check Out</button>
                                                </form>
                                            </td>
                                        @endif
                                    @else
                                        <td><button type="button" class="btn btn-success"
                                                onclick="getCheckIn({{ $security->id }})" data-bs-toggle="modal"
                                                data-bs-target="#modalCheckIn">Check In</button></td>
                                    @endif
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Modal Checkin --}}
    <div class="modal fade" id="modalCheckIn" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Check In Satpam</h1>
                </div>
                <div class="modal-body">
                    <div id="bodyModalCheckIn"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            $('#securityTable').DataTable({});
        });

        function getCheckIn(satpam_id) {
            $('#bodyModalCheckIn').html("");
            $.ajax({
                type: 'POST',
                url: '{{ route('security.modalCheckin') }}',
                data: {
                    '_token': '<?php echo csrf_token(); ?>',
                    'satpam_id': satpam_id,
                },
                success: function(data) {
                    $('#bodyModalCheckIn').html(data.data);
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    alert('Terjadi Kesalahan, Coba lagi beberapa saat kemudian.\nError Message: ' +
                        errorThrown);
                }
            });
        }

        function showTime() {
            var date = new Date(),
                utc = new Date(Date(
                    date.getFullYear(),
                    date.getMonth(),
                    date.getDate(),
                    date.getHours(),
                    date.getMinutes(),
                    date.getSeconds()
                ));

            $('#datetime').html(utc.toLocaleString('en-ID', {
                dateStyle: 'full',
                timeStyle: 'medium',
                hour12: false
            }));
        }

        setInterval(showTime, 1000);
    </script>
@endsection
