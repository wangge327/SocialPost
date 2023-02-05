<?php
namespace Simcify\Controllers;

use Simcify\Date;
use Simcify\Models\BalanceModel;
use Simcify\Models\OrderModel;
use Simcify\Models\StudentModel;
use Simcify\Nmi;
use Simcify\Mail;
use Simcify\Auth;
use Simcify\Database;
use Simcify\Models\PaymentModel;

class Payment extends Checkin {
    //when student creating from docu sign email
    public function createPassword() {
        $user = Database::table("users")->find(input("user_id"));
        return view('create_account',
            array("user_id" => input("user_id"),
            "email"=>$user->email,
            "pin"=>$user->pin,
            "lease_end"=>$user->lease_end));
    }
// from student
    public function payment() {
        return $this->showPaymentPage(Auth::user());
    }
// After select credit or paypal from checkin or take payment
    public function submitPaymentMode() {
        if (input("payment_option","notCheckin")=="checkin"){  //from checkin page
            $price_total=input("price_total");
            $parentPage='payment/checkin_pre';
            $page=input("payment_type","Cash");
            if($page=="Cash"){
                $page="confirm.php";
                $result="To make your payment with cash,please see cashier at window!";
                $checkin_done  = "done";
                StudentModel::SetCheckin(input("payment_user_id"));
                return view("payment/checkin_pre", compact("checkin_done", "page", "result"));
            }
            else{ // $page == "Credit Card" and Paypal
            	$payment_user = Database::table("users")->find(input("payment_user_id"));
            	$price_total = $price_total;
				return view('payment/checkin_pre', compact("page","price_total","payment_user", "parentPage"));
			}
        }
        else  //from take payment
            return $this->showPaymentPage(Database::table("users")->find(input("payment_user_id")));
    }
//by admin
    public function take_payment() {
        return $this->showPaymentPage(Database::table("users")->find(input("customerid")));
    }

    public function showPaymentPage($payment_user) {  //show current payment page or submit payment by credit, check, cash
        $page = input("payment_type","select");
        $user = Auth::user();
        $order = OrderModel::Get($payment_user);
        $price_total = PaymentModel::getPriceBalance($payment_user);
        $parentPage="payment";
        $student_room_fee = PaymentModel::getStudentRoomFee($payment_user);

        if ($page == 'select'){ // take payment by admin. or .. ?
            $employer = Database::table("employers")->where("id", $payment_user->employer)->first();
            $companies = Database::table("companies")->where("id", Auth::user()->company)->first();
            $student_price_balance = PaymentModel::getPriceBalance($payment_user);

            $student=$payment_user;
            return view('payment', compact("user","page", "price_total", "order","payment_user","parentPage","employer","companies", "student_room_fee", "student_price_balance","student"));
        }
        else{  //cash or check, credit card, Holding
            if ($page=='Cash' || $page=='Check' || $page=='Holding'){
                OrderModel::SubmitOrder($page,"T_".$page,'******'.$page);
                $this->CheckinDone(); //if checkin, go to CheckinDone page
                $result="$".input("price_total")." was paid successfully by ".$page."!";
                $page="confirm.php";
                $payment_user=Database::table("users")->find(input("payment_user_id"));
                $student=$payment_user;
                return view('payment', compact("user","page","result","payment_user","student"));

            }
            elseif($page == "Subscribe"){
                $company = Database::table("companies")->where("id" , 1)->first();
                $company_weekly_fee = $company->weekly;
                $gw = new Nmi();
                $gw->setLogin();

                // Create Default PlanID if no in db
                $nmi_db_default = Database::table("nmi_plan")->where("plan_id" , env("NMI_PLAN_DEFAULT_ID"))->first();
                if(empty($nmi_db_default)){
                    $plan_name = "Weekly Room Rate Subscription for Default";
                    $gw->addPlan($company_weekly_fee,env("NMI_PLAN_DEFAULT_ID"), $plan_name);
                    $response = $gw->responses;
                    if($response['response'] == 1){
                        $plan = array(
                            "plan_id" => env("NMI_PLAN_DEFAULT_ID"),
                            "amount" => $company_weekly_fee,
                        );
                        Database::table("nmi_plan")->insert($plan);
                    }
                    else{
                        $result="Failed! .".$response['responsetext'];
                        print_r($result);
                        exit(0);
                    }
                }
                $plan_id = env("NMI_PLAN_DEFAULT_ID");

                $weekly_amount = $student_room_fee['week_fee'];
                if($company_weekly_fee != $weekly_amount){
                    $plan_id = env("NMI_PLAN_PREFIX")."_".$payment_user->id."_plan";
                    $plan_name = "Weekly Room Rate Subscription for ". $payment_user->fname;

                    $nmi_db = Database::table("nmi_plan")->where("plan_id" , $plan_id)->first();

                    if(empty($nmi_db)){
                        $gw->addPlan($weekly_amount,$plan_id, $plan_name);
                        $response = $gw->responses;

                        if($response['response'] == 1){
                            $invoice = array(
                                "plan_id" => $plan_id,
                                "amount" => $weekly_amount,
                            );
                            Database::table("nmi_plan")->insert($invoice);
                        }
                        else{
                            $result="Failed! .".$response['responsetext'];
                            print_r($result);
                            exit(0);
                        }
                    }
                }
                $student=$payment_user;
                return view('payment', compact("user","page","price_total","payment_user","parentPage", "weekly_amount","student","plan_id"));
            }
            elseif($page == "Unsubscribe"){
                $gw = new Nmi();
                $gw->setLogin();

                $gw->deleteSubscription($payment_user->subscription_id);
                $response = $gw->responses;
                if($response['response'] == 1){
                    $s = array(
                        "subscription_id" => "",
                        "is_subscribe" => 0,
                        "nmi_plan_id" => 0,
                    );
                    StudentModel::Update($payment_user->id, $s);
                    $result="Subscription Success Canceled!";
                }
                else {
                    $result = "Failed! ." . $response['responsetext'];
                }
                $page="confirm.php";
                $parentPage=input("parentPage",'payment'); //  check_pre or payment

                return view($parentPage, compact("user","page","result", "payment_user"));
            }
            $price_total=input("price_total");
            $student=$payment_user;
            return view('payment', compact("user","page","price_total","payment_user","parentPage","student"));
        }
    }

