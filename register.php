<?php
    // Start the session
    session_start();

    //Include useful functions
    include_once 'tools/useful_fonctions.php';

    //If there is already a connected user, redirect to the charging page
    if(user_logged()){
        header('Location: charging.php');
        //End of the script: Useful to keep session messages in memory
        exit();
    }

    //Process registration
    process_registration();
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
    <form class="form-signin" method="post" action="register.php">

        <?php include 'tools/flash_messages.php'; ?>
    
        <h1 class="h3 mb-3 font-weight-normal">Charging App</h1>
        <h1 class="lead">New account</h1>
        <div class="form-group">
            <label for="inputFirstName" class="sr-only">First Name</label>
            <input type="text" name="first_name" id="inputFirstName" class="form-control" placeholder="First Name" value="<?php if(isset($_POST["first_name"])){echo $_POST["first_name"];} ?>">
        </div>
        <div class="form-group">
            <label for="inputLastName" class="sr-only">Last Name</label>
            <input type="text" name="last_name" id="inputLastName" class="form-control" placeholder="Last Name" value="<?php if(isset($_POST["last_name"])){echo $_POST["last_name"];} ?>" required>
        </div>
        <div class="form-group">
            <label for="inputEmail" class="sr-only">Email address</label>
            <input type="email" name="email" id="inputEmail" class="form-control" placeholder="Email address" value="<?php if(isset($_POST["email"])){echo $_POST["email"];} ?>" required autofocus>
        </div>
        <div class="form-group">
            <label for="inputPassword" class="sr-only">Password</label>
            <input type="password" name="password" id="inputPassword" class="form-control" placeholder="Password" required>
        </div>
        <div class="form-group">
            <label for="inputPasswordConfirmation" class="sr-only">Password Confirmation</label>
            <input type="password" name="password_confirmation" id="inputPasswordConfirmation" class="form-control" placeholder="Password" required>
        </div>
        <button class="btn btn-lg btn-primary btn-block" type="submit">Create</button>
        <p class="mt-5 mb-3 text-center">
            Already have an account? 
            <a href="login.php">sign in now</a>
        </p>
        <p class="mt-5 mb-3 text-muted">&copy; <?php echo date("Y"); ?></p>
    </form>
</body>
</html>