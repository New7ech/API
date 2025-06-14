<nav class="navbar navbar-header navbar-header-transparent navbar-expand-lg border-bottom">
    <div class="container-fluid">
        <nav class="navbar navbar-header-left navbar-expand-lg navbar-form nav-search p-0 d-none d-lg-flex">
            <!-- Navigation search form can be placed here -->
        </nav>
        <ul class="navbar-nav topbar-nav ms-md-auto align-items-center">

            @include('layouts.notification')

            <li class="nav-item topbar-user dropdown hidden-caret">
                <a class="dropdown-toggle profile-pic" data-bs-toggle="dropdown" href="#" aria-expanded="false">
                    <div class="avatar-sm">
                        {{-- <img src="{{ Auth::user()->photo ? asset('storage/' . Auth::user()->photo) : asset('assets/img/profile1.png') }}" alt="Image de Profil" class="avatar-img rounded-circle" /> --}}
                    </div>
                    <span class="profile-username">
                        {{-- <span class="fw-bold">{{ Auth::user()->name ?? 'Utilisateur' }}</span> --}}
                    </span>
                </a>
                <ul class="dropdown-menu dropdown-user animated fadeIn">
                    <div class="dropdown-user-scroll scrollbar-outer">
                        <li>
                            <div class="user-box d-flex align-items-center">
                                <div class="avatar-lg me-3">
                                     {{-- <img src="{{ Auth::user()->photo ? asset('storage/' . Auth::user()->photo) : asset('assets/img/profile1.png') }}" alt="Image de Profil" class="avatar-img rounded" /> --}}
                                </div>
                                <div class="u-text">
                                    {{-- <h4>{{ Auth::user()->name ?? 'Utilisateur' }}</h4> --}}
                                    {{-- <p class="text-muted mb-1">{{ Auth::user()->email ?? '' }}</p> --}}
                                    {{-- @if(Auth::user()) --}}
                                    {{-- <a href="{{ route('users.show', Auth::user()->id) }}" class="btn btn-xs btn-secondary btn-sm"> --}}
                                        <i class="fas fa-user-circle"></i> Mon Profil
                                    </a>
                                    {{-- @endif --}}
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="#"> <!-- Placeholder for user settings/activity log -->
                                <i class="fas fa-cog me-2"></i> Paramètres du compte
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="#"
                            {{-- <a class="dropdown-item" href="{{ route('logout') }}" --}}
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                               <i class="fas fa-sign-out-alt me-2"></i> Déconnexion
                            </a>
                            <form id="logout-form" action="#" method="POST" class="d-none">
                            {{-- <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none"> --}}
                                @csrf
                            </form>
                        </li>
                    </div>
                </ul>
            </li>
        </ul>
    </div>
</nav>
