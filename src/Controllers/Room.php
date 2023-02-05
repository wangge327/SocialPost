<?php
namespace Simcify\Controllers;

use Simcify\Models\RoomModel;
use Simcify\Str;
use Simcify\File;
use Simcify\Mail;
use Simcify\Auth;
use Simcify\Database;
use Simcify\Signer;

class Room extends Admin{

    public function getList($page='') {
        $rooms = array();
        $rooms_temp = array();
        if(isset($_REQUEST["builing"]))
            $rooms_temp = Database::table("rooms")->where("building_id", $_REQUEST["builing"])->get();
        else
            $rooms_temp = Database::table("rooms")->get();
        foreach($rooms_temp as $each_room){
            $each_room_temp = array();
            $each_room_temp = $each_room;
            $each_room_temp->bed_data = Room::getBedData($each_room->id);
            $rooms[] = $each_room_temp;
        }
        $bed_statistic = RoomModel::getBedStatistic($rooms);

        $buildings = Database::table("buildings")->get();

        $user = Auth::user();
        return view('room'.$page, compact("user", "rooms", "buildings", "bed_statistic"));
    }

    public function getRoomList() {
        return $this->getList('/room_list');
    }

    public function create(){
        header('Content-type: application/json');
        $employer_data = array(
            "name" => $_POST["name"],
            "building_id" => $_POST["building_id"]
        );

        Database::table("rooms")->insert($employer_data);

        // Action Log
        Customer::addActionLog("Room", "Create Room", "Created a Room : ". $_POST["name"]);

        exit(json_encode(responder("success","Alright", "Room successfully created","reload()")));
    }

    public function createBed(){
        header('Content-type: application/json');
        $employer_data = array(
            "name" => $_POST["name"],
            "room_id" => $_POST["room_id"]
        );

        Database::table("beds")->insert($employer_data);

        // Action Log
        Customer::addActionLog("Bed", "Create Bed", "Created a Bed : ". $_POST["name"]);

        exit(json_encode(responder("success","Alright", "Bed successfully created","reload()")));
    }

    public function reviewRoom($room_id){
        $user = Auth::user();
        $room = Database::table("rooms")->where("id", $room_id)->first();
        $building = Database::table("buildings")->where("id", $room->building_id)->first();
        $beds = $this->getBedData($room_id);

        return view('room/review', compact("user", "room", "building", "beds"));
    }

    public function delete() {
        $room = Database::table("rooms")->find(input("roomid"));

        Database::table("rooms")->where("id", input("roomid"))->delete();
        Database::table("beds")->where("room_id", input("roomid"))->delete();


        // Action Log
        Customer::addActionLog("Room", "Delete Room", "Deleted Room : ". $room->name );

        header('Content-type: application/json');
        exit(json_encode(responder("success", "Room Deleted!", "Student successfully deleted.","reload()")));
    }

    public function ChangeBedStatus(){
        $bed = Database::table("beds")->find(input("bedid"));
        $data=array('status'=>RoomModel::Vacant);
        if ($bed->status==RoomModel::Vacant)
            $data=array('status'=>RoomModel::Unavailable);
        elseif ($bed->status==RoomModel::Occupied)
            RoomModel::SetVacant($bed->id);

        Database::table("beds")->where("id", $bed->id)->update($data);
        // Action Log
        Customer::addActionLog("Bed", "Change Status", $bed->name." ".$data['status']);

        header('Content-type: application/json');
        exit(json_encode(responder("success", "Bed Status", "This Bed status was changed to ".$data['status'],"reload()")));
    }

    public function deleteBed(){
        $bed = Database::table("beds")->find(input("bedid"));
        Database::table("beds")->where("id", input("bedid"))->delete();
        // Action Log
        Customer::addActionLog("Bed", "Delete Bed", "Deleted Bed : ". $bed->name );

        header('Content-type: application/json');
        exit(json_encode(responder("success", "Bed Deleted!", "Bed successfully deleted.","reload()")));
    }

