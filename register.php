<?php
include 'includes/header.php';
include 'database/db.php';
$base_path = '';


// create an empty message and message type (for styling)
$message = '';
$message_type = 'info'; // bootstrap alert-info by default

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // check if any field is missing
    if (empty($_POST["email"]) || empty($_POST["username"]) || empty($_POST["password"]) || empty($_POST["confirm_password"])) {
        $message = "please fill in all required fields.";
        $message_type = 'danger';
    } else {
        // get user input
        $registerEmail = $_POST["email"];
        $registerUser = $_POST["username"];
        $registerPW = $_POST["password"];
        $registerConfirmPW = $_POST["confirm_password"];

        // sanitise for database
        $registerEmail = mysqli_real_escape_string($dbc, $registerEmail);
        $registerUser = mysqli_real_escape_string($dbc, $registerUser);
        $registerPW = mysqli_real_escape_string($dbc, $registerPW);
        $registerConfirmPW = mysqli_real_escape_string($dbc, $registerConfirmPW);

        // check if passwords match
        if ($registerPW !== $registerConfirmPW) {
            $message = "passwords do not match.";
            $message_type = 'danger';
        } else {
            // check if username exists
            $name_result = mysqli_query($dbc, "SELECT username FROM users WHERE username = '$registerUser'");
            if ($name_result && mysqli_num_rows($name_result) > 0) {
                $message = "username already exists. please try another.";
                $message_type = 'danger';
            } else {
                // check if email exists
                $email_result = mysqli_query($dbc, "SELECT userEmail FROM users WHERE userEmail = '$registerEmail'");
                if ($email_result && mysqli_num_rows($email_result) > 0) {
                    $message = "email already exists. please try another.";
                    $message_type = 'danger';
                } else {

                    // insert the user all fields are valid:
                    $sql = "INSERT INTO users (username, userEmail, isAdmin, userPassword)
                                    VALUES ('$registerUser', '$registerEmail', 0, '$registerPW')";
                    if (mysqli_query($dbc, $sql)) {
                        $safeUser = htmlspecialchars($registerUser); 
                        $message = "welcome, <strong>$safeUser</strong>! registration successful. <a href='login.php'>log in here</a>.";
                        $message_type = 'success';
                    } else {
                        $message = "database error: " . htmlspecialchars(mysqli_error($dbc));
                        $message_type = 'danger';
                    }
                }
            }
        }
    }
}
?>


<body class="login-body">

  <div class="login-form-container">
     <?php if (!empty($message)): ?>
            <div class="alert alert-<?= $message_type ?> text-center" role="alert">
                <?= $message ?>
            </div>
        <?php endif; ?>
    <!-- login Form -->
    <form action="register.php" method="POST" onsubmit="return validation();">
      <h2 class="text-center login-title">Register</h2>

      <!-- email input -->
      <div class="mb-3">
        <label class="form-label login-label" for="email">Email</label>
        <input type="email" name="email" id="email" class="form-control login-input" />
        <p class="login-error-msg" id="email-error"></p>
      </div>

      <!-- username input -->
      <div class="mb-3">
        <label class="form-label login-label" for="username">username</label>
        <input type="text" name="username" id="username" class="form-control login-input" />
        <p class="username-error-msg" id="email-error"></p>
      </div>


      <!-- password input -->
      <div class="mb-3">
        <label class="form-label login-label" for="password">Password</label>
        <input type="password" name="password" id="password" class="form-control login-input" />
        <p class="login-error-msg" id="password-error"></p>
      </div>

      <!-- Confirm password input -->
      <div class="mb-3">
        <label class="form-label login-label" for="confirm_password">Confirm Password</label>
        <input type="password" name="confirm_password" id="confirm_password" class="form-control login-input" />
        <p class="login-error-msg" id="confirm-password-error"></p>
      </div>

      <!-- area to show backend error (via js) -->
      <p class="login-error-msg" id="login-error"></p>

      <!-- submit button -->
      <button class="btn login-btn">Register</button>

      <!-- registration link -->
      <p class="mt-3">Already have an account? <a class="login-register-link" href="login.php">login here</a>.</p>

    </form>
  </div>

  <div class="login-icons-container">
    <div class="login-social-icons text-center">
      <a href="#"><i class="fab fa-instagram login-icon tag"></i></a>
      <a href="#"><i class="fab fa-facebook-f login-icon tag"></i></a>
      <a href="#"><i class="fab fa-tiktok login-icon tag"></i></a>
    </div>
  </div>

  <!-- hidden div with PHP error to pass to JS -->
  <?php if (isset($_SESSION['register_error'])): ?>
    <div id="server-error" data-message="<?= htmlspecialchars($_SESSION['register_error']) ?>"></div>
    <?php unset($_SESSION['register_error']); ?>
  <?php endif; ?>

  <!-- Validation -->
  <script>
    function validation() {
      const email = document.getElementById('email');
      const username = document.getElementById('username');
      const password = document.getElementById('password');
      const confirmPassword = document.getElementById('confirm_Password');


      const emailError = document.getElementById('email-error');
       const usernameError = document.getElementById('username-error');
      const passwordError = document.getElementById('password-error');
      const confirmPasswordError = document.getElementById('confirm-password-error');
      const registerError = document.getElementById('register-error');

      // clear any previous errors
       emailError.textContent = '';
       usernameError.textContent = '';
       passwordError.textContent = '';
       confirmPasswordError.textContent = '';
       registerError.textContent = '';
      let isValid = true;

      // check email field
      if (email.value.trim() === '' || !email.value.includes('@')) {
        emailError.textContent = "Please enter in a valid email.";
        isValid = false;
      }
      //check username field
       if (username.value.trim() === '') {
        usernameError.textContent = "Username is required.";
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

      if (password.value !== confirmPassword.value) {
      confirmPasswordError.textContent = "Passwords do not match.";
      isValid = false;
  }

      return isValid;
    }

    // show server-side (PHP) error once using JS
    window.addEventListener('DOMContentLoaded', () => {
      const errorDiv = document.getElementById('server-error');
      const registerError = document.getElementById('register-error');

      if (errorDiv && errorDiv.dataset.message) {
        registerError.textContent = errorDiv.dataset.message;
        errorDiv.remove(); // remove the error div so it doesn't persist
      }
    });
  </script>

</body>
</html>

<?php include 'includes/footer.php'; ?>