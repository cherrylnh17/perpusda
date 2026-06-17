<?php

namespace App\Console\Commands;

use App\Models\KenaikanBerkala;
use App\Models\KenaikanGolongan;
use Illuminate\Console\Command;

class ProsesPendingKenaikan extends Command
{
    protected $signature   = 'kenaikan:proses-pending';
    protected $description = 'Set status scheduled → pending untuk kenaikan yang sudah jatuh tempo';

    public function handle(): void
    {
        // Berkala
        $berkala = KenaikanBerkala::jatuhTempo()->get();
        $berkala->each(fn ($k) => $k->update(['status' => 'pending']));
        $this->info("Berkala: {$berkala->count()} record → pending");

        // Golongan
        $golongan = KenaikanGolongan::jatuhTempo()->get();
        $golongan->each(fn ($k) => $k->update(['status' => 'pending']));
        $this->info("Golongan: {$golongan->count()} record → pending");
    }
}
