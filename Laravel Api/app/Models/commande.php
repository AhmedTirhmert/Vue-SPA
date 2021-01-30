<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class commande extends Model
{
    use HasFactory;
    use SoftDeletes;


    public $timestamps=false;

    protected $primaryKey='numero_cmnd';
    protected $fillable = [
        'id_client',
        'id_etat',
        'id_admin',
        'date_effectue',
        'Message',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];
    public function admin()
    {
        return $this->belongsTo(User::class,'id_admin');
    }
    public function client()
    {
        return $this->belongsTo(User::class,'id_client');
    }
    public function state()
    {
        return $this->hasOne(State::class,'id_etat','id_etat');
    }
    public function Details()
    {
        return $this->hasMany(CommandeDet::class,'numero_cmnd','numero_cmnd');
    }

}
