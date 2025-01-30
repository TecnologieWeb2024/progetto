<?php
require_once('core/formFieldsValidator.php');
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
        $email = trim($_POST['email']);
        $password = trim($_POST['password']);

        // Validazione degli input
        if (!FormFieldsValidator::validateEmail($email)) {
            return ['success' => false, 'message' => 'Email non valida.'];
        }

        if (!FormFieldsValidator::validatePassword($password)) {
            return ['success' => false, 'message' => 'Password non valida.'];
        }

        $authResult = $this->dbh->authUser($email, $password);
        if ($authResult !== false) {
            $this->setSessionData($authResult);
            session_regenerate_id(true);  // Rigenera l'ID della sessione per sicurezza
            return ['success' => true, 'message' => 'Autenticazione riuscita.'];
        }
        return ['success' => false, 'message' => 'Si è verificato un errore, riprova più tardi.'];
    }

    private function setSessionData($authResult)
    {
        unset($_SESSION['customer'], $_SESSION['seller']); // Pulisce le sessioni esistenti

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
        }
    }

    public function logout()
    {
        // Inizia la sessione, se non è già stata avviata
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Elimina tutte le variabili di sessione
        unset($_SESSION['customer'], $_SESSION['seller'], $_SESSION['user_id'], $_SESSION['auth']['success']);

        // Elimina il cookie di sessione se esiste
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }

        // Distrugge la sessione
        session_destroy();
        header("Location: index.php");
    }
}
