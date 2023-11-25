@extends('layout.layout')

@section('content')
    <h1>Tenant</h1>
    <div class="card mb-4">
        <div class="card-body">
            <h4>Edit Tenant</h4>
            @if ($errors->any())
                <div class="alert alert-danger" role="alert">
                    <p class="mb-0">Terjadi Kesalahan!</p>
                    @foreach ($errors->all() as $error)
                        <p class="mb-0">- {{ $error }}</p>
                    @endforeach
                </div>
            @endif
            <form action="{{ route('tenant.update', $tenant->id) }}" method="post">
                @method('put')
                @csrf
                <div class="row mb-3">
                    <div class="col-12">
                        <label for="txtTenantName" class="form-label">Nama Tenant</label>
                        <input type="text" class="form-control" id="txtTenantName" name="tenant_name"
                            value="{{ $tenant->name }}" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-12">
                        <label for="txtTenantAddress" class="form-label">Alamat Tenant</label>
                        <textarea class="form-control" id="txtTenantAddress" name="tenant_address" rows="3" style="resize:none;" required>{{ $tenant->address }}</textarea>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-12">
                        <label for="txtPhone" class="form-label">Nomor Telepon Tenant</label>
                        <input type="tel" class="form-control" id="txtPhone" name="phone_number" maxlength="15"
                            value="{{ $tenant->phone_number }}" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="" class="form-label">Jenis Tenant</label>
                    <br>
                    <div class="col-12 d-flex align-items-center" style="height: 3em;">
                        <div class="form-check-inline">
                            @if ($tenant->type == 'product')
                                <input type="radio" name="type" id="rdoBarang" class="form-check-input" value="product"
                                    checked>
                            @else
                                <input type="radio" name="type" id="rdoBarang" class="form-check-input"
                                    value="product">
                            @endif
                            <label for="" class="form-check-label">Barang</label>
                        </div>
                        <div class="form-check-inline">
                            @if ($tenant->type == 'service')
                                <input type="radio" name="type" id="rdoJasa" class="form-check-input" value="service"
                                    checked>
                            @else
                                <input type="radio" name="type" id="rdoJasa" class="form-check-input"
                                    value="service">
                            @endif
                            <label for="" class="form-check-label">Jasa</label>
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-6">
                        <label for="txtOpenHour" class="form-label">Jam Buka Tenant</label>
                        <input type="time" class="form-control" id="txtOpenHour" name="opening_hour"
                            value="{{ $tenant->service_hour_start }}" required>
                    </div>
                    <div class="col-6">
                        <label for="txtCloseHour" class="form-label">Jam Tutup Tenant</label>
                        <input type="time" class="form-control" id="txtCloseHour" name="closing_hour"
                            value="{{ $tenant->service_hour_end }}" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-4">
                        <div class="row mb-3">
                            <div class="col-12">
                                <label for="txtBankName" class="form-label">Nama Bank Tenant</label>
                                <input type="text" class="form-control" id="txtBankName" name="bank_name"
                                    value="{{ $tenant->bank_name }}" required>
                            </div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="row mb-3">
                            <div class="col-12">
                                <label for="txtAccountNum" class="form-label">Nomor Rekening Tenant</label>
                                <input type="text" class="form-control" id="txtAccountNum" name="bank_account"
                                    value="{{ $tenant->bank_account }}" required>
                            </div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="row mb-3">
                            <div class="col-12">
                                <label for="txtBankHolder" class="form-label">Nama Pemilik Rekening Tenant</label>
                                <input type="text" class="form-control" id="txtBankHolder" name="bank_holder"
                                    value="{{ $tenant->account_holder }}" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="" class="form-label">Delivery Tenant</label>
                    <br>
                    <div class="col-12 d-flex align-items-center" style="height: 3em;">
                        <div class="form-check-inline">
                            @if ($tenant->delivery == 1)
                                <input type="radio" name="delivery" id="rdoDeliveryYes" class="form-check-input"
                                    value="yes" checked>
                            @else
                                <input type="radio" name="delivery" id="rdoDeliveryYes" class="form-check-input"
                                    value="yes">
                            @endif
                            <label for="" class="form-check-label">Menyediakan</label>
                        </div>
                        <div class="form-check-inline">
                            @if ($tenant->delivery == 0)
                                <input type="radio" name="delivery" id="rdoDeliveryNo" class="form-check-input"
                                    value="no" checked>
                            @else
                                <input type="radio" name="delivery" id="rdoDeliveryNo" class="form-check-input"
                                    value="no">
                            @endif
                            <label for="" class="form-check-label">Tidak Menyediakan</label>
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="" class="form-label">Pembayaran Tunai</label>
                    <br>
                    <div class="col-12 d-flex align-items-center" style="height: 3em;">
                        <div class="form-check-inline">
                            @if ($tenant->cash == 1)
                                <input type="radio" name="cash" id="rdoCashYes" class="form-check-input"
                                    value="yes" checked>
                            @else
                                <input type="radio" name="cash" id="rdoCashYes" class="form-check-input"
                                    value="yes">
                            @endif
                            <label for="" class="form-check-label">Menyediakan</label>
                        </div>
                        <div class="form-check-inline">
                            @if ($tenant->cash == 0)
                                <input type="radio" name="cash" id="rdoCashNo" class="form-check-input"
                                    value="no" checked>
                            @else
                                <input type="radio" name="cash" id="rdoCashNo" class="form-check-input"
                                    value="no">
                            @endif
                            <label for="" class="form-check-label">Tidak Menyediakan</label>
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="row mb-3">
                        <div class="col-4">
                            <label for="txtUsername" class="form-label">Username</label>
                            <input type="text" class="form-control" id="txtUsername" name="username"
                                value="{{ $tenant->user->username }}" required>
                        </div>
                        <div class="col-4">
                            <label for="txtPass" class="form-label">Password</label>
                            <input type="password" class="form-control" id="txtPass" name="password"
                                placeholder="••••••••">
                        </div>
                        <div class="col-4">
                            <label for="txtPass" class="form-label">Konfirmasi Password</label>
                            <input type="password" class="form-control" id="txtConfPass" name="conf_pass"
                                placeholder="••••••••">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-6">
                            <button type="submit" class="btn btn-primary">Simpan</button>
            </form>
            <a href="{{ route('tenant.index') }}"type="button" class="btn btn-secondary">Batal</a>
        </div>
        <div class="col-6 text-end">
            <form action="{{ route('tenant.deactivate') }}" method="post"
                onclick="if(!confirm('Apakah anda yakin untuk menonaktifkan tenant ini?')) return false;">
                @csrf
                <input type="hidden" name="tenant_id" value="{{ $tenant->id }}">
                <button type="submit" class="btn btn-danger">Nonaktifkan</button>
        </div>
    </div>
    </div>
    </div>
    </div>
@endsection
