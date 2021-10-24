<?php
include "../db/db.php";
include "../classes/source.php";
$database = new db();
$db = $database->getConnection();
$source = new source($db);

if(isset($_SERVER['REQUEST_METHOD'] ) == 'POST'){

    if(!empty($_POST['token']) and !empty($_POST['name'])){
        $admin = $source->checkToken($_POST['token']);
        if($admin ){
                while($row = mysqli_fetch_assoc($admin)) {
                    $type = $row['type'];
                }
                if($type =='admin'){
                    $permission = $source->addPermission($_POST['name']);
                    if($permission){
                        $array = array(
                            'status' => true,
                            'message' => "created successfully",
                            'code' => 200
                        );
                    }else{
                        $array = array(
                            'status' => false,
                            'message' => "Something went wrong please try again",
                            'code' => 400
                        );
                    }
                }else{
                    $array = array(
                        'status' => false,
                        'message' => "Please login as admin",
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
            'message' => "All fields are required",
            'code' => 400
        );
    }
}
echo  json_encode($array);
?>