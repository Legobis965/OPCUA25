using System.Runtime.Serialization;

namespace Client_OPCUA25.Models;

/// <summary>
/// Types de messages websocket
/// </summary>
internal enum MessageTypes
{
    [EnumMember(Value = "connection")]
    Connection,
    [EnumMember(Value = "pauseMachine")]
    PauseMachine
}

/// <summary>
/// Types de pages
/// </summary>
internal enum PageTypes
{
    [EnumMember(Value = "home")]
    Home,
    [EnumMember(Value = "machine")]
    Machine
}

/// <summary>
/// Liste des machines
/// </summary>
internal enum Machines
{
    [EnumMember(Value = "Tornos")]
    Tornos,
    [EnumMember(Value = "DMG")]
    DMG,
    [EnumMember(Value = "Robodrill")]
    Robodrill
}

/// <summary>
/// États possibles des machines
/// </summary>
internal enum MachineStates
{
    [EnumMember(Value = "offline")]
    Offline,
    [EnumMember(Value = "online")]
    Online,
    [EnumMember(Value = "production")]
    Production
}

/// <summary>
/// Modes possibles des machines
/// </summary>
internal enum MachineModes
{
    [EnumMember(Value = "MDI")]
    MDI = 100,
    [EnumMember(Value = "MEM")]
    MEM = 200,
    [EnumMember(Value = "EDIT")]
    EDIT = 300,
    [EnumMember(Value = "REMOTE")]
    RMT = 400,
    [EnumMember(Value = "JOG")]
    JOG = 500,
    [EnumMember(Value = "HANDLE")]
    HND = 600
}