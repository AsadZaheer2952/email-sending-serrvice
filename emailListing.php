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
            $merchantEmails= $source->getMerchantEmails($merchantId);
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