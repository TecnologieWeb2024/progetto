function changeOrderStatus () {
  const orderId = document.getElementById("modalOrderId").textContent;

  fetch("utils/updateOrderStatus.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify({
      order_id: orderId,
      new_status: 4, // In preparazione
    }),
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        alert("Ordine aggiornato con successo");
        // ricarica la pagina
        location.reload();
      } else {
        alert("Errore: " + data.message);
      }
    })
    .catch((err) => {
      console.error("Errore AJAX:", err);
      alert("Errore durante la richiesta.");
    });
}


function cancelOrder() {
  const orderId = document.getElementById("modalOrderId").textContent;

  fetch("utils/updateOrderStatus.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify({
      order_id: orderId,
      new_status: 7, // Cancellato
    }),
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        alert("Ordine aggiornato con successo");
        // reload pagina
        location.reload();
      } else {
        alert("Errore: " + data.message);
      }
    })
    .catch((err) => {
      console.error("Errore AJAX:", err);
      alert("Errore durante la richiesta.");
    });
}
