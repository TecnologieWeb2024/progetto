<section>
    <div class="container">
        <div class="row">
            <?php foreach ($orders as $order):
                $products = $dbh->getOrderProducts($order['order_id']);
                $date = DateTime::createFromFormat('Y-m-d H:i:s', $order['order_date']);
            ?>
                <div class="col-md-4 p-2">
                    <div
                        class="card mb-4 h-100"
                        data-bs-toggle="modal"
                        data-bs-target="#orderModal"
                        data-order-id="<?php echo $order['order_id']; ?>">
                        <div class="card-header border-0 text-center">
                            <p class="card-title">
                                <?php if (array_key_exists('seller', $_SESSION)): ?>
                                    Ordine #<?php echo $order['order_id'] ?> del: <?php echo $date->format('d/m/Y') ?> <br>
                                    Utente: <?php echo $dbh->getUserInfo($order['user_id'])['email'] ?>
                                <?php else: ?>
                                    Ordinato il: <?php echo $date->format('d/m/Y'); ?>
                                <?php endif; ?>
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
                                                    src="<?php echo $products[$i]['product_image']; ?>"
                                                    class="img-fluid"
                                                    alt="<?php echo htmlspecialchars($products[$i]['product_name']); ?>">
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </div>
                                <?php endfor; ?>
                            </div>
                        </div>
                        <div class="card-footer border-0 text-center">
                            <p>Totale: <?php echo number_format($order['total_price'], 2, ',', '.'); ?>€</p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php require("orderModal.php"); ?>

<script src="js/products.js"></script>
<script src="js/orders.js"></script>