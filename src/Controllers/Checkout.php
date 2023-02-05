<?php
namespace Simcify\Controllers;

use Simcify\Models\BalanceModel;
use Simcify\Models\CheckoutModel;
use Simcify\Models\InvoiceModel;
use Simcify\Models\OrderModel;
use Simcify\Models\RoomModel;
use Simcify\Models\StudentModel;
use Simcify\Auth;
use Simcify\Database;

class Checkout{
    public function remaining_balance($user_id){
        $user = Auth::user();
        $student = Database::table("users")->find($user_id);
        $data = $this->getCheckoutHistoryData($student);
        $order = OrderModel::Get($student);
        $security_deposit_history = Database::table("invoices")->where("student_id", $student->id)->where("security_paid", ">", "0")->get();
        $refund_deposit_history = Database::table("invoices")->where("student_id", $student->id)->where("status", "Refunded")->get();

        $data["rem_bal"] = $order->security_fee - $student->balance;
        $data["totalFineFee"] = $data['total_fine'];
        $data['security_deposit_history'] = $security_deposit_history;
        $data['refund_deposit_history'] = $refund_deposit_history;
        $data['user'] = $user;
        $data['student'] = $student;
        $data['order'] = $order;
        $user->page_title = "Final checkout";
        Customer::addActionLog("Student", "remain balance", "ID:".$student->id." ".$student->fname." ". $student->lname." remain balance:$".$data["rem_bal"]);
        return view('customer/checkout', $data);
    }

    public function addtionalPayment(){
        $student = Database::table("users")->find(input("studentid"));
        $order = Database::table("orders")->where("student_id", $student->id)->last();
        $data["amount"] = $student->balance - $order->security_fee;
        $data['student'] = $student;
        $data['order'] = $order;

        return view('customer/addtional_payment', $data);
    }

    public function updateAddtionalPayment(){
        header('Content-type: application/json');
        $user = Database::table("users")->find(input("user_id"));
        CheckoutModel::AdditionalPayment($user,input("amount"),"T_Cash",'C******');
        exit(json_encode(responder("success", "Additional Payment" ,"Additional Payment completed!","reload()")));
    }

    public function addtionalPaymentCreditCard(){
        $data['user'] = Auth::user();
        $data['payment_user'] = Database::table("users")->find(input("user_id"));
        $data["price_total"] = input("amount");

        return view('customer/addtional_payment_creditcard', $data);
    }

    public function refundRemainingBalance(){
        header('Content-type: application/json');
        $user = Database::table("users")->find(input("user_id"));
        $order =OrderModel::Get($user);

        $return_balance = input("return_balance");

        $invoice_id=0;
        if((input("payment_mode") == "Check") || (input("payment_mode") == "Cash")){
            $transaction = "T_" . input("payment_mode");
            $card = '******';
            $invoice_id=InvoiceModel::addInvoice($user, $return_balance, input("payment_mode"), "Refunded", $order->id, $transaction, $card,0,0,0,0,$return_balance,"by_admin");
        }
        elseif(input("payment_mode") == "Credit Card"){
            $security_deposit_history = Database::table("invoices")->where("student_id", $user->id)->where("security_paid", ">", "0")->where('status','Paid')->where('payment_mode','Credit Card')->get();
            $noRefund = true;
            foreach($security_deposit_history as $invoice){
                if ($invoice->price>=$return_balance){
                    $response=CC::Refund($invoice,$return_balance);
                    if ($response['response']!=1)
                        exit(json_encode(responder("error", "Declined!", "Credit card declined please refund using an alternative method. Error Text is ".$response['responsetext'],"reload()")));
                    $noRefund = false;
                    $invoice_id=$invoice->id;
                    break;
                }
            }
            if ($noRefund)
                exit(json_encode(responder("error", "Exceeded Amount!" ,input("payment_mode") . " Method have limit for refunding. Please check Security Deposit History","reload()")));
        }
        BalanceModel::addBalanceHistory($user,$return_balance, "Return", "Refunded with ".input("payment_mode"),$invoice_id,BalanceModel::None,0);
        Customer::addActionLog("Student", "checkout", "ID:".$user->id." ".$user->fname." ". $user->lname." $".$return_balance." returned");

        exit(json_encode(responder("success", "Remaining Balance" ,"$".$return_balance." was returned to student!","reload()")));
    }

