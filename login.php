<?php
include 'includes/header.php';
include 'database/db.php'; 
$base_path = ''; 

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
                    $_SESSION['logged-in'] = true;
                    header("Location: admin/dashboard.php");
                    exit;
                } else {
                      $_SESSION['logged-in'] = true;

                      // check if they were trying to add an item to the cart before logging in
                      if (isset($_SESSION['pending_add'])) {
                          $product_id = (int)$_SESSION['pending_add'];
                          unset($_SESSION['pending_add']); // clear it from session
                          header("Location: add-to-cart.php?product_id=$product_id");
                          exit;
                      }

                      // otherwise just go to the homepage
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

<body class="login-body">
        
  <div class="login-form-container">

    <!-- login Form -->
    <form action="login.php" method="POST" onsubmit="return validation();">
      <h2 class="text-center login-title">Login</h2>

      <!-- email input -->
      <div class="mb-3">
        <label class="form-label login-label">Email</label>
        <input type="email" name="email" id="email" class="form-control login-input" />
        <p class="login-error-msg" id="email-error"></p>
      </div>

      <!-- password input -->
      <div class="mb-3">
        <label class="form-label login-label">Password</label>
        <input type="password" name="password" id="password" class="form-control login-input" />
        <p class="login-error-msg" id="password-error"></p>
      </div>

      <!-- area to show backend error (via JS) -->
      <p class="login-error-msg" id="login-error"></p>

      <!-- submit button -->
      <button class="btn login-btn">Login</button>

      <!-- registration link -->
      <p class="mt-3">Don't have an account? <a class="login-register-link" href="register.php">Register here</a>.</p>
    </form>
  </div>

  <div class="login-icons-container">
    <div class="login-social-icons text-center">
      <a href="#"><i class="fab fa-instagram login-icon tag"></i></a>
      <a href="#"><i class="fab fa-facebook-f login-icon tag"></i></a>
      <a href="#"><i class="fab fa-tiktok login-icon tag"></i></a>
    </div>
  </div>

      <!--flash telling user they need to login to add to cart: -->
    <?php if (isset($_SESSION['flash_add_login'])): ?>
      <div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 9999;">
        <div class="toast login-toast align-items-center show" role="alert" aria-live="assertive" aria-atomic="true">
          <div class="d-flex">
            <div class="toast-body">
              <?= $_SESSION['flash_add_login']; ?>
            </div>
            <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
          </div>
        </div>
      </div>
      <?php unset($_SESSION['flash_add_login']); ?>
    <?php endif; ?>


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

<?php include 'includes/footer.php'; ?>