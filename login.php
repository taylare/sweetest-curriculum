<?php 
include 'database/db.php';

/**************************************************/
/***BLOCK OF CODE THAT RUNS ONCE USER HITS LOGIN***/
/*************************************************/
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Quicksand:wght@400;600&display=swap');

        body {
        background-color: rgb(221, 220, 222);
        font-family: 'Quicksand', sans-serif;
        margin: 0;
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
        min-height: 100vh;
        padding: 20px;
        }

        .form-container {
        width: 100%;
        max-width: 500px;
        background-color: #fff;
        border: 2px dashed #923c79;
        border-radius: 20px;
        padding: 30px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
        transition: 0.3s ease;
        }

        form h2 {
        font-size: 26px;
        margin-bottom: 20px;
        text-align: center;
        font-weight: 600;
        color: #923c79;
        }

        label {
        font-weight: 600;
        color: rgb(171, 121, 244);
        }

        form input[name='email'],
        form input[name='password'] {
        width: 100%;
        padding: 12px;
        margin-bottom: 20px;
        border: 1px solid #e3c6ff;
        border-radius: 14px;
        background-color: #fdf7ff;
        font-size: 16px;
        transition: all 0.2s ease;
        }

        .form-control:focus {
        border-color: #c8b6ff;
        box-shadow: 0 0 0 4px rgba(207, 178, 255, 0.3);
        background-color: #fcfaff;
        }

        .btn {
        width: 100%;
        padding: 14px;
        background-color: #923c79;
        border: none;
        border-radius: 14px;
        color: rgb(154, 255, 154);
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        text-transform: uppercase;
        transition: background-color 0.4s ease;
        }

        .btn:hover {
        background-color: rgba(154, 255, 154, 0.87);
        color: #923c79;
        }

        .error-msg {
        color: rgb(255, 48, 65);
        font-family: Arial, sans-serif;
        font-weight: 500;
        margin-top: 10px;
        text-align: center;
        }

        .register-link {
        text-decoration: none;
        color: #923c79;
        font-style: italic;
        font-weight: bolder;
        }

        .register-link:hover {
        color: rgb(154, 255, 154);
        text-decoration: underline;
        }

        .icons-container {
        text-align: center;
        padding: 20px 10px;
        color: #923c79;
        font-family: 'Quicksand', sans-serif;
        }

        .icons-container .social-icons a {
        color: #923c79;
        margin: 0 20px;
        font-size: 1.6rem;
        transition: transform 0.3s ease, color 0.3s ease;
        }

        .icons-container .social-icons a:hover {
        color: rgb(132, 250, 132);
        transform: scale(1.2);
        }

        .tag:hover {
        transform: scale(1.3); /*scales slightly when hovering over btn */
        }

    </style>
</head>
<body>
    
    <div class="form-container">
        <form method="POST">
        <div class="mb-3">
            <h2 class="text-center">Login</h2>
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <button class="btn">Login</button>
        <p class="mt-3">Don't have an account? <a class="register-link" href="register.php">Register here</a>.</p>
        <?php if (isset($error)): ?>
            <p class="error-msg"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
        </form>
        
    </div>
    <div class="icons-container">
        <div class="social-icons text-center">
            <a href="#" target="_blank"><i class="fab fa-instagram tag" data-icon="instagram"></i></a>
            <a href="#" target="_blank"><i class="fab fa-facebook-f tag" data-icon="facebook"></i></a>
            <a href="#" target="_blank"><i class="fab fa-tiktok tag" data-icon="tiktok"></i></a>
        </div>
    </div>
</body>
</html>
