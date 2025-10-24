<table border="1" cellpadding="8" cellspacing="0">
    <thead>
        <tr>
            <th>No</th>
            <th>Nama Kategori Klinis</th>
        </tr>
    </thead>
    <tbody>
        @foreach($kategoriKlinis as $index => $kat)
        <tr>
            <td>{{ $index + 1 }}</td>

            <td>{{ $kat->nama_kategori_klinis }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
