<?php
function isUserLoggedIn()
{
    return $_SESSION['auth']['success'] ?? false;
}

function isUserCustomer()
{
    return array_key_exists('customer', $_SESSION);
}
function isUserSeller()
{
    return array_key_exists('seller', $_SESSION);
}
?>