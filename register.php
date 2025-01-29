<?php
class RegistrationHelper
{
    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return 'Invalid request method.';
        }

        require_once('bootstrap.php');
        $email = filter_var($_POST['registration-email'], FILTER_VALIDATE_EMAIL);
        $password = trim($_POST['registration-password']);
        $first_name = htmlspecialchars(trim($_POST['first_name']));
        $last_name = htmlspecialchars(trim($_POST['last_name']));
        $role = 2; // Only customers can register through the website
        $phone = $_POST['phone'];

        unset($_POST['registration-email'], $_POST['registration-password'], $_POST['first_name'], $_POST['last_name'], $_POST['phone']);

        if (!$email) {
            return 'Email non valida.';
        }

        // Sanitizzazione del telefono
        if (!preg_match("/^\+?[0-9]{10,15}$/", $phone)) {
            return 'Numero di telefono non valido.';
        }

        
        $address = '';
        $registrationResult = $dbh->registerUser($first_name, $last_name, $email, $password, $phone, $role, $address);
        unset($email, $password, $first_name, $last_name, $role, $phone, $address);
        echo $registrationResult;
        return $registrationResult === true ? 'Registrazione avvenuta con successo.' : 'Errore durante la registrazione.';
    }
}

$registrationHelper = new RegistrationHelper();
try {
    $registrationResult = $registrationHelper->register();
} catch (Exception $e) {
    return $e->getMessage();
}

echo $registrationResult;
if ($registrationResult !== true) {
?>
    <div class="alert alert-danger" role="alert"> <?php echo ($registrationResult) ?> </div>;
<?php
} else {
?>
    <div class="alert alert-success" role="alert"> <?php echo ($registrationResult) ?> </div>;
<?php
}
header('Location: index.php');
?>
<div>Result: <?php var_dump($registrationResult) ?></div>