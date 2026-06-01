<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Data Karyawan</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 10px;
            color: #1e293b;
            background: #fff;
        }

        /* ── Header ─────────────────────────────────────── */
        .header {
            margin-bottom: 14px;
            padding-bottom: 10px;
            border-bottom: 2.5px solid #2563eb;
            display: table;
            width: 100%;
        }
        .header-left  { display: table-cell; vertical-align: middle; }
        .header-right { display: table-cell; vertical-align: middle; text-align: right; }

        .org-name  { font-size: 13px; font-weight: bold; color: #1e3a8a; }
        .doc-title { font-size: 10px; color: #64748b; margin-top: 2px; }

        .print-info { font-size: 8.5px; color: #94a3b8; }
        .print-info strong { color: #64748b; }

        /* ── Filter chips ────────────────────────────────── */
        .filter-bar {
            margin-bottom: 10px;
            font-size: 8.5px;
            color: #64748b;
        }
        .chip {
            display: inline-block;
            padding: 1px 7px;
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            border-radius: 99px;
            color: #1d4ed8;
            font-weight: bold;
            margin-left: 4px;
        }

        /* ── Summary row ─────────────────────────────────── */
        .summary {
            display: table;
            width: 100%;
            margin-bottom: 12px;
            border-collapse: separate;
            border-spacing: 6px 0;
        }
        .summary-box {
            display: table-cell;
            padding: 7px 10px;
            border-radius: 6px;
            text-align: center;
            width: 25%;
        }
        .summary-box .val  { font-size: 15px; font-weight: bold; }
        .summary-box .lbl  { font-size: 7.5px; color: #64748b; margin-top: 1px; }
        .box-total   { background: #eff6ff; color: #1d4ed8; }
        .box-aktif   { background: #f0fdf4; color: #15803d; }
        .box-cuti    { background: #fffbeb; color: #b45309; }
        .box-nonaktif{ background: #fef2f2; color: #b91c1c; }

        /* ── Table ───────────────────────────────────────── */
        table { width: 100%; border-collapse: collapse; }

        thead th {
            background: #1e40af;
            color: #fff;
            padding: 6px 7px;
            text-align: left;
            font-size: 8px;
            text-transform: uppercase;
            letter-spacing: .4px;
            white-space: nowrap;
        }
        thead th.center { text-align: center; }
        thead th.right  { text-align: right; }

        tbody tr:nth-child(odd)  { background: #fff; }
        tbody tr:nth-child(even) { background: #f8fafc; }
        tbody tr:last-child td   { border-bottom: none; }

        tbody td {
            padding: 5px 7px;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: top;
            font-size: 9.5px;
        }
        tbody td.center { text-align: center; }
        tbody td.right  { text-align: right; }
        tbody td.mono   { font-family: 'DejaVu Sans Mono', monospace; font-size: 8.5px; }

        .name      { font-weight: bold; color: #0f172a; }
        .sub       { font-size: 8px; color: #94a3b8; margin-top: 1px; }

        /* ── Badge ───────────────────────────────────────── */
        .badge {
            display: inline-block;
            padding: 1px 7px;
            border-radius: 99px;
            font-size: 8px;
            font-weight: bold;
            white-space: nowrap;
        }
        .badge-aktif   { background: #dcfce7; color: #15803d; }
        .badge-cuti    { background: #fef9c3; color: #b45309; }
        .badge-pensiun { background: #f3f4f6; color: #6b7280; }
        .badge-resign  { background: #fee2e2; color: #b91c1c; }

        /* ── Gender pill ─────────────────────────────────── */
        .pill-l { color: #1d4ed8; }
        .pill-p { color: #be185d; }

        /* ── Footer ──────────────────────────────────────── */
        .footer {
            margin-top: 14px;
            padding-top: 8px;
            border-top: 1px solid #e2e8f0;
            display: table;
            width: 100%;
            font-size: 8px;
            color: #94a3b8;
        }
        .footer-left  { display: table-cell; }
        .footer-right { display: table-cell; text-align: right; }
    </style>
</head>
<body>

    {{-- ── Header ────────────────────────────────────────────── --}}
    <div class="header">
        <div class="header-left">
            <div class="org-name">Sistem Informasi Kepegawaian</div>
            <div class="doc-title">Laporan Data Karyawan</div>
        </div>
        <div class="header-right">
            <div class="print-info">Dicetak: <strong>{{ now()->format('d F Y, H:i') }} WIB</strong></div>
            <div class="print-info">Total: <strong>{{ $karyawans->count() }} karyawan</strong></div>
        </div>
    </div>

    {{-- ── Filter chips ────────────────────────────────────────── --}}
    @if (!empty($filterInfo))
    <div class="filter-bar">
        Filter aktif:
        @foreach ($filterInfo as $f)
            <span class="chip">{{ $f }}</span>
        @endforeach
    </div>
    @endif

    {{-- ── Summary boxes ───────────────────────────────────────── --}}
    @php
        $total    = $karyawans->count();
        $aktif    = $karyawans->where('status_aktif', 'Aktif')->count();
        $cuti     = $karyawans->where('status_aktif', 'Cuti')->count();
        $nonaktif = $total - $aktif - $cuti;
    @endphp
    <div class="summary">
        <div class="summary-box box-total">
            <div class="val">{{ $total }}</div>
            <div class="lbl">Total Karyawan</div>
        </div>
        <div class="summary-box box-aktif">
            <div class="val">{{ $aktif }}</div>
            <div class="lbl">Aktif</div>
        </div>
        <div class="summary-box box-cuti">
            <div class="val">{{ $cuti }}</div>
            <div class="lbl">Cuti</div>
        </div>
        <div class="summary-box box-nonaktif">
            <div class="val">{{ $nonaktif }}</div>
            <div class="lbl">Pensiun / Resign</div>
        </div>
    </div>

    {{-- ── Tabel ────────────────────────────────────────────────── --}}
    <table>
        <thead>
            <tr>
                <th style="width:20px">#</th>
                <th style="width:140px">Nama Karyawan</th>
                <th style="width:105px">NIP</th>
                <th class="center" style="width:40px">JK</th>
                <th style="width:65px">Tgl Lahir</th>
                <th style="width:85px">Jabatan</th>
                <th style="width:65px">Kontrak</th>
                <th style="width:55px">Pendidikan</th>
                <th style="width:60px">Tgl Masuk</th>
                <th class="right" style="width:80px">Gaji</th>
                <th class="center" style="width:52px">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($karyawans as $i => $k)
            <tr>
                <td>{{ $i + 1 }}</td>

                <td>
                    <div class="name">{{ $k->nama_lengkap }}</div>
                    <div class="sub">{{ $k->nik }}</div>
                </td>

                <td class="mono">{{ $k->nip }}</td>

                <td class="center">
                    @if ($k->jenis_kelamin === 'Laki-laki')
                        <span class="pill-l">L</span>
                    @elseif ($k->jenis_kelamin === 'Perempuan')
                        <span class="pill-p">P</span>
                    @else
                        <span style="color:#cbd5e1">—</span>
                    @endif
                </td>

                <td>{{ $k->tanggal_lahir?->format('d/m/Y') ?? '—' }}</td>

                <td>{{ $k->jabatan?->nama_jabatan ?? '—' }}</td>

                <td style="font-size:8.5px">{{ $k->jenisKontrak?->nama_kontrak ?? '—' }}</td>

                <td>{{ $k->pendidikan?->nama_pendidikan ?? '—' }}</td>

                <td>{{ $k->tanggal_masuk?->format('d/m/Y') ?? '—' }}</td>

                <td class="right" style="font-size:9px">
                    {{ number_format($k->gaji, 0, ',', '.') }}
                </td>

                <td class="center">
                    @php $cls = strtolower($k->status_aktif); @endphp
                    <span class="badge badge-{{ $cls }}">{{ $k->status_aktif }}</span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- ── Footer ──────────────────────────────────────────────── --}}
    <div class="footer">
        <div class="footer-left">Sistem Informasi Kepegawaian &mdash; Dokumen ini digenerate secara otomatis</div>
        <div class="footer-right">{{ now()->format('d/m/Y H:i') }} WIB</div>
    </div>

</body>
</html>
