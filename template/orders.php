<?php
require_once('bootstrap.php');
if (!isset($_SESSION['customer']['user_id'])) {
    header('Location: /login.php');
    exit;
}
$orders = $dbh->getAllUserOrders($_SESSION['customer']['user_id']);
?>
<h1 class="text-center">I tuoi ordini</h1>
<section>
    <div class="container">
        <div class="row">
            <?php foreach ($orders as $order):
                $products = $dbh->getOrderProducts($order['order_id']);
            ?>
                <div class="col-md-4 p-2">
                    <div class="card mb-4 h-100">
                        <div class="card-header border border-0">
                            <!-- NOTE: Il link deve puntare a order.php?id=... -->
                            <a href="#">
                                <p class="card-title text-center">Ordinato il: <?php $date = DateTime::createFromFormat('Y-m-d H:i:s', $order['order_date']);
                                                                                echo $date->format('d/m/Y'); ?></p>
                            </a>
                        </div>
                        <div class="card-body border border-0">
                            <div class="row">
                                <?php for ($i = 0; $i < 4; $i++): ?>
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
                                                <img src="<?php echo $products[$i]['product_image']; ?>" class="img-fluid" alt="<?php echo $products[$i]['product_name']; ?>">
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </div>
                                <?php endfor; ?>
                            </div>
                        </div>
                        <div class="card-footer border border-0">
                            <p class="text-center">Totale: <?php echo $order['total_price']; ?>€</p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>