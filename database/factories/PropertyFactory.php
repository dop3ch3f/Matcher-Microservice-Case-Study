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
                        $factoryFields[] = '' .$this->faker->numberBetween(100,999). '';
                    }
                    if($field === 'yearOfConstruction') {
                        $factoryFields[] = ''.$this->faker->numberBetween(1999,2022).'';
                    }
                    if($field === 'rooms') {
                        $factoryFields[] = ''.$this->faker->numberBetween(1,20).'';
                    }
                    if($field === 'heatingType') {
                        $factoryFields[] = $this->faker->randomElement(['gas', 'electric']);
                    }
                    if($field === 'parking') {
                        $factoryFields[] = $this->faker->randomElement([true, false]);
                    }
                    if($field === 'returnActual') {
                        $factoryFields[] = ''.$this->faker->randomFloat(1,10.5, 99.9).'';
                    }
                    if($field === 'rent') {
                        $factoryFields[] = ''.$this->faker->numberBetween(1000,9999).'';
                    }
                }
                return json_encode($factoryFields);
            }
        ];
    }
}
