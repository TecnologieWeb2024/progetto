<?php
class DatabaseHelper
{
    private $db;
    public function __construct($servername, $username, $password, $dbname, $port)
    {
        $this->db = new mysqli($servername, $username, $password, $dbname, $port);
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

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

    public function authUser($email, $password)
    {
        // Recupera l'utente dal database in base al nome utente
        $query = "SELECT * FROM `User` WHERE email = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        // Verifica se esiste l'utente
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            // Verifica che gli hash delle password corrispondano
            if (password_verify($password, $user['passwordHash'])) {
                return $user;  // Autenticazione riuscita
            }
        }
        // Se l'utente non esiste o la password è errata, ritorna false
        return false;
    }

    public function registerUser($first_name, $last_name, $email, $password, $phone, $role, $address)
    {
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $query = "INSERT INTO `User`(`first_name`,`last_name`,`email`,`passwordHash`,`address`,`phone_number`,`role`) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("sssssis", $first_name, $last_name, $email, $passwordHash, $address, $phone, $role);
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    /* Query Prodotti */

    /**
     * Recupera un prodotto dal database in base all'id.
     * @param int $product_id
     * @return array|null
     */
    public function getProduct($product_id)
    {
        $query = "SELECT * FROM `Product` WHERE product_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    /**
     * Recupera i primi $n_products prodotti dal database.
     * @param int $n_products
     * @return array Un array di prodotti.
     */
    public function getProducts($n_products)
    {
        $query = "SELECT * FROM `Product` LIMIT ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $n_products);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Recupera tutti i prodotti dal database.
     * @return array Un array di prodotti.
     */
    public function getAllProducts()
    {
        $query = "SELECT * FROM `Product`";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /* Query Ordini */

    /**
     * Recupera un ordine dal database in base all'id.
     * @param int $order_id
     * @return array Un array di ordini.
     */
    public function getOrders($user_id, $n_orders)
    {
        $query = "SELECT * FROM `Order` WHERE user_id = ? LIMIT ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ii", $user_id, $n_orders);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Recupera tutti gli ordini di un utente dal database.
     * @param int $user_id L'id dell'utente
     * @return array Un array di ordini
     */
    public function getAllOrders($user_id)
    {
        $query = "SELECT * FROM `Order` WHERE user_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    
}
?>
