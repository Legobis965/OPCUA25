<?php
    require $root . '/class/Database.php';
    require $root . '/class/fonctions.php';

    if (isset($_POST['username'], $_POST['password'], $_POST['checkPassword'], $_POST['isAdmin'])) {
        $db = new Database();
        $existingUser = $db->query('SELECT username FROM users', onlyFirst: false);
        
        // Remapper pour avoir un tableau simple des usernames
        $usernames = array_column($existingUser, 'username');

        // Si les deux mots de passe correspondent
        if ($_POST['password'] !== $_POST['checkPassword']) {
            $error = 'Les mots de passe ne correspondent pas';
        }

        // Si l'utilisateur existe déjà dans la base de données
        elseif (in_array($_POST['username'], $usernames)) {
            $error = "L'utilisateur {$_POST['username']} existe déjà";
        }

        // Si le mot de passe contient moins de 8 caractères
        elseif (strlen($_POST['password']) < 8) {
            $error = 'Le mot de passe doit contenir au moins 8 caractères';
        } else {
            try {
                // Éxécution de la requête SQL
                $request = $db->query('INSERT INTO users (username, password, isAdmin) VALUES (:username, :password, :isAdmin)', 
                [
                    'username' => $_POST['username'],
                    'password' => password_hash($_POST['password'], PASSWORD_DEFAULT),
                    'isAdmin'  => $_POST['isAdmin']
                ]);

                // Envoi de la notification quand un utilisateur est créé 
                set_notification(States::Success, "{$_POST['username']} a bien été créé.");
            } catch (Exception) {
                set_notification(States::Error, "Erreur lors de l'ajout de {$_POST['username']}");
            }

            header('Location: /admin');
            exit;
        }
    }
?>

<link rel="stylesheet" href="/css/admin-form.css">

<a href="/admin" class="margin-center button">Retour à la page d'administration</a>
<div class="box">
    <h2>Nouvel utilisateur</h2>
    <form action="" method="POST">
        <div class="input-group">
            <img src="/image/Icons/user.webp">
            <input type="username" name="username" required placeholder="Identifiant" value="<?= isset($_POST['username']) ? $_POST['username'] : '' ?>">
        </div>
        <div class="input-group">
            <img src="/image/Icons/password.webp">
            <input type="password" name="password" required placeholder="Mot de passe" value="<?= isset($_POST['password']) ? $_POST['password'] : '' ?>">
        </div>
        <div class="input-group">
            <img src="/image/Icons/password.webp">
            <input type="password" name="checkPassword" required placeholder="Confirmer Mot de passe" value="<?= isset($_POST['checkPassword']) ? $_POST['checkPassword'] : '' ?>">
        </div>
        <select class="center-container" name="isAdmin">
            <option value="0">Utilisateur</option>
            <option value="1">Administrateur</option>
        </select>
        <?php if (isset($error)): ?><p class="error"><?= $error ?></p><?php endif ?>
        <button class="button center-container" type="submit"><img src="/image/icons/add user.webp">Créer utilisateur</button>
    </form>
</div>