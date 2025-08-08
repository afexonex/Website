<?php

include('config.php');
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT username, credit, total_cc, usertype FROM users WHERE id = '$user_id' LIMIT 1";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    $user = mysqli_fetch_assoc($result);
    $username = $user['username'];
    $credit = $user['credit'];
    $total_cc = $user['total_cc'];
    $usertype = $user['usertype'];
} else {
    session_destroy();
    header("Location: login.php");
    exit();
}

function redeemCode($code) {
    global $conn, $username, $user_id;

    // Check if redeem code exists and is active
    $stmt = $conn->prepare("SELECT id, credits, status FROM redeem_codes WHERE code = ?");
    $stmt->bind_param("s", $code);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        return ["status" => "error", "message" => "Invalid redeem code."];
    }

    $redeemData = $result->fetch_assoc();

    // Check if code is already redeemed
    if ($redeemData['status'] != 'active') {
        return ["status" => "error", "message" => "This code has already been redeemed."];
    }

    $addedCredits = $redeemData['credits'];

    // Begin transaction to update user's credits and redeem code status
    $conn->begin_transaction();

    try {
        // Update user's credits
        $updateUserCredits = $conn->prepare("UPDATE users SET credit = credit + ? WHERE username = ?");
        $updateUserCredits->bind_param("is", $addedCredits, $username);
        $updateUserCredits->execute();

        // Update redeem code status
        $updateRedeemCode = $conn->prepare("UPDATE redeem_codes SET status = 'redeemed', redeemed_by = ?, redeemed_at = NOW() WHERE id = ?");
        $updateRedeemCode->bind_param("si", $user_id, $redeemData['id']);
        $updateRedeemCode->execute();

        $conn->commit();

        return ["status" => "success", "message" => "Redeem code applied successfully! {$addedCredits} credits have been added to your account."];
    } catch (Exception $e) {
        $conn->rollback();
        return ["status" => "error", "message" => "An error occurred. Please try again."];
    }
}

// Handle redeem code submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $code = $_POST['code'];
    $response = redeemCode($code);
    echo json_encode($response);
    exit();
}
?>



<!DOCTYPE html>



<html lang="en" class="dark-style layout-navbar-fixed layout-menu-fixed layout-compact layout-menu-collapsed " dir="ltr" data-theme="theme-default" data-assets-path="./assets/" data-template="vertical-menu-template-dark">

  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Collapsed menu - Layouts | Vuexy - Bootstrap Admin Template</title>

    
    <meta name="description" content="Start your development with a Dashboard for Bootstrap 5" />
    <meta name="keywords" content="dashboard, bootstrap 5 dashboard, bootstrap 5 design, bootstrap 5">
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
    

    <!-- Page CSS -->
    

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
  
  <!-- Layout wrapper -->
<div class="layout-wrapper layout-content-navbar  ">
  <div class="layout-container">

    
    




<!-- Menu -->

<?php include './headers/sidebar.php'; ?>
<!-- / Menu -->

    

    <!-- Layout container -->
    <div class="layout-page">
      
      



<!-- Navbar -->

<?php include './headers/nav.php'; ?>

<!-- / Navbar -->

      

      <!-- Content wrapper -->
      <div class="content-wrapper">

        <!-- Content -->
        
          <div class="container-xxl flex-grow-1 container-p-y">
            
            


<!-- Layout Demo -->
<div class="layout-demo-wrapper">
  <div class="layout-demo-placeholder">
  </div>
  <div class="layout-demo-info">
    <div class="alert alert-danger mt-4" role="alert">
      <span class="fw-medium">do not  share your redeem codes </div>
  </div>
</div>

<div class="col-md-6">
  <div class="card mb-4">
    <h5 class="card-header">Redeem Code</h5>
    <div class="card-body">
      <!-- Message display area -->
      <div id="messageArea"></div>
      
      <!-- Redeem code input field -->
      <div class="form-floating mb-3">
        <input type="text" class="form-control" id="redeemCodeInput" placeholder="Enter redeem code" />
        <label for="redeemCodeInput">Enter redeem code</label>
      </div>
      
      <!-- Redeem button -->
      <button type="button" class="btn btn-primary" id="redeemButton">Redeem</button>
    </div>
  </div>
</div>


          <!-- / Content -->

          
          

<!-- Footer -->
<?php include './headers/footer.php'; ?>
<!-- / Footer -->

          

  

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
  
  

  <!-- Main JS -->
  <script src="./assets/js/main.js"></script>
  

  <!-- Page JS -->
  
  
<!-- JavaScript for AJAX request -->
<script>
document.getElementById('redeemButton').addEventListener('click', function() {
  const code = document.getElementById('redeemCodeInput').value;
  const messageArea = document.getElementById('messageArea');

  // Clear any previous messages
  messageArea.innerHTML = '';

  // AJAX request to redeem code
  fetch('redeem.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded'
    },
    body: `code=${code}`
  })
  .then(response => response.json())
  .then(data => {
    // Display success or error message in alert format
    if (data.status === 'success') {
      messageArea.innerHTML = `
        <div class="alert alert-success mt-4" role="alert">
          <span class="fw-medium">${data.message}</span>
        </div>`;
    } else {
      messageArea.innerHTML = `
        <div class="alert alert-danger mt-4" role="alert">
          <span class="fw-medium">${data.message}</span>
        </div>`;
    }
  })
  .catch(error => {
    messageArea.innerHTML = `
      <div class="alert alert-danger mt-4" role="alert">
        <span class="fw-medium">An error occurred. Please try again.</span>
      </div>`;
  });
});
</script>
</body>

</html>

<!-- beautify ignore:end -->

