using CommunityToolkit.Mvvm.ComponentModel;
using Client_OPCUA25.Models;

namespace Client_OPCUA25.WebSocket;

internal class SubscriptionList : List<OPCWebSocketClient>
{
    private readonly ObservableObject monitoredObject;

    public Machines? MonitoredMachine { get; }

    public SubscriptionList(ObservableObject monitoredObject, Machines? monitoredMachine)
    {
        monitoredObject.PropertyChanged += async (sender, e) =>
        {
            // Assignation automatique des statuts des machines dans la page d'accueil quand ils changent
            if (e.PropertyName == nameof(MachineData.State))
            {
                if (monitoredObject == MachineDataMonitor.Tornos)
                {
                    MachineDataMonitor.Home.Tornos = MachineDataMonitor.Tornos.State;
                }
                else if (monitoredObject == MachineDataMonitor.DMG)
                {
                    MachineDataMonitor.Home.DMG = MachineDataMonitor.DMG.State;
                }
                else if (monitoredObject == MachineDataMonitor.Robodrill)
                {
                    MachineDataMonitor.Home.Robodrill = MachineDataMonitor.Robodrill.State;
                }
            }

            // Envoi des données à tous les clients abonnés dès que des données changent
            await Task.WhenAll(this.Select(c => c.SendJson(monitoredObject)));
        };
        this.monitoredObject = monitoredObject;
        MonitoredMachine = monitoredMachine;
    }

    /// <summary>
    /// Abonne un client à une liste
    /// </summary>
    /// <param name="client">Client à abonner</param>
    public async Task Subscribe(OPCWebSocketClient client)
    {
        // Ajout du client à la liste de souscription
        Add(client);
        // Envoi immédiat des données actuelles au client
        await client.SendJson(monitoredObject);
    }
}