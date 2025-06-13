<?php

include 'includes/header.php';
include 'database/db.php';


// check if the user is logged in by checking if we have their user id saved in the session
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// get the user id from the session
$user_id = $_SESSION['user_id'];

// set up empty message variables (to show alerts to the user)
$message = '';
$message_type = 'info';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // always check for presence of checkbox, unchecked = not in $_POST, so treat as 0
    $privacyAccepted = !empty($_POST['privacy']) ? 1 : 0;

    // make sure we update regardless of current state
    $updateSQL = "UPDATE Users SET privacyAccepted = $privacyAccepted WHERE user_id = $user_id";

    if (mysqli_query($dbc, $updateSQL)) {
        if ($privacyAccepted) {
            $message = "you have accepted the privacy terms. you may now make purchases.";
            $message_type = 'success';
        } else {
            $message = "you have opted out of the privacy terms. you will not be able to make purchases.";
            $message_type = 'warning';
        }
    } else {
        $message = "error updating your preference. please try again.";
        $message_type = 'danger';
    }
}


// getting the current privacy setting from the database, so we can show whether the box is checked when the page loads
$result = mysqli_query($dbc, "SELECT privacyAccepted FROM Users WHERE user_id = $user_id");
$user = mysqli_fetch_assoc($result);
$currentPrivacy = $user['privacyAccepted'] ?? 0; // fallback to 0 if not set
?>

<body class="settings-body">

  <!-- main container with spacing -->
  <div class="container mt-5">
    <h2 class="text-center">privacy settings</h2>

    <!-- show a message if we have one -->
    <?php if (!empty($message)): ?>
      <div class="alert alert-<?= $message_type ?> text-center">
        <?= $message ?>
      </div>
    <?php endif; ?>

    <!-- form to let the user update their privacy preference -->
    <form method="POST" action="privacy-settings.php" class="mt-4">

      <!-- checkbox to accept or decline privacy terms -->
      <div class="form-check mb-3">
        <!-- this will be checked if the user already accepted the terms -->
        <input class="form-check-input" type="checkbox" id="privacy" name="privacy"
          <?= $currentPrivacy ? 'checked' : '' ?>>
        <label class="form-check-label" for="privacy">
          i accept the <a href="privacy.php" target="_blank">privacy terms</a>.
        </label>
      </div>

      <!-- submit button -->
      <button type="submit" class="privacy-btn">Save Preferences</button>
    </form>
  </div>
</body>

<?php include 'includes/footer.php'; ?>
