<?php

namespace Database\Seeders;

use App\Models\Property;
use App\Models\SearchProfile;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // seed all the tables needed for test case
        Property::create([
            'name' => 'Awesome house in the middle of my town',
            'address' => 'Main street 17, 12456 Berlin',
            'property_type' => 'd44d0090-a2b5-47f7-80bb-d6e6f85fca90',
            'fields' => json_encode([
                'area' => '180',
                'yearOfConstruction' => '2010',
                'rooms' => '5',
                'heatingType' => 'gas',
                'parking' => true,
                'returnActual' => '12.8',
                'rent' => '3750'
            ]),
        ]);

        Property::factory()->count(4)->create();

        SearchProfile::create([
            'name' => 'Looking for any Awesome realestate!',
            'property_type' => 'd44d0090-a2b5-47f7-80bb-d6e6f85fca90',
            'search_fields' => json_encode([
                'price' => ['0', '2000000'],
                'area' => ['150', null],
                'yearOfConstruction' => ['2010', null],
                'rooms' => ['4', null],
            ]),
            'return_potential' => json_encode(['15', null]),
        ]);

        SearchProfile::factory()->count(10)->create();
    }
}
