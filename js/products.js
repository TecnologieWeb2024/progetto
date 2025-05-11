document.addEventListener("DOMContentLoaded", function () {
  // Modal population code
  const productModal = document.getElementById("productModal");
  if (productModal) {
    productModal.addEventListener("show.bs.modal", handleModalPopulating);
  }

  // Event delegation for quantity buttons
  document.addEventListener("click", function (e) {
    if (e.target.matches(".quantity-right-plus, .quantity-right-plus-modal")) {
      adjustQuantity(e.target, "increase");
    } else if (
      e.target.matches(".quantity-left-minus, .quantity-left-minus-modal")
    ) {
      adjustQuantity(e.target, "decrease");
    }
  });

  // Event delegation for add-to-cart buttons
  document.addEventListener("click", function (e) {
    if (e.target.matches(".btn-add-to-cart")) {
      handleAddToCart(e.target);
    }
  });

  // Handle modal content population
  function handleModalPopulating(event) {
    const button = event.relatedTarget;
    const productId = button.getAttribute("data-product-id");
    const productName = button.getAttribute("data-name");
    const productPrice = button.getAttribute("data-price");
    const productImage = button.getAttribute("data-image");
    const productDescription = button.getAttribute("data-description");
    const productMax = button.getAttribute("data-max");

    document.getElementById("modalProductName").textContent = productName;
    document.getElementById("modalProductPrice").textContent =
      productPrice + "€";
    document.getElementById("modalProductImage").src = productImage;
    document.getElementById("modalProductImage").alt = productName + " image";
    document.getElementById("modalProductDescription").textContent =
      productDescription;

    const maxQuantity = document.getElementById("modalQuantity");
    if (maxQuantity) {
      maxQuantity.max = productMax;
    }

    const stock = document.getElementById("modalProductStock")
    if (stock) {
      stock.textContent = productMax;
    }
    const id = document.getElementById("modalProductId")
    if (id) {
      id.textContent = productId;
    }
    document
      .querySelector(".btn-add-to-cart")
      .setAttribute("data-product-id", productId);
  }

  // Adjust quantity (either increase or decrease)
  function adjustQuantity(button, action) {
    const input = button
      .closest(".d-flex")
      .querySelector("input[type='number']");
    let quantity = parseInt(input.value);
    const max = parseInt(input.max);
    const min = parseInt(input.min);

    if (!isNaN(quantity)) {
      if (action === "increase" && (isNaN(max) || quantity < max)) {
        input.value = quantity + 1;
      } else if (action === "decrease" && (isNaN(min) || quantity > min)) {
        input.value = quantity - 1;
      }
    }
  }

  // Handle Add-to-cart functionality
  function handleAddToCart(button) {
    const productId = button.getAttribute("data-product-id");
    const container = button.closest(".d-flex");
    const quantityInput = container.querySelector("input[type='number']");
    const quantity = quantityInput ? quantityInput.value : 1;

    fetch("addToCartHandler.php", {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: `product_id=${encodeURIComponent(
        productId
      )}&quantity=${encodeURIComponent(quantity)}`,
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          console.log("Product added to cart successfully.");
          window.location.reload();
        } else {
          console.log("Failed to add product to cart: " + data.message);
        }
      })
      .catch((error) => {
        console.error("Error while trying to add item to cart:", error);
      });
  }
});
