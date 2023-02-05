<?php
namespace Simcify\Controllers;

use Simcify\Auth;
use Simcify\Database;

class FeeManage extends Admin{
    public function specialFee() {
        $data = array(
            "user" => Auth::user(),
            "buildings" => Database::table("buildings")->get()
        );
        $special_room = array();
        $t_special_room = Database::table("special_room")->get();
        foreach($t_special_room as $each_t_special_room){
            $each_t_special_room->building_name =Room::getBuildingName($each_t_special_room->building_id);
            $each_t_special_room->room_name = Room::getRoomName($each_t_special_room->room_id);
            $each_t_special_room->bed_name = Room::getBedName($each_t_special_room->bed_id);
            $special_room[] = $each_t_special_room;
        }
        $data['special_room'] = $special_room;
        return view('special_fees', $data);
    }

    public function createSpecialFee(){
        header('Content-type: application/json;');
        $data = array(
            "building_id" => input("building_id"),
            "room_id" => input("room_id"),
            "bed_id" => input("bed_id"),
            "weekly" => input("weekly"),
            "daily" => input("daily"),
        );
        Database::table("special_room")->insert($data);
        $fees = Database::table("companies")->first();
        $this->changeOrderBySpecialFee( input("bed_id"), input("weekly"), input("daily"),$fees->wweekly);

        // Action Log
        $t_special_room = Database::table("special_room")->last();
        Customer::addActionLog("Setting", "Add Special Fee", "Added Special Fee(Room:". Room::getRoomName($t_special_room->room_id) .", Bed: ". Room::getBedName($t_special_room->bed_id) ." )");

        exit(json_encode(responder("success", "Added", "Special Room Fee successfully Added","reload()")));
    }

    public function updateSpecialFee(){
        header('Content-type: application/json;');
        $t_special_room = Database::table("special_room")->where("id", input("special_room_id"))->first();
        $this->changeOrderBySpecialFee( $t_special_room->bed_id, input("weekly"), input("daily"),$t_special_room->weely);
        $data = array(
            "weekly" => input("weekly"),
            "daily" => input("daily"),
        );
        Database::table("special_room")->where("id", input("special_room_id"))->update($data);

        // Action Log
        Customer::addActionLog("Setting", "Update Special Fee", "Updated Special Fee(Room:". Room::getRoomName($t_special_room->room_id) .", Bed: ". Room::getBedName($t_special_room->bed_id) ." )");
        exit(json_encode(responder("success", "Updated", "Special Room Fee successfully Updated","reload()")));
    }

    public function updateSpecialFeeView(){
        $special_room = Database::table("special_room")->where("id", input("special_room_id"))->first();
        $special_room->building_name = Room::getBuildingName($special_room->building_id);
        $special_room->room_name = Room::getRoomName($special_room->room_id);
        $special_room->bed_name = Room::getBedName($special_room->bed_id);

        $data = array(
            "special_room" => $special_room
        );
        return view('extras/update_special_fees', $data);
    }

    public function deleteSpecialFee(){
        Database::table("special_room")->where("id", input("special_room_id"))->delete();

        // Action Log
        $t_special_room = Database::table("special_room")->where("id", input("special_room_id"))->first();
        Customer::addActionLog("Setting", "Delete Special Fee", "Deleted Special Fee(Room:". Room::getRoomName($t_special_room->room_id) .", Bed: ". Room::getBedName($t_special_room->bed_id) ." )");

        header('Content-type: application/json');
        exit(json_encode(responder("success", "Return Default!", "Special fee is returned to default.","reload()")));
    }

}
