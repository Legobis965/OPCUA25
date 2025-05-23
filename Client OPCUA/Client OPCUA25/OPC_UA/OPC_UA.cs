using Opc.UaFx.Client;

namespace Client_OPCUA25.OPC_UA;

/// <summary>
/// Gère la connexion au serveur OPCUA et les souscriptions
/// </summary>
internal static class OPC_UAClient
{
    private static OpcClient client;

    /// <summary>
    /// Se connecte au serveur OPC UA.
    /// </summary>
    public static void Connect()
    {
        try
        {
            // Création du client
            client = new OpcClient("opc.tcp://localhost:59611/");
            client.Security.UserIdentity = new OpcClientIdentity("admin", "admin");

            // Connexion au serveur
            Console.WriteLine("Connecting to OPC UA server...");
            client.Connect();
            Console.WriteLine("Connected to OPC UA server.");

            // Abonnement aux nœuds
            Console.WriteLine("Subscribing to nodes ...");
            Subscribe(OPC_UASubscriptions.InfluxDbSubscription);
            Subscribe(OPC_UASubscriptions.InfluxDbArraySubscription);
            Subscribe(OPC_UASubscriptions.WebSocketSubscription);
            Console.WriteLine("Subscribed to nodes.");
        }
        catch (Exception ex)
        {
            throw new Exception($"Erreur lors de la connexion au serveur OPCUA: {ex.Message}");
        }
    }

    /// <summary>
    /// Abonne une liste de nœuds pour recevoir des notifications de changement de données
    /// </summary>
    /// <param name="nodes">Liste à abonner</param>
    public static void Subscribe(object[] nodes) => nodes
        .Cast<OPCNodeSubscription>()
        .ToList()
        .ForEach(n => client.SubscribeDataChange($"ns=2;s={n.NodeId}", (sender, e) => n.Handler(sender, e)));
}