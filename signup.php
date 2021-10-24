<?php
include "db/db.php";
include "classes/source.php";
$database = new db();
$db = $database->getConnection();
$source = new source($db);

if(isset($_SERVER['REQUEST_METHOD'] ) == 'POST'){
    if(!empty($_POST['name']) and !empty($_POST['email']) and !empty($_POST['password']) and !empty($_POST['confirmPassword'])){
        $filterEmail = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
        if(!$filterEmail) {
            $array = array(
                'status' => false,
                'message' => "Please enter well formed email address",
                'code' => 400
            );
        }else{
            $name = $_POST['name'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $confirmPassword = $_POST['confirmPassword'];
            $merchantId = $_POST['merchantId'] ? $_POST['merchantId'] : 0;
            $params = [
                'name' => $_POST['name'],
                'email' => $_POST['email'],
                'password' => $_POST['password'],
                'merchantId' => $_POST['merchantId'],
                'type' => 'merchant'
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