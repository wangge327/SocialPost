<?php

namespace Simcify\Controllers;

use Simcify\Auth;
use Simcify\Database;
use Simcify\Models\FineModel;
use Simcify\Models\OrderModel;
use Simcify\Models\PaymentModel;
use Simcify\Models\StudentModel;

class Dashboard
{

    /**
     * Get dashboard view
     * 
     * @return \Pecee\Http\Response
     */
    public function get()
    {
        $user = Auth::user();
        if ($user->role == "user") {
            // invoice
            //$invoice = Database::table("invoices")->where("student_id" , $user->id)->get();


            $user->phone_number = StudentModel::getPhoneNumber($user);
            $student = $user;
            $user->page_title = "Student Profile";
            return view('dashboard', compact("user", "student"));
        } else {
            $folders = Database::table("folders")->where("company", $user->company)->count("id", "folders")[0]->folders;

            // signed vs unsigned
            $signed = Database::table("files")->where("company", $user->company)->where("status", "Signed")->count("id", "total")[0]->total;
            $unsigned = Database::table("files")->where("company", $user->company)->where("status", "Unsigned")->count("id", "total")[0]->total;

            // pending signing requests 
            $pendingRequests = Database::table("requests")->where("company", $user->company)->where("status", "Pending")->count("id", "total")[0]->total;

            return view('dashboard', compact("user", "folders", "pendingRequests", "signed", "unsigned"));
        }
    }
}
