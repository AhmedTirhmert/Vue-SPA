<?php

namespace Database\Seeders;

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
        // \App\Models\UserType::factory(2)->create();
        // \App\Models\User::factory(100)->create();
        //  \App\Models\Banque::factory(8)->create();
        //  \App\Models\Payment::factory(30)->create();
        //  \App\Models\Employee::factory(50)->create();
         \App\Models\EmployeePayment::factory(250)->create();
    }
}
