<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Presensi Siswa</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 30px; }
        .header h1 { color: #2c3e50; margin: 0; }
        .filter-info { background: #e3f2fd; padding: 15px; margin-bottom: 20px; border-radius: 5px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #3498db; color: white; font-weight: bold; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        .total-row { font-weight: bold; background-color: #ecf0f1 !important; }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN PRESENSI SISWA</h1>
        <p>Dicetak: {{ now()->format('d F Y H:i') }}</p>
    </div>

    <div class="filter-info">
        <strong>Filter:</strong> 
        Kelas: {{ $filterInfo['kelas'] }} | 
        Mapel: {{ $filterInfo['mapel'] }} | 
        Periode: {{ $filterInfo['tanggal_mulai'] }} s/d {{ $filterInfo['tanggal_akhir'] }}
    </div>

    <table>
        <thead>
            <tr>
                <th>Nama Siswa</th>
                <th>Kelas</th>
                <th>Mapel</th>
                <th>Hadir</th>
                <th>Izin</th>
                <th>Sakit</th>
                <th>Alpa</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse($laporan as $item)
            <tr>
                <td>{{ $item['nama'] }}</td>
                <td>{{ $item['kelas'] }}</td>
                <td>{{ $item['mapel'] }}</td>
                <td>{{ $item['hadir'] }}</td>
                <td>{{ $item['izin'] }}</td>
                <td>{{ $item['sakit'] }}</td>
                <td>{{ $item['alpa'] }}</td>
                <td>{{ $item['total'] }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="8" style="text-align: center; padding: 20px;">Tidak ada data</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top: 40px; text-align: right;">
        <p>{{ config('app.name', 'Sistem Presensi') }}</p>
    </div>
</body>
</html>