<!DOCTYPE html>
<html lang="fr" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ziind Laafi | Plateforme Immobilière</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="h-full flex flex-col font-sans text-slate-900 antialiased">

    <header class="sticky top-0 z-50 bg-white/95 backdrop-blur-md border-b-4 border-blue-900 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex justify-between items-center">
        
        {{-- Logo --}}
        <div class="flex items-center gap-4">
            <div class="h-12 w-12 rounded-xl bg-gradient-to-br from-blue-900 to-blue-600 flex items-center justify-center shadow-md shadow-blue-500/20">
                <span class="text-white font-black text-xl tracking-wider">ZL</span>
            </div>
            <div>
                <h1 class="text-xl font-extrabold tracking-tight text-blue-900 sm:text-2xl">Ziind Laafi</h1>
                <p class="text-xs font-semibold uppercase tracking-wider text-emerald-600">L'habitat serein</p>
            </div>
        </div>

        {{-- Bouton hamburger visible uniquement sur mobile --}}
        <button id="menu-toggle" class="lg:hidden flex flex-col gap-1.5 p-2 rounded-lg hover:bg-slate-100 transition">
            <span class="block w-6 h-0.5 bg-slate-700 transition-all" id="bar1"></span>
            <span class="block w-6 h-0.5 bg-slate-700 transition-all" id="bar2"></span>
            <span class="block w-6 h-0.5 bg-slate-700 transition-all" id="bar3"></span>
        </button>

        {{-- Navigation desktop (cachée sur mobile) --}}
        <nav class="hidden lg:flex items-center gap-4 sm:gap-6">
            <a href="{{ route('properties.index') }}" class="text-sm font-bold text-slate-600 hover:text-blue-900 transition-colors">Catalogue</a>

            @auth
                <span class="text-sm font-semibold text-slate-700">
                    Bonjour, {{ auth()->user()->name }} ({{ ucfirst(auth()->user()->role) }})
                </span>

                @if(in_array(auth()->user()->role, ['bailleur', 'agent']))
                    <a href="{{ route('bailleur.index') }}" class="text-sm font-bold text-blue-950 hover:text-blue-700">Mes Annonces</a>
                @endif

                @if(auth()->user()->role === 'bailleur')
                    <a href="{{ route('properties.create') }}" class="px-5 py-2.5 text-sm font-bold text-white bg-blue-900 rounded-3xl">Déposer une annonce</a>
                @endif

                @if(auth()->user()->role === 'client')
    <a href="{{ route('client.visites') }}" class="text-sm font-bold text-slate-600 hover:text-blue-900">Mes Visites</a>
    <a href="{{ route('client.favoris') }}" class="text-sm font-bold text-slate-600 hover:text-blue-900">❤️ Mes Favoris</a>  
