<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class FinanceInstall extends Command
{
    protected $signature = 'finance:install';
    protected $description = 'Install Finance API (migrate, seed and prepare project)';

    public function handle(): int
    {
        $this->info('🚀 Starting Finance API installation...');

        $this->info('🧹 Running migrations...');
        Artisan::call('migrate:fresh');
        $this->line(Artisan::output());

        $this->info('🌱 Seeding database...');
        Artisan::call('db:seed');
        $this->line(Artisan::output());

        $this->info('🔑 Generating app key...');
        Artisan::call('key:generate');

        $this->info('⚡ Clearing caches...');
        Artisan::call('optimize:clear');

        $this->info('✅ Finance API installed successfully!');
        $this->info('👉 Default admin: admin@finance.local / password');

        return Command::SUCCESS;
    }
}