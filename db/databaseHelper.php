<?php
class DatabaseHelper
{
    private $db;
    public function __construct($servername, $username, $password, $dbname, $port)
    {
        $this->db = new mysqli($servername, $username, $password, $dbname, $port);
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

        // Imposta il charset a utf8mb4 per supportare emoji e caratteri speciali.
        $this->db->set_charset("utf8mb4");

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
     * @return array Un array con i campi dell'ordine.
     */
    public function getOrder($order_id)
    {
        $query = "SELECT * FROM `Order` WHERE order_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $order_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

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

    /**
     * Inserisce un ordine nel database.
     * @param int $user_id L'id dell'utente
     * @param array $products Un array di prodotti
     * @param int $shipment_id L'id della spedizione
     * @param int $payment_id L'id del pagamento
     * @return array Un array con il risultato dell'operazione
     */

    public function insertOrder($user_id, array $products, int $shipment_id, int $payment_id): array
    {
        // Inizia la transazione
        $this->db->begin_transaction();

        // Calcola il prezzo totale dell'ordine
        $total_price = $this->calculateTotalPrice($products);
        if ($total_price === false) {
            $this->db->rollback();
            return ['success' => false, 'message' => 'Errore nella creazione dell\'ordine: dati prodotto mancanti.'];
        }

        // Inserisce l'ordine
        $order_id = $this->insertOrderIntoDatabase($user_id, $total_price, $shipment_id, $payment_id);
        if ($order_id === false) {
            $this->db->rollback();
            return ['success' => false, 'message' => 'Errore nella creazione dell\'ordine. Impossibile inserire l\'ordine.'];
        }

        // Inserisce i dettagli dell'ordine
        if (!$this->insertOrderDetails($order_id, $products)) {
            $this->db->rollback();
            return ['success' => false, 'message' => 'Errore nella creazione dell\'ordine. Impossibile inserire i dettagli dell\'ordine.'];
        }

        // Completa la transazione
        $this->db->commit();

        return ['success' => true, 'message' => 'Ordine #' . $order_id . ' creato con successo.'];
    }

    /*********************** Funzioni di utility per l'inserimento di un ordine **********************/
    /**
     * Calcola il prezzo totale dell'ordine.
     * @param array $products Un array di prodotti
     * @return float|bool Il prezzo totale, false in caso di errore
     */
    private function calculateTotalPrice(array $products)
    {
        $total_price = 0;

        foreach ($products as $product) {
            if (!isset($product['product_id'], $product['quantity'], $product['price'])) {
                return false; // Se i dati del prodotto sono incompleti, ritorna false
            }
            $total_price += $product['quantity'] * $product['price'];
        }

        return $total_price;
    }

    /**
     * Esegue l'inserimento effettivo dell'ordine nel database.
     * @param int $user_id L'id dell'utente
     * @param float $total_price Il prezzo totale dell'ordine
     * @param int $shipment_id L'id della spedizione
     * @param int $payment_id L'id del pagamento
     * @return int|bool L'id dell'ordine appena creato, false in caso di errore
     */
    private function insertOrderIntoDatabase($user_id, $total_price, $shipment_id, $payment_id)
    {
        $query = "INSERT INTO `Order` (order_date, total_price, user_id, shipment_id, payment_id) VALUES (NOW(), ?, ?, ?, ?)";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("diii", $total_price, $user_id, $shipment_id, $payment_id);

        if (!$stmt->execute()) {
            return false;
        }

        return $this->db->insert_id; // Restituisce l'ID dell'ordine appena creato
    }

    /**
     * Inserisce i dettagli di un ordine nel database.
     * @param int $order_id L'id dell'ordine
     * @param array $products Un array di prodotti
     * @return bool True se l'inserimento è avvenuto con successo, false altrimenti
     */
    private function insertOrderDetails($order_id, array $products)
    {
        $query = "INSERT INTO Order_Detail (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($query);

        foreach ($products as $product) {
            $stmt->bind_param("iiid", $order_id, $product['product_id'], $product['quantity'], $product['price']);
            if (!$stmt->execute()) {
                return false;
            }
        }

        return true;
    }
}
?>
