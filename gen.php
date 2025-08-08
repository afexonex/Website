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
            
            


<div class="col-md-6">
    <div class="card mb-4">
        <h5 class="card-header">Credit Card Generator</h5>
        <div class="card-body">
            <form method="POST">
                <div class="mb-3">
                    <label for="bin" class="form-label">BIN (e.g., 411111xxxxxxxxxx)</label>
                    <input id="bin" name="bin" class="form-control" type="text" placeholder="Enter BIN" required />
                </div>
                <div class="mb-3">
                    <label for="mm" class="form-label">Month (MM, optional)</label>
                    <input id="mm" name="mm" class="form-control" type="text" placeholder="Leave empty for random" />
                </div>
                <div class="mb-3">
                    <label for="yy" class="form-label">Year (YYYY, optional)</label>
                    <input id="yy" name="yy" class="form-control" type="text" placeholder="Leave empty for random" />
                </div>
                <div class="mb-3">
                    <label for="cvv" class="form-label">CVV (optional)</label>
                    <input id="cvv" name="cvv" class="form-control" type="text" placeholder="Leave empty for random" />
                </div>
                <div class="mb-3">
                    <label for="amount" class="form-label">Amount (Number of Cards)</label>
                    <input id="amount" name="amount" class="form-control" type="number" min="1" placeholder="Default is 10" />
                </div>
                <button type="submit" class="btn btn-primary w-100">Generate</button>
            </form>

            <?php
            class CardGenerator {
                private $bin;
                private $mm;
                private $yy;
                private $cvv;
                private $amount;

                public function __construct($bin, $mm = '', $yy = '', $cvv = '', $amount = 10) {
                    $this->bin = $bin;
                    $this->mm = $mm ?: str_pad(mt_rand(1, 12), 2, '0', STR_PAD_LEFT);
                    $this->yy = $yy ?: mt_rand(date('y'), date('y') + 10);
                    $this->cvv = $cvv;
                    $this->amount = max(1, intval($amount));
                }

                public function generateCards(): array {
                    $cards = [];
                    for ($i = 0; $i < $this->amount; $i++) {
                        $cards[] = $this->generateCard();
                    }
                    return $cards;
                }

                private function generateCard(): string {
                    $number = $this->generateCardNumber();
                    $cvv = $this->cvv ?: $this->generateCVV();
                    return "$number|$this->mm|$this->yy|$cvv";
                }

                private function generateCardNumber(): string {
                    $bin = $this->bin;
                    $length = substr($bin, 0, 2) === '34' || substr($bin, 0, 2) === '37' ? 15 : 16;

                    while (strlen($bin) < $length - 1) {
                        $bin .= mt_rand(0, 9);
                    }

                    return $bin . $this->calculateLuhn($bin);
                }

                private function calculateLuhn($number): int {
                    $sum = 0;
                    $reverse = strrev($number);
                    for ($i = 0; $i < strlen($reverse); $i++) {
                        $digit = intval($reverse[$i]);
                        if ($i % 2 === 0) {
                            $digit *= 2;
                            if ($digit > 9) $digit -= 9;
                        }
                        $sum += $digit;
                    }
                    return (10 - ($sum % 10)) % 10;
                }

                private function generateCVV(): string {
                    $cvvLength = substr($this->bin, 0, 2) === '34' || substr($this->bin, 0, 2) === '37' ? 4 : 3;
                    return str_pad(mt_rand(0, pow(10, $cvvLength) - 1), $cvvLength, '0', STR_PAD_LEFT);
                }
            }

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $bin = $_POST['bin'];
                $mm = $_POST['mm'] ?? '';
                $yy = $_POST['yy'] ?? '';
                $cvv = $_POST['cvv'] ?? '';
                $amount = $_POST['amount'] ?? 10;

                $generator = new CardGenerator($bin, $mm, $yy, $cvv, $amount);
                $cards = $generator->generateCards();

                echo '<div class="mt-4">';
                echo '<textarea id="cards" rows="8" class="form-control" readonly>' . implode("\n", $cards) . '</textarea>';
                echo '<button class="btn  btn-success mt-3 w-100 copy-btn" onclick="copyToClipboard()">Copy to Clipboard</button>';
                echo '</div>';
            }
            ?>

            <script>
                function copyToClipboard() {
                    const textarea = document.getElementById('cards');
                    textarea.select();
                    document.execCommand('copy');
                    alert('Cards copied to clipboard!');
                }
            </script>
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
  // Copy to Clipboard Functionality
  document.querySelectorAll('.copy-btn').forEach(button => {
    button.addEventListener('click', () => {
      const card = button.getAttribute('data-card');
      navigator.clipboard.writeText(card).then(() => {
        alert('Card copied to clipboard: ' + card);
      }).catch(err => {
        alert('Failed to copy card: ' + err);
      });
    });
  });
</script>
</body>



</html>

<!-- beautify ignore:end -->

