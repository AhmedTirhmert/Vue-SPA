<?php

namespace Database\Factories;

use App\Models\Banque;
use Illuminate\Database\Eloquent\Factories\Factory;

class BanqueFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Banque::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $banques=[
            'Attijariwafa Bank',
            'Banque Populaire',
            'BMCE',
            'Société Générale',
            'BMCI',
            'Crédit Agricole',
            'Crédit du Maroc',
            'CIH Bank',
        ];
        return [
            'name' => $banques[$this->faker->unique()->numberBetween(1,8)-1]
        ];
    }
}
