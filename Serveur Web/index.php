<?php
$root = __DIR__;
$url = explode('?', $_SERVER['REQUEST_URI'])[0];
$url = rtrim($url, '/');

// Redirection login
if ($url !== '/login' && empty($_SESSION['isLoggedIn'])) {
    header('Location: /login');
    exit;
}

if ($url === '/login' && !empty($_SESSION['isLoggedIn'])) {
    header('Location: /');
    exit;
}

// Restriction admin
if (str_starts_with($url, '/admin') && empty($_SESSION['isAdmin'])) {
    header('Location: /');
    exit;
}

$isHome = $url === '';

require $root . '/parts/header.php';

$pagePath = $root . '/templates' . ($isHome ? '/home' : $url) . '.php';

if (file_exists($pagePath)) {
    if (str_contains($url, '/machines/') || $isHome) {
        require $root . '/parts/ws-tokens.php';
    }

    if (str_contains($url, '/machines/')) {
        require $root . '/parts/machine-header.php';
        require $pagePath;
        require $root . '/parts/machine-footer.php';
    } else {
        require $pagePath;
    }
} else {
    require $root . '/templates/404-error.php';
}

require $root . '/parts/footer.php';
