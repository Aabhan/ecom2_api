<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include './Helpers/DatabaseConfig.php';
include './Helpers/Authenication.php';

global $CON;

// Check if the token is provided in the POST request
if(isset($_POST['token'])) {
    $token = $_POST['token'];
    
    // Get the user ID corresponding to the token
    $userId = getUserId($token);

    // Check if the user ID is retrieved successfully
    if ($userId) {
        // Modify the SQL query to fetch order details for the authenticated user
        $sql = "SELECT order_details.*, orders.user_id, orders.order_date, orders.status, products.title, products.price, products.image_url 
        FROM order_details 
        JOIN orders ON order_details.order_id = orders.order_id 
        JOIN products ON order_details.product_id = products.product_id 
        WHERE orders.user_id = '$userId'";


        
        $result = mysqli_query($CON, $sql);

        $orderDetails = [];

        // Fetch order details
        while ($row = mysqli_fetch_assoc($result)) {
            $orderDetails[] = $row;
        }

        // Check if the query was successful
        if ($result) {
            echo json_encode(
                array(
                    "success" => true,
                    "message" => "Order Details fetched successfully!",
                    "data" => $orderDetails
                )
            );
        } else {
            echo json_encode(
                array(
                    "success" => false,
                    "message" => "Failed to fetch order details."
                )
            );
        }
    } else {
        echo json_encode(
            array(
                "success" => false,
                "message" => "Invalid token or token expired. Please log in again."
            )
        );
    }
} else {
    echo json_encode(
        array(
            "success" => false,
            "message" => "Token is not provided."
        )
    );
}
?>
