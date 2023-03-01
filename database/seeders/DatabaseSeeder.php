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
            'role' => 'admin',
            'branch_id' => '1',
        ]);

        User::factory()->create([
            'name' => 'joel',
            'email' => 'j@g.com',
            'password' => '$2y$10$hg3Pn45ZiQlaD./r8RfEQeQvFrhAmmkL0exf7DWpk32g9t0.RGeKu',
            'role' => 'user',
            'branch_id' => '2',
        ]);

        Branch::factory()->count(1)->create([
            'branch_name' => 'HO',
            'branch_address' => 'Cabanatuan',
            'branch_contactNo' => '12345678',
        ]);
        Branch::factory()->count(1)->create([
            'branch_name' => 'Gapan',
            'branch_address' => 'San Nicolas',
            'branch_contactNo' => '12345678',
        ]);

        Item::factory()->count(1)->create([
            'item_name' => 'bond paper',
            'unit_name' => 'rim',
            'pieces_perUnit' => '200',
            'fixed_unit' => '1',
        ]);
        Item::factory()->count(1)->create([
            'item_name' => 'ballpen',
            'unit_name' => 'box',
            'pieces_perUnit' => '100',
        ]);

        Supplier::factory()->count(1)->create([
            'suppliers_name' => 'National Bookstore',
            'suppliers_email' => 'nation@l.com',
            'suppliers_contact' => '098765432',
        ]);
        Supplier::factory()->count(1)->create([
            'suppliers_name' => 'Pacific',
            'suppliers_email' => 'pacifc@g.com',
            'suppliers_contact' => '0982374629',
        ]);

        ItemPrice::factory()->count(1)->create([
            'supplier_id' => '1',
            'item_id' => '1',
            'price_perUnit' => '1000',
            'price_perPieces' => '10',
        ]);
        ItemPrice::factory()->count(1)->create([
            'supplier_id' => '1',
            'item_id' => '2',
            'price_perUnit' => '300',
            'price_perPieces' => '5',
        ]);
        ItemPrice::factory()->count(1)->create([
            'supplier_id' => '2',
            'item_id' => '1',
            'price_perUnit' => '1500',
            'price_perPieces' => '20',
        ]);
        ItemPrice::factory()->count(1)->create([
            'supplier_id' => '2',
            'item_id' => '2',
            'price_perUnit' => '250',
            'price_perPieces' => '17',
        ]);
    }
}
