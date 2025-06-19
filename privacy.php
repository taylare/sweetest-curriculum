<?php include 'includes/header.php'; ?>

<body class="privacy-body">

  <div class="container mt-5 mb-5">
    <h2 class="text-center mb-4">Privacy Terms:</h2>

    <div class="card shadow-sm p-4 rounded-3">
      

      <h5 class="mt-4">Personal information</h5>
      <p>
       By using this website, creating an account, or completing a purchase, you agree to our collection, use, and storage of your personal information in agreement with this Privacy terms of agreement.
       We may collect information such as your name, email address, mailing and billing addresses, payment details, order history, and preferences.
       This data is used to process transactions, deliver products and improve our services.
      </p>

      <h5 class="mt-4">Information Usage</h5>
      <p>
      Your personal information is treated as confidential.
      It will not be sold or shared with third parties for marketing purposes;
      however, we may share your data with trusted service providers
      (such as payment processors, shipping companies, or technical support providers)
      solely for the purpose of fulfilling your orders and maintaining the site.
      All partners are required to protect your information in compliance with applicable data protection laws.
      </p>

     

      <h5 class="mt-4">User rights</h5>
      <p>
       You have the right to access, update, or delete your personal data at any time by contacting us. 
       You may also withdraw your consent at any point throughout your usage on the site.
       We take reasonable administrative and technical actions to secure your data against unauthorized access or misuse.
      </p>

      <h5 class="mt-4">Policy updates</h5>
      <p>
       By checking the agreement box or proceeding with a purchase, you acknowledge that you have read, understood, and accepted these terms.
       We reserve the right to update this Privacy Policy at any time. 
       Continued use of our site after changes are posted account for your acceptance of the updated policy. 
      </p>
  <?php if (isset($_SESSION['user_id'])):?>
      <div class="text-center mt-4">
        <a href="privacy-settings.php" class="privacy-btn">View Your Privacy Settings</a>
      </div>
  <?php endif; ?>
    </div>
  </div>

</body>

<?php include 'includes/footer.php'; ?>
