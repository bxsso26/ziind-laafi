<?php

namespace App\Http\Controllers;

use App\Http\Controllers\PropertyController;
use App\Http\Controllers\ManagerUserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// ROUTES PUBLIQUES
Route::get('/', [PropertyController::class, 'index'])->name('home');
Route::get('/properties', [PropertyController::class, 'index'])->name('properties.index');
Route::get('/properties/{id}', [PropertyController::class, 'show'])->name('properties.show');
Route::get('/authentification', fn() => view('auth'))->name('auth.page');

// Auth
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ROUTES CONNECTÉS (tous rôles)
Route::middleware(['auth'])->group(function () {
    Route::post('/properties/{id}/visit', [PropertyController::class, 'visit'])->name('properties.visit');
    Route::get('/mes-visites', [PropertyController::class, 'mesVisites'])->name('client.visites');
    Route::post('/favorites/{id}', [PropertyController::class, 'toggleFavorite'])->name('favorites.toggle');
    Route::get('/mes-favoris', [PropertyController::class, 'mesFavoris'])->name('client.favoris');
});

// ROUTES AGENTS / BAILLEURS
Route::middleware(['auth', 'role:agent,bailleur'])->group(function () {
    Route::get('/mes-annonces', [PropertyController::class, 'mesAnnonces'])->name('bailleur.index');
    Route::get('/agent/dashboard', [PropertyController::class, 'agentDashboard'])->name('agent.dashboard');
    
    Route::get('/properties/create', [PropertyController::class, 'create'])->name('properties.create'); // ← déplacé ici
    Route::post('/properties', [PropertyController::class, 'store'])->name('properties.store'); // ← une seule fois
    Route::get('/properties/{id}/edit', [PropertyController::class, 'edit'])->name('properties.edit');
    Route::put('/properties/{id}', [PropertyController::class, 'update'])->name('properties.update');
    Route::delete('/properties/{id}', [PropertyController::class, 'retirer'])->name('properties.destroy');
    
    Route::patch('/agent/properties/{id}/validate', [PropertyController::class, 'validate'])->name('agent.properties.validate');
    Route::patch('/agent/properties/{id}/reject', [PropertyController::class, 'retirerannonce'])->name('agent.properties.reject');
    Route::patch('/agent/visits/{id}/validate', [PropertyController::class, 'validateVisit'])->name('agent.visits.validate');
    Route::patch('/agent/visits/{id}/reject', [PropertyController::class, 'rejectVisit'])->name('agent.visits.reject');
});

// ROUTES MANAGER
Route::middleware(['auth', 'role:manager'])->prefix('manager')->name('manager.')->group(function () {
    Route::get('/dashboard', [ManagerUserController::class, 'dashboard'])->name('dashboard');
    Route::get('/users', [ManagerUserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [ManagerUserController::class, 'create'])->name('users.create');
    Route::post('/users', [ManagerUserController::class, 'store'])->name('users.store');
    Route::get('/users/{id}/edit', [ManagerUserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{id}', [ManagerUserController::class, 'update'])->name('users.update');
    Route::delete('/users/{id}', [ManagerUserController::class, 'destroy'])->name('users.destroy');
    Route::get('/properties', [ManagerUserController::class, 'manageIndex'])->name('properties.index');
    Route::patch('/properties/{id}/retirer', [ManagerUserController::class, 'retirer'])->name('properties.retirer');
});