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
        $fb_pages = Database::table("facebook_pages")->where("user_id", $user->id)->where("fb_id", $fb_user->fb_id)->first();
        $fb_pages_api = $this->get_pages_api($fb_user->fb_id, $fb_user->fb_long_lived_access_token);
        $data = array(
            "user" => Auth::user(),
            "fb_user" => $fb_user,
            "fb_pages" => $fb_pages,
            "fb_pages_api" => $fb_pages_api,
        );

        return view('facebook/add_page', $data);
    }

    public function addPageDB(){
        header('Content-type: application/json');
        $facebook_pages = Database::table("facebook_pages")->where("user_id", input("user_id"))->where("fb_id", input("fb_id"))->first();
        $fb_page_data = array();
        foreach (input()->post as $field) {
            if ($field->index == "csrf-token" || $field->index == "status") {
                continue;
            }
            $fb_page_data[$field->index] = escape($field->value);
        }

        if(input(status) == "Set"){
            if (empty($facebook_pages)){
                Database::table("facebook_pages")->insert($fb_page_data);
            }
            else{
                Database::table("facebook_pages")->where("id", $facebook_pages->id)->update($fb_page_data);
            }
            Customer::addActionLog("Facebook", "Set Facebook Page", "User ID:". input("user_id").", Page Name". input("page_name"));
            exit(json_encode(responder("success", "Set", "This page is Set in site." ,"reload()")));
        }
        else{
            Database::table("facebook_pages")->where("id", $facebook_pages->id)->delete();
            Customer::addActionLog("Facebook", "Remove Facebook Page", "User ID:". input("user_id").", Page Name". input("page_name"));
            exit(json_encode(responder("success", "Remove", "This page is Removed from site." ,"reload()")));
        }
    }

    public function get_pages_api($facebook_id, $access_token){
        $page_details = "https://graph.facebook.com/" . $facebook_id . "/accounts?fields=name,access_toke&access_token=" . $access_token;
        $response = file_get_contents($page_details);
        $response = json_decode($response);

        $return_array = array();
        foreach($response->data as $each_response){
            $each_response->page_token = $this->get_page_token_api($each_response->id, $access_token);
            $return_array[] = $each_response;
        }
        return $return_array;
    }

    public function get_page_token_api($page_id, $access_token){
        $page_details = "https://graph.facebook.com/" . $page_id . "?fields=access_token&access_token=" . $access_token;
        $response = file_get_contents($page_details);
        $response = json_decode($response);

        return $response->access_token;
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

        $user = Database::table("facebook_account")->where("user_id",$fb_user_data["user_id"])->where("fb_id",$fb_user_data["fb_id"])->first();
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

    public function delete() {
        $facebook_account = Database::table("facebook_account")->where("id", input("tbid"))->first();
        Database::table("facebook_account")->where("id", input("tbid"))->delete();
        Customer::addActionLog("Facebook", "Delete Facebook Account", "Deleted Facebook Name : ". $facebook_account->fb_name);

        header('Content-type: application/json');
        exit(json_encode(responder("success", "帐户删除!", "Facebook 帐户已成功删除","reload()")));
    }
}
