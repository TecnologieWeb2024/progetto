<section>
    <h2 class="text-center">I nostri prodotti</h2>
    <div class="container">
        <div class="row">
            <?php
            require_once('bootstrap.php');
            $products = $dbh->getAllProducts();

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
                        <img src="<?php echo $product['image'] ?>" class="card-img-top" alt="<?php echo $product['name'] ?>">
                        <div class="card-body border border-0">
                            <!-- NOTE: L'altezza minima è impostata per supportare titoli che occupano più di una riga -->
                            <h3 class="card-title" style="min-height: 4em;"><?php echo $product['product_name'] ?></h3>
                            <p class="card-text"><?php echo $product['price'] ?>€</p>
                            <!-- TODO: Aggiungere un selettore di quantità -->
                            <a href="#" title="add-to-cart" class="btn btn-primary float-end"><em class="fa fa-cart-plus"></em></a>
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