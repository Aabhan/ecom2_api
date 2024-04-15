<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


include './Helpers/DatabaseConfig.php';
include './Helpers/Authenication.php';


global $CON;
$product_id = $_POST['productId'];


$sql = "Select ratings.*,full_name,email,phone_number from ratings join users on ratings.user_id=users.user_id where ratings.product_id = '$product_id' ";
$result = mysqli_query($CON, $sql);

$ratings = [];

while ($row = mysqli_fetch_assoc($result)) {
    $ratings[] = $row;
}

if ($result) {
    echo json_encode(
        array(
            "success" => true,
            "message" => "Rating fetched successfully!",
            "data" => $ratings
        )
    );
} else {
    echo json_encode(
        array(
            "success" => false,
            "message" => "Something went wrong!"
        )
    );
}
