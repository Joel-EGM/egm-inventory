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

        Branch::factory()->count(1)->create([
            'branch_name' => 'HO',
            'branch_address' => 'Cabanatuan',
            'branch_contactNo' => '12345678',
        ]);
        Item::factory()->count(1)->create([
            'item_name' => 'bond paper',
            'unit_name' => 'rim',
            'branch_name' => 'HO',
        ]);
        Supplier::factory()->count(10)->create();
    }
}
