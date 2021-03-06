<?php

namespace Database\Factories;

use App\Models\Property;
use Illuminate\Database\Eloquent\Factories\Factory;
use Ramsey\Uuid\Uuid;

class PropertyFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Property::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'address' => $this->faker->address(),
            'property_type' => Uuid::uuid4()->toString(),
            'fields' => function () {
                $testFields = ['area', 'yearOfConstruction', 'rooms', 'heatingType', 'parking', 'returnActual', 'rent'];
                $factoryFields = [];
                foreach ($testFields as $field) {
                    if($field === 'area') {
                        $factoryFields['area'] = '' .$this->faker->numberBetween(100,999). '';
                    }
                    if($field === 'yearOfConstruction') {
                        $factoryFields['yearOfConstruction'] = '' .$this->faker->numberBetween(1999,2022).'';
                    }
                    if($field === 'rooms') {
                        $factoryFields['rooms'] = ''.$this->faker->numberBetween(1,20).'';
                    }
                    if($field === 'heatingType') {
                        $factoryFields['heatingType'] = $this->faker->randomElement(['gas', 'electric']);
                    }
                    if($field === 'parking') {
                        $factoryFields['parking'] = $this->faker->randomElement([true, false]);
                    }
                    if($field === 'returnActual') {
                        $factoryFields['returnActual'] = ''.$this->faker->randomFloat(1,10.5, 99.9).'';
                    }
                    if($field === 'rent') {
                        $factoryFields['rent'] = ''.$this->faker->numberBetween(1000,9999).'';
                    }
                }
                return json_encode($factoryFields);
            }
        ];
    }
}
