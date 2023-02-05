<?php
namespace Simcify\Models;
use Simcify\Auth;
use Simcify\Database;
use Simcify\Date;
use Simcify\Mail;

class OrderModel
{
    const Init='init';
    const Active='active';
    const Closed='closed';

    public function __construct() {
    }

    static public function Add($user)
    {
        $fees = Database::table("companies")->first();

        $student_room_fee = PaymentModel::getStudentRoomFee($user);
        $weeks=env("SITE_Portal")?1:2;
        $price = array(
            'room'=>$weeks*$student_room_fee['week_fee'],
            'security'=>$user->security_deposit,
            'administration'=>$fees->administration,
            'laundry'=>$fees->laundry
        );

        $price['room_due']= $price['room'];
        $price['security_due'] = $price['security'];
        $price['administration_due'] = $price['administration'];
        $price['laundry_due'] = $price['laundry'];

        $employer = Database::table("employers")->where("id", $user->employer)->first();
        if(!empty($employer)){
            $total_employer_paid = 0;
            if($employer->room_pay == 1){
                $price['room_due'] = 0;
                $price['employer_room_due'] = $price['room'];
                $total_employer_paid += $price['room'];
            }

            if($employer->security_pay == 1){
                $price['security_due'] = 0;
                $price['employer_security_due'] = $price['security'];
                $total_employer_paid += $price['security'];
            }

            if($employer->administration_pay == 1){
                $price['administration_due'] = 0;
                $price['employer_administration_due'] = $price['administration'];
                $total_employer_paid += $price['administration'];
            }

            if($employer->laundry_pay == 1){
                $price['laundry_due'] = 0;
                $price['employer_laundry_due'] = $price['laundry'];
                $total_employer_paid += $price['laundry'];
            }
        }

        $order = array(
            "student_id" => $user->id,
            "bed_id" => $user->bed_id,
            "lease_start" => $user->lease_start,
            "lease_end" =>Date::GetLeaseEnd($user->lease_start),
            "room_fee" => $price["room"],
            "room_due_status" => $price["room_due"],
            "security_fee" => $price["security"],
            "security_due_status" => $price["security_due"],
            "administration_fee" => $price["administration"],
            "administration_due_status" => $price['administration_due'],
            "laundry_fee" => $price["laundry"],
            "laundry_due_status" => $price['laundry_due'],
            "status" => self::Init
        );
        Database::table("orders")->insert($order);
        $return_order = Database::table("orders")->last();

        BalanceModel::addBalanceHistory($user,$student_room_fee['week_fee'], "Balance Owed", "room fee (1st week)",0,BalanceModel::Room,$student_room_fee['week_fee']);
        if (!env("SITE_Portal"))
            BalanceModel::addBalanceHistory($user,$student_room_fee['week_fee'], "Balance Owed", "room fee (2nd week)",0,BalanceModel::Room,$price["room_due"]);
        BalanceModel::addBalanceHistory($user,$price["security"], "Balance Owed", "Security Deposit",0,BalanceModel::Security,$price["security_due"]);
        BalanceModel::addBalanceHistory($user,$price["administration"], "Balance Owed", "Administration",0,BalanceModel::Administration,$price["administration_due"]);
        BalanceModel::addBalanceHistory($user,$price["laundry"], "Balance Owed", "Laundry",0,BalanceModel::Laundry,$price["laundry_due"]);

        if($total_employer_paid != 0){
            $invoice_id=InvoiceModel::addInvoice($user, $total_employer_paid, "Cash", "Paid",$return_order->id, "T_Cash", "******Cash", $price["employer_room_due"],$price['employer_administration_due'], $price["employer_security_due"], $price['employer_laundry_due'],0,"by_employer");
            BalanceModel::addBalanceHistory($user,-$total_employer_paid, "Paid by Employer","Initial",$invoice_id,BalanceModel::None,0);
        }

        return $return_order;
    }

    static public function Get($user)
    {
        $order = Database::table("orders")->where("student_id", $user->id)->last();
        if (empty($order)){
            ReportModel::SendError("Empty Order User Id:".$user->id);
        }
        return $order;
    }

