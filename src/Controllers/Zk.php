<?php
namespace Simcify\Controllers;

use Simcify\Auth;
use Simcify\Database;

define("ZK_Log",$_SERVER['DOCUMENT_ROOT']."/log/zk.log");

class Zk extends Admin{
    public function index($user_id) {
        $student = Database::table("users")->find($user_id);
        $data=Zk::getPerson($student);
        if (input('cmd')=='new_key'){
            $data['cardNo'] = input('card_number');
        }
        else if (input('cmd')=='disable_key'){
            $data['cardNo'] = '';
        }
        else if (input('cmd')=='Enable'){
            $data['isDisabled'] = true;
        }
        else if (input('cmd')=='Disable'){
            $data['isDisabled'] = false;
        }
        if (input('cmd'))
            Zk::curl("person/add",$data);

        $student->cardNo=$data['cardNo']?$data['cardNo']:"Disabled";
        $student->zk_status=$data['isDisabled']?'Disable':'Enable';

        $user = Auth::user();
        $user->page_title='Door Access';
        return view('customer/zk_index', compact("user", "student"));
    }

    static public function AddCard($student,$card){
        $data=Zk::getPerson($student);
        $data['cardNo'] = $card;
        Zk::curl("person/add",$data);
    }

    static public function getPerson($student){
        $zkRes=ZK::curl("person/get/".$student->id);
        if ($zkRes['message']=="Person does not exist"){
            Zk::addPerson($student);
            return Zk::getPerson($student);
        }
        elseif ($zkRes['message']="success"){
            return $zkRes['data'];
        }
        return $zkRes['message'];
    }

    static public function addPerson($student){
        $post = [
            "accEndTime" => date('Y-m-d H:i:s', strtotime($student->lease_end)),
            "accLevelIds" => "1",
            "accStartTime" => date('Y-m-d H:i:s', strtotime($student->lease_start)),
            "email" => $student->email,
            "gender" => $student->gender=="Male"?"M":"F",
            "hireDate" => date('Y-m-d'),
            "lastName" => $student->lname,
            "mobilePhone" => $student->phone,
            "name" => $student->fname,
            "pin" => $student->id,
        ];
        Zk::curl("person/add",$post);
    }

    static public function delete_person($id){
        ZK::curl("person/delete/".$id,[]);
    }

    static public function curl($url,$data="Get"){
        error_log("\n".$url."   ".date("Y-m-d H:i:s")."\n", 3,ZK_Log);
        $url = env("ZK_URL") . "api/".$url."?access_token=" . env("ZK_ACCESS_TOKEN");
        $ch=curl_init($url);
        if ($data!="Get"){   //POST request
            error_log(print_r($data,true), 3,ZK_Log);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        $result = curl_exec($ch);
        curl_close($ch);
        $result=json_decode($result,true);  //covert to Array  when true
        error_log(print_r($result,true), 3,ZK_Log);
        return $result;
    }
}
