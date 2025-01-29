<?php
class DatabaseHelper
{
    private $db;
    public function __construct($servername, $username, $password, $dbname, $port)
    {
        $this->db = new mysqli($servername, $username, $password, $dbname, $port);
        if ($this->db->connect_error) {
            die("Connessione al Database fallita: " . $this->db->connect_error);
        }
    }


    /*********************************************/
    /******************* QUERY *******************/
    /*********************************************/

    /**
     * Esempio.SELECT
     */
    public function esempioSelect()
    {
        $query = "SELECT * FROM user";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }



    /**
     * Esempio INSERT.
     */
    public function esempioInsert($a, $b, $c)
    {
        $query = "INSERT INTO notification (a, b, c) VALUES (?, ?, ?)";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param($a, $b, $c);
        $stmt->execute();
    }

    public function authUser($username, $password)
    {
        // Recupera l'utente dal database in base al nome utente
        $query = "SELECT * FROM user WHERE username = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("s", $username);  // Solo il nome utente, la password non serve nella query
        $stmt->execute();
        $result = $stmt->get_result();

        // Verifica se esiste l'utente
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();  // Ottieni i dati dell'utente

            // Verifica la password usando password_verify()
            if (password_verify($password, $user['password'])) {
                return $user;  // Autenticazione riuscita
            }
        }

        // Se l'utente non esiste o la password è errata, ritorna false
        return false;
    }
}
