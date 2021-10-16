<?php

namespace Database\Factories;

use App\Models\SearchProfile;
use Illuminate\Database\Eloquent\Factories\Factory;
use Ramsey\Uuid\Uuid;

class SearchProfileFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SearchProfile::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'property_type' => Uuid::uuid4()->toString(),
            'search_fields' => json_encode([
                    'area' => $this->generateSearchField('' .$this->faker->numberBetween(100,999). ''),
                    'yearOfConstruction' => $this->generateSearchField('' .$this->faker->numberBetween(1999,2022). ''),
                    'rooms' => $this->generateSearchField('' .$this->faker->numberBetween(1,20). ''),
                    'heatingType' => $this->generateSearchField($this->faker->randomElement(['gas', 'electric'])),
                    'parking' => $this->generateSearchField($this->faker->randomElement([true, false])),
                    'rent' => $this->generateSearchField(''.$this->faker->numberBetween(1000,9999).''),
                ]),
            'return_potential' => json_encode($this->generateSearchField(''.$this->faker->randomFloat(1,10.5, 99.9).'')),
        ];
    }

    public function generateSearchField($value): array
    {
        return [
            $this->faker->randomElement([$value, null]),
            $this->faker->randomElement([$value, null]),
            ];
    }
}
