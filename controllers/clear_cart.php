<?php

require_once "../global.php";

$_SESSION['cart'] = [];

header('Location: ' . SITE_URL . '/cart.php');
exit;