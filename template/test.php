<!DOCTYPE html>
<head>
    <title>Login</title>
    <meta charset="UTF-8">
</head>
<html lang="it">
<body>
    
    <h1>Registrazione</h1>
    
    <form action="process-signup.php" method="post" id="signup" novalidate>
        <div>
            <label for="nome">Nome</label>
            <input type="text" id="nome" name="nome">
        </div>
        
        <div>
            <label for="email">email</label>
            <input type="email" id="email" name="email">
        </div>
        
        <div>
            <label for="password">Password</label>
            <input type="password" id="password" name="password">
        </div>
        
        <div>
            <label for="conferma_password">Ripeti la password</label>
            <input type="password" id="conferma_password" name="conferma_password">
        </div>
        
        <button>Registrati</button>
    </form>
    
</body>

</html>