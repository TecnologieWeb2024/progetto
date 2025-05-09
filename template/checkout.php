<?php
require_once('bootstrap.php');

if (!isset($_SESSION['customer']['user_id'])) {
    header('Location: index.php');
}
$cart = $dbh->getCartId($_SESSION['customer']['user_id']);
$cartItems = $dbh->getCartProducts($_SESSION['customer']['user_id']);

if (empty($cartItems['products'])) {
    header('Location: index.php?page=products');
}

$cartTotal = $cartItems['total_price'];
$paymentMethods = $dbh->getPaymentMethods();
$shippingMethods = $dbh->getShippingMethods();

?>
<div class="container my-4">
    <!-- Pagamento -->
    <div class="row justify-content-center">
        <div class="col-12 col-md-6">
            <h3 class="mb-3">Scegli metodo di Pagamento</h3>
            <form id="payment-form">
                <div class="card">
                    <div class="card-body">
                        <?php foreach ($paymentMethods as $method): 
                            $active = $method['is_active'] ?>
                            <div class="form-check mb-2">
                                <label class="form-check-label border rounded w-100 ps-2 d-flex align-items-center hover-darken <?php echo $active ? '' : 'bg-secondary  bg-opacity-25'?> "  for="payment_<?php echo $method['payment_method_id']; ?>" style="cursor: pointer;">
                                    <input class="form-check-input me-3 ms-1 mb-1"
                                        type="radio"
                                        id="payment_<?php echo $method['payment_method_id']; ?>"
                                        name="payment_method"
                                        value="<?php echo $method['payment_method_id']; ?>"
                                        <?php echo $active ? '' : 'disabled'; ?>
                                        required>
                                    <div class="my-2 <?php echo $method['is_active'] ? '' : 'text-muted'; ?>">
                                        <img src="<?php echo $method['icon']; ?>" alt="<?php echo $method['name']; ?>" class="me-2" style="height: 24px;">
                                        <?php echo $method['name']; ?>
                                    </div>
                                </label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- Spedizione -->
    <div class="row mt-4 justify-content-center">
        <div class="col-12 col-md-6">
            <h3 class="mb-3">Scegli metodo di Spedizione</h3>
            <form id="shipping-form">
                <div class="card">
                    <div class="card-body">
                        <?php foreach ($shippingMethods as $method): ?>
                            <div class="form-check mb-2">
                                <label class="form-check-label border rounded w-100 ps-2 d-flex align-items-center hover-darken" for="shipping_<?php echo $method['shipping_method_id']; ?>" style="cursor: pointer;">
                                    <input class="form-check-input me-3 ms-1 mb-1"
                                        type="radio"
                                        id="shipping_<?php echo $method['shipping_method_id']; ?>"
                                        name="shipping_method"
                                        value="<?php echo $method['shipping_method_id']; ?>"
                                        required>
                                    <div>
                                        <img src="<?php echo $method['icon']; ?>" alt="<?php echo $method['name']; ?>" class="me-2" style="height: 24px;">
                                        <?php echo $method['name']; ?>
                                        <p class="text-muted mb-0">
                                            <?php echo $method['description']; ?><br>
                                            Prezzo: <?php echo number_format($method['price'], 2, ',', '.'); ?>€
                                        </p>
                                    </div>
                                </label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
</div>