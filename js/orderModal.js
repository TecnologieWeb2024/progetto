function changeStatus(orderId, newStatus) {
  fetch("utils/updateOrderStatus.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify({
      order_id: orderId,
      new_status: newStatus,
    }),
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
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

function acceptOrder() {
  const orderId = document.getElementById("modalOrderId").textContent;
  changeStatus(orderId, 4); // In preparazione
}

function cancelOrder() {
  const orderId = document.getElementById("modalOrderId").textContent;
  changeStatus(orderId, 7); // Cancellato
}

function advanceStatus() {
  const orderId = document.getElementById("modalOrderId").textContent;
  const currentStatus = document.getElementById("modalOrderStatus").textContent;
  let newStatus = stringToStatus(currentStatus);
  if (newStatus === null) {
    alert("Stato dell'ordine sconosciuto: " + currentStatus);
    return;
  }
  newStatus += 1; // Avanza allo stato successivo
  if (newStatus > 0 && newStatus < 7) {
    changeStatus(orderId, newStatus);
  }
}

/*
    (1, 'Checkout'),
    (2, 'In attesa di pagamento'),
    (3, 'Pagato'),
    (4, 'In preparazione'),
    (5, 'Spedito'),
    (6, 'Consegnato'),
    (7, 'Cancellato'),
    (8, 'Rimborsato')
*/
function stringToStatus(statusDesc) {
  switch (statusDesc) {
    case "Checkout":
      return 1;
    case "In attesa di pagamento":
      return 2;
    case "Pagato":
      return 3;
    case "In preparazione":
      return 4;
    case "Spedito":
      return 5;
    case "Consegnato":
      return 6;
    case "Cancellato":
      return 7;
    case "Rimborsato":
      return 8;
    default:
      console.error("Status sconosciuto:", statusDesc);
      return null;
  }
}