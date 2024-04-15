<?php

include './Helpers/DatabaseConfig.php';
include './Helpers/Authenication.php';

// Check if token is provided
if (!isset($_POST['token'])) {
    echo json_encode(array(
        "success" => false,
        "message" => "Token is required"
    ));
    die();
}

$token = $_POST['token'];

$userId = getUserId($token);

// Check if token is valid
if (!$userId) {
    echo json_encode(array(
        "success" => false,
        "message" => "Invalid token"
    ));
    die();
}

// Check if required fields are provided
if (isset($_POST['full_name'], $_POST['phone_number'])) {

    $full_name = $_POST['full_name'];
    $phoneNumber = $_POST['phone_number'];

    // Use prepared statements to prevent SQL injection
    $sql = "UPDATE users SET full_name = ?, phone_number = ? WHERE user_id = ?";
    $stmt = mysqli_prepare($CON, $sql);

    // Bind parameters and execute the statement
    mysqli_stmt_bind_param($stmt, "ssi", $full_name, $phoneNumber, $userId);
    $result = mysqli_stmt_execute($stmt);

    // Check for query execution success
    if ($result) {
        echo json_encode(array(
            "success" => true,
            "message" => "Profile updated successfully"
        ));
    } else {
        echo json_encode(array(
            "success" => false,
            "message" => "Profile update failed"
        ));
    }

    // Close statement
    mysqli_stmt_close($stmt);

} else {
    echo json_encode(array(
        "success" => false,
        "message" => "full_name and phone number are required",
    ));
}
?>
