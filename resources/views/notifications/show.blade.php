@extends('layouts.app')

@section('contenus')
<div class="container">
    <h1>Détails de la Notification</h1>

    @if ($notification)
        <div class="card">
            <div class="card-header">
                Détails de la Notification
            </div>
            <div class="card-body">
                <p><strong>Message:</strong> {{ $notification->data['message'] }}</p>
                <p><strong>Reçue le:</strong> {{ $notification->created_at->format('d/m/Y H:i:s') }} ({{ $notification->created_at->diffForHumans() }})</p>
                <p><strong>Statut:</strong> 
                    @if($notification->read_at)
                        Lue le {{ $notification->read_at->format('d/m/Y H:i:s') }}
                    @else
                        Non lue
                    @endif
                </p>

                @if(!$notification->read_at)
                    <form action="{{ route('notifications.markAsRead', $notification->id) }}" method="POST" class="mb-3">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-success">Marquer comme lue</button>
                    </form>
                @endif

                <a href="{{ route('notifications.index') }}" class="btn btn-sm btn-primary">Retour à toutes les notifications</a>
            </div>
        </div>
    @else
        <p class="text-center text-muted">Notification non trouvée ou accès non autorisé.</p>
    @endif
</div>
@endsection