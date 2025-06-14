@extends('layouts.app')

@section('contenus')
<div class="container-fluid mt-4">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h1 class="card-title mb-0">Notifications</h1>
            @if(Auth::user()->unreadNotifications->isNotEmpty())
            <form action="{{ route('notifications.markAllAsRead') }}" method="POST" class="mb-0">
                @csrf
                <button type="submit" class="btn btn-sm btn-outline-primary">Marquer toutes comme lues</button>
            </form>
            @endif
        </div>
        <div class="card-body">
            @if($notifications->isEmpty())
                <p class="text-center text-muted">Vous n'avez aucune notification.</p>
            @else
                <ul class="list-group list-group-flush">
                    @foreach($notifications as $notification)
                        <li class="list-group-item d-flex justify-content-between align-items-center @if(!$notification->read_at) list-group-item-warning @endif">
                            <div>
                                <a href="{{ route('notifications.show', $notification->id) }}" class="text-decoration-none">
                                    <p class="mb-1">{{ $notification->data['message'] }}</p>
                                </a>
                                <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                            </div>
                            @if(!$notification->read_at)
                                <form action="{{ route('notifications.markAsRead', $notification->id) }}" method="POST" class="mb-0">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-success">Marquer comme lue</button>
                                </form>
                            @else
                                <span class="badge bg-success text-white">Lue</span>
                            @endif
                        </li>
                    @endforeach
                </ul>

                @if($notifications->hasPages())
                    <div class="mt-3">
                        {{ $notifications->links() }}
                    </div>
                @endif
            @endif
        </div>
    </div>
</div>
@endsection