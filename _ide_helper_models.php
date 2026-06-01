<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * @property int $id_jabatan
 * @property string $nama_jabatan
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Karyawan> $karyawans
 * @property-read int|null $karyawans_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Jabatan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Jabatan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Jabatan query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Jabatan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Jabatan whereIdJabatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Jabatan whereNamaJabatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Jabatan whereUpdatedAt($value)
 */
	class Jabatan extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id_jenis_kontrak
 * @property string $nama_kontrak
 * @property int $jam_kerja_sehari
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Karyawan> $karyawans
 * @property-read int|null $karyawans_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisKontrak newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisKontrak newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisKontrak query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisKontrak whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisKontrak whereIdJenisKontrak($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisKontrak whereJamKerjaSehari($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisKontrak whereNamaKontrak($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JenisKontrak whereUpdatedAt($value)
 */
	class JenisKontrak extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id_karyawan
 * @property string $nama_lengkap
 * @property string $nip
 * @property string $nik
 * @property string|null $jenis_kelamin
 * @property string|null $tanggal_lahir
 * @property \Illuminate\Support\Carbon $tanggal_masuk
 * @property string|null $alamat
 * @property string|null $agama
 * @property string|null $golongan_darah
 * @property string|null $foto
 * @property int|null $id_jabatan
 * @property int|null $id_pendidikan
 * @property int|null $id_jenis_kontrak
 * @property string $status_aktif
 * @property numeric $gaji
 * @property \Illuminate\Support\Carbon $tanggal_mulai_jabatan
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $foto_url
 * @property-read \App\Models\Jabatan|null $jabatan
 * @property-read \App\Models\JenisKontrak|null $jenisKontrak
 * @property-read \App\Models\Pendidikan|null $pendidikan
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Karyawan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Karyawan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Karyawan query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Karyawan whereAgama($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Karyawan whereAlamat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Karyawan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Karyawan whereFoto($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Karyawan whereGaji($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Karyawan whereGolonganDarah($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Karyawan whereIdJabatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Karyawan whereIdJenisKontrak($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Karyawan whereIdKaryawan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Karyawan whereIdPendidikan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Karyawan whereJenisKelamin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Karyawan whereNamaLengkap($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Karyawan whereNik($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Karyawan whereNip($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Karyawan whereStatusAktif($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Karyawan whereTanggalLahir($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Karyawan whereTanggalMasuk($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Karyawan whereTanggalMulaiJabatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Karyawan whereUpdatedAt($value)
 */
	class Karyawan extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id_pendidikan
 * @property string $nama_pendidikan
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Karyawan> $karyawans
 * @property-read int|null $karyawans_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pendidikan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pendidikan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pendidikan query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pendidikan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pendidikan whereIdPendidikan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pendidikan whereNamaPendidikan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pendidikan whereUpdatedAt($value)
 */
	class Pendidikan extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 */
	class User extends \Eloquent {}
}

