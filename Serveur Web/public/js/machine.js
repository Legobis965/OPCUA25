// 🔹 Déclaration des constantes et sélection des éléments HTML
const scrollButton = document.querySelector(".scroll-button");
const checkbox = document.getElementById("toggle");
const historyDivs = document.querySelectorAll(".history");
const realtimeDivs = document.querySelectorAll(".realtime");
const realtimeSelector = document.getElementById("realtime-selector");
const iframes = document.querySelectorAll("iframe");
const startDateTime = document.getElementById("start-datetime");
const endDateTime = document.getElementById("end-datetime");
const statusIndicator = document.querySelector(".status-indicator");
const mode = document.getElementById("mode");
const currentProgram = document.getElementById("current-program");
const alarmMessage = document.getElementById("alarm-message");
const operatorMessage = document.getElementById("operator-message");
const restartMachine = document.getElementById("restart-machine");
const pauseMachine = document.getElementById("pause-machine");
  

// 🔹 Obtention de la machine en cours
var machineName;
if (window.location.href.includes("/machines/tour")) {
    machineName = Machines.DMG;
} else if (window.location.href.includes("/machines/centre-de-fraisage")) {
    machineName = Machines.ROBODRILL;
} else if (window.location.href.includes("/machines/decolleteuse")) {
    machineName = Machines.TORNOS;
}

// 🔹 Création de la classe MachineWebSocket qui hérite de OPCWebSocket
class MachineWebSocket extends OPCWebSocket {
    constructor() {
        super(PageType.MACHINE, machineName);
    }

    // 🔹 Réception et affichage des données d'un message
    onReceivedMessage(data) {
        statusIndicator.classList.remove("online", "offline", "production");
        statusIndicator.classList.add(data.state);
        mode.innerText = data.mode?.trim() ? data.mode : "N/A";
        alarmMessage.innerText = data.alarmMessage?.trim() ? data.alarmMessage : "N/A";
        operatorMessage.innerText = data.operatorMessage?.trim() ? data.operatorMessage : "N/A";
        currentProgram.innerText = data.currentProgram?.trim() ? data.currentProgram : "N/A";
    }
}

let webSocket = new MachineWebSocket();

// 🔹 Affichage du bouton scrollTOP
window.addEventListener("scroll", () => {
    if (window.scrollY >= 40) {
        scrollButton.classList.add("visible");
    } else {
        scrollButton.classList.remove("visible");
    }
});

// 🔹 Affichage temps réel / historique
function toggleVisibility() {
    realtimeDivs.forEach(div => div.classList.toggle("hidden", !checkbox.checked));
    historyDivs.forEach(div => div.classList.toggle("hidden", checkbox.checked));
    updateIframesTimeRange();
}

// 🔹 Exécuter une première fois pour l'état initial
toggleVisibility();

// 🔹 Mettre à jour les iframes en fonction du thème
function updateIframeTheme() {
    iframes.forEach(iframe => {
        let url = new URL(iframe.src);
        url.searchParams.set("theme", theme); // Change le paramètre theme
        iframe.src = url.toString();
    });
}

// 🔹 Actualiser les graphiques en fonction de l'interval choisi 
function updateIframesTimeRange() {
    if (checkbox.checked) {
        iframes.forEach(iframe => {
            let IframeUrl = new URL(iframe.src);
            IframeUrl.searchParams.set("from", realtimeSelector.value);
            IframeUrl.searchParams.set("to", "now");
            iframe.src = IframeUrl.toString();
        });
    } else {
        iframes.forEach(iframe => {
            let iframesUrl = new URL(iframe.src);
            iframesUrl.searchParams.set("from", new Date(startDateTime.value).getTime());
            iframesUrl.searchParams.set("to", new Date(endDateTime.value).getTime());
            iframe.src = iframesUrl.toString();
        });
    }
}