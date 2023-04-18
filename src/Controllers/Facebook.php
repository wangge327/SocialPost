<?php

namespace Simcify\Controllers;

use Simcify\Auth;
use Simcify\Database;
use Simcify\Models\BalanceModel;
use Simcify\Models\OrderModel;

class Facebook
{
    public function get()
    {
        $user = Auth::user();
        $data = array(
            "user" => $user,
            "fb_user" => Database::table("facebook_account")->where("user_id", $user->id)->get()
        );
        return view('facebook/view', $data);
    }

    public function getPage(){
        $user = Auth::user();
        $fb_user = Database::table("facebook_account")->where("user_id", $user->id)->get();
        foreach($fb_user as $each_user){
            $each_user->fb_page = Database::table("facebook_pages")->where("user_id", $user->id)->where("fb_id", $each_user->fb_id)->get();
        }

        $data = array(
            "user" => Auth::user(),
            "fb_user" => $fb_user,
        );

        return view('facebook/get_page', $data);
    }

    public function addPage($fb_id)
    {
        $user = Auth::user();

        $fb_user = Database::table("facebook_account")->where("user_id", $user->id)->where("fb_id", $fb_id)->first();
        $fb_pages = Database::table("facebook_pages")->where("user_id", $user->id)->where("fb_id", $fb_id)->first();
        $fb_pages_api = $this->get_pages_api($fb_user->fb_id, $fb_user->fb_long_lived_access_token);

        $data = array(
            "user" => Auth::user(),
            "fb_user" => $fb_user,
            "fb_pages" => $fb_pages,
            "fb_pages_api" => $fb_pages_api,
        );

        return view('facebook/add_page', $data);
    }

    public function publishPost(){
        $user = Auth::user();
        $fb_user = Database::table("facebook_account")->where("user_id", $user->id)->get();
        foreach($fb_user as $each_user){
            $each_user->fb_page = Database::table("facebook_pages")->where("user_id", $user->id)->where("fb_id", $each_user->fb_id)->get();
        }

        $data = array(
            "user" => Auth::user(),
            "fb_user" => $fb_user,
        );

        return view('facebook/publish_post', $data);
    }

    public function publishPostDB(){
        header('Content-type: application/json');

        $user = Auth::user();

        $facebook_pages = Database::table("facebook_pages")->where("user_id", $user->id)->get();

        foreach($facebook_pages as $each_page){
            $facebook_publish = $this->publish_post_api($each_page->page_id, $each_page->page_access_token, input("message"));

            if (isset($facebook_publish->id)) {
                $fb_post_id = $facebook_publish->id;
            } else {
                exit(json_encode(responder("error", "Failed", "Failed to publish post to Facebook" . json_encode($facebook_publish->error), "reload()")));
            }

            $this->save_posting_history($user->id, "Facebook", $each_page->fb_id, $each_page->page_id, $fb_post_id, input("message"));

            // Action Log
            Customer::addActionLog("Posting", "Publish Post to Facecebook", "Facebook ID:" . $each_page->fb_id .  ", Posting Content: " . input("message"));
        }
        exit(json_encode(responder("success", "Success", "此帖子已成功发布到 facebook 页面。", "reload()")));

    }

    function save_posting_history($user_id, $social_type, $fb_id, $fb_page_id, $fb_post_id, $message)
    {
        $insert_array = array(
            "user_id" => $user_id,
            "social_type" => $social_type,
            "fb_id" => $fb_id,
            "fb_page_id" => $fb_page_id,
            "fb_post_id" => $fb_post_id,
            "message" => $message
        );

        Database::table("posting_history")->insert($insert_array);
        $posting_history_id = Database::table("posting_history")->insertId();

        return $posting_history_id;
    }

    public function publishPostView()
    {
        $data = array(
            "user_id" => input("user_id"),
            "fb_id" => input("fb_id"),
            "page_id" => input("page_id"),
            "page_name" => input("page_name"),
            "page_access_token" => input("page_access_token")
        );
        return view('facebook/publish_post_modal', $data);
    }

