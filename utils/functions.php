<?php
function isUserLoggedIn()
{
    // TODO: Implement
    return true;
}



function hashPassword($password)
{
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
    return $hashedPassword;
}

function checkPassword($inputPassword, $hashedPassword)
{
    return password_verify($inputPassword, $hashedPassword);
}
?>