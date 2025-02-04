<?php

require_once('bootstrap.php');
// $user = $dbh->getUserInfo($_SESSION['customer']['user_id']);
?>
<h1 class="text-center">Gestione Prodotti</h1>
<div class="container col-md-4 mt-4">
</div>


<div>

    <?php 
    $products = $dbh->getAllProducts();
    $totalProducts = count($products);
    ?>
    <h5>Nel DB ci sono <?php echo $totalProducts ?> prodotti</h5>
</div>

<div class="container">
        <div class="row">
            <?php
            require_once('bootstrap.php');
            require("prodotto.php");
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
                        <img src="<?php echo $product['image'] ?>" class="card-img-top" alt="<?php echo $product['product_name'] ?>">
                        <div class="container d-flex flex-column m-0 p-0">
                            <div class="col-12">
                                <div class="card-body d-flex flex-column" style="min-height: 10em">
                                    <!-- Titolo e link per il modale -->
                                    <a type="button"
                                        class="h4 card-title btn-link"
                                        data-bs-toggle="modal"
                                        data-bs-target="#productModal"
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
                                    <div class="mt-auto">
                                        <p class="card-text h3 text-end">In magazzino: <?php echo $product['stock']; ?></p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="card-footer">
                                <div class="container d-flex justify-content-center">
                                    <div class="d-flex align-items-center ">
                                        <?php if ($product['available'] == 1): ?>
                                            <button type="button" id="nascondiBtn" class="btn btn-danger"  data-id="<?php echo $product['product_id'] ?>">Nascondi</button>
                                        <?php else: ?>
                                            <button type="button" id="mostraBtn"   class="btn btn-success" data-id="<?php echo $product['product_id'] ?>">Mostra</button>
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
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var productModal = document.getElementById("productModal");
        productModal.addEventListener("show.bs.modal", function(event) {
            var button = event.relatedTarget; // The button that triggered the modal
            var productName = button.getAttribute("data-name");
            var productPrice = button.getAttribute("data-price");
            var productImage = button.getAttribute("data-image");
            var productDescription = button.getAttribute("data-description");
            var productMax = button.getAttribute("data-max");
            // Set modal content
            document.getElementById("modalProductName").textContent = productName;
            document.getElementById("modalProductPrice").textContent = productPrice + "€";
            document.getElementById("modalProductImage").src = productImage;
            document.getElementById("modalProductDescription").textContent = productDescription;

            var quantityInput = document.getElementById("quantity");
            quantityInput.setAttribute("max", productMax);
        });
    });
</script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Add event listeners to all quantity increment and decrement buttons
        document.querySelectorAll(".quantity-right-plus").forEach(function(button) {
            button.addEventListener("click", function(e) {
                e.preventDefault();
                // Find the corresponding input field
                var input = this.closest(".d-flex").querySelector("input[type='number']");
                var quantity = parseInt(input.value);
                if (!isNaN(quantity)) {
                    input.value = quantity + 1; // Increment quantity
                }
            });
        });

        document.querySelectorAll(".quantity-left-minus").forEach(function(button) {
            button.addEventListener("click", function(e) {
                e.preventDefault();
                // Find the corresponding input field
                var input = this.closest(".d-flex").querySelector("input[type='number']");
                var quantity = parseInt(input.value);
                if (!isNaN(quantity) && quantity > 1) {
                    input.value = quantity - 1; // Decrement quantity, but not below 1
                }
            });
        });
    });
</script>
<!-- 
<script>
    $(document).ready(function(){
        $("#nascondiBtn").click(function(){
            var userId = $(this).data("id"); // Get the ID from the button attribute

            $.ajax({
                url: "query.php",
                type: "POST",
                data: { action: "nascondi", id: userId }, // Send ID to PHP
                success: function(response) {
                    $("#risultato").html(response);
                },
                error: function() {
                    $("#risultato").html("<p style='color:red;'>Error in AJAX request</p>");
                }
            });
        });
    });
</script> -->