    public function updateViewBed(){
        $data = array(
            "bed" => Database::table("beds")->where("id", input("bedid"))->first()
        );
        return view('extras/update_bed', $data);
    }

    // get state names
    public function getRooms() {
        $json= Database::table("rooms")->where("building_id", input("countryID"))->get();
        header('Content-Type: application/json');
        echo json_encode($json);
    }

    public function updateBed() {

        foreach (input()->post as $field) {
            if ($field->index == "csrf-token" || $field->index == "bedid") {
                continue;
            }
            Database::table("beds")->where("id" , input("bedid"))->update(array($field->index => escape($field->value)));
        }

        // Action Log
        Customer::addActionLog("Bed", "Update Bed", "Changed Bed information: ");

        header('Content-type: application/json');
        exit(json_encode(responder("success", "Alright!", "Bed was successfully updated","reload()")));
    }

    public function roomBedStatus(){
        $user = Auth::user();

        return view('room/room_bed_status', compact("user"));
    }

    function getBeds() {
        $json= Database::table("beds")->where("room_id", input("stateID"))->get();
//        foreach ($json as $bed){
//            $student= Database::table("users")->find($bed->student_id);
//            $bed->lease_start=
//        }
        header('Content-Type: application/json');
        echo json_encode($json);
    }

    function getStudentNameByID($student_id){
        $student = Database::table("users")->find($student_id);
        return $student->fname . " " . $student->lname;
    }

    function getBedData($room_id) {
    	$beds = array();
        $bed_temps = Database::table("beds")->where("room_id", $room_id)->get();
        foreach($bed_temps as $each_bed){
			$each_bed_temp = $each_bed;
            $student = Database::table("users")->find($each_bed->student_id);
			$each_bed_temp->student_name = $student->fname . " " . $student->lname;
            $each_bed_temp->student_name ="<a href='".url('Customer@profile').$student->id."'>".$each_bed_temp->student_name."</a>";
            $each_bed_temp->lease_start = $student->lease_start;
            $each_bed_temp->lease_end= $student->lease_end;
			$beds[] = $each_bed_temp;
		}
        return $beds;
    }

