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
            $permissions= $source->allPermissions();
            $allPermissions = [];
            if($permissions){
                while ($row = mysqli_fetch_assoc($permissions)){
                    $allPermissions[] = [
                        'id' => $row['id'],
                        'name' => $row['name'],
                        'createdAt'  => $row['created_at'],
                    ];
                }
                $array = array(
                    'status' => true,
                    'message' => "Found",
                    'data' => $allPermissions,
                    'code' => 200
                );
            }else{
                $array = array(
                    'status' => true,
                    'message' => "Found",
                    'data' => $allPermissions,
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