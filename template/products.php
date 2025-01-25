<h1 class="text-center">I nostri prodotti</h1>
<div class="container">
    <div class="row">
        <?php
        // TODO: Ovviamente, i dati devono essere recuperati dal database
        $products = [
            ["name" => "Product 1", "price" => "1.00", "image" => "./assets/img/image1.jpg"],
            ["name" => "Product 2", "price" => "2.00", "image" => "./assets/img/image1.jpg"],
            ["name" => "Product 3", "price" => "3.00", "image" => "./assets/img/image1.jpg"],
            ["name" => "Product 4", "price" => "4.00", "image" => "./assets/img/image1.jpg"],
            ["name" => "Product 5", "price" => "5.00", "image" => "./assets/img/image1.jpg"],
            ["name" => "Product 6", "price" => "1.00", "image" => "./assets/img/image1.jpg"],
            ["name" => "Product 7", "price" => "2.00", "image" => "./assets/img/image1.jpg"],
            ["name" => "Product 8", "price" => "3.00", "image" => "./assets/img/image1.jpg"],
            ["name" => "Product 9", "price" => "4.00", "image" => "./assets/img/image1.jpg"]
        ];

        // Pagination logic
        $totalProducts = count($products);
        $productsPerPage = 8;
        $totalPages = ceil($totalProducts / $productsPerPage);

        // Get current page from URL, if not present default to 1
        $currentPage = isset($_GET['pageNumber']) ? (int)$_GET['pageNumber'] : 1;
        $startIndex = ($currentPage - 1) * $productsPerPage;

        // Slice the array to get the products for the current page
        $currentProducts = array_slice($products, $startIndex, $productsPerPage);

        foreach ($currentProducts as $product): ?>
            <div class="col-sm-6 col-md-4 col-lg-3">
                <div class="card mb-4">
                    <img src="<?php echo $product['image'] ?>" class="card-img-top" alt="<?php echo $product['name'] ?>">
                    <div class="card-body border border-0">
                        <h5 class="card-title"><?php echo $product['name'] ?></h5>
                        <p class="card-text"><?php echo $product['price'] ?>€</p>
                        <!-- TODO: Aggiungere un selettore di quantità -->
                        <a href="#" class="btn btn-primary float-end">Aggiungi al carrello</a>
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