<?php
namespace Simcify\Controllers;

use Simcify\Auth;
use Simcify\Database;
use Simcify\File;
use Simcify\Models\OrderModel;
use Simcify\Models\PaymentModel;
use Simcify\Models\StudentModel;

class DbControl extends Admin{
//db/get_balance
    public function GetBalance(){
        $customers = Database::table("users")->where("company", Auth::user()->company)->where("role", "user")->get();
        foreach ($customers as $customer) {
//            StudentModel::Update($customer->id,array('balance'=>PaymentModel::getPriceBalance($customer)));

            $balance_history = Database::table("balance_history")->where("student_id", $customer->id)->get();
            $balance=0;
            foreach($balance_history as $each){
                $balance+=$each->amount;
                if ($each->balance!=0){
                    if ($each->balance!=$balance)
                        echo "<br>not same user_id:".$customer->id." ".$each->balance.":".$balance;
                    break;
                }
                Database::table("balance_history")->where("id" , $each->id)->update(array("balance"=>$balance));
            }
            if ($customer->balance!=$balance)
                echo "<br>incorrect user_id:".$customer->id." ".$customer->balance.":".$balance;
        }
        echo "balance history updated";
    }
//https://students.irhliving.com/db/add_order_and_balancehistory/2101
    function addOrderAndBalancehistory($user_id){
        $users = Database::table("users")->where("id",">", $user_id)->get();
        foreach($users as $user){
            OrderModel::Add($user);
        }
        echo "added";
    }

    //https://students.irhliving.com/db/remove_user/2101
    function removeUser($user_id){
        $user = Database::table("users")->find($user_id);
        if (!empty($user->avatar)) {
            File::delete($user->avatar, "avatar");
        }
        StudentModel::delete($user,StudentModel::Suspended);
        Database::table("users")->where("id", $user_id)->delete();
        Database::table("invoices")->where("student_id", $user_id)->delete();
        Database::table("balance_history")->where("student_id", $user_id)->delete();
        Database::table("orders")->where("student_id", $user_id)->delete();
        Database::table("cancel_lease_history")->where("student_id", $user_id)->delete();
        Database::table("fine_history")->where("student_id", $user_id)->delete();
        echo "Deleted";
    }
}
