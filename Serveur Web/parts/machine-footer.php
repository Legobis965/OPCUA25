<?php

// Crée un token basé sur des infos de session + sel secret
$secret = 'clé-ultra-secrète'; // stockée dans un fichier non-web-accessible
$timestamp = time();
$token = hash_hmac('sha256', $_SESSION['username'] . '|' . session_id() . '|' . $timestamp, $secret);


$dir = "C:\\Windows\\Temp\\OPCUA25_tokens";

// Crée le dossier s'il n'existe pas déjà
if (!is_dir($dir)) {
    mkdir($dir, 0777, true);
}

// Écriture du contenu dans le fichier
file_put_contents($dir . "\\_ws_token_{$_SESSION['username']}", "$token:$timestamp");
?>

        </div>
    </div>
    <button class="scroll-button" onclick="window.scrollTo(0, 0)"><img src="/image/Icons/scrollUp.png"></button>
</section>

<script src="/js/webSocket.js"></script>
<script src="/js/machine.js"></script>