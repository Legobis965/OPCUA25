using Client_OPCUA25.Models;
using Client_OPCUA25.WebSocket;
using static Client_OPCUA25.InfluxDB;

namespace Client_OPCUA25.OPC_UA;

/// <summary>
/// Contient les listes de nœuds OPCUA à souscrire
/// </summary>
internal static class OPC_UASubscriptions
{
    /// <summary>
    /// Liste des nœuds à envoyer à InfluxDB
    /// </summary>
    public static InfluxDbNodeSubscription[] InfluxDbSubscription { get; } = [
        new() { Measure = Measures.ROBODRILL, Field = "machinedparts", NodeId = "ROBODRILL.NUMBERMACHINEDPARTS" },
        new() { Measure = Measures.DMG, Field = "machinedparts", NodeId = "DMG.NUMBERMACHINEDPARTS" },
        new() { Measure = Measures.TORNOS, Field = "machinedparts", NodeId = "TORNOS.NUMBERMACHINEDPARTS" }
    ];

    /// <summary>
    /// Liste des nœuds en tableau à envoyer à InfluxDB
    /// </summary>
    public static InfluxDbArrayNodeSubscription[] InfluxDbArraySubscription { get; } = [
        new() { Measure = Measures.ROBODRILL, Field = "coord", NodeId = "ROBODRILL.AXISPOSITION" },
        new() { Measure = Measures.DMG, Field = "coord", NodeId = "DMG.AXISPOSITION" },
        new() { Measure = Measures.TORNOS, Field = "coord", NodeId = "TORNOS.AXISPOSITION" }
    ];

    /// <summary>
    /// Liste des nœuds à envoyer au websocket
    /// </summary>
    public static WebSocketNodeSubscription[] WebSocketSubscription { get; } = [
        // Nœuds Robodrill
        new(MachineDataMonitor.Robodrill, nameof(MachineData.OperatorMessage)) { NodeId = "ROBODRILL.OPERATORMESSAGES" },
        new(MachineDataMonitor.Robodrill, nameof(MachineData.CurrentProgram)) { NodeId = "ROBODRILL.ACTIVEPROGRAMCOMMENT" },
        new(MachineDataMonitor.Robodrill, nameof(MachineData.AlarmMessage)) { NodeId = "ROBODRILL.ALARMMESSAGES" },
        new(MachineDataMonitor.Robodrill, nameof(MachineData.State), OPC_UAParsers.ParseState) { NodeId = "ROBODRILL.CNC_STATUS" },
        new(MachineDataMonitor.Robodrill, nameof(MachineData.Mode), OPC_UAParsers.ParseMode) { NodeId = "ROBODRILL.CNC_MODE" },

        // Nœuds DMG
        new(MachineDataMonitor.DMG, nameof(MachineData.OperatorMessage)) { NodeId = "DMG.OPERATORMESSAGES" },
        new(MachineDataMonitor.DMG, nameof(MachineData.CurrentProgram)) { NodeId = "DMG.ACTIVEPROGRAMCOMMENT" },
        new(MachineDataMonitor.DMG, nameof(MachineData.AlarmMessage)) { NodeId = "DMG.ALARMMESSAGES" },
        new(MachineDataMonitor.DMG, nameof(MachineData.State), OPC_UAParsers.ParseState) { NodeId = "DMG.CNC_STATUS" },
        new(MachineDataMonitor.DMG, nameof(MachineData.Mode), OPC_UAParsers.ParseMode) { NodeId = "DMG.CNC_MODE" },

        // Nœuds Tornos
        new(MachineDataMonitor.Tornos, nameof(MachineData.OperatorMessage)) { NodeId = "TORNOS.OPERATORMESSAGES" },
        new(MachineDataMonitor.Tornos, nameof(MachineData.CurrentProgram)) { NodeId = "TORNOS.ACTIVEPROGRAMCOMMENT" },
        new(MachineDataMonitor.Tornos, nameof(MachineData.AlarmMessage)) { NodeId = "TORNOS.ALARMMESSAGES" },
        new(MachineDataMonitor.Tornos, nameof(MachineData.State), OPC_UAParsers.ParseState) { NodeId = "TORNOS.CNC_STATUS" },
        new(MachineDataMonitor.Tornos, nameof(MachineData.Mode), OPC_UAParsers.ParseMode) { NodeId = "TORNOS.CNC_MODE" }
    ];
}