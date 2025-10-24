<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Role</title>
    <style>
        table { width: 80%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid black; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>

    <h1>Daftar Role</h1>

    <table border="1" cellpadding="8" cellspacing="0">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Role</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($role as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item->nama_role }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="2" style="text-align: center;">Data role masih kosong.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>
