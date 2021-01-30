<?php

namespace Database\Factories;

use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;

class EmployeeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Employee::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'salary' => $this->faker->numberBetween(1000,9000),
            'name' => $this->faker->name(),
            'phone' => $this->faker->unique()->e164PhoneNumber(),
            'cin' => $this->faker->regexify("([A-Z]){2}([0-9]){5}"),
            'work_place' => $this->faker->address(),
        ];
    }
}
