<?php
require "vendor/stripe/stripe-php/init.php";

class source
{
    public $con;
    public function __construct($db)
    {
        $this->con = $db;
    }

    public function signup($params){
        $dateTime = date("Y-m-d H:i:s");
        $name = $params['name'];
        $email = $params['email'];
        $password = $params['password'];
        $merchantId = $params['merchantId'];
        $type = $params['type'];
        $str=rand();
        $token = md5($str);
        $signup = mysqli_query($this->con , "insert into merchants(name,email,password,parent_id ,token,type,balance, created_at)
            values('$name','$email','$password',$merchantId ,'$token','$type','0','$dateTime')");
        if($signup){
            return true;
        }
            return false;
    }

    public function login ($params){
        $email = $params['email'];
        $password = $params['password'];
        $login = mysqli_query($this->con , "select * from merchants where email = '$email' and password = '$password'");
        return $login;

    }

    public function checkToken($token){
        $query = mysqli_query($this->con , "select * from merchants where token='$token'");
        $count = mysqli_num_rows($query);
        if($count>0){
            return $query;
        }
        return false;
    }

    public function checkEmailIfExist($email){
        $query = mysqli_query($this->con , "select * from merchants where email='$email'");
        $count = mysqli_num_rows($query);
        if($count>0){
            return false;
        }
            return true;
    }

    public function getMerchantUsers($merchantId){
        $query = mysqli_query($this->con , "select * from merchants where parent_id='$merchantId'");
        $count = mysqli_num_rows($query);
        if($count>0){
            return $query;
        }
        return false;
    }

    public function getSelectedUserProfile($merchantId,$secondoryUserId){
        $query = mysqli_query($this->con , "select * from merchants where parent_id='$merchantId' and id=$secondoryUserId");
        $count = mysqli_num_rows($query);
        if($count>0){
            return $query;
        }
        return false;
    }

    public function getProfile($merchantId){
        $query = mysqli_query($this->con , "select * from merchants where id='$merchantId'");
        $count = mysqli_num_rows($query);
        if($count>0){
            return $query;
        }
        return false;
    }


    public function base64($base64_string) {
        $extension = explode('/', explode(':', substr($base64_string, 0, strpos($base64_string, ';')))[1])[1];   // .jpg .png .pdf
        $replace = substr($base64_string, 0, strpos($base64_string, ',')+1);
        $image = str_replace($replace, '', $base64_string);
        $image = str_replace(' ', '+', $image);
        $fileName = time().'.'.$extension;
        $newImage = file_put_contents('uploads/'.$fileName, base64_decode($image));
        return "uploads/$fileName";
}

    public function updateProfile($params,$merchantId){
        $name = $params['name'];
        $image = $params['image'];
        $query = mysqli_query($this->con , "update merchants set name='$name', image='$image' where id='$merchantId'");
        if($query){
            return true;
        }
        return false;
    }

    public function deleteSelectedUser($merchantId,$secondoryUserId){
        $query = mysqli_query($this->con , "delete from merchants where id='$secondoryUserId' and parent_id='$merchantId'");
        if($query){
            return true;
        }
        return false;
    }

    public function addPermission($name){
        $dateTime = date("Y-m-d H:i:s");
        $query = mysqli_query($this->con , "insert into permissions (name,created_at) values ('$name','$dateTime')");
        if($query){
            return true;
        }
        return false;
    }

    public function allPermissions(){
        $query = mysqli_query($this->con,"select * from permissions");
        $count = mysqli_num_rows($query);
        if($count>0){
            return $query;
        }
        return false;
    }

    public function getUserPermission($permission,$merchantId){
        $query = mysqli_query($this->con , "select * from secondory_user_permissions as sup
                                inner join permissions as p on sup.permission_id=p.id where sup.merchant_id=$merchantId and p.name='$permission'");

        $count = mysqli_num_rows($query);
        if($count>0){
            return $query;
        }
        return false;
    }

    public function assignPermission($merchantId,$permissionId){
        $query = mysqli_query($this->con , "insert into secondory_user_permissions (merchant_id,permission_id) 
                                                values($merchantId,$permissionId)");
        if($query){
            return $query;
        }
        return false;
    }

    public function saveEmailData($params){
        $to = $params['receiverEmail'];
        $merchantId = $params['merchantId'];
        $toName = $params['receiverName'];
        $subject = $params['subject'];
        $body = $params['body'];
        $cc = $params['cc'];
        $ccName = $params['ccName'];
        $bcc = $params['bcc'];
        $bccName = $params['bccName'];
        $query = mysqli_query($this->con , "insert into emails (merchant_id,receiver,receiver_name,cc,cc_name,bcc,bcc_name,subject,body,status) 
                                                values($merchantId , '$to' , '$toName' , '$cc' , '$ccName' , '$bcc' , '$bccName','$subject','$body' , 'pending' ) ");
        if($query){
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

    public function getMerchantEmails($merchantId){
        $query = mysqli_query($this->con,"select * from emails where merchant_id = $merchantId");
        $count = mysqli_num_rows($query);
        if($count>0){
            return $query;
        }
        return false;
    }

    public function checkParent($merchantId,$userId){
        $query = mysqli_query($this->con , "select * from merchants where id=$userId and parent_id=$merchantId");
        $count = mysqli_num_rows($query);
        if($count>0){
            return $query;
        }
        return false;
    }

    public function getStripeToke($data){
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_URL => 'https://api.stripe.com/v1/tokens',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FRESH_CONNECT => true,
            CURLOPT_POSTFIELDS => http_build_query($data),
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer sk_test_ghik78JaiHEiBqqJelsKLfBY00opmgUQT8',
                'Content-type: application/x-www-form-urlencoded',
            ]
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }

    public function charge($token,$amount){
        $stripe = new \Stripe\StripeClient(
            'sk_test_ghik78JaiHEiBqqJelsKLfBY00opmgUQT8'
        );
        $stripe->charges->create([
            'amount' => $amount,
            'currency' => 'usd',
            'source' => $token,
            'description' => 'balance top up',
        ]);

        return $stripe;
    }

    public function addPayment($data,$merchantId,$amount){
        $number = $data['card[number]'];
        $expYear = $data['card[exp_year]'];
        $expMonth = $data['card[exp_month]'];
        $cvc = $data['card[cvc]'];

        $query = mysqli_query($this->con,"insert into payments (merchant_id,number,exp_month,exp_year,cvc,amount)
                                values($merchantId , '$number' , '$expMonth' , '$expYear' , '$cvc' , '$amount')");
        if($query){
            return true;
        }else{
            return false;
        }
    }

    public function updateBalance($amount,$id){
        $select = mysqli_query($this->con,"select * from merchants where id=$id");
        $count =mysqli_num_rows($select);
        if($count>0){
            while($row =mysqli_fetch_assoc($select)){
                $balance = $row['balance'];
            }
            $updatedBalance = $balance + $amount;
            $update = mysqli_query($this->con,"update merchants set balance=$updatedBalance where id=$id");
            if($update){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    public function detectBalance($merchantId , $amount){
        $select = mysqli_query($this->con,"select * from merchants where id=$merchantId");
        $count =mysqli_num_rows($select);
        if($count>0){
            while($row =mysqli_fetch_assoc($select)){
                $balance = $row['balance'];
            }
            $updatedBalance = $balance - $amount;
            $update = mysqli_query($this->con,"update merchants set balance=$updatedBalance where id=$merchantId");
            if($update){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }


}