
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include './Helpers/DatabaseConfig.php';
include './Helpers/Authenication.php';

if (
    isset($_POST['title']) &&
    isset($_POST['description']) &&
    isset($_POST['price']) &&
    isset($_POST['category_id']) &&
    isset($_POST['token']) &&
    isset($_POST['is_old']) &&
    isset($_POST['is_negotiable']) &&
    isset($_FILES['image'])

) {
    global $CON;
    $title = $_POST['title'];
    $token = $_POST['token'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $category = $_POST['category_id'];
    $isOld = $_POST['is_old'];
    $isNegotiable = $_POST['is_negotiable'];
    
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



    $sql = "INSERT INTO products (title, description, price, category_id, image_url, is_old, is_negotiable, user_id) VALUES ('$title', '$description', '$price', '$category', '$upload_path', '$isOld','$isNegotiable','$userId')";
    $result = mysqli_query($CON, $sql);

    if ($result) {
        echo json_encode(
            array(
                "success" => true,
                "message" => "Product added successfully!"
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
            "required fields" => "token, title, description, price, category, image"
        )
    );
}
