<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Branch;
use App\Models\Item;
use App\Models\Supplier;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        User::factory()->create([
            'name' => 'egm',
            'email' => 'e@g.com',
            'password' => '$2y$10$hg3Pn45ZiQlaD./r8RfEQeQvFrhAmmkL0exf7DWpk32g9t0.RGeKu',
        ]);

        Branch::factory()->count(10)->create();
        Item::factory()->count(10)->create([
            'supplier_id' => '1',
        ]);
        Supplier::factory()->count(10)->create();
    }
}
