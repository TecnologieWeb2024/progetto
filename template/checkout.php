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
$test = $dbh->getOrder(17);

print_r($test);
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
                            <div class="form-check mb-2 pe-4">
                                <label class="form-check-label border rounded w-100 ps-2 d-flex align-items-center hover-darken <?php echo $active ? '' : 'bg-secondary  bg-opacity-25' ?> " for="payment_<?php echo $method['payment_method_id']; ?>" style="cursor: pointer;">
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
                            <div class="form-check mb-2 pe-4">
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

    <!-- Inserimento Indirizzo -->
    <div class="row mt-4 justify-content-center">
        <div class="col-12 col-md-6">
            <h3 class="mb-3">Indirizzo di Spedizione</h3>
            <form id="address-form">
                <div class="card">
                    <div class="card-body">
                        <div class="mb-3 ps-4 pe-4">
                            <label for="address" class="form-label">Indirizzo</label>
                            <input type="text" class="form-control" id="address" name="address" required>
                        </div>
                        <div class="mb-3 ps-4 pe-4">
                            <label for="city" class="form-label">Città</label>
                            <input type="text" class="form-control" id="city" name="city" required>
                        </div>
                        <div class="mb-3 ps-4 pe-4">
                            <label for="postal_code" class="form-label">Codice Postale</label>
                            <input type="text" class="form-control" id="postal_code" name="postal_code" required>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- Riepilogo Ordine -->
    <div class="row mt-4 justify-content-center">
        <div class="col-12 col-md-6">
            <h3 class="mb-3">Riepilogo Ordine</h3>
            <div class="card">
                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Prodotto</th>
                                <th>Prezzo</th>
                                <th>Quantità</th>
                            </tr>
                        </thead>
                        <tbody id="order-summary">
                            <?php foreach ($cartItems['products'] as $product): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($product['product_name']); ?></td>
                                    <td><?php echo number_format($product['price'], 2, ',', '.'); ?>€</td>
                                    <td><?php echo $product['quantity']; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <p class="fw-bold">Totale: <?php echo number_format($cartTotal, 2, ',', '.'); ?>€</p>
                    <p class="fw-bold">Spedizione: <span id="shipping-cost">0,00</span>€</p>
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const shippingRadios = document.querySelectorAll('input[name="shipping_method"]');
                            const shippingCostSpan = document.getElementById('shipping-cost');
                            const shippingMethods = <?php echo json_encode($shippingMethods); ?>;

                            function formatEuro(amount) {
                                return amount.toFixed(2).replace('.', ',');
                            }

                            function updateShippingCost() {
                                const selected = document.querySelector('input[name="shipping_method"]:checked');
                                if (selected) {
                                    const methodId = selected.value;
                                    const method = shippingMethods.find(m => m.shipping_method_id == methodId);
                                    if (method) {
                                        shippingCostSpan.textContent = formatEuro(parseFloat(method.price));
                                    }
                                }
                            }

                            shippingRadios.forEach(radio => {
                                radio.addEventListener('change', updateShippingCost);
                            });

                            // Set initial value if already selected
                            updateShippingCost();
                        });
                    </script>
                </div>
            </div>
        </div>
    </div>
    <!-- Pulsante di Conferma -->
    <div class="row mt-4 justify-content-center">
        <div class="col-12 col-md-6 text-center">
            <form id="confirm-order-form" action="index.php?page=payment" method="post">
                <!-- Hidden fields to collect values from the other forms -->
                <input type="hidden" name="payment_method" id="hidden-payment-method">
                <input type="hidden" name="shipping_method" id="hidden-shipping-method">
                <input type="hidden" name="shipping_cost" id="hidden-shipping-cost">
                <input type="hidden" name="address" id="hidden-address">
                <input type="hidden" name="city" id="hidden-city">
                <input type="hidden" name="postal_code" id="hidden-postal-code">
                <button type="submit" class="btn btn-primary">Procedi al pagamento</button>
            </form>
        </div>
    </div>
    <script>
        document.getElementById('confirm-order-form').addEventListener('submit', function(e) {
            // Copy values from the other forms into the hidden fields
            const payment = document.querySelector('input[name="payment_method"]:checked');
            const shipping = document.querySelector('input[name="shipping_method"]:checked');
            const address = document.getElementById('address');
            const city = document.getElementById('city');
            const postal = document.getElementById('postal_code');

            if (!payment || !shipping || !address.value || !city.value || !postal.value) {
                e.preventDefault();
                alert('Compila tutti i campi per confermare l\'ordine.');
                return false;
            }

            document.getElementById('hidden-payment-method').value = payment.value;
            document.getElementById('hidden-shipping-method').value = shipping.value;
            document.getElementById('hidden-address').value = address.value;
            document.getElementById('hidden-city').value = city.value;
            document.getElementById('hidden-postal-code').value = postal.value;
            
            // Add shipping cost to POST data
            const shippingMethods = <?php echo json_encode($shippingMethods); ?>;
            const selectedMethod = shippingMethods.find(m => m.shipping_method_id == shipping.value);
            document.getElementById('hidden-shipping-cost').value = selectedMethod.price;
        });
    </script>
</div>