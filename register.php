<?php
session_start();
require_once 'config.php';

$success = $error = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $terms = isset($_POST['terms']) ? true : false;

    if (empty($username)) {
        $error = "Username is required.";
    } elseif (empty($email)) {
        $error = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } elseif (empty($password)) {
        $error = "Password is required.";
    } elseif (!$terms) {
        $error = "You must agree to the terms and conditions.";
    } else {
        // Check for existing username or email
        $checkSql = "SELECT id FROM users WHERE username = ? OR email = ?";
        if ($stmt = $conn->prepare($checkSql)) {
            $stmt->bind_param("ss", $username, $email);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                $error = "Username or Email already exists.";
            } else {
                $passwordHash = password_hash($password, PASSWORD_DEFAULT);
                $secretkey = bin2hex(random_bytes(16)); // generate a 32-char hex secret key

                $sql = "INSERT INTO users (username, email, password, secretkey, reg_data) VALUES (?, ?, ?, ?, NOW())";
                if ($stmt = $conn->prepare($sql)) {
                    $stmt->bind_param("ssss", $username, $email, $passwordHash, $secretkey);

                    if ($stmt->execute()) {
                        $success = "Registration successful! You can now log in.";
                    } else {
                        $error = "An error occurred. Please try again.";
                    }
                }
            }
            $stmt->close();
        }
    }
}
?>




<!DOCTYPE html>



<html lang="en" class="dark-style layout-wide  customizer-hide" dir="ltr" data-theme="theme-default" data-assets-path="./assets/" data-template="vertical-menu-template-dark">

  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Blue Eye | register</title>

    
    <meta name="description" content="Cc checker, bin checker" />
    <meta name="keywords" content="Cc checker, bin checker">
    <!-- Canonical SEO -->
    <link rel="canonical" href="https://1.envato.market/vuexy_admin">
    
    
    <!-- ? PROD Only: Google Tag Manager (Default ThemeSelection: GTM-5DDHKGP, PixInvent: GTM-5J3LMKC) -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
      new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
      j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
      'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
      })(window,document,'script','dataLayer','GTM-5J3LMKC');</script>
    <!-- End Google Tag Manager -->
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="./assets/img/favicon/favicon.ico" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&ampdisplay=swap" rel="stylesheet">

    <!-- Icons -->
    <link rel="stylesheet" href="./assets/vendor/fonts/fontawesome.css" />
    <link rel="stylesheet" href="./assets/vendor/fonts/tabler-icons.css"/>
    <link rel="stylesheet" href="./assets/vendor/fonts/flag-icons.css" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="./assets/vendor/css/rtl/core-dark.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="./assets/vendor/css/rtl/theme-default-dark.css" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="./assets/css/demo.css" />
    
    <!-- Vendors CSS -->
    <link rel="stylesheet" href="./assets/vendor/libs/node-waves/node-waves.css" />
    <link rel="stylesheet" href="./assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
    <link rel="stylesheet" href="./assets/vendor/libs/typeahead-js/typeahead.css" /> 
    <!-- Vendor -->
<link rel="stylesheet" href="./assets/vendor/libs/@form-validation/umd/styles/index.min.css" />

    <!-- Page CSS -->
    <!-- Page -->
<link rel="stylesheet" href="./assets/vendor/css/pages/page-auth.css">

    <!-- Helpers -->
    <script src="./assets/vendor/js/helpers.js"></script>
    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Template customizer: To hide customizer set displayCustomizer value false in config.js.  -->
    <script src="./assets/vendor/js/template-customizer.js"></script>
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="./assets/js/config.js"></script>
    
</head>

<body>

  
  <!-- ?PROD Only: Google Tag Manager (noscript) (Default ThemeSelection: GTM-5DDHKGP, PixInvent: GTM-5J3LMKC) -->
  <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-5DDHKGP" height="0" width="0" style="display: none; visibility: hidden"></iframe></noscript>
  <!-- End Google Tag Manager (noscript) -->
  
  <!-- Content -->

