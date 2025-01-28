<?php
// TODO: Ovviamente, i dati devono essere recuperati dal database.
$orders = [
    ["products" => [
        ["name" => "Product 1", "price" => "1.00", "image" => "./assets/img/image1.jpg"],
        ["name" => "Product 2", "price" => "2.00", "image" => "./assets/img/image1.jpg"],
        ["name" => "Product 5", "price" => "5.00", "image" => "./assets/img/image1.jpg"],
        ["name" => "Product 5", "price" => "5.00", "image" => "./assets/img/image1.jpg"],
        ["name" => "Product 5", "price" => "5.00", "image" => "./assets/img/image1.jpg"]
    ], "total" => "3.00", "date" => "2025-01-01"],
    ["products" => [
        ["name" => "Product 2", "price" => "2.00", "image" => "./assets/img/image1.jpg"],
        ["name" => "Product 3", "price" => "3.00", "image" => "./assets/img/image1.jpg"]
    ], "total" => "3.00", "date" => "2025-01-02"],
    [
        "products" => [
            ["name" => "Product 1", "price" => "1.00", "image" => "./assets/img/image1.jpg"],
            ["name" => "Product 3", "price" => "3.00", "image" => "./assets/img/image1.jpg"],
            ["name" => "Product 5", "price" => "5.00", "image" => "./assets/img/image1.jpg"],
            ["name" => "Product 5", "price" => "5.00", "image" => "./assets/img/image1.jpg"]
        ],
        "total" => "4.00",
        "date" => "2025-01-03"
    ],
    ["products" => [
        ["name" => "Product 1", "price" => "1.00", "image" => "./assets/img/image1.jpg"],
        ["name" => "Product 2", "price" => "2.00", "image" => "./assets/img/image1.jpg"],
        ["name" => "Product 5", "price" => "5.00", "image" => "./assets/img/image1.jpg"],
        ["name" => "Product 5", "price" => "5.00", "image" => "./assets/img/image1.jpg"],
        ["name" => "Product 5", "price" => "5.00", "image" => "./assets/img/image1.jpg"]
    ], "total" => "3.00", "date" => "2025-01-01"],
    ["products" => [
        ["name" => "Product 1", "price" => "1.00", "image" => "./assets/img/image1.jpg"],
        ["name" => "Product 3", "price" => "3.00", "image" => "./assets/img/image1.jpg"],
        ["name" => "Product 5", "price" => "5.00", "image" => "./assets/img/image1.jpg"],
        ["name" => "Product 5", "price" => "5.00", "image" => "./assets/img/image1.jpg"]
    ], "total" => "3.00", "date" => "2025-01-01"],
    ["products" => [
        ["name" => "Product 1", "price" => "1.00", "image" => "./assets/img/image1.jpg"],
        ["name" => "Product 2", "price" => "2.00", "image" => "./assets/img/image1.jpg"],
        ["name" => "Product 5", "price" => "5.00", "image" => "./assets/img/image1.jpg"],
        ["name" => "Product 5", "price" => "5.00", "image" => "./assets/img/image1.jpg"],
        ["name" => "Product 5", "price" => "5.00", "image" => "./assets/img/image1.jpg"]
    ], "total" => "3.00", "date" => "2025-01-01"],
    ["products" => [
        ["name" => "Product 1", "price" => "1.00", "image" => "./assets/img/image1.jpg"],
        ["name" => "Product 2", "price" => "2.00", "image" => "./assets/img/image1.jpg"],
        ["name" => "Product 5", "price" => "5.00", "image" => "./assets/img/image1.jpg"],
        ["name" => "Product 5", "price" => "5.00", "image" => "./assets/img/image1.jpg"],
        ["name" => "Product 5", "price" => "5.00", "image" => "./assets/img/image1.jpg"]
    ], "total" => "3.00", "date" => "2025-01-01"],
];
?>
<h1 class="text-center">I tuoi ordini</h1>
<section>
    <div class="container">
        <div class="row">
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
                                        <?php if (isset($order['products'][$i])):
                                            // Se ci sono più di 3 prodotti, mostro un link "Altro..." al posto dell'immagine
                                            if ($i == 3 && count($order['products']) > 3): ?>
                                                <!-- NOTE: Il link deve puntare a order.php?id=... -->
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
                        <div class="card-footer border border-0">
                            <p class="text-center">Totale: <?php echo $order['total']; ?>€</p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>