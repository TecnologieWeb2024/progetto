<?php
require_once("app/alertGenerator/alertStrategy.php");

class Alert
{
    private $strategy;
    private $message;

    public function __construct(AlertStrategy $strategy, $message)
    {
        $this->strategy = $strategy;
        $this->message = $message;
    }

    public function display()
    {
        echo $this->strategy->getAlertMessage($this->message);
    }
}
?>
