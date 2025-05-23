using System.Reflection;
using System.Globalization;
using Opc.UaFx;
using Opc.UaFx.Client;
using CommunityToolkit.Mvvm.ComponentModel;

namespace Client_OPCUA25.OPC_UA;

/// <summary>
/// Classe de base des nœuds OPCUA
/// </summary>
internal abstract class OPCNodeSubscription
{
    public required string NodeId { get; init; }

    /// <summary>
    /// Gère les changements de données pour le nœud OPCUA
    /// </summary>
    /// <param name="sender">Nœud modifié</param>
    /// <param name="e">Nouvelles valeurs du nœud</param>
    public abstract void Handler(object sender, OpcDataChangeReceivedEventArgs e);
}

/// <summary>
/// Nœud OPCUA à envoyer dans InfluxDB
/// </summary>
internal class InfluxDbNodeSubscription : OPCNodeSubscription
{
    public required string Measure { get; init; }
    public required string Field { get; init; }

    public override void Handler(object sender, OpcDataChangeReceivedEventArgs e)
    {
        // Convertion de la valeur en double
        if (double.TryParse(e.Item.Value.ToString(), out var value))
        {
            // Envoi de la valeur à InfluxDB
            InfluxDB.SendValue(Measure, Field, value);
        }
    }
}

/// <summary>
/// Nœud OPCUA en tableau à envoyer dans InfluxDB
/// </summary>
internal class InfluxDbArrayNodeSubscription : InfluxDbNodeSubscription
{
    public override void Handler(object sender, OpcDataChangeReceivedEventArgs e)
    {
        // Considération de la valeur comme un tableau de string
        if (e.Item.Value.Value is string[] array)
        {
            for (int i = 0; i < array.Length; i++)
            {
                // Conversion de la valeur en double
                if (double.TryParse(array[i], CultureInfo.InvariantCulture, out var value))
                {
                    // Envoi de la valeur à InfluxDB
                    InfluxDB.SendValue(Measure, Field + i.ToString(), value);
                }
            }
        }
    }
}

/// <summary>
/// Nœud OPCUA à envoyer dans le websocket
/// </summary>
/// <param name="monitoredObject">Objet machine à modifier</param>
/// <param name="propertyName">Propriété à modifier</param>
/// <param name="parser">Fonction pour convertir les valeurs</param>
internal class WebSocketNodeSubscription(ObservableObject monitoredObject, string propertyName, Func<OpcValue, object>? parser = null) : OPCNodeSubscription
{
    private readonly ObservableObject monitoredObject = monitoredObject;
    private readonly PropertyInfo propertyInfo = monitoredObject.GetType().GetProperty(propertyName) ?? throw new ArgumentException($"Property '{propertyName}' not found on type '{monitoredObject.GetType()}'");
    private readonly Func<OpcValue, object>? parser = parser;

    public override void Handler(object sender, OpcDataChangeReceivedEventArgs e)
    {
        // Récupération de la valeur et du type
        var value = e.Item.Value;
        var targetType = propertyInfo.PropertyType;

        // Conversion et assignation de la valeur
        propertyInfo.SetValue(monitoredObject, parser is not null ? parser(value) : value.ToString());
    }
}