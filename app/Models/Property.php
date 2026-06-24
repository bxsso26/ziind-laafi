<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Property extends Model
{
    use HasFactory;

    // Liste des colonnes de la base de données qu'on autorise Laravel à remplir
    protected $fillable = [
        'user_id',
        'type',
        'property_usage',
        'contract_option',
        'zone',
        'size',
        'price',
        'description',
        'photo_path',
        'status'
    ];

    // Scope de filtrage multicritères (EF-B1)
    public function scopeFilter($query, array $filters)
    {
        if (!empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }
        if (!empty($filters['usage'])) {
            $query->where('property_usage', $filters['usage']);
        }
        if (!empty($filters['option'])) {
            $query->where('contract_option', $filters['option']);
        }
        if (!empty($filters['zone'])) {
            $query->where('zone', 'like', '%' . $filters['zone'] . '%');
        }
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function visitRequests()
    {
        return $this->hasMany(VisitRequest::class);
    }
}