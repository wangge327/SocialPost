<?php
namespace Simcify\Models;

use Simcify\Database;

class RoomModel
{
    const Vacant='Vacant';
    const Occupied='Occupied';
    const Unavailable='Unavailable';

    public static function SetVacant($bed_id){
        if ($bed_id>0){  //previous bed should be Vacant status.
            Database::table("beds")->where("id" ,$bed_id)->update(array('student_id' => 0,'status'=>RoomModel::Vacant));
        }
    }

    public static  function getBedStatistic($room_array){
        $return_array = array();
        $total_count = 0;
        $occupied_count = 0;
        $vacant_count = 0;
        foreach($room_array as $each_room){
            foreach($each_room->bed_data as $each_bed){
                $total_count++;
                if($each_bed->status == RoomModel::Occupied)
                    $occupied_count++;
                else if($each_bed->status == RoomModel::Vacant)
                    $vacant_count++;
            }
        }
        $return_array["total_count"] = $total_count;
        $return_array["occupied_count"] = $occupied_count;
        $return_array["vacant_count"] = $vacant_count;
        $return_array["unavailable_count"] = $total_count-$occupied_count-$vacant_count;
        $return_array["occupied_percent"] = round(($occupied_count / $total_count * 100), 1);
        $return_array["vacant_percent"] = round(($vacant_count / $total_count * 100), 1);
        $return_array["unavailable_percent"] = round(100-$return_array["occupied_percent"]-$return_array["vacant_percent"],1);

        return $return_array;
    }
}