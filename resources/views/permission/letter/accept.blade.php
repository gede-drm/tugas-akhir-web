<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Perizinan | Surat Persetujuan</title>
    <style>
        * {
            font-family: 'Arial', 'Sans-serif';
            font-size: 11pt;
            color: black;
            background: white;
            line-height: 14pt;
        }

        body {
            margin: 50px 50px;
        }

        .text-start {
            text-align: left;
        }

        .text-center {
            text-align: center;
        }

        .text-justify {
            text-align: justify;
        }

        .text-end {
            text-align: right;
        }

        .mt-0 {
            margin-top: 0;
        }

        .mb-0 {
            margin-bottom: 0;
        }

        table,
        th,
        td {
            border: 1px solid black;
            padding: 2px 5px;
        }

        table {
            border-collapse: collapse;
            border-spacing: 10px;
        }

        hr {
            border: 0.25px solid black;
        }
    </style>
</head>

<body>
    <div class="text-center" style="font-size: 14pt;">
        <p class="mb-0"><strong><u>SURAT KONTRAK PENGERJAAN DAN IZIN RENOVASI</u></strong></p>
        <p class="mt-0"><strong>No. {{ $permission->perm_number }}</strong></p>
    </div>
    <br>
    <div class="text-justify">
        <p>Dengan surat ini, pada tanggal {{ date('d-m-Y') }} pihak manajemen apartemen <strong>menyetujui</strong> pengerjaan
            renovasi yang telah diajukan pada tanggal {{ date('d-m-Y H:i', strtotime($permission->proposal_date)) }} dengan detail kontrak tertera di bawah ini.</p>
        <p class="mb-0"><strong>Unit Apartemen</strong></p>
        <table style="width: 100%;" class="text-start">
            <tr>
                <td style="width: 30%;"><strong>Tower</strong></td>
                <td>{{ $permission->serviceTransaction->unit->tower->name }}</td>
            </tr>
            <tr>
                <td><strong>Nomor Unit</strong></td>
                <td>{{ $permission->serviceTransaction->unit->unit_no }}</td>
            </tr>
            <tr>
                <td><strong>Pemilik Unit</strong></td>
                <td>{{ $permission->serviceTransaction->unit->owner_name }}</td>
            </tr>
            <tr>
                <td><strong>Penghuni Unit</strong></td>
                <td>{{ $permission->serviceTransaction->unit->holder_name }}</td>
            </tr>
            <tr>
                <td><strong>Nomor Telepon Penghuni</strong></td>
                <td>{{ $permission->serviceTransaction->unit->holder_ph_number }}</td>
            </tr>
        </table>
        <p class="mb-0"><strong>Detail Pengerjaan</strong></p>
        <table style="width: 100%;" class="text-start">
            <tr>
                <td style="width: 30%;"><strong>Tenant Penanggung Jawab</strong></td>
                <td>
                    {{ $tenant->name }}
                    <br>
                    {{ $tenant->address }}
                    <br>
                    {{ $tenant->phone_number }}
                </td>
            </tr>
            <tr>
                <td><strong>Tanggal Mulai Pengerjaan</strong></td>
                <td>{{ date('d-m-Y H:i', strtotime($permission->start_date)) }}</td>
            </tr>
            <tr>
                <td><strong>Tanggal Selesai Pengerjaan</strong></td>
                <td>{{ date('d-m-Y H:i', strtotime($permission->end_date)) }}</td>
            </tr>
            <tr>
                <td><strong>Jumlah Pekerja</strong></td>
                <td>{{ $permission->number_of_worker }}</td>
            </tr>
            <tr>
                <td><strong>Deskripsi Pekerjaan</strong></td>
                <td>{{ $permission->description }}</td>
            </tr>
            <tr>
                <td><strong>Daftar Pekerja</strong></td>
                <td>
                    <table class="table-worker" class="text-start">
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
        </table>
        <p>Demikian surat ini dibuat untuk dapat digunakan dengan sebaik-baiknya. Terima kasih.</p>
        <table style="width: 100%; border: none;">
            <tr class="border: none;">
                <td style="width: 50%; text-align: center;">Tenant,</td>
                <td style="width: 50%; text-align: center;">Manajemen,</td>
            </tr>
            <br>
            <br>
            <br>
            <br>
            <tr class="border: none;">
                <td style="width: 50%;"><div style="margin: auto; width: 80%; border-bottom: 1px solid black;"></div></td>
                <td style="width: 50%;"><div style="margin: auto; width: 80%; border-bottom: 1px solid black;"></div></td>
            </tr>
        </table>
        <br>
        <br>
        <br>
        <p class="mb-0"><strong>Catatan:</strong></p>
        <p>Hanya pekerja terdaftar di atas yang dapat mendapatkan izin masuk ke dalan apartemen.</p>
        <hr>
        <p style="font-size: 10pt;">Surat ini dibuat oleh sistem pada {{ $date }} atas persetujuan manajemen ({{ Auth::user()->username }})</p>
    </div>
</body>

</html>
