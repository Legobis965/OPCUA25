<?php

// Libération du verrou de session
session_write_close();

// Création du nouveau chemin
$ch = curl_init("http://127.0.0.1:3000" . $_SERVER['REQUEST_URI']);

curl_setopt_array($ch, [
    CURLOPT_CUSTOMREQUEST => $_SERVER['REQUEST_METHOD'],
    CURLOPT_FOLLOWLOCATION => false,                       // Ignore les redirections
    CURLOPT_RETURNTRANSFER => false,                       // La réponse ne sera pas chargée en mémoire mais renvoyée directement par paquets
    CURLOPT_HEADER => false,                               // On gère nous-mêmes les en-têtes
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CONNECTTIMEOUT => 3,                           // Timeout de connexion
    CURLOPT_TIMEOUT => 60,                                 // Timeour de réponse
]);

// Transfert du corps éventuel de la requête
$body = file_get_contents('php://input');
if ($body !== '') {
    curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
}
// Transfert des en-têtes de la requête en ignorant certains qui sont générés automatiquement
$headers = [];
foreach (getallheaders() as $key => $value) {
    $key = strtolower($key);
    if (!in_array($key, ['content-length', 'transfer-encoding', 'connection'])) {
        $headers[] = "$key: $value";
    }
}
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

// Retour des en-têtes de la réponse
curl_setopt($ch, CURLOPT_HEADERFUNCTION, function ($ch, $line) {
    if (stripos($line, 'HTTP/') === 0) {
        // Transfert du code HTTP
        preg_match('#HTTP/\d\.\d\s+(\d+)#', $line, $m);
        http_response_code(intval($m[1] ?? 200));
    } elseif (!preg_match('#^Transfer-Encoding#i', $line)) {
        // Transfert des en-têtes
        header($line, false);
    }
    return strlen($line);
});

// Retour du corps de la réponse par paquets de 4ko
curl_setopt($ch, CURLOPT_WRITEFUNCTION, function ($ch, $chunk) {
    echo $chunk;
    if (ob_get_level()) {
        ob_flush();
    }
    flush();
    return strlen($chunk);
});

// Lancement de la requête
$ok = curl_exec($ch);
if ($ok === false) {
    // Retour d'une erreur 502 si la requête a échoué
    http_response_code(502);
    echo "Erreur proxy : " . curl_error($ch);
}
curl_close($ch);