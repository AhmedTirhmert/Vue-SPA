<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    use HasFactory;


    protected $table='etat_commande';

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];
}
