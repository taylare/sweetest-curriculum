<?php 
include 'database/db.php';



if ($_SERVER['REQUEST_METHOD'] === 'POST'){ //checking if the form was submitted first 
    if (empty($_POST['email']) || empty($_POST['password'])) { //if user didn't input data in either field, display error msg
        $error = "Please enter both email and password";
    } else { //storing user's email & password in variables:
        $email = $_POST['email'];
        $password = $_POST['password'];

        
        // check if the connection worked
        if (!$dbc) {
            die("Connection failed: " . mysqli_connect_error());
        }
        //making the email input safe to use in the SQL query:
        $email = mysqli_real_escape_string($dbc, $email);

        //building and running the sql query:
        $sql = "SELECT * FROM users WHERE userEmail = '$email'";
        $result = mysqli_query($dbc, $sql);

        //check if user was found:
        if($result && mysqli_num_rows($result) === 1) {
            $user = mysqli_fetch_assoc($result); //storing array in user

            //verifying that the password matches:
            if($password === $user['userPassword']){
                //if pw is correct, log the user in and redirect to the appropriate page
                //saving the user's ID and admin status so we can remember who is logged in
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['isAdmin'] = $user['isAdmin'];
                $_SESSION['username'] = $user['username']; 

                //redirecting based on user's role
                if ($user['isAdmin'] == 1){
                    header("Location: admin/dashboard.php");
                    exit;
                } else {
                    header("Location: index.php");
                    exit;
                }

            
            } else { //passwords didnt match
                $error = "Invalid password, please try again.";
            }
        } else { //no user with that email found
            $error = "Invalid Email or Password.";
        }
        //close db connection:
        mysqli_close($dbc);

    }
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>

    body {
        background-color: rgb(213, 247, 255);
        margin: 0;
    }

        .form-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            padding: 20px;
        }

        form {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.4);
            width: 100%;
            max-width: 400px;
        }

        form h2 {
            font-size: 24px;
            margin-bottom: 18px;
            text-align: center;
            color: rgb(41, 42, 41);
            font-family: Arial, Helvetica, sans-serif;
        }

        form input[name='email'],
        form input[name='password'] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #d3d3d3;
            border-radius: 10px;
            font-size: 16px;
        }

        .btn {
            width: 100%;
            padding: 14px;
            background-color: rgb(41, 42, 41);
            border: none;
            border-radius: 8px;
            color: white;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            text-transform: uppercase;
        }

        .btn:hover {
            background-color: rgb(255, 174, 237);
            transition: background-color 0.4s ease;
        }

        .error-msg {
            color: rgb(255, 48, 65);
            font-family: Arial, Helvetica, sans-serif;
        }


    </style>
</head>
<body>
    
<h2 class="text-center">Login</h2>
<div class="form-container">
    <form method="POST">
    <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Password</label>
        <input type="password" name="password" class="form-control" required>
    </div>
    <button class="btn">Login</button>
    <p class="mt-3">Don't have an account? <a href="register.php">Register here</a>.</p>
    <?php if (isset($error)): ?>
        <p class="error-msg"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

</form>
    
</div>

</body>

</html>
