<?php 

enum States: string 
{
    case Success = 'success';
    case Warning = 'warning';
    case Error = 'error';
}

function set_notification(States $state, string $message)
{
    $_SESSION['notification'] = [
        'state' => $state->value,
        'message' => $message
    ];
}