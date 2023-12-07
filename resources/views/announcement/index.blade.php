@extends('layout.layout')

@section('content')
    <div class="row">
        <div class="col-6">
            <h1>Pemberitahuan</h1>
        </div>
        <div class="col-6 text-end">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                data-bs-target="#modalPemberitahuan">Tambah</button>
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
            <h4>Daftar Pemberitahuan</h4>
            <table id="annTable" class="table table-striped table-hover text-center">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Judul</th>
                        <th>Deskripsi</th>
                        <th>Pembuat</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($announcements as $key => $announcement)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $announcement->date }}</td>
                            <td>{{ $announcement->title }}</td>
                            <td class="text-start">{!! nl2br($announcement->description) !!}</td>
                            <td>{{ $announcement->management->username }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Modal Pemberitahuan --}}
    <div class="modal fade" id="modalPemberitahuan" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Tambah Pemberitahuan</h1>
                </div>
                <div class="modal-body">
                    <form action="{{ route('announcement.store') }}" method="post">
                        @csrf
                        <div class="row mb-3">
                            <div class="col-12">
                                <label for="txtAnnouncementTitle" class="form-label">Judul</label>
                                <input type="text" class="form-control" id="txtAnnouncementTitle" name="title"
                                    placeholder="Isikan Judul Pemberitahuan" maxlength="100" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-12">
                                <label for="txtAnnouncementDesc" class="form-label">Deskripsi</label>
                                <textarea class="form-control" id="txtAnnouncementDesc" name="description" rows="3" style="resize:none;"
                                    placeholder="Isikan Deskripsi Pemberitahuan" maxlength="512" required></textarea>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">Simpan</button>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            $('#annTable').DataTable({});
        });
    </script>
@endsection
