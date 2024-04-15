<?php

include './Helpers/Authenication.php';
include './Helpers/DatabaseConfig.php';

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

    if (!isset($_POST['user_id'])) {
        echo json_encode(array(
            "success" => false,
            "message" => "user id is required!"
        ));
        exit();
    }

    $user_id = $_POST['user_id'];

    // Use prepared statements to prevent SQL injection
    $stmt = $CON->prepare("DELETE FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);

    if ($stmt->execute()) {
        echo json_encode(array(
            "success" => true,
            "message" => "user deleted successfully!"
        ));
    } else {
        echo json_encode(array(
            "success" => false,
            "message" => "user deletion failed!"
        ));
    }
} else {
    echo json_encode(array(
        "success" => false,
        "message" => "Token is required!"
    ));
}
