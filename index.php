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
?>
<!DOCTYPE html>



<html lang="en" class="dark-style layout-navbar-fixed layout-menu-fixed layout-compact " dir="ltr" data-theme="theme-default" data-assets-path="./assets/" data-template="vertical-menu-template-dark">

  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Dashboard - CRM | Vuexy - Bootstrap Admin Template</title>

    
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
    <link rel="stylesheet" href="./assets/vendor/libs/apex-charts/apex-charts.css" />

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
            
              <div class="col-md-6 col-xl-4 mb-4">
    <div class="card h-100">
      <div class="card-header d-flex justify-content-between">
        <div class="card-title mb-0">
          <h5 class="mb-0">Important Notice:</h5>
          <h6 class="text-danger"> 1) Do not use Gen Cards</h6>
          <h6 class="text-danger"> 2) Use dumped cards Or Scrape cards</h6>
          <h6 class="text-danger"> 3)evry check yiu will loss 1 credit </h6>
        </div>
        
      </div>
      <div class="card-body">
        <div id="salesLastMonth"></div>
      </div>
    </div>
  </div>
<div class="row">

  <!-- Total Profit -->
  <div class="col-xl-2 col-md-4 col-6 mb-4">
    <div class="card">
      <div class="card-body">
        <div class="badge p-2 bg-label-danger mb-2 rounded"><i class="ti ti-currency-dollar ti-md"></i></div>
        <h5 class="card-title mb-1 pt-2">Balance</h5>
        <small class="text-muted">Credits</small>
        <p class=" text-success mb-2 mt-1"><?= $credit ?></p>
        <div class="pt-1">
        </div>
      </div>
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
  <script src="./assets/vendor/libs/apex-charts/apexcharts.js"></script>

  <!-- Main JS -->
  <script src="./assets/js/main.js"></script>
  

  <!-- Page JS -->
  <script src="./assets/js/dashboards-crm.js"></script>
  
</body>

</html>

<!-- beautify ignore:end -->
