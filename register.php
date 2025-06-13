<?php
include 'includes/header.php';
include 'database/db.php';
$base_path = '';

$message = '';
$message_type = 'info';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (
        empty($_POST["email"]) ||
        empty($_POST["username"]) ||
        empty($_POST["password"]) ||
        empty($_POST["confirm_password"])
    ) {
        $message = "please fill in all required fields.";
        $message_type = 'danger';
    } else {
        // Sanitize inputs
        $registerEmail = mysqli_real_escape_string($dbc, $_POST["email"]);
        $registerUser = mysqli_real_escape_string($dbc, $_POST["username"]);
        $registerPW = mysqli_real_escape_string($dbc, $_POST["password"]);
        $registerConfirmPW = mysqli_real_escape_string($dbc, $_POST["confirm_password"]);
        $privacyAccepted = isset($_POST["privacy"]) ? 1 : 0;

        if ($registerPW !== $registerConfirmPW) {
            $message = "passwords do not match.";
            $message_type = 'danger';
        } else {
            // Check for existing username
            $name_result = mysqli_query($dbc, "SELECT username FROM users WHERE username = '$registerUser'");
            if ($name_result && mysqli_num_rows($name_result) > 0) {
                $message = "username already exists. please try another.";
                $message_type = 'danger';
            } else {
                // Check for existing email
                $email_result = mysqli_query($dbc, "SELECT userEmail FROM users WHERE userEmail = '$registerEmail'");
                if ($email_result && mysqli_num_rows($email_result) > 0) {
                    $message = "email already exists. please try another.";
                    $message_type = 'danger';
                } else {
                    // Insert user into the database
                    $sql = "INSERT INTO users (username, userEmail, isAdmin, userPassword, privacyAccepted)
                            VALUES ('$registerUser', '$registerEmail', 0, '$registerPW', $privacyAccepted)";
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

<!-- Registration Form UI -->
<body class="login-body">
  <div class="login-form-container">
    <!-- Show success or error message -->
    <?php if (!empty($message)): ?>
      <div class="alert alert-<?= $message_type ?> text-center" role="alert">
        <?= $message ?>
      </div>
    <?php endif; ?>

    <!-- Registration Form -->
    <form action="register.php" method="POST" onsubmit="return validation();">
      <h2 class="text-center login-title">Register</h2>

      <!-- Email Field -->
      <div class="mb-3">
        <label class="form-label login-label" for="email">Email</label>
        <input type="email" name="email" id="email" class="form-control login-input" />
        <p class="login-error-msg" id="email-error"></p>
      </div>

      <!-- Username Field -->
      <div class="mb-3">
        <label class="form-label login-label" for="username">Username</label>
        <input type="text" name="username" id="username" class="form-control login-input" />
        <p class="login-error-msg" id="username-error"></p>
      </div>

      <!-- Password Field -->
      <div class="mb-3">
        <label class="form-label login-label" for="password">Password</label>
        <input type="password" name="password" id="password" class="form-control login-input" />
        <p class="login-error-msg" id="password-error"></p>
      </div>

      <!-- Confirm Password Field -->
      <div class="mb-3">
        <label class="form-label login-label" for="confirm_password">Confirm Password</label>
        <input type="password" name="confirm_password" id="confirm_password" class="form-control login-input" />
        <p class="login-error-msg" id="confirm-password-error"></p>
      </div>

      <!-- Privacy Terms Checkbox -->
      <div class="mb-3 form-check">
        <input type="checkbox" class="form-check-input" id="privacy" name="privacy">
        <label class="form-check-label" for="privacy">
          I accept the <a href="privacy.php" target="_blank">privacy terms</a>.
        </label>
      </div>

      <!-- Placeholder for JS Error -->
      <p class="login-error-msg" id="register-error"></p>
      <button class="btn login-btn">Register</button>

      <!-- Login Link -->
      <p class="mt-3">Already have an account? <a class="login-register-link" href="login.php">login here</a>.</p>
    </form>
  </div>

  <!-- Social Media Icons -->
  <div class="login-icons-container">
    <div class="login-social-icons text-center">
      <a href="#"><i class="fab fa-instagram login-icon tag"></i></a>
      <a href="#"><i class="fab fa-facebook-f login-icon tag"></i></a>
      <a href="#"><i class="fab fa-tiktok login-icon tag"></i></a>
    </div>
  </div>

  <?php if (isset($_SESSION['register_error'])): ?>
    <div id="server-error" data-message="<?= htmlspecialchars($_SESSION['register_error']) ?>"></div>
    <?php unset($_SESSION['register_error']); ?>
  <?php endif; ?>

  <!-- Front-End Validation Script -->
  <script>
    function validation() {
      const email = document.getElementById('email');
      const username = document.getElementById('username');
      const password = document.getElementById('password');
      const confirmPassword = document.getElementById('confirm_password');
      const privacy = document.getElementById('privacy');

      // Error message elements
      const emailError = document.getElementById('email-error');
      const usernameError = document.getElementById('username-error');
      const passwordError = document.getElementById('password-error');
      const confirmPasswordError = document.getElementById('confirm-password-error');
      const registerError = document.getElementById('register-error');

      // Clear previous errors
      emailError.textContent = '';
      usernameError.textContent = '';
      passwordError.textContent = '';
      confirmPasswordError.textContent = '';
      registerError.textContent = '';
      let isValid = true;

      // Email validation
      if (email.value.trim() === '' || !email.value.includes('@')) {
        emailError.textContent = "Please enter in a valid email.";
        isValid = false;
      }

      // Username validation
      if (username.value.trim() === '') {
        usernameError.textContent = "Username is required.";
        isValid = false;
      }

      // Password validation
      if (password.value.trim() === '') {
        passwordError.textContent = "You must include a password.";
        isValid = false;
      } else if (password.value.length < 4) {
        passwordError.textContent = "Password must be at least 4 characters.";
        isValid = false;
      }

      // Confirm password match
      if (password.value !== confirmPassword.value) {
        confirmPasswordError.textContent = "Passwords do not match.";
        isValid = false;
      }

      return isValid;
    }

    // Handle PHP error message passed via session
    window.addEventListener('DOMContentLoaded', () => {
      const errorDiv = document.getElementById('server-error');
      const registerError = document.getElementById('register-error');
      if (errorDiv && errorDiv.dataset.message) {
        registerError.textContent = errorDiv.dataset.message;
        errorDiv.remove();
      }
    });
  </script>
</body>
</html>

<?php include 'includes/footer.php'; ?>
