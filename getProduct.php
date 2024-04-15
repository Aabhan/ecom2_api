<?php
include './Helpers/DatabaseConfig.php';
include './Helpers/Authenication.php';


global $CON;

// $sql = "Select * from products";
$sql = "Select products.*,full_name,email,phone_number,user_location from products join categories on categories.category_id=products.category_id join users on products.user_id=users.user_id where products.is_available=1";
$result = mysqli_query($CON, $sql);

$categories = [];

while ($row = mysqli_fetch_assoc($result)) {
    $categories[] = $row;
}

if ($result) {
    echo json_encode(
        array(
            "success" => true,
            "message" => "Products fetched successfully!",
            "data" => $categories
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
