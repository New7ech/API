<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Accueil;
use App\Models\Article;
use App\Models\Facture;
use App\Models\Fournisseur;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAccueilRequest;
use App\Http\Requests\UpdateAccueilRequest;

class AccueilController extends Controller
{
    /**
     * Display a listing of the resource.
     */
public function index()
{
    // Récupérer toutes les factures
    $factures = Facture::all();

    // Calculer le nombre total de factures
    $nombreFactures = $factures->count();

    // Calculer le montant total des factures
    // $montantTotal = $factures->sum('montant_ttc');

    // Calculer le montant total des factures par mois
    $montantTotal = $factures->filter(function ($facture) {
        $dateFacture = Carbon::parse($facture->date_facture);
        return $dateFacture->year == now()->year && $dateFacture->month == now()->month;
    })->sum('montant_ttc');

    // Calculer le nombre de factures payées
    $nombreFacturesPayees = $factures->where('statut_paiement', 'payé')->count();

    // Calculer le nombre de factures impayées
    $nombreFacturesImpayees = $factures->where('statut_paiement', 'impayé')->count();

    // Calculer le montant total des factures impayées
    $montantImpayes = $factures->where('statut_paiement', 'impayé')->sum('montant_ttc');

    // Calculer le nombre de factures pour le mois courant
    $nombreFacturesMoisCourant = $factures->filter(function ($facture) {
        $dateFacture = Carbon::parse($facture->date_facture);
        return $dateFacture->year == now()->year && $dateFacture->month == now()->month;
    })->count();

    // Calculer le montant total par mode de paiement
    $montantCarte = $factures->where('mode_paiement', 'carte')->sum('montant_ttc');
    $montantCheque = $factures->where('mode_paiement', 'chèque')->sum('montant_ttc');
    $montantEspeces = $factures->where('mode_paiement', 'espèces')->sum('montant_ttc');

    // Récupérer les factures impayées
    $facturesImpayees = $factures->where('statut_paiement', 'impayé');

    // Récupérer les factures récentes (par exemple, les 10 dernières)
    $facturesRecentes = $factures->sortByDesc(function ($facture) {
        return Carbon::parse($facture->date_facture);
    })->take(10);

    // Calculer les données pour le graphique d'évolution des impayés
    $labels = [];
    $data = [];
    for ($i = 1; $i <= 12; $i++) {
        $labels[] = date('M', mktime(0, 0, 0, $i, 1));
        $data[] = $factures->filter(function ($facture) use ($i) {
            $dateFacture = Carbon::parse($facture->date_facture);
            return $dateFacture->month == $i && $facture->statut_paiement == 'impayé';
        })->sum('montant_ttc');
    }

    // Passer les variables à la vue
    return view('welcome', compact(
        'nombreFactures',
        'montantTotal',
        'nombreFacturesPayees',
        'nombreFacturesImpayees',
        'montantImpayes',
        'nombreFacturesMoisCourant',
        'montantCarte',
        'montantCheque',
        'montantEspeces',
        'facturesImpayees',
        'facturesRecentes',
        'labels',
        'data'
    ));
}














    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAccueilRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Accueil $accueil)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Accueil $accueil)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAccueilRequest $request, Accueil $accueil)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Accueil $accueil)
    {
        //
    }
}