	function getBlockList() {

		$rooms 				= array();
		$rooms_all 			= array();
    	$rooms_temp 		= array();
    	$cols 				= array();
		$cols_set 			= array();
		$rows				= array();
		$rows_set			= array();
		$rows_static_col	= array();
		$floors_set			= array();
		$floors				= array();

		$building_feed = [];
        $rooms_temp = Database::table("rooms")->where("building_id", input("building_id"))->get();

        $rows_set['id'] = Room::autoId();
        $rows_static_col['colSpan'] = '2';
        $rows_static_col['id'] = Room::autoId();
        $rows_static_col['type'] = 'others';
        $rows_static_col['title'] = 'stair';
        $rows_set['cols'] = $rows_static_col;

        $rows[0] = $rows_set;
        $room_floor = array();

        foreach($rooms_temp as $key=> $each_room){

            $room_floor_number = (int)(($each_room->name - ((int)($each_room->name/1000)*1000) ) / 100);
            if($room_floor_number == 0)
                continue;

            $each_room_temp = array();
            $each_room_temp['id'] = $each_room->id;
            $each_room_temp['roomNo'] = $each_room->name;
            $each_room_temp['beds'] = Room::getBlockBedData($each_room->id);
            $each_room_temp['floor'] = $room_floor_number;

            $room_floor[$room_floor_number-1][] = $each_room_temp;
        }


        $building_feed['floors'] = $room_floor;
        $building_feed['floors_prefix'] = ["1st", "2nd", "3rd"];;


        // ==================== Status Start=====================
        foreach($rooms_temp as $each_room){
            $each_room_temp = array();
            $each_room_temp = $each_room;
            $each_room_temp->bed_data = Room::getBedData($each_room->id);
            $rooms[] = $each_room_temp;
        }
        $bed_statistic = RoomModel::getBedStatistic($rooms);
        $status_feed[0]['type'] = 'Building Occupancy '.$bed_statistic['occupied_count'].'('.$bed_statistic['occupied_percent'].'%)';
        $status_feed[0]['count'] = $bed_statistic['occupied_count'];
        $status_feed[0]['percent'] = $bed_statistic['occupied_percent'];
        $status_feed[1]['type'] = 'Building Vacant     '.$bed_statistic['vacant_count'].'('.$bed_statistic['vacant_percent'].'%)';
        $status_feed[1]['count'] = $bed_statistic['vacant_count'];
        $status_feed[1]['percent'] = $bed_statistic['vacant_percent'];
        $building_feed['status'] = $status_feed;

        $building_feed['total_beds'] = $bed_statistic['total_count'];
        // ==================== Status End=====================

        // ==================== All Status Start=====================
        $rooms_all_temp = Database::table("rooms")->get();
        foreach($rooms_all_temp as $each_room){
            $each_room_temp = $each_room;
            $each_room_temp->bed_data = Room::getBedData($each_room->id);
            $rooms_all[] = $each_room_temp;
        }
        $all_bed_statistic = RoomModel::getBedStatistic($rooms_all);
        $all_status_feed[0]['type'] = 'occupied';
        $all_status_feed[0]['count'] = $all_bed_statistic['occupied_count'];
        $all_status_feed[0]['percent'] = $all_bed_statistic['occupied_percent'];
        $all_status_feed[1]['type'] = 'vacant_count';
        $all_status_feed[1]['count'] = $all_bed_statistic['vacant_count'];
        $all_status_feed[1]['percent'] = $all_bed_statistic['vacant_percent'];
        $all_status_feed[2]['count'] = $all_bed_statistic['unavailable_count'];
        $all_status_feed[2]['percent'] = $all_bed_statistic['unavailable_percent'];
        $building_feed['all_total_beds'] = $all_bed_statistic['total_count'];
        $building_feed['all_status'] = $all_status_feed;

        // ==================== Status End===========================

		header('Content-Type: application/json');
        echo json_encode($building_feed, JSON_PRETTY_PRINT);
    }

	function getBlockBedData($room_id) {
    	$beds = array();
		$temp_bads = array();

        $bed_temps = Database::table("beds")->where("room_id", $room_id)->get();
        foreach($bed_temps as $each_bed){
			$temp_bads['id'] = $each_bed->id;
			$temp_bads['status'] = $each_bed->status;
			$temp_bads['bedName'] = $each_bed->name;

			$temp_user = array();
            $student = Database::table("users")->find($each_bed->student_id);

			if(!empty($student)) {
				$temp_user['id'] 	= $student->id;
				$temp_user['name'] 	= $student->fname . " " . $student->lname;
            	$temp_user['gender'] = $student->gender;
            	$temp_user['avatar'] = $student->avatar;
                $temp_user['intern'] = $student->intern;
            	$temp_user['url'] = url('Customer@profile').$student->id;
            	if($student->identifier == "")
                    $temp_user['identifier'] = "Not Set";
            	else
                    $temp_user['identifier'] = $student->identifier;

				$temp_bads['user'] = $temp_user;
			} else {
				$temp_bads['user'] = NULL;
			}

			array_push($beds, $temp_bads);
		}
        return $beds;
    }

	function autoId() {

		$autoId = md5(uniqid(rand(), true));

		return $autoId;
	}

    public static function getBuildingName($building_id){
        $building = Database::table("buildings")->where("id", $building_id)->first();
        return $building->name;
    }
    public static function getRoomName($room_id){
        $room = Database::table("rooms")->where("id", $room_id)->first();
        return $room->name;
    }
    public static function getBedName($bed_id){
        $bed = Database::table("beds")->where("id", $bed_id)->first();
        return $bed->name;
    }
}
