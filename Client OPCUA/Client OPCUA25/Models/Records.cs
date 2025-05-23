namespace Client_OPCUA25.Models;

internal record ConnectionMessage(
    MessageTypes Type,
    string Token,
    PageTypes Page,
    Machines? Machine
);