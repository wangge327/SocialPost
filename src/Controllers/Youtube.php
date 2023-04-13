<?php

namespace Simcify\Controllers;

use Simcify\Auth;
use Simcify\Database;

class Youtube
{
    public function get()
    {
        $user = Auth::user();
        $data = array(
            "user" => $user,
            "fb_user" => Database::table("facebook_account")->where("user_id", $user->id)->get()
        );
        return view('youtube/view', $data);
    }

    public function getVideo()
    {
        $user = Auth::user();
        $youtube_videos = Database::table("youtube_videos")->where("user_id", $user->id)->get();

        $data = array(
            "user" => Auth::user(),
            "youtube_videos" => $youtube_videos,
        );

        return view('youtube/get-video', $data);
    }

    public function callback()
    {
        $data = array(
            "user" => Auth::user()
        );
        return view('youtube/callback', $data);
    }

    public function callbackAjax()
    {
        $return = array();
        $callback_array = $this->parse_url_hash(input("callback_url"));
        if (isset($callback_array['error']))
            echo "error";

        if (isset($callback_array['state'])) {
            if ($callback_array['state'] == 'pass-through') {
                $google_login_expire = time() + 3600;

                $_SESSION["google_login"] = true;
                $_SESSION["google_login_expire"] = $google_login_expire;
                $_SESSION["google_login_access_token"] = $callback_array['access_token'];
                echo "success";
            }
        }
        //print_r(json_encode($callback_array));
    }

    function parse_url_hash($hash_string)
    {
        $return_array = array();
        $hash_explode = explode("&", $hash_string);
        foreach ($hash_explode as $each_hash_explode) {
            $t_explode = explode("=", $each_hash_explode);
            $return_array[$t_explode[0]] = $t_explode[1];
        }
        return $return_array;
    }

    public function searchVideoAjax()
    {
        $return = array();
        $search_list = $this->seachVideoAPI(env("GOOGLE_API_KEY"), urlencode(input("q")));
        foreach ($search_list['items'] as $each_list) {
            $t_array = array();
            $t_array['user_id'] = input("user_id");
            $t_array['video_id'] = $each_list['id']['videoId'];
            $t_array['video_title'] = $each_list['snippet']['title'];
            $t_array['video_description'] = $each_list['snippet']['description'];
            $t_array['video_thumbnails'] = $each_list['snippet']['thumbnails']['medium']['url'];

            $t_array['channel_id'] = $each_list['snippet']['channelId'];
            $t_array['channel_title'] = $each_list['snippet']['channelTitle'];
            $t_array['json'] = urlencode(json_encode($t_array));
            $return[] = $t_array;
        }

        print_r(json_encode($return));
    }

    public function sendComment()
    {
        header('Content-type: application/json');
        $user = Auth::user();
        
        $result = $this->sendCommentAPI(env("GOOGLE_API_KEY"), $_SESSION["google_login_access_token"], input("video_id"), input("comment"));
        if(isset($result['error'])){
            exit(json_encode(responder("error", "发表评论", json_encode($result['error']), "")));
        }
        $posting_history_id = $this->save_youtube_posting_history($user->id, "Youtube", input("video_id"), input("comment"));
        Customer::addActionLog("Youtube", "Send Comment", "Video ID : " . input("video_id") . ", Posting history ID: " . $posting_history_id);

        exit(json_encode(responder("success", "发表评论", "已成功向该视频发送评论。", "reload()")));

    }

    function sendCommentAPI($key, $access_token, $video_id, $content)
    {
        $ch = curl_init();
        $authorization = "Authorization: Bearer " . $access_token;
        $url = 'https://youtube.googleapis.com/youtube/v3/commentThreads?part=snippet';
        $url .= '&key=' . $key;
        
        $post = array(
            "snippet" => array (
                "videoId" => $video_id,
                "topLevelComment" => array (
                    "snippet" => array (
                        "textOriginal" => $content
                    )
                )
            )
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

    function save_youtube_posting_history($user_id, $social_type, $yt_video_id, $message)
    {
        $insert_array = array(
            "user_id" => $user_id,
            "social_type" => $social_type,
            "yt_video_id" => $yt_video_id,
            "message" => $message
        );

        Database::table("posting_history")->insert($insert_array);
        $posting_history_id = Database::table("posting_history")->insertId();

        return $posting_history_id;
    }

    public function sendCommentView()
    {
        $data = array(
            "user_id" => input("user_id"),
            "video_id" => input("video_id"),
            //"comment_thread" => $this->getCommentThreadAPI(env("GOOGLE_API_KEY"), input("video_id")),
            "comment_thread" => "",
            "page_access_token" => input("page_access_token")
        );

        return view('youtube/send_comment_modal', $data);
    }

    function check_video_set($youtube_videos_array, $t_video_id)
    {
        foreach ($youtube_videos_array as $each_set_video) {
            if ($each_set_video->video_id == $t_video_id)
                return true;
        }
        return false;
    }

    function seachVideoAPI($key, $query)
    {
        $url = 'https://www.googleapis.com/youtube/v3/search?part=snippet,id&maxResults=20&type=video&key=' . $key;
        $url .= '&q=' . $query;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        $data = json_decode(curl_exec($ch), true);

        return $data;
    }

    function getCommentThreadAPI($key, $video_id)
    {
        $url = 'https://youtube.googleapis.com/youtube/v3/commentThreads?part=snippet%2Creplies&&key=' . $key;
        $url .= '&videoId=' . $video_id;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        $data = json_decode(curl_exec($ch), true);

        return $data['items'][0]['id'];
    }

    public function chooseVideoDB()
    {
        header('Content-type: application/json');
        $video_infor_array = input("video_infor");
        if (empty($video_infor_array))
            exit(json_encode(responder("error", "No Videos", "Please choose Video", "")));

        foreach ($video_infor_array as $each_infor) {
            $video_infor = json_decode(urldecode($each_infor->value));
            $video_infor_data = (array) $video_infor;

            Database::table("youtube_videos")->insert($video_infor_data);
            Customer::addActionLog("Youtube", "Set Youtube Video", "Set Video ID : " . $video_infor_data['video_id']);
        }


        exit(json_encode(responder("success", "视频注册", "某些视频设置了高亮显示以发送评论。", "reload()")));
    }

    public function unsetVideoDB()
    {
        header('Content-type: application/json');
        Database::table("youtube_videos")->where("user_id", input("user_id"))->where("video_id", input("video_id"))->delete();
        Customer::addActionLog("Youtube", "Unset Youtube Video", "Unset Video ID : " . input("video_id"));

        exit(json_encode(responder("success", "取消设置 Youtube 视频!", "该视频已成功取消设置。", "reload()")));
    }
}
