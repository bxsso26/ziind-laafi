<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Modèle Favorite
 *
 * Représente un favori enregistré par un client.
 * Un favori est l'association entre un utilisateur (client) et une annonce immobilière.
 * Permet aux clients de sauvegarder les biens qui les intéressent.
 */
class Favorite extends Model
{
    /**
     * Colonnes autorisées à l'assignation de masse.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'property_id'];

    /**
     * Relation : un favori appartient à une annonce immobilière.
     * Permet d'accéder aux détails du bien via $favorite->property
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function property()
    {
        return $this->belongsTo(Property::class);
    }
}