<?php

require_once './global.php';

$fetch = new Woo_Fetch(STORE_URL, CONSUMER_KEY, CONSUMER_SECRET);

$result = $fetch->request("GET", "/wp-json/wc/v3/products", [
  'per_page' => 50, 
  'page' => 1,
  'orderby' => 'date',
  'order' => 'desc'
]);

$products = [];

if ($result['success']) {
    if (! is_null($result['response'])) {
        $products = json_decode($result['response'], true);
    }
} else {
    echo $result['message'];
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Home</title>
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

<body class="index-page">

  <?php require_once "./components/header.php"; ?>

  <main class="main">
    <?php
    if (isset($_SESSION['toast'])) {
      $toastType = json_decode($_SESSION['toast'], true)['type'];
      $toastMessage = json_decode($_SESSION['toast'], true)['message'];
      echo "<script>
        $(document).ready(function() {
            toastr.options.positionClass = 'toast-bottom-right';
            toastr.$toastType('$toastMessage');
        });
      </script>";
      unset($_SESSION['toast']);
    }
    ?>

    <!-- Hero Section -->
    <section id="hero" class="hero section">

      <div class="hero-container">
        <div class="hero-content">
          <div class="content-wrapper" data-aos="fade-up" data-aos-delay="100">
            <h1 class="hero-title">Discover Amazing Products</h1>
            <p class="hero-description">Explore our curated collection of premium items designed to enhance your lifestyle. From fashion to tech, find everything you need with exclusive deals and fast shipping.</p>
            <div class="hero-actions" data-aos="fade-up" data-aos-delay="200">
              <a href="#products" class="btn-primary">Shop Now</a>
              <a href="#promo-cards" class="btn-secondary">Browse Categories</a>
            </div>
            <div class="features-list" data-aos="fade-up" data-aos-delay="300">
              <div class="feature-item">
                <i class="bi bi-truck"></i>
                <span>Free Shipping</span>
              </div>
              <div class="feature-item">
                <i class="bi bi-award"></i>
                <span>Quality Guarantee</span>
              </div>
              <div class="feature-item">
                <i class="bi bi-headset"></i>
                <span>24/7 Support</span>
              </div>
            </div>
          </div>
        </div>

        <div class="hero-visuals">
          <div class="product-showcase" data-aos="fade-left" data-aos-delay="200">
            <div class="product-card featured">
              <img src="<?php echo $products[0]['images'][0]['src'] ?>" alt="Featured Product" class="img-fluid">
              <div class="product-badge">Best Seller</div>
              <div class="product-info">
                <h4><?php echo $products[0]['name']; ?></h4>
                <div class="price">
                  <span class="sale-price"><?php echo $products[0]['sale_price'] !== '' ? "Rs." . number_format($products[0]['sale_price']) : "Rs." . number_format($products[0]['price']); ?></span>
                  <span class="original-price"><?php echo $products[0]['sale_price'] !== '' ? "Rs." . number_format($products[0]['regular_price']) : '' ?></span>
                </div>
              </div>
            </div>

            <div class="product-grid">
              <div class="product-mini" data-aos="zoom-in" data-aos-delay="400">
                <img src="<?php echo $products[1]['images'][0]['src'] ?>" alt="Product" class="img-fluid">
                <span class="mini-price">Rs.<?php echo $products[1]['price']; ?></span>
              </div>
              <div class="product-mini" data-aos="zoom-in" data-aos-delay="500">
                <img src="<?php echo $products[2]['images'][0]['src'] ?>" alt="Product" class="img-fluid">
                <span class="mini-price">Rs.<?php echo $products[2]['price']; ?></span>
              </div>
            </div>
          </div>

          <div class="floating-elements">
            <div class="floating-icon cart" data-aos="fade-up" data-aos-delay="600">
              <i class="bi bi-cart3"></i>
              <span class="notification-dot">
                <?php echo get_cart_count(); ?>
              </span>
            </div>
            <div class="floating-icon wishlist" data-aos="fade-up" data-aos-delay="700">
              <i class="bi bi-heart"></i>
            </div>
            <div class="floating-icon search" data-aos="fade-up" data-aos-delay="800">
              <i class="bi bi-search"></i>
            </div>
          </div>
        </div>
      </div>

    </section><!-- /Hero Section -->

    <!-- Promo Cards Section 
    <section id="promo-cards" class="promo-cards section">
      <div class="container" data-aos="fade-up" data-aos-delay="100">
        <div class="row gy-4">

          <div class="col-lg-6">
            <div class="category-featured" data-aos="fade-right" data-aos-delay="200">
              <div class="category-image">
                <img src="assets/img/product/product-f-2.webp" alt="Women's Collection" class="img-fluid">
              </div>
              <div class="category-content">
                <span class="category-tag">Trending Now</span>
                <h2>New Summer Collection</h2>
                <p>Discover our latest arrivals designed for the modern lifestyle. Elegant, comfortable, and sustainable fashion for every occasion.</p>
                <a href="#" class="btn-shop">Explore Collection <i class="bi bi-arrow-right"></i></a>
              </div>
            </div>
          </div>

          <div class="col-lg-6">

            <div class="row gy-4">

              <div class="col-xl-6">
                <div class="category-card cat-men" data-aos="fade-up" data-aos-delay="300">
                  <div class="category-image">
                    <img src="assets/img/product/product-m-5.webp" alt="Men's Fashion" class="img-fluid">
                  </div>
                  <div class="category-content">
                    <h4>Men's Wear</h4>
                    <p>242 products</p>
                    <a href="#" class="card-link">Shop Now <i class="bi bi-arrow-right"></i></a>
                  </div>
                </div>
              </div>

              <div class="col-xl-6">
                <div class="category-card cat-kids" data-aos="fade-up" data-aos-delay="400">
                  <div class="category-image">
                    <img src="https://practice-php.page.gd/wp-content/uploads/2025/10/VGWebsiteThumbnailsSwitch2-ezgif.com-webp-to-jpg-converter.jpg" alt="Kid's Fashion" class="img-fluid">
                  </div>
                  <div class="category-content">
                    <h4>Consoles</h4>
                    <p>185 products</p>
                    <a href="#" class="card-link">Shop Now <i class="bi bi-arrow-right"></i></a>
                  </div>
                </div>
              </div>

              <div class="col-xl-6">
                <div class="category-card cat-cosmetics" data-aos="fade-up" data-aos-delay="500">
                  <div class="category-image">
                    <img src="assets/img/product/product-3.webp" alt="Cosmetics" class="img-fluid">
                  </div>
                  <div class="category-content">
                    <h4>Beauty Products</h4>
                    <p>127 products</p>
                    <a href="#" class="card-link">Shop Now <i class="bi bi-arrow-right"></i></a>
                  </div>
                </div>
              </div>

              <div class="col-xl-6">
                <div class="category-card cat-accessories" data-aos="fade-up" data-aos-delay="600">
                  <div class="category-image">
                    <img src="assets/img/product/product-12.webp" alt="Accessories" class="img-fluid">
                  </div>
                  <div class="category-content">
                    <h4>Accessories</h4>
                    <p>308 products</p>
                    <a href="#" class="card-link">Shop Now <i class="bi bi-arrow-right"></i></a>
                  </div>
                </div>
              </div>

            </div>
          </div>

        </div>

      </div>
    </section>/Promo Cards Section -->

    <!-- Best Sellers Section -->
    <section id="best-sellers" class="best-sellers section" style="display: none;">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up" id="products">
        <h2>Best Sellers</h2>
        <p>Necessitatibus eius consequatur ex aliquid fuga eum quidem sint consectetur velit</p>
      </div><!-- End Section Title -->

      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="row g-5">

          <!-- Product 1 -->
          <div class="col-lg-3 col-md-6">
            <div class="product-item">
              <div class="product-image">
                <div class="product-badge">Limited</div>
                <img src="assets/img/product/product-1.webp" alt="Product Image" class="img-fluid" loading="lazy">
                <div class="product-actions">
                  <button class="action-btn wishlist-btn">
                    <i class="bi bi-heart"></i>
                  </button>
                  <button class="action-btn compare-btn">
                    <i class="bi bi-arrow-left-right"></i>
                  </button>
                  <button class="action-btn quickview-btn">
                    <i class="bi bi-zoom-in"></i>
                  </button>
                </div>
                <button class="cart-btn">Select Options</button>
              </div>
              <div class="product-info">
                <div class="product-category">Premium Collection</div>
                <h4 class="product-name"><a href="product-details.html">Mauris blandit aliquet elit</a></h4>
                <div class="product-rating">
                  <div class="stars">
                    <i class="bi bi-star-fill"></i>
                    <i class="bi bi-star-fill"></i>
                    <i class="bi bi-star-fill"></i>
                    <i class="bi bi-star-fill"></i>
                    <i class="bi bi-star"></i>
                  </div>
                  <span class="rating-count">(24)</span>
                </div>
                <div class="product-price">$189.00</div>
                <div class="color-swatches">
                  <span class="swatch active" style="background-color: #2563eb;"></span>
                  <span class="swatch" style="background-color: #059669;"></span>
                  <span class="swatch" style="background-color: #dc2626;"></span>
                </div>
              </div>
            </div>
          </div>
          <!-- End Product 1 -->

          <!-- Product 2 -->
          <div class="col-lg-3 col-md-6">
            <div class="product-item">
              <div class="product-image">
                <div class="product-badge sale-badge">25% Off</div>
                <img src="assets/img/product/product-4.webp" alt="Product Image" class="img-fluid" loading="lazy">
                <div class="product-actions">
                  <button class="action-btn wishlist-btn">
                    <i class="bi bi-heart"></i>
                  </button>
                  <button class="action-btn compare-btn">
                    <i class="bi bi-arrow-left-right"></i>
                  </button>
                  <button class="action-btn quickview-btn">
                    <i class="bi bi-zoom-in"></i>
                  </button>
                </div>
                <button class="cart-btn">Add to Cart</button>
              </div>
              <div class="product-info">
                <div class="product-category">Best Sellers</div>
                <h4 class="product-name"><a href="product-details.html">Sed do eiusmod tempor incididunt</a></h4>
                <div class="product-rating">
                  <div class="stars">
                    <i class="bi bi-star-fill"></i>
                    <i class="bi bi-star-fill"></i>
                    <i class="bi bi-star-fill"></i>
                    <i class="bi bi-star-fill"></i>
                    <i class="bi bi-star-half"></i>
                  </div>
                  <span class="rating-count">(38)</span>
                </div>
                <div class="product-price">
                  <span class="old-price">$240.00</span>
                  <span class="current-price">$180.00</span>
                </div>
                <div class="color-swatches">
                  <span class="swatch active" style="background-color: #1f2937;"></span>
                  <span class="swatch" style="background-color: #f59e0b;"></span>
                  <span class="swatch" style="background-color: #8b5cf6;"></span>
                </div>
              </div>
            </div>
          </div>
          <!-- End Product 2 -->

          <!-- Product 3 -->
          <div class="col-lg-3 col-md-6">
            <div class="product-item">
              <div class="product-image">
                <img src="assets/img/product/product-7.webp" alt="Product Image" class="img-fluid" loading="lazy">
                <div class="product-actions">
                  <button class="action-btn wishlist-btn">
                    <i class="bi bi-heart"></i>
                  </button>
                  <button class="action-btn compare-btn">
                    <i class="bi bi-arrow-left-right"></i>
                  </button>
                  <button class="action-btn quickview-btn">
                    <i class="bi bi-zoom-in"></i>
                  </button>
                </div>
                <button class="cart-btn">Add to Cart</button>
              </div>
              <div class="product-info">
                <div class="product-category">New Arrivals</div>
                <h4 class="product-name"><a href="product-details.html">Lorem ipsum dolor sit amet consectetur</a></h4>
                <div class="product-rating">
                  <div class="stars">
                    <i class="bi bi-star-fill"></i>
                    <i class="bi bi-star-fill"></i>
                    <i class="bi bi-star-fill"></i>
                    <i class="bi bi-star"></i>
                    <i class="bi bi-star"></i>
                  </div>
                  <span class="rating-count">(12)</span>
                </div>
                <div class="product-price">$95.00</div>
                <div class="color-swatches">
                  <span class="swatch active" style="background-color: #ef4444;"></span>
                  <span class="swatch" style="background-color: #06b6d4;"></span>
                  <span class="swatch" style="background-color: #10b981;"></span>
                </div>
              </div>
            </div>
          </div>
          <!-- End Product 3 -->

          <!-- Product 4 -->
          <div class="col-lg-3 col-md-6">
            <div class="product-item">
              <div class="product-image">
                <div class="product-badge trending-badge">Trending</div>
                <img src="assets/img/product/product-10.webp" alt="Product Image" class="img-fluid" loading="lazy">
                <div class="product-actions">
                  <button class="action-btn wishlist-btn active">
                    <i class="bi bi-heart-fill"></i>
                  </button>
                  <button class="action-btn compare-btn">
                    <i class="bi bi-arrow-left-right"></i>
                  </button>
                  <button class="action-btn quickview-btn">
                    <i class="bi bi-zoom-in"></i>
                  </button>
                </div>
                <button class="cart-btn">Add to Cart</button>
              </div>
              <div class="product-info">
                <div class="product-category">Designer Series</div>
                <h4 class="product-name"><a href="product-details.html">Ut enim ad minim veniam quis</a></h4>
                <div class="product-rating">
                  <div class="stars">
                    <i class="bi bi-star-fill"></i>
                    <i class="bi bi-star-fill"></i>
                    <i class="bi bi-star-fill"></i>
                    <i class="bi bi-star-fill"></i>
                    <i class="bi bi-star-fill"></i>
                  </div>
                  <span class="rating-count">(56)</span>
                </div>
                <div class="product-price">$165.00</div>
                <div class="color-swatches">
                  <span class="swatch" style="background-color: #64748b;"></span>
                  <span class="swatch active" style="background-color: #7c3aed;"></span>
                  <span class="swatch" style="background-color: #f59e0b;"></span>
                </div>
              </div>
            </div>
          </div>
          <!-- End Product 4 -->

        </div>

      </div>

    </section>

    <!-- Cards Section -->
    <section id="cards" class="cards section">

      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="row gy-4">
          <div class="col-lg-4 col-md-6 mb-5 mb-md-0" data-aos="fade-up" data-aos-delay="200">
            <div class="product-category">
              <h3 class="category-title">
                <i class="bi bi-fire"></i> Trending Now
              </h3>
              <div class="product-list">
                <div class="product-card">
                  <div class="product-image">
                    <img src="assets/img/product/product-1.webp" alt="Premium Leather Tote" class="img-fluid">
                    <div class="product-badges">
                      <span class="badge-new">New</span>
                    </div>
                  </div>
                  <div class="product-info">
                    <h4 class="product-name">Premium Leather Tote</h4>
                    <div class="product-rating">
                      <i class="bi bi-star-fill"></i>
                      <i class="bi bi-star-fill"></i>
                      <i class="bi bi-star-fill"></i>
                      <i class="bi bi-star-fill"></i>
                      <i class="bi bi-star-half"></i>
                      <span>(24)</span>
                    </div>
                    <div class="product-price">
                      <span class="current-price">$87.50</span>
                    </div>
                  </div>
                </div>

                <div class="product-card">
                  <div class="product-image">
                    <img src="assets/img/product/product-3.webp" alt="Statement Earrings" class="img-fluid">
                  </div>
                  <div class="product-info">
                    <h4 class="product-name">Statement Earrings</h4>
                    <div class="product-rating">
                      <i class="bi bi-star-fill"></i>
                      <i class="bi bi-star-fill"></i>
                      <i class="bi bi-star-fill"></i>
                      <i class="bi bi-star-fill"></i>
                      <i class="bi bi-star-fill"></i>
                      <span>(41)</span>
                    </div>
                    <div class="product-price">
                      <span class="current-price">$39.99</span>
                    </div>
                  </div>
                </div>

                <div class="product-card">
                  <div class="product-image">
                    <img src="assets/img/product/product-5.webp" alt="Organic Cotton Shirt" class="img-fluid">
                  </div>
                  <div class="product-info">
                    <h4 class="product-name">Organic Cotton Shirt</h4>
                    <div class="product-rating">
                      <i class="bi bi-star-fill"></i>
                      <i class="bi bi-star-fill"></i>
                      <i class="bi bi-star-fill"></i>
                      <i class="bi bi-star-fill"></i>
                      <i class="bi bi-star"></i>
                      <span>(18)</span>
                    </div>
                    <div class="product-price">
                      <span class="current-price">$45.00</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="col-lg-4 col-md-6 mb-5 mb-md-0" data-aos="fade-up" data-aos-delay="300">
            <div class="product-category">
              <h3 class="category-title">
                <i class="bi bi-award"></i> Best Sellers
              </h3>
              <div class="product-list">
                <div class="product-card">
                  <div class="product-image">
                    <img src="assets/img/product/product-2.webp" alt="Slim Fit Denim" class="img-fluid">
                    <div class="product-badges">
                      <span class="badge-sale">-15%</span>
                    </div>
                  </div>
                  <div class="product-info">
                    <h4 class="product-name">Slim Fit Denim</h4>
                    <div class="product-rating">
                      <i class="bi bi-star-fill"></i>
                      <i class="bi bi-star-fill"></i>
                      <i class="bi bi-star-fill"></i>
                      <i class="bi bi-star-fill"></i>
                      <i class="bi bi-star-fill"></i>
                      <span>(87)</span>
                    </div>
                    <div class="product-price">
                      <span class="current-price">$68.00</span>
                      <span class="old-price">$80.00</span>
                    </div>
                  </div>
                </div>

                <div class="product-card">
                  <div class="product-image">
                    <img src="assets/img/product/product-6.webp" alt="Designer Handbag" class="img-fluid">
                  </div>
                  <div class="product-info">
                    <h4 class="product-name">Designer Handbag</h4>
                    <div class="product-rating">
                      <i class="bi bi-star-fill"></i>
                      <i class="bi bi-star-fill"></i>
                      <i class="bi bi-star-fill"></i>
                      <i class="bi bi-star-fill"></i>
                      <i class="bi bi-star-half"></i>
                      <span>(56)</span>
                    </div>
                    <div class="product-price">
                      <span class="current-price">$129.99</span>
                    </div>
                  </div>
                </div>

                <div class="product-card">
                  <div class="product-image">
                    <img src="assets/img/product/product-8.webp" alt="Leather Crossbody" class="img-fluid">
                    <div class="product-badges">
                      <span class="badge-hot">Hot</span>
                    </div>
                  </div>
                  <div class="product-info">
                    <h4 class="product-name">Leather Crossbody</h4>
                    <div class="product-rating">
                      <i class="bi bi-star-fill"></i>
                      <i class="bi bi-star-fill"></i>
                      <i class="bi bi-star-fill"></i>
                      <i class="bi bi-star-fill"></i>
                      <i class="bi bi-star-fill"></i>
                      <span>(112)</span>
                    </div>
                    <div class="product-price">
                      <span class="current-price">$95.50</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="col-lg-4 col-md-6 mb-5 mb-md-0" data-aos="fade-up" data-aos-delay="400">
            <div class="product-category">
              <h3 class="category-title">
                <i class="bi bi-star"></i> Featured Items
              </h3>
              <div class="product-list">
                <div class="product-card">
                  <div class="product-image">
                    <img src="assets/img/product/product-7.webp" alt="Pleated Midi Skirt" class="img-fluid">
                  </div>
                  <div class="product-info">
                    <h4 class="product-name">Pleated Midi Skirt</h4>
                    <div class="product-rating">
                      <i class="bi bi-star-fill"></i>
                      <i class="bi bi-star-fill"></i>
                      <i class="bi bi-star-fill"></i>
                      <i class="bi bi-star-fill"></i>
                      <i class="bi bi-star"></i>
                      <span>(32)</span>
                    </div>
                    <div class="product-price">
                      <span class="current-price">$75.00</span>
                    </div>
                  </div>
                </div>

                <div class="product-card">
                  <div class="product-image">
                    <img src="assets/img/product/product-4.webp" alt="Geometric Earrings" class="img-fluid">
                    <div class="product-badges">
                      <span class="badge-limited">Limited</span>
                    </div>
                  </div>
                  <div class="product-info">
                    <h4 class="product-name">Geometric Earrings</h4>
                    <div class="product-rating">
                      <i class="bi bi-star-fill"></i>
                      <i class="bi bi-star-fill"></i>
                      <i class="bi bi-star-fill"></i>
                      <i class="bi bi-star-fill"></i>
                      <i class="bi bi-star-half"></i>
                      <span>(47)</span>
                    </div>
                    <div class="product-price">
                      <span class="current-price">$42.99</span>
                    </div>
                  </div>
                </div>

                <div class="product-card">
                  <div class="product-image">
                    <img src="assets/img/product/product-9.webp" alt="Structured Satchel" class="img-fluid">
                  </div>
                  <div class="product-info">
                    <h4 class="product-name">Structured Satchel</h4>
                    <div class="product-rating">
                      <i class="bi bi-star-fill"></i>
                      <i class="bi bi-star-fill"></i>
                      <i class="bi bi-star-fill"></i>
                      <i class="bi bi-star-fill"></i>
                      <i class="bi bi-star-fill"></i>
                      <span>(64)</span>
                    </div>
                    <div class="product-price">
                      <span class="current-price">$89.99</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>

    </section><!-- /Cards Section -->

    <!-- Call To Action Section -->
    <section id="call-to-action" class="call-to-action section">

      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="row">
          <div class="col-lg-8 mx-auto">
            <div class="main-content text-center" data-aos="zoom-in" data-aos-delay="200">
              <div class="offer-badge" data-aos="fade-down" data-aos-delay="250">
                <span class="limited-time">Limited Time</span>
                <span class="offer-text">50% OFF</span>
              </div>

              <h2 data-aos="fade-up" data-aos-delay="300">Exclusive Flash Sale</h2>

              <p class="subtitle" data-aos="fade-up" data-aos-delay="350">Don't miss out on our biggest sale of the year. Premium quality products at unbeatable prices for the next 48 hours only.</p>

              <div class="countdown-wrapper" data-aos="fade-up" data-aos-delay="400">
                <div class="countdown d-flex justify-content-center" data-count="2025/12/31">
                  <div>
                    <h3 class="count-days"></h3>
                    <h4>Days</h4>
                  </div>
                  <div>
                    <h3 class="count-hours"></h3>
                    <h4>Hours</h4>
                  </div>
                  <div>
                    <h3 class="count-minutes"></h3>
                    <h4>Minutes</h4>
                  </div>
                  <div>
                    <h3 class="count-seconds"></h3>
                    <h4>Seconds</h4>
                  </div>
                </div>
              </div>

              <div class="action-buttons" data-aos="fade-up" data-aos-delay="450">
                <a href="#" class="btn-shop-now">Shop Now</a>
                <a href="#" class="btn-view-deals">View All Deals</a>
              </div>
            </div>
          </div>
        </div>

        <div class="row featured-products-row" data-aos="fade-up" data-aos-delay="500">
          <?php foreach($products as $product) : ?>
            <div class="col-lg-3 col-md-6" data-aos="zoom-in" data-aos-delay="100">
              <a href="<?php echo SITE_URL; ?>/product-details.php?id=<?php echo $product['id']; ?>">
                <div class="product-showcase">
                  <div class="product-image">
                    <img src="<?php echo $product['images'][0]['src']; ?>" alt="Featured Product" class="img-fluid">
                    <?php if ($product['on_sale']) {
                      $discount = floor((($product['regular_price'] - $product['sale_price']) / $product['regular_price']) * 100);
                      ?>
                      <div class="discount-badge">-<?php echo $discount; ?>%</div>
                    <?php } ?>
                  </div>
                  <div class="product-details">
                    <h6><?php echo $product['name']; ?></h6>
                    <div class="price-section">
                      <span class="original-price"><?php echo $product['on_sale'] ? 'Rs.' . number_format($product['regular_price']) : ''; ?></span>
                      <span class="sale-price"><?php echo $product['on_sale'] ? 'Rs.' . number_format($product['sale_price']) : 'Rs.' . number_format($product['price']); ?></span>
                    </div>
                    <div class="rating-stars">
                      <i class="bi bi-star-fill"></i>
                      <i class="bi bi-star-fill"></i>
                      <i class="bi bi-star-fill"></i>
                      <i class="bi bi-star-fill"></i>
                      <i class="bi bi-star-fill"></i>
                      <span class="rating-count">(324)</span>
                    </div>
                  </div>
                </div>
              </a>
            </div>
          <?php endforeach; ?>
        </div>

      </div>

    </section><!-- /Call To Action Section -->

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