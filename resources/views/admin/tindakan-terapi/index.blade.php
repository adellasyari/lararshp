<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Tindakan Terapi</title>
    <style>
        table { width: 90%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid black; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>

    <h1>Daftar Kode Tindakan Terapi</h1>

    <table border="1" cellpadding="8" cellspacing="0">
        <thead>
            <tr>
                <th>No</th>
                <th>ID</th>
                <th>Kode</th>
                <th>Deskripsi Tindakan</th>
                <th>ID Kategori</th>
                <th>ID Kategori Klinis</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($tindakanTerapi as $index => $tindakan)
            <tr>
                <td>{{ $index + 1 }}</td>
                
                <td>{{ $tindakan->idkode_tindakan_terapi }}</td>
                <td>{{ $tindakan->kode }}</td>
                <td>{{ $tindakan->deskripsi_tindakan_terapi }}</td>
                <td>{{ $tindakan->idkategori }}</td>
                <td>{{ $tindakan->idkategori_klinis }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align: center;">Data tindakan terapi masih kosong.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>
