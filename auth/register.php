<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


include '../Helpers/DatabaseConfig.php';

if (
    isset($_POST['fullname']) &&
    isset($_POST['email']) &&
    isset($_POST['password']) &&
    isset($_POST['phone_number'])&&
    isset($_POST['user_location'])

   



) {
    global $CON;
    $email = $_POST['email'];
    $fullname = $_POST['fullname'];
    $password = $_POST['password'];
    $phoneNumber = $_POST['phone_number'];
    $userLocation = $_POST['user_location'];
    

    $sql = "Select * from users where email ='$email'";
    $result = mysqli_query($CON, $sql);
    $num = mysqli_num_rows($result);
    if ($num > 0) {
        echo json_encode(
            array(
                "success" => false,
                "message" => "Email already exists!"
            )
        );
        return;
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (full_name, email, password,role,phone_number,user_location) VALUES ('$fullname', '$email', '$hashed_password','user','$phoneNumber','$userLocation')";
        $result = mysqli_query($CON, $sql);

        if ($result) {
            echo json_encode(
                array(
                    "success" => true,
                    "message" => "User registered successfully!"
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
    }
} else {
    echo json_encode(
        array(
            "success" => false,
            "message" => "Please fill all the fields!",
            "required fields" => "fullname, email, password"
        )
    );
}
