<?php
include './Helpers/DatabaseConfig.php';
include './Helpers/Authenication.php';

if (
    isset($_POST['title']) &&
    isset($_POST['description']) &&
    isset($_POST['token']) &&
    isset($_FILES['image'])

) {
    global $CON;
    $title = $_POST['title'];
    $token = $_POST['token'];
    $description = $_POST['description'];
    $userId = getUserId($token);


    // $checkAdmin = isAdmin($token);

    // if (!$checkAdmin) {
    //     echo json_encode(
    //         array(
    //             "success" => false,
    //             "message" => "You are not authorized!"
    //         )
    //     );
    //     die();
    // }

    $image_name = $_FILES['image']['name'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_size = $_FILES['image']['size'];
    // $image_extension = pathinfo($image_name, PATHINFO_EXTENSION);

    // if($image_extension != 'jpg' && $image_extension != 'jpeg' && $image_extension != 'png'){
    //     echo json_encode(
    //         array(
    //             "success" => false,
    //             "message" => "Image extension not allowed!"
    //         )
    //     );
    //     die();
    // }

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
    
//     try {
//         move_uploaded_file($image_tmp_name, $upload_path);
        
//     } catch (e) {
// print(e);    }

    if ( !move_uploaded_file($image_tmp_name, $upload_path)) {
        echo json_encode(
            array(
                "success" => false,
                "message" => "Image upload failed!"
            )
        );
        die();
    }



    $sql = "INSERT INTO donation (title, description, image_url,user_id) VALUES ('$title', '$description', '$upload_path','$userId')";
    $result = mysqli_query($CON, $sql);

    if ($result) {
        echo json_encode(
            array(
                "success" => true,
                "message" => "Thank you for Donating!"
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
            "required fields" => "token, title, description,image"
        )
    );
}
