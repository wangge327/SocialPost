<?php
namespace Simcify\Controllers;

use Simcify\Auth;
use Simcify\Database;
use Simcify\Models\BalanceModel;
use Simcify\Models\OrderModel;

class EmailTemplate extends Admin {
    public function get() {
    	$data = array(
    		"user" => Auth::user(),
            "email_templates" => Database::table("email_templates")->get()
        );    	
        return view('email_template', $data);
    }


    public function create() {
        header('Content-type: application/json');
        $email_cotent = urldecode($_POST["email_content"]);
        $template_data = array(
            "name" => $_POST["template_name"],
            "email_title" => $_POST["email_title"],
            "email_content" => $email_cotent
        );
        Database::table("email_templates")->insert($template_data);

        // Action Log
        Customer::addActionLog("Email Template", "Create Template", "Created Template : ". input("template_name"));

        exit(json_encode(responder("success","Alright", "Email Template successfully created","reload()")));
    }

    public function delete() {
        // Action Log
        $email_template = Database::table("email_templates")->where("id", input("templateid"))->first();
        Customer::addActionLog("Email Template", "Delete Template", "Deleted Template : ". $email_template->name);

        Database::table("email_templates")->where("id", input("templateid"))->delete();
        header('Content-type: application/json');
        exit(json_encode(responder("success", "Email Template Deleted!", "Email Template successfully deleted.","reload()")));
    }

    public function updateview() {
        $data = array(
                "email_templates" => Database::table("email_templates")->where("id", input("templateid"))->first()
            );
        return view('extras/update_email_template', $data);
    }

    public function update() {
        header('Content-type: application/json');
        $email_cotent = urldecode($_POST["email_content"]);
        $template_data = array(
            "name" => $_POST["template_name"],
            "email_title" => $_POST["email_title"],
            "email_content" => $email_cotent
        );
        Database::table("email_templates")->where("id" , input("templateid"))->update($template_data);

        // Action Log
        Customer::addActionLog("Email Template", "Update Template", "Updated Email Template : ". input("template_name"));


        exit(json_encode(responder("success", "Alright!", "Email Template was successfully updated","reload()")));
    }
}
