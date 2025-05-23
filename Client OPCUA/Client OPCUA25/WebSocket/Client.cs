using Fleck;
using Newtonsoft.Json;
using Newtonsoft.Json.Linq;
using Newtonsoft.Json.Converters;
using Newtonsoft.Json.Serialization;
using Client_OPCUA25.Models;

namespace Client_OPCUA25.WebSocket;

internal class OPCWebSocketClient : IDisposable
{
    private const string TOKENS_DIR = @"C:\Windows\Temp\OPCUA25_tokens";

    private readonly WebSocketConnection socket;
    private readonly TaskCompletionSource<bool> tcs;
    private SubscriptionList? list;
    private bool disposed = false;

    public OPCWebSocketClient(WebSocketConnection socket)
    {
        this.socket = socket;
        tcs = new TaskCompletionSource<bool>();                       // Initialisation de la TCS
        socket.OnMessage = async msg => await OnReceivedMessage(msg); // Gérer la réception des messages
        socket.OnClose = Dispose;                                     // Gérer la déconnexion du client
        WaitForConnectionMessage();
    }

    /// <summary>
    /// Vérifie si le token est valide
    /// </summary>
    /// <param name="token">Token du client</param>
    /// <returns>Si l'utilisateur a pu être authentifié</returns>
    private static async Task<bool> Authenticate(string token)
    {
        // Vérifie l'existence du dossier
        if (Directory.Exists(TOKENS_DIR))
        {
            // Vérifie chaque fichier de token
            foreach (var file in Directory.GetFiles(TOKENS_DIR, "ws_token_*"))
            {
                var contenu = await File.ReadAllTextAsync(file);
                var parts = contenu.Split(':');
                if (parts.Length != 2) continue;

                // Vérifie la validité (1 minute)
                var timestamp = long.Parse(parts[1]);
                if ((DateTimeOffset.UtcNow.ToUnixTimeSeconds() - timestamp) > 60)
                {
                    // Supprime le fichier s'il n'est plus valide
                    File.Delete(file);
                    continue;
                }

                // Vérification du token
                if (token == parts[0]) return true;
            }
        }
        return false;
    }

    /// <summary>
    /// Attend que le client s'abonne à une liste ou un timeout de 10 secondes <br/>
    /// Si le timeout est atteint, la connexion est fermée
    /// </summary>
    private async void WaitForConnectionMessage()
    {
        // Attente du message de connexion ou du timeout de 10 secondes
        if (await Task.WhenAny(tcs.Task, Task.Delay(10000)) != tcs.Task)
        {
            // Timeout atteint, fermer la connexion
            Dispose();
        }
    }

    /// <summary>
    /// Gère la réception des messages du client
    /// </summary>
    /// <param name="message">Le message reçu en format JSON</param>
    private async Task OnReceivedMessage(string message)
    {
        // Le serveur ignore les messages qu'il n'arrive pas à lire
        try
        {
            // Récupération du type de message
            var messageTypeString = JObject.Parse(message)["type"]?.ToString();
            // Vérification et transformation du type en MessageTypes
            if (string.IsNullOrEmpty(messageTypeString) || !Enum.TryParse<MessageTypes>(char.ToUpper(messageTypeString[0]) + messageTypeString[1..], out var messageType)) return;

            // Lecture du JSON du message et traitement du message
            switch (messageType)
            {
                case MessageTypes.Connection when JsonConvert.DeserializeObject<ConnectionMessage>(message) is ConnectionMessage connectionMessage:
                    await OnConnectionMessageReceived(connectionMessage);
                    break;
            }
        }
        catch { }
    }

    /// <summary>
    /// Gère la réception d'un message de connexion
    /// </summary>
    /// <param name="connectionMessage">Le message de connexion reçu</param>
    private async Task OnConnectionMessageReceived(ConnectionMessage connectionMessage)
    {
        //Authentification de l'utilisateur
        if (!await Authenticate(connectionMessage.Token))
        {
            // Déconnexion s'il n'a pas pu être authentifié
            Dispose();
            return;
        }

        // Vérifie si le client n'est pas déjà abonné
        if (list is null)
        {
            // Recherche de la liste de souscription appropriée
            list = connectionMessage.Page switch
            {
                PageTypes.Home => ClientSubscriptionLists.HomeClients,
                PageTypes.Machine when connectionMessage.Machine is Machines machine => machine switch
                {
                    Machines.Tornos => ClientSubscriptionLists.TornosClients,
                    Machines.DMG => ClientSubscriptionLists.DMGClients,
                    Machines.Robodrill => ClientSubscriptionLists.RobodrillClients,
                    _ => null
                },
                _ => null
            };

            if (list is not null)
            {
                // Indique que le client s'est bien connecté
                tcs.TrySetResult(true);
                // Abonnement du client à la liste de souscription
                await list.Subscribe(this);
            }
            else
            {
                // Si aucune liste de souscription n'est trouvée le client est déconnecté
                Dispose();
            }
        }
    }

    /// <summary>
    /// Envoie des données au client
    /// </summary>
    /// <param name="data">Les données à envoyer</param>
    public async Task SendJson(object data) => await socket.Send(JsonConvert.SerializeObject(data, new JsonSerializerSettings
    {
        ContractResolver = new DefaultContractResolver
        {
            NamingStrategy = new CamelCaseNamingStrategy()
        },
        Converters = [new StringEnumConverter()]
    }));

    public void Dispose()
    {
        if (disposed) return; // Empêche un double appel
        disposed = true;

        // Fermeture du WebSocket
        socket.Close();
        // Désabonnement de la liste
        list?.Remove(this);
        // Suppression de l'objet
        GC.SuppressFinalize(this);
    }
}