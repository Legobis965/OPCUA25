<?php
// Crée un token basé sur des infos de session + sel secret
const TOKENS_DIR = "C:\\Windows\\Temp\\OPCUA25_tokens";
$secret = 'B9FDCF7F7FE33F9FF2D28B1E9B2F7';
$timestamp = time();
$token = hash_hmac('sha256', $_SESSION['username'] . '|' . session_id() . '|' . $timestamp, $secret);

// Crée le dossier s'il n'existe pas déjà
if (!is_dir(TOKENS_DIR)) {
    mkdir(TOKENS_DIR, recursive:true);
}

// Écriture du contenu dans le fichier
file_put_contents(TOKENS_DIR . "\\ws_token_{$_SESSION['username']}", "$token:$timestamp");
?>

<script>
    const wsToken = '<?= $token ?>';
</script>