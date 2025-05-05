document.addEventListener("DOMContentLoaded", function () {
    var orderModal = document.getElementById("orderModal");
    if (!orderModal) return;
  
    // Blura l'elemento attivo prima che il modale venga mostrato
    orderModal.addEventListener("show.bs.modal", function (event) {
      setTimeout(() => {
        if (document.activeElement) {
          document.activeElement.blur();
        }
      }, 0); // eseguito subito dopo la chiamata a show
    });
  
    // Gestione dei dati ordine
    orderModal.addEventListener("show.bs.modal", function (event) {
      var orderId = event.relatedTarget.getAttribute("data-order-id");
  
      fetch("getFullOrderDetails.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "order_id=" + encodeURIComponent(orderId),
      })
        .then((res) => res.json())
        .then((resp) => {
          if (!resp.success) {
            console.error("Errore recupero dettagli ordine:", resp.message);
            return;
          }
          var d = resp.data;
          console.log("Dettagli ordine:", d);
          // Riepilogo
          document.getElementById("modalOrderId").textContent = d.order_id;
          document.getElementById("modalOrderDate").textContent = new Date(
            d.order_date
          ).toLocaleDateString();
          var total = Number(d.total_price) || 0;
          document.getElementById("modalOrderTotal").textContent =
            total.toFixed(2) + " €";
          document.getElementById("modalOrderState").textContent = d.order_state;
  
          // Pagamento
          document.getElementById("modalPaymentStatus").textContent =
            d.payment_status;
          document.getElementById("modalPaymentMethod").textContent =
            d.payment_method || "–";
  
          // Spedizione
          document.getElementById("modalShipmentAddress").textContent =
            d.shipment_address || "–";
          document.getElementById("modalShippingMethod").textContent =
            d.shipping_method || "–";
          document.getElementById("modalShipmentStatus").textContent =
            d.shipment_status || "–";
  
          // Prodotti
          var tbody = document.getElementById("modalOrderItems");
          tbody.innerHTML = "";
          (d.items || []).forEach((item) => {
            var subtotal = (Number(item.price) * Number(item.quantity)).toFixed(2);
            var tr = document.createElement("tr");
            tr.innerHTML = `
                      <td>${item.product_name}</td>
                      <td>€ ${Number(item.price).toFixed(2)}</td>
                      <td>${item.quantity}</td>
                      <td>€ ${subtotal}</td>
                  `;
            tbody.appendChild(tr);
          });
        })
        .catch((err) => console.error("Fetch error:", err));
    });
  });
  