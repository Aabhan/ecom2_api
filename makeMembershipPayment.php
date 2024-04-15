<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


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

if (!isset($_POST['token']) ||!isset($_POST['amount']) || !isset($_POST['other_data'])) {
    echo json_encode(
        array(
            "success" => false,
            "message" => "token, amount and other_data is is required!"
        )
    );
    die();
}

global $CON;
$token = $_POST['token'];
$amount = $_POST['amount'];
$other_data = $_POST['other_data'];
$userId = getUserId($token);


$sql = "INSERT INTO membershipPayment (user_id, amount, other_data) VALUES ('$userId','$amount','$other_data')";

$result = mysqli_query($CON, $sql);

if ($result) {
    $sql = "UPDATE users SET is_member = '1' WHERE user_id = '$userId'";


    $result = mysqli_query($CON, $sql);


    if ($result) {
        echo json_encode(
            array(
                "success" => true,
                "message" => "Payment added  successfully!"
            )
        );
    } else {
        echo json_encode(
            array(
                "success" => false,
                "message" => "Updating user status failed!"
            )
        );
    }
} else {
    echo json_encode(
        array(
            "success" => false,
            "message" => "Creating payment failed!"
        )
    );
}