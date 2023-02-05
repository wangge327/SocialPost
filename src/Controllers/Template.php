<?php
namespace Simcify\Controllers;

use Simcify\Str;
use Simcify\File;
use Simcify\Auth;
use Simcify\Signer;
use Simcify\Database;

class Template{

    /**
     * Get templates view
     * 
     * @return \Pecee\Http\Response
     */
    public function get() {
    	$user = Auth::user();
        return view('templates', compact("user"));
    }

    /**
     * Upload a file
     * 
     * @return Json
     */
    public function uploadfile() {
        header('Content-type: application/json');
        $user = Auth::user();
        $actionTakenBy = escape($user->fname.' '.$user->lname);
        /*
         * Check, whether IP address register is allowed in .env
         * If yes, then capture the user's IP address
         */
        if (env('REGISTER_IP_ADDRESS_IN_HISTORY') == 'Enabled') {
            $actionTakenBy .= ' ['.getUserIpAddr().']';
        }
        $data = array(
                        "company" => $user->company,
                        "uploaded_by" => $user->id,
                        "name" => input("name"),
                        "folder" => 1,
                        "file" => $_FILES['file'],
                        "is_template" => "Yes",
                        "source" => "form",
                        "document_key" => Str::random(32),
                        "activity" => 'Template uploaded by <span class="text-primary">'.$actionTakenBy.'</span>.'
                    );
        $upload = Signer::upload($data);
        if ($upload['status'] == "success") {
            exit(json_encode(responder("success", "", "","documentsCallback()", false)));
        }else{
            exit(json_encode(responder("error", "Oops!", $upload['message'])));
        }
    }

    /**
     * Create a template version of a file
     * 
     * @return Json
     */
    public function create() {
        header('Content-type: application/json');
        $document = Database::table("files")->where("document_key", input("document_key"))->first();
        $templateId = Signer::duplicate($document->id);
        Database::table("files")->where("id", $templateId)->update(array("is_template" => "Yes"));
        $template = Database::table("files")->where("id", $templateId)->first();
        $url = url("Document@open").$template->document_key;
        exit(json_encode(responder("success", "Created!", "Template created, click continue to view.","redirect('".$url."')")));
    }
    /**
     * Get documents view
     * 
     * @return \Pecee\Http\Response
     */
    public function fetch() {
        $user = Auth::user();
        $folders = array();
        $documents = Database::table("files")
                                          ->where("company", $user->company)
                                          ->where("folder", 1)
                                          ->where("is_template", "Yes")
                                          ->orderBy("id", false)
                                          ->get();
        foreach ($documents as $key => $document) {
            if ($user->role == "user" && $document->uploaded_by != $user->id) {
                unset($documents[$key]);
            }
            if ($user->id == $document->uploaded_by || $document->accessibility == "Everyone") {
                continue;
            }
            if ($document->accessibility == "Only Me" && $user->id != $document->uploaded_by) {
                unset($documents[$key]);
            }
            $giveAccess = false;
            if ($document->accessibility == "Departments") {
                $allowedDepartments = json_decode($document->departments);
                foreach ($allowedDepartments as $department) {
                    $userDepartments = Database::table("departmentmembers")->where("department", $department)->where("member", $user->id)->get("department");
                    if (count($userDepartments) > 0) {
                        $giveAccess = true;
                        break;
                    }
                }
            }
            if (!$giveAccess) {
                unset($documents[$key]);
            }
        }
        return view('extras/documents', compact("user", "folders", "documents"));
    }

}
