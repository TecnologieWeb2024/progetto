<div class="container">
    <?php if (isUserLoggedIn()): ?>
        <h1 class="text-center">Bentornato, <?php echo $_SESSION['customer']['first_name'] ?></h1>
        <section>
            <div class="container d-flex justify-content-center">
                <?php
                require_once('bootstrap.php');
                require("orderModal.php");
                $orders = $dbh->getOrders($_SESSION['customer']['user_id'], 3);
                if (count($orders) > 0):
                ?>
                    <div class="row m-2 bg-white border border-3 p-4 rounded">
                        <h2>Ordini recenti</h2>
                        <?php foreach ($orders as $order):
                            $products = $dbh->getOrderProducts($order['order_id']);
                        ?>
                            <div class="col-md-4 p-2">
                                <div class="card mb-4 h-100">
                                    <div class="card-header border border-0">
                                        <!-- NOTE: Il link deve puntare a order.php?id=... o qualcosa di simile -->
                                        <a href="#"
                                            data-bs-toggle="modal"
                                            data-bs-target="#orderModal"
                                            data-order-id="<?php echo $order['order_id']; ?>">
                                            <p class="card-title text-center">
                                                Ordinato il:
                                                <?php
                                                $date = DateTime::createFromFormat('Y-m-d H:i:s', $order['order_date']);
                                                echo $date->format('d/m/Y');
                                                ?>
                                                <br>
                                                <?php
                                                $orderStatus = $dbh->getOrderState($order['order_id']);
                                                $badgeClass = getBadgeClassFromStateId($orderStatus['order_state_id']);
                                                ?>
                                                <span class="badge <?php echo $badgeClass; ?>">
                                                    <?php echo $orderStatus['descrizione']; ?>
                                                </span>
                                            </p>

                                        </a>
                                    </div>
                                    <div class="card-body border border-0">
                                        <div class="row">
                                            <?php for ($i = 0; $i < count($products); $i++): ?>
                                                <div class="col-6 mb-2">
                                                    <?php if (count($products) > 0): ?>

                                                        <?php if ($i == 3 && count($products) > 3): ?>
                                                            <a href="#" class="d-block h-100">
                                                                <div class="card h-100 border border-0">
                                                                    <div class="card-body text-center d-flex align-items-center justify-content-center">
                                                                        Altro...
                                                                    </div>
                                                                </div>
                                                            </a>
                                                        <?php else: ?>
                                                            <img src="<?php echo $products[$i]['image']; ?>" class="img-fluid" alt="<?php echo $products[$i]['product_name']; ?>">
                                                        <?php endif; ?>
                                                    <?php endif; ?>
                                                </div>
                                            <?php endfor; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </section>
    <?php else: ?>
        <h1 class="text-center">Benvenuto su CoffeeBo</h1>
    <?php endif;
    require("template/products.php");
    ?>
</div>
<script src="js/orders.js"></script>