@endif

                @if(auth()->user()->role === 'agent')
                    <a href="{{ route('agent.dashboard') }}" class="text-sm font-bold text-blue-950 hover:text-blue-700">Espace Agent</a>
                @endif

                @if(auth()->user()->role === 'manager')
                    <a href="{{ route('manager.users.index') }}" class="text-sm font-bold text-slate-600 hover:text-blue-900">Utilisateurs</a>
                    <a href="{{ route('manager.dashboard') }}" class="text-sm font-bold text-slate-600 hover:text-blue-900">📊 Stats</a>
                    <a href="{{ route('manager.properties.index') }}" class="text-sm font-bold text-slate-600 hover:text-blue-900">Propriétés</a>
                @endif

                <form action="{{ route('logout') }}" method="POST" class="inline m-0">
                    @csrf
                    <button type="submit" class="px-5 py-2.5 text-sm font-bold text-white bg-red-600 hover:bg-red-700 rounded-3xl border-0 cursor-pointer">
                        Déconnexion
                    </button>
                </form>
            @else
                <a href="{{ route('auth.page') }}" class="text-sm font-bold text-slate-600 hover:text-blue-900">Se connecter / S'inscrire</a>
                <a href="{{ route('auth.page') }}" class="px-5 py-2.5 text-sm font-bold text-white bg-blue-900 rounded-3xl">Déposer une annonce</a>
            @endauth
        </nav>
    </div>

    {{-- Menu mobile (caché par défaut, s'ouvre avec le hamburger) --}}
    <div id="mobile-menu" class="hidden lg:hidden bg-white border-t border-slate-100 px-4 py-4 space-y-2">
        <a href="{{ route('properties.index') }}" class="block text-sm font-bold text-slate-600 hover:text-blue-900 py-2 border-b border-slate-50">Catalogue</a>

        @auth
            <p class="text-sm font-semibold text-slate-400 py-1">{{ auth()->user()->name }} — {{ ucfirst(auth()->user()->role) }}</p>

            @if(in_array(auth()->user()->role, ['bailleur', 'agent']))
                <a href="{{ route('bailleur.index') }}" class="block text-sm font-bold text-blue-900 py-2">Mes Annonces</a>
            @endif

            @if(auth()->user()->role === 'bailleur')
                <a href="{{ route('properties.create') }}" class="block text-sm font-bold text-blue-900 py-2">Déposer une annonce</a>
            @endif

            @if(auth()->user()->role === 'client')
    <a href="{{ route('client.visites') }}" class="text-sm font-bold text-slate-600 hover:text-blue-900">Mes Visites</a>
    <a href="{{ route('client.favoris') }}" class="text-sm font-bold text-slate-600 hover:text-blue-900">❤️ Mes Favoris</a>
@endif

            @if(auth()->user()->role === 'agent')
                <a href="{{ route('agent.dashboard') }}" class="block text-sm font-bold text-slate-600 py-2">Espace Agent</a>
            @endif

            @if(auth()->user()->role === 'manager')
                <a href="{{ route('manager.users.index') }}" class="block text-sm font-bold text-slate-600 py-2">Utilisateurs</a>
                <a href="{{ route('manager.dashboard') }}" class="block text-sm font-bold text-slate-600 py-2">📊 Statistiques</a>
                <a href="{{ route('manager.properties.index') }}" class="block text-sm font-bold text-slate-600 py-2">Propriétés</a>
            @endif

            <form action="{{ route('logout') }}" method="POST" class="pt-2 border-t border-slate-100">
                @csrf
                <button type="submit" class="w-full text-left text-sm font-bold text-red-600 py-2 bg-transparent border-0 cursor-pointer">
                    Déconnexion
                </button>
            </form>
        @else
            <a href="{{ route('auth.page') }}" class="block text-sm font-bold text-slate-600 py-2">Se connecter / S'inscrire</a>
            <a href="{{ route('auth.page') }}" class="block text-sm font-bold text-blue-900 py-2">Déposer une annonce</a>
        @endauth
    </div>
</header>

<script>
    const toggle = document.getElementById('menu-toggle');
    const menu = document.getElementById('mobile-menu');
    const bar1 = document.getElementById('bar1');
    const bar2 = document.getElementById('bar2');
    const bar3 = document.getElementById('bar3');

    toggle.addEventListener('click', () => {
        menu.classList.toggle('hidden');
        bar1.classList.toggle('rotate-45');
        bar1.classList.toggle('translate-y-2');
        bar2.classList.toggle('opacity-0');
        bar3.classList.toggle('-rotate-45');
        bar3.classList.toggle('-translate-y-2');
    });
</script>
    <main class="flex-grow max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-10">
        @if(session('success'))
            <div class="mb-8 p-4 bg-emerald-50 border-l-4 border-emerald-500 rounded-r-xl flex items-center gap-3 shadow-sm animate-fade-in">
                <span class="text-emerald-600 font-bold">✓</span>
                <p class="text-sm font-medium text-emerald-800">{{ session('success') }}</p>
            </div>
        @endif

        @yield('content')
    </main>

    <footer class="bg-slate-50 border-t border-slate-200 py-6 mt-12">
    <div class="max-w-7xl mx-auto px-4 text-center">
        <p class="text-sm font-medium text-slate-500">
            &copy; 2026 - <span class="font-bold text-blue-900">Ziind Laafi</span> | Plateforme Immobilière Professionnelle. Tous droits réservés.
        </p>
    </div>
</footer>
</body>
</html>