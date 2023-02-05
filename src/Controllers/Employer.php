<?php
namespace Simcify\Controllers;

use Simcify\Auth;
use Simcify\Database;
use Simcify\Models\OrderModel;

class Employer extends Admin {
    public function get() {
    	$employers = Database::table("employers")->orderBy('name', 'asc')->get();
    	$employersData = array();
        $backgroundColors = array("bg-danger","bg-success","bg-warning","bg-purple");

    	foreach ($employers as $employer){
            $payment_items = "";
    	    if($employer->room_pay == 1)
    	        $payment_items .= " Room Pay,";
            if($employer->administration_pay == 1)
                $payment_items .= " Administration Pay,";
            if($employer->security_pay == 1)
                $payment_items .= " Security Pay,";
            if($employer->laundry_pay == 1)
                $payment_items .= " Laundry Pay,";

            $payment_items = substr($payment_items, 0,-1);

    		$employersData[] = array(
                "employer" => $employer,
                "color" => $backgroundColors[array_rand($backgroundColors)],
                "payment_items" => $payment_items
            );
    	}
    	$data = array(
    			"user" => Auth::user(),
    			"employers" => $employersData
    		);
        return view('employers', $data);
    }

    /**
     * Create business user account
     * 
     * @return Json
     */

    public function create() {
        header('Content-type: application/json');
        $employer_data = array(
            "name" => $_POST["name"],
            "company_info" => $_POST["company_info"],
            "email" => $_POST["email"],
            "room_pay" => $_POST["room_pay"],
            "administration_pay" => $_POST["administration_pay"],
            "security_pay" => $_POST["security_pay"],
            "laundry_pay" => $_POST["laundry_pay"]
        );

        Database::table("employers")->insert($employer_data);

        // Action Log
        Customer::addActionLog("Employer", "Create Employer", "Created a Employer : ". $_POST["name"]);

        exit(json_encode(responder("success","Alright", "Employer successfully created","reload()")));
    }

    public function delete() {
        // Action Log
        $employer = Database::table("employers")->where("id", input("employerid"))->first();
        Customer::addActionLog("Employer", "Delete Employer", "Deleted a Employer: ". $employer->name);

        Database::table("employers")->where("id", input("employerid"))->delete();

        header('Content-type: application/json');
        exit(json_encode(responder("success", "Employer Deleted!", "Employer successfully deleted.","reload()")));
    }

    public function updateview() {
        $data = array(
                "employer" => Database::table("employers")->where("id", input("employerid"))->first()
            );
        return view('extras/update_employer', $data);
    }

    public function update() {
    	
        foreach (input()->post as $field) {
            if ($field->index == "csrf-token" || $field->index == "employerid") {
                continue;
            }
            Database::table("employers")->where("id" , input("employerid"))->update(array($field->index => escape($field->value)));
        }

        $employer = Database::table("employers")->where("id", input("employerid"))->first();
        $users = Database::table("users")->where("employer", input("employerid"))->get();
        foreach($users as $each_user){
            $order = OrderModel::Get($each_user);
            $price = array(
                'room_due_status' => $order->room_fee,
                'security_due_status' => $order->security_fee,
                'administration_due_status' => $order->administration_fee,
                'laundry_due_status' => $order->laundry_fee
            );
            if($employer->room_pay == 1)
                $price['room_due_status'] = 0;
            if($employer->security_pay == 1)
                $price['security_due_status'] = 0;
            if($employer->administration_pay == 1)
                $price['administration_due_status'] = 0;
            if($employer->laundry_pay == 1)
                $price['laundry_due_status'] = 0;

            Database::table("orders")->where("id" , $order->id)->update($price);
        }

        // Action Log
        Customer::addActionLog("Employer", "Edit Employer", "Changed Employer information: ". $employer->name);

        header('Content-type: application/json');
        exit(json_encode(responder("success", "Alright!", "Employer was successfully updated","reload()")));
    }

}
