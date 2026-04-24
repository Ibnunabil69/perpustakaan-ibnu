<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Denda - {{ config('app.name') }}</title>
    <style>
        @page {
            size: A4;
            margin: 1.5cm;
        }

        body {
            font-family: 'Times New Roman', Times, serif;
            color: #1a1a1a;
            line-height: 1.4;
            margin: 0;
            padding: 0;
            background-color: white;
        }

        /* Kop Surat Style */
        .header-kop {
            text-align: center;
            border-bottom: 3px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
            position: relative;
        }

        .header-kop::after {
            content: '';
            display: block;
            border-bottom: 1px solid #000;
            margin-top: 2px;
        }

        .brand-name {
            font-size: 24px;
            font-weight: bold;
            text-transform: uppercase;
            margin: 0;
            letter-spacing: 2px;
        }

        .brand-sub {
            font-size: 12px;
            margin: 5px 0 0 0;
            font-style: italic;
            color: #4b5563;
        }

        /* Document Title */
        .doc-title {
            text-align: center;
            text-decoration: underline;
            font-size: 16px;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 20px;
        }

        .meta-info {
            font-size: 11px;
            margin-bottom: 15px;
            display: flex;
            justify-content: space-between;
        }

        /* Table Style */
        .table-print {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
        }

        .table-print th {
            background-color: #f3f4f6 !important;
            border: 1px solid #374151;
            padding: 8px 4px;
            text-transform: uppercase;
        }

        .table-print td {
            border: 1px solid #374151;
            padding: 6px 8px;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .font-bold {
            font-weight: bold;
        }

        /* Summary Area */
        .summary-box {
            margin-top: 20px;
            float: right;
            width: 250px;
            border: 1px solid #000;
            padding: 10px;
        }

        .summary-item {
            display: flex;
            justify-content: space-between;
            font-size: 12px;
        }

        /* Signature Area */
        .signature-wrapper {
            margin-top: 50px;
            clear: both;
            display: flex;
            justify-content: flex-end;
        }

        .signature-box {
            text-align: center;
            width: 200px;
        }

        @media print {
            .no-print {
                display: none;
            }

            body {
                -webkit-print-color-adjust: exact;
            }
        }
    </style>
</head>

<body onload="window.print()">
    <div class="max-w-4xl mx-auto">
        <!-- Kop Surat -->
        <div class="header-kop">
            <h1 class="brand-name">{{ config('app.name', 'PerpusKita') }}</h1>
            <p class="brand-sub">Sistem Manajemen Perpustakaan Digital Terintegrasi</p>
        </div>

        <h2 class="doc-title">Laporan Pertanggungjawaban Denda</h2>

        <div class="meta-info">
            <div>
                <strong>Periode:</strong>
                {{ $from ? \Carbon\Carbon::parse($from)->translatedFormat('d F Y') : 'Awal' }}
                s/d
                {{ $to ? \Carbon\Carbon::parse($to)->translatedFormat('d F Y') : 'Sekarang' }}
            </div>
            <div>
                <strong>Tanggal Cetak:</strong> {{ now()->translatedFormat('d F Y') }}
            </div>
        </div>

        <!-- Table -->
        <table class="table-print">
            <thead>
                <tr>
                    <th style="width: 30px">No</th>
                    <th>Nama Anggota</th>
                    <th>Judul Buku</th>
                    <th style="width: 80px">Tgl Pinjam</th>
                    <th style="width: 80px">Tgl Kembali</th>
                    <th style="width: 50px">Telat</th>
                    <th style="width: 100px" class="text-right">Total Denda</th>
                </tr>
            </thead>
            <tbody>
                @forelse($peminjamans as $index => $p)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>
                            <div class="font-bold">{{ $p->user->name }}</div>
                        </td>
                        <td>{{ $p->book->judul }}</td>
                        <td class="text-center">{{ \Carbon\Carbon::parse($p->tanggal_pinjam)->format('d/m/Y') }}</td>
                        <td class="text-center">{{ \Carbon\Carbon::parse($p->tanggal_kembali)->format('d/m/Y') }}</td>
                        <td class="text-center">
                            @php
                                $target = \Carbon\Carbon::parse($p->tanggal_kembali_target);
                                $kembali = \Carbon\Carbon::parse($p->tanggal_kembali);
                                $diff = $target->diffInDays($kembali);
                            @endphp
                            {{ $diff }} Hari
                        </td>
                        <td class="text-right font-bold">
                            Rp {{ number_format($p->total_denda, 0, ',', '.') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center italic">Tidak ditemukan data denda dalam periode terpilih.</td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr style="background-color: #f9fafb;">
                    <td colspan="6" class="text-right font-bold uppercase" style="padding: 10px;">Total Denda Terkumpul
                    </td>
                    <td class="text-right font-bold" style="font-size: 13px;">
                        Rp {{ number_format($totalDenda, 0, ',', '.') }}
                    </td>
                </tr>
            </tfoot>
        </table>

        <div class="no-print" style="margin-top: 50px; text-align: center;">
            <button onclick="window.print()" style="padding: 10px 20px; cursor: pointer;">Cetak Ulang</button>
        </div>
    </div>
</body>

</html>