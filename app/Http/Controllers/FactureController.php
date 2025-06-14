<?php

namespace App\Http\Controllers;

use App\Models\Facture;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Notifications\StockLowNotification;
use Illuminate\Support\Facades\Notification;
use App\Models\User;

class FactureController extends Controller
{
    public function index(Request $request)
    {
        $query = Facture::query();

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('id', 'like', "%{$search}%")
                  ->orWhere('statut_paiement', 'like', "%{$search}%");
        }

        $factures = $query->paginate(15);

        return view('factures.index', compact('factures'));
    }

    public function create()
    {
        return view('factures.create', [
            'articles' => Article::all(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_nom' => 'required|string|max:255', // <-- rendre obligatoire
            'client_prenom' => 'nullable|string|max:255',
            'client_adresse' => 'nullable|string|max:255',
            'client_telephone' => 'nullable|string|max:20',
            'client_email' => 'nullable|email|max:255',
            'statut_paiement' => 'required|in:impayé,payé',
            'mode_paiement' => 'nullable|string',
            'articles' => 'required|array',
            'articles.*.article_id' => 'required|exists:articles,id',
            'articles.*.quantity' => 'required|integer|min:1',
        ]);

        DB::beginTransaction();

        try {
            $articlesData = $validated['articles'];
            $montantHTTotal = 0;
            $details = [];

            foreach ($articlesData as $index => $item) {
                $article = Article::findOrFail($item['article_id']);

                if ($item['quantity'] > $article->quantite) {
                    DB::rollBack();
                    return redirect()->back()
                        ->withErrors(["articles.{$index}.quantity" => "Quantité en stock insuffisante pour l'article {$article->name}."])
                        ->withInput();
                }

                $prixUnitaire = $article->prix;
                $ligneHT = $prixUnitaire * $item['quantity'];

                $details[] = [
                    'article' => $article,
                    'quantity' => $item['quantity'],
                    'prix_unitaire' => $prixUnitaire,
                    'montant_ht' => $ligneHT,
                ];

                $montantHTTotal += $ligneHT;
            }

            $tva = 18;
            $montantTTC = $montantHTTotal * 1.18;

            // Générer un numéro de facture
            $numero = 'FAC-' . date('Y') . '-' . str_pad(Facture::withTrashed()->count() + 1, 4, '0', STR_PAD_LEFT);

            $facture = Facture::create([
                'client_nom' => $validated['client_nom'],
                'client_prenom' => $validated['client_prenom'],
                'client_adresse' => $validated['client_adresse'],
                'client_telephone' => $validated['client_telephone'],
                'client_email' => $validated['client_email'],
                'numero' => $numero, // Inclure le numéro de facture
                'date_facture' => now(),
                'montant_ht' => $montantHTTotal,
                'tva' => $tva,
                'montant_ttc' => $montantTTC,
                'mode_paiement' => $validated['mode_paiement'],
                'statut_paiement' => $validated['statut_paiement'],
                'date_paiement' => $validated['statut_paiement'] === 'payé' ? now() : null,
            ]);

            foreach ($details as $d) {
                $facture->articles()->attach($d['article']->id, [
                    'quantite' => $d['quantity'],
                    'prix_unitaire' => $d['prix_unitaire'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $d['article']->decrement('quantite', $d['quantity']);

                if ($d['article']->fresh()->quantite < 5) {
                    Notification::send(User::all(), new StockLowNotification($d['article'], 5));
                }
            }

            $pdf = Pdf::loadView('factures.pdf', [
                'facture' => $facture,
                'details' => $details,
            ]);

            DB::commit();
            return $pdf->download("Facture_{$facture->id}.pdf");

        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            Log::error('Article non trouvé', ['error' => $e->getMessage()]);
            return redirect()->back()->withErrors(['article_id' => 'Article introuvable !']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur création facture : ' . $e->getMessage());
            return redirect()->back()->withErrors(['general' => 'Une erreur est survenue : ' . $e->getMessage()]);
        }
    }


    public function show(Facture $facture)
    {
        return view('factures.show', compact('facture'));
    }

    public function edit(Facture $facture)
    {
        return view('factures.edit', [
            'facture' => $facture,
            'articles' => Article::all(),
        ]);
    }

    public function update(Request $request, Facture $facture)
    {
        $validated = $request->validate([
            'client_nom' => 'nullable|string|max:255',
            'client_prenom' => 'nullable|string|max:255',
            'client_adresse' => 'nullable|string|max:255',
            'client_telephone' => 'nullable|string|max:20',
            'client_email' => 'nullable|email|max:255',
            'statut_paiement' => 'required|in:impayé,payé',
            'mode_paiement' => 'nullable|string',
            'articles' => 'nullable|array',
            'articles.*.article_id' => 'required|exists:articles,id',
            'articles.*.quantity' => 'required|integer|min:1',
        ]);

        DB::beginTransaction();

        try {
            $articlesData = $validated['articles'];
            $montantHTTotal = 0;
            $details = [];

            foreach ($articlesData as $index => $item) {
                $article = Article::findOrFail($item['article_id']);

                if ($item['quantity'] > $article->quantite) {
                    DB::rollBack();
                    return redirect()->back()
                        ->withErrors(["articles.{$index}.quantity" => "Quantité en stock insuffisante pour l'article {$article->name}."])
                        ->withInput();
                }

                $prixUnitaire = $article->prix;
                $ligneHT = $prixUnitaire * $item['quantity'];

                $details[] = [
                    'article' => $article,
                    'quantity' => $item['quantity'],
                    'prix_unitaire' => $prixUnitaire,
                    'montant_ht' => $ligneHT,
                ];

                $montantHTTotal += $ligneHT;
            }

            $tva = 18;
            $montantTTC = $montantHTTotal * 1.18;

            $facture->update([
                'client_nom' => $validated['client_nom'] ?? $facture->client_nom,
                'client_prenom' => $validated['client_prenom'] ?? $facture->client_prenom,
                'client_adresse' => $validated['client_adresse'] ?? $facture->client_adresse,
                'client_telephone' => $validated['client_telephone'] ?? $facture->client_telephone,
                'client_email' => $validated['client_email'] ?? $facture->client_email,
                'montant_ht' => $montantHTTotal,
                'tva' => $tva,
                'montant_ttc' => $montantTTC,
                'mode_paiement' => $validated['mode_paiement'] ?? $facture->mode_paiement,
                'statut_paiement' => $validated['statut_paiement'] ?? $facture->statut_paiement,
                'date_paiement' => ($validated['statut_paiement'] ?? $facture->statut_paiement) === 'payé' ? now() : null,
            ]);

            $facture->articles()->detach();

            foreach ($details as $d) {
                $facture->articles()->attach($d['article']->id, [
                    'quantite' => $d['quantity'],
                    'prix_unitaire' => $d['prix_unitaire'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $d['article']->decrement('quantite', $d['quantity']);

                if ($d['article']->fresh()->quantite < 5) {
                    Notification::send(User::all(), new StockLowNotification($d['article'], 5));
                }
            }

            DB::commit();
            return redirect()->route('factures.index')->with('success', 'Facture mise à jour avec succès.');

        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            Log::error('Article non trouvé', ['error' => $e->getMessage()]);
            return redirect()->back()->withErrors(['article_id' => 'Article introuvable !']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur mise à jour facture : ' . $e->getMessage());
            return redirect()->back()->withErrors(['general' => 'Une erreur est survenue : ' . $e->getMessage()]);
        }
    }

    public function destroy(Facture $facture)
    {
        $facture->delete();
        return redirect()->route('factures.index')->with('success', 'Facture supprimée avec succès.');
    }

    public function genererPdf(Facture $facture)
    {
        // Recharge la facture pour avoir les dernières données
        $facture = Facture::with('articles')->findOrFail($facture->id);

        // Prepare details similar to how it might be done in the 'store' or 'show' method if needed by the PDF view
        // For example, if 'details' array was used in the original PDF generation:
        $details = [];
        $montantHTTotal = 0;
        foreach ($facture->articles as $articlePivot) {
            // Assuming 'prix_unitaire' and 'quantite' are stored in the pivot table 'article_facture'
            $prixUnitaire = $articlePivot->pivot->prix_unitaire;
            $quantity = $articlePivot->pivot->quantite;
            $ligneHT = $prixUnitaire * $quantity;

            $details[] = [
                'article' => $articlePivot, // The Article model itself
                'quantity' => $quantity,
                'prix_unitaire' => $prixUnitaire,
                'montant_ht' => $ligneHT,
            ];
            $montantHTTotal += $ligneHT;
        }

        // The PDF view might also expect calculated totals directly from the $facture model
        // if they are stored on the factures table (e.g., $facture->montant_ht, $facture->montant_ttc)
        // Adjust the data passed to the view based on what `factures.pdf.blade.php` expects.

        $pdf = Pdf::loadView('factures.pdf', [
            'facture' => $facture,
            'details' => $details, // Pass this if your PDF view iterates over 'details'
                                // If not, and it uses $facture->articles directly, ensure that relationship is correctly structured
        ]);

        return $pdf->download('Facture_' . ($facture->numero ? $facture->numero : $facture->id) . '.pdf');
    }
}
