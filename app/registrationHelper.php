<?php
class RegistrationHelper
{
    public function register()
    {
        require_once('bootstrap.php');
        $errors = [];
        $email = filter_var($_POST['registration-email'], FILTER_VALIDATE_EMAIL);
        $password = trim($_POST['registration-password']);
        $first_name = htmlspecialchars(trim($_POST['first_name']));
        $last_name = htmlspecialchars(trim($_POST['last_name']));
        $role = 2; // È possibile registrare solo utenti di tipo customer dal form di registrazione.
        $phone = $_POST['phone'];

        if (!$email) {
            $errors[] = 'Email non valida.';
        }

        // Controllo numero di telefono (da 10 a 15 caratteri con il + all'inizio opzionale)
        if (!preg_match('/^\+?[0-9]{10,15}$/', $phone)) {
            $errors[] = 'Numero di telefono non valido.';
        }

        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }

        unset($_POST['registration-email'], $_POST['registration-password'], $_POST['first_name'], $_POST['last_name'], $_POST['phone']);

        $address = ''; // TODO: Da decidere se rimuovere da DB.

        $registrationResult = $dbh->registerUser($first_name, $last_name, $email, $password, $phone, $role, $address);
        unset($email, $password, $first_name, $last_name, $role, $phone, $address);

        if ($registrationResult) {
            return ['success' => true, 'message' => 'Registrazione avvenuta con successo.'];
        } else {
            return ['success' => false, 'errors' => ['Errore durante la registrazione.']];
        }
    }
}
?>