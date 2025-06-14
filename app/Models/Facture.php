<?php

namespace App\Models;

use App\Models\Article;
use App\Models\Client;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Facture extends Model
{
    use HasFactory, SoftDeletes;
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $table = 'factures';
    protected $primaryKey = 'id';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'client_nom',
        'client_prenom',
        'client_adresse',
        'client_telephone',
        'client_email',
        'numero',
        'date_facture',
        'montant_ht',
        'tva',
        'montant_ttc',
        'statut_paiement',
        'date_paiement',
        'mode_paiement',
        'quantity',
        'prix_unitaire',
        'date',
    ];

    public function articles()
    {
        return $this->belongsToMany(Article::class, 'article_facture')
                    ->withPivot('quantite', 'prix_unitaire')
                    ->withTimestamps();
    }


    public function scopeSearch($query, $search)
    {
        return $query->where('client_id', 'like', "%{$search}%")
            ->orWhere('produit_id', 'like', "%{$search}%")
            ->orWhere('quantite', 'like', "%{$search}%")
            ->orWhere('prix_unitaire', 'like', "%{$search}%")
            ->orWhere('date_facture', 'like', "%{$search}%")
            ->orWhere('statut', 'like', "%{$search}%");
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where('client_id', 'like', "%{$search}%")
                ->orWhere('produit_id', 'like', "%{$search}%")
                ->orWhere('quantite', 'like', "%{$search}%")
                ->orWhere('prix_unitaire', 'like', "%{$search}%")
                ->orWhere('date_facture', 'like', "%{$search}%")
                ->orWhere('statut', 'like', "%{$search}%");
        });
    }
}
