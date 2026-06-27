<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Profil Karyawan - {{ $karyawan->nama_lengkap }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11px;
            color: #1e293b;
            background: #fff;
            padding: 20px 30px;
        }

        /* ── Header ─────────────────────────────────────── */
        .header {
            margin-bottom: 16px;
            padding-bottom: 12px;
            border-bottom: 2.5px solid #2563eb;
        }
        .org-name  { font-size: 14px; font-weight: bold; color: #1e3a8a; }
        .doc-title { font-size: 10px; color: #64748b; margin-top: 2px; }
        .print-info { font-size: 8.5px; color: #94a3b8; margin-top: 4px; }
        .print-info strong { color: #64748b; }

        /* ── Profile Section ────────────────────────────── */
        .profile-header {
            margin-bottom: 16px;
            padding: 12px 0;
        }
        .profile-name {
            font-size: 20px;
            font-weight: bold;
            color: #0f172a;
            margin-bottom: 4px;
        }
        .profile-sub {
            font-size: 10px;
            color: #64748b;
        }
        .badge {
            display: inline-block;
            padding: 2px 10px;
            border-radius: 99px;
            font-size: 9px;
            font-weight: bold;
            margin-left: 8px;
        }
        .badge-aktif   { background: #dcfce7; color: #15803d; }
        .badge-pensiun { background: #f3f4f6; color: #6b7280; }

        /* ── Section ────────────────────────────────────── */
        .section {
            margin-bottom: 14px;
        }
        .section-title {
            font-size: 11px;
            font-weight: bold;
            color: #1e40af;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
            padding-bottom: 4px;
            border-bottom: 1px solid #e2e8f0;
        }

        /* ── Data Table ─────────────────────────────────── */
        .data-table {
            width: 100%;
            border-collapse: collapse;
        }
        .data-table td {
            padding: 5px 8px;
            vertical-align: top;
            font-size: 10px;
            border-bottom: 1px solid #f1f5f9;
        }
        .data-table .label {
            width: 140px;
            color: #64748b;
            font-weight: 500;
        }
        .data-table .value {
            color: #0f172a;
        }

        /* ── History Table ──────────────────────────────── */
        .history-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 6px;
        }
        .history-table th {
            background: #f1f5f9;
            padding: 5px 8px;
            text-align: left;
            font-size: 9px;
            font-weight: bold;
            color: #475569;
            text-transform: uppercase;
        }
        .history-table td {
            padding: 4px 8px;
            font-size: 9.5px;
            border-bottom: 1px solid #f1f5f9;
            color: #334155;
        }
        .history-status {
            display: inline-block;
            padding: 1px 6px;
            border-radius: 4px;
            font-size: 8px;
            font-weight: bold;
        }
        .status-diterima  { background: #dcfce7; color: #15803d; }
        .status-pending   { background: #fef9c3; color: #b45309; }
        .status-scheduled { background: #dbeafe; color: #1d4ed8; }
        .status-stop      { background: #fee2e2; color: #b91c1c; }

        /* ── Footer ──────────────────────────────────────── */
        .footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #e2e8f0;
            font-size: 8px;
            color: #94a3b8;
            text-align: center;
        }

        /* ── Two Column Layout ──────────────────────────── */
        .two-col {
            display: table;
            width: 100%;
        }
        .col-left, .col-right {
            display: table-cell;
            width: 50%;
            vertical-align: top;
            padding-right: 10px;
        }
        .col-right {
            padding-left: 10px;
            padding-right: 0;
        }
    </style>
</head>
<body>

    {{-- ── Header ────────────────────────────────────────────── --}}
    <div class="header">
        <div class="org-name">Sistem Informasi Kepegawaian</div>
        <div class="doc-title">Profil Data Karyawan</div>
        <div class="print-info">Dicetak: <strong>{{ now()->format('d F Y, H:i') }} WIB</strong></div>
    </div>

    {{-- ── Profile Header ────────────────────────────────────── --}}
    <div class="profile-header">
        <div class="profile-name">
            {{ $karyawan->nama_lengkap }}
            <span class="badge badge-{{ strtolower($karyawan->status_aktif) }}">{{ $karyawan->status_aktif }}</span>
        </div>
        <div class="profile-sub">
            NIP: {{ $karyawan->nip }} &nbsp;|&nbsp; NIK: {{ $karyawan->nik }}
            @if ($karyawan->jabatan)
                &nbsp;|&nbsp; {{ $karyawan->jabatan->nama_jabatan }}
            @endif
        </div>
    </div>

    <div class="two-col">
        {{-- ── Data Pribadi ──────────────────────────────────── --}}
        <div class="col-left">
            <div class="section">
                <div class="section-title">Data Pribadi</div>
                <table class="data-table">
                    <tr>
                        <td class="label">Nama Lengkap</td>
                        <td class="value">{{ $karyawan->nama_lengkap }}</td>
                    </tr>
                    <tr>
                        <td class="label">NIK</td>
                        <td class="value">{{ $karyawan->nik }}</td>
                    </tr>
                    <tr>
                        <td class="label">NIP</td>
                        <td class="value">{{ $karyawan->nip }}</td>
                    </tr>
                    <tr>
                        <td class="label">Jenis Kelamin</td>
                        <td class="value">{{ $karyawan->jenis_kelamin ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="label">Tanggal Lahir</td>
                        <td class="value">{{ $karyawan->tanggal_lahir?->format('d F Y') ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="label">Agama</td>
                        <td class="value">{{ $karyawan->agama ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="label">Golongan Darah</td>
                        <td class="value">{{ $karyawan->golongan_darah ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="label">Pendidikan</td>
                        <td class="value">
                            @if ($karyawan->pendidikan)
                                {{ $karyawan->pendidikan->jenjang }}
                            @elseif ($karyawan->nama_pendidikan)
                                {{ $karyawan->nama_pendidikan }}
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="label">Alamat</td>
                        <td class="value">{{ $karyawan->alamat ?? '-' }}</td>
                    </tr>
                </table>
            </div>
        </div>

        {{-- ── Data Kepegawaian ──────────────────────────────── --}}
        <div class="col-right">
            <div class="section">
                <div class="section-title">Data Kepegawaian</div>
                <table class="data-table">
                    <tr>
                        <td class="label">Jabatan</td>
                        <td class="value">{{ $karyawan->jabatan?->nama_jabatan ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="label">Jenis Kontrak</td>
                        <td class="value">{{ $karyawan->jenisKontrak?->nama_kontrak ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="label">Golongan</td>
                        <td class="value">
                            @if ($karyawan->golongan)
                                {{ $karyawan->golongan->nama_golongan }} ({{ $karyawan->golongan->tipe }})
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="label">Tanggal Masuk</td>
                        <td class="value">{{ $karyawan->tanggal_masuk?->format('d F Y') ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="label">Status</td>
                        <td class="value">{{ $karyawan->status_aktif }}</td>
                    </tr>
                    @if ($karyawan->jenisKontrak?->jam_kerja_sehari)
                    <tr>
                        <td class="label">Jam Kerja / Hari</td>
                        <td class="value">{{ $karyawan->jenisKontrak->jam_kerja_sehari }} jam</td>
                    </tr>
                    @endif
                </table>
            </div>

            {{-- ── Jadwal Kenaikan Aktif ─────────────────────── --}}
            @php
                $berkalaAktif = $karyawan->kenaikanBerkalaAktif;
                $golonganAktif = $karyawan->kenaikanGolonganAktif;
            @endphp
            @if ($berkalaAktif || $golonganAktif)
            <div class="section">
                <div class="section-title">Jadwal Kenaikan Aktif</div>
                <table class="data-table">
                    @if ($berkalaAktif)
                    <tr>
                        <td class="label">Kenaikan Berkala</td>
                        <td class="value">
                            {{ $berkalaAktif->tanggal_berikutnya?->format('d F Y') ?? '-' }}
                            ({{ ucfirst($berkalaAktif->status) }})
                        </td>
                    </tr>
                    @endif
                    @if ($golonganAktif)
                    <tr>
                        <td class="label">Kenaikan Golongan</td>
                        <td class="value">
                            {{ $golonganAktif->tanggal_berikutnya?->format('d F Y') ?? '-' }}
                            ({{ ucfirst($golonganAktif->status) }})
                        </td>
                    </tr>
                    @endif
                </table>
            </div>
            @endif
        </div>
    </div>

    {{-- ── Riwayat Kenaikan Berkala ──────────────────────────── --}}
    @if ($karyawan->kenaikanBerkalas->isNotEmpty())
    <div class="section">
        <div class="section-title">Riwayat Kenaikan Berkala</div>
        <table class="history-table">
            <thead>
                <tr>
                    <th>Tanggal Berikutnya</th>
                    <th>Status</th>
                    <th>Catatan</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($karyawan->kenaikanBerkalas as $b)
                <tr>
                    <td>{{ $b->tanggal_berikutnya?->format('d M Y') ?? '-' }}</td>
                    <td>
                        @php
                            $statusClass = match($b->status) {
                                'diterima'  => 'status-diterima',
                                'pending'   => 'status-pending',
                                'scheduled' => 'status-scheduled',
                                'stop'      => 'status-stop',
                                default     => 'status-scheduled',
                            };
                            $statusLabel = match($b->status) {
                                'diterima'  => 'Disetujui',
                                'pending'   => 'Menunggu',
                                'scheduled' => 'Terjadwal',
                                'stop'      => 'Dihentikan',
                                default     => ucfirst($b->status),
                            };
                        @endphp
                        <span class="history-status {{ $statusClass }}">{{ $statusLabel }}</span>
                    </td>
                    <td>{{ $b->catatan ?? '-' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    {{-- ── Riwayat Kenaikan Golongan ─────────────────────────── --}}
    @if ($karyawan->historiGolongans->isNotEmpty())
    <div class="section">
        <div class="section-title">Riwayat Kenaikan Golongan</div>
        <table class="history-table">
            <thead>
                <tr>
                    <th>Tanggal Efektif</th>
                    <th>Golongan Lama</th>
                    <th>Golongan Baru</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($karyawan->historiGolongans as $h)
                <tr>
                    <td>{{ $h->tanggal_efektif?->format('d M Y') ?? '-' }}</td>
                    <td>{{ $h->golonganLama?->nama_golongan ?? '-' }}</td>
                    <td><strong>{{ $h->golonganBaru?->nama_golongan ?? '-' }}</strong></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    {{-- ── Footer ────────────────────────────────────────────── --}}
    <div class="footer">
        Sistem Informasi Kepegawaian &mdash; Dokumen ini digenerate secara otomatis &mdash; {{ now()->format('d/m/Y H:i') }} WIB
    </div>

</body>
</html>
