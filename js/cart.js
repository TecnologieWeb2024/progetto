document.querySelectorAll(".quantity-right-plus").forEach(function (button) {
  button.addEventListener("click", function () {
    var input = button.parentElement.querySelector("input");
    $.ajax({
      url: "modifyCartHandler.php",
      method: "POST",
      data: {
        product_id: input.id.split("-")[1],
        quantity: parseInt(input.value) + 1,
      },
      success: function (response) {
        if (response.success) {
          window.location.reload();
        } else {
          alert(response.message);
        }
      },
    });
  });
});

document.querySelectorAll(".quantity-left-minus").forEach(function (button) {
  button.addEventListener("click", function () {
    var input = button.parentElement.querySelector("input");
    $.ajax({
      url: "modifyCartHandler.php",
      method: "POST",
      data: {
        product_id: input.id.split("-")[1],
        quantity: parseInt(input.value) - 1,
      },
      success: function (response) {
        if (response.success) {
          window.location.reload();
        } else {
          alert(response.message);
        }
      },
    });
  });
});

$(document).ready(function () {
  $(".btn-danger").click(function () {
    var productId = $(this).data("product-id");
    $.ajax({
      url: "modifyCartHandler.php",
      method: "POST",
      data: {
        product_id: productId,
        remove: true,
      },
      success: function (response) {
        if (response.success) {
          window.location.reload();
        } else {
          alert(response.message);
        }
      },
    });
  });
});

$(document).ready(function () {
  $("input[type='number']").on("change", function () {
    var input = $(this);
    $.ajax({
      url: "modifyCartHandler.php",
      method: "POST",
      data: {
        product_id: input.attr("id").split("-")[1],
        quantity: parseInt(input.val()),
      },
      success: function (response) {
        if (response.success) {
          window.location.reload();
        } else {
          alert(response.message);
        }
      },
    });
  });
});
