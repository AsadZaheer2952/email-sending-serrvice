<?php
include "db/db.php";
include "classes/source.php";
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php';
$database = new db();
$db = $database->getConnection();
$source = new source($db);
$mail = new PHPMailer();

if(isset($_SERVER['REQUEST_METHOD'] ) == 'POST'){
    if( !empty($_POST['token'])){
        if(!empty($_POST['receiver']) and !empty($_POST['receiverName']) and !empty($_POST['subject']) and !empty($_POST['body'])) {
            $cc = isset($_POST['cc']) ? $_POST['cc'] : '';
            $ccName = isset($_POST['ccName']) ? $_POST['ccName'] : '';
            $bcc = isset($_POST['bcc']) ? $_POST['bcc'] : '';
            $bccName = isset($_POST['bccName']) ? $_POST['bccName'] : '';
            $merchant = $source->checkToken($_POST['token']);
            if ($merchant) {
                while ($row = mysqli_fetch_assoc($merchant)) {
                    $merchantId = $row['id'];
                    $merchantType = $row['type'];
                    $balance = $row['balance'];
                }
                if($balance >= 0.0489){
                    $params = [
                        'merchantId' => $merchantId,
                        'receiverEmail' => $_POST['receiver'],
                        'receiverName' => $_POST['receiverName'],
                        'subject' => $_POST['subject'],
                        'body' => $_POST['body'],
                        'cc' => $cc,
                        'ccName' => $ccName,
                        'bcc' => $bcc,
                        'bccName' => $bccName,
                    ];

                    $email = $source->saveEmailData($params);
                    if($email){
                        $sendEmail = $source->sendEmail($params,$mail);
                        if($sendEmail){
                            $detectBalance = $source->detectBalance($merchantId , 0.0489);
                            $array = array(
                                'status' => true,
                                'message' => "Email send successfully",
                                'code' => 200
                            );
                        }else{
                            $array = array(
                                'status' => false,
                                'message' => "Email not send, please try again",
                                'code' => 400
                            );
                        }

                    }else{
                        $array = array(
                            'status' => false,
                            'message' => "Something went wrong, please try again",
                            'code' => 400
                        );
                    }
                }else{
                    $array = array(
                        'status' => false,
                        'message' => "You have a low balance",
                        'code' => 400
                    );
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
                'message' => "Receiver email , Receiver name, subject , body fields are required",
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
echo json_encode($array);

?>