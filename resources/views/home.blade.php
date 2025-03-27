<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Laravel</title>

        @vite(['resources/css/reset.css', 'resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body>
        <div id="app">
            <div class="user">
                <div>
                    <h1 class="title">{{ $user->name }}</h1>
                    <dl class="metas">
                        <dt class="metas__term">Client&nbsp;:</dt>
                        <dd class="metas__value">{{ $user->client?->type->label() ?? 'Non défini' }}</dd>
                        <dt class="metas__term">Fournisseur&nbsp;:</dt>
                        <dd class="metas__value">{{ $user->supplier ? 'Oui' : 'Non' }}</dd>
                    </dl>
                </div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button class="button">Se déconnecter</button>
                </form>
            </div>

            <cart :products="{{ json_encode($products) }}" :receipt="{{ json_encode($receipt) }}" />
        </div>
    </body>
</html>
