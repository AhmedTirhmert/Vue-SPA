<?php

namespace Database\Factories;

use App\Models\Payment;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Payment::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'amount' => $this->faker->randomFloat(2,1000,20000),
            'Exp_date' => $this->faker->dateTime('now'),
            'Payed' => $this->faker->boolean(),
            'Banque_id' => $this->faker->numberBetween(1,8),

        ];
    }
}
