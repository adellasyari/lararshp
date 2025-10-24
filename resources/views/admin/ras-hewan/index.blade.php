<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Ras Hewan</title>
    <style>
        table { width: 80%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid black; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>

    <h1>Daftar Ras Hewan</h1>

    <table border="1" cellpadding="8" cellspacing="0">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Ras Hewan</th>
                <th>Jenis Hewan (Induk)</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($list_ras as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item->nama_ras }}</td>

                <td>{{ $item->jenisHewan->nama_jenis_hewan ?? 'Jenis Tidak Ditemukan' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="3" style="text-align: center;">Data ras hewan masih kosong.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>