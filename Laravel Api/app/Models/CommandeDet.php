<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommandeDet extends Model
{
    use HasFactory;
    public $timestamps=false;

    protected $fillable = [
        'code_produit',
        'numero_cmnd',
        'qte_cmnd',
    ];


    protected $table='concerne';


    public function Commande()
    {
        return $this->hasMany(Commande::class,'numero_cmnd','numero_cmnd');
    }
    public function Product()
    {
        return $this->hasMany(Product::class,'code_produit','code_produit');
    }
    public function Category()
    {
        return $this->hasMany(Categorie::class,'code_categorie','code_categorie');
    }
}
