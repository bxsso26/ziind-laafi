<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Disque de stockage par défaut
    |--------------------------------------------------------------------------
    | Disque utilisé par défaut pour toutes les opérations de stockage de fichiers.
    | Configuré via la variable d'environnement FILESYSTEM_DISK dans le fichier .env
    */
    'default' => env('FILESYSTEM_DISK', 'local'),

    'disks' => [

        /*
        | Disque 'local' : stockage privé, non accessible publiquement via le navigateur.
        | Utilisé pour les fichiers sensibles (exports, documents internes...).
        | Racine : storage/app/private/
        */
        'local' => [
            'driver' => 'local',
            'root'   => storage_path('app/private'),
            'serve'  => true,
            'throw'  => false,
            'report' => false,
        ],

        /*
        | Disque 'public' : stockage accessible via le navigateur.
        | Utilisé pour les photos des annonces immobilières.
        | Racine physique : storage/app/public/
        | URL publique   : APP_URL/storage/ (via le lien symbolique public/storage)
        | Commande pour créer le lien : php artisan storage:link
        */
        'public' => [
            'driver'     => 'local',
            'root'       => storage_path('app/public'),
            'url'        => rtrim(env('APP_URL', 'http://localhost'), '/') . '/storage',
            'visibility' => 'public',
            'throw'      => false,
            'report'     => false,
        ],

        /*
        | Disque 's3' : stockage cloud Amazon S3.
        | Non utilisé actuellement, disponible pour une mise en production future.
        | Nécessite les variables AWS_* dans le fichier .env
        */
        's3' => [
            'driver'                  => 's3',
            'key'                     => env('AWS_ACCESS_KEY_ID'),
            'secret'                  => env('AWS_SECRET_ACCESS_KEY'),
            'region'                  => env('AWS_DEFAULT_REGION'),
            'bucket'                  => env('AWS_BUCKET'),
            'url'                     => env('AWS_URL'),
            'endpoint'                => env('AWS_ENDPOINT'),
            'use_path_style_endpoint' => env('AWS_USE_PATH_STYLE_ENDPOINT', false),
            'throw'                   => false,
            'report'                  => false,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Liens symboliques
    |--------------------------------------------------------------------------
    | Définit les liens créés par la commande : php artisan storage:link
    | La clé est le chemin du lien dans public/, la valeur est la cible réelle.
    | Cela permet d'accéder aux fichiers de storage/ via une URL publique.
    */
    'links' => [
        public_path('storage') => storage_path('app/public'),
    ],

];