<!DOCTYPE html>
<head>
    <title>Login</title>
    <meta charset="UTF-8">
</head>
<html lang="it">
<body>
    
    <h1>Registrazione</h1>
    
    <form action="utils/process-signup.php" method="post" id="signup" novalidate>
        <div>
            <label for="name">Name</label>
            <input type="text" id="name" name="name">
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
            <label for="password_confirmation">Repeat password</label>
            <input type="password" id="password_confirmation" name="password_confirmation">
        </div>
        
        <button>Sign up</button>
    </form>



    <h1 style="color:green;">
        GeeksforGeeks
    </h1>
    
    <h4>
        How to call PHP function
        on the click of a Button ?
    </h4>
    
    <?php
        if(array_key_exists('button1', $_POST)) {
            button1();
        }
        else if(array_key_exists('button2', $_POST)) {
            button2();
        }
        function button1() {
            echo "This is Button1 that is selected";
        }
        function button2() {
            echo "This is Button2 that is selected";
        }
    ?>

    <form action="utils/process-signup.php" method="post">
        <input type="submit" name="button1"
                class="button" value="Button1" />
        
        <input type="submit" name="button2"
                class="button" value="Button2" />
    </form>
    
</body>

</html>