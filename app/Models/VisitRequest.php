<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Modèle VisitRequest (Demande de visite)
 *
 * Représente une demande de visite soumise par un client
 * pour un bien immobilier publié sur la plateforme.
 * La demande est traitée (validée ou refusée) par un agent.
 */
class VisitRequest extends Model
{
    /**
     * Colonnes autorisées à l'assignation de masse.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',     // Identifiant du client qui fait la demande
        'property_id', // Identifiant de l'annonce concernée
        'visit_date',  // Date souhaitée pour la visite
        'message',     // Message optionnel du client à l'agent
        'status',      // Statut : 'en attente', 'validée', 'refusée'
    ];

    /**
     * Relation : une demande de visite concerne un bien immobilier.
     * Permet d'accéder aux détails du bien via $visitRequest->property
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    /**
     * Relation : une demande de visite est faite par un utilisateur (client).
     * Permet d'accéder aux infos du client via $visitRequest->user
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}