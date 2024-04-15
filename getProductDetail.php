<?php

include './Helpers/DatabaseConfig.php';
include './Helpers/Authenication.php';

if (!isset($_POST['token'])) {
    echo json_encode(
        array(
            "success" => false,
            "message" => "You are not authorized!"
        )
    );
    die();
}

global $CON;

$token = $_POST['token'];
$productId = $_POST['product_id'];

// Perform authorization and get user ID from token
$userId = getUserId($token);

// Query to fetch product details
$sql = "SELECT * FROM products WHERE product_id = '$productId' AND user_id = '$userId'"; 
$result = mysqli_query($CON, $sql);

if ($result) {
    $row = mysqli_fetch_assoc($result);
    echo json_encode(
        array(
            "success" => true,
            "message" => "Product details fetched successfully!",
            "data" => $row
        )
    );
} else {
    echo json_encode(
        array(
            "success" => false,
            "message" => "Fetching product details failed!"
        )
    );
}
