<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable=[
        'Reference',
        'designation',
        'code_categorie',
        'code_fournisseur',
        'prix_unitaire',
        'quantite'
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $primaryKey='code_produit';


    public function Commande()
    {
        return $this->belongsToMany(CommandeDet::class,'code_produit','code_produit');
    }
    public function Fournisseur()
    {
        return $this->belongsTo(Fournisseur::class,'code_fournisseur','code_fournisseur');
    }
    public function Category()
    {
        return $this->belongsTo(Categorie::class,'code_categorie','code_categorie');
    }
}
