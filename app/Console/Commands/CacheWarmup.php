<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use App\Services\CacheService;

#[Signature('app:cache-warmup')]
#[Description('Command description')]
class CacheWarmup extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(){return CacheService::bootCache();}
}
