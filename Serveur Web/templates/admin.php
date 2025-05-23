<?php
    require $root . '/class/Database.php';
    $db = new Database();
    // Vérification si l'utilisateur est administrateur
    $users = $db->query('SELECT username, isAdmin FROM users', onlyFirst: false);
?>

<h2>Tableau de bord Administrateur</h2>
<a class="center-container button" href="/"> <img src="/image/Icons/home.webp">Retour à l'accueil</a>
<div class="center-container box">
    <table>
        <thead>
            <tr>
                <th>Nom d'utilisateur</th>
                <th class="action">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($users as $user): ?>
            <tr>
                <td><?= $user['username'] ?> <?php if($user['isAdmin']): ?><span>(administrateur)</span><?php endif ?></td> 
                <td class="action">
                    <?php if (!$user['isAdmin']): ?>
                    <a class="center-container button error" href="/admin/supprimer-utilisateur?utilisateur=<?= $user['username'] ?>" onclick="return confirm('Voulez-vous vraiment supprimer l\'utilisateur ' + username + ' ?')">
                        <img src="/image/icons/delete.webp">
                        Supprimer utilisateur
                    </a>
                    <?php endif ?>
                    <a class="center-container button" href="/admin/modifier-mot-de-passe?utilisateur=<?= $user['username'] ?>"><img src="/image/icons/edit.webp">Modifier mot de passe</a>
                </td>
            </tr>
             <?php endforeach ?>
        </tbody>
    </table>
</div>
<a href="../admin/nouvel-utilisateur" class="center-container button success"><img src="/image/icons/add user.webp">Ajouter un utilisateur</a>