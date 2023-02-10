<?php
namespace Simcify\Controllers;

use Simcify\Auth;
use Simcify\Database;
use Simcify\Models\BalanceModel;
use Simcify\Models\OrderModel;

class Facebook{
    public function get() {
        $user = Auth::user();
    	$data = array(
    		"user" => $user,
            "fb_user" => Database::table("facebook_account")->where("user_id", $user->id)->get()
        );
        return view('facebook/view', $data);
    }

    public function addPage(){
        $user = Auth::user();
        $fb_user = Database::table("facebook_account")->where("user_id", $user->id)->first();
        $fb_pages = Database::table("facebook_account")->where("user_id", $user->id)->get();
        $data = array(
            "user" => Auth::user(),
            "fb_user" => $fb_user,
            "fb_pages" => $fb_pages
        );
        return view('facebook/add_page', $data);
    }

    public function callback(){
        $data = array(
            "user" => Auth::user()
        );
        return view('facebook/fb-callback', $data);
    }

    public function addFbAccount(){
        header('Content-type: application/json');
        $fb_user_data = array();
        foreach (input()->post as $field) {
            if ($field->index == "csrf-token") {
                continue;
            }
            $fb_user_data[$field->index] = escape($field->value);
        }

        $user = Database::table("facebook_account")->where("fb_id",$fb_user_data["fb_id"])->first();
        if (!empty($user)) {
            exit(json_encode(responder("error", "错误", "此 Facebook 帐户已添加", "window.location.replace('" . env("APP_URL") . "/facebook')")));
        }
        else{
            Database::table("facebook_account")->insert($fb_user_data);
            $newId = Database::table("facebook_account")->insertId();

            // Action Log
            Customer::addActionLog("Facebook", "Add FB Account", "ID:" . $newId .  " , FB Email:" . $fb_user_data["fb_email"]);

            exit(json_encode(responder("success", "好吧", "Facebook账号添加成功", "window.location.replace('" . env("APP_URL") . "/facebook')")));
        }
    }

    public function createByAjax() {
        header('Content-type: application/json');
        $employer_data = array(
            "type" => $_POST["type"],
            "amount" => $_POST["amount"]
        );
        Database::table("fine_fees")->insert($employer_data);
        exit(json_encode(Database::table("fine_fees")->last()));
    }

    public function delete() {
        // Action Log
        $facebook_account = Database::table("facebook_account")->where("id", input("tbid"))->first();
        Database::table("facebook_account")->where("id", input("tbid"))->delete();
        Customer::addActionLog("Facebook", "Delete Facebook Account", "Deleted Facebook Name : ". $facebook_account->fb_name);

        header('Content-type: application/json');
        exit(json_encode(responder("success", "帐户删除!", "Facebook 帐户已成功删除","reload()")));
    }

    public function updateview() {
        $data = array(
                "fine_fees" => Database::table("fine_fees")->where("id", input("fineid"))->first()
            );
        return view('extras/updatefine', $data);
    }

    public function update() {
        foreach (input()->post as $field) {
            if ($field->index == "csrf-token" || $field->index == "fineid") {
                continue;
            }
            Database::table("fine_fees")->where("id" , input("fineid"))->update(array($field->index => escape($field->value)));
        }

        // Action Log
        Customer::addActionLog("Fine", "Edit Fine", "Edited Fine : ". input("type"));

        header('Content-type: application/json');
        exit(json_encode(responder("success", "Alright!", "Fine Fee was successfully updated","reload()")));
    }

    public function updateAddFine(){ //to the student
        $user_id=input("user_id");

        $login_user = Auth::user();
        $user = Database::table("users")->find($user_id);
        $order =OrderModel::Get($user);
        header('Content-type: application/json');
        if($order->security_due_status > 0){
            exit(json_encode(responder("error", "Add Fine Error" ,"You have to make payment about Security fee!","reload()")));
        }
        else{
            $fine_ids = input("fine_ids");
            $fine_amount = 0;
            if ($fine_ids!=null){
                $fine_history = array(
                    "student_id" => $user_id,
                    "record_person" => $login_user->id,
                    "fine_id" => json_encode($fine_ids),
                    "bed_id" => $user->bed_id,
                    "note" => input("note")
                );
                Database::table("fine_history")->insert($fine_history);
                foreach($fine_ids as $each_id){
                    $fine_fee = Database::table("fine_fees")->where("id" , $each_id->value)->first();
                    $fine_amount += $fine_fee->amount;
                }
                BalanceModel::addBalanceHistory($user,$fine_amount, "Fine",input("note"),BalanceModel::Other,0);
                Customer::addActionLog("Fine", "Add Fine $".$fine_amount." to Student", "Added Fine Fee to " . $user->fname . " " . $user->lname);
            }
            exit(json_encode(responder("success", "Fine" ,"Fine were added!","reload()")));
        }
    }


}
