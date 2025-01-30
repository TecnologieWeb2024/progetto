<?php
require_once("core/alertGenerator/alert.php");
require_once("core/alertGenerator/successAlertStrategy.php");
require_once("core/alertGenerator/dangerAlertStrategy.php");

class AlertFactory
{
    private static $strategies = [
        'success' => SuccessAlertStrategy::class,
        'danger' => DangerAlertStrategy::class
    ];

    public static function createAlert($type, $message)
    {
        if (!isset(self::$strategies[$type])) {
            throw new Exception("Unknown alert type");
        }
        return new Alert(new self::$strategies[$type](), $message);
    }
}
?>