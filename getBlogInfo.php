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
$userId = getUserId($token);

$sql = "SELECT blog_id, blog_decscription, blog_date, image_url FROM blog WHERE user_id='$userId'";
$result = mysqli_query($CON, $sql);

if ($result) {
    $rows = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }
    echo json_encode(
        array(
            "success" => true,
            "message" => "Blogs fetched successfully!",
            "data" => $rows
        )
    );
} else {
    echo json_encode(
        array(
            "success" => false,
            "message" => "Fetching blogs failed!"
        )
    );
}
?>

	
