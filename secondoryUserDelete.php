<?php
include "db/db.php";
include "classes/source.php";
$database = new db();
$db = $database->getConnection();
$source = new source($db);

if(isset($_SERVER['REQUEST_METHOD'] ) == 'GET'){

    if( !empty($_GET['token']) and !empty($_GET['merchantId'])){
        $merchant = $source->checkToken($_GET['token']);
        if($merchant){
            while($row = mysqli_fetch_assoc($merchant)){
                $merchantId = $row['id'];
            }
            $merchantUser = $source->deleteSelectedUser($merchantId , $_GET['merchantId']);
            $merchantData = [];
            if($merchantUser){
                $array = array(
                    'status' => true,
                    'message' => "delete successfully",
                    'code' => 200
                );
            }else{
                $array = array(
                    'status' => false,
                    'message' => "Something Went Wrong, please try again",
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