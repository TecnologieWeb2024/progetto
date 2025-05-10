<?php

require_once('bootstrap.php');
if (isset($_SESSION['customer'])) {
    $user = $dbh->getUserInfo($_SESSION['customer']['user_id']);
} elseif (isset($_SESSION['seller'])) {
    $user = $dbh->getUserInfo($_SESSION['seller']['user_id']);
}
?>
<h1 class="text-center">Impostazioni Account</h1>
<div class="container col-md-4 mt-4">
    <form action="updateAccount.php" method="post">
        <div class="form-group">
            <label class="mt-2" for="first_name">Nome:</label>
            <input type="text" class="form-control mt-2" id="first_name" value="<?php echo htmlspecialchars($user['first_name']); ?>" readonly>
        </div>
        <div class="form-group">
            <label class="mt-2" for="last_name">Cognome:</label>
            <input type="text" class="form-control mt-2" id="last_name" value="<?php echo htmlspecialchars($user['last_name']); ?>" readonly>
        </div>
        <div class="form-group">
            <label class="mt-2" for="email">Email:</label>
            <input type="email" class="form-control mt-2" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
        </div>
        <h6 class="mt-4 text-danger text-center">Modifica Password</h6>
        <div class="form-group">
            <label for="new_password">Nuova Password:</label>
            <input type="password" class="form-control" id="new_password" name="new_password">
        </div>
        <div class="form-group">
            <label for="confirm_password">Conferma Password:</label>
            <input type="password" class="form-control" id="confirm_password" name="confirm_password">
        <button type="submit" class="btn btn-primary w-100 mt-4">Update</button>
    </form>
</div>