    function UpdatePlanNMI($mni_plan, $weekly_amount){
        $gw = new Nmi();
        $gw->setLogin();
        $gw->editPlan($mni_plan , $weekly_amount);
        $response = $gw->responses;
        if($response['response'] == 1){
            $nmi = array(
                "amount" => $weekly_amount,
            );
            Database::table("nmi_plan")->where("plan_id" , $mni_plan)->update($nmi);
        }
    }

    public function history() {
        $user = Auth::user();
        $invoices = Database::table("invoices");
        if ($user->role == "user") {
            $invoices =$invoices->where("student_id", $user->id);
        }
        $invoices =$invoices->get();
        return view('payment/history', compact("user", "invoices"));
    }

    public function checkin(){
    	$checkin_token = $_REQUEST["token"];

    	if(isset($_REQUEST["lname"])){
			$current_user = Database::table("users")->where("lname", $_REQUEST["lname"])->where("pin", $_REQUEST["pin"])->first();
			if(empty($current_user))
				echo "-1";
			else
				print_r($current_user->id);
			exit(0);
		}
		if(isset($_REQUEST["reset_pin"])){
			$reset_pin = rand(100000, 999999);
			$current_user = Database::table("users")->where("lname", $_REQUEST["reset_lname"])->where("email", $_REQUEST["reset_email"])->first();
			if(empty($current_user))
				echo "-1";
			else{
				$user = Database::table("users")->where("token", $_REQUEST["token"])->first();
				Mail::send(
	                $user->email, "Pin Changed",
	                array(
	                    "title" => "Pin Changed",
	                    "subtitle" => "Please review your new Pin",
	                    "buttonText" => "Check-in now",
	                    "message" => "Your new Pin code is ". $reset_pin
	                )
	            );
	            
				$data = array('pin' => $reset_pin);
	            Database::table('users')->where("token", $_REQUEST["token"])->update($data);
				echo "1";
			}
			exit(0);
		}
		if(isset($_REQUEST["uid"])){
			$current_user = Database::table("users")->find($_REQUEST["uid"]);
			$user=$current_user;
            $order = OrderModel::Get($current_user);
            $owed_price = PaymentModel::getPriceBalance($current_user);
			return view('payment/checkin', compact("current_user", "owed_price", "order","user"));
		}			
        else{
        	return view('payment/checkin_pre', compact("checkin_token"));
		}	      
    }
    
    public function status(){
		$user = Auth::user();
        $invoices = Database::table("invoices");
        if ($user->role == "user") {
            $invoices =$invoices->where("student_id", $user->id);
        }
        $invoices =$invoices->get();
        return view('payment/status', compact("user", "invoices"));
	}
}
