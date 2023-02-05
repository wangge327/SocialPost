<?php
namespace Simcify\Controllers;

use Simcify\Auth;
use Simcify\Database;

class Company{
    public function get() {
    	$companies = Database::table("companies")->get();
    	$companiesData = $companies;

    	$data = array(
    			"user" => Auth::user(),
    			"companies" => $companiesData
    		);
        return view('companies', $data);
    }

    /**
     * Create business user account
     * 
     * @return Json
     */

    public function create() {
        header('Content-type: application/json');
        $slug = $this->slugify($_POST["name"]);
        $company_data = array(
            "name" => $_POST["name"],
            "information" => $_POST["information"],
            "address" => $_POST["address"],
            "slug" => $slug
        );

        Database::table("companies")->insert($company_data);

        // Action Log
        //Customer::addActionLog("Sponsor", "Create Sponsor", "Created a Sponsor : ". $_POST["name"]);

        exit(json_encode(responder("success","Alright", "Sponsor successfully created","reload()")));
    }

    public function delete() {
        // Action Log
        $company = Database::table("companies")->where("id", input("companyid"))->first();
        //Customer::addActionLog("Sponsor", "Delete Sponsor", "Deleted a Employer: ". $company->name);

        Database::table("companies")->where("id", input("companyid"))->delete();

        header('Content-type: application/json');
        exit(json_encode(responder("success", "Sponsor Deleted!", "Sponsor successfully deleted.","reload()")));
    }

    public function updateview() {
        $data = array(
                "company" => Database::table("companies")->where("id", input("companyid"))->first()
            );
        return view('extras/update_company', $data);
    }

    public function update() {
    	
        foreach (input()->post as $field) {
            if ($field->index == "csrf-token" || $field->index == "employerid") {
                continue;
            }
            Database::table("companies")->where("id" , input("companyid"))->update(array($field->index => escape($field->value)));
        }

        header('Content-type: application/json');
        exit(json_encode(responder("success", "Alright!", "Employer was successfully updated","reload()")));
    }

    public function slugify($text, string $divider = '-')
    {
        // replace non letter or digits by divider
        $text = preg_replace('~[^\pL\d]+~u', $divider, $text);

        // transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);

        // trim
        $text = trim($text, $divider);

        // remove duplicate divider
        $text = preg_replace('~-+~', $divider, $text);

        // lowercase
        $text = strtolower($text);

        if (empty($text)) {
            return 'n-a';
        }

        return $text;
    }

}
