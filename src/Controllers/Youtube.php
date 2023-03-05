<?php
namespace Simcify\Controllers;

use Simcify\Auth;
use Simcify\Database;
use Simcify\Models\BalanceModel;
use Simcify\Models\OrderModel;

class Youtube{
    public function get() {
        $user = Auth::user();
    	$data = array(
    		"user" => $user,
            "fb_user" => Database::table("facebook_account")->where("user_id", $user->id)->get()
        );
        return view('facebook/view', $data);
    }

    public function getVideo(){
        $user = Auth::user();
        $youtube_videos = Database::table("youtube_videos")->where("user_id", $user->id)->get();

        $data = array(
            "user" => Auth::user(),
            "youtube_videos" => $youtube_videos,
        );

        return view('youtube/get-video', $data);
    }

    public function searchVideoAjax(){
        $return = array();
        $search_list = $this->seachVideoAPI(env("GOOGLE_API_KEY"), input("q"));
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

    function seachVideoAPI($key, $query){
        $url = 'https://www.googleapis.com/youtube/v3/search?part=snippet,id&maxResults=20&type=video&key=' . $key;
        $url .= '&q=' . $query;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        $data = json_decode(curl_exec($ch), true);

        return $data;
    }

    public function chooseVideoDB(){
        header('Content-type: application/json');
        $video_infor_array = input("video_infor");
        if(empty($video_infor_array))
            exit(json_encode(responder("error", "No Videos", "Please choose Video" ,"")));

        foreach($video_infor_array as $each_infor){
            $video_infor = json_decode(urldecode($each_infor->value));
            $video_infor_data = (array) $video_infor;

            Database::table("youtube_videos")->insert($video_infor_data);
            Customer::addActionLog("Youtube", "Set Youtube Video", "Set Video ID : ". $video_infor_data->video_id);
        }


        exit(json_encode(responder("success", "Video Registered", "Some videos is regitered into site for sending commit." ,"reload()")));

    }

    public function unsetVideoDB(){
        header('Content-type: application/json');
        Database::table("youtube_videos")->where("user_id", input("user_id"))->where("video_id", input("video_id"))->delete();
        Customer::addActionLog("Youtube", "Unset Youtube Video", "Unset Video ID : ". input("video_id"));

        exit(json_encode(responder("success", "取消设置 Youtube 视频!", "该视频已成功取消设置。","reload()")));

    }
}
