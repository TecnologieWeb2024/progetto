document.addEventListener("DOMContentLoaded", function () {
  // Incrementa quantità
  document.querySelectorAll(".quantity-right-plus").forEach(function (button) {
    button.addEventListener("click", function () {
      var input = button.parentElement.querySelector("input");
      updateQuantity(input, parseInt(input.value) + 1);
    });
  });

  // Decrementa quantità
  document.querySelectorAll(".quantity-left-minus").forEach(function (button) {
    button.addEventListener("click", function () {
      var input = button.parentElement.querySelector("input");
      updateQuantity(input, parseInt(input.value) - 1);
    });
  });

  // Cambio manuale della quantità
  document.querySelectorAll("input[type='number']").forEach(function (input) {
    input.addEventListener("change", function () {
      updateQuantity(input, parseInt(input.value));
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
