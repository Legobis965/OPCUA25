<?php
    require $root . '/class/Database.php';
    require $root . '/class/fonctions.php';

    if (isset($_GET['utilisateur'])) {
        $db = new Database();
        try {
            // Éxécution de la requête SQL
            $users = $db->query('DELETE FROM users WHERE username = :username', 
            [
                'username' => $_GET['utilisateur']
            ]);
            
            // Envoi de la notification quand un utilisateur est supprimé  
            set_notification(States::Success, "{$_GET['utilisateur']} a bien été supprimé.");
        } catch (Exception) {
            set_notification(States::Error, "Erreur lors de la suppression de {$_GET['utilisateur']}");
        }
    }

    header('Location: /admin');