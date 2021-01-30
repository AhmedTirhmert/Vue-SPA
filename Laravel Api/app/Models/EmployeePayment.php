<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeePayment extends Model
{
    use HasFactory;
    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];
    protected $fillable = [
        'employee_id',
        'credit',
        'debut',
        'date'
    ];
    public function Employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
