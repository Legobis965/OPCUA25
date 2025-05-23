<?php 
// Récupération de l'url en fonction de la machine
    switch ($url) {
        case '/machines/tour':
            $machineName = 'Tour';
            break;

        case '/machines/centre-de-fraisage':
            $machineName = 'Centre de Fraisage';
            break;
        
        case '/machines/decolleteuse':
            $machineName = 'Décolleteuse';
            break;
    } 
?>

<section>
    <div class="control-panel flex-column">
        <div class="machine-name flex-column">
            <img src="/image/<?= $machineName ?>.png">      
            <div>
                <h2><?= $machineName ?></h2>
                <div class="status-indicator"></div>
            </div>
        </div>
        <div class="controls-container flex-column">
            <div class="controls flex-column">
                <div class="flex-column">
                    <label class="center-container checkbox">
                        <input type="checkbox" id="toggle" onchange="toggleVisibility()" checked>
                        <div></div>
                        <p>Données en direct</p>
                    </label> 
                    <div class="realtime">
                        <select class="center-container" id="realtime-selector" onchange="updateIframesTimeRange()">
                            <option value="now-10s">10 dernières secondes</option>
                            <option value="now-30s">30 dernières secondes</option>
                            <option value="now-60s">60 dernières secondes</option>
                            <option value="now-5m">5 dernières minutes</option>
                            <option value="now-15m">15 dernières minutes</option>
                            <option value="now-30m">30 dernières minutes</option>
                            <option value="now-60m">60 dernières minutes</option>
                            <option value="now-3h">3 dernières heures</option>
                            <option value="now-6h">6 dernières heures</option>
                            <option value="now-12h">12 dernières heures</option>
                            <option value="now-24h">24 dernières heures</option>
                            <option value="now-2d">2 derniers jours</option>
                            <option value="now-7d">7 derniers jours</option>
                            <option value="now-30d">30 derniers jours</option>
                        </select>
                    </div>
                    <div class="history">
                        <p>Date de début :</p>
                        <input type="datetime-local" id="start-datetime" onchange="updateIframesTimeRange()">
                    </div>
                    <div class="history">
                        <p>Date de fin :</p>
                        <input type="datetime-local" id="end-datetime" onchange="updateIframesTimeRange()">
                    </div>
                </div>
                <button class="center-container button error" id="stop-machine"><img src="/image/Icons/stop.webp">Arrêter la machine</button>
                <!-- <button class="center-container button warning" id="pause-machine"><img src="/image/Icons/pause.webp">Mettre en pause la machine</button> -->
                <button class="center-container button success" id="restart-machine"><img src="/image/Icons/play.webp">Relancer la machine</button>
                <a class="center-container button" href="/"> <img src="/image/Icons/home.webp">Retour à l'accueil</a>
            </div>
        </div>
    </div>
    <div class="stats">
        <div class="box messages">
            <div class="flex-column">
                <div class="text-icon center-container">
                    <img src="/image/icons/alert.webp">
                    <p>Message d'alerte : <span id="alarm-message"></span></p>
                </div>
                <div class="text-icon center-container">
                    <img src="/image/icons/info.webp">
                    <p>Message opérateur : <span id="operator-message"></span></p>
                </div>
            </div>
            <div class="flex-column">
                <div class="text-icon center-container">
                    <img src="/image/icons/gear.webp">
                    <p>Programme en cours : <span id="current-program"></span></p>
                </div>
                <div class="text-icon center-container">
                    <img src="/image/icons/switch.webp">
                    <p>Mode : <span id="mode"></span></p>
                </div>
            </div>
        </div>
        <div class="graph">