<div class="container-xxl">
  <div class="authentication-wrapper authentication-basic container-p-y">
    <div class="authentication-inner py-4">

      <!-- Display Success or Error Messages -->
      <?php if (!empty($success)): ?>
        <div class="alert alert-success" role="alert">
          <?php echo $success; ?>
        </div>
      <?php elseif (!empty($error)): ?>
        <div class="alert alert-danger" role="alert">
          <?php echo $error; ?>
        </div>
      <?php endif; ?>

      <!-- Register Card -->
      <div class="card">
        <div class="card-body">
          <div class="app-brand justify-content-center mb-4 mt-2">
            <a href="index.html" class="app-brand-link gap-2">
              <span class="app-brand-text demo text-body fw-bold ms-1">Blue Eye Checker</span>
            </a>
          </div>

          <!-- Registration Form -->
          <form id="formAuthentication" class="mb-3" method="POST">
            <div class="mb-3">
              <label for="username" class="form-label">Username</label>
              <input type="text" class="form-control" id="username" name="username" placeholder="Enter your username" autofocus>
            </div>
            <div class="mb-3">
              <label for="email" class="form-label">Email</label>
              <input type="text" class="form-control" id="email" name="email" placeholder="Enter your email">
            </div>
            <div class="mb-3 form-password-toggle">
              <label class="form-label" for="password">Password</label>
              <div class="input-group input-group-merge">
                <input type="password" id="password" class="form-control" name="password" placeholder="••••••••••••" aria-describedby="password" />
                <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
              </div>
            </div>

            <div class="mb-3">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" id="terms-conditions" name="terms">
                <label class="form-check-label" for="terms-conditions">
                  I agree to <a href="javascript:void(0);">privacy policy & terms</a>
                </label>
              </div>
            </div>
            <button class="btn btn-primary d-grid w-100">Sign up</button>
          </form>

          <p class="text-center">
            <span>Already have an account?</span>
            <a href="login.php"><span>Sign in instead</span></a>
          </p>

          <div class="divider my-4"><div class="divider-text">or</div></div>

          <div class="d-flex justify-content-center">
            <a href="javascript:;" class="btn btn-icon btn-label-facebook me-3">
              <i class="tf-icons fa-brands fa-facebook-f fs-5"></i>
            </a>
            <a href="javascript:;" class="btn btn-icon btn-label-google-plus me-3">
              <i class="tf-icons fa-brands fa-google fs-5"></i>
            </a>
            <a href="javascript:;" class="btn btn-icon btn-label-twitter">
              <i class="tf-icons fa-brands fa-twitter fs-5"></i>
            </a>
          </div>
        </div>
      </div>
      <!-- Register Card -->
    </div>
  </div>
</div>

<!-- / Content -->

  
  <div class="buy-now">
    <a href="https://1.envato.market/vuexy_admin" target="_blank" class="btn btn-danger btn-buy-now">Buy Now</a>
  </div>
  

  

  <!-- Core JS -->
  <!-- build:js assets/vendor/js/core.js -->
  
  <script src="./assets/vendor/libs/jquery/jquery.js"></script>
  <script src="./assets/vendor/libs/popper/popper.js"></script>
  <script src="./assets/vendor/js/bootstrap.js"></script>
  <script src="./assets/vendor/libs/node-waves/node-waves.js"></script>
  <script src="./assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
  <script src="./assets/vendor/libs/hammer/hammer.js"></script>
  <script src="./assets/vendor/libs/i18n/i18n.js"></script>
  <script src="./assets/vendor/libs/typeahead-js/typeahead.js"></script>
   <script src="./assets/vendor/js/menu.js"></script>
  
  <!-- endbuild -->

  <!-- Vendors JS -->
  <script src="./assets/vendor/libs/@form-validation/umd/bundle/popular.min.js"></script>
<script src="./assets/vendor/libs/@form-validation/umd/plugin-bootstrap5/index.min.js"></script>
<script src="./assets/vendor/libs/@form-validation/umd/plugin-auto-focus/index.min.js"></script>

  <!-- Main JS -->
  <script src="./assets/js/main.js"></script>
  

  <!-- Page JS -->
  <script src="./assets/js/pages-auth.js"></script>
  
</body>

</html>

<!-- beautify ignore:end -->

