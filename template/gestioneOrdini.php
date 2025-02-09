<?php

require_once('bootstrap.php');
// $user = $dbh->getUserInfo($_SESSION['customer']['user_id']);
?>
<h1 class="text-center">Gestione Ordini</h1>
<div class="container col-md-4 mt-4">
</div>


<div>

    <?php
    $orders = $dbh->getAllOrders(); // ? Aggiungere range?

    $totalOrders = count($orders);
    ?>
    <!-- 
        // TODO totale ordini
        // TODO totale ordini da evadere
        // TODO totale ordini evasi
        // TODO totale ordini conclusi -->
    <h5>Nel DB ci sono <?php echo $totalOrders ?> prodotti</h5>
    <!-- <h5>ORDINI <?php print_r($orders) ?> </h5> -->



</div>

<section>
    <div class="container d-flex justify-content-center">
        <?php
        require_once('bootstrap.php');
        $orders = $dbh->getAllOrders();
        if (count($orders) > 0):
        ?>
            <div class="row m-2 bg-white border border-3 p-4 rounded">
                <h2>Ordini più recenti</h2>
                <?php foreach ($orders as $order):
                    $products = $dbh->getOrderProducts($order['order_id']);
                ?>
                    <div class="col-md-4 p-2">
                        <div class="card mb-4 h-100">
                            <div class="card-header border border-0">
                                <!-- NOTE: Il link deve puntare a order.php?id=... o qualcosa di simile -->
                                <a href="#">
                                    <p class="card-title text-center">Ordinato il: <?php $date = DateTime::createFromFormat('Y-m-d H:i:s', $order['order_date']);
                                                                                    echo $date->format('d/m/Y'); ?></p>

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
                                                    <img src="<?php echo $products[$i]['product_image']; ?>" class="img-fluid" alt="<?php echo $products[$i]['product_name']; ?>">
                                                    
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </div>
                                    <?php endfor; ?>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="container d-flex justify-content-center">
                                    <div class="d-flex align-items-center ">
                                        <form method="POST" action="changeAvailable.php">
                                        <input type="hidden" name="product_id" value="1">
                                        <button type="submit" name="runQuery" id="nascondiBtn" class="btn btn-danger" >Elimina</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>1