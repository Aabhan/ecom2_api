<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


include './Helpers/DatabaseConfig.php';
include './Helpers/Authenication.php';


// Check if the database connection is successful
if (!$CON) {
    die(json_encode(array(
        "success" => false,
        "message" => "Database connection failed: " . mysqli_connect_error()
    )));
}

if (!isset($_POST['token'])) {
    echo json_encode(
        array(
            "success" => false,
            "message" => "You are not authorized!"
        )
    );
    die();
}

if (!isset($_POST['cart']) || !isset($_POST['total'])) {
    echo json_encode(
        array(
            "success" => false,
            "message" => "Cart and total are required!"
        )
    );
    die();
}

global $CON;

$token = $_POST['token'];
$cart = $_POST['cart'];
$total = $_POST['total'];

$userId = getUserId($token);

// Check if user ID is valid
if (!$userId) {
    echo json_encode(
        array(
            "success" => false,
            "message" => "Invalid token!"
        )
    );
    die();
}

// Prepare the SQL statement for inserting an order
$stmt = $CON->prepare("INSERT INTO orders (user_id, total) VALUES (?, ?)");
if (!$stmt) {
    echo json_encode(
        array(
            "success" => false,
            "message" => "Failed to prepare statement: " . $CON->error
        )
    );
    die();
}

$stmt->bind_param("is", $userId, $total);

if ($stmt->execute()) {
    $orderId = $stmt->insert_id;

    $cartList = json_decode($cart, true); // Decode JSON to associative array

    if (!is_array($cartList)) {
        echo json_encode(
            array(
                "success" => false,
                "message" => "Invalid cart format!"
            )
        );
        die();
    }

    foreach ($cartList as $cartItem) {
        $product = $cartItem['product'];
        $quantity = $cartItem['quantity'];
        $price = $product['price'];
        $line_total = $quantity * $price;
        $productID = $product['product_id'];

        // Prepare the SQL statement for inserting order details
        $stmtDetails = $CON->prepare("INSERT INTO order_details (order_id, product_id, quantity, line_total) VALUES (?, ?, ?, ?)");
        if (!$stmtDetails) {
            echo json_encode(
                array(
                    "success" => false,
                    "message" => "Failed to prepare order details statement: " . $CON->error
                )
            );
            die();
        }

        $stmtDetails->bind_param("iiid", $orderId, $productID, $quantity, $line_total);

        if (!$stmtDetails->execute()) {
            echo json_encode(
                array(
                    "success" => false,
                    "message" => "Failed to insert order details: " . $stmtDetails->error
                )
            );
            die();
        }
    }

    echo json_encode(
        array(
            "success" => true,
            "message" => "Order created successfully!",
            "order_id" => $orderId
        )
    );
} else {
    echo json_encode(
        array(
            "success" => false,
            "message" => "Order creation failed: " . $stmt->error
        )
    );
}
