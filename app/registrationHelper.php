<?php
class RegistrationHelper
{
    private $dbh;

    public function __construct($dbh)
    {
        $this->dbh = $dbh;
    }

    public function register()
    {
        require_once('bootstrap.php');

        // Pulizia e validazione input
        $email = filter_var($_POST['registration-email'], FILTER_VALIDATE_EMAIL);
        $password = trim($_POST['registration-password']);
        $first_name = htmlspecialchars(trim($_POST['first_name']));
        $last_name = htmlspecialchars(trim($_POST['last_name']));
        $phone = trim($_POST['phone']);
        $role = 2; // Solo utenti di tipo "customer"
        $address = ''; // TODO: Decidere se rimuovere dal database

        // Validazioni
        if (!$email) {
            return ['success' => false, 'message' => 'Email non valida.'];
        }

        if (!$this->validatePassword($password)) {
            return ['success' => false, 'message' => 'La password non soddisfa i requisiti di sicurezza.'];
        }

        if (!$this->validatePhone($phone)) {
            return ['success' => false, 'message' => 'Numero di telefono non valido.'];
        }

        // Registrazione utente
        try {
            $registrationResult = $this->dbh->registerUser($first_name, $last_name, $email, $password, $phone, $role, $address);

            if (!$registrationResult) {
                throw new RuntimeException('Errore durante la registrazione.');
            }

            return ['success' => true, 'message' => 'Registrazione avvenuta con successo.'];
        } catch (Exception $e) {
            // Logga l'errore e restituisci un messaggio generico
            error_log('Errore nella registrazione: ' . $e->getMessage()); // Salva nei log dell'applicazione
            return ['success' => false, 'message' => 'Si è verificato un errore, riprova più tardi.'];
        }
    }

    private function validatePassword($password)
    {
        return preg_match('/^(?=.*\d)(?=.*[A-Z])(?=.*[a-z])(?=.*[\W_]).{8,}$/', $password);
    }

    private function validatePhone($phone)
    {
        return preg_match('/^\+?[0-9]{10,15}$/', $phone);
    }
}
