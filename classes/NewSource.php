<?php


class NewSource
{
    public $con;
    public function __construct($db)
    {
        $this->con = $db;
    }

    public function lowBalanceMerchants(){
        $query = mysqli_query($this->con, "select * from merchants where balance < 5 and type <> 'admin'");
        $count =mysqli_num_rows($query);
        if($count>0){
            return $query;
        }
        return false;
    }

    public function sendEmail($params,$mail){
        $mail->isSMTP();
        $mail->Host = 'smtp.mailtrap.io';
        $mail->SMTPAuth = true;
        $mail->Username = 'bca74d474e9e48';
        $mail->Password = 'ad814f2ab5eaf7';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 2525;

        $mail->setFrom("support@programmingforce.com", "programming force");
        $mail->addAddress($params['receiverEmail']);
        $mail->addCC($params['cc'], $params['ccName']);
        $mail->addBCC($params['bcc'], $params['bccName']);
        $mail->Subject = $params['subject'];
        $mail->Body    = $params['body'];
        if($mail->send()){
            return true;
        }else{
            return false;
        }
    }
}