    public function addPageDB()
    {
        header('Content-type: application/json');

        $facebook_pages = Database::table("facebook_pages")->where("user_id", input("user_id"))->where("fb_id", input("fb_id"))->first();

        $fb_page_data = array();
        foreach (input()->post as $field) {
            if ($field->index == "csrf-token" || $field->index == "status") {
                continue;
            }
            $fb_page_data[$field->index] = escape($field->value);
        }

        if (input("status") == "Set") {
            if (empty($facebook_pages)) {
                Database::table("facebook_pages")->insert($fb_page_data);
            } else {
                Database::table("facebook_pages")->where("id", $facebook_pages->id)->update($fb_page_data);
            }
            Customer::addActionLog("Facebook", "Set Facebook Page", "Facebook ID:" . input("fb_id") . ", Page Name:" . input("page_name"));
            exit(json_encode(responder("success", "设置", "此页面将设置在站点中。", "reload()")));
        } else {
            Database::table("facebook_pages")->where("id", $facebook_pages->id)->delete();
            Customer::addActionLog("Facebook", "Remove Facebook Page", "Facebook ID:" . input("fb_id") . ", Page Name:" . input("page_name"));
            exit(json_encode(responder("success", "取消设置", "此页面已从站点中删除。", "reload()")));
        }
    }

    public function get_pages_api($facebook_id, $access_token)
    {
        $page_details = "https://graph.facebook.com/" . $facebook_id . "/accounts?access_token=" . $access_token;
        $response = file_get_contents($page_details);
        $response = json_decode($response);

        return $response->data;
    }

    public function get_page_token_api($page_id, $access_token)
    {
        $page_details = "https://graph.facebook.com/" . $page_id . "?fields=access_token&access_token=" . $access_token;
        $response = file_get_contents($page_details);
        $response = json_decode($response);

        return $response->access_token;
    }

    function publish_post_api($page_id, $page_token, $message)
    {
        $ch = curl_init();
        $url = "https://graph.facebook.com/" . $page_id . "/feed";
        $postdata = "message=" . $message . "&access_token=" . $page_token;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_REFERER, $url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
        curl_setopt($ch, CURLOPT_POST, 1);

        $result = curl_exec($ch);
        $result = json_decode($result);
        return $result;
    }

    public function callback()
    {
        $data = array(
            "user" => Auth::user()
        );
        return view('facebook/fb-callback', $data);
    }

    public function addFbAccount()
    {
        header('Content-type: application/json');
        $fb_user_data = array();
        foreach (input()->post as $field) {
            if ($field->index == "csrf-token") {
                continue;
            }
            $fb_user_data[$field->index] = escape($field->value);
        }

        $user = Database::table("facebook_account")->where("user_id", $fb_user_data["user_id"])->where("fb_id", $fb_user_data["fb_id"])->first();
        if (empty($user)) {
            Database::table("facebook_account")->insert($fb_user_data);
            $newId = Database::table("facebook_account")->insertId();
        } else {
            Database::table("facebook_account")->where("user_id", $fb_user_data["user_id"])->where("fb_id", $fb_user_data["fb_id"])->update($fb_user_data);
        }

        // Action Log
        Customer::addActionLog("Facebook", "Add FB Account", "FB ID:" . $fb_user_data["fb_id"] .  " , FB Email:" . $fb_user_data["fb_email"]);
        exit(json_encode(responder("success", "好吧", "Facebook账号添加成功", "window.location.replace('" . env("APP_URL") . "/facebook')")));
    }

    public function delete()
    {
        $facebook_account = Database::table("facebook_account")->where("id", input("tbid"))->first();
        Database::table("facebook_account")->where("id", input("tbid"))->delete();
        Database::table("facebook_pages")->where("user_id", $facebook_account->user_id)->where("fb_id", $facebook_account->fb_id)->delete();
        Customer::addActionLog("Facebook", "Delete Facebook Account", "Deleted Facebook Name : " . $facebook_account->fb_name);

        header('Content-type: application/json');
        exit(json_encode(responder("success", "帐户删除!", "Facebook 帐户已成功删除", "reload()")));
    }
}
