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
        require_once('productModal.php')
        ?>
        <div class="row">
            <div class="col-12 mb-4">
                <h1 class="text-center">Carrello</h1>
                <h2 class="text-center fw-bold">Totale: <?php echo $totalPrice ?>€</h2>
            </div>
        </div>
        <?php foreach ($cartProducts as $product): ?>
            <div class="row border rounded p-3 m-1 bg-light justify-content-center align-items-center">
                <div class="col-6 col-md-2 text-center text-md-start">
                    <img src="<?php echo $product['image']; ?>" class="img-fluid" alt="<?php echo $product['product_name']; ?> image">
                </div>
                <!-- Product Details (Name, Description, Price) -->
                <div class="col-12 col-md-4 text-center text-md-start">
                    <a class="h2 btn-link"
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
                    <p class="d-none d-md-block"><?php echo $product['product_description']; ?></p>
                    <p class="d-md-none fw-bold h3"><?php echo $product['price']; ?>€</p>


                </div>

                <!-- Price -->
                <div class="d-none d-md-block col-md-2 text-center">
                    <p class="fw-bold h3"><?php echo $product['price'] ?>€</p>
                </div>
                <!-- Quantity -->
                <div class="col-md-2 d-flex justify-content-center align-items-center text-center">
                    <div class="d-flex align-items-center border border-1 rounded ms-2 me-2">
                        <button type="button" class="quantity-left-minus btn btn-secondary btn-number rounded rounded-0 rounded-start" data-type="minus" data-field="">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-dash" viewBox="0 0 16 16">
                                <path d="M4 8a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7A.5.5 0 0 1 4 8"></path>
                            </svg>
                        </button>
                        <label for="quantity-<?php echo $product['product_id']; ?>" class="visually-hidden">Quantità</label>
                        <input type="number" id="quantity-<?php echo $product['product_id']; ?>" name="quantity" class="form-control text-center rounded rounded-0" value="<?php echo $product['quantity']; ?>" min="1" max="<?php echo $product['stock']; ?>" style="width:3em">
                        <button type="button" class="quantity-right-plus btn btn-secondary btn-number rounded rounded-0 rounded-end" data-type="plus" data-field="">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus" viewBox="0 0 16 16">
                                <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4"></path>
                            </svg>
                        </button>
                    </div>
                    <a href="#" class="btn btn-danger" title="remove-product" data-product-id="<?php echo $product['product_id'] ?>">
                        <em class="fa fa-trash"></em>
                    </a>
                </div>
            </div>


        <?php endforeach; ?>
    </div>
</div>

<script src="js/products.js"></script>
<script src="js/cart.js"></script>