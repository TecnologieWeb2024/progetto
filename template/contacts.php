<div class="container mb-4">
    <h1 class="mb-4">Contatti</h1>

    <p>Se hai domande, non esitare a contattarci attraverso i seguenti metodi:</p>

    <h2 class="mt-4">Email</h2>
    <p>Puoi inviarci un'email a <a href="mailto:info@example.com">info@example.com</a></p>

    <h2 class="mt-4">Telefono</h2>
    <p>Chiamaci al: (123) 456-7890</p>

    <h2 class="mt-4">Indirizzo</h2>
    <p>Vieni a trovarci in:</p>
    <address>
        Via Dell'Università 50, Cesena, FC
    </address>

    <h2 class="mt-4">Modulo di Contatto</h2>
    <form action="#" method="post" class="needs-validation" novalidate>
        <div class="mb-3">
            <label for="name" class="form-label">Nome:</label>
            <input type="text" id="name" name="name" class="form-control" required>
            <div class="invalid-feedback">
                Per favore inserisci il tuo nome.
            </div>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email:</label>
            <input type="email" id="email" name="email" class="form-control" required>
            <div class="invalid-feedback">
                Per favore inserisci un indirizzo email valido.
            </div>
        </div>

        <div class="mb-3">
            <label for="message" class="form-label">Messaggio:</label>
            <textarea id="message" name="message" class="form-control" rows="5" required></textarea>
            <div class="invalid-feedback">
                Per favore inserisci il tuo messaggio.
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Invia</button>
    </form>
</div>