<section>
    <h2 class="text-center">I nostri prodotti</h2>

    <div class="container">
        <div class="row">
            <?php
            require_once('bootstrap.php');
            require("productModal.php");
            $products = $dbh->getAllProducts();

            // tolgo i prodotti non disponibili e terminati
            $products = array_filter($products, fn ($p) => $p["available"] == 1 && $p["stock"] > 0);

            // Pagination logic
            $totalProducts = count($products);
            $productsPerPage = 9;
            $totalPages = ceil($totalProducts / $productsPerPage);

            // Get current page from URL, if not present default to 1
            $currentPage = isset($_GET['pageNumber']) ? (int)$_GET['pageNumber'] : 1;
            $startIndex = ($currentPage - 1) * $productsPerPage;

            // Slice the array to get the products for the current page
            $currentProducts = array_slice($products, $startIndex, $productsPerPage);

            foreach ($currentProducts as $product): ?>
                <div class="col-sm-6 col-md-4">
                    <div class="card mb-4">
                        <a  data-bs-toggle="modal"
                            data-bs-target="#productModal"
                            data-product-id="<?php echo $product['product_id']; ?>"
                            data-name="<?php echo htmlspecialchars($product['product_name']); ?>"
                            data-price="<?php echo $product['price']; ?>"
                            data-image="<?php echo $product['image']; ?>"
                            data-description="<?php echo htmlspecialchars($product['product_description'] ?? ''); ?>"
                            data-max="<?php echo $product['stock']; ?>">
                            <img src="<?php echo $product['image'] ?>" class="card-img-top" alt="<?php echo $product['product_name'] ?>">
                        </a>
                        <div class="container d-flex flex-column m-0 p-0">
                            <div class="col-12">
                                <div class="card-body d-flex flex-column" style="min-height: 10em">
                                    <!-- Titolo e link per il modale -->
                                    <a
                                        class="h4 card-title btn-link text-decoration-none"
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

                                    <!-- Sezione prezzo spinta in fondo grazie a mt-auto -->
                                    <div class="mt-auto">
                                        <p class="card-text h3 text-end"><?php echo $product['price']; ?>€</p>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="container d-flex justify-content-center">
                                    <div class="d-flex align-items-center justify-content-between gap-3">
                                        <div class="d-flex align-items-center border border-1 rounded ms-2 me-2">
                                            <button type="button" class="quantity-left-minus btn btn-secondary btn-number rounded rounded-0 rounded-start" data-type="minus" data-field="">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-dash" viewBox="0 0 16 16">
                                                    <path d="M4 8a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7A.5.5 0 0 1 4 8"></path>
                                                </svg>
                                            </button>
                                            <label for="quantity-<?php echo $product['product_id']; ?>" class="visually-hidden">Quantità</label>
                                            <input type="number" id="quantity-<?php echo $product['product_id']; ?>" name="quantity" class="form-control text-center rounded rounded-0" value="1" min="1" max="<?php echo $product['stock']; ?>" style="width:3em">
                                            <button type="button" class="quantity-right-plus btn btn-secondary btn-number rounded rounded-0 rounded-end" data-type="plus" data-field="">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus" viewBox="0 0 16 16">
                                                    <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4"></path>
                                                </svg>
                                            </button>
                                        </div>
                                        <?php if (!isUserLoggedIn()): ?>
                                            <a href="#" class="btn btn-primary float-end w-25 me-2" style="text-decoration: none" data-bs-toggle="modal" data-bs-target="#loginModal"><em class="fa fa-cart-plus"></em></a>
                                        <?php else: ?>
                                            <a href="#" title="add-to-cart" class="btn btn-primary btn-add-to-cart float-end w-25 me-2"
                                                data-product-id="<?php echo $product['product_id']; ?>">
                                                <em class="fa fa-cart-plus"></em>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

            <!-- Pagination controls -->
            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-center">
                    <?php for ($page = 1; $page <= $totalPages; $page++): ?>
                        <li class="page-item <?php if ($page == $currentPage) echo 'active'; ?>">
                            <?php
                            // Get the current URL and append the page parameter
                            $queryParams = $_GET;
                            $queryParams['pageNumber'] = $page;
                            $newQueryString = http_build_query($queryParams);
                            ?>
                            <a class="page-link" href="?<?php echo $newQueryString; ?>" data-page="<?php echo $page; ?>"><?php echo $page; ?></a>
                        </li>
                    <?php endfor; ?>
                </ul>
            </nav>
        </div>
    </div>
</section>

<script src="js/products.js"></script>