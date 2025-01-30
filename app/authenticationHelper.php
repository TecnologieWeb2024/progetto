<?php
class AuthenticationHelper
{
    private $dbh;

    public function __construct($dbh)
    {
        $this->dbh = $dbh;
    }

    public function login()
    {
        // Ripulisce gli input
        $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
        $password = trim($_POST['password']);


        //unset($_POST['email'], $_POST['password']);

        // Chiama il metodo per autenticare l'utente
        $authResult = $this->dbh->authUser($email, $password);

        // Se l'autenticazione è riuscita
        if ($authResult !== false) {
            $this->setSessionData($authResult);
            session_regenerate_id(true);  // Rigenera l'ID della sessione per sicurezza
            return true;
        }

        // Se l'autenticazione fallisce
        return false;
    }

    private function setSessionData($authResult)
    {
        $_SESSION['auth_success'] = false; // di default l'autenticazione non è riuscita.

        unset($_SESSION['customer'], $_SESSION['seller']);

        $role = '';
        if ($authResult['role'] == 1) {
            $role = 'seller';
        } elseif ($authResult['role'] == 2) {
            $role = 'customer';
        }

        // Se c'è un ruolo valido, imposta i dati nella sessione
        if ($role !== '') {
            $_SESSION[$role]['first_name'] = $authResult['first_name'];
            $_SESSION[$role]['email'] = $authResult['email'];
            $_SESSION[$role]['role'] = $role;
            $_SESSION['user_id'] = $authResult['user_id'];
            $_SESSION['auth_success'] = true;
        }
    }

    public function logout()
    {
        // Rimuove tutte le variabili di sessione e distrugge la sessione
        session_unset();
        session_destroy();
    }
}
?>