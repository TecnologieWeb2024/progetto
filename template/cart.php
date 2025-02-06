<div class="container">
    <div class="col-12">
        <?php
        require_once('bootstrap.php');
        if (isUserLoggedIn() === false) {
            header('Location: index.php');
        }

        $result = $dbh->getCartProducts($_SESSION['customer']['user_id']);
        $cartProducts = $result['products'];
        $totalPrice = $result['total_price'];
        require_once('prodotto.php')
        ?>
        <div class="row">
            <div class="col-12 mb-4">
                <h1>Carrello</h1>
                <p class="text-end h2">Totale: <?php echo $totalPrice ?>€</p>
            </div>
            <?php foreach ($cartProducts as $product): ?>
                <div class="row border p-3 m-1 bg-light">
                    <div class="col-2">
                        <img src="<?php echo $product['image'] ?>" class="img-fluid" alt="">
                    </div>
                    <div class="col-4">
                        <a type="button"
                            class="h4 card-title btn-link"
                            data-bs-toggle="modal"
                            data-bs-target="#productModal"
                            data-product-id="<?php echo $product['product_id']; ?>"
                            data-name="<?php echo htmlspecialchars($product['product_name']); ?>"
                            data-price="<?php echo $product['price']; ?>"
                            data-image="<?php echo $product['image']; ?>"
                            data-description="<?php echo htmlspecialchars($product['product_description'] ?? ''); ?>"
                            data-max="<?php echo $product['stock']; ?>">
                            <?php echo $product['product_name']; ?>
                        </a>
                        <p><?php echo $product['product_description'] ?></p>
                    </div>
                    <div class="col-2">
                        <p><?php echo $product['price'] ?>€</p>
                    </div>
                    <div class="col-2">
                        <p><?php echo $product['quantity'] ?></p>
                    </div>
                    <div class="col-2">
                        <a href="#" class="btn btn-danger" data-product-id="<?php echo $product['product_id'] ?>">
                            <em class="fa fa-trash"></em>
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<script src="js/products.js"></script>
