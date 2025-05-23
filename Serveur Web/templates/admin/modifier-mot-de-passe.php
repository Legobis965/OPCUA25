<?php   
    require $root . '/class/Database.php';
    require $root . '/class/fonctions.php';

    if (!isset($_GET['utilisateur'])) {
        header('Location: /admin');
        exit;
    }

    // Si les champs sont remplis
    if (isset($_POST['password'], $_POST['checkPassword'], $_GET['utilisateur'])) {
        $db = new Database();
        
        // Si les deux mots de passe correspondent
        if ($_POST['password'] !== $_POST['checkPassword']) {
            $error = 'Les mots de passe ne correspondent pas';
        }

        // Si le mot de passe contient moins de 8 caractères
        elseif (strlen($_POST['password']) < 8) {
            $error = 'Le mot de passe doit contenir au moins 8 caractères';
        }

        // Vérifie l'existence de l'utilisateur
        elseif (!in_array($_GET['utilisateur'], array_column($db->query('SELECT username FROM users'), 'username'))) {
            $error = "L'utilisateur {$_GET['utilisateur']} n'existe pas";
        } else {
            try {
                // Éxécution de la requête SQL
                $users = $db->query('UPDATE users SET password = :password WHERE username = :username',
                [
                    'username' => $_GET['utilisateur'],
                    'password' => password_hash($_POST['password'], PASSWORD_DEFAULT)
                ]);

                // Envoi de la notification quand un mot de passe est modifié
                set_notification(States::Success, 'Le mot de passe à bien été modifié.');
            } catch (Exception) {
                set_notification(States::Error, 'Erreur lors de la modification du mot de passe');
            }

            header('Location: /admin');
            exit;
        }
    }
?>

<link rel="stylesheet" href="/css/admin-form.css">

<a href="/admin" class="margin-center button">Retour à la page d'administration</a>
<div class="box">
    <h2>Modifier mot de passe</h2>
    <form action="" method="POST">
        <div class="input-group">
            <img src="/image/Icons/password.webp">
            <input type="password" name="password" required placeholder="Nouveau mot de passe" value="<?= isset($_POST['password']) ? $_POST['password'] : '' ?>">
        </div>
        <div class="input-group">
            <img src="/image/Icons/password.webp">
            <input type="password" name="checkPassword" required placeholder="Confirmer nouveau mot de passe" value="<?= isset($_POST['checkPassword']) ? $_POST['checkPassword'] : '' ?>">
        </div>
        <?php if (isset($error)): ?><p class="error"><?= $error ?></p><?php endif ?>
        <button class="button center-container" type="submit"><img src="/image/icons/edit.webp">Modifier le mot de passe</button>
    </form>
</div>