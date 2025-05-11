document.addEventListener("DOMContentLoaded", function () {
  // Incrementa quantità
  document.querySelectorAll(".quantity-right-plus").forEach(function (button) {
    button.addEventListener("click", function () {
      var input = button.parentElement.querySelector("input");
      var currentQuantity = parseInt(input.value);
      var maxQuantity = parseInt(input.max);

      if (currentQuantity < maxQuantity) {
        updateQuantity(input, currentQuantity + 1);
      }
    });
  });

  // Decrementa quantità
  document.querySelectorAll(".quantity-left-minus").forEach(function (button) {
    button.addEventListener("click", function () {
      var input = button.parentElement.querySelector("input");
      var currentQuantity = parseInt(input.value);
      var minQuantity = parseInt(input.min);

      if (currentQuantity > minQuantity) {
        updateQuantity(input, currentQuantity - 1);
      }
    });
  });

  // Cambio manuale della quantità
  document.querySelectorAll("input[type='number']").forEach(function (input) {
    input.addEventListener("change", function () {
      var quantity = parseInt(this.value);
      var maxQuantity = parseInt(this.max);
      var minQuantity = parseInt(this.min);

      if (quantity > maxQuantity) {
        this.value = maxQuantity;
      } else if (quantity < minQuantity) {
        this.value = minQuantity;
      } else {
        updateQuantity(this, quantity);
      }
    });
  });

  // Rimozione prodotto dal carrello
  document.querySelectorAll(".btn-danger").forEach(function (btn) {
    btn.addEventListener("click", function () {
      var productId = btn.dataset.productId;
      fetch("modifyCartHandler.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/x-www-form-urlencoded",
        },
        body: `product_id=${encodeURIComponent(productId)}&remove=true`,
      })
        .then((res) => res.json())
        .then((res) => {
          if (res.success) {
            window.location.reload();
          }
        });
    });
  });

  function updateQuantity(input, quantity) {
    fetch("modifyCartHandler.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded",
      },
      body: `product_id=${encodeURIComponent(
        input.id.split("-")[1]
      )}&quantity=${encodeURIComponent(quantity)}`,
    })
      .then((res) => res.json())
      .then((res) => {
        if (res.success) {
          window.location.reload();
        }
      });
  }
});
