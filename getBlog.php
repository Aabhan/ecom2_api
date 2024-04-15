<?php
include './Helpers/DatabaseConfig.php';
include './Helpers/Authenication.php';


global $CON;


$sql = "Select blog.*,full_name,email,phone_number from blog join users on blog.user_id=users.user_id ";
$result = mysqli_query($CON, $sql);

$blog = [];

while ($row = mysqli_fetch_assoc($result)) {
    $blog[] = $row;
}

if ($result) {
    echo json_encode(
        array(
            "success" => true,
            "message" => "Blog fetched successfully!",
            "data" => $blog
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
