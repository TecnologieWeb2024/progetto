<?php

require_once('bootstrap.php');
// $user = $dbh->getUserInfo($_SESSION['customer']['user_id']);
?>
<h1 class="text-center">SELLER</h1>
<div class="container col-md-4 mt-4">
    <div class="d-grid gap-2 col-6 mx-auto">
        <!-- // TODO: mettere qualcosa di piu' bello -->
        <button type="button" class="btn btn-primary" onclick="window.location='index.php?page=gestioneProdotti'">Gestione prodotti</button>
        <button type="button" class="btn btn-primary" onclick="window.location='index.php?page=gestioneOrdini'">Gestione ordini</button>
    </div>
</div>