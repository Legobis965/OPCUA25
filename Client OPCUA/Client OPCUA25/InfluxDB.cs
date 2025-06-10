using InfluxDB.Client;
using InfluxDB.Client.Writes;
using InfluxDB.Client.Api.Domain;

namespace Client_OPCUA25;

/// <summary>
/// Gère la connexion à InfluxDB
/// </summary>
internal static class InfluxDB
{
    public static class Measures
    {
        public const string ROBODRILL = "ROBODRILL";
        public const string DMG = "DMG";
        public const string TORNOS = "TORNOS";
    }

    public static class Credentials
    {
        public const string TOKEN = "R6AIhiDpzW6pTCJYj2Ka1dZgwUlg6jHKTuDRg64vMeXu22bBC7iU5vJ3pk-CNXYspYJR9v_jhVfkOUcb4k0pzA==";
        public const string SERVER_URI = "http://localhost:8086/";
        public const string BUCKET = "IlotConnecte";
        public const string ORG = "Opcua25";
    }   

    private static readonly InfluxDBClient client = new(Credentials.SERVER_URI, Credentials.TOKEN);
    private static readonly WriteApi writeApi = client.GetWriteApi();

    /// <summary>
    /// Envoie une valeur dans la base de données
    /// </summary>
    /// <param name="measure">Nom de la mesure</param>
    /// <param name="field">Champ de la valeur</param>
    /// <param name="value">Valeur à envoyer</param>
    public static void SendValue(string measure, string field, double value)
    {
        try
        {
            var point = PointData
                .Measurement(measure)
                .Field(field, value)
                .Timestamp(DateTime.Now, WritePrecision.Ns);
            writeApi.WritePoint(point, Credentials.BUCKET, Credentials.ORG);
        }
        catch (Exception ex)
        {
            throw new Exception("Erreur lors de l'envoi d'une mesure à InfluxDB : \n" + ex.Message);
        }
    }
}