    //$paymentMode=Credit Card,Subscribe,Cash,Holding  ...
    static public function SubmitOrder($paymentMode, $transaction, $card){
        $paymentUser = Database::table("users")->find(input("payment_user_id"));
        $priceTotal=input("price_total");
        $payment_option=input('payment_option');

        $securityPaid = input('security_amount');
        $roomPaid = input('room_amount');
        $administrationPaid = input('administration_amount');
        $laundryPaid = input('laundry_amount');
        $holding_amount=input('holding_amount');

        $order =OrderModel::Get($paymentUser);
        $orderData = array(
            "status" => self::Active
        );

        $invoice_id=InvoiceModel::addInvoice($paymentUser, $priceTotal, $paymentMode, "Paid", $order->id, $transaction, $card,$roomPaid,$administrationPaid,$securityPaid,$laundryPaid,$holding_amount,$payment_option);

        $orderData["security_due_status"] = $order->security_due_status - $securityPaid;
        $orderData["room_due_status"] = $order->room_due_status - $roomPaid;
        $orderData["administration_due_status"] = $order->administration_due_status - $administrationPaid;
        $orderData["laundry_due_status"] = $order->laundry_due_status - $laundryPaid;

        if (isset($orderData["security_due_status"]) && $orderData["security_due_status" ] != $order->security_due_status){
            BalanceModel::addBalanceHistory($paymentUser, -$securityPaid, "Payment with ".$paymentMode, "Security Deposit applied. Balance $" . $orderData["security_due_status" ],$invoice_id,BalanceModel::Security,$orderData["security_due_status" ]);
        }
        if (isset($orderData["room_due_status"]) && $orderData["room_due_status" ] != $order->room_due_status) {
            BalanceModel::addBalanceHistory($paymentUser, -$roomPaid, "Payment with ".$paymentMode, "Room fee applied. Balance $" . $orderData["room_due_status"],$invoice_id,BalanceModel::Room,$orderData["room_due_status"] );
        }
        if (isset($orderData["administration_due_status"]) && $orderData["administration_due_status"] != $order->administration_due_status) {
            BalanceModel::addBalanceHistory($paymentUser, -$administrationPaid, "Payment with ".$paymentMode, "Administration fee applied. Balance $" . $orderData["administration_due_status"],$invoice_id,BalanceModel::Administration,$orderData["administration_due_status"]);
        }
        if (isset($orderData["laundry_due_status"]) && $orderData["laundry_due_status"] != $order->laundry_due_status) {
            BalanceModel::addBalanceHistory($paymentUser, -$laundryPaid, "Payment with ".$paymentMode, "Laundry fee applied. Balance $" . $orderData["laundry_due_status"] ,$invoice_id,BalanceModel::Laundry,$orderData["laundry_due_status"]);
        }

        if ($holding_amount>0){
            BalanceModel::addHoldingBalance($paymentUser, $holding_amount);
            BalanceModel::addBalanceHistory($paymentUser, -$holding_amount, "Payment with ".$paymentMode, "Holding Balance Amount",$invoice_id,BalanceModel::Holding,$paymentUser->holding_balance);
        }

        if ($paymentMode=='Holding'){
            BalanceModel::addHoldingBalance($paymentUser, -$priceTotal);
        }

        Database::table("orders")->where("id" , $order->id)->update($orderData);
    }

    static public function Refund($invoice_id) {
        $order_transaction = Database::table("invoices")->find($invoice_id);
        $order_data=Database::table("orders")->find($order_transaction->order_id);
        $order_array = array(
            'room_due_status'=> $order_data->room_due_status+$order_transaction->room_paid,
            'security_due_status' => $order_data->security_due_status+$order_transaction->security_paid,
            'administration_due_status'=> $order_data->administration_due_status+ $order_transaction->administration_paid,
            'laundry_due_status'=> $order_data->laundry_due_status+ $order_transaction->laundry_paid
        );
        Database::table("orders")->where("id" , $order_transaction->order_id)->update($order_array);
    }

    static public function ChangeOrderDate($user,$start) {
        StudentModel::Update($user->id,array('status'=>StudentModel::Arrived));
        $order=OrderModel::Get($user);
        $dd = array(
            'lease_start' => date('Y-m-d', strtotime($start)),
            'lease_end'=> date('Y-m-d', strtotime($start. ' + 14 days')),
        );
        Database::table("orders")->where("id" , $order->id)->update($dd);
    }
}