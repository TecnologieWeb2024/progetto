let timerID = null; // puntatore  timer
let conteggio = 0;

function avviaTimer(secondi=5) {
    timerID = setInterval(() => {
        conteggio++;
        console.log(`🔔 Notifica #${conteggio}`);
    }, secondi * 1000); // ogni 1 secondo

    console.log("⏳ Timer avviato.");
}

function fermaTimer() {
    if (timerID !== null) {
        clearInterval(timerID);
        console.log("⏹️ Timer disattivato manualmente.");
        timerID = null;
        conteggio = 0;
    }
}
