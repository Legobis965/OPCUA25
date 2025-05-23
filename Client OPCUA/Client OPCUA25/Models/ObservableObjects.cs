using CommunityToolkit.Mvvm.ComponentModel;

namespace Client_OPCUA25.Models;

internal partial class HomeData : ObservableObject
{
    [ObservableProperty]
    public partial MachineStates Tornos { get; set; }

    [ObservableProperty]
    public partial MachineStates DMG { get; set; }

    [ObservableProperty]
    public partial MachineStates Robodrill { get; set; }
}

internal partial class MachineData : ObservableObject
{
    [ObservableProperty]
    public partial MachineStates State { get; set; }

    [ObservableProperty]
    public partial string CurrentProgram { get; set; }

    [ObservableProperty]
    public partial string AlarmMessage { get; set; }

    [ObservableProperty]
    public partial string OperatorMessage { get; set; }

    [ObservableProperty]
    public partial MachineModes? Mode { get; set; }
}