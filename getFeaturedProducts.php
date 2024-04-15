<?php
include './Helpers/DatabaseConfig.php';
include './Helpers/Authenication.php';

// Function to get featured products
function getFeaturedProducts() {
    global $CON;

    // Query to retrieve featured products
    $sql = "SELECT products.*, full_name, email, phone_number, user_location 
            FROM products 
            JOIN categories ON categories.category_id = products.category_id 
            JOIN users ON products.user_id = users.user_id 
            WHERE products.is_available = 1 
            AND users.is_member = 1"; // Only select products from members

    $result = mysqli_query($CON, $sql);

    // Check if query executed successfully
    if ($result) {
        $featuredProducts = [];
        // Fetch products and add them to the array
        while ($row = mysqli_fetch_assoc($result)) {
            $featuredProducts[] = $row;
        }
        // Free result set
        mysqli_free_result($result);
        
        return array(
            "success" => true,
            "message" => "Featured products fetched successfully!",
            "data" => $featuredProducts
        );
    } else {
        // Handle query error
        return array(
            "success" => false,
            "message" => "Something went wrong!"
        );
    }
}

// Call the function and echo the JSON response
echo json_encode(getFeaturedProducts());
?>
