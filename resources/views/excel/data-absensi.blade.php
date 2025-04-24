<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Tanggal</th>
                <th>Jam Masuk</th>
                <th>Jam Pulang</th>
                <th>Lama Kerja</th>
                <th>Lokasi Masuk</th>
                <th>Lokasi Pulang</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($absensis as $absensi)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $absensi->tanggal }}</td>
                    <td>{{ $absensi->jam_masuk }}</td>
                    <td>{{ $absensi->jam_pulang }}</td>
                    <td>{{ $absensi->lama_kerja }}</td>
                    <td>{{ $absensi->lokasi_masuk }}</td>
                    <td>{{ $absensi->lokasi_pulang }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>
