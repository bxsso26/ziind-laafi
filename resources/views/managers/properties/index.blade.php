@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h2 class="text-2xl font-black text-slate-900">Gestion des Propriétés</h2>
        <span class="text-sm text-slate-500">{{ $properties->total() }} annonces au total</span>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 border-b border-slate-200">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase">Type</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase">Zone</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase">Prix</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase">Statut</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($properties as $property)
                <tr class="hover:bg-slate-50">
                    <td class="px-6 py-4 font-semibold text-slate-800">{{ $property->type }}</td>
                    <td class="px-6 py-4 text-slate-600">{{ $property->zone }}</td>
                    <td class="px-6 py-4 font-bold text-red-600">{{ number_format($property->price, 0, '.', ' ') }} F CFA</td>
                    <td class="px-6 py-4">
                        @php
                            $colors = [
                                'publiée'    => 'bg-emerald-100 text-emerald-700',
                                'en attente' => 'bg-amber-100 text-amber-700',
                                'retirée'    => 'bg-red-100 text-red-700',
                            ];
                            $color = $colors[strtolower($property->status)] ?? 'bg-slate-100 text-slate-600';
                        @endphp
                        <span class="px-2.5 py-1 rounded-lg text-xs font-bold {{ $color }}">
                            {{ ucfirst($property->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        @if(strtolower($property->status) === 'publiée')
                            <form action="{{ route('manager.properties.retirer', $property->id) }}" method="POST"
                                onsubmit="return confirm('Retirer cette annonce ?')">
                                @csrf
                                @method('PATCH')
                                <button type="submit"
                                    class="px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white text-xs font-bold rounded-lg border-0 cursor-pointer transition">
                                    Retirer
                                </button>
                            </form>
                        @else
                            <span class="text-xs text-slate-400">—</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-slate-400">Aucune propriété trouvée.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-4 border-t border-slate-100">
            {{ $properties->links() }}
        </div>
    </div>
</div>
@endsection