<?php
session_start();

$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
$publicPath = __DIR__ . '/public' . $uri;

// Redirection des requêtes Grafana
if (str_starts_with($uri, '/grafana')) {
    if (!empty($_SESSION['isLoggedIn'])) {
        require __DIR__ . '/grafana.php';
    } else {
        http_response_code(401);
    }
    exit;
}

// Fichier statique (CSS, JS, images, etc.)
if (file_exists($publicPath) && is_file($publicPath)) {
    // Détection du type MIME pour bien servir les fichiers
    $ext = pathinfo($publicPath, PATHINFO_EXTENSION);

    // Types MIME personnalisés
    $mimeTypes = [
        'css'  => 'text/css',
        'js'   => 'application/javascript',
        'png'  => 'image/png',
        'jpg'  => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'webp' => 'image/webp',
        'html' => 'text/html',
        'htm'  => 'text/html',
        'ico'  => 'image/x-icon'
    ];

    $mimeType = $mimeTypes[$ext] ?? 'application/octet-stream';
    header("Content-Type: $mimeType");
    readfile($publicPath);
    exit;
}

// Sinon, on passe tout au routeur principal
require __DIR__ . '/index.php';