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
            return ['success' => true, 'message' => 'Password aggiornata con successo.'];
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
     * Aggiorna la disponibilità di un prodotto nel database.
     * @param int $id_product
     */
    public function changeAvailability(int $product_id)
    {
        $query = "SELECT available FROM `product` WHERE product_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $result = $stmt->get_result();


        $available = $result->fetch_assoc()['available'];

        $this->db->begin_transaction();
        if ($available == 1) {
            $query = "UPDATE `product` SET available = 0 WHERE product_id = ?";
        } else {
            $query = "UPDATE `product` SET available = 1 WHERE product_id = ?";
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
    public function getCart($user_id)
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
                p.price,
                p.stock, 
                p.image, 
                cd.quantity
            FROM 
                Cart c
            JOIN 
                Cart_Detail cd ON c.cart_id = cd.cart_id
            JOIN 
                Product p ON cd.product_id = p.product_id
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
        $query = "
            SELECT cd.quantity 
            FROM Cart_Detail cd
            JOIN Cart c ON cd.cart_id = c.cart_id
            WHERE c.user_id = ? AND cd.product_id = ?
        ";
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
        if ($quantity > $product['stock']) {
            $quantity = $product['stock'];
        }

        $this->db->begin_transaction();
        // Aggiorna la quantità del prodotto nel carrello
        $query = "
            UPDATE Cart_Detail cd
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

        $query = "
            DELETE cd
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
            $stmt = $this->db->prepare("INSERT INTO Cart (user_id) VALUES (?)");
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
        $stmt = $this->db->prepare("UPDATE Cart_Detail SET quantity = quantity + ?, updated_at = NOW() WHERE cart_id = ? AND product_id = ?");
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

        $stmt = $this->db->prepare("INSERT INTO Cart_Detail (cart_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiid", $cart_id, $product_id, $quantity, $price);
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
                p.product_name AS product_name, 
                p.price AS product_price, 
                p.image AS product_image,
                od.quantity
            FROM 
                Order_Detail od
            JOIN 
                Product p ON od.product_id = p.product_id
            WHERE 
                od.order_id = ?
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
                'product_image' => $row['product_image'],
                'quantity'      => $row['quantity']
            ];
        }
        return $products;
    }

    /**
     * Inserisce un ordine nel database.
     * @param int $user_id L'id dell'utente
     * @param array $products Un array di prodotti e quantità (es. [['product_id' => 1, 'quantity' => 2, 'price' => 10.99], ['product_id' => 2, 'quantity' => 1, 'price' => 5.99], ...])
     * @param int $shipment_id L'id della spedizione
     * @param int $payment_id L'id del pagamento
     * @return array ['success' => true|false, 'message' => '...'] in base all'esito dell'operazione.
     */

    public function insertOrder($user_id, array $products, int $shipment_id, int $payment_id): array
    {
        $this->db->begin_transaction();

        $total_price = $this->calculateTotalPrice($products);
        if ($total_price === false) {
            $this->db->rollback();
            return ['success' => false, 'message' => 'Errore nella creazione dell\'ordine: dati prodotto mancanti.'];
        }

        $order_id = $this->insertOrderIntoDatabase($user_id, $total_price, $shipment_id, $payment_id);
        if ($order_id === false) {
            $this->db->rollback();
            return ['success' => false, 'message' => 'Errore nella creazione dell\'ordine. Impossibile inserire l\'ordine.'];
        }

        if (!$this->insertOrderDetails($order_id, $products)) {
            $this->db->rollback();
            return ['success' => false, 'message' => 'Errore nella creazione dell\'ordine. Impossibile inserire i dettagli dell\'ordine.'];
        }

        $this->db->commit();

        return ['success' => true, 'message' => 'Ordine #' . $order_id . ' creato con successo.'];
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
     * @param int $user_id L'id dell'utente
     * @param float $total_price Il prezzo totale dell'ordine
     * @param int $shipment_id L'id della spedizione
     * @param int $payment_id L'id del pagamento
     * @return int|bool L'id dell'ordine appena creato, false in caso di errore
     */
    private function insertOrderIntoDatabase($user_id, $total_price, $shipment_id, $payment_id)
    {
        $query = "INSERT INTO `Order` (order_date, total_price, user_id, shipment_id, payment_id) VALUES (NOW(), ?, ?, ?, ?)";

        try {
            $stmt = $this->db->prepare($query);
            $stmt->bind_param("diii", $total_price, $user_id, $shipment_id, $payment_id);
            if ($stmt->execute() === false) {
                return false;
            }
        } catch (mysqli_sql_exception $e) {
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
        try {
            $stmt = $this->db->prepare($query);

            foreach ($products as $product) {
                $stmt->bind_param("iiid", $order_id, $product['product_id'], $product['quantity'], $product['price']);
                if ($stmt->execute() === false) {
                    return false;
                }
            }
        } catch (mysqli_sql_exception $e) {
            return false;
        }

        return true;
    }
}
