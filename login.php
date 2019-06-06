<?php
    // Start the session
    session_start();

    //Include useful functions
    include_once 'tools/useful_fonctions.php';

    //Process login
    process_login();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="custom_scripts/signin.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <script src="js/bootstrap.min.js"></script>
</head>
<body>
    <form class="form-signin" method="post" action="login.php">
        
        <?php include 'tools/flash_messages.php'; ?>
        
        <h1 class="h3 mb-3 font-weight-normal">Charging App</h1>
        <h1 class="lead">Please sign in</h1>
        <div class="form-group">
            <label for="inputEmail" class="sr-only">Email address</label>
            <input type="email" name="email" id="inputEmail" class="form-control" placeholder="Email address" value="<?php if(isset($_POST["email"])){echo $_POST["email"];} ?>" required autofocus>
        </div>
        <div class="form-group">
            <label for="inputPassword" class="sr-only">Password</label>
            <input type="password" name="password" id="inputPassword" class="form-control" placeholder="Password" required>
        </div>
        <button class="btn btn-lg btn-primary btn-block" type="submit">Connect</button>
        <p class="mt-5 mb-3 text-center">Don't have an account? 
            <a href="register.php">create your own now</a>
        </p>
        <p class="mt-5 mb-3 text-muted">&copy; <?php echo date("Y"); ?></p>
    </form>
</body>
</html>