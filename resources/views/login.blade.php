<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        @vite(['resources/css/reset.css', 'resources/css/app.css'])
    </head>
    <body class="login">
        <form class="login__box" action="{{ route('login') }}" method="POST">
            @csrf
            <label for="user">Utilisateur</label>
            <select name="user" id="user">
                @foreach (App\Models\User::all() as $user)
                <option value="{{ $user->id }}">{{ $user->email }}</option>
                @endforeach
            </select>
            <button class="button">Se connecter</button>
        </form>
    </body>
</html>
