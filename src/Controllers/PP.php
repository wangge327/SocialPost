<?php
namespace Simcify\Controllers;

use Simcify\Auth;
use Simcify\Database;
use Simcify\Mail;
use Simcify\Models\OrderModel;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;
use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\SandboxEnvironment;

define("PP_Log",$_SERVER['DOCUMENT_ROOT']."/log/pp.log");

class PP extends Checkin {
    // Paypal submit after paying return
    public function submitPaypal() {
        $user = Auth::user();
        $response = json_decode(input("response"), true);
        $price=input("price_total");
        error_log("\n"."PP User_id:".$user->id." ".$user->email." price:".$price."  ".date("Y-m-d H:i:s")."\n", 3,PP_Log);
        error_log(print_r($response,true), 3,PP_Log);

        if ($response['status'] == "COMPLETED"){
            OrderModel::SubmitOrder('Paypal',$response['id'],'PP***');
            $result="$".input("price_total")." was paid successfully in Paypal!";
            $this->CheckinDone(); //if checkin, go to CheckinDone page
        }
        else{
            $result="Failed! ." . input("response");
        }
        $page="confirm.php";
        $parentPage=input("parentPage",'payment'); //  check_pre or payment
        return view($parentPage, compact("user","page","result"));
    }

    public function result() {
        $page="confirm.php";
        $result="Paypal payment success !";
        return view('payment', compact("user","page","result"));
    }

}
