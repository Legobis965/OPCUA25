using Client_OPCUA25.Models;

namespace Client_OPCUA25.WebSocket;

/// <summary>
/// Listes de souscription auquelles les clients s'abonnent <br/>
/// Chaque liste observe un objet de données de machine et envoie les données à tous les clients abonnés
/// </summary>
internal static class ClientSubscriptionLists
{
    public static SubscriptionList HomeClients { get; private set; }
    public static SubscriptionList TornosClients { get; private set; }
    public static SubscriptionList DMGClients { get; private set; }
    public static SubscriptionList RobodrillClients { get; private set; }

    /// <summary>
    /// Initialise les listes de souscription pour chaque machine et pour la page d'accueil
    /// </summary>
    public static void Initialize()
    {
        HomeClients = new SubscriptionList(MachineDataMonitor.Home, null);
        TornosClients = new SubscriptionList(MachineDataMonitor.Tornos, Machines.Tornos);
        DMGClients = new SubscriptionList(MachineDataMonitor.DMG, Machines.DMG);
        RobodrillClients = new SubscriptionList(MachineDataMonitor.Robodrill, Machines.Robodrill);
    }
}

/// <summary>
/// Enregistre les données des machines en direct
/// </summary>
internal static class MachineDataMonitor
{
    public static HomeData Home { get; private set; } = new HomeData();
    public static MachineData Tornos { get; private set; } = new MachineData();
    public static MachineData DMG { get; private set; } = new MachineData();
    public static MachineData Robodrill { get; private set; } = new MachineData();
}