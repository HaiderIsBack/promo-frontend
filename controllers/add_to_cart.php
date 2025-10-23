<?php

require_once "../global.php";

header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);

$response = [];

if (!empty($data['productId']) && !empty($data['quantity']) && $data['quantity'] > 0) {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    $productId = $data['productId'];
    $quantity = (int) $data['quantity'];

    $found = false;
    
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] === $productId) {
            $item['quantity'] += $quantity;
            $found = true;
            break;
        }
    }
    unset($item);

    if (!$found) {
        $_SESSION['cart'][] = [
            'id' => $productId,
            'quantity' => $quantity
        ];
    }

    $cart_items_count = 0;
    foreach($_SESSION['cart'] as &$item) {
        $cart_items_count += (int) $item['quantity'];
    }
    unset($item);

    $response = [
        'success' => true,
        'message' => 'Your ' . ($quantity > 1 ? 'products are' : 'product is') . ' added to cart successfully.',
        'cartCount' => $cart_items_count
    ];
} else {
    $response = [
        'success' => false,
        'message' => 'Invalid AJAX request.'
    ];
}

echo json_encode($response);