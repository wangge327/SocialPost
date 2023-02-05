<?php

include_once 'vendor/autoload.php';

use Simcify\Database;
use Simcify\Application;
use Simcify\Models\StudentModel;

$app = new Application();
//http://amg.signer/db_script.php

updateUserSignStatus();
//updateUserStatus();
//update_order_lease_date();

function updateUserStatus(){
    $users = Database::table("users")->where("role", "user")->where("status", StudentModel::Created)->get();
    foreach($users as $user){
        $request = Database::table("invoices")->where("student_id", $user->id)->first();
        if ($request){
            StudentModel::Update($user->id,array('status'=>StudentModel::Arrived));
        }
    }
    echo "done!";
}

function updateUserSignStatus(){
    $users = Database::table("users")->where("role", "user")->get();
    foreach($users as $user){
        $request = Database::table("requests")->where("receiver", $user->id)->first();
        if ($request){
//            if ($request->status==StudentModel::Pending)
//                StudentModel::Update($user->id,array('sign_status'=>StudentModel::Sent));
            if ($request->status==StudentModel::Signed)
                StudentModel::Update($user->id,array('sign_status'=>StudentModel::Signed));
        }
    }
    echo "done!";
}

function UpdateBalanceHistoryData(){
    $balances = Database::table("balance_history")->where("note", "2 weeks room fee")->get();
    foreach($balances as $item){
        $order = Database::table("orders")->where("student_id",$item->student_id)->first();
        if ($order){
            $note = array(
                'note'=> "room fee (".$order->lease_start.":".$order->lease_end.")"
            );
            Database::table("balance_history")->where("id", $item->id)->update($note);
        }
        else{
            echo $item->student_id."  ";
        }
    }
}

function update_order_lease_date(){
    $orders = Database::table("orders")->where("student_id",">", "2101")->get();
    foreach($orders as $each_order){
        $student = Database::table("users")->where("id","=", $each_order->student_id)->first();
        $lease_start = $student->lease_start;
        $lease_end = date('Y-m-d', strtotime($lease_start. ' + 14 days'));
        $order_array = array(
            'lease_start'=> $lease_start,
            'lease_end'=> $lease_end
        );
        Database::table("orders")->where("id" , $each_order->id)->update($order_array);
    }
    echo "done!";
}

?>