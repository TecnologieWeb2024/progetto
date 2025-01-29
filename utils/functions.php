<?php
function isUserLoggedIn()
{
    return $_SESSION["auth_success"] ?? false;
}

?>