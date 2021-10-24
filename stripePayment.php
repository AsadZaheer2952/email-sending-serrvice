<?php
include "db/db.php";
include "classes/source.php";

//require "vendor/stripe/stripe-php/init.php";
$database = new db();
$db = $database->getConnection();
$source = new source($db);
if(isset($_SERVER['REQUEST_METHOD'] ) == 'POST'){
    if( !empty($_POST['token'])){
        if(!empty($_POST['number']) and !empty($_POST['expMonth']) and !empty($_POST['expYear'])
            and !empty($_POST['cvc']) and !empty('amount')){
            $data =  [
                'card[number]' => $_POST['number'],
                'card[exp_month]' => $_POST['expMonth'],
                'card[exp_year]' => $_POST['expYear'],
                'card[cvc]' => $_POST['cvc'],
            ];
            $merchant = $source->checkToken($_POST['token']);
            if($merchant) {
                while ($row = mysqli_fetch_assoc($merchant)) {
                    $merchantId = $row['id'];
                }
                $stripTokenResponse = $source->getStripeToke($data);
                if($stripTokenResponse){
                    $stripTokenRes = json_decode($stripTokenResponse);
                    $stripToken =  $stripTokenRes->id;
                    $addBalance = $source->charge($stripToken,$_POST['amount']);
                    if($addBalance){
                        $addStripePayment = $source->addPayment($data,$merchantId, $_POST['amount']);
                        if($addStripePayment){
                            $updateBalance = $source->updateBalance( $_POST['amount']/100,$merchantId);
                            if($updateBalance){
                                $array = array(
                                    'status' => true,
                                    'message' => "Your balance update successfully",
                                    'code' => 200
                                );
                            }else{
                                $array = array(
                                    'status' => false,
                                    'message' => "Something went wrong",
                                    'code' => 400
                                );
                            }
                        }else{
                            $array = array(
                                'status' => false,
                                'message' => "Something went wrong",
                                'code' => 400
                            );
                        }
                    }else{
                        $array = array(
                            'status' => false,
                            'message' => "Payment fail, please try again",
                            'code' => 400
                        );
                    }
                }else{
                    $array = array(
                        'status' => false,
                        'message' => "Something went wrong",
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