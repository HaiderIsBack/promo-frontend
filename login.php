<?php

require_once "./global.php";

if (verify_user_token()) {
  header('Location: '. SITE_URL . '/index.php');
  die();
}

$error_message = '';

if (isset($_POST['login-submit'])) {
  $username = $_POST['username'];
  $password = $_POST['password'];

  if (!$username && !$password) {
    echo "Fields are empty!";
  } else {
    $url = 'https://practice-php.page.gd/wp-json/jwt-auth/v1/token';
    $data = [ "username" => $username, "password" => $password ];

    $options = array(
        'http' => array(
            'header' => "Content-type: application/json\r\n",
            'method' => 'POST',
            'content' => json_encode($data),
            'ignore_errors' => true
        )
    );

    try {
      $context = stream_context_create($options);
      $result = file_get_contents($url, false, $context);

      $result = json_decode($result, true);

      if ($result && isset($result['token'])) {
          $_SESSION['token'] = $result['token'];
          $_SESSION['user_email'] = $result['user_email'];

          $_SESSION['toast'] = json_encode(['type' => 'success', 'message' => 'You have successfully Logged In!']);

          $redirect = isset($_POST['redirect-url']) ? urldecode($_POST['redirect-url']) : null;
          
          if (! is_null($redirect)) {
            if (strpos($redirect, SITE_URL) === 0 || str_starts_with($redirect, '/')) {
              header("Location: " . $redirect);
            } else {
              header("Location: " . SITE_URL . "/index.php");
            }
          } else {
            header("Location: " . SITE_URL . "/index.php");
          }
          die();
      } else {
        $error_message = $result['message'];
      }
    } catch (Exception $e) {
      echo "Error: " . $e->getMessage();
    }
  }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Login - NiceShop Bootstrap Template</title>
  <meta name="description" content="">
  <meta name="keywords" content="">

  <!-- Favicons -->
  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="assets/vendor/drift-zoom/drift-basic.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

  <!-- Main CSS File -->
  <link href="assets/css/main.css" rel="stylesheet">

  <!-- Vendor JS Files -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

  <!-- =======================================================
  * Template Name: NiceShop
  * Template URL: https://bootstrapmade.com/niceshop-bootstrap-ecommerce-template/
  * Updated: Aug 26 2025 with Bootstrap v5.3.7
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<body class="login-page">

  <?php require_once "./components/header.php"; ?>

  <main class="main">
    <?php
    if (isset($_SESSION['toast'])) {
      $toastMessage = json_decode($_SESSION['toast'], true)['message'];
      echo "<script>
        $(document).ready(function() {
            toastr.options.positionClass = 'toast-bottom-right';
            toastr.success('$toastMessage');
        });
      </script>";
      unset($_SESSION['toast']);
    }
    ?>

    <!-- Page Title -->
    <div class="page-title light-background">
      <div class="container d-lg-flex justify-content-between align-items-center">
        <h1 class="mb-2 mb-lg-0">Login</h1>
        <nav class="breadcrumbs">
          <ol>
            <li><a href="index.php">Home</a></li>
            <li class="current">Login</li>
          </ol>
        </nav>
      </div>
    </div><!-- End Page Title -->

    <!-- Login Section -->
    <section id="login" class="login section">

      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="row justify-content-center">
          <div class="col-lg-8 col-md-10">
            <div class="auth-container" data-aos="fade-in" data-aos-delay="200">

              <!-- Login Form -->
              <div class="auth-form login-form active">
                <div class="form-header">
                  <h3>Welcome Back</h3>
                  <p>Sign in to your account</p>
                </div>

                <form class="auth-form-content" method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                  <div class="input-group mb-3">
                    <span class="input-icon">
                      <i class="bi bi-envelope"></i>
                    </span>
                    <input type="text" name="username" class="form-control" placeholder="Username" required="" autocomplete="username">
                  </div>

                  <div class="input-group mb-3">
                    <span class="input-icon">
                      <i class="bi bi-lock"></i>
                    </span>
                    <input type="password" name="password" class="form-control" placeholder="Password" required="" autocomplete="current-password">
                    <span class="password-toggle">
                      <i class="bi bi-eye"></i>
                    </span>
                  </div>

                  <div class="form-options mb-4">
                    <div class="remember-me">
                      <input type="checkbox" id="rememberLogin">
                      <label for="rememberLogin">Remember me</label>
                    </div>
                    <a href="#" class="forgot-password">Forgot password?</a>
                  </div>

                  <?php
                  if (isset($_GET['redirect_url'])) {
                    echo "<input type='hidden' name='redirect-url' value='". urlencode($_GET['redirect_url']) ."' />";
                  }
                  ?>

                  <?php
                  if (!empty($error_message)) {
                    $error_message = preg_replace('/<a\b[^>]*>(.*?)<\/a>/i', '', $error_message);
                    echo "<p style='color: crimson;'>$error_message</p>";
                  }
                  ?>

                  <button type="submit" name="login-submit" class="auth-btn primary-btn mb-3">
                    Sign In
                    <i class="bi bi-arrow-right"></i>
                  </button>

                  <div class="divider">
                    <span>or</span>
                  </div>

                  <button type="button" class="auth-btn social-btn">
                    <i class="bi bi-google"></i>
                    Continue with Google
                  </button>

                  <div class="switch-form">
                    <span>Don't have an account?</span>
                    <a href="register.php" class="switch-btn" data-target="register">Create account</a>
                  </div>
                </form>
              </div>

              <!-- Register Form -->
              <div class="auth-form register-form">
                <div class="form-header">
                  <h3>Create Account</h3>
                  <p>Join us today and get started</p>
                </div>

                <form class="auth-form-content">
                  <div class="name-row">
                    <div class="input-group">
                      <span class="input-icon">
                        <i class="bi bi-person"></i>
                      </span>
                      <input type="text" class="form-control" placeholder="First name" required="" autocomplete="given-name">
                    </div>
                    <div class="input-group">
                      <span class="input-icon">
                        <i class="bi bi-person"></i>
                      </span>
                      <input type="text" class="form-control" placeholder="Last name" required="" autocomplete="family-name">
                    </div>
                  </div>

                  <div class="input-group mb-3">
                    <span class="input-icon">
                      <i class="bi bi-envelope"></i>
                    </span>
                    <input type="email" class="form-control" placeholder="Email address" required="" autocomplete="email">
                  </div>

                  <div class="input-group mb-3">
                    <span class="input-icon">
                      <i class="bi bi-lock"></i>
                    </span>
                    <input type="password" class="form-control" placeholder="Create password" required="" autocomplete="new-password">
                    <span class="password-toggle">
                      <i class="bi bi-eye"></i>
                    </span>
                  </div>

                  <div class="input-group mb-3">
                    <span class="input-icon">
                      <i class="bi bi-lock-fill"></i>
                    </span>
                    <input type="password" class="form-control" placeholder="Confirm password" required="" autocomplete="new-password">
                    <span class="password-toggle">
                      <i class="bi bi-eye"></i>
                    </span>
                  </div>

                  <div class="terms-check mb-4">
                    <input type="checkbox" id="termsRegister" required="">
                    <label for="termsRegister">
                      I agree to the <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a>
                    </label>
                  </div>

                  <button type="submit" class="auth-btn primary-btn mb-3">
                    Create Account
                    <i class="bi bi-arrow-right"></i>
                  </button>

                  <div class="divider">
                    <span>or</span>
                  </div>

                  <button type="button" class="auth-btn social-btn">
                    <i class="bi bi-google"></i>
                    Sign up with Google
                  </button>

                  <div class="switch-form">
                    <span>Already have an account?</span>
                    <button type="button" class="switch-btn" data-target="login">Sign in</button>
                  </div>
                </form>
              </div>

            </div>
          </div>
        </div>

      </div>

    </section><!-- /Login Section -->

  </main>

  <footer id="footer" class="footer dark-background">
    <div class="footer-main">
      <div class="container">
        <div class="row gy-4">
          <div class="col-lg-4 col-md-6">
            <div class="footer-widget footer-about">
              <a href="index.html" class="logo">
                <span class="sitename">NiceShop</span>
              </a>
              <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam in nibh vehicula, facilisis magna ut, consectetur lorem. Proin eget tortor risus.</p>

              <div class="social-links mt-4">
                <h5>Connect With Us</h5>
                <div class="social-icons">
                  <a href="#" aria-label="Facebook"><i class="bi bi-facebook"></i></a>
                  <a href="#" aria-label="Instagram"><i class="bi bi-instagram"></i></a>
                  <a href="#" aria-label="Twitter"><i class="bi bi-twitter-x"></i></a>
                  <a href="#" aria-label="TikTok"><i class="bi bi-tiktok"></i></a>
                  <a href="#" aria-label="Pinterest"><i class="bi bi-pinterest"></i></a>
                  <a href="#" aria-label="YouTube"><i class="bi bi-youtube"></i></a>
                </div>
              </div>
            </div>
          </div>

          <div class="col-lg-2 col-md-6 col-sm-6">
            <div class="footer-widget">
              <h4>Shop</h4>
              <ul class="footer-links">
                <li><a href="category.html">New Arrivals</a></li>
                <li><a href="category.html">Bestsellers</a></li>
                <li><a href="category.html">Women's Clothing</a></li>
                <li><a href="category.html">Men's Clothing</a></li>
                <li><a href="category.html">Accessories</a></li>
                <li><a href="category.html">Sale</a></li>
              </ul>
            </div>
          </div>

          <div class="col-lg-2 col-md-6 col-sm-6">
            <div class="footer-widget">
              <h4>Support</h4>
              <ul class="footer-links">
                <li><a href="support.html">Help Center</a></li>
                <li><a href="account.html">Order Status</a></li>
                <li><a href="shiping-info.html">Shipping Info</a></li>
                <li><a href="return-policy.html">Returns &amp; Exchanges</a></li>
                <li><a href="#">Size Guide</a></li>
                <li><a href="contact.html">Contact Us</a></li>
              </ul>
            </div>
          </div>

          <div class="col-lg-4 col-md-6">
            <div class="footer-widget">
              <h4>Contact Information</h4>
              <div class="footer-contact">
                <div class="contact-item">
                  <i class="bi bi-geo-alt"></i>
                  <span>123 Fashion Street, New York, NY 10001</span>
                </div>
                <div class="contact-item">
                  <i class="bi bi-telephone"></i>
                  <span>+1 (555) 123-4567</span>
                </div>
                <div class="contact-item">
                  <i class="bi bi-envelope"></i>
                  <span>hello@example.com</span>
                </div>
                <div class="contact-item">
                  <i class="bi bi-clock"></i>
                  <span>Monday-Friday: 9am-6pm<br>Saturday: 10am-4pm<br>Sunday: Closed</span>
                </div>
              </div>

              <div class="app-buttons mt-4">
                <a href="#" class="app-btn">
                  <i class="bi bi-apple"></i>
                  <span>App Store</span>
                </a>
                <a href="#" class="app-btn">
                  <i class="bi bi-google-play"></i>
                  <span>Google Play</span>
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="footer-bottom">
      <div class="container">
        <div class="row gy-3 align-items-center">
          <div class="col-lg-6 col-md-12">
            <div class="copyright">
              <p>© <span>Copyright</span> <strong class="sitename">NiceShop</strong>. All Rights Reserved.</p>
            </div>
            <div class="credits mt-1">
              <!-- All the links in the footer should remain intact. -->
              <!-- You can delete the links only if you've purchased the pro version. -->
              <!-- Licensing information: https://bootstrapmade.com/license/ -->
              <!-- Purchase the pro version with working PHP/AJAX contact form: [buy-url] -->
              Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a>
            </div>
          </div>

          <div class="col-lg-6 col-md-12">
            <div class="d-flex flex-wrap justify-content-lg-end justify-content-center align-items-center gap-4">
              <div class="payment-methods">
                <div class="payment-icons">
                  <i class="bi bi-credit-card" aria-label="Credit Card"></i>
                  <i class="bi bi-paypal" aria-label="PayPal"></i>
                  <i class="bi bi-apple" aria-label="Apple Pay"></i>
                  <i class="bi bi-google" aria-label="Google Pay"></i>
                  <i class="bi bi-shop" aria-label="Shop Pay"></i>
                  <i class="bi bi-cash" aria-label="Cash on Delivery"></i>
                </div>
              </div>

              <div class="legal-links">
                <a href="tos.html">Terms</a>
                <a href="privacy.html">Privacy</a>
                <a href="tos.html">Cookies</a>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </footer>

  <!-- Scroll Top -->
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Preloader -->
  <div id="preloader"></div>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>
  <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
  <script src="assets/vendor/aos/aos.js"></script>
  <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="assets/vendor/drift-zoom/Drift.min.js"></script>
  <script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>

  <!-- Main JS File -->
  <script src="assets/js/main.js"></script>

  <script>
    const passwordToggleBtn = document.querySelector(".password-toggle");

    passwordToggleBtn.addEventListener("click", (e) => {
      const inputEl = passwordToggleBtn.parentNode.querySelector("input");
      
      if (inputEl.type === "password") {
        inputEl.type = "text";
      } else {
        inputEl.type = "password";
      }
    });
  </script>

</body>

</html>