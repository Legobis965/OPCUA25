<?php
// Destruction de la seesion + redirection
session_destroy();
header('Location: /login');