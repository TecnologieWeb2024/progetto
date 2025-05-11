<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$orders = isUserSeller() ? $dbh->getAllSellerOrders($_SESSION['seller']['user_id']) : $dbh->getAllUserOrders($_SESSION['customer']['user_id']);

function renderOrderCards($orders, $dbh)
{
    foreach ($orders as $order) {
        $products = isUserSeller()
            ? $dbh->getSellerOrderDetails($order['order_id'], $_SESSION['seller']['user_id'])
            : $dbh->getOrderProducts($order['order_id']);
        $date = DateTime::createFromFormat('Y-m-d H:i:s', $order['order_date']);
?>
        <div class="col-md-4 p-2">
            <div
                class="card mb-4 h-100 hover-darken"
                data-bs-toggle="modal"
                data-bs-target="#orderModal"
                data-order-id="<?php echo $order['order_id']; ?>">
                <div class="card-header border-0 text-center">
                    <p class="card-title">
                        <?php if (isUserSeller()): ?>
                            Ordine #<?php echo $order['order_id'] ?> del: <?php echo $date->format('d/m/Y') ?> <br>
                            Utente: <?php echo $dbh->getUserInfo($order['user_id'])['email'];
                                else: ?>
                            Ordinato il: <?php echo $date->format('d/m/Y'); ?>
                        <?php endif; ?>
                        <br>
                        <?php $orderStatus = $dbh->getOrderstatus($order['order_id']);
                        $badgeClass = getBadgeClassFromstatusId($orderStatus['order_status_id']);
                        ?>
                        <span class="badge <?php echo $badgeClass; ?>">
                            <?php echo $orderStatus['descrizione']; ?>
                        </span>
                    </p>
                </div>
                <div class="card-body border-0">
                    <div class="row">
                        <?php for ($i = 0; $i < 4; $i++): ?>
                            <div class="col-6 mb-2">
                                <?php if (isset($products[$i])): ?>
                                    <?php if ($i === 3 && count($products) > 4): ?>
                                        <div class="card h-100 border-0 d-flex align-items-center justify-content-center">
                                            Altro...
                                        </div>
                                    <?php else: ?>
                                        <img
                                            src="<?php echo $products[$i]['image']; ?>"
                                            class="img-fluid rounded"
                                            alt="<?php echo htmlspecialchars($products[$i]['product_name']); ?>">
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        <?php endfor; ?>
                    </div>
                </div>
                <div class="card-footer border-0 text-center">
                    <?php $sub = array_reduce($products, fn($a, $p) => $a + $p['price'] * $p['quantity'], 0); ?>
                    <p>Totale ordine: <?= number_format($sub, 2, ',', '.') ?>€</p>
                </div>
            </div>
        </div>
    <?php
    }
}

function renderOrderSection($title, $orders, $dbh)
{
    ?>
    <div class="row border rounded mb-4 p-4">
        <h2 class="text-center"><?php echo $title . ": " . count($orders); ?></h2>
        <?php renderOrderCards($orders, $dbh); ?>
    </div>
<?php
}
?>

<section>
    <div class="container">
        <div class="row"></div>
        <?php if (isUserSeller()): ?>
            <?php
            renderOrderSection("Ordini in attesa", $dbh->getWaitingOrders($_SESSION['seller']['user_id']), $dbh);
            renderOrderSection("Ordini accettati", $dbh->getAcceptedOrders($_SESSION['seller']['user_id']), $dbh);
            renderOrderSection("Ordini cancellati", $dbh->getCanceledOrders($_SESSION['seller']['user_id']), $dbh);
            ?>
        <?php else: ?>
            <div class="row">
                <?php renderOrderCards($orders, $dbh); ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php require("orderModal.php"); ?>

<script src="js/products.js"></script>
<script src="js/orders.js"></script>