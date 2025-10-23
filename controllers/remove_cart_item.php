<?php

require_once "../global.php";

if (!isset($_GET['id'])) {
    header('Location: ' . SITE_URL . '/index.php');
    exit;
}

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$_SESSION['cart'] = array_filter($_SESSION['cart'], function ($item) { 
    return $item['id'] != $_GET['id']; 
});

$_SESSION['cart'] = array_values($_SESSION['cart']);

header('Location: ' . SITE_URL . '/cart.php');
exit;