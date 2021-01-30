<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];
    protected $fillable = [
        'Exp_date' ,
        'Banque_id',
        'Payed' ,
        'amount'
    ];

    public function Banque()
    {
        return $this->belongsTo(Banque::class,'Banque_id');
    }
}
