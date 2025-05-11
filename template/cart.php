<div class="container">
    <div class="col-12">
        <?php
        require_once('bootstrap.php');
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
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
                <?php if (!empty($cartProducts)): ?>
                    <div class="d-flex justify-content-between align-items-center">
                        <h2 class="fw-bold mb-0">Totale: <?php echo $totalPrice ?>€</h2>
                        <a href="index.php?page=checkout" class="btn btn-success">Procedi al pagamento</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <?php
        if (empty($cartProducts)): ?>
            <div class="row">
                <div class="col-12 p-4 text-center justify-content-center align-items-center card">
                    <h2>Il carrello è vuoto</h2>
                    <svg class="my-4" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" width="256" height="256" viewBox="0 0 256 256" xml:space="preserve">
                        <defs>
                        </defs>
                        <g style="stroke: none; stroke-width: 0; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: none; fill-rule: nonzero; opacity: 1;" transform="translate(1.4065934065934016 1.4065934065934016) scale(2.81 2.81)">
                            <path d="M 45 90 C 20.187 90 0 69.813 0 45 C 0 20.187 20.187 0 45 0 c 24.813 0 45 20.187 45 45 C 90 69.813 69.813 90 45 90 z M 45 4 C 22.393 4 4 22.393 4 45 s 18.393 41 41 41 s 41 -18.393 41 -41 S 67.607 4 45 4 z" style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: rgb(0,0,0); fill-rule: nonzero; opacity: 1;" transform=" matrix(1 0 0 1 0 0) " stroke-linecap="round" />
                            <circle cx="30.344" cy="33.274" r="5.864" style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: rgb(0,0,0); fill-rule: nonzero; opacity: 1;" transform="  matrix(1 0 0 1 0 0) " />
                            <circle cx="59.663999999999994" cy="33.274" r="5.864" style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: rgb(0,0,0); fill-rule: nonzero; opacity: 1;" transform="  matrix(1 0 0 1 0 0) " />
                            <path d="M 72.181 65.49 c -0.445 0 -0.893 -0.147 -1.265 -0.451 c -7.296 -5.961 -16.5 -9.244 -25.916 -9.244 c -9.417 0 -18.62 3.283 -25.916 9.244 c -0.854 0.7 -2.115 0.572 -2.814 -0.283 c -0.699 -0.855 -0.572 -2.115 0.283 -2.814 C 24.561 55.398 34.664 51.795 45 51.795 c 10.336 0 20.438 3.604 28.447 10.146 c 0.855 0.699 0.982 1.959 0.283 2.814 C 73.335 65.239 72.76 65.49 72.181 65.49 z" style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: rgb(0,0,0); fill-rule: nonzero; opacity: 1;" transform=" matrix(1 0 0 1 0 0) " stroke-linecap="round" />
                        </g>
                    </svg>
                    <br>
                    <a href="index.php?page=products" class="btn btn-primary m-4 p-4">Continua lo shopping</a>
                </div>
            </div>
        <?php endif;

        foreach ($cartProducts as $product): ?>
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