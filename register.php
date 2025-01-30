<?php
require_once('app/registrationHelper.php');
$registrationHelper = new RegistrationHelper();
try {
    $registrationResult = $registrationHelper->register();
} catch (Exception $e) {
    return $e->getMessage();
}

if($registrationResult === true) {
    header('Location: login.php');
} else {
    echo $registrationResult;
}
?>
