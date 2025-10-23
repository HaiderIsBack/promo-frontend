<?php

require_once './global.php';

$fetch = new Woo_Fetch(STORE_URL, CONSUMER_KEY, CONSUMER_SECRET);

if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = [];
}

$ids = array_column($_SESSION['cart'], 'id');
$id_list = implode(',', $ids);

$products = [];

if (count($ids) > 0) {
  $result = $fetch->request("GET", "/wp-json/wc/v3/products", ['include' => $id_list]);

  if ($result['success']) {
      if (! is_null($result['response'])) {
          $products = json_decode($result['response'], true);
          foreach ($products as &$product) {
              foreach ($_SESSION['cart'] as $item) {
                  if ($item['id'] == $product['id']) {
                      $product['cart_quantity'] = $item['quantity'];
                      break;
                  }
              }
          }
          unset($product);
      }
  } else {
      echo $result['message'];
  }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Cart - NiceShop Bootstrap Template</title>
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

  <!-- Main CSS File -->
  <link href="assets/css/main.css" rel="stylesheet">

  <!-- =======================================================
  * Template Name: NiceShop
  * Template URL: https://bootstrapmade.com/niceshop-bootstrap-ecommerce-template/
  * Updated: Aug 26 2025 with Bootstrap v5.3.7
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<body class="cart-page">

  <?php require_once "./components/header.php"; ?>

  <main class="main">

    <!-- Page Title -->
    <div class="page-title light-background">
      <div class="container d-lg-flex justify-content-between align-items-center">
        <h1 class="mb-2 mb-lg-0">Cart</h1>
        <nav class="breadcrumbs">
          <ol>
            <li><a href="index.html">Home</a></li>
            <li class="current">Cart</li>
          </ol>
        </nav>
      </div>
    </div><!-- End Page Title -->

    <!-- Cart Section -->
    <section id="cart" class="cart section">

      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="row">
          <div class="col-lg-8" data-aos="fade-up" data-aos-delay="200">
            <div class="cart-items">
              <div class="cart-header d-none d-lg-block">
                <div class="row align-items-center">
                  <div class="col-lg-6">
                    <h5>Product</h5>
                  </div>
                  <div class="col-lg-2 text-center">
                    <h5>Price</h5>
                  </div>
                  <div class="col-lg-2 text-center">
                    <h5>Quantity</h5>
                  </div>
                  <div class="col-lg-2 text-center">
                    <h5>Total</h5>
                  </div>
                </div>
              </div>

              <?php if (count($products) > 0) { 
                foreach($products as $product) : 
                ?>
                <div class="cart-item">
                  <div class="row align-items-center">
                    <div class="col-lg-6 col-12 mt-3 mt-lg-0 mb-lg-0 mb-3">
                      <div class="product-info d-flex align-items-center">
                        <div class="product-image">
                          <img src="<?php echo $product['images'][0]['src']; ?>" alt="Product" class="img-fluid" loading="lazy">
                        </div>
                        <div class="product-details">
                          <h6 class="product-title"><?php echo $product['name']; ?></h6>
                          <!-- <div class="product-meta">
                            <span class="product-color">Color: Black</span>
                            <span class="product-size">Size: M</span>
                          </div> -->
                          <a href="controllers/remove_cart_item.php?id=<?php echo $product['id']; ?>" class="remove-item" type="button">
                            <i class="bi bi-trash"></i> Remove
                          </a>
                        </div>
                      </div>
                    </div>
                    <div class="col-lg-2 col-12 mt-3 mt-lg-0 text-center">
                      <div class="price-tag">
                        <span class="current-price"><?php echo $product['sale_price'] !== '' ? "Rs." . number_format($product['sale_price']) : "Rs." . number_format($product['price']); ?></span>
                        <span class="original-price"><?php echo $product['sale_price'] !== '' ? "Rs." . number_format($product['regular_price']) : '' ?></span>
                      </div>
                    </div>
                    <div class="col-lg-2 col-12 mt-3 mt-lg-0 text-center">
                      <div class="quantity-selector">
                        <button class="quantity-btn decrease">
                          <i class="bi bi-dash"></i>
                        </button>
                        <input type="number" class="quantity-input" value="<?php echo $product['cart_quantity']; ?>" min="1" max="10">
                        <button class="quantity-btn increase">
                          <i class="bi bi-plus"></i>
                        </button>
                      </div>
                    </div>
                    <div class="col-lg-2 col-12 mt-3 mt-lg-0 text-center">
                      <div class="item-total">
                        <span>
                          <?php
                            echo 'Rs.' . number_format($product['price'] * $product['cart_quantity']);
                          ?>
                        </span>
                      </div>
                    </div>
                  </div>
                </div>
              <?php
                endforeach; 
              } else { ?>
                <p>Your cart is empty! Visit <a href="category.html">Shop</a></p>
              <?php } ?>

              <div class="cart-actions">
                <div class="row">
                  <div class="col-lg-6 mb-3 mb-lg-0">
                    <div class="coupon-form">
                      <div class="input-group">
                        <input type="text" class="form-control" placeholder="Coupon code">
                        <button class="btn btn-outline-accent" type="button">Apply Coupon</button>
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-6 text-md-end">
                    <button class="btn btn-outline-heading me-2">
                      <i class="bi bi-arrow-clockwise"></i> Update Cart
                    </button>
                    <button class="btn btn-outline-remove">
                      <i class="bi bi-trash"></i> Clear Cart
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="col-lg-4 mt-4 mt-lg-0" data-aos="fade-up" data-aos-delay="300">
            <div class="cart-summary">
              <h4 class="summary-title">Order Summary</h4>

              <div class="summary-item">
                <span class="summary-label">Subtotal</span>
                <span class="summary-value">$269.96</span>
              </div>

              <div class="summary-item shipping-item">
                <span class="summary-label">Shipping</span>
                <div class="shipping-options">
                  <div class="form-check text-end">
                    <input class="form-check-input" type="radio" name="shipping" id="standard" checked="">
                    <label class="form-check-label" for="standard">
                      Standard Delivery - $4.99
                    </label>
                  </div>
                  <div class="form-check text-end">
                    <input class="form-check-input" type="radio" name="shipping" id="express">
                    <label class="form-check-label" for="express">
                      Express Delivery - $12.99
                    </label>
                  </div>
                  <div class="form-check text-end">
                    <input class="form-check-input" type="radio" name="shipping" id="free">
                    <label class="form-check-label" for="free">
                      Free Shipping (Orders over $300)
                    </label>
                  </div>
                </div>
              </div>

              <div class="summary-item">
                <span class="summary-label">Tax</span>
                <span class="summary-value">$27.00</span>
              </div>

              <div class="summary-item discount">
                <span class="summary-label">Discount</span>
                <span class="summary-value">-$0.00</span>
              </div>

              <div class="summary-total">
                <span class="summary-label">Total</span>
                <span class="summary-value">$301.95</span>
              </div>

              <div class="checkout-button">
                <a href="#" class="btn btn-accent w-100">
                  Proceed to Checkout <i class="bi bi-arrow-right"></i>
                </a>
              </div>

              <div class="continue-shopping">
                <a href="#" class="btn btn-link w-100">
                  <i class="bi bi-arrow-left"></i> Continue Shopping
                </a>
              </div>

              <div class="payment-methods">
                <p class="payment-title">We Accept</p>
                <div class="payment-icons">
                  <i class="bi bi-credit-card"></i>
                  <i class="bi bi-paypal"></i>
                  <i class="bi bi-wallet2"></i>
                  <i class="bi bi-bank"></i>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>

    </section><!-- /Cart Section -->

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

</body>

</html>