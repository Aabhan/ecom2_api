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

    if (!isset($_POST['category_id'])) {
        echo json_encode(array(
            "success" => false,
            "message" => "Category id is required!"
        ));
        exit();
    }

    $category_id = $_POST['category_id'];

    // Use prepared statements to prevent SQL injection
    $stmt = $CON->prepare("DELETE FROM categories WHERE category_id = ?");
    $stmt->bind_param("i", $category_id);

    if ($stmt->execute()) {
        echo json_encode(array(
            "success" => true,
            "message" => "Category deleted successfully!"
        ));
    } else {
        echo json_encode(array(
            "success" => false,
            "message" => "Category deletion failed!"
        ));
    }
} else {
    echo json_encode(array(
        "success" => false,
        "message" => "Token is required!"
    ));
}
