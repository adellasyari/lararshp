
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Pemilik</title>
    <style>
        table { width: 80%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid black; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>

    <h1>Daftar Pemilik Hewan</h1>

    <table border="1" cellpadding="8" cellspacing="0">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Pemilik</th>
                <th>No WA</th>
                <th>Alamat</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($pemilik as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                
                <td>{{ $item->user->nama ?? 'User Dihapus/Tidak Ditemukan' }}</td>
                
                <td>{{ $item->no_wa }}</td>
                <td>{{ $item->alamat }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="4" style="text-align: center;">Data pemilik masih kosong.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>