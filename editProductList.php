<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


include './Helpers/Authenication.php';
include './Helpers/DatabaseConfig.php';


if (isset($_POST['title'], $_POST['token'], $_POST['description'], $_POST['price'], $_POST['is_old'], $_POST['is_negotiable'], $_POST['product_id'])) {
global $CON;
    $title = $_POST['title'];
    $token = $_POST['token'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    
    $isOld = $_POST['is_old'];
    $isNegotiable = $_POST['is_negotiable'];
    $productID = $_POST['product_id'];

$sql = "UPDATE products SET title = '$title', description = '$description', price = '$price',is_old = '$isOld',is_negotiable = '$isNegotiable' WHERE product_id = '$productID'";
$result = mysqli_query($CON, $sql);
if ($result){
    echo json_encode(
        array(
            "success" => true,
            "message" => "Updates made successfully",
        )
    );
}else{
    echo json_encode(
        array(
            "success" => false,
            "message" => "Product update failed"
        )
    );

}

}else {
    // Handle case where not all POST variables are set
    echo json_encode(
        array(
            "success" => false,
            "message" => "Not all required fields are set"
        )
    );
}
