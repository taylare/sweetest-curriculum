<?php
include 'database/db.php'; 

//check if the form was submitted using POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    //check if email or password fields were empty
    if (empty($_POST['email']) || empty($_POST['password'])) {
        // save error message in a session variable temporarily
        $_SESSION['login_error'] = "Please enter both email and password.";
        header("Location: login.php");
        exit;
    } else {
        //store submitted email and password
        $email = $_POST['email'];
        $password = $_POST['password'];


        //sanitize email to prevent SQL injection
        $email = mysqli_real_escape_string($dbc, $email);

        //look up the user by email
        $sql = "SELECT * FROM users WHERE userEmail = '$email'";
        $result = mysqli_query($dbc, $sql);

        //if user is found
        if ($result && mysqli_num_rows($result) === 1) {
            $user = mysqli_fetch_assoc($result); // Fetch user data

            //check if the entered password matches the one in the DB
            if ($password === $user['userPassword']) {
                //Save user info in session so they're "logged in"
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['isAdmin'] = $user['isAdmin'];
                $_SESSION['username'] = $user['username'];

                //redirect to admin or regular user dashboard
                if ($user['isAdmin'] == 1) {
                    header("Location: admin/dashboard.php");
                    exit;
                } else {
                    header("Location: index.php");
                    exit;
                }
            } else {
                //incorrect password
                $_SESSION['login_error'] = "Invalid password, please try again.";
                header("Location: login.php");
                exit;
            }
        } else {
            //no user found with that email
            $_SESSION['login_error'] = "Invalid Email or Password.";
            header("Location: login.php");
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
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

    .form-control {
      width: 100%;
      padding: 12px;
      margin-bottom: 5px;
      border: 1px solid #e3c6ff;
      border-radius: 14px;
      background-color: #fdf7ff;
    }

    .form-control:focus {
      border-color: #c8b6ff;
      box-shadow: 0 0 0 4px rgba(207, 178, 255, 0.3);
    }

    .btn {
      width: 100%;
      padding: 14px;
      background-color: #923c79;
      border: none;
      border-radius: 14px;
      color: rgb(154, 255, 154);
      font-weight: 600;
      text-transform: uppercase;
    }

    .btn:hover {
      background-color: rgba(154, 255, 154, 0.87);
      color: #923c79;
    }

    .error-msg {
      color: rgb(255, 48, 65);
      font-weight: 500;
      font-size: 0.9rem;
      margin-top: 5px;
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
      transform: scale(1.3);
    }
  </style>
</head>
<body>

  <div class="form-container">
    <!-- login Form -->
    <form action="login.php" method="POST" onsubmit="return validation();">
      <h2 class="text-center">Login</h2>

      <!-- email input -->
      <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" id="email" class="form-control" />
        <p class="error-msg" id="email-error"></p>
      </div>

      <!-- password input -->
      <div class="mb-3">
        <label class="form-label">Password</label>
        <input type="password" name="password" id="password" class="form-control" />
        <p class="error-msg" id="password-error"></p>
      </div>

      <!-- area to show backend error (via JS) -->
      <p class="error-msg" id="login-error"></p>

      <!-- submit button -->
      <button class="btn">Login</button>

      <!-- registration link -->
      <p class="mt-3">Don't have an account? <a class="register-link" href="register.php">Register here</a>.</p>
    </form>
  </div>

  <div class="icons-container">
    <div class="social-icons text-center">
      <a href="#"><i class="fab fa-instagram tag"></i></a>
      <a href="#"><i class="fab fa-facebook-f tag"></i></a>
      <a href="#"><i class="fab fa-tiktok tag"></i></a>
    </div>
  </div>

  <!-- hidden div with PHP error to pass to JS -->
  <?php if (isset($_SESSION['login_error'])): ?>
    <div id="server-error" data-message="<?= htmlspecialchars($_SESSION['login_error']) ?>"></div>
    <?php unset($_SESSION['login_error']); ?>
  <?php endif; ?>

  <!-- Validation -->
  <script>
    function validation() {
      const email = document.getElementById('email');
      const password = document.getElementById('password');
      const emailError = document.getElementById('email-error');
      const passwordError = document.getElementById('password-error');
      const loginError = document.getElementById('login-error');

      // clear any previous errors
      emailError.textContent = '';
      passwordError.textContent = '';
      loginError.textContent = '';

      let isValid = true;

      // check email field
      if (email.value.trim() === '') {
        emailError.textContent = "You must include an email.";
        isValid = false;
      } else if (!email.value.includes('@')) {
        emailError.textContent = "Please enter a valid email.";
        isValid = false;
      }

      // check password field
      if (password.value.trim() === '') {
        passwordError.textContent = "You must include a password.";
        isValid = false;
      } else if (password.value.length < 4) {
        passwordError.textContent = "Password must be at least 4 characters.";
        isValid = false;
      }

      return isValid;
    }

    // show server-side (PHP) error once using JS
    window.addEventListener('DOMContentLoaded', () => {
      const errorDiv = document.getElementById('server-error');
      const loginError = document.getElementById('login-error');

      if (errorDiv && errorDiv.dataset.message) {
        loginError.textContent = errorDiv.dataset.message;
        errorDiv.remove(); // remove the error div so it doesn't persist
      }
    });
  </script>

</body>
</html>
