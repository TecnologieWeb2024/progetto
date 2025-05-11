document.addEventListener("DOMContentLoaded", function () {
  // Modal population code
  var productModal = document.getElementById("productModal");
  if (productModal) {
    productModal.addEventListener("show.bs.modal", function (event) {
      var button = event.relatedTarget;

      var productId = button.getAttribute("data-product-id");
      var productName = button.getAttribute("data-name");
      var productPrice = button.getAttribute("data-price");
      var productImage = button.getAttribute("data-image");
      var productDescription = button.getAttribute("data-description");
      var productMax = button.getAttribute("data-max");

      document.getElementById("modalProductName").textContent = productName;
      document.getElementById("modalProductPrice").textContent =
        productPrice + "€";
      document.getElementById("modalProductImage").src = productImage;
      document.getElementById("modalProductImage").alt = productName + " image";
      document.getElementById("modalProductDescription").textContent =
        productDescription;

      console.log("Product ID: " + productId);
      console.log("Product Name: " + productName);
      console.log("Product Price: " + productPrice);
      console.log("Product Image: " + productImage);
      console.log("Product Description: " + productDescription);
      console.log("Product Max: " + productMax);

      var maxQuantity = document.getElementById("modalQuantity");
      if (maxQuantity) {
        maxQuantity.max = productMax;
      }
      var productStockSpan = document.getElementById("modalProductStock");
      if (productStockSpan) {
        productStockSpan.textContent = productMax + " in stock";
      }
      var productIdSpan = document.getElementById("modalProductId");
      if (productIdSpan) {
        productIdSpan.textContent = productId;
      }
      var addToCartBtn = document.querySelector(".btn-add-to-cart");
      if (addToCartBtn) {
        addToCartBtn.setAttribute("data-product-id", productId);
      }
    });
  }

  // Incrementa quantità
  document
    .querySelectorAll(".quantity-right-plus, .quantity-right-plus-modal")
    .forEach(function (button) {
      button.addEventListener("click", function (e) {
        e.preventDefault();
        var input = this.closest(".d-flex").querySelector(
          "input[type='number']"
        );
        var quantity = parseInt(input.value);
        var max = parseInt(input.max);
        if (!isNaN(quantity)) {
          if (isNaN(max) || quantity < max) {
            input.value = quantity + 1;
          } else {
            input.value = max;
          }
        }
      });
    });

  // Decrementa quantità
  document
    .querySelectorAll(".quantity-left-minus, .quantity-left-minus-modal")
    .forEach(function (button) {
      button.addEventListener("click", function (e) {
        e.preventDefault();
        var input = this.closest(".d-flex").querySelector(
          "input[type='number']"
        );
        var quantity = parseInt(input.value);
        var min = parseInt(input.min);
        if (!isNaN(quantity)) {
          if (isNaN(min) || quantity > min) {
            input.value = quantity - 1;
          } else {
            input.value = min;
          }
        }
      });
    });

  // Cambio manuale della quantità con rispetto di min/max
  document.querySelectorAll("input[type='number']").forEach(function (input) {
    input.addEventListener("change", function () {
      var value = parseInt(this.value);
      var max = parseInt(this.max);
      var min = parseInt(this.min);
      if (!isNaN(value)) {
        if (!isNaN(max) && value > max) this.value = max;
        if (!isNaN(min) && value < min) this.value = min;
      }
    });
  });

  // Add-to-cart event listener
  document.querySelectorAll(".btn-add-to-cart").forEach(function (btn) {
    btn.addEventListener("click", function (e) {
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
        body:
          "product_id=" +
          encodeURIComponent(productId) +
          "&quantity=" +
          encodeURIComponent(quantity),
      })
        .then(function (response) {
          return response.json();
        })
        .then(function (data) {
          if (data.success) {
            console.log("Product added to cart successfully.");
            window.location.reload();
          } else {
            console.log("Failed to add product to cart: " + data.message);
          }
        })
        .catch(function (error) {
          console.log("Error while trying to add item to cart:", error);
        });
    });
  });
});
