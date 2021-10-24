<?php
include "db/db.php";
include "classes/source.php";
$database = new db();
$db = $database->getConnection();
$source = new source($db);

if(isset($_SERVER['REQUEST_METHOD'] ) == 'POST'){

    if( !empty($_POST['token']) and !empty($_POST['name']) and !empty($_POST['email']) and !empty($_POST['password']) and !empty($_POST['confirmPassword'])){
        $merchant = $source->checkToken($_POST['token']);
        if($merchant){
            while($row = mysqli_fetch_assoc($merchant)){
                $merchantId = $row['id'];
            }
            $name = $_POST['name'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $confirmPassword = $_POST['confirmPassword'];
            $params = [
                'name' => $_POST['name'],
                'email' => $_POST['email'],
                'password' => $_POST['password'],
                'merchantId' => $merchantId,
                'type' => 'user'
            ];
            $checkEmail = $source->checkEmailIfExist($email);
            if(!$checkEmail){
                $array = array(
                    'status' => false,
                    'message' => "Email already exists, please try another.",
                    'code' => 400
                );
            }else{
                if($password == $confirmPassword){
                    $signup = $source->signup($params);
                    if($signup){
                        $array = array(
                            'status' => true,
                            'message' => "Registered successfully",
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
                        'message' => "password does not match",
                        'code' => 400
                    );
                }

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