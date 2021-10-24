<?php
include "../db/db.php";
include "../classes/NewSource.php";
$database = new db();
$db = $database->getConnection();
$source = new NewSource($db);

if(isset($_SERVER['REQUEST_METHOD'] ) == 'POST'){

    if( !empty($_POST['email']) and !empty($_POST['password'])){

        $filterEmail = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
        if(!$filterEmail){
            $array = array(
                'status' => false,
                'message' => "Please enter well formed email address",
                'code' => 400
            );
        }else{
            $params = [
                'email' => $_POST['email'] ,
                'password' => $_POST['password'],
            ];
            $login = $source->login($params);
            $count = mysqli_num_rows($login);
            $data = [];
            if($count>0){
                while ($row = mysqli_fetch_assoc($login)){
                    $data[] = [
                        'name' => $row['name'],
                        'email' => $row['email'],
                        'image' => $row['image'],
                        'balance' => $row['balance'],
                        'parentId' => $row['parent_id'],
                        'token' => $row['token'],
                        'type' => $row['type'],
                    ];
                }
                $array = array(
                    'status' => true,
                    'message' => "Found",
                    'data' => $data[0],
                    'code' => 200
                );
            }else{
                $array = array(
                    'status' => false,
                    'message' => "please enter right credential",
                    'code' => 400
                );
            }
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