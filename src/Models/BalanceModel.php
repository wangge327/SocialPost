<?php
namespace Simcify\Models;
use Simcify\Database;

class BalanceModel
{
    const Room='Room';
    const Security='Security';
    const Administration='Administration';
    const Laundry='Laundry';
    const Other='Other';
    const Holding='Holding';
    const None='None';

    static public function addBalanceHistory($user, $amount, $action, $note,$invoice_id=0,$type=BalanceModel::None,$owed_balance=0){
        if ($action!="Payment with Holding"){
            $user->balance+=$amount;
            StudentModel::Update($user->id,array('balance' => $user->balance));
        }
        $balance_history = array(
            "student_id" => $user->id,
            "amount" => $amount,
            "action" => $action,
            "note" => $note,
            "invoice_id" => $invoice_id,
            "type"=>$type,
            "owed_balance" => $owed_balance,
            "balance"=>$user->balance
        );
        Database::table("balance_history")->insert($balance_history);
    }

    static public function addHoldingBalance($user, $amount){
        $user->holding_balance+=$amount;
        StudentModel::Update($user->id,array('holding_balance' => $user->holding_balance));
    }
}