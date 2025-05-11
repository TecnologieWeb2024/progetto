<?php
require_once('bootstrap.php');
require_once('core/PaymentController.php');

$controller = new PaymentController($dbh);

if (!isUserLoggedIn() || !isUserCustomer()) {
    header('Location: index.php');
    exit;
}

try {
    // Fix the method call to properly use the processPayment method
    $paymentData = $controller->processPayment();

    if (!$paymentData['success']) {
        $_SESSION['error_message'] = $paymentData['message'];
        header('Location: ' . $paymentData['redirect']);
        exit;
    }

    $cartProducts = $paymentData['cartProducts'];
    $totalPrice = $paymentData['totalPrice'];
    $order_id = $paymentData['order_id'];
    $payment_method = $paymentData['payment_method'];
    // Get shipping method from POST as it's not returned by the controller
    $shipping_method = isset($_POST['shipping_method']) ? trim($_POST['shipping_method']) : '';
    $shipping_cost = isset($_POST['shipping_cost']) ? trim($_POST['shipping_cost']) : 0;
    $totalPrice += $shipping_cost;
    $totalPrice = number_format((float)$totalPrice, 2, '.', '');

    // Store shipping cost in session for access across pages
    $_SESSION['shipping_cost'] = $shipping_cost;
} catch (Exception $e) {
    $_SESSION['error_message'] = $e->getMessage();
    header('Location: index.php?page=cart');
    exit;
}
?>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-7 col-md-9">
            <div class="card shadow mb-4">
                <div class="card-body">
                    <h1 class="text-center mb-4">Pagamento</h1>
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h2 class="fw-bold mb-0">Totale: <?php echo $totalPrice ?>€</h2>
                        <a href="index.php?page=cart" class="btn btn-outline-danger">Annulla</a>
                    </div>
                    <?php if ($payment_method == 1): ?>
                        <h3 class="mb-3 text-center">Pagamento con Carta di Credito</h3>
                        <div class="text-center mb-3">
                            <img src="assets/img/icons/credit-card.png" alt="Carta di Credito" style="height:48px;">
                        </div>
                        <div id="payment-alerts"></div>
                        <form method="post" action="process_credit_card.php" id="credit-card-form">
                            <div class="mb-3">
                                <label for="cc_number" class="form-label">Numero Carta</label>
                                <input type="text" class="form-control" id="cc_number" name="cc_number" placeholder="1234 5678 9012 3456" required>
                                <div class="invalid-feedback">Inserisci un numero di carta valido (16 cifre).</div>
                            </div>
                            <div class="row">
                                <div class="col-6 mb-3">
                                    <label for="cc_expiry" class="form-label">Scadenza (MM/AA)</label>
                                    <input type="text" class="form-control" id="cc_expiry" name="cc_expiry" placeholder="MM/AA" required>
                                    <div class="invalid-feedback">Formato richiesto: MM/AA</div>
                                </div>
                                <div class="col-6 mb-3">
                                    <label for="cc_cvc" class="form-label">CVC</label>
                                    <input type="text" class="form-control" id="cc_cvc" name="cc_cvc" placeholder="123" required>
                                    <div class="invalid-feedback">Il CVC deve avere 3 o 4 cifre.</div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Paga</button>
                        </form>
                    <?php elseif ($payment_method == 2): ?>
                        <h3 class="mb-3 text-center">Pagamento con PayPal</h3>
                        <div class="text-center mb-3">
                            <img src="assets/img/icons/paypal.png" alt="PayPal" style="height:48px;">
                        </div>
                        <div id="paypal-alerts"></div>
                        <form method="post" action="process_paypal.php" id="paypal-form">
                            <div class="mb-3">
                                <label for="paypal_email" class="form-label">Email PayPal</label>
                                <input type="email" class="form-control" id="paypal_email" name="paypal_email" placeholder="email@example.com" required>
                                <div class="invalid-feedback">Inserisci un indirizzo email valido.</div>
                            </div>
                            <div class="mb-3">
                                <label for="paypal_password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="paypal_password" name="paypal_password" required>
                                <div class="invalid-feedback">La password deve essere di almeno 8 caratteri.</div>
                            </div>
                            <button type="submit" class="btn btn-primary w-100" style="background-color:#003087; border:none;">
                                Accedi e paga
                            </button>
                        </form>
                    <?php elseif ($payment_method == 3): ?>
                        <h3 class="mb-3 text-center">Pagamento con Bonifico Bancario</h3>
                        <div class="border rounded p-4 mb-3 bg-light text-center">
                            <p class="mb-2"><strong>Intestazione:</strong> CoffeeBO S.p.A.</p>
                            <p class="mb-2"><strong>IBAN:</strong> IT60X0542811101000000123456</p>
                            <p class="mb-2"><strong>Banca:</strong> Banca del Caffè</p>
                            <p class="mb-2"><strong>Causale:</strong> Numero ordine <?php echo $order_id ?></p>
                            <p class="mb-0"><strong>Importo:</strong> <?php echo $totalPrice ?>€</p>
                        </div>
                    <?php elseif ($payment_method == 4): ?>
                        <h3 class="mb-3 text-center">Pagamento in Contrassegno</h3>
                        <div class="border rounded p-4 mb-3 bg-light text-center">
                            <p class="mb-2">Pagherai in contanti al corriere al momento della consegna.</p>
                            <p class="mb-0">Assicurati di avere l'importo esatto.</p>
                        </div>
                    <?php elseif ($payment_method == 5): ?>
                        <h3 class="mb-3 text-center">Pagamento con Bitcoin</h3>
                        <div class="alert alert-dark text-center">
                            Puoi pagare utilizzando Bitcoin (BTC).<br>
                            Invia l'importo esatto al seguente indirizzo wallet Bitcoin:<br>
                            <strong>1FakeBitcoinAddress1234567890ABCDEF</strong><br>
                            <span class="d-block mt-2"><strong>Nota:</strong> Il pagamento sarà considerato valido solo dopo la conferma della transazione sulla blockchain.</span>
                        </div>
                        <form method="post" action="process_bitcoin.php" class="text-center">
                            <button type="submit" class="btn btn-dark">Ho effettuato il pagamento</button>
                        </form>
                    <?php else:
                        $_SESSION['error_message'] = 'Metodo di pagamento non valido.';
                        header('Location: index.php?page=cart');
                        exit;
                    endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    document.getElementById('credit-card-form').addEventListener('submit', function(e) {
        const ccNumber = document.getElementById('cc_number').value.replace(/\s|-/g, '');
        const ccExpiry = document.getElementById('cc_expiry').value;
        const ccCvc = document.getElementById('cc_cvc').value;

        // Clear previous alerts
        document.getElementById('payment-alerts').innerHTML = '';

        let isValid = true;
        let errorMessages = [];

        // Reset validation states
        document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));

        // Validate credit card number - 16 digits
        if (!/^\d{16}$/.test(ccNumber)) {
            document.getElementById('cc_number').classList.add('is-invalid');
            errorMessages.push('Il numero della carta deve contenere 16 cifre.');
            isValid = false;
        }

        // Validate expiry date - MM/AA format
        if (!/^(0[1-9]|1[0-2])\/([0-9]{2})$/.test(ccExpiry)) {
            document.getElementById('cc_expiry').classList.add('is-invalid');
            errorMessages.push('La data di scadenza deve essere nel formato MM/AA.');
            isValid = false;
        }

        // Validate CVC - 3 or 4 digits
        if (!/^\d{3,4}$/.test(ccCvc)) {
            document.getElementById('cc_cvc').classList.add('is-invalid');
            errorMessages.push('Il codice CVC deve contenere 3 o 4 cifre.');
            isValid = false;
        }

        if (!isValid) {
            e.preventDefault();
            // Display alert with all error messages
            const alertDiv = document.createElement('div');
            alertDiv.className = 'alert alert-danger';
            alertDiv.innerHTML = '<strong>Errore!</strong> Correggi i seguenti problemi:<ul>' +
                errorMessages.map(msg => '<li>' + msg + '</li>').join('') + '</ul>';
            document.getElementById('payment-alerts').appendChild(alertDiv);
        }
    });
</script>
<script>
    document.getElementById('paypal-form').addEventListener('submit', function(e) {
        const email = document.getElementById('paypal_email').value;
        const password = document.getElementById('paypal_password').value;

        // Clear previous alerts
        document.getElementById('paypal-alerts').innerHTML = '';

        let isValid = true;
        let errorMessages = [];

        // Reset validation states
        document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));

        // Validate email with regex
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            document.getElementById('paypal_email').classList.add('is-invalid');
            errorMessages.push('Inserisci un indirizzo email valido.');
            isValid = false;
        }

        // Validate password (minimum 8 characters)
        if (password.length < 8) {
            document.getElementById('paypal_password').classList.add('is-invalid');
            errorMessages.push('La password deve essere di almeno 8 caratteri.');
            isValid = false;
        }

        if (!isValid) {
            e.preventDefault();
            // Display alert with all error messages
            const alertDiv = document.createElement('div');
            alertDiv.className = 'alert alert-danger';
            alertDiv.innerHTML = '<strong>Errore!</strong> Correggi i seguenti problemi:<ul>' +
                errorMessages.map(msg => '<li>' + msg + '</li>').join('') + '</ul>';
            document.getElementById('paypal-alerts').appendChild(alertDiv);
        }
    });
</script>