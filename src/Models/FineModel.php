<?php
namespace Simcify\Models;
use Simcify\Auth;
use Simcify\Database;
use Simcify\Mail;

class FineModel
{
    const Init='init';
    const Active='active';
    const Closed='closed';

    public static function GetFineHistory($user_id)
    {
        $fine_history = Database::table("fine_history")->where("student_id" , $user_id)->get();
        $return_fine_history = array();

        foreach($fine_history as $each_fine_history){
            $total_amount = 0;
            $fine_items = array();
            $fine_ids = json_decode($each_fine_history->fine_id);
            foreach($fine_ids as $each_fine_ids){
                $fine_item = Database::table("fine_fees")->where("id" , $each_fine_ids->value)->first();
                $fine_items[] = $fine_item;
                $total_amount += $fine_item->amount;
            }
            $each_fine_history->total_amount = $total_amount;
            $each_fine_history->fine_itmes = $fine_items;

            $return_fine_history[] = $each_fine_history;
        }
        return $return_fine_history;
    }
}