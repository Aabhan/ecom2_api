<?php
include './Helpers/DatabaseConfig.php';
include './Helpers/Authenication.php';

global $CON;

$sql = "SELECT * FROM donation";
$result = mysqli_query($CON, $sql);

if (!$result) {
    // Handle query execution error
    echo json_encode(
        array(
            "success" => false,
            "message" => "Error executing query: " . mysqli_error($CON)
        )
    );
    exit; // Stop further execution
}

$donations = [];
while ($row = mysqli_fetch_assoc($result)) {
    $donations[] = $row;
}

echo json_encode(
    array(
        "success" => true,
        "message" => "Donated books fetched successfully!",
        "data" => $donations
    )
);
?>
