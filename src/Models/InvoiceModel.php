<?php
namespace Simcify\Models;

use Simcify\Auth;
use Simcify\Date;
use Simcify\Mail;
use Simcify\Database;


class InvoiceModel {
    static public function addInvoice($user,$price,$paymentMode,$status,$orderId,$transactionId,$card,$roomPaid,$administrationPaid,$securityPaid,$laundryPaid,$holding_paid,$payment_option) {
        $invoice = array(
            "price" => $price,
            "name" =>  filter_var($user->fname . ' ' . $user->lname, FILTER_SANITIZE_STRING),
            "email" => filter_var($user->email, FILTER_SANITIZE_EMAIL),
            "transaction_id" => $transactionId,
            "order_id" => $orderId,
            "student_id" => $user->id,
            "payment_mode" => $paymentMode,
            "status" => $status,
            "room_number" => $user->room_id,
            "bed_id" => $user->bed_id,
            "start_date" => $user->lease_start,
            "end_date" => $user->lease_end,
            "card_number" =>$card,
            "paid_user_id" => Auth::user()->id,
            "room_paid" => $roomPaid,
            "administration_paid" => $administrationPaid,
            "security_paid" => $securityPaid,
            "laundry_paid" => $laundryPaid,
            "holding_paid" => $holding_paid,
            "payment_option"=>$payment_option
        );
        Database::table("invoices")->insert($invoice);
        Mail::send(
            $user->email, "Hiawatha Student Housing Payment",
            array(
                "title" => $paymentMode." $".$price,
                "subtitle" => "Click the link below to check the payment history.",
                "buttonText" => "Payment History",
                "buttonLink" => env("APP_URL")."/history",
                "message" => "Your order $".$price." has been paid with ".$paymentMode." by ".$payment_option." .<br> Thank you<br>".env("APP_NAME")." Team"
            ),
            "withbutton"
        );
        return Database::table("invoices")->insertId();
    }

    public static function getInvoicesByDate($start_date, $end_date){
        $invoices = Database::table("invoices")->where("created_at",">=", $start_date)->where("created_at","<=", $end_date)->get();
        if(!empty($invoices)){
            $r_invoices = array();
            foreach($invoices as $each_invoice){
                if(($each_invoice->payment_mode == "Cash") || ($each_invoice->payment_mode == "Check") || ( $each_invoice->payment_mode == "Credit Card")){
                    $r_invoices[] = array(
                        "invoice" => $each_invoice,
                        "user" => Database::table("users")->find($each_invoice->student_id)
                    );
                }
            }
            $invoices = $r_invoices;
        }
        return $invoices;
    }
}