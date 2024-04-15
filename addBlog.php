<?php
include './Helpers/DatabaseConfig.php';
include './Helpers/Authenication.php';

if (
    isset($_POST['title']) &&
    isset($_POST['blog_description']) &&
    // isset($_POST['blog_date']) &&
    isset($_POST['token']) &&
    isset($_FILES['image'])


   

) {
    global $CON;
    $title = $_POST['title'];
    $token = $_POST['token'];
    $blogDescription = $_POST['blog_description'];
    // $blogDate = $_POST['blog_date'];

    $userId = getUserId($token);
    $image_name = $_FILES['image']['name'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_size = $_FILES['image']['size'];

    if ($image_size > 5000000) {
        echo json_encode(
            array(
                "success" => false,
                "message" => "Image size must be less than 5MB!"
            )
        );
        die();
    }

    $image_new_name = time().'_'.$image_name;

    $upload_path = 'images/'.$image_new_name;

    if ( !move_uploaded_file($image_tmp_name, $upload_path)) {
        echo json_encode(
            array(
                "success" => false,
                "message" => "Image upload failed!"
            )
        );
        die();
    }

    // $sql = "INSERT INTO blog (title, blog_decscription, blog_date, user_id) VALUES ('$title', '$blogDescription','$blogDate','$userId')";
    $sql = "INSERT INTO blog (title, blog_decscription, user_id, image_url) VALUES ('$title', '$blogDescription','$userId','$upload_path')";

    $result = mysqli_query($CON, $sql);

    if ($result) {
        echo json_encode(
            array(
                "success" => true,
                "message" => "Blog added successfully!"
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
} else {
    echo json_encode(
        array(
            "success" => false,
            "message" => "Please fill all the fields!",
            "required fields" => "token, title, Blog description, blog photo"
        )
    );
}