<?php
namespace Simcify\Models;

use Simcify\Auth;
use Simcify\Controllers\Checkout;
use Simcify\Date;
use Simcify\Mail;
use Simcify\Database;
use Simcify\Controllers\Customer;

define("Order_Log",$_SERVER['DOCUMENT_ROOT']."/log/order.log");

class PaymentModel {

    static public function getStudentRoomFee($student) {
        $fees = Database::table("companies")->first();
        $special_room = Database::table("special_room")->where("bed_id", $student->bed_id)->first();
        $week_fee = $fees->weekly;
        $day_fee = $fees->daily;
        if(!empty($special_room)){
            $week_fee = $special_room->weekly;
            $day_fee = $special_room->daily;
        }
        if($student->weekly_rate != 0 ){
            if($student->weekly_rate != $fees->weekly ){
                $week_fee = $student->weekly_rate;
                $day_fee = (int)($week_fee/7);
            }
        }

        $return_fee = array();
        $return_fee['week_fee'] = $week_fee;
        $return_fee['day_fee'] = $day_fee;

        return $return_fee;
    }

    static public function getPriceBalance($payment_user): float
    {   //=security+laundry
    	$order = OrderModel::Get($payment_user);
        return $order->room_due_status + $order->administration_due_status+$order->security_due_status + $order->laundry_due_status;
	}

    static public function getCancelBalance($student, $order_data): float
    {
        $return_balance = $order_data->room_fee + $order_data->security_fee +$order_data->administration_fee +$order_data->laundry_fee - $order_data->room_due_status - $order_data->security_due_status - $order_data->administration_due_status - $order_data->laundry_due_status;

        $student_room_fee = PaymentModel::getStudentRoomFee($student);

        $today = date('Y-m-d');
        if($today < $order_data->lease_end){
            $diff_days = Date::date_diff($order_data->lease_end, $today);
            $return_balance -= $student_room_fee['day_fee']*$diff_days - $order_data->security_fee;
        }
        else{
            $return_balance -= $order_data->security_fee;
        }
        return $return_balance;
    }
}