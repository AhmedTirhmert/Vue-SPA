<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];
    protected $fillable = [
        'name',
        'cin',
        'phone',
        'salary',
        'work_place',
    ];


    public function Payment()
    {
        return $this->hasMany(EmployeePayment::class);
    }
}
