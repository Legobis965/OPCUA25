using System.Runtime.Serialization;
using Client_OPCUA25.Models;
using Opc.UaFx;

namespace Client_OPCUA25.OPC_UA;

/// <summary>
/// Contient les fonctions pour convertir les valeurs OPCUA
/// </summary>
internal static class OPC_UAParsers
{
    /// <summary>
    /// Converti une <see cref="string"/> en <see cref="MachineStates"/>
    /// </summary>
    /// <param name="value">Valeur OPCUA</param>
    /// <returns>Statut de la machine</returns>
    public static object ParseState(OpcValue value)
    {
        if (value.ToString() == "START")
            return MachineStates.Production;
        else if (value.Status.CodeBits == (uint)OpcStatusCode.BadNotConnected)
            return MachineStates.Offline;
        else
            return MachineStates.Online;
    }


    /// <summary>
    /// Converti une <see cref="string"/> en <see cref="MachineModes"/>
    /// </summary>
    /// <param name="value">Valeur OPCUA</param>
    /// <returns>Mode de la machine</returns>
    public static object ParseMode(OpcValue value)
    {
        foreach (var field in typeof(MachineModes).GetFields())
        {
            var attribute = field
                .GetCustomAttributes(typeof(EnumMemberAttribute), false)
                .Cast<EnumMemberAttribute>()
                .FirstOrDefault();

            if (attribute?.Value == value.ToString() && field.GetValue(null) is MachineModes mode) return mode;
        }

        throw new NotImplementedException();
    }
}