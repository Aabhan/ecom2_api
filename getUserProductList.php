
<?php

include './Helpers/Authenication.php';
include './Helpers/DatabaseConfig.php';


$token = $_POST['token'];
$userId = getUserId($token);
$productList=[];

global $CON; 


    
    $userProductList = getUserProductList($userId);

        if ($userProductList != null) {

            echo json_encode(
                array(
                    "success" => true,
                    "message" => "product Succefully Retrived",
                    "products"=>$userProductList

                )
                
                );
        
        
        }
    



function getUserProductList($userId){
    global $CON;
    // $sql = "select * from product p  join category c on p.category = c.catID where p.userID = '$userID'";
    $sql = "SELECT * from products WHERE user_id = '$userId' AND  is_available = '1' ORDER BY product_id DESC ";

    $result = mysqli_query($CON, $sql);
    $num = mysqli_num_rows($result);

    if ($num == 0) {
        $productList[] = [1,0];
        return $productList;
    }else{
        while ($row = mysqli_fetch_assoc($result)) {
            
            
            $productList[] = $row;


        }

        

        return $productList;
    }




}