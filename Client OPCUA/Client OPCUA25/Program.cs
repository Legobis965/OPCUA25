using Fleck;
using Client_OPCUA25.OPC_UA;
using Client_OPCUA25.WebSocket;

namespace Client_OPCUA25;

internal class Program
{
    private static void HandleExceptionAsync(Exception ex)
    {
        Console.WriteLine($"Erreur : {ex.Message}");
        File.AppendAllText("OPCUA_client_errors.log", $"[{DateTime.Now}] {ex}\n\n");
    }
    
    public static async Task Main()
    {
        try
        {
            // Initialisation du serveur WebSocket
            var wsServer = new WebSocketServer("ws://0.0.0.0:8080");
            wsServer.Start(c => _ = new OPCWebSocketClient((c as WebSocketConnection)!));

            // Initialisation des listes de souscription
            ClientSubscriptionLists.Initialize();

            while (true)
            {
                try
                {
                    // Reconnexion au serveur OPC UA
                    OPC_UAClient.Connect();
                    // Attente infinie
                    await Task.Delay(Timeout.Infinite);
                }
                catch (Exception ex)
                {
                    HandleExceptionAsync(ex);
                    await Task.Delay(2000); // Petite pause avant de réessayer
                }
            }
        }
        catch (Exception ex)
        {
            HandleExceptionAsync(ex);
        }
    }
}