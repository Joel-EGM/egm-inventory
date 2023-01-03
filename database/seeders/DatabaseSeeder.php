<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Branch;
use App\Models\Item;
use App\Models\ItemPrice;
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
            'pieces_perUnit' => '200',
        ]);
        Supplier::factory()->count(1)->create([
            'suppliers_name' => 'National Bookstrore',
            'suppliers_email' => 'nation@l.com',
            'suppliers_contact' => '098765432',
        ]);
        ItemPrice::factory()->count(1)->create([
            'supplier_id' => '1',
            'item_id' => '1',
            'price_perUnit' => '1000',
            'price_perPieces' => '10',
        ]);
    }
}
