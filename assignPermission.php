<?php
include "db/db.php";
include "classes/source.php";
$database = new db();
$db = $database->getConnection();
$source = new source($db);

if(isset($_SERVER['REQUEST_METHOD'] ) == 'POST'){

    if( !empty($_POST['token'])){
        if(!empty($_POST['merchantId']) and !empty($_POST['permissionId'])) {

            $merchant = $source->checkToken($_POST['token']);
            if ($merchant) {
                while ($row = mysqli_fetch_assoc($merchant)) {
                    $merchantId = $row['id'];
                    $merchantType = $row['type'];
                }
                if ($merchantType == 'user') {
                    $merchantPermission = $source->getUserPermission('assignPermission', $merchantId);
                    if ($merchantPermission) {
                        $assignPermission = $source->assignPermission($_POST['merchantId'], $_POST['permissionId']);
                        if ($assignPermission) {
                            $array = array(
                                'status' => true,
                                'message' => "Permission assign successfully",
                                'code' => 200
                            );
                        } else {
                            $array = array(
                                'status' => false,
                                'message' => "Something went wrong, please try again",
                                'code' => 400
                            );
                        }
                    } else {
                        $array = array(
                            'status' => false,
                            'message' => "you dont have permission",
                            'code' => 400
                        );
                    }
                } else {
                    $assignPermission = $source->assignPermission($_POST['merchantId'], $_POST['permissionId']);
                    if ($assignPermission) {
                        $array = array(
                            'status' => true,
                            'message' => "Permission assign successfully",
                            'code' => 200
                        );
                    } else {
                        $array = array(
                            'status' => false,
                            'message' => "Something went wrong, please try again",
                            'code' => 400
                        );
                    }
                }

            } else {
                $array = array(
                    'status' => false,
                    'message' => "Unauthenticated",
                    'code' => 400
                );
            }
        }else{
            $array = array(
                'status' => false,
                'message' => "All fields are required",
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