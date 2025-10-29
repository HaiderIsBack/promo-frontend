<?php

require_once '../global.php';

if (isset($_POST['confirm-order-placement'])) {
    if (count($_SESSION['cart']) < 1) {
        header("Location: " . SITE_URL . '/index.php');
        exit;
    }

    $first_name = $_POST['first-name'];
    $last_name = $_POST['last-name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $apartment = $_POST['apartment'];
    $city = $_POST['city'];
    $state = $_POST['state'];
    $zip = $_POST['zip'];
    $country = $_POST['country'];

    $save_address = isset($_POST['save-address']) ? $_POST['save-address'] : '';
    $billing_same = isset($_POST['billing-same']) ? $_POST['billing-same'] : '';

    list($payment_method_id, $payment_method_title) = explode(':', $_POST['payment-method'], 2);
    print_r($payment_method_title);

    $terms_check = $_POST['terms'];

    $order_data = [
        'payment_method' => $payment_method_id,
        'payment_method_title' => $payment_method_title,
        'set_paid' => true,
        'billing' => [
            'first_name' => $first_name,
            'last_name' => $last_name,
            'address_1' => $address,
            'city' => $city,
            'state' => $state,
            'postcode' => $zip,
            'country' => $country,
            'email' => $email,
            'phone' => $phone
        ],
        'shipping' => [
            'first_name' => $first_name,
            'last_name' => $last_name,
            'address_1' => $address,
            'city' => $city,
            'state' => $state,
            'postcode' => $zip,
            'country' => $country
        ],
        'line_items' => $_SESSION['cart']
    ];

    $fetch = new Woo_Fetch(STORE_URL, CONSUMER_KEY, CONSUMER_SECRET);

    $result = $fetch->request("POST", "/wp-json/wc/v3/orders/", [], $order_data);

    print_r($result);
    die();
}