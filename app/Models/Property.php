<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Modèle Property (Bien immobilier)
 *
 * Représente une annonce immobilière sur la plateforme Ziind Laafi.
 * Gère les attributs du bien, les filtres de recherche multicritères
 * et les relations avec l'utilisateur propriétaire et les demandes de visite.
 */
class Property extends Model
{
    use HasFactory;

    /**
     * Colonnes autorisées à l'assignation de masse.
     * Toute colonne absente ici sera ignorée lors d'un create() ou update().
     *
     * @var array
     */
    protected $fillable = [
        'user_id',         // Identifiant du bailleur/agent qui a créé l'annonce
        'type',            // Type de bien : Villa, Appartement, Terrain...
        'property_usage',  // Usage : résidence, bureau, commerce, agriculture
        'contract_option', // Option : location ou vente
        'zone',            // Localisation géographique du bien
        'size',            // Superficie en m²
        'price',           // Prix de vente ou loyer mensuel en F CFA
        'description',     // Description détaillée du bien
        'photo_path',      // Chemin relatif vers la photo stockée dans storage/
        'status',          // Statut : 'en attente', 'publiée', 'retirée'
    ];

    /**
     * Scope de filtrage multicritères pour le catalogue public.
     *
     * Applique dynamiquement des conditions WHERE selon les filtres
     * passés depuis le formulaire de recherche (type, usage, option, zone).
     * Un filtre vide ou absent est simplement ignoré.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  array $filters  Tableau associatif des filtres actifs
     * @return void
     */
    public function scopeFilter($query, array $filters)
    {
        // Filtre par type de bien (Villa, Appartement, Terrain...)
        if (!empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        // Filtre par usage (résidence, bureau, commerce, agriculture)
        if (!empty($filters['usage'])) {
            $query->where('property_usage', $filters['usage']);
        }

        // Filtre par option contractuelle (location ou vente)
        if (!empty($filters['option'])) {
            $query->where('contract_option', $filters['option']);
        }

        // Filtre par zone géographique (recherche partielle insensible à la casse)
        if (!empty($filters['zone'])) {
            $query->where('zone', 'like', '%' . $filters['zone'] . '%');
        }
    }

    /**
     * Relation : une annonce appartient à un utilisateur (bailleur ou agent).
     * Permet d'accéder aux infos du propriétaire via $property->user
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relation : une annonce peut avoir plusieurs demandes de visite.
     * Permet d'accéder aux visites via $property->visitRequests
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function visitRequests()
    {
        return $this->hasMany(VisitRequest::class);
    }
}