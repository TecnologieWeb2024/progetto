<?php
require_once('core/formFieldsValidator.php');
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
        $email = trim($_POST['registration-email']);
        $password = trim($_POST['registration-password']);
        $first_name = trim($_POST['first_name']);
        $last_name = trim($_POST['last_name']);
        $phone = trim($_POST['phone']);
        // $role = 2; // Solo utenti di tipo "customer"
        $role = trim($_POST['tipoCliente']);;
        $address = ''; // TODO: Decidere se rimuovere dal database

        // Validazioni
        if(!FormFieldsValidator::validateEmail($email)) {
            return ['success' => false, 'message' => 'Email non valida.'];
        }

        if (!FormFieldsValidator::validatePassword($password)) {
            return ['success' => false, 'message' => 'La password non soddisfa i requisiti di sicurezza.'];
        }

        if (!FormFieldsValidator::validateName($first_name)) {
            return ['success' => false, 'message' => 'Nome non valido.'];
        }

        if (!FormFieldsValidator::validateName($last_name)) {
            return ['success' => false, 'message' => 'Cognome non valido.'];
        }

        if (!FormFieldsValidator::validatePhone($phone)) {
            return ['success' => false, 'message' => 'Numero di telefono non valido.'];
        }

        if (!FormFieldsValidator::validateAddress($address)) {
            return ['success' => false, 'message' => 'Indirizzo non valido.'];
        }

        // Registrazione utente
        return $this->dbh->registerUser($first_name, $last_name, $email, $password, $phone, $role, $address);

    }
}
