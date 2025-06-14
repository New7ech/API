<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fournisseur extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'fournisseurs';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
        'description',
        'nom_entreprise',
        'adresse',
        'telephone',
        'email',
        'ville',
        'pays',
    ];
}
