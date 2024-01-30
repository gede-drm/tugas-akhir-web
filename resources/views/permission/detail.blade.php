@extends('layout.layout')

@section('content')
    <h1>Perizinan</h1>
    <div class="card mb-4">
        <div class="card-body">
            <a href="{{ route('permission.index') }}" class="btn btn-sm btn-outline-primary mb-2"><i
                    class='bx bx-chevron-left'></i>Kembali</a>
            <h4>Detail Perizinan (ID: {{ $permission->id }})
                @if ($permission->status != null)
                    @if ($permission->status == 'accept')
                        <span class="badge rounded-pill bg-success">Disetujui</span>
                    @else
                        <span class="badge rounded-pill bg-danger">Ditolak</span>
                    @endif
                @endif
            </h4>
            <table class="table table-borderless">
                @if ($permission->status != null)
                    @if ($permission->status == 'accept')
                        <tr>
                            <td><strong>Nomor Surat</strong></td>
                            <td>{{ $permission->perm_number }}</td>
                        </tr>
                    @endif
                @endif
                <tr>
                    <td><strong>Unit Pemohon</strong></td>
                    <td>{{ $permission->serviceTransaction->unit->unit_no }}</td>
                </tr>
                <tr>
                    <td><strong>Tenant Pemohon</strong></td>
                    <td>{{ $permission->serviceTransaction->services[0]->tenant->name }}</td>
                </tr>
                <tr>
                    <td><strong>Tanggal Pengajuan</strong></td>
                    <td>{{ $permission->proposal_date }}</td>
                </tr>
                <tr>
                    <td><strong>Tanggal Pengerjaan</strong></td>
                    <td>{{ date('Y-m-d H:i', strtotime($permission->start_date)) }} s.d.
                        {{ date('Y-m-d H:i', strtotime($permission->end_date)) }}</td>
                </tr>
                <tr>
                    <td><strong>Jumlah Pekerja</strong></td>
                    <td>{{ $permission->number_of_worker }}</td>
                </tr>
                <tr>
                    <td><strong>Deskripsi</strong></td>
                    <td>{{ $permission->description }}</td>
                </tr>
                <tr>
                    <td><strong>Detail Transaksi</strong></td>
                    <td>
                        @foreach ($permission->serviceTransaction->services as $sDetail)
                            <p class="mb-0">- {{ $sDetail->name }} x{{ $sDetail->pivot->quantity }}
                                {{ $sDetail->pricePer }}</p>
                        @endforeach
                    </td>
                </tr>
                <tr>
                    <td><strong>Daftar Pekerja</strong></td>
                    <td>
                        <table class="table table-bordered table-sm">
                            <tr>
                                <th>No</th>
                                <th>Nama Pekerja</th>
                                <th>NIK</th>
                            </tr>
                            @foreach ($permission->workers as $key => $worker)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $worker->worker_name }}</td>
                                    <td>{{ $worker->idcard_number }}</td>
                                </tr>
                            @endforeach
                        </table>
                    </td>
                </tr>
                @if ($permission->status != null)
                    @if ($permission->status == 'accept')
                        <tr>
                            <td><strong>Tanggal Persetujuan</strong></td>
                            <td>{{ $permission->approval_date }}</td>
                        </tr>
                        <tr>
                            <td><strong>Petugas</strong></td>
                            <td>{{ $permission->managementApproval->username }}</td>
                        </tr>
                        <tr>
                            <td><strong>Riwayat Pengerjaan</strong></td>
                            <td>
                                <table class="table table-bordered table-sm">
                                    <tr>
                                        <th>No</th>
                                        <th>Tanggal Masuk</th>
                                        <th>Nama Pegawai</th>
                                        <th>Satpam yang Bertugas</th>
                                    </tr>
                                    @if (count($permission->permits) > 0)
                                        @foreach ($permission->permits as $key => $permit)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>{{ $permit->date }}</td>
                                                <td>{{ $permit->worker->worker_name }}</td>
                                                <td>{{ $permit->security->name }}</td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="4" class="text-center">Tidak Tercatat Adanya Pengerjaan</td>
                                        </tr>
                                    @endif
                                </table>
                            </td>
                        </tr>
                    @else
                        <tr>
                            <td><strong>Tanggal Penolakan</strong></td>
                            <td>{{ $permission->approval_date }}</td>
                        </tr>
                        <tr>
                            <td><strong>Petugas</strong></td>
                            <td>{{ $permission->managementApproval->username }}</td>
                        </tr>
                    @endif
                @endif
            </table>
            <br>
            @if ($permission->status == null)
                <div class="row mb-3">
                    <div class="d-grid gap-2">
                        <form action="{{ route('permission.accept') }}" method="post" class="w-100"
                            onclick="if(!confirm('Apakah anda yakin untuk menyetujui pengajuan perizinan ini?')) return false;">
                            @csrf
                            <input type="hidden" name="permission_id" value="{{ $permission->id }}">
                            <button type="submit" class="btn btn-success w-100">Setujui Perizinan</button>
                        </form>
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                            data-bs-target="#modalReject">Tolak Perizinan</button>
                    </div>
                </div>
            @else
                <div class="row mb-3">
                    <div class="d-grid gap-2">
                        <form action="{{ route('permission.download') }}" method="post" class="w-100">
                            @csrf
                            <input type="hidden" name="permission_id" value="{{ $permission->id }}">
                            @if ($permission->status == 'accept')
                                <button type="submit" class="btn btn-info w-100">Download Surat Persetujuan</button>
                            @else
                                <button type="submit" class="btn btn-info w-100">Download Surat Penolakan</button>
                            @endif
                        </form>
                    </div>
                </div>
            @endif
        </div>
    </div>
    {{-- Modal Reject --}}
    <div class="modal fade" id="modalReject" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Alasan Tolak Perizinan</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('permission.reject') }}" method="post">
                        @csrf
                        <div class="row mb-5">
                            <div class="col-12">
                                <textarea class="form-control" id="txtReasonReject" name="reject_reason" rows="3" style="resize:none;"
                                    placeholder="Tuliskan Alasan Penolakan Perizinan" required></textarea>
                            </div>
                        </div>
                        <input type="hidden" name="permission_id" value="{{ $permission->id }}">
                        <button type="submit" class="btn btn-danger w-100">Tolak</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
@endsection
