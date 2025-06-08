<?php

/**
 * Common utility functions for payment processing
 */

/**
 * Process a payment for an order
 * 
 * @param object $dbh Database handler
 * @param object $controller Payment controller
 * @param int $paymentMethodId The payment method ID
 * @param string $transactionReference The transaction reference
 * @return bool Success indicator
 */
function processPayment($dbh, $controller, $paymentMethodId, $transactionReference)
{
    // Get order ID from session
    $order_id = $_SESSION['last_order_id'] ?? null;

    if (!$order_id) {
        $_SESSION['error_message'] = 'Ordine non trovato';
        header('Location: index.php?page=cart');
        exit;
    }

    // Fetch order details to get amount
    $orderDetails = $dbh->getOrder($order_id);
    $amount = $orderDetails['total_price'] + $_SESSION['shipping_cost'];
    $amount = number_format((float)$amount, 2, '.', '');
    unset($_SESSION['shipping_cost']);

    // Create payment record
    $result = $dbh->insertPayment($order_id, $paymentMethodId, $amount, 2, $transactionReference);

    if (!$result['success']) {
        $_SESSION['error_message'] = 'Errore durante il pagamento: ' . $result['message'];
        header('Location: index.php?page=payment');
        exit;
    }

    // Update order status to "Pagato" (status 3)
    $dbh->updateOrderStatus($order_id, 3);

    // Clear the cart
    $dbh->clearCart($_SESSION['customer']['user_id']);

    // Complete the checkout process
    $controller->completeCheckout();

    // Redirect to confirmation page
    $_SESSION['success_message'] = 'Pagamento effettuato con successo!';
    header('Location: index.php?page=order_confirmation&order_id=' . $order_id);
    exit;
}
