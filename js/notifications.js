function markAsRead(notificationId) {
  // Simula la marcatura di una notifica come letta
  const notification = document.querySelector(
    `.list-group-item[data-notification-id="${notificationId}"]`
  );
  if (notification && notification.classList.contains("bg-primary-subtle")) {
    notification.classList.remove("active", "bg-primary-subtle");
    notification.classList.add("bg-secondary-subtle");

    // Call PHP function via AJAX
    fetch("utils/updateNotifications.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({
        notificationIds: [notificationId],
      }),
    })
      .then((response) => response.json())
      .then((data) => {
        console.log(data);
        if (data.success) {
          alert("Notifica segnata come letta.");
        } else {
          alert(
            "Si è verificato un errore durante l'aggiornamento della notifica."
          );
        }
      })
      .catch((error) => {
        console.error("Error:", error);
        alert(
          "Si è verificato un errore durante l'aggiornamento della notifica."
        );
      });
  } else if (
    notification &&
    notification.classList.contains("bg-secondary-subtle")
  ) {
    alert("Notifica già letta");
  } else {
    alert("Notifica non trovata.");
  }
}

function markAllAsRead() {
  console.log("markAllAsRead");
  // Simula la marcatura di tutte le notifiche come lette
  const notifications = document.querySelectorAll(".list-group-item");
  const notificationIds = [];

  notifications.forEach((notification) => {
    // Solo se la notifica non è letta
    if (notification.classList.contains("bg-primary-subtle")) {
      const notificationId = parseInt(
        notification.getAttribute("data-notification-id")
      );
      notificationIds.push(notificationId);

      // Update UI
      // notification.classList.remove('active', 'bg-primary-subtle');
      // notification.classList.add('bg-secondary-subtle');
    }
  });

  // Call PHP function for each notification via AJAX
  if (notificationIds.length > 0) {
    fetch("utils/updateNotifications.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({
        notificationIds: notificationIds,
      }),
    })
      .then((response) => response.json())
      .then((data) => {
        console.log(data);
        if (data.success) {
          alert("Tutte le notifiche sono state segnate come lette.");
          location.reload();
        } else {
          alert(
            "Si è verificato un errore durante l'aggiornamento delle notifiche."
          );
        }
      })
      .catch((error) => {
        console.error("Error:", error);
        alert(
          "Si è verificato un errore durante l'aggiornamento delle notifiche."
        );
      });
  } else {
    alert("Tutte le notifiche sono già state lette.");
  }
}

function markAllAsNotRead() {
  console.log("markAllAsRead");
  // Simula la marcatura di tutte le notifiche come lette
  const notifications = document.querySelectorAll(".list-group-item");
  const notificationIds = [];

  notifications.forEach((notification) => {
    // Solo se la notifica non è letta
    if (notification.classList.contains("bg-secondary-subtle")) {
      const notificationId = parseInt(
        notification.getAttribute("data-notification-id")
      );
      notificationIds.push(notificationId);

      // Update UI
      // notification.classList.remove('active', 'bg-primary-subtle');
      // notification.classList.add('bg-secondary-subtle');
    }
  });

  // Call PHP function for each notification via AJAX
  if (notificationIds.length > 0) {
    fetch("utils/resetUpdateNotifications.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({
        notificationIds: notificationIds,
      }),
    })
      .then((response) => response.json())
      .then((data) => {
        console.log(data);
        if (data.success) {
          alert("Tutte le notifiche sono state segnate come non lette.");
          location.reload();
        } else {
          alert(
            "Si è verificato un errore durante l'aggiornamento delle notifiche."
          );
        }
      })
      .catch((error) => {
        console.error("Error:", error);
        alert(
          "Si è verificato un errore durante l'aggiornamento delle notifiche."
        );
      });
  } else {
    alert("Tutte le notifiche sono già state lette.");
  }
}

function sendNotification(userId, message) {
  console.log(userId);
  console.log(message);

  fetch("utils/insertNotification.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify({
      user_id: userId,
      message: message,
    }),
  })
    .then((response) => response.json())
    .then((data) => {
      console.log(data);
      if (data.success) {
        alert("Notifica inviata con successo!");
        location.reload();
      } else {
        alert("Errore: " + data.error);
      }
    })
    .catch((error) => {
      console.error("Errore nella richiesta:", error);
      alert("Errore durante l'invio della notifica.");
    });
}
