@extends('layout.layout')

@section('content')
    <h1>Tenant</h1>
    <div class="card mb-4">
        <div class="card-body">
            <h4>Tambah Tenant</h4>
            @if ($errors->any())
                <div class="alert alert-danger" role="alert">
                    <p class="mb-0">Terjadi Kesalahan!</p>
                    @foreach ($errors->all() as $error)
                        <p class="mb-0">- {{ $error }}</p>
                    @endforeach
                </div>
            @endif
            <form action="{{ route('tenant.store') }}" method="post">
                @csrf
                <div class="row mb-3">
                    <div class="col-12">
                        <label for="txtTenantName" class="form-label">Nama Tenant</label>
                        <input type="text" class="form-control" id="txtTenantName" name="tenant_name"
                            placeholder="Toko Galon Biru Muda" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-12">
                        <label for="txtTenantAddress" class="form-label">Alamat Tenant</label>
                        <textarea class="form-control" id="txtTenantAddress" name="tenant_address" rows="3" style="resize:none;"
                            placeholder="Ruko San Diego MR 1-550, Jl.Kalisari Permai 1, Pakuwon City" required></textarea>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-12">
                        <label for="txtPhone" class="form-label">Nomor Telepon Tenant</label>
                        <input type="tel" class="form-control" id="txtPhone" name="phone_number" maxlength="15"
                            placeholder="6281234567890" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="" class="form-label">Jenis Tenant</label>
                    <br>
                    <div class="col-12 d-flex align-items-center" style="height: 3em;">
                        <div class="form-check-inline">
                            <input type="radio" name="type" id="rdoBarang" class="form-check-input" value="product"
                                checked>
                            <label for="" class="form-check-label">Barang</label>
                        </div>
                        <div class="form-check-inline">
                            <input type="radio" name="type" id="rdoJasa" class="form-check-input" value="service">
                            <label for="" class="form-check-label">Jasa</label>
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-6">
                        <label for="txtOpenHour" class="form-label">Jam Buka Tenant</label>
                        <input type="time" class="form-control" id="txtOpenHour" name="opening_hour" value="08:00"
                            required>
                    </div>
                    <div class="col-6">
                        <label for="txtCloseHour" class="form-label">Jam Tutup Tenant</label>
                        <input type="time" class="form-control" id="txtCloseHour" name="closing_hour" value="17:00" min="00:01" max="23:59"
                            required>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-4">
                        <div class="row mb-3">
                            <div class="col-12">
                                <label for="txtBankName" class="form-label">Nama Bank Tenant</label>
                                <input type="text" class="form-control" id="txtBankName" name="bank_name"
                                    placeholder="BCA" required>
                            </div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="row mb-3">
                            <div class="col-12">
                                <label for="txtAccountNum" class="form-label">Nomor Rekening Tenant</label>
                                <input type="text" class="form-control" id="txtAccountNum" name="bank_account"
                                    placeholder="5200880123" required>
                            </div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="row mb-3">
                            <div class="col-12">
                                <label for="txtBankHolder" class="form-label">Nama Pemilik Rekening Tenant</label>
                                <input type="text" class="form-control" id="txtBankHolder" name="bank_holder"
                                    placeholder="Richardsen Salim" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="" class="form-label">Delivery Tenant</label>
                    <br>
                    <div class="col-12 d-flex align-items-center" style="height: 3em;">
                        <div class="form-check-inline">
                            <input type="radio" name="delivery" id="rdoDeliveryYes" class="form-check-input"
                                value="yes" checked>
                            <label for="" class="form-check-label">Menyediakan</label>
                        </div>
                        <div class="form-check-inline">
                            <input type="radio" name="delivery" id="rdoDeliveryNo" class="form-check-input"
                                value="no">
                            <label for="" class="form-check-label">Tidak Menyediakan</label>
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="" class="form-label">Pembayaran Tunai</label>
                    <br>
                    <div class="col-12 d-flex align-items-center" style="height: 3em;">
                        <div class="form-check-inline">
                            <input type="radio" name="cash" id="rdoCashyYes" class="form-check-input"
                                value="yes" checked>
                            <label for="" class="form-check-label">Menyediakan</label>
                        </div>
                        <div class="form-check-inline">
                            <input type="radio" name="cash" id="rdoCashNo" class="form-check-input"
                                value="no">
                            <label for="" class="form-check-label">Tidak Menyediakan</label>
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-4">
                        <label for="txtUsername" class="form-label">Username</label>
                        <input type="text" class="form-control" id="txtUsername" name="username"
                            placeholder="galonbirusandiego" required>
                    </div>
                    <div class="col-4">
                        <label for="txtPass" class="form-label">Password</label>
                        <input type="password" class="form-control" id="txtPass" name="password"
                            placeholder="••••••••" required>
                    </div>
                    <div class="col-4">
                        <label for="txtPass" class="form-label">Konfirmasi Password</label>
                        <input type="password" class="form-control" id="txtConfPass" name="conf_pass"
                            placeholder="••••••••" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="{{ route('tenant.index') }}"type="button" class="btn btn-secondary">Batal</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
