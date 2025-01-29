<div class="modal fade" id="registerModal" tabindex="-1" aria-labelledby="registerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-dark text-light">
                <p class="modal-title" id="registerModalLabel">Registrazione</p>
                <a href="#" title="close" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></a>
            </div>
            <div class="container-fluid d-flex justify-content-center align-items-center">
                <div class="col-md-4">
                    <div class="modal-body">
                        <form action="register.php" method="post" class="needs-validation" novalidate>
                            <div class="mb-3">
                                <label for="name" class="form-label">Nome:</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                                <div class="invalid-feedback">
                                    Inserisci il tuo nome.
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="surname" class="form-label">Cognome:</label>
                                <input type="text" class="form-control" id="surname" name="surname" required>
                                <div class="invalid-feedback">
                                    Inserisci il tuo cognome.
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="registration-email" class="form-label">Email:</label>
                                <input type="email" class="form-control" id="registration-email" name="registration-email" required>
                                <div class="invalid-feedback">
                                    Inserisci una mail valida.
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="registration-password" class="form-label">Password:</label>
                                <input type="password" class="form-control" id="registration-password" name="registration-password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W_]).{8,}" required>
                                <div class="invalid-feedback">
                                    La password deve essere di almeno 8 caratteri:
                                    <ul>
                                        <li>Una maiuscola.</li>
                                        <li>Una minuscola.</li>
                                        <li>Un numero.</li>
                                        <li>Un carattere speciale.</li>
                                    </ul>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="phone" class="form-label">Telefono:</label>
                                <input type="tel" class="form-control" id="phone" name="phone" required>
                                <div class="invalid-feedback">
                                    Inserisci un numero di telefono.
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Registrati</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer fixed-bottom justify-content-center text-center bg-light py-3">
        Sei già registrato?<a href="#" class="btn btn-link" data-bs-toggle="modal" data-bs-target="#loginModal" style="text-decoration: none"><em class="fa fa-sign-in"></em>&nbsp;Login</a>
    </div>
</div>

<script>
    // Example starter JavaScript for disabling form submissions if there are invalid fields
    (function() {
        'use strict'

        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        var forms = document.querySelectorAll('.needs-validation')

        // Loop over them and prevent submission
        Array.prototype.slice.call(forms)
            .forEach(function(form) {
                form.addEventListener('submit', function(event) {
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    }

                    form.classList.add('was-validated')
                }, false)
            })
    })();
</script>