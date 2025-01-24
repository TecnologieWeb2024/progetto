<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title><?php echo $templateParams["titolo"] ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="./css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <nav class="navbar navbar-expand-md bg-dark navbar-dark justify-content-center sticky-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">COFFEEBO</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#collapsibleNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-center" id="collapsibleNavbar">
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link" href="#">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Prodotti</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Contatti</a></li>
                </ul>
                <?php if (isUserLoggedIn()) : ?>
                    <ul class="navbar-nav ms-auto">
                        <!-- TODO: Usare icone per Carrello e Logout -->
                        <li class="nav-item"><a class="nav-link" href="#">Carrello</a></li>
                        <li class="nav-item"><a class="nav-link logout-link" href="#">Logout</a></li>
                    </ul>
                <?php else: ?>
                    <ul class="navbar-nav ms-auto">
                        <!-- TODO: Usare icone per Login -->
                        <li class="nav-item"><a class="nav-link" href="#">Login</a></li>
                    </ul>
                <?php endif; ?>
                
            </div>
        </div>
    </nav>

    <main>

    </main>

    <footer>

    </footer>
</body>

</html>