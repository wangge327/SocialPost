<?php
namespace Simcify\Controllers;

use Simcify\Auth;
use Simcify\Database;
use Simcify\Models\InvoiceModel;
use function Simcify\print_r2;

class Drawer extends Admin {
    public function get() {
    	$batchs = Database::table("drawer_batch")->orderBy("id",false)->get();
    	if(!empty($batchs)){
    		$r_batch = array();
			foreach($batchs as $each_batch){
				$r_batch[] = array(
	                "batch" => $each_batch,
	                "user" => Database::table("users")->find($each_batch->user_id)
	            );
			}
			$batchs = $r_batch;
		}
    	$data = array(
    		"user" => Auth::user(),
            "batchs" => $batchs
        );
        return view('drawer_batch', $data);
    }

    public function viewTransaction($drawer_id){  //currently used
        $user = Auth::user();
        $batch = Database::table("drawer_batch")->where("id", $drawer_id)->first();
        $start_time = $batch->start_time;
        $end_time = $batch->end_time;
        if($batch->status == "open")
            $end_time = date("Y-m-d", strtotime("+1 day"));

        $invoices = Database::table("invoices")->innerJoin('users','invoices.student_id','users.id')->where("invoices`.`created_at",">=", $start_time)->where("invoices`.`created_at","<=", $end_time)->get('payment_mode','unit','price','security_paid','`invoices.created_at`','unit','student_id','name','transaction_id','`invoices.status`');

        $total = array(
            "cash" => 0,
            "credit" => 0,
            "check" => 0,
            "security" => 0
        );

        if(!empty($invoices)){
            $r_invoices = array();
            foreach($invoices as $each_invoice){
                if(($each_invoice->payment_mode == "Cash") || ($each_invoice->payment_mode == "Check") || ( $each_invoice->payment_mode == "Credit Card")){
                    $r_invoices[] =$each_invoice;
                    if($each_invoice->payment_mode == "Cash")
                        $total['cash'] += $each_invoice->price;
                    if($each_invoice->payment_mode == "Check")
                        $total['check'] += $each_invoice->price;
                    if($each_invoice->payment_mode == "Credit Card")
                        $total['credit'] += $each_invoice->price;

                    $total['security'] += $each_invoice->security_paid;
                }
            }
            $invoices = $r_invoices;
        }

        $data = array(
            "user" => $user,
            "batch" => $batch,
            "start_time" => $start_time,
            "end_time" => $end_time,
            "invoices" => $invoices,
            "total" => $total
        );
//        print_r2($data);
        return view('extras/view_transaction', $data);
    }

    /**
     * Create business user account
     * 
     * @return Json
     */

    public function create() {
    	$user = Auth::user();
        header('Content-type: application/json');
        $batch_data = array(
        	"user_id" => $user->id,
            "open_amount" => $_POST["open_amount"],
            "drawer_number" => $_POST["drawer_number"],
            "start_time" => date("Y-m-d H:i:s"),
            "status" => "open"
        );

        $exist_drawer = Database::table("drawer_batch")->where("drawer_number", $_POST["drawer_number"])->first();
        if(!empty($exist_drawer)){
            exit(json_encode(responder("error","Already Exist", "This drawer number is existed.","reload()")));
        }
        else{
            Database::table("drawer_batch")->insert($batch_data);

            // Action Log
            Customer::addActionLog("Drawer", "Create Drawer", "Created new drawer: Number-". $_POST["drawer_number"]);

            exit(json_encode(responder("success","Alright", "Batch successfully created","reload()")));
        }
    }

    public function delete() {
        // Action Log
        $drawer = Database::table("drawer_batch")->where("id", input("batchid"))->first();
        Customer::addActionLog("Drawer", "Delete Drawer", "Deleted drawer: Number-". $drawer->drawer_number);

        Database::table("drawer_batch")->where("id", input("batchid"))->delete();

        header('Content-type: application/json');
        exit(json_encode(responder("success", "Batch Deleted!", "Batch successfully deleted.","reload()")));
    }

    public function closeview() {
        $data = array(
                "batch" => Database::table("drawer_batch")->where("id", input("batchid"))->first()
            );
        return view('extras/close_batch', $data);
    }

    public function close() {
        header('Content-type: application/json');
    	$batch = Database::table("drawer_batch")->where("id", input("batchid"))->first();

    	$close_batch_data = array(
            "closing_amount" => input("closing_amount")
        );
    	Database::table("drawer_batch")->where("id" , input("batchid"))->update($close_batch_data);

        // Action Log
        Customer::addActionLog("Drawer", "Close Drawer", "Closing amount ".input("closing_amount")." was added in Drawer: Number-". $batch->drawer_number);

        exit(json_encode(responder("success", "Alright!", "Closing amount ".input("closing_amount")." was added.","reload()")));
    }
	
	public function view_transaction() {  //old_not used
		$batch = Database::table("drawer_batch")->where("id", input("batchid"))->first();
		$start_time = $batch->start_time;
		$end_time = $batch->end_time;
		if($batch->status == "open")
			$end_time = date("Y-m-d H:i:s");

        $invoices = InvoiceModel::getInvoicesByDate($start_time, $end_time);
    	$data = array(
                "batch" => Database::table("drawer_batch")->where("id", input("batchid"))->first(),
                "start_time" => $start_time,
                "end_time" => $end_time,
                "invoices" => $invoices
            );
        return view('extras/view_transaction', $data);
    }
}
