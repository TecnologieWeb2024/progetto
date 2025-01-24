<div class="container">
    <?php if (isUserLoggedIn()): ?>
        <h1>Welcome back, <?php echo $_SESSION["testUsername"] ?></h1>
        <div class="row m-2 bg-secondary p-2 rounded">
            <h2 class="text-white">Your orders</h2>
            <?php
            // TODO: Ovviamente, i dati devono essere recuperati dal database. Recupera gli ultimi 4 ordini
            $orders = [
                ["products" => [
                    ["name" => "Product 1", "price" => "1.00", "image" => "./assets/img/image1.jpg"],
                    ["name" => "Product 2", "price" => "2.00", "image" => "./assets/img/image1.jpg"],
                    ["name" => "Product 5", "price" => "5.00", "image" => "./assets/img/image1.jpg"],
                    ["name" => "Product 5", "price" => "5.00", "image" => "./assets/img/image1.jpg"],
                    ["name" => "Product 5", "price" => "5.00", "image" => "./assets/img/image1.jpg"]
                ], "total" => "3.00", "date" => "2022-01-01"],
                ["products" => [
                    ["name" => "Product 2", "price" => "2.00", "image" => "./assets/img/image1.jpg"],
                    ["name" => "Product 3", "price" => "3.00", "image" => "./assets/img/image1.jpg"]
                ], "total" => "3.00", "date" => "2022-01-02"],
                [
                    "products" => [
                        ["name" => "Product 1", "price" => "1.00", "image" => "./assets/img/image1.jpg"],
                        ["name" => "Product 3", "price" => "3.00", "image" => "./assets/img/image1.jpg"],
                        ["name" => "Product 5", "price" => "5.00", "image" => "./assets/img/image1.jpg"],
                        ["name" => "Product 5", "price" => "5.00", "image" => "./assets/img/image1.jpg"]
                    ],
                    "total" => "4.00",
                    "date" => "2022-01-03"
                ]
            ];
            ?>
            <?php foreach ($orders as $order): ?>
                <div class="col-md-4">
                    <div class="card mb-4" style="height: 100%;">
                        <div class="card-header">
                            <!-- NOTE: Il link deve puntare a order.php?id=... -->
                            <a href="#"><h5 class="card-title">Ordinato il: <?php echo $order['date']; ?></h5></a>
                            <p class="card-text">Totale: <?php echo $order['total']; ?>€</p>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <?php for ($i = 0; $i < 4; $i++): ?>
                                    <div class="col-6 mb-2">
                                        <?php if (isset($order['products'][$i])): ?>
                                            <?php if ($i == 3 && count($order['products']) > 4): ?>
                                                <a href="#" class="d-block h-100">
                                                    <div class="card h-100">
                                                        <div class="card-body text-center d-flex align-items-center justify-content-center">
                                                            Altro...
                                                        </div>
                                                    </div>
                                                </a>
                                            <?php else: ?>
                                                <img src="<?php echo $order['products'][$i]['image']; ?>" class="img-fluid" alt="<?php echo $order['products'][$i]['name']; ?>">
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
    <?php else: ?>
        <h1>Welcome to CoffeeBo</h1>
    <?php endif; ?>
    <div class="row">
        <?php
        // TODO: Ovviamente, i dati devono essere recuperati dal database
        $products = [
            ["name" => "Product 1", "price" => "1.00", "image" => "./assets/img/image1.jpg"],
            ["name" => "Product 2", "price" => "2.00", "image" => "./assets/img/image1.jpg"],
            ["name" => "Product 3", "price" => "3.00", "image" => "./assets/img/image1.jpg"],
            ["name" => "Product 4", "price" => "4.00", "image" => "./assets/img/image1.jpg"],
            ["name" => "Product 5", "price" => "5.00", "image" => "./assets/img/image1.jpg"]
        ];

        foreach ($products as $product): ?>
            <div class="col-sm-6 col-md-4 col-lg-3">
                <div class="card mb-4">
                    <img src="<?php echo $product['image'] ?>" class="card-img-top" alt="<?php echo $product['name'] ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $product['name'] ?></h5>
                        <p class="card-text"><?php echo $product['price'] ?></p>
                        <a href="#" class="btn btn-primary">Add to Cart</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>