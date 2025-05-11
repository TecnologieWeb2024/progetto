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

    /* ############################### Query User ############################### */

    /**
     * Verifica che mail e password siano corretti e autentica l'utente.
     * @param string $email
     * @param string $password
     * @return array|array Un array con i dati dell'utente se l'autenticazione è riuscita o ['success' => false, 'message' => '...'] in caso di errore.
     */
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
            return ['success' => false, 'message' => 'Password errata'];
        }
        // Se l'utente non esiste o la password è errata, ritorna false
        return ['success' => false, 'message' => 'Utente non trovato'];
    }

    /**
     * Registra un nuovo utente nel database.
     * @param string $first_name Nome
     * @param string $last_name Cognome
     * @param string $email Email
     * @param string $password Password
     * @param string $phone Numero di telefono
     * @param int $role Ruolo (1 = venditore, 2 = cliente)
     * @param string $address Indirizzo
     * @return array ['success' => true|false, 'message' => '...'].
     */
    public function registerUser($first_name, $last_name, $email, $password, $phone, $role, $address)
    {
        $this->db->begin_transaction();
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $query = "INSERT INTO `User`(`first_name`,`last_name`,`email`,`passwordHash`,`address`,`phone_number`,`role`) VALUES (?, ?, ?, ?, ?, ?, ?)";

        try {
            $stmt = $this->db->prepare($query);
            $stmt->bind_param("sssssis", $first_name, $last_name, $email, $passwordHash, $address, $phone, $role);

            if ($stmt->execute() === false) {
                $this->db->rollback();
                return ['success' => false, 'message' => 'Errore nella registrazione dell\'utente.'];
            }
            $this->db->commit();
            return ['success' => true, 'message' => 'Registrazione avvenuta con successo.'];
        } catch (mysqli_sql_exception $e) {
            $this->db->rollback();
            // Puoi verificare l'errore specifico (es. duplicate entry) e personalizzare il messaggio
            if ($e->getCode() === 1062) { // Duplicate entry
                return ['success' => false, 'message' => 'L\'email è già in uso.'];
            }
            return ['success' => false, 'message' => 'Errore nella registrazione: ' . $e->getMessage()];
        }
    }

    /**
     * Aggiorna la password di un utente nel database.
     * @param int $user_id
     * @param string $new_password
     * @return array ['success' => true|false, 'message' => '...'] in base all'esito dell'operazione.
     */
    public function updatePassword(int $user_id, string $new_password): array
    {
        // Controlla la password attuale dell'utente
        $query = "SELECT passwordHash FROM `User` WHERE user_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $user_id); // Cambiato "s" in "i"
        $stmt->execute();
        $result = $stmt->get_result();

        // Se l'utente non esiste
        if ($result->num_rows === 0) {
            return ['success' => false, 'message' => 'Utente non trovato.'];
        }

        $old_password = $result->fetch_assoc()['passwordHash'];

        // La nuova password non può essere uguale a quella attuale
        if (password_verify($new_password, $old_password)) {
            return ['success' => false, 'message' => 'La nuova password non può essere uguale a quella attuale.'];
        }

        $new_passwordHash = password_hash($new_password, PASSWORD_DEFAULT);

        $this->db->begin_transaction();
        $query = "UPDATE `User` SET passwordHash = ? WHERE user_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("si", $new_passwordHash, $user_id);

        if (!$stmt->execute()) {
            $this->db->rollback();
            return ['success' => false, 'message' => 'Errore nell\'aggiornamento della password.'];
        }

        if ($this->db->commit()) {
            return ['success' => true, 'message' => 'Password aggiornata con successo, effettuare nuovamente il login.'];
        }
        $this->db->rollback();
        return ['success' => false, 'message' => 'Errore nel salvataggio della password: rollback eseguito.'];
    }

    /**
     * Recupera un utente dal database in base all'id.
     * @param int $user_id
     * @return array|null
     */
    public function getUserInfo($user_id)
    {
        $query = "SELECT * FROM `User` WHERE user_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    /* ############################### Query Prodotti ############################### */

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

    /**
     * Recupera i prodotti di un venditore dal database.
     * @param int $seller_id L'id del venditore
     * @return array Un array di prodotti.
     */
    public function getProductsBySeller($seller_id)
    {
        $query = "SELECT * FROM `Product` WHERE seller_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $seller_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Aggiorna la disponibilità di un prodotto nel database.
     * @param int $id_product
     */
    public function changeAvailability(int $product_id)
    {
        $query = "SELECT available FROM `Product` WHERE product_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $result = $stmt->get_result();


        $available = $result->fetch_assoc()['available'];

        $this->db->begin_transaction();
        if ($available == 1) {
            $query = "UPDATE `Product` SET available = 0 WHERE product_id = ?";
        } else {
            $query = "UPDATE `Product` SET available = 1 WHERE product_id = ?";
        }

        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $product_id);

        if (!$stmt->execute()) {
            $this->db->rollback();
            return ['success' => false, 'message' => 'Errore nell\'aggiornamento della disponibilità.'];
        }

        if ($this->db->commit()) {
            return ['success' => true, 'message' => 'Disponibilità aggiornata con successo.'];
        }
        $this->db->rollback();
        return ['success' => false, 'message' => 'Errore nel salvataggio della disponibilità: rollback eseguito.'];
    }

    /* ############################### Query Carrello ############################### */

    /**
     * Ritorna l'id del carrello associato a un utente.
     * @param int $user_id L'id dell'utente.
     * @return int|array L'id del carrello o ['success' => false, 'message' => '...'] in caso di errore.
     */
    public function getCartId($user_id)
    {
        $query = "SELECT cart_id FROM Cart WHERE user_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $cart = $result->fetch_assoc();

        if (!$cart) {
            return ['success' => false, 'message' => 'Carrello non trovato.'];
        }
        return $cart['cart_id'];
    }

    /**
     * Calcola il prezzo totale dei prodotti nel carrello.
     * @param array $cartProducts
     * @return float Il prezzo totale.
     */

    /**
     * Ritorna tutti i prodotti presenti nel carrello di un utente e il totale.
     * @param int $user_id
     * 
     * @return array Un array con i prodotti e il totale: ['products' => [...], 'total_price' => ...].
     */
    public function getCartProducts($user_id)
    {
        $query = "
            SELECT
                p.product_id,
                p.product_name,
                p.product_description,
                p.price AS price,
                p.stock,
                p.image,
                cd.quantity
            FROM
                `Cart` c
            JOIN
                `Cart_Detail` cd ON c.cart_id = cd.cart_id
            JOIN
                `Product` p ON cd.product_id = p.product_id
            WHERE
                c.user_id = ?
        ";

        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        $cartProducts = [];

        while ($row = $result->fetch_assoc()) {
            $cartProducts[] = [
                'product_id'            => $row['product_id'],
                'product_name'          => $row['product_name'],
                'product_description'   => $row['product_description'],
                'price'                 => $row['price'],
                'image'                 => $row['image'],
                'stock'                 => $row['stock'],
                'quantity'              => $row['quantity']
            ];
        }
        $total_price = $this->calculateTotalPrice($cartProducts);

        return ['products' => $cartProducts, 'total_price' => $total_price];
    }

    /**
     * Modifica la quantità di un prodotto nel carrello.
     * @param int $user_id L'id dell'utente.
     * @param int $product_id L'id del prodotto.
     * @param int $quantity La nuova quantità.
     * 
     * @return array ['success' => true|false, 'message' => '...'] in base all'esito dell'operazione.
     */
    public function changeCartProductQuantity($user_id, $product_id, $quantity)
    {
        // Verifica che la quantità non sia negativa
        if ($quantity < 0) {
            return ['success' => false, 'message' => 'La quantità non può essere negativa.'];
        }

        // Controlla se il prodotto esiste nel carrello
        $query = "SELECT cd.quantity 
                    FROM Cart_Detail cd
                    JOIN Cart c ON cd.cart_id = c.cart_id
                    WHERE c.user_id = ? AND cd.product_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ii", $user_id, $product_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $cart_product = $result->fetch_assoc();

        if (!$cart_product) {
            return ['success' => false, 'message' => 'Prodotto non presente nel carrello.'];
        }

        // Se la quantità è 0, rimuovi il prodotto dal carrello
        if ($quantity == 0) {
            return $this->removeProductFromCart($user_id, $product_id);
        }

        if (!$this->productExists($product_id)) {
            return ['success' => false, 'message' => 'Prodotto non esistente.'];
        }
        // Controlla la quantità massima disponibile nel magazzino
        $query = "SELECT stock FROM Product WHERE product_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $product = $result->fetch_assoc();

        // Se la quantità richiesta è maggiore della disponibilità, imposta la quantità al massimo disponibile.
        $quantity = min($quantity, $product['stock']);

        $this->db->begin_transaction();
        // Aggiorna la quantità del prodotto nel carrello
        $query = "UPDATE Cart_Detail cd
            JOIN Cart c ON cd.cart_id = c.cart_id
            SET cd.quantity = ?
            WHERE c.user_id = ? AND cd.product_id = ?
        ";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("iii", $quantity, $user_id, $product_id);

        if ($stmt->execute() && $stmt->affected_rows > 0) {
            $this->db->commit();
            return ['success' => true, 'message' => 'Quantità aggiornata con successo.'];
        }
        $this->db->rollback();
        return ['success' => false, 'message' => 'Errore nell\'aggiornamento della quantità.'];
    }

    /**
     * Rimuove un prodotto dal carrello.
     * @param int $user_id L'id dell'utente.
     * @param int $product_id L'id del prodotto.
     * 
     * @return array ['success' => true|false, 'message' => '...'] in base all'esito dell'operazione.
     */
    public function removeProductFromCart($user_id, $product_id)
    {
        if (!$this->productExists($product_id)) {
            return ['success' => false, 'message' => 'Prodotto non esistente.'];
        }

        $this->db->begin_transaction();
        $query = "DELETE cd
            FROM Cart_Detail cd
            JOIN Cart c ON cd.cart_id = c.cart_id
            WHERE c.user_id = ? AND cd.product_id = ?
        ";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ii", $user_id, $product_id);
        $stmt->execute();

        // Controlla se l'eliminazione è avvenuta con successo
        if ($stmt->affected_rows > 0) {
            $this->db->commit();
            return ['success' => true, 'message' => 'Prodotto rimosso dal carrello.'];
        } else {
            $this->db->rollback();
            return ['success' => false, 'message' => 'Prodotto non presente nel carrello.'];
        }
    }

    /**
     * Aggiunge un prodotto al carrello di un utente.
     * @param int $user_id
     * @param int $product_id
     * @param int $quantity
     * 
     */
    public function addToCart($user_id, $product_id, $quantity)
    {
        $this->db->begin_transaction();
        $cart_id = $this->getOrCreateCart($user_id);

        if (!$this->productExists($product_id)) {
            $this->db->rollback();
            return ['success' => false, 'message' => "Prodotto non esistente."];
        }

        if ($this->productInCart($cart_id, $product_id)) {
            $this->updateCartItem($cart_id, $product_id, $quantity);
        } else {
            $this->insertCartItem($cart_id, $product_id, $quantity);
        }
        $this->db->commit();
        return ['success' => true, 'message' => "Prodotto aggiunto al carrello."];
    }

   /**
    * Svuota il carrello di un utente.
    * @param int $user_id
    * @return array ['success' => true|false, 'message' => '...'] in base all'esito dell'operazione.
    */
    public function clearCart($user_id)
    {
        $query = "DELETE cd
            FROM Cart_Detail cd
            JOIN Cart c ON cd.cart_id = c.cart_id
            WHERE c.user_id = ?
        ";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        return ['success' => true, 'message' => "Carrello svuotato."];
    }

    /* ********************* funzioni di utility per *********************
       ********************* l'aggiunta al carrello  ********************* */

    /**
     * Controlla se un utente ha un carrello e lo crea se non esiste.
     * @param int $user_id
     * @return int L'id del carrello.
     */
    private function getOrCreateCart($user_id)
    {
        $stmt = $this->db->prepare("SELECT cart_id FROM Cart WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->bind_result($cart_id);
        $stmt->fetch();
        $stmt->close();

        if (!$cart_id) {
            $stmt = $this->db->prepare("INSERT INTO Cart (user_id) VALUES (?), created_at = NOW(), updated_at = NOW()");
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $cart_id = $stmt->insert_id;
            $stmt->close();
        }

        return $cart_id;
    }

    /**
     * Verifica se un prodotto esiste nel database.
     * @param int $product_id
     * @return bool True se il prodotto esiste, false altrimenti.
     */
    private function productExists($product_id)
    {
        $stmt = $this->db->prepare("SELECT 1 FROM Product WHERE product_id = ?");
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $stmt->store_result();
        $exists = $stmt->num_rows > 0;
        $stmt->close();
        return $exists;
    }

    /**
     * Verifica se un prodotto è già presente nel carrello.
     * @param int $cart_id
     * @param int $product_id
     * @return bool True se il prodotto è già presente, false altrimenti.
     */
    private function productInCart($cart_id, $product_id)
    {
        $stmt = $this->db->prepare("SELECT 1 FROM Cart_Detail WHERE cart_id = ? AND product_id = ?");
        $stmt->bind_param("ii", $cart_id, $product_id);
        $stmt->execute();
        $stmt->store_result();
        $exists = $stmt->num_rows > 0;
        $stmt->close();
        return $exists;
    }

    /**
     * Aggiorna la quantità di un prodotto nel carrello.
     * @param int $cart_id
     * @param int $product_id
     * @param int $quantity
     */
    private function updateCartItem($cart_id, $product_id, $quantity)
    {
        $stmt = $this->db->prepare("UPDATE Cart_Detail SET quantity = quantity + ? WHERE cart_id = ? AND product_id = ?");
        $stmt->bind_param("iii", $quantity, $cart_id, $product_id);
        $stmt->execute();
        $stmt->close();
    }

    /**
     * Inserisce un prodotto nel carrello.
     * @param int $cart_id
     * @param int $product_id
     * @param int $quantity
     */
    private function insertCartItem($cart_id, $product_id, $quantity)
    {
        $price = $this->getProductPrice($product_id);

        $stmt = $this->db->prepare("INSERT INTO Cart_Detail (cart_id, product_id, quantity) VALUES (?, ?, ?)");
        $stmt->bind_param("iii", $cart_id, $product_id, $quantity);
        $stmt->execute();
        $stmt->close();
    }

    /**
     * Recupera i prodotti presenti nel carrello di un utente.
     * @param int $user_id
     * @return array Un array di prodotti.
     */
    private function getProductPrice($product_id)
    {
        $stmt = $this->db->prepare("SELECT price FROM Product WHERE product_id = ?");
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $stmt->bind_result($price);
        $stmt->fetch();
        $stmt->close();
        return $price;
    }

    /* ############################### Query Ordini ############################### */

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
        $query = "SELECT * FROM `Order` WHERE user_id = ? ORDER BY order_date DESC LIMIT ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ii", $user_id, $n_orders);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Recupera tutti gli ordini di un utente dal database.
     * @param int $user_id L'id dell'utente
     * @return array Un array di ordini.
     */
    public function getAllUserOrders($user_id)
    {
        $query = "SELECT * FROM `Order` WHERE user_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Recupera tutti gli ordini per un venditore dal database.
     * @param int $seller_id L'id del venditore
     * @return array Un array di ordini.
     */
    public function getAllSellerOrders($seller_id)
    {
        $query = "SELECT DISTINCT o.*
                    FROM `Order` o
                    JOIN `Order_Detail` od  ON o.order_id = od.order_id
                    JOIN `Product` p        ON od.product_id = p.product_id
                    WHERE p.seller_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $seller_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Recupera un ordine dal database in base all'id.
     * @param int $order_id
     * @return array Un array con i campi dell'ordine.
     */
    public function getOrdersByStatus($seller_id, $order_status_id)
    {
        $query = "SELECT DISTINCT o.*
                    FROM `Order` o
                    JOIN `Order_Detail` od  ON o.order_id = od.order_id
                    JOIN `Product` p        ON od.product_id = p.product_id
                    WHERE p.seller_id     = ?
                    AND o.order_status_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ii", $seller_id, $order_status_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Recupera gli ordini in attesa di essere accettati, ossia "pagato".
     * @param int $seller_id L'id del venditore
     * @return array Un array di ordini in attesa.
     */
    public function getWaitingOrders($seller_id)
    {
        return array_merge(
            $this->getOrdersByStatus($seller_id, 1),
            $this->getOrdersByStatus($seller_id, 2),
            $this->getOrdersByStatus($seller_id, 3)
        );
    }

    /**
     * Recupera gli ordini accettati, ossia "in preparazione", "spedito" e "consegnato".
     * @param int $seller_id L'id del venditore
     * @return array Un array di ordini accettati.
     */
    public function getAcceptedOrders($seller_id)
    {
        return array_merge(
            $this->getOrdersByStatus($seller_id, 4),
            $this->getOrdersByStatus($seller_id, 5),
            $this->getOrdersByStatus($seller_id, 6)
        );
    }

    /**
     * Recupera gli ordini rifiutati, ossia "rifiutato" e "rimborsato".
     * @param int $seller_id L'id del venditore
     * @return array Un array di ordini rifiutati o rimborsati.
     */
    public function getCanceledOrders($seller_id)
    {
        return array_merge(
            $this->getOrdersByStatus($seller_id, 7),
            $this->getOrdersByStatus($seller_id, 8)
        );
    }

    /**
     * Recupera tutti gli ordini  dal database.
     * @return array Un array di ordini.
     */
    public function getAllOrders()
    {
        $query = "SELECT * FROM `Order`";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getOrderStatus($order_id)
    {
        $query = "SELECT `Order_Status`.`order_status_id`, `Order_Status`.`descrizione` 
                    FROM `Order_Status`, `Order` 
                    WHERE `Order`.order_id = ? AND `Order`.order_status_id = `Order_Status`.order_status_id";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $order_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    /**
     * Recupera tutti i prodotti all'interno di un ordine dal database.
     * @param int $order_id L'id dell'ordine
     * @return array|array Un array di prodotti o ['success' => false, 'message' => '...'] in caso di errore.
     */
    public function getOrderProducts($order_id)
    {
        // Prepara la query SQL per ottenere i dettagli dell'ordine
        $query = "
            SELECT
                p.product_id,
                p.product_name    AS product_name,
                p.price          AS product_price,
                p.image,
                od.quantity
            FROM
                `Order_Detail` od
            JOIN
                `Product` p ON od.product_id = p.product_id
            WHERE
                od.order_id = ?;
        ";

        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $order_id);
        if (!$stmt->execute()) {
            return ['success' => false, 'message' => 'Impossibile ottenere i dettagli dell\'ordine #' . $order_id];
        }

        $result = $stmt->get_result();

        $products = [];

        while ($row = $result->fetch_assoc()) {
            $products[] = [
                'product_id'    => $row['product_id'],
                'product_name'  => $row['product_name'],
                'price'         => $row['product_price'],
                'image'         => $row['image'],
                'quantity'      => $row['quantity']
            ];
        }
        return $products;
    }

    /**
     * Inserisce un ordine nel database.
     * @param int   $user_id        L'id dell'utente
     * @param array $products       Un array di prodotti e quantità
     * @param int   $order_status_id L'id dello stato iniziale dell'ordine
     * @param int   $shipment_id    L'id della spedizione
     * @return array ['success' => true|false, 'message' => '...']
     */
    public function insertOrder(int $user_id, array $products, int $order_status_id, int $shipment_id): array
    {
        $this->db->begin_transaction();

        $total_price = $this->calculateTotalPrice($products);
        if ($total_price === false) {
            $this->db->rollback();
            return ['success' => false, 'message' => 'Errore nella creazione dell\'ordine: dati prodotto mancanti.'];
        }

        // PASSIAMO ORA $order_status_id invece di $payment_id
        $order_id = $this->insertOrderIntoDatabase($user_id, $total_price, $order_status_id, $shipment_id);
        if ($order_id === false) {
            $this->db->rollback();
            return ['success' => false, 'message' => 'Errore nella creazione dell\'ordine. Impossibile inserire l\'ordine.'];
        }

        if (!$this->insertOrderDetails($order_id, $products)) {
            $this->db->rollback();
            return ['success' => false, 'message' => 'Errore nella creazione dell\'ordine. Impossibile inserire i dettagli dell\'ordine.'];
        }

        $this->db->commit();
        return ['success' => true, 'message' => 'Ordine #' . $order_id . ' creato con successo.', 'data' => $order_id];
    }

    public function updateOrderStatus(int $order_id, int $new_status): array
    {
        $this->db->begin_transaction();
        $query = "UPDATE `Order` SET order_status_id = ? WHERE order_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ii", $new_status, $order_id);

        if (!$stmt->execute()) {
            $this->db->rollback();
            return ['success' => false, 'message' => 'Errore nell\'aggiornamento dello stato dell\'ordine.'];
        }

        if ($this->db->commit()) {
            return ['success' => true, 'message' => 'Stato dell\'ordine aggiornato con successo.'];
        }
        $this->db->rollback();
        return ['success' => false, 'message' => 'Errore nel salvataggio dello stato dell\'ordine: rollback eseguito.'];
    }



    public function insertShipping(int $order_id, int $shipment_id): array
    {
        $this->db->begin_transaction();
        $query = "UPDATE `Order` SET shipment_id = ? WHERE order_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ii", $shipment_id, $order_id);

        if (!$stmt->execute()) {
            $this->db->rollback();
            return ['success' => false, 'message' => 'Errore nell\'inserimento della spedizione.'];
        }

        if ($this->db->commit()) {
            return ['success' => true, 'message' => 'Spedizione inserita con successo.', 'data' => $shipment_id];
        }
        $this->db->rollback();
        return ['success' => false, 'message' => 'Errore nel salvataggio della spedizione: rollback eseguito.'];
    }

    /* ********************* funzioni di utility per *********************
       ********************* l'inserimento di ordini ********************* */

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
     * @param int   $user_id        L'id dell'utente
     * @param float $total_price    Il prezzo totale dell'ordine
     * @param int   $order_status_id L'id dello stato iniziale dell'ordine
     * @param int   $shipment_id    L'id della spedizione
     * @return int|bool             L'id dell'ordine appena creato, false in caso di errore
     */
    private function insertOrderIntoDatabase(int $user_id, float $total_price, int $order_status_id, $shipment_id = null)
    {
        $query = "
        INSERT INTO `Order` (
            order_date,
            total_price,
            user_id,
            order_status_id,
            shipment_id
        ) VALUES (
            NOW(),
            ?,
            ?,
            ?,
            ?
        )
    ";

        try {
            $stmt = $this->db->prepare($query);
            // d = double (per decimal), i = integer
            $stmt->bind_param("diii", $total_price, $user_id, $order_status_id,$shipment_id);
            if ($stmt->execute() === false) {
                return false;
            }
        } catch (mysqli_sql_exception $e) {
            // In caso di eccezione, ritorna false per permettere il rollback a monte
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
        $query = "INSERT INTO Order_Detail (order_id, product_id, quantity) VALUES (?, ?, ?)";
        try {
            $stmt = $this->db->prepare($query);

            foreach ($products as $product) {
                $stmt->bind_param("iii", $order_id, $product['product_id'], $product['quantity']);
                if ($stmt->execute() === false) {
                    return false;
                }
            }
        } catch (mysqli_sql_exception $e) {
            return false;
        }

        return true;
    }

    /**
     * Registra un nuovo pagamento per un ordine.
     * @param int    $order_id
     * @param int    $payment_method_id
     * @param float  $amount
     * @param int    $status                // payment_status_id
     * @param string $transaction_reference
     * @return array ['success' => true|false, 'data' => int|null, 'message' => '...']
     */
    public function insertPayment(int $order_id, int $payment_method_id, float $amount, int $status, string $transaction_reference): array
    {
        $this->db->begin_transaction();
        $query = "INSERT INTO `Payment` (
            order_id,
            payment_date,
            payment_method_id,
            amount,
            status,
            transaction_reference
        ) VALUES (
            ?, NOW(), ?, ?, ?, ?
        )
    ";
        try {
            $stmt = $this->db->prepare($query);
            $stmt->bind_param("iidds", $order_id, $payment_method_id, $amount, $status, $transaction_reference);
            if (!$stmt->execute()) {
                $this->db->rollback();
                return ['success' => false, 'data' => null, 'message' => 'Errore inserimento pagamento.'];
            }
            $payment_id = $stmt->insert_id;
            $this->db->commit();
            return ['success' => true, 'data' => $payment_id, 'message' => 'Pagamento registrato con successo.'];
        } catch (mysqli_sql_exception $e) {
            $this->db->rollback();
            return ['success' => false, 'data' => null, 'message' => 'Eccezione durante pagamento: ' . $e->getMessage()];
        }
    }

    /**
     * Aggiorna lo stato di un pagamento.
     * @param int $payment_id
     * @param int $new_status            // payment_status_id
     * @return array ['success' => true|false, 'message' => '...']
     */
    public function updatePaymentStatus(int $payment_id, int $new_status): array
    {
        $query = "
        UPDATE `Payment`
        SET status = ?
        WHERE payment_id = ?
    ";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ii", $new_status, $payment_id);
        if ($stmt->execute() && $stmt->affected_rows > 0) {
            return ['success' => true, 'message' => 'Stato pagamento aggiornato.'];
        }
        return ['success' => false, 'message' => 'Nessun pagamento aggiornato o ID non valido.'];
    }

    /**
     * Recupera i dettagli di pagamento per un ordine.
     * @param int $order_id
     * @return array ['success' => true|false, 'data' => array|null, 'message' => '...']
     */
    public function getPaymentDetails(int $order_id): array
    {
        $query = "
        SELECT
            payment_id,
            order_id,
            payment_date,
            payment_method_id,
            amount,
            status,
            transaction_reference
        FROM `Payment`
        WHERE order_id = ?
    ";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $order_id);
        if (!$stmt->execute()) {
            return ['success' => false, 'data' => null, 'message' => 'Impossibile recuperare pagamento.'];
        }
        $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        return ['success' => true, 'data' => $result, 'message' => 'Dettagli pagamento recuperati.'];
    }

    /**
     * Crea un nuovo record di spedizione.
     * @param string $address
     * @param string $tracking_number           // può essere inizialmente null o placeholder
     * @param string $shipping_method
     * @return array ['success' => true|false, 'data' => int|null, 'message' => '...']
     */
    public function createShipment(string $address, ?string $tracking_number, int $shipping_method, int $shipping_status): array
    {
        $this->db->begin_transaction();
        $query = "INSERT INTO `Shipment` (
            shipment_date,
            address,
            tracking_number,
            shipping_method,
            status
        ) VALUES (
            NOW(), ?, ?, ?, ?
        )
    ";
        try {
            $stmt = $this->db->prepare($query);
            $stmt->bind_param("ssii", $address, $tracking_number, $shipping_method, $shipping_status);
            if (!$stmt->execute()) {
                $this->db->rollback();
                return ['success' => false, 'data' => null, 'message' => 'Errore creazione spedizione.'];
            }
            $shipment_id = $stmt->insert_id;
            $this->db->commit();
            return ['success' => true, 'data' => $shipment_id, 'message' => 'Spedizione creata con successo.'];
        } catch (mysqli_sql_exception $e) {
            $this->db->rollback();
            return ['success' => false, 'data' => null, 'message' => 'Eccezione spedizione: ' . $e->getMessage()];
        }
    }

    /**
     * Aggiorna lo stato e/o tracking di una spedizione.
     * @param int    $shipment_id
     * @param string $new_status
     * @param string $new_tracking_number
     * @return array ['success' => true|false, 'message' => '...']
     */
    public function updateShipmentStatus(int $shipment_id, string $new_status, ?string $new_tracking_number): array
    {
        $query = "
        UPDATE `Shipment`
        SET status = ?, tracking_number = ?
        WHERE shipment_id = ?
    ";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ssi", $new_status, $new_tracking_number, $shipment_id);
        if ($stmt->execute() && $stmt->affected_rows > 0) {
            return ['success' => true, 'message' => 'Spedizione aggiornata.'];
        }
        return ['success' => false, 'message' => 'Nessuna spedizione aggiornata o ID non valido.'];
    }

    /**
     * Collega un ordine a una spedizione esistente.
     * @param int $order_id
     * @param int $shipment_id
     * @return array ['success' => true|false, 'message' => '...']
     */
    public function linkOrderToShipment(int $order_id, int $shipment_id): array
    {
        $query = "
        UPDATE `Order`
        SET shipment_id = ?
        WHERE order_id = ?
    ";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ii", $shipment_id, $order_id);
        if ($stmt->execute() && $stmt->affected_rows > 0) {
            return ['success' => true, 'message' => 'Ordine collegato alla spedizione.'];
        }
        return ['success' => false, 'message' => 'Errore collegamento ordine/spedizione.'];
    }

    /**
     * Recupera i dettagli di un ordine (solo i prodotti di questo seller).
     * @param int $order_id
     * @param int $seller_id
     * @return array
     */
    public function getSellerOrderDetails(int $order_id, int $seller_id): array
    {
        $query = "SELECT
                    od.order_detail_id,
                    od.product_id,
                    p.product_name,
                    p.image,
                    p.price,
                    od.quantity                
                FROM `Order_Detail` od
                JOIN `Product` p
                    ON od.product_id = p.product_id
                WHERE od.order_id  = ?
                AND p.seller_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ii", $order_id, $seller_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Recupera tutti i dettagli dell'ordine, pagamento e spedizione.
     * @param int $order_id
     * @return array ['success' => true|false, 'data' => array|null, 'message' => '...']
     */
    /**
     * Recupera tutti i dettagli dell'ordine, pagamento e spedizione.
     * @param int $order_id
     * @return array ['success'=>bool,'data'=>array|null,'message'=>string]
     */
    public function getFullOrderDetails(int $order_id): array
    {
        $query = "
        SELECT
            o.order_id,
            o.order_date,
            o.total_price,
            o.user_id,

            os.descrizione       AS order_status,

            pmt.payment_id,
            pmt.payment_date,
            pm.name              AS payment_method,
            pms.description      AS payment_status,

            shp.shipment_id,
            shp.shipment_date,
            shp.address          AS shipment_address,
            shp.shipping_method,
            shp.tracking_number,
            ss.status       AS shipment_status,
            sm.name         AS shipping_method

        FROM `Order` o
        LEFT JOIN `Order_Status`       os  ON o.order_status_id       = os.order_status_id
        LEFT JOIN `Payment`           pmt ON pmt.order_id           = o.order_id
        LEFT JOIN `Payment_Method`    pm  ON pmt.payment_method_id  = pm.payment_method_id
        LEFT JOIN `Payment_Status`    pms ON pmt.status             = pms.payment_status_id
        LEFT JOIN `Shipment`          shp ON o.shipment_id          = shp.shipment_id
        LEFT JOIN `Shipment_Status`   ss  ON shp.status             = ss.shipment_status_id
        LEFT JOIN `Shipping_Method`   sm  ON shp.shipping_method    = sm.shipping_method_id
        WHERE o.order_id = ?

    ";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $order_id);
        if (!$stmt->execute()) {
            return ['success' => false, 'data' => null, 'message' => 'Impossibile recuperare dettagli ordine.'];
        }
        $data = $stmt->get_result()->fetch_assoc();
        return ['success' => true, 'data' => $data, 'message' => 'Dettagli ordine completi recuperati.'];
    }

    /**
     * Recupera il totale delle vendite per un venditore in un anno specifico.
     * @param int $seller_id
     * @param int $year
     * @return float Il totale delle vendite.
     */
    public function getTotalSales(int $seller_id, int $year): float
    {
        $query = "SELECT SUM(p.price * od.quantity) AS total_sales
            FROM `Order_Detail` od
            JOIN `Order` o ON od.order_id = o.order_id AND o.order_status_id > 2 AND o.order_status_id < 7-- Solo ordini pagati.
            JOIN `Product` p ON od.product_id = p.product_id
            WHERE p.seller_id = ? AND YEAR(o.order_date) = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ii", $seller_id, $year);
        $stmt->execute();
        $result = $stmt->get_result();
        return (float)($result->fetch_assoc()['total_sales'] ?? 0.0);
    }

    /**
     * Recupera il numero totale di ordini per un venditore in un anno specifico.
     * @param int $seller_id
     * @param int $year
     * @return int Il numero totale di ordini.
     */
    public function getTotalOrders(int $seller_id, int $year): int
    {
        $query = "SELECT COUNT(DISTINCT o.order_id) AS total_orders
            FROM `Order` o
            JOIN `Order_Detail` od ON o.order_id = od.order_id
            JOIN `Product` p ON od.product_id = p.product_id
            WHERE p.seller_id = ? AND YEAR(o.order_date) = ? AND o.order_status_id > 2 AND o.order_status_id < 7 -- Solo ordini";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ii", $seller_id, $year);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc()['total_orders'] ?? 0;
    }

    /**
     * Recupera i prodotti più venduti per un venditore in un anno specifico.
     * @param int $seller_id
     * @param int $year
     * @param int $limit
     * @return array Un array di prodotti più venduti.
     */
    public function getBestSellingProducts(int $seller_id, int $year, int $limit = 5): array
    {
        $query = "SELECT p.product_id, p.product_name, SUM(od.quantity) AS total_sold
            FROM `Order_Detail` od
            JOIN `Order` o ON od.order_id = o.order_id AND o.order_status_id > 2 AND o.order_status_id < 7 -- Solo ordini pagati.
            JOIN `Product` p ON od.product_id = p.product_id
            WHERE p.seller_id = ? AND YEAR(o.order_date) = ?
            GROUP BY p.product_id
            ORDER BY total_sold DESC
            LIMIT ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("iii", $seller_id, $year, $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC) ?? [];
    }

    public function getBestCustomers(int $seller_id, int $year, int $limit = 5): array
    {
        $query = "SELECT u.user_id, u.email, SUM(od.quantity * p.price) AS total_spent
            FROM `Order` o
            JOIN `User` u ON o.user_id = u.user_id
            JOIN `Order_Detail` od ON o.order_id = od.order_id
            JOIN `Product` p ON od.product_id = p.product_id
            WHERE p.seller_id = ? AND YEAR(o.order_date) = ? AND o.order_status_id > 2 AND o.order_status_id < 7 -- Solo ordini pagati.
            GROUP BY u.user_id
            ORDER BY total_spent DESC
            LIMIT ?
        ";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("iii", $seller_id, $year, $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC) ?? [];
    }

    /**
     * Recupera i metodi di pagamento disponibili.
     */
    function getPaymentMethods()
    {
        $query = "SELECT * FROM `Payment_Method`";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Recupera i metodi di spedizione disponibili.
     */
    function getShippingMethods()
    {
        $query = "SELECT * FROM `Shipping_Method`";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
