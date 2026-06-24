<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Authentification - Ziind Laafi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background-color: #f4f6f9; }
        .auth-card { border: none; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        .nav-pills .nav-link { color: #495057; border-radius: 8px; }
        .nav-pills .nav-link.active { background-color: #0d6efd; }
    </style>
</head>
<body>
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            
            <div class="text-center mb-4">
    <h3 class="text-primary fw-bold">ZIIND LAAFI</h3>
    <p class="text-muted text-uppercase small">Portail d'authentification</p>
    
</div>

            <div class="card auth-card p-4 bg-white">
                <ul class="nav nav-pills nav-justified mb-4" id="pills-tab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="pills-login-tab" data-bs-toggle="pill" data-bs-target="#pills-login" type="button" role="tab">
                            <i class="fas fa-sign-in-alt me-2"></i>Connexion
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="pills-register-tab" data-bs-toggle="pill" data-bs-target="#pills-register" type="button" role="tab">
                            <i class="fas fa-user-plus me-2"></i>Inscription
                        </button>
                    </li>
                </ul>

                <div class="tab-content" id="pills-tabContent">
                    
                    <div class="tab-pane fade show active" id="pills-login" role="tabpanel">
                        <form action="{{ route('login.post') }}" method="POST">
                            @csrf
                            @if($errors->any())
                               <div class="alert alert-danger mb-3">
                                @foreach($errors->all() as $error)
                                 <p class="mb-0">{{ $error }}</p>
                                 @endforeach
                              </div>
                            @endif
                            <div class="mb-3">
                                <label for="login-email" class="form-label">Adresse Email</label>
                                <input type="email" name="email" id="login-email" class="form-control" placeholder="nom@example.com" required>
                            </div>
                            <div class="mb-4">
                                <label for="login-password" class="form-label">Mot de passe</label>
                                <input type="password" name="password" id="login-password" class="form-control" placeholder="••••••••" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100 py-2">Se connecter</button>
                        </form>
                    </div>
                    

                    <div class="tab-pane fade" id="pills-register" role="tabpanel">
                        <form action="{{ route('register') }}" method="POST">
                            @csrf
                            @if($errors->any())
                               <div class="alert alert-danger mb-3">
                                @foreach($errors->all() as $error)
                                 <p class="mb-0">{{ $error }}</p>
                                 @endforeach
                              </div>
                            @endif
                            <div class="mb-3">
                                <label class="form-label d-block fw-semibold text-secondary">Je m'inscris en tant que :</label>
                                <div class="row g-2">
                                    <div class="col">
                                        <input type="radio" class="btn-check" name="role" id="role-client" value="client" checked>
                                        <label class="btn btn-outline-primary w-100 py-2" for="role-client">Client</label>
                                    </div>
                                    <div class="col">
                                        <input type="radio" class="btn-check" name="role" id="role-bailleur" value="bailleur">
                                        <label class="btn btn-outline-primary w-100 py-2" for="role-bailleur">Bailleur</label>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="reg-nom" class="form-label">Nom complet</label>
                                <input type="text" name="name" id="reg-nom" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="reg-email" class="form-label">Email</label>
                                <input type="email" name="email" id="reg-email" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="reg-tel" class="form-label">Téléphone</label>
                                <input type="text" name="telephone" id="reg-tel" class="form-control" placeholder="+226..." required>
                            </div>
                            <div class="mb-4">
                                <label for="reg-password" class="form-label">Mot de passe (min 8 car.)</label>
                                <input type="password" name="password" id="reg-password" class="form-control" required>
                            </div>
                            <div class="mb-4">
                                <label for="reg-password-confirm" class="form-label">Confirmer le mot de passe</label>
                                <input type="password" name="password_confirmation" id="reg-password-confirm" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-success w-100 py-2">Créer mon compte</button>
                        </form>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>                                            