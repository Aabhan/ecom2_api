<?php

include './Helpers/Authenication.php';
include './Helpers/DatabaseConfig.php';

if (

    isset($_POST['token'])

) {
    $token = $_POST['token'];

    $isAdmin = isAdmin($token);


    if (!$isAdmin) {
        echo json_encode(array(
            "success" => false,
            "message" => "You are not authorized!"

        ));
        die();
    }


    global $CON;


    $sql = 'select sum(total) as total_income from orders where status = "paid"';

    $result = mysqli_query($CON, $sql);
    $row = mysqli_fetch_assoc($result);
    $total_income = $row['total_income'];

    $sql = 'select count(*) as total_users from users';
    $result = mysqli_query($CON, $sql);
    $row = mysqli_fetch_assoc($result);
    $total_users = $row['total_users'];

    $sql = 'select count(*) as total_orders from orders';
    $result = mysqli_query($CON, $sql);
    $row = mysqli_fetch_assoc($result);
    $total_orders = $row['total_orders'];

    $sql = 'select count(*) as total_products from products';
    $result = mysqli_query($CON, $sql);
    $row = mysqli_fetch_assoc($result);
    $total_products = $row['total_products'];

    $sql = 'select count(*) as total_blog from blog';
    $result = mysqli_query($CON, $sql);
    $row = mysqli_fetch_assoc($result);
    $total_blog = $row['total_blog'];

    $sql = 'select count(*) as total_donation from donation';
    $result = mysqli_query($CON, $sql);
    $row = mysqli_fetch_assoc($result);
    $total_donation = $row['total_donation'];
    


    if ($result) {


        //top 5 categoris with total income
       // $sql="
        //SELECT categories,sum(amount0 from payments join "


        echo json_encode(array(
            "success" => true,
            "message" => "Stats fetched successfully!",
            "data" => array(
                "total_income" => $total_income,
                "total_users" => $total_users,
                "total_orders" => $total_orders,
                "total_products" => $total_products,
                "total_blog" => $total_blog,
                "total_donation" => $total_donation

            )

        ));
    } else {

        echo json_encode(array(
            "success" => false,
            "message" => "Fetching total income failed!"

        ));
    }
} else {
    echo json_encode(array(
        "success" => false,
        "message" => "Token is required!"

    ));
}