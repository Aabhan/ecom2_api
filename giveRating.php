<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


include './Helpers/DatabaseConfig.php';
include './Helpers/Authenication.php';



if (!isset($_POST['token'])) {
    echo json_encode(array(
        "success" => false,
        "message" => "Token is required"
    ));
    die();
}

$token = $_POST['token'];

$userId = getUserId($token);

if (!$userId) {
    echo json_encode(array(
        "success" => false,
        "message" => "Invalid token"
    ));
    die();
}


if (isset(
    $_POST['product_id'],
    $_POST['rating'],
    $_POST['reviews']


)) {

    $product_id = $_POST['product_id'];
    $rating = $_POST['rating'];
    $reviews = $_POST['reviews'];


    $sql = "select * from ratings where product_id = $product_id AND user_id = $userId";

    $result = mysqli_query($CON, $sql);


    $rating_id = null;

    if (mysqli_num_rows($result) > 0) {
        $ratingData = mysqli_fetch_assoc($result);
        $rating_id = $ratingData['rating_id'];
    }

    $sql = '';

    if ($rating_id != null) {
        $sql = "UPDATE ratings SET rating = '$rating', reviews = '$reviews' WHERE rating_id = $rating_id";
    } else {
        $sql = "INSERT INTO ratings (user_id, product_id, rating, reviews) VALUES ($userId, $product_id, '$rating', '$reviews')";
    }

    $result = mysqli_query($CON, $sql);


    if ($result) {
        echo json_encode(array(
            "success" => true,
            "message" => "Rating added successfully"
        ));


        $sql = "UPDATE products SET rating = (SELECT AVG(rating) FROM ratings WHERE product_id = $product_id) WHERE product_id = $product_id";
        $result = mysqli_query($CON, $sql);
        die();
    }

    echo json_encode(array(
        "success" => false,
        "message" => "Failed to add rating"
    ));
} else {
    echo json_encode(array(
        "success" => false,
        "message" => "product_id and rating are required"
    ));
    die();
}
