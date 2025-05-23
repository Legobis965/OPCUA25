<h2>Accueil</h2>
<div class="center-container">
    <a href="/machines/tour/" class="box container-machine flex-column" >
        <img src="/image/Tour.png" alt="Machine Tour">
        <h2 class="machine-name">Tour</h2>
        <div class="state" id="dmg"></div>
    </a>
    <a href="/machines/centre-de-fraisage/" class="box container-machine flex-column" >
        <img src="/image/Centre de Fraisage.png" alt="Machine Fraiseuse">
        <h2 class="machine-name">Centre de Fraisage</h2>
        <div class="state" id="robodrill"></div>
    </a>
    <a href="/machines/decolleteuse/" class="box container-machine flex-column" >
        <img src="/image/Décolleteuse.png" alt="Machine Perceuse">
        <h2 class="machine-name">Décolleteuse</h2>
        <div class="state" id="tornos"></div>
    </a>
</div>
<?php if ($_SESSION['isAdmin']): ?> <a href="/admin" class="margin-center admin button">Accéder à la page d'administration</a> <?php endif ?>

<script src="/js/webSocket.js"></script>
<script src="/js/home.js"></script>