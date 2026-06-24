@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <h2 class="text-2xl font-bold text-slate-900 mb-8">📊 Tableau de bord statistique</h2>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <div class="p-6 bg-white border border-amber-200 rounded-3xl shadow-sm text-center">
            <h6 class="text-xs font-bold text-slate-500 uppercase tracking-wider">Annonces en attente</h6>
            <span class="block text-5xl font-black text-amber-600 my-3">{{ $pendingPropertiesCount }}</span>
            <p class="text-sm text-slate-600">À valider pour publication.</p>
        </div>
        
        <div class="p-6 bg-white border border-emerald-200 rounded-3xl shadow-sm text-center">
            <h6 class="text-xs font-bold text-slate-500 uppercase tracking-wider">Demandes de visite</h6>
            <span class="block text-5xl font-black text-emerald-600 my-3">{{ $visitsByMonth->sum('total') }}</span>
            <p class="text-sm text-slate-600">Total des rendez-vous demandés.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <div class="bg-white border border-slate-100 rounded-3xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 bg-slate-50 border-b border-slate-100 font-bold text-slate-800">
                🏠 Propriétés par type
            </div>
            <table class="w-full text-sm text-slate-600">
                <thead class="bg-slate-50 text-xs text-slate-500 uppercase">
                    <tr>
                        <th class="px-6 py-3 text-left">Type de bien</th>
                        <th class="px-6 py-3 text-right">Quantité</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($propertiesByType as $item)
                        <tr>
                            <td class="px-6 py-4 font-semibold text-slate-900">{{ $item->type }}</td>
                            <td class="px-6 py-4 text-right font-bold text-blue-900">{{ $item->total }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="2" class="px-6 py-4 text-center text-slate-400">Aucune propriété enregistrée</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="bg-white border border-slate-100 rounded-3xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 bg-slate-900 text-white font-bold">
                📅 Évolution des visites (2026)
            </div>
            <ul class="divide-y divide-slate-100">
                @php $mois = [1=>'Janvier', 2=>'Février', 3=>'Mars', 4=>'Avril', 5=>'Mai', 6=>'Juin', 7=>'Juillet', 8=>'Août', 9=>'Septembre', 10=>'Octobre', 11=>'Novembre', 12=>'Décembre']; @endphp
                @forelse($visitsByMonth as $visit)
                    <li class="px-6 py-4 flex justify-between items-center text-sm font-medium text-slate-700">
                        {{ $mois[$visit->month] ?? 'Mois '.$visit->month }}
                        <span class="px-3 py-1 bg-emerald-100 text-emerald-800 rounded-full text-xs font-bold">{{ $visit->total }} visites</span>
                    </li>
                @empty
                    <li class="px-6 py-4 text-center text-slate-400 text-sm">Aucune donnée cette année</li>
                @endforelse
            </ul>
        </div>
    </div>
</div>
@endsection