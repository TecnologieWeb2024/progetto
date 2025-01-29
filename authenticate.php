<?php
class Authenticator
{
    public function login($email, $password)
    {
        // Ripulisce gli input
        $email = trim($email);
        $password = trim($password);

        // Chiama il metodo per autenticare l'utente
        require_once('bootstrap.php');
        $authResult = $this->authUser($email, $password);

        // Se l'autenticazione è riuscita
        if ($authResult !== false) {
            $this->setSessionData($authResult);
            session_regenerate_id(true);  // Rigenera l'ID della sessione per sicurezza
            return true;
        }

        // Se l'autenticazione fallisce
        return false;
    }

    private function authUser($email, $password)
    {
        global $dbh;
        return $dbh->authUser($email, $password);
    }

    private function setSessionData($authResult)
    {
        // Rimuove eventuali dati di sessione preesistenti
        unset($_SESSION['customer'], $_SESSION['seller']);

        // Determina il ruolo
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

$authenticator = new Authenticator();

if (isset($_POST['login-email'], $_POST['login-password'])) {
    if ($authenticator->login($_POST['login-email'], $_POST['login-password'])) {
        header('Location: index.php');
    } else {
        $loginError = 'Email o password errati.';
    }
}