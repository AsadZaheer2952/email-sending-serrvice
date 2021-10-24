<?php
include "db/db.php";
include "classes/source.php";
$database = new db();
$db = $database->getConnection();
$source = new source($db);

if(isset($_SERVER['REQUEST_METHOD'] ) == 'POST'){

    if( !empty($_POST['token']) ){
        if(isset($_POST['image'])){
            $image = $source->base64($_POST['image']);
        }else{
            $image = null;
        }
        $merchant = $source->checkToken($_POST['token']);
        if($merchant) {
            while ($row = mysqli_fetch_assoc($merchant)) {
                $merchantId = $row['id'];
            }
            $name = $_POST['name'];
            $params = [
                'name' => $_POST['name'],
                'image' => $image,
            ];

            $signup = $source->updateProfile($params , $merchantId);
            if ($signup) {
                $array = array(
                    'status' => true,
                    'message' => "update successfully",
                    'code' => 200
                );
            } else {
                $array = array(
                    'status' => false,
                    'message' => "Something went wrong please try again",
                    'code' => 400
                );
            }

        }else{
            $array = array(
                'status' => false,
                'message' => "Unauthenticated",
                'code' => 400
            );
        }

    }else{
        $array = array(
            'status' => false,
            'message' => "Token not passed",
            'code' => 400
        );
    }
}
echo  json_encode($array);
?>