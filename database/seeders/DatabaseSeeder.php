<?php

namespace Database\Seeders;

use App\Models\Fund;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $fundTitle = 'Cushon Equities Fund';
        Fund::create(['name' => $fundTitle, 'handle' => Str::slug($fundTitle)]);
    }
}
