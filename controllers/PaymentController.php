<?php
require_once('bootstrap.php');

class PaymentController
{
    private $dbh;

    public function __construct($dbh)
    {
        $this->dbh = $dbh;
    }

    public function processPayment()
    {
        if (!isUserLoggedIn() || !isUserCustomer()) {
            return [
                'success' => false,
                'redirect' => 'index.php',
                'message' => 'Accesso non autorizzato'
            ];
        }

        $result = $this->dbh->getCartProducts($_SESSION['customer']['user_id']);
        $cartProducts = $result['products'];
        $totalPrice = $result['total_price'];

        if (empty($cartProducts)) {
            return [
                'success' => false,
                'redirect' => 'index.php?page=cart',
                'message' => 'Il carrello è vuoto'
            ];
        }

        $payment_method = isset($_POST['payment_method']) ? trim($_POST['payment_method']) : '';
        $shipping_method = isset($_POST['shipping_method']) ? trim($_POST['shipping_method']) : '';
        
        $address = '';
        // Se il metodo di spedizione non è "ritiro in negozio", prendi i dati dell'indirizzo.
        if ($shipping_method != 3) {
            $address = isset($_POST['address']) ? trim($_POST['address']) : '';
            $city = isset($_POST['city']) ? trim($_POST['city']) : '';
            $postal_code = isset($_POST['postal_code']) ? trim($_POST['postal_code']) : '';
            $address = $address . ", " . $city . ", " . $postal_code;
        }

        // Inizia un nuovo flusso di checkout o continua uno esistente
        if (!isset($_SESSION['checkout_in_progress']) || isset($_POST['restart_checkout'])) {
            $this->startNewCheckout();
        }

        // Crea spedizione
        $shipment_id = $this->createOrRetrieveShipment($address, $shipping_method);
        if (!$shipment_id) {
            return [
                'success' => false,
                'redirect' => 'index.php?page=cart',
                'message' => 'Errore durante la creazione della spedizione'
            ];
        }

        // Crea ordine
        $order_id = $this->createOrRetrieveOrder($_SESSION['customer']['user_id'], $cartProducts, $shipment_id);
        if (!$order_id) {
            return [
                'success' => false,
                'redirect' => 'index.php?page=cart',
                'message' => 'Errore durante la creazione dell\'ordine'
            ];
        }

        return [
            'success' => true,
            'cartProducts' => $cartProducts,
            'totalPrice' => $totalPrice,
            'payment_method' => $payment_method,
            'order_id' => $order_id
        ];
    }

    /**
     * Inizia un nuovo processo di checkout
     */
    public function startNewCheckout()
    {
        $_SESSION['checkout_in_progress'] = true;
        $_SESSION['checkout_id'] = uniqid('checkout_');
        unset($_SESSION['shipment_created']);
        unset($_SESSION['order_created']);
        unset($_SESSION['last_shipment_id']);
        unset($_SESSION['last_order_id']);
    }

    /**
     * Completa il processo di checkout
     */
    public function completeCheckout()
    {
        unset($_SESSION['checkout_in_progress']);
        unset($_SESSION['checkout_id']);
        unset($_SESSION['shipment_created']);
        unset($_SESSION['order_created']);
        unset($_SESSION['last_shipment_id']);
        unset($_SESSION['last_order_id']);
    }

    private function createOrRetrieveShipment($address, $shipping_method)
    {
        // Crea una nuova spedizione solo se non è già stata creata per questo checkout
        if (!isset($_SESSION['shipment_created']) || $_SESSION['shipment_created'] !== true) {
            $result = $this->dbh->createShipment($address, NULL, $shipping_method, 1);
            if ($result['success'] == true) {
                $_SESSION['shipment_created'] = true;
                $_SESSION['last_shipment_id'] = $result['data'];
                return $result['data'];
            }
            return false;
        } else {
            return isset($_SESSION['last_shipment_id']) ? $_SESSION['last_shipment_id'] : null;
        }
    }

    private function createOrRetrieveOrder($user_id, $cartProducts, $shipment_id)
    {
        // Crea un nuovo ordine solo se non è già stato creato per questo checkout
        if (!isset($_SESSION['order_created']) || $_SESSION['order_created'] !== true) {
            $result = $this->dbh->insertOrder($user_id, $cartProducts, 2, $shipment_id);
            if ($result['success'] == true) {
                $_SESSION['order_created'] = true;
                $_SESSION['last_order_id'] = $result['data'];
                return $result['data'];
            }
            return false;
        } else {
            return isset($_SESSION['last_order_id']) ? $_SESSION['last_order_id'] : null;
        }
    }
}
