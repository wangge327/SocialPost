<?php

namespace Simcify\Controllers;

use Simcify\Auth;
use Simcify\Database;
use Simcify\Models\BalanceModel;
use Simcify\Models\OrderModel;

class Posting
{
    public function get()
    {
        $user = Auth::user();
        $fb_pages = Database::table("facebook_pages")->where("user_id", $user->id)->first();
        $data = array(
            "user" => $user,
            "fb_pages" => $fb_pages
        );

        return view('posting/view', $data);
    }

    public function publishPost()
    {
        header('Content-type: application/json');

        $user = Auth::user();
        $fb_id = "";
        $fb_page_id = "";
        $fb_post_id = "";

        if (empty(input("message")))
            exit(json_encode(responder("error", "Empty", "Please type the message", "reload()")));

        $social_type = array();
        if (empty(input("social_type")))
            exit(json_encode(responder("error", "Unselect", "Plesase select Social Network", "reload()")));
        else {
            foreach (input("social_type") as $each_social) {
                $social_type[] = $each_social->value;
                if ($each_social->value == "Facebook") {
                    $fb_user = Database::table("facebook_account")->where("user_id", $user->id)->first();
                    $fb_pages = Database::table("facebook_pages")->where("user_id", $user->id)->where("fb_id", $fb_user->fb_id)->first();

                    if (empty($fb_pages))
                        exit(json_encode(responder("error", "Facebook Page", "Please set facebook page to publish post!", "reload()")));

                    $fb_id = $fb_pages->fb_id;
                    $fb_page_id = $fb_pages->page_id;

                    $facebook_publish = $this->facebook_publish_post_api($fb_pages->page_id, $fb_pages->page_access_token, input("message"));

                    if (isset($facebook_publish->id)) {
                        $fb_post_id = $facebook_publish->id;
                    } else {
                        exit(json_encode(responder("error", "Failed", "Failed to publish post to Facebook" . json_encode($facebook_publish->error), "reload()")));
                    }
                }
            }

            $posting_history_id = $this->save_posting_history($user->id, json_encode($social_type), $fb_id, $fb_page_id, $fb_post_id, input("message"));

            // Action Log
            Customer::addActionLog("Posting", "Publish Posting", "UserID:" . $user->id .  ",Posting history ID: " . $posting_history_id);
            exit(json_encode(responder("success", "Success", "Sucess publish", "reload()")));
        }
    }

    public function publishPostFacebook()
    {
        header('Content-type: application/json');

        $user = Auth::user();

        $fb_id = input("fb_id");
        $page_id = input("page_id");
        $page_name = input("page_name");
        $page_access_token = input("page_access_token");

        $facebook_publish = $this->facebook_publish_post_api($page_id, $page_access_token, input("message"));

        if (isset($facebook_publish->id)) {
            $fb_post_id = $facebook_publish->id;
        } else {
            exit(json_encode(responder("error", "Failed", "Failed to publish post to Facebook" . json_encode($facebook_publish->error), "reload()")));
        }

        $posting_history_id = $this->save_posting_history($user->id, "Facebook", $fb_id, $page_id, $fb_post_id, input("message"));

        // Action Log
        Customer::addActionLog("Posting", "Publish Post to Facecebook", "UserID:" . $user->id .  ", Posting history ID: " . $posting_history_id);
        exit(json_encode(responder("success", "Success", "This post successfully published to facebook page", "reload()")));
    }

    public function history()
    {
        $user = Auth::user();
        $posting_history_db = Database::table("posting_history")->where("user_id", $user->id)->get();
        $posting_history = array();

        foreach ($posting_history_db as $each_history) {
            $each_history->message = substr($each_history->message, 0, 150) . " ....";
            //$each_history->social_type = $this->array_to_string(json_decode($each_history->social_type));
            $posting_history[] = $each_history;
        }

        $data = array(
            "user" => $user,
            "posting_history" => $posting_history
        );

        return view('posting/history', $data);
    }

    function array_to_string($t_array)
    {
        $return_str = "";
        foreach ($t_array as $t_each) {
            if ($return_str == "")
                $return_str .= $t_each;
            else
                $return_str .= ", " . $t_each;
        }
        return $return_str;
    }
}