    public function finalCheckout() {
        $user = Database::table("users")->find(input("user_id"));
        header('Content-type: application/json');
//        $checkout = array(
//            "student_id" => $user->id,
//            "bed_id" => $user->bed_id,
//            "total_amount" => input("total_amount")
//        );
//        Database::table("checkout")->insert($checkout);
//        $checkoutId = Database::table("checkout")->insertId();
//        Database::table("fine_history")->where("student_id" , $user->id)->where("bed_id" , $user->bed_id)->where("checkout_id" , 0)->update(array('checkout_id'=> $checkoutId));
        $order = OrderModel::Get($user);
        $outstanding_balance=$order->security_fee - $user->balance;
        $dd=array(
            'room_id' => 0,'bed_id'=> 0,'unit'=> '',
            'status'=> StudentModel::Terminated,
            'action'=> StudentModel::Checked_Out,
            'outstanding_balance'=>$outstanding_balance
        );
        StudentModel::Update($user->id,$dd);
        Database::table("beds")->where("id" , $user->bed_id)->update(array('student_id' => 0,'status'=> RoomModel::Unavailable));
        exit(json_encode(responder("success", "Checked out" ,"Remain balance $".$outstanding_balance." was added into outstanding balance (Write Off).","redirect('".url('Customer@profile').$user->id."')")));
    }

    public function getCheckoutHistoryData($user){
        $total_fine = 0;
        // fine
        $fine_history = Database::table("fine_history")->where("student_id" , $user->id)->where("bed_id", $user->bed_id)->where("checkout_id" , 0)->get();

        $return_fine_history = array();
        foreach($fine_history as $each_fine_history){
            $fine_ids = json_decode($each_fine_history->fine_id);
            foreach($fine_ids as $each_fine_ids){
                $each_fine_history_array = array();
                $each_fine_history_array['fine_history'] = $each_fine_history;
                $fine_item = Database::table("fine_fees")->where("id" , $each_fine_ids->value)->first();

                $each_fine_history_array['fine_type'] = $fine_item->type;
                $each_fine_history_array['fine_amount'] = $fine_item->amount;
                $total_fine += $fine_item->amount;

                $return_fine_history[] = $each_fine_history_array;
            }
        }

        $data = array(
            "user" => $user,
            "fine_history" => $return_fine_history,
            "total_fine" => $total_fine
        );

        return $data;
    }

    public function getCheckoutHistory(){
        $user = Auth::user();
        if ( $user->role =='user' ){
            return view('checkout_history', $this->getCheckoutHistoryData($user));
        }
        else{
            $checkout_history = array();
            $checkout_history_array = Database::table("checkout")->get();
            foreach($checkout_history_array as $each_checkout_history_array){
                $each_checkout_history = array();
                $each_checkout_history['checkout'] = $each_checkout_history_array;
                $each_checkout_history['student'] = Database::table("users")->where("id" , $each_checkout_history_array->student_id)->first();

                // fine
                $fine_history = Database::table("fine_history")->where("checkout_id" , $each_checkout_history_array->id)->get();
                $return_fine_history = array();
                foreach($fine_history as $each_fine_history){
                    $each_fine_history_array = array();
                    $each_fine_history_array['fine_history'] = $each_fine_history;
                    $fine = Database::table("fine_fees")->where("id" , $each_fine_history->fine_id)->first();
                    $each_fine_history_array['fine_type'] = $fine->type;
                    $each_fine_history_array['fine_amount'] = $fine->amount;

                    $return_fine_history[] = $each_fine_history_array;
                }
                $each_checkout_history['fine_history'] = $return_fine_history;

                $checkout_history[] = $each_checkout_history;
            }
            $data = array(
                "user" => Auth::user(),
                "checkout_history" => $checkout_history
            );
            return view('checkout_history', $data);
        }
    }
}
