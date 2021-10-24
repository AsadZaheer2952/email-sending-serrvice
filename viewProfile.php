<?php
include "db/db.php";
include "classes/source.php";
$database = new db();
$db = $database->getConnection();
$source = new source($db);

if(isset($_SERVER['REQUEST_METHOD'] ) == 'GET'){

    if( !empty($_GET['token'])){
        $merchant = $source->checkToken($_GET['token']);
        if($merchant){
            while($row = mysqli_fetch_assoc($merchant)){
                $merchantId = $row['id'];
            }
            $merchantUsers = $source->getProfile($merchantId);
            $merchantData = [];
            if($merchantUsers){
                while ($usersRow = mysqli_fetch_assoc($merchantUsers)){
                    $merchantData[] = [
                        'name' => $usersRow['name'],
                        'email' => $usersRow['email'],
                        'image' => $usersRow['image'],
                        'balance' => $usersRow['balance'],
                        'type' => $row['type'],
                        'createdAt'  => $usersRow['created_at'],
                    ];
                }
                $array = array(
                    'status' => true,
                    'message' => "Found",
                    'data' => $merchantData,
                    'code' => 200
                );
            }else{
                $array = array(
                    'status' => true,
                    'message' => "Found",
                    'data' => $merchantData,
                    'code' => 200
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