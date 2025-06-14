@extends('layouts.app')
@section('contenus')

<div class="container">
  <div class="page-inner">
    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
      <div>
        <h3 class="fw-bold mb-3">Accueil</h3>
        <h6 class="op-7 mb-2">Application de gestion de factures</h6>
      </div>
      <div class="ms-md-auto py-2 py-md-0">
        <a href="{{ url('factures') }}" class="btn btn-label-info btn-round me-2">Facturation</a>
        <a href="{{ url('factures/create') }}" class="btn btn-primary btn-round">Ajout facture</a>
      </div>
    </div>
    <div class="row">
      <div class="col-sm-6 col-md-3">
        <div class="card card-stats card-round">
          <div class="card-body">
            <div class="row align-items-center">
              <div class="col-icon">
                <div class="icon-big text-center icon-primary bubble-shadow-small">
                  <i class="fas fa-file-invoice"></i>
                </div>
              </div>
              <div class="col col-stats ms-3 ms-sm-0">
                <div class="numbers">
                  <p class="card-category">Total Factures</p>
                  <h4 class="card-title">{{ number_format($nombreFactures, 0, ',', ' ') }}</h4>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-sm-6 col-md-3">
        <div class="card card-stats card-round">
          <div class="card-body">
            <div class="row align-items-center">
              <div class="col-icon">
                <div class="icon-big text-center icon-info bubble-shadow-small">
                  <i class="fas fa-money-bill-wave"></i>
                </div>
              </div>
              <div class="col col-stats ms-3 ms-sm-0">
                <div class="numbers">
                  <p class="card-category">Montant Total</p>
                  <h4 class="card-title">{{ number_format($montantTotal, 0, ',', ' ') }} FCFA</h4>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-sm-6 col-md-3">
        <div class="card card-stats card-round">
          <div class="card-body">
            <div class="row align-items-center">
              <div class="col-icon">
                <div class="icon-big text-center icon-success bubble-shadow-small">
                  <i class="fas fa-check-circle"></i>
                </div>
              </div>
              <div class="col col-stats ms-3 ms-sm-0">
                <div class="numbers">
                  <p class="card-category">Factures Payées</p>
                  <h4 class="card-title">{{ number_format($nombreFacturesPayees, 0, ',', ' ') }}</h4>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-sm-6 col-md-3">
        <div class="card card-stats card-round">
          <div class="card-body">
            <div class="row align-items-center">
              <div class="col-icon">
                <div class="icon-big text-center icon-secondary bubble-shadow-small">
                  <i class="fas fa-times-circle"></i>
                </div>
              </div>
              <div class="col col-stats ms-3 ms-sm-0">
                <div class="numbers">
                  <p class="card-category">Factures Impayées</p>
                  <h4 class="card-title">{{ number_format($nombreFacturesImpayees, 0, ',', ' ') }}</h4>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-8">
        <div class="card card-round">
          <div class="card-header">
            <div class="card-head-row">
              <div class="card-title">Statistiques des Factures par Mode de Paiement</div>
              <div class="card-tools">
                <a href="#" class="btn btn-label-success btn-round btn-sm me-2">
                  <span class="btn-label">
                    <i class="fa fa-pencil"></i>
                  </span>
                  Export
                </a>
                <a href="#" class="btn btn-label-info btn-round btn-sm">
                  <span class="btn-label">
                    <i class="fa fa-print"></i>
                  </span>
                  Print
                </a>
              </div>
            </div>
          </div>
          <div class="card-body">
            <div class="chart-container" style="min-height: 375px">
              <canvas id="statisticsChart"></canvas>
            </div>
            <div id="myChartLegend"></div>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card card-primary card-round">
          <div class="card-header">
            <div class="card-head-row">
              <div class="card-title">Statistiques des Impayés</div>
              <div class="card-tools">
                <div class="dropdown">
                  <button class="btn btn-sm btn-label-light dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Export
                  </button>
                  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a class="dropdown-item" href="#">Action</a>
                    <a class="dropdown-item" href="#">Another action</a>
                    <a class="dropdown-item" href="#">Something else here</a>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="card-body pb-0">
            <div class="mb-4 mt-2">
              <h1>{{ number_format($montantImpayes, 0, ',', ' ') }} FCFA</h1>
            </div>
            <div class="pull-in">
              <canvas id="dailySalesChart"></canvas>
            </div>
          </div>
        </div>
        <div class="card card-round">
          <div class="card-body pb-0">
            <div class="h1 fw-bold float-end text-primary">+5%</div>
            <h2 class="mb-2">{{ number_format($nombreFacturesMoisCourant, 0, ',', ' ') }}</h2>
            <p class="text-muted">Factures ce mois-ci</p>
            <div class="pull-in sparkline-fix">
              <div id="lineChart"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-4">
        <div class="card card-round">
          <div class="card-body">
            <div class="card-head-row card-tools-still-right">
              <div class="card-title">Liste des Factures Impayées</div>
              <div class="card-tools">
                <div class="dropdown">
                  <button class="btn btn-icon btn-clean me-0" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-ellipsis-h"></i>
                  </button>
                  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a class="dropdown-item" href="#">Action</a>
                    <a class="dropdown-item" href="#">Another action</a>
                    <a class="dropdown-item" href="#">Something else here</a>
                  </div>
                </div>
              </div>
            </div>
            <div class="card-list py-4">
              @foreach($facturesImpayees as $facture)
              <div class="item-list">
                <div class="info-user ms-3">
                  <div class="username">Facture de {{ $facture->client_nom }} {{ $facture->client_prenom }}</div>
                  <div class="status">Date: {{ $facture->date_facture }}</div>
                  <div class="status">Montant: {{ number_format($facture->montant_ttc, 0, ',', ' ') }} FCFA</div>
                </div>
                <button class="btn btn-icon btn-link op-8 me-1">
                  <i class="far fa-envelope"></i>
                </button>
                <button class="btn btn-icon btn-link btn-danger op-8">
                  <i class="fas fa-ban"></i>
                </button>
              </div>
              @endforeach
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-8">
        <div class="card card-round">
          <div class="card-header">
            <div class="card-head-row card-tools-still-right">
              <div class="card-title">Historique des Transactions</div>
              <div class="card-tools">
                <div class="dropdown">
                  <button class="btn btn-icon btn-clean me-0" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-ellipsis-h"></i>
                  </button>
                  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a class="dropdown-item" href="#">Action</a>
                    <a class="dropdown-item" href="#">Another action</a>
                    <a class="dropdown-item" href="#">Something else here</a>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="card-body p-0">
            <div class="table-responsive">
              <table class="table align-items-center mb-0">
                <thead class="thead-light">
                  <tr>
                    <th scope="col">Numéro de Facture</th>
                    <th scope="col" class="text-end">Date</th>
                    <th scope="col" class="text-end">Montant</th>
                    <th scope="col" class="text-end">Statut</th>
                    <th scope="col" class="text-end">Mode de Paiement</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($facturesRecentes as $facture)
                  <tr>
                    <th scope="row">
                      <button class="btn btn-icon btn-round btn-{{ $facture->statut_paiement == 'payé' ? 'success' : 'danger' }} btn-sm me-2">
                        <i class="fa fa-{{ $facture->statut_paiement == 'payé' ? 'check' : 'times' }}"></i>
                      </button>
                      Facture #{{ $facture->id }}
                    </th>
                    <td class="text-end">{{ $facture->date_facture }}</td>
                    <td class="text-end">{{ number_format($facture->montant_ttc, 0, ',', ' ') }} FCFA</td>
                    <td class="text-end">
                      <span class="badge badge-{{ $facture->statut_paiement == 'payé' ? 'success' : 'danger' }}">{{ $facture->statut_paiement }}</span>
                    </td>
                    <td class="text-end">{{ $facture->mode_paiement }}</td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!--   Core JS Files   -->
