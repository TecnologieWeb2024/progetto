<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $templateParams["title"] ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="./css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <nav class="navbar navbar-expand-md bg-dark navbar-dark sticky-top">
        <div class="container-fluid d-flex">
            <div class="col-3 d-flex flex-grow-1">
                <a class="navbar-brand" href="index.php?page=home">COFFEEBO</a>
            </div>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target=".collapsibleNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="col-6 collapse flex-grow-1 navbar-collapse justify-content-center collapsibleNavbar">
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link" href="index.php?page=home"><em class="fa fa-home d-md-none">&nbsp;</em>Home Page</a></li>
                    <li class="nav-item"><a class="nav-link" href="index.php?page=products"><em class="fa fa-coffee d-md-none">&nbsp;</em>I nostri prodotti</a></li>
                    <li class="nav-item"><a class="nav-link" href="index.php?page=contacts"><em class="fa fa-file-text-o	d-md-none">&nbsp;</em>Contattaci</a></li>
                </ul>
            </div>
            <div class="col-3 collapse flex-grow-1 navbar-collapse justify-content-end collapsibleNavbar">
                <?php if (isUserLoggedIn()) : ?>
                    <ul class="navbar-nav">
                        <!-- TODO: Usare icone per Carrello e Logout -->
                        <li class="nav-item"><a class="nav-link" href="#"><em class="fa fa-shopping-bag">&nbsp;</em><span class="d-md-none">Carrello</span></a></li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <em class="fa fa-user">&nbsp;</em><span class="d-md-none">Account</span>

                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <li><a href="#" class="dropdown-item"><em class="fa fa-cog"></em>&nbsp;Impostazioni Account</a></li>
                                <li><a href="index.php?page=orders" class="dropdown-item"><em class="fa fa-list-alt"></em>&nbsp;I miei ordini</a></li>
                                <li><a href="#" class="dropdown-item link-danger link-underline-opacity-0"><em class="fa fa-sign-out"></em>&nbsp;Logout</a></li>
                            </ul>
                        </li>
                    </ul>
                <?php else: ?>
                    <ul class="navbar-nav">
                        <!-- TODO: Usare icone per Login -->
                        <li class="nav-item"><a href="#" class="btn btn-primary d-none d-md-inline" style="text-decoration: none" data-bs-toggle="modal" data-bs-target="#loginModal"><em class="fa fa-sign-in"></em>&nbsp;Login</a></li>
                        <li class="nav-item"><a href="#" class="link-primary d-inline d-md-none" style="text-decoration: none" data-bs-toggle="modal" data-bs-target="#loginModal"><em class="fa fa-sign-in"></em>&nbsp;Login</a></li>
                    </ul>
                <?php endif; ?>
            </div>
        </div>
    </nav>
    <div>
        <?php
        if (!empty($templateParams["alerts"])) :
            require($templateParams["alerts"]);
        endif;
        ?>
    </div>
    <main class="flex-fill mt-4">
        <?php
        if (!empty($templateParams["main-content"])) :
            require($templateParams["main-content"]);
        endif;
        if (!isUserLoggedIn()):
            require("login.php");
        endif;
        ?>
    </main>
    <aside>
        <?php
        if (!empty($templateParams["aside-content"])) {
            require($templateParams["aside-content"]);
        }
        ?>
    </aside>
    <footer class="py-4 bg-dark text-center">
        <div class="container-fluid justify-content-center text-secondary">
            <div class="mb-3">
                <a href="#" class="text-light text-decoration-none">About Us</a>
            </div>
            <p>Copyright © <?php echo date("Y"); ?> CoffeeBo All rights reserved.</p>
        </div>
    </footer>
</body>

</html>