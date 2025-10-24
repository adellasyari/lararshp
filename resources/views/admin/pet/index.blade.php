<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Pet (Hewan Peliharaan)</title>
    <style>
        table { width: 95%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid black; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>

    <h1>Daftar Pet (Hewan Peliharaan)</h1>

    <table border="1" cellpadding="8" cellspacing="0">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Hewan</th>
                <th>Pemilik</th>
                <th>Jenis Hewan</th>
                <th>Ras Hewan</th>
                <th>Tanggal Lahir</th>
                <th>Warna/Tanda</th>
                <th>Jenis Kelamin</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($pet as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item->nama }}</td>
                <td>{{ $item->pemilik->user->nama ?? 'User Dihapus' }}</td>
                
                <td>{{ $item->rasHewan->jenisHewan->nama_jenis_hewan ?? 'Jenis Dihapus' }}</td>
                
                <td>{{ $item->rasHewan->nama_ras_hewan ?? 'Ras Dihapus' }}</td>
                
                <td>{{ $item->tanggal_lahir }}</td>
                <td>{{ $item->warna_tanda }}</td>
                <td>{{ $item->jenis_kelamin }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="8" style="text-align: center;">Data hewan masih kosong.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>