document.addEventListener("DOMContentLoaded", function() {
    // Modal population code
    var productModal = document.getElementById("productModal");
    if(productModal){
        productModal.addEventListener("show.bs.modal", function(event) {
            var button = event.relatedTarget;
            var productName = button.getAttribute("data-name");
            var productPrice = button.getAttribute("data-price");
            var productImage = button.getAttribute("data-image");
            var productDescription = button.getAttribute("data-description");
            var productMax = button.getAttribute("data-max");
            document.getElementById("modalProductName").textContent = productName;
            document.getElementById("modalProductPrice").textContent = productPrice + "€";
            document.getElementById("modalProductImage").src = productImage;
            document.getElementById("modalProductDescription").textContent = productDescription;
            var quantityInput = document.getElementById("quantity");
            if(quantityInput){
                quantityInput.setAttribute("max", productMax);
            }
        });
    }
    
    // Quantity increment and decrement buttons
    document.querySelectorAll(".quantity-right-plus").forEach(function(button) {
        button.addEventListener("click", function(e) {
            e.preventDefault();
            var input = this.closest(".d-flex").querySelector("input[type='number']");
            var quantity = parseInt(input.value);
            if (!isNaN(quantity)) {
                input.value = quantity + 1;
            }
        });
    });
    document.querySelectorAll(".quantity-left-minus").forEach(function(button) {
        button.addEventListener("click", function(e) {
            e.preventDefault();
            var input = this.closest(".d-flex").querySelector("input[type='number']");
            var quantity = parseInt(input.value);
            if (!isNaN(quantity) && quantity > 1) {
                input.value = quantity - 1;
            }
        });
    });
    
    // Add-to-cart event listener
    document.querySelectorAll(".btn-add-to-cart").forEach(function(btn) {
        btn.addEventListener("click", function(e) {
            e.preventDefault();
            var productId = this.getAttribute("data-product-id");
            var container = this.closest(".d-flex");
            var quantityInput = container.querySelector("input[type='number']");
            var quantity = quantityInput ? quantityInput.value : 1;
            fetch("addToCartHandler.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/x-www-form-urlencoded",
                    },
                    body: "product_id=" + encodeURIComponent(productId) + "&quantity=" + encodeURIComponent(quantity),
                })
                .then(function(response) { return response.json(); })
                .then(function(data) {
                    if (data.success) {
                        console.log("Product added to cart successfully.");
                    } else {
                        console.log("Failed to add product to cart.");
                    }
                })
                .catch(function(error) {
                    console.log("Error while trying to add item to cart:", error);
                });
        });
    });
});

document.addEventListener("DOMContentLoaded", function() {
    // Event listener per i bottoni add-to-cart
    document.querySelectorAll(".btn-add-to-cart").forEach(function(btn) {
        btn.addEventListener("click", function(e) {
            e.preventDefault(); // Previene il comportamento di default del link

            // Recupera il product id dal data attribute
            var productId = this.getAttribute("data-product-id");

            // Risale al container flex che contiene sia l'input che il pulsante
            var container = this.closest(".d-flex");
            // Seleziona il campo input del tipo number all'interno di questo container
            var quantityInput = container.querySelector("input[type='number']");
            var quantity = quantityInput ? quantityInput.value : 1;

            // Debug: mostra i dati nella console
            console.log("Product ID:", productId, "Quantity:", quantity);

            fetch('addToCartHandler.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: 'product_id=' + encodeURIComponent(productId) + '&quantity=' + encodeURIComponent(quantity)
                })
                .then(function(response) {
                    return response.json();
                })
                .then(function(data) {
                    // Gestisci la risposta, ad esempio mostra un messaggio o aggiorna il carrello
                    console.log("Risposta dal server:", data);
                })
                .catch(function(error) {
                    console.error("Errore nella richiesta:", error);
                });
        });
    });
});
