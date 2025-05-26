<?php 
include 'database/db.php';
session_start();


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
                $error = "invalid email or password";
                echo $error;
            }
        } else { //no user with that email found
            $error = "invalid email or password";
            echo $error;
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
</head>
<body>
    
<h2>Login</h2>

<form method="POST">
  <div class="mb-3">
    <label class="form-label">Email</label>
    <input type="email" name="email" class="form-control" required>
  </div>
  <div class="mb-3">
    <label class="form-label">Password</label>
    <input type="password" name="password" class="form-control" required>
  </div>
  <button class="btn btn-primary">Login</button>
</form>

<p class="mt-3">Don't have an account? <a href="register.php">Register here</a>.</p>


</body>

</html>
