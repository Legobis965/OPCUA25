// Déclaration des constantes/variables
const dmg = document.getElementById("dmg");
const robodrill = document.getElementById("robodrill");
const tornos = document.getElementById("tornos");

// Création de la classe HomeWebSocket qui hérite de OPCWebSocket
class HomeWebSocket extends OPCWebSocket {
    constructor() {
        super(PageType.HOME);
    }

    // Suppression de la class précédente et ajout de la class envoyé par le serveur
    onReceivedMessage(data) {
        tornos.classList.remove("online", "offline", "production");
        robodrill.classList.remove("online", "offline", "production");
        dmg.classList.remove("online", "offline", "production");

        tornos.classList.add(data.tornos);
        robodrill.classList.add(data.robodrill);
        dmg.classList.add(data.dmg);
    }
}

// Création 
new HomeWebSocket();