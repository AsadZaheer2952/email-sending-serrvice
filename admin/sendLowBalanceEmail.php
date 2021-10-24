<?php

include "../db/db.php";
include "../classes/NewSource.php";
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require '../vendor/autoload.php';

$database = new db();
$db = $database->getConnection();
$newSource = new NewSource($db);
$mail = new PHPMailer();
$merchants = $newSource->lowBalanceMerchants();
if($merchants){
    while ($row = mysqli_fetch_assoc($merchants)){
        $params = [
            'receiverEmail' => $row['email'],
            'subject' => "Sending Low Balance Email",
            'body' => "Your balance is low , please top up your balance",
            'cc' => '',
            'ccName' => '',
            'bcc' => '',
            'bccName' => ','
        ];
        $newSource->sendEmail($params,$mail);
    }
    $array = array(
        'status' => false,
        'message' => "Successfully send emails",
        'code' => 400
    );
}else{
    $array = array(
        'status' => false,
        'message' => "No Merchant find with low balance",
        'code' => 400
    );
}
echo json_encode($array)
?>