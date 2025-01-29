<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-dark text-light">
                <p class="modal-title" id="loginModalLabel">Login</p>
                <a href="#" title="close" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></a>
            </div>
            <div class="container-fluid d-flex justify-content-center align-items-center">
                <div class="col-md-4">
                    <div class="modal-body">
                        <form action="authenticate.php" method="post" class="needs-validation" novalidate>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email:</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                                <div class="invalid-feedback">
                                    Inserisci una mail valida.
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password:</label>
                                <input type="password" class="form-control" id="password" name="password" required>
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
                            <button type="submit" class="btn btn-primary w-100">Login</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer fixed-bottom justify-content-center text-center bg-light py-3">
        Non hai un account?<a href="#" class="btn btn-link" data-bs-toggle="modal" data-bs-target="#registerModal" style="text-decoration: none"><em class="fa fa-user-plus"></em>&nbsp;Registrati</a>
    </div>
</div>
<?php require("register.php"); ?>
<script>
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