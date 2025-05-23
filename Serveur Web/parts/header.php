<?php
    // Chargement du css selon la page
    $urlParts = explode('/', $url);
    $fileName = (end($urlParts) ?: 'home') . '.css';
    $filePath =  $root . '/public/css/' . $fileName;

    $ifNotif = false;

    // Recherche et affichage d'une notification 
    if (isset($_SESSION['notification']['state'], $_SESSION['notification']['message'])) {
        $ifNotif = true;
        $state = $_SESSION['notification']['state'];
        $message = $_SESSION['notification']['message'];
        $_SESSION['notification'] = null;
    }
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ilôt connecté</title>
    <link rel="shortcut icon" href="/image/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="/css/style.css">
    <?php if (file_exists($filePath)): ?>
        <link rel="stylesheet" href="/css/<?= $fileName ?>">
    <?php endif ?>
    <?php if (str_contains($_SERVER['REQUEST_URI'], "machines")): ?>
        <link rel="stylesheet" href="/css/machines.css">
    <?php endif ?>
</head>
<body class="light">
    <header>
        <a href="/" class="logo-container">
            <img src="/image/logo ilot connecté.png" alt="Logo Ilôt Connecté" class="logo">
            <h1 class="header">Ilôt connecté</h1>
        </a>
        <div class="center-container">
            <button class="theme-toggle" id="theme-toggle" aria-label="auto" aria-live="polite" onclick="setThemePreference()">
                <svg class="sun-and-moon" aria-hidden="true" width="24" height="24" viewBox="0 0 24 24">
                    <mask class="moon" id="moon-mask">
                    <rect x="0" y="0" width="100%" height="100%" fill="white" />
                    <circle cx="24" cy="10" r="6" fill="black" />
                    </mask>
                    <circle class="sun" cx="12" cy="12" r="6" mask="url(#moon-mask)" fill="currentColor" />
                    <g class="sun-beams" stroke="currentColor">
                        <line x1="12" y1="1" x2="12" y2="3" />
                        <line x1="12" y1="21" x2="12" y2="23" />
                        <line x1="4.22" y1="4.22" x2="5.64" y2="5.64" />
                        <line x1="18.36" y1="18.36" x2="19.78" y2="19.78" />
                        <line x1="1" y1="12" x2="3" y2="12" />
                        <line x1="21" y1="12" x2="23" y2="12" />
                        <line x1="4.22" y1="19.78" x2="5.64" y2="18.36" />
                        <line x1="18.36" y1="5.64" x2="19.78" y2="4.22" />
                    </g>
                </svg>
            </button>

            <a class="button" href="/logout">Se déconnecter</a>
        </div>
    </header>
    <div class="notification box center-container <?= $ifNotif ? 'lazy-visible ' . $state : '' ?>" id="notification"> 
        <div class="state-icon"></div>
        <div>
            <p class="state-text"></p> 
            <p id="notification-message"><?= $ifNotif ? $message : '' ?></p>
        </div>
        <button class="center-container" onclick="closeNotification()">
            <img src="/image/icons/notif-close.webp">
        </button>
    </div>
    <main class="flex-column">
    