<div class="sidebar sidebar-style-2" data-background-color="dark">
    <div class="sidebar-logo">
      <!-- Logo Header -->
      @include('layouts.logoheader')
      <!-- End Logo Header -->
    </div>
    <div class="sidebar-wrapper scrollbar scrollbar-inner">
      <div class="sidebar-content">
        <ul class="nav nav-secondary">

          <li class="nav-item">
            <a href="{{ url('/') }}">
                <i class="fas fa-home"></i>
                <p>Accueil</p>
            </a>
          </li>

          <li class="nav-section">
            <span class="sidebar-mini-icon">
              <i class="fa fa-ellipsis-h"></i>
            </span>
            <h4 class="text-section">Menu Principal</h4>
          </li>

            {{-- @if (auth()->user()->hasRole('compagnie')) --}}

              {{-- @elseif (auth()->user()->hasRole('admin')) --}}
                <li class="nav-item">
                    <a data-bs-toggle="collapse" href="#demande">
                        <i class="fas fa-plus"></i>
                    <p>Catalogue & Article</p>
                    <span class="caret"></span>
                    </a>
                    <div class="collapse" id="demande">
                    <ul class="nav nav-collapse">
                        <li>
                            <a href="{{ route('articles.create') }}">
                                <i class="fas fa-cart-plus"></i>
                                <p>Ajouter un article</p>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('articles.index') }}">
                                <i class="fas fa-edit"></i>
                                <p>Gérer les articles</p>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('categories.index') }}">
                                <i class="fas fa-tags"></i> {{-- Changed icon --}}
                                <p>Catégories</p>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('emplacements.index') }}">
                                <i class="fas fa-map-marker-alt"></i> {{-- Changed icon --}}
                                <p>Emplacements</p>
                            </a>
                        </li>

                    </ul>
                    </div>
                </li>

                <li class="nav-item">
                    <a data-bs-toggle="collapse" href="#fournisseur">
                        <i class="fas fa-shipping-fast"></i>
                    <p>Fournisseur</p>
                    <span class="caret"></span>
                    </a>
                    <div class="collapse" id="fournisseur">
                    <ul class="nav nav-collapse">
                        <li>
                            <a href="{{ route('fournisseurs.create') }}">
                                <i class="fas fa-user-plus"></i> {{-- Changed icon --}}
                                <p>Ajouter un fournisseur</p>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('fournisseurs.index') }}">
                                <i class="fas fa-users-cog"></i> {{-- Changed icon --}}
                                <p>Gérer les fournisseurs</p>
                            </a>
                        </li>

                    </ul>
                    </div>
                </li>

                <li class="nav-item">
                    <a data-bs-toggle="collapse" href="#facture">
                        <i class="fas fa-money-check-alt"></i>
                    <p>Facture</p>
                    <span class="caret"></span>
                    </a>
                    <div class="collapse" id="facture">
                    <ul class="nav nav-collapse">
                        <li>
                            <a href="{{ route('factures.create') }}">
                                <i class="fas fa-file-invoice-dollar"></i> {{-- Changed icon --}}
                                <p>Créer une facture</p>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('factures.index') }}">
                                <i class="fas fa-folder-open"></i> {{-- Changed icon --}}
                                <p>Gérer les factures</p>
                            </a>
                        </li>

                    </ul>
                    </div>
                </li>


            {{-- @endif --}}

            {{-- @if (auth()->user()->hasRole('admin')) --}}
              <li class="nav-item">
                <a href="{{ route('statistiques.index') }}">
                  <i class="fas fa-chart-line"></i>
                    <p>Statistiques</p>
                </a>
              </li>

            {{-- @if (auth()->user()->hasRole('admin')) --}}
            <li class="nav-section">
                <span class="sidebar-mini-icon">
                  <i class="fa fa-ellipsis-h"></i>
                </span>
                <h4 class="text-section">Administration</h4>
            </li>
              <li class="nav-item">
                  <a data-bs-toggle="collapse" href="#administration-menu"> {{-- Changed ID for clarity --}}
                      <i class="fas fa-cogs"></i> {{-- Changed icon --}}
                  <p>Paramètres</p>
                  <span class="caret"></span>
                  </a>
                  <div class="collapse" id="administration-menu">
                    <ul class="nav nav-collapse">
                        <li class="nav-item">
                            <a data-bs-toggle="collapse" href="#users-submenu"> {{-- Changed ID --}}
                                <i class="fas fa-users"></i>
                                <p>Utilisateurs</p>
                                <span class="caret"></span>
                            </a>
                            <div class="collapse" id="users-submenu">
                                <ul class="nav nav-collapse">
                                <li>
                                    <a href="{{ route('users.index') }}">
                                        <p>Liste des Utilisateurs</p>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('users.create') }}">
                                        <p>Créer un Utilisateur</p>
                                    </a>
                                </li>
                                </ul>
                            </div>
                        </li>
                        <li class="nav-item">
                            <a data-bs-toggle="collapse" href="#roles-submenu"> {{-- Changed ID --}}
                                <i class="fas fa-user-shield"></i>
                                <p>Rôles</p>
                                <span class="caret"></span>
                            </a>
                            <div class="collapse" id="roles-submenu">
                                <ul class="nav nav-collapse">
                                <li>
                                    <a href="{{ route('roles.index') }}">
                                        <p>Liste des Rôles</p>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('roles.create') }}">
                                        <p>Créer un Rôle</p>
                                    </a>
                                </li>
                                </ul>
                            </div>
                        </li>
                        <li class="nav-item">
                            <a data-bs-toggle="collapse" href="#permissions-submenu"> {{-- Changed ID --}}
                                <i class="fas fa-key"></i> {{-- Changed icon --}}
                                <p>Permissions</p>
                                <span class="caret"></span>
                            </a>
                            <div class="collapse" id="permissions-submenu">
                                <ul class="nav nav-collapse">
                                <li>
                                    <a href="{{ route('permissions.index') }}">
                                        <p>Liste des Permissions</p>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('permissions.create') }}">
                                        <p>Créer une Permission</p>
                                  </a>
                              </li>
                              </ul>
                          </div>
                      </li>
                  </ul>
                  </div>
              </li>
            {{-- @endif --}}
        </ul>
      </div>
    </div>
  </div>
