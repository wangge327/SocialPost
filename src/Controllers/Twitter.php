<?php

namespace Simcify\Controllers;

use Simcify\Auth;
use Simcify\Database;

class Twitter
{
    public function get()
    {
        $user = Auth::user();
        $twitter_oauth = Database::table("twitter_oauth")->where("user_id", $user->id)->first();
        $data = array(
            "user" => $user,
            "twitter_oauth" => $twitter_oauth
        );

        $this->check_twitter_login($user->id);
        return view('twitter/view', $data);
    }

    public function sendTweetView(){
        $user = Auth::user();
        $twitter_oauth = Database::table("twitter_oauth")->where("user_id", $user->id)->first();
        $data = array(
            "user" => $user,
            "twitter_oauth" => $twitter_oauth
        );

        $this->check_twitter_login($user->id);
        return view('twitter/send_tweet', $data);
    }

    public function sendTweetDB(){
        header('Content-type: application/json');
        $user = Auth::user();
        $twitter_oauth = Database::table("twitter_oauth")->where("user_id", $user->id)->first();

        $send_tweet = $this->sendTweetAPI($twitter_oauth->twitter_token, input("tweet"));

        if(isset($send_tweet["data"])){
            Customer::addActionLog("Twitter", "Send Tweet", "Twitter ID :" . $twitter_oauth->twitter_name . ", Tweet : " . input("tweet"));
            exit(json_encode(responder("success", "发送推文管理", "您的推文已成功发送到推特。", "reload()")));
        }
        else{
            exit(json_encode(responder("error", "Error!!!", json_encode($send_tweet), "reload()")));
        }

    }

    function check_twitter_login($user_id){
        session_start();
        $twitter_oauth = Database::table("twitter_oauth")->where("user_id", $user_id)->first();
        $db_time = strtotime($twitter_oauth->updated_time);
        $current_time = time();

        $dif = $current_time - $db_time;

        if($dif > 4800)
            $_SESSION['twitter_login'] = false;
        else
            $_SESSION['twitter_login'] = true;

    }

    function sendTweetAPI($access_token, $text)
    {
        $ch = curl_init();
        $authorization = "Authorization: Bearer " . $access_token;
        $url = 'https://api.twitter.com/2/tweets';

        $post = array(
            "text" => $text
        );

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization ));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post));
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        $result = curl_exec($ch);
        curl_close($ch);

        return (array)json_decode($result);
    }

    function getTweetsAPI($access_token, $ids){
        $ch = curl_init();
        $authorization = "Authorization: Bearer " . $access_token;
        $url = 'https://api.twitter.com/2/tweets?ids=';

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array($authorization ));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        $result = curl_exec($ch);
        curl_close($ch);

        return (array)json_decode($result);
    }
}
