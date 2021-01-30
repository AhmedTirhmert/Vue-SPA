<?php

namespace Database\Factories;

use App\Models\EmployeePayment;
use App\Models\Employee;


use Illuminate\Database\Eloquent\Factories\Factory;

class EmployeePaymentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = EmployeePayment::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $id=$this->faker->numberBetween(1,50);
        return [
            'employee_id' => $id,
            'credit' => $this->faker->numberBetween(100,Employee::find($id)->salary),
            'debut' => Employee::find($id)->salary,
            'date' => $this->faker->date(),
        ];
    }
}