<script src="assets/js/core/jquery-3.7.1.min.js"></script>

<!-- Chart JS -->
<script src="assets/js/plugin/chart.js/chart.min.js"></script>

<!-- Chart Circle -->
<script src="assets/js/plugin/chart-circle/circles.min.js"></script>

<!-- Bootstrap Notify -->
<script src="assets/js/plugin/bootstrap-notify/bootstrap-notify.min.js"></script>

<!-- jQuery Vector Maps -->
<script src="assets/js/plugin/jsvectormap/jsvectormap.min.js"></script>
<script src="assets/js/plugin/jsvectormap/world.js"></script>

<!-- Kaiadmin JS -->
<script src="assets/js/kaiadmin.min.js"></script>

<!-- Kaiadmin DEMO methods, don't include it in your project! -->
{{-- <script src="assets/js/demo.js"></script> --}}
<script>
  // Exemple de script pour initialiser les graphiques
  $(document).ready(function() {
    var ctx = document.getElementById('statisticsChart').getContext('2d');
    var statisticsChart = new Chart(ctx, {
      type: 'bar',
      data: {
        labels: ['Carte', 'Chèque', 'Espèces'],
        datasets: [{
          label: 'Montant par Mode de Paiement',
          data: [{{ $montantCarte }}, {{ $montantCheque }}, {{ $montantEspeces }}],
          backgroundColor: [
            'rgba(255, 99, 132, 0.2)',
            'rgba(54, 162, 235, 0.2)',
            'rgba(255, 206, 86, 0.2)'
          ],
          borderColor: [
            'rgba(255, 99, 132, 1)',
            'rgba(54, 162, 235, 1)',
            'rgba(255, 206, 86, 1)'
          ],
          borderWidth: 1
        }]
      },
      options: {
        scales: {
          y: {
            beginAtZero: true
          }
        }
      }
    });

    var ctxDailySales = document.getElementById('dailySalesChart').getContext('2d');
    var dailySalesChart = new Chart(ctxDailySales, {
      type: 'line',
      data: {
        labels: {!! json_encode($labels) !!},
        datasets: [{
          label: 'Évolution des Impayés',
          data: {!! json_encode($data) !!},
          backgroundColor: 'rgba(75, 192, 192, 0.2)',
          borderColor: 'rgba(75, 192, 192, 1)',
          borderWidth: 1
        }]
      },
      options: {
        scales: {
          y: {
            beginAtZero: true
          }
        }
      }
    });
  });
</script>
@endsection
