<div class="container">
    <?php if (isUserLoggedIn()): ?>
        <h1 class="text-center">Bentornato, <?php echo $_SESSION["testUsername"] ?></h1>
        <section>
            <div class="container d-flex justify-content-center">
                <div class="row m-2 bg-white border border-3 p-4 rounded">
                    <h4>Ordini recenti</h4>
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
                        <div class="col-md-4 p-2">
                            <div class="card mb-4 h-100">
                                <div class="card-header border border-0">
                                    <!-- NOTE: Il link deve puntare a order.php?id=... -->
                                    <a href="#">
                                        <h6 class="card-title text-center">Ordinato il: <?php echo $order['date']; ?></h6>
                                    </a>
                                </div>
                                <div class="card-body border border-0">
                                    <div class="row">
                                        <?php for ($i = 0; $i < 4; $i++): ?>
                                            <div class="col-6 mb-2">
                                                <?php if (isset($order['products'][$i])): ?>
                                                    <?php if ($i == 3 && count($order['products']) > 3): ?>
                                                        <a href="#" class="d-block h-100">
                                                            <div class="card h-100 border border-0">
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
            </div>
        </section>
    <?php else: ?>
        <h1>Welcome to CoffeeBo</h1>
    <?php endif;
    require("template/products.php");
    ?>
</div>