<?php
function isUserLoggedIn()
{
    return $_SESSION['auth']['success'] ?? false;
}

?>