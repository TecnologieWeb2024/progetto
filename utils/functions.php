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

function getBadgeClassFromstatusId(int $statusId): string
{
    return match (true) {
        in_array($statusId, [1, 2]) => 'badge-status-warning',
        in_array($statusId, [3]) => 'badge-status-indigo-600',
        in_array($statusId, [4, 5, 6]) => 'badge-status-success',
        default => 'badge-status-danger',
    };
}
