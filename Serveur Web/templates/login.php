<?php
    require $root . '/class/Database.php';

    $error = false;

    // Si le nom d'utilisateur et le mot de passe sont saisis le formulaire est traité 
    if (isset($_POST['username'], $_POST['password'])) {
        // Récupération de l'utilisateur dans la base de données
        $db = new Database();
        $result = $db->query('SELECT password, isAdmin FROM users WHERE username = :username', ['username' => $_POST['username']]);

        // Vérification de l'utilisateur et du mot de passe 
        if ($result != null && password_verify($_POST['password'], $result['password'])) {
            // Connexion de l'utilisateur
            $_SESSION['isLoggedIn'] = true;
            $_SESSION['username'] = $_POST['username'];
            $_SESSION['isAdmin'] = $result['isAdmin'];
            header('Location: /');
            exit;
        } else {
            // Affichage du message d'erreur
            $error = true;
        }
    }
?>

<div class="center-container main">
    <div class="background-image"></div>
    <div class="container">
        <div class="welcome-section center-container">
            <div class="center-container title">
                <img src="/image/logo lycée.png">
                <h1>Bienvenue sur l'ilôt connecté !</h1>
            </div>
            <p>Accédez à votre espace personnalisé et profitez d’une gestion simplifiée de vos machines connectées.</p>
        </div>
        <div class="login-container center-container">
            <h2>Se connecter</h2>
            <form action="" method="POST">
                <div class="input-group">
                    <img src="/image/Icons/user.webp">
                    <input type="username" id="username" name="username" required placeholder="Identifiant" value="<?= isset($_POST['username']) ? $_POST['username'] : '' ?>">
                </div>
                <div class="input-group">
                    <img src="/image/Icons/password.webp">
                    <input type="password" id="password" name="password" required placeholder="Mot de passe" value="<?= isset($_POST['password']) ? $_POST['password'] : '' ?>">
                </div>
                <?php if ($error): ?><p class="error">Identifiant ou mot de passe incorrect</p><?php endif ?>
                <button class="button" type="submit">Se connecter</button>
            </form>
        </div>
    </div>
</div>