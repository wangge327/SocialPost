<?php
namespace Simcify\Controllers;

use Simcify\Auth;
use Simcify\Database;
use Simcify\Mail;
use Simcify\Models\CheckoutModel;
use Simcify\Models\OrderModel;
use Simcify\Models\StudentModel;
use Simcify\Nmi;

define("CC_Log",$_SERVER['DOCUMENT_ROOT']."/log/cc_error.log");
define("Payment_Log",$_SERVER['DOCUMENT_ROOT']."/log/payment.log");

class CC extends Checkin {
    public function submitCard() {
        $user_id=input("payment_user_id");
        $payment_type=input('payment_type',"Credit Card"); //Credit Card or Subscribe

        $payment_user= Database::table("users")->find($user_id);
        $user = Auth::user();

        $gw = new Nmi();
        $gw->setLogin();
        $gw->billing['firstname'] = input("fname");
        $gw->billing['lastname'] = input("lname");
        $gw->billing['address1'] = input("address");
        $gw->billing['city'] = input("city");
        $gw->billing['state'] = input("state");
        $gw->billing['zip'] = input("zip");
        $gw->billing['country'] = input("country");
        $gw->billing['phone'] = input("phone");
        $gw->billing['email'] = input("email");

        $title="Payment of ".$payment_user->fname.' '.$payment_user->lname;
        $p_order_id = rand(5,100)."-".rand(1,5000);
        $gw->setOrder($p_order_id, $title, 1, 1, $payment_user->phone, $_SERVER['REMOTE_ADDR']);
        $ccexp = $_POST['ccexpmm'] . $_POST['ccexpyy']; //"1010"  //999
        if ($payment_type=="Credit Card"){
            $price=input("price_total");
            $r = $gw->doSale($price, $_POST['ccnumber'], $ccexp, $_POST['cvv']);
        }
        else{
            $price=input("weekly_amount");
            // Get Plan ID
            $plan_id = input("plan_id");
            $r = $gw->addSubscription($plan_id, $_POST['ccnumber'], $ccexp );
        }

        $response = $gw->responses;

        error_log("\n".$payment_type." User_id:".$payment_user->id." ".$payment_user->email." price:".$price." ".date("Y-m-d H:i:s")."\n", 3,Payment_Log);
        error_log(print_r($response,true), 3,Payment_Log);
        error_log(print_r($_POST,true), 3,CC_Log);
        if ($response['response']==1){
            $cardNumber=str_pad(substr($_POST['ccnumber'], -4), strlen($_POST['ccnumber']), '*', STR_PAD_LEFT);
            if (input('payment_type',"Checkout")=='Checkout'){
                CheckoutModel::AdditionalPayment($payment_user,$price,$response['transactionid'],$cardNumber);
            }
            else{
                OrderModel::SubmitOrder($payment_type,$response['transactionid'],$cardNumber);

                if ($payment_type=="Subscribe"){
                    // Save subscribe ID
                    $subscribe_id = $response['subscription_id'];
                    $nmi_plan_id= Database::table("nmi_plan")->where("plan_id" , $plan_id)->first();

                    $s = array(
                        "subscription_id" => $subscribe_id,
                        "is_subscribe" => 1,
                        "nmi_plan_id" => $nmi_plan_id->id,
                    );
                    StudentModel::Update($user_id,$s);

                    $invoice = Database::table("invoices")->where("student_id" , $user_id)->last();
                    Database::table("invoices")->where("id" , $invoice->id)->update(array('nmi_plan_id' => $plan_id));
                }
                $this->CheckinDone(); //if checkin, go to CheckinDone page
            }
            $result="$".$price." was paid successfully by ".$payment_type.". Transaction Id is ".$response['transactionid'];
        }
        else{
            $result="Failed! .Response is ".$response['responsetext'];
        }

        $page="confirm.php";
        $parentPage=input("parentPage",'payment'); //  check_pre or payment
        $payment_user= Database::table("users")->find($user_id);
        $student=$payment_user;
        return view($parentPage, compact("user","page","result","payment_user","student"));
    }

    public function refundInvoice() {
        $invoice_id=input("invoice_id");
        $invoice= Database::table("invoices")->where("id", $invoice_id)->first();
        $student= Database::table("users")->find($invoice->student_id);

        if ($invoice->payment_mode=="Credit Card"){
            CC::Refund($invoice,$invoice->price);
        }

        Mail::send(
            $student->email, "Hiawatha Student Housing Payment",
            array(
                "title" => "Your payment was refunded",
                "subtitle" => "Click the link below to check the payment history.",
                "buttonText" => "Payment History",
                "buttonLink" => env("APP_URL")."/history",
                "message" => $invoice->payment_mode." payment $".$invoice->price." has been refunded by Hiawatha inc.<br> Thank you<br>".env("APP_NAME")." Team"
            ),
            "withbutton"
        );
        OrderModel::Refund($invoice_id);
        header('Content-type: application/json');
        exit(json_encode(responder("success", "Refunded!", $invoice->payment_mode." Payment ".$invoice->price." was successfully Refunded.","reload()")));
    }

    Static public function Refund($invoice,$price) {
        $student= Database::table("users")->find($invoice->student_id);
        $gw = new Nmi();
        $gw->setLogin();
        $gw->doRefund($invoice->transaction_id,$price);
        $response = $gw->responses;
        error_log(print_r($response,true), 3,Payment_Log);
        if ($response['response']==1){
            //1 = Transaction Approved,2 = Transaction Declined,3 = Error in transaction data or system error
            Database::table("invoices")->where("id" , $invoice->id)->update(array('status' => 'Refunded'));
            Mail::send(
                $student->email, "Hiawatha Student Housing Payment",
                array(
                    "title" => "Credit Card Payment was refunded",
                    "subtitle" => "Click the link below to check the payment history.",
                    "buttonText" => "Payment History",
                    "buttonLink" => env("APP_URL")."/history",
                    "message" => "Credit Card payment $".$price." has been refunded by Hiawatha inc.<br> Thank you<br>".env("APP_NAME")." Team"
                ),
                "withbutton"
            );
        }
        return $response;
    }
}
