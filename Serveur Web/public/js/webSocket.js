// 🔹 Déclaration des enumérations
const PageType = Object.freeze({
    HOME: "home",
    MACHINE: "machine",
});

const Machines = Object.freeze({
    TORNOS: "Tornos",
    DMG: "DMG",
    ROBODRILL: "Robodrill",
});

// 🔹 Class OPCWebSocket qui hérite de la class WebSocket
class OPCWebSocket extends WebSocket {
    constructor(pageType, machine = null) {
        super(`ws://${window.location.hostname}:8080`);

        // 🔹 Reçois un message et décode le JSON présent dans ce message
        this.onmessage = (event) => {
            let data = JSON.parse(event.data); // Décodage du JSON
            this.onReceivedMessage(data);
        };

        this.onopen = () => {
            // Envoi du message de connexion
            this.sendMessage({
                type: "connection",
                token: wsToken, // Token donné par le serveur
                page: pageType,
                machine: machine
            });
        };

        this.onclose = (e) => {
            // Affichage du message d'erreur si le serveur s'est déconnecté
            if (!e.wasClean) {
                this.showConnectionError();
            }
        };

        this.onerror = this.showConnectionError;

        // 🔹 Fermer proprement la connexion WebSocket lorsque l'utilisateur quitte la page
        window.addEventListener("beforeunload", () => {
            if (this.readyState === WebSocket.OPEN) {
                this.close();
            }
        });
    }

    showConnectionError() {
        setNotification(NotificationType.ERROR, "Erreur lors de la récupération des informations des machines");
    }

    // 🔹 Envoi d'un message au serveur 
    sendMessage(message) {
        let jsonString = JSON.stringify(message); // Encodage en JSON       
        this.send(jsonString);
    }

    // 🔹 Méthode appellée lors de la réception d'un message
    onReceivedMessage(data) { }
}