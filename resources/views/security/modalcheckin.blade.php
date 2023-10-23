<form action="{{ route('security.storeCheckin') }}" method="post">
    @csrf
    <div class="row mb-3">
        <div class="col-12">
            <label for="cbTower" class="form-label">Tower</label>
            <select class="form-select" id="cbTower" name="tower" aria-label="Default select example"
                required>
                <option value="" disabled selected>Pilih Tower</option>
                @foreach ($towers as $tower)
                    <option value="{{ $tower->id }}">{{ $tower->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-12">
            <label for="txtSecurityName" class="form-label">Nama Satpam</label>
            <p class="mb-3">{{ $security->name }}</p>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-12">
            <input type="hidden" name="satpam_id" value="{{ $security->id }}">
            <button type="submit" class="btn btn-primary">Proses Check In</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        </div>
    </div>
</form>