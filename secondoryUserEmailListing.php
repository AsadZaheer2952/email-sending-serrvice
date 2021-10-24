<?php
include "db/db.php";
include "classes/source.php";
$database = new db();
$db = $database->getConnection();
$source = new source($db);

if(isset($_SERVER['REQUEST_METHOD'] ) == 'GET'){

    if( !empty($_GET['token'])){
        if(!empty($_GET['merchantId'])){
            $merchant = $source->checkToken($_GET['token']);
            if($merchant){
                while($row = mysqli_fetch_assoc($merchant)){
                    $merchantId = $row['id'];
                }
                $checkParent = $source->checkParent($merchantId,$_GET['merchantId']);
                if($checkParent){
                    $merchantEmails= $source->getMerchantEmails($_GET['merchantId']);
                    $merchantData = [];
                    if($merchantEmails){
                        while ($usersRow = mysqli_fetch_assoc($merchantEmails)){
                            $merchantData[] = [
                                'id' => $usersRow['id'],
                                'receiverEmail' => $usersRow['receiver'],
                                'receiverName' => $usersRow['receiver_name'],
                                'cc' => $usersRow['cc'],
                                'ccName' => $usersRow['cc_name'],
                                'bcc' => $usersRow['bcc'],
                                'bccName'  => $usersRow['bcc_name'],
                                'subject'  => $usersRow['subject'],
                                'body'  => $usersRow['body'],
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
                        'message' => "Selected user is not your secondary user",
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
                'message' => "Merchant id is required",
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