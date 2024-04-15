<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


include './Helpers/DatabaseConfig.php';
include './Helpers/Authenication.php';

if (isset($_POST['token'])) {
    $token = $_POST['token'];
    $isAdmin = isAdmin($token);

    if (!$isAdmin) {
        echo json_encode(array(
            "success" => false,
            "message" => "You are not authorized!"
        ));
        exit();
    }

    if (!isset($_POST['blog_id'])) {
        echo json_encode(array(
            "success" => false,
            "message" => "Blog id is required!"
        ));
        exit();
    }

    $blog_id = $_POST['blog_id'];

    // Use prepared statements to prevent SQL injection
    $stmt = $CON->prepare("DELETE FROM blog WHERE blog_id = ?");
    $stmt->bind_param("i", $blog_id);

    if ($stmt->execute()) {
        echo json_encode(array(
            "success" => true,
            "message" => "Blog deleted successfully!"
        ));
    } else {
        echo json_encode(array(
            "success" => false,
            "message" => "Blog deletion failed! Error: " . $CON->error
        ));
    }
} else {
    echo json_encode(array(
        "success" => false,
        "message" => "Token is required!"
    ));
}
