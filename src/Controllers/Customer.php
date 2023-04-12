<?php

namespace Simcify\Controllers;

use Google\Service\Firestore\Order;
use Simcify\Date;
use Simcify\Models\FineModel;
use Simcify\Models\OrderModel;
use Simcify\Models\BalanceModel;
use Simcify\Models\ReportModel;
use Simcify\Models\RoomModel;
use Simcify\Models\StudentModel;
use Simcify\Nmi;
use Simcify\Str;
use Simcify\File;
use Simcify\Mail;
use Simcify\Auth;
use Simcify\Database;
use Simcify\Signer;
use Simcify\Models\PaymentModel;
use GuzzleHttp;

class Customer extends Admin
{
    public function get()
    {
        $db = Database::table("users")->where("company", Auth::user()->company)->where("role", "user")->where('action', '!=', StudentModel::Deleted)->orderBy("id", false);
        if (!empty(input("search"))) {
            $db = $db->where("fname", "LIKE", "%" . input("search") . "%");
        }
        $customers = $db->get();
        $companies = Database::table("companies")->where("id", Auth::user()->company)->first();
        $company_security = $companies->security;
        $company_weekly = $companies->weekly;

        $customersData = array();
        foreach ($customers as $customer) {
            $customersData[] = array(
                "user" => $customer,
                "request" => Database::table("requests")->where("receiver", $customer->id)->first(),
            );
        }
        $user = Auth::user();
        $customers = $customersData;
        $enum = Database::table("users")->enum_values('sponsor');
        $phone_code = Database::table("country_phone")->where("iso3", "<>", "NULL")->where("iso", "<>", "US")->get();
        return view('customers', compact("user", "customers", "enum", "company_security", "company_weekly", "phone_code"));
    }

    public function profile($user_id)
    {
        $user = Auth::user();
        $student = Database::table("users")->find($user_id);

        // invoice
        $invoice = Database::table("invoices")->where("student_id", $student->id)->get();
        // get request
        $requestsData = Database::table("requests")->where("receiver", $student->id)->orderBy("id", false)->first();
        $document_key = Database::table("files")->where("document_key", $requestsData->document)->first()->document_key;

        $order = OrderModel::Get($student);

        $balance_history = Database::table("balance_history")->where("student_id", $student->id)->get();
        // Email Template
        $email_templates = Database::table("email_templates")->get();

        $student_room_fee = PaymentModel::getStudentRoomFee($student);
        $student->weekly_rate = $student_room_fee['week_fee'];
        $student->phone_number = StudentModel::getPhoneNumber($student);
        $student->unpaid = PaymentModel::getPriceBalance($student);

        $phone_code = Database::table("country_phone")->where("iso3", "<>", "NULL")->get();

        $user->page_title = "Student Profile";

        $return_fine_history = FineModel::GetFineHistory($user_id);

        return view('customer/profile', compact("user", "student", "invoice", "return_fine_history", "document_key", "order", "balance_history", "phone_code", "email_templates"));
    }

    public function create()
    {
        header('Content-type: application/json');
        $password = rand(111111, 999999);

        $user_data = array(
            "avatar" => '',
            "company" => Auth::user()->company,
            "pin" => $password,
            "password" => Auth::password($password)
        );

        foreach (input()->post as $field) {
            if ($field->index == "csrf-token") {
                continue;
            }
            $user_data[$field->index] = escape($field->value);
        }
        $user_data['birthday'] = ($user_data['birthday'] == '') ? '1990.01.01' : $user_data['birthday'];

        $email = input('email');

        $signup = Auth::signup(
            $user_data,
            array(
                "uniqueEmail" => $email
            )
        );

        if ($signup["status"] == "success") {
            $signup_user = Database::table("users")->find($signup["user_id"]);

            // Action Log
            Customer::addActionLog("Member", "Create Member", "ID:" . $signup_user->id . " " . input('fname') . " " . input('lname') . " email:" . $email);

            if ($email != "") {
                $this->sendAgreement([$email], $signup_user->id);
                $message = "Email was sent to " . $email;
            }

            exit(json_encode(responder("success", "Member Created", $message, "reload()")));
        } else {
            exit(json_encode(responder("error", "Oops!", $signup["message"])));
        }
    }

    public function createLMSUser($user)
    {
        return;
        $url = env('LMS_REGISTER_SITE_URL') . 'register/create_from_main';

        $post_query = "fname=" . $user->fname . "&";
        $post_query .= "lname=" . $user->lname . "&";
        $post_query .= "email=" . $user->email . "&";
        $post_query .= "mobile=" . $user->phone . "";
        $url .= "?" . $post_query;

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_query);
        curl_setopt($ch, CURLOPT_POST, 1);


        curl_exec($ch);
        $err = curl_error($ch);

        curl_close($ch);
        if ($err)
            echo "cURL Error #:" . $err;
    }

    public function create_employer()
    {
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
        $return_employers = Database::table("employers")->orderBy('name', 'asc')->get();
        $return_options = '<option value="0"></option>';
        foreach ($return_employers as $return_employer) {
            $return_options = $return_options . '<option value="' . $return_employer->id . '">' . $return_employer->name . '</option>';
        }
        $return_options = $return_options . '<option value="other">Other</option>';

        echo $return_options;
    }

    public function createPassword()
    {
        header('Content-type: application/json');
        if (hash_compare(input("password"), input("confirm_password"))) {
            Database::table(config('auth.table'))->where("id", input("user_id"))->update(array("password" => Auth::password(input("password"))));
            exit(json_encode(responder("success", "好吧", "密码设置成功", "window.location.replace('" . env("APP_URL") . "')")));
        } else {
            exit(json_encode(responder("error", "密码错误", "您输入的密码不匹配")));
        }
    }

    public function uploadLease($user_id)
    {
        $student = Database::table("users")->find($user_id);
        $user = Auth::user();
        $user->page_title = 'Upload Lease Document';
        return view('customer/upload_lease', compact("user", "student"));
    }

    public function updateUploadLease()
    {
        try {
            header('Content-type: application/json');

            $select_user = input("select_user");
            if ($_FILES['file']['name'] == "") {
                exit(json_encode(responder("error", "No File", "Please select file to upload.", "reload()")));
            } else {
                $targetPath = getcwd() . '/uploads/lease/' . $_FILES['file']['name'];
                move_uploaded_file($_FILES['file']['tmp_name'], $targetPath);

                StudentModel::Update($select_user, array('lease_upload' => $_FILES['file']['name']));


                exit(json_encode(responder("success", "Uploaded", "Lease Document uploaded successfully.", "reload()")));
            }
        } catch (Exception $e) {
            echo '\nCaught exception: ',  $e->getMessage(), "\n";
        }
    }

    public function changeStatus()
    {
        $user = Database::table("users")->find(input("customerid"));
        $status = input('status');
        if ($status == StudentModel::Suspended) {
            StudentModel::delete($user, StudentModel::Suspended);
        } else
            StudentModel::Update($user->id, array('status' => $status, 'action' => StudentModel::Active));

        Customer::addActionLog("Student", $status . " Student", "ID:" . $user->id . " " . $user->fname . " " . $user->lname . " " . $user->email);

        header('Content-type: application/json');
        exit(json_encode(responder("success", $status, "The student was successfully changed.", "reload()")));
    }

    public function delete()
    {
        $account = Database::table("users")->find(input("customerid"));
        if (!empty($account->avatar)) {
            File::delete($account->avatar, "avatar");
        }
        StudentModel::delete($account, StudentModel::Deleted);
        // Action Log
        Customer::addActionLog("Member", "Delete Member", "ID:" . $account->id . " " . $account->fname . " " . $account->lname . " " . $account->email);
        if (env("SITE_Portal"))
            Zk::delete_person(input("customerid"));

        header('Content-type: application/json');
        exit(json_encode(responder("success", "Member Deleted!", "The member was successfully deleted.", "reload()")));
    }

    public static function addActionLog($action_type, $action_sub_type, $action_content)
    {
        $user = Auth::user();
        $action_log = array(
            "admin_id" => $user->id,
            "action_type" => $action_type,
            "action_sub_type" => $action_sub_type,
            "action_content" => $action_content
        );
        Database::table("action_log")->insert($action_log);
    }



    public function cancel_lease()
    {
        $data = array(
            "user" => Database::table("users")->find(input("customerid")),
            "buildings" => Database::table("buildings")->get(),
            "cancel_lease_items" => Database::table("cancel_lease_item")->get()
        );
        return view('extras/cancel_lease', $data);
    }

    public function updateCancelLease()
    {
        $user = Database::table("users")->find(input("user_id"));
        $cancel_lease_ids_get = input("cancel_lease_item_ids");

        $cancel_lease_ids = array();
        foreach ($cancel_lease_ids_get as $cancel_lease_id) {
            $cancel_lease_ids[] = $cancel_lease_id->value;
        }
        $cancel_lease_history = array(
            "student_id" => input("user_id"),
            "cancel_lease_ids" => json_encode($cancel_lease_ids)
        );
        header('Content-type: application/json');
        $order = Database::table("orders")->where("student_id", input("user_id"))->last();

        Database::table("cancel_lease_history")->insert($cancel_lease_history);
        RoomModel::SetVacant($user->bed_id);
        Database::table("orders")->where("id", $order->id)->update(array("status" => "closed"));

        $cancel_balance = PaymentModel::getCancelBalance($user, $order);

        StudentModel::Update(input("user_id"), array('room_id' => 0, 'bed_id' => 0, 'status' => StudentModel::Terminated, 'unit' => '', 'action' => StudentModel::Canceled));

        BalanceModel::addBalanceHistory($user, $cancel_balance, "Cancel Lease", "Balance returned", 0, BalanceModel::None, 0);

        // Action Log
        $message = $user->fname . " " . $user->lname . "'s lease was canceled.";
        Customer::addActionLog("Student", "Cancel Lease", $message);
        exit(json_encode(responder("success", "Cancel Lease", $message, "reload()")));
    }

    public function add_fine()
    {
        $student = Database::table("users")->find(input("customerid"));
        $room_name = Database::table("rooms")->find($student->room_id)->name . '-' . Database::table("beds")->find($student->room_id)->name;
        $record_person = array();
        $fine_fees = Database::table("fine_fees")->get();

        $data = array(
            "user" => $student,
            "buildings" => Database::table("buildings")->get(),
            "fine_fees" => $fine_fees,
            "record_person" => $record_person,
            "room_name" => $room_name
        );

        return view('extras/add_fine', $data);
    }

    public function add_fine_old()
    {
        $student = Database::table("users")->find(input("customerid"));
        $room_name = Database::table("rooms")->find($student->room_id)->name . '-' . Database::table("beds")->find($student->room_id)->name;
        $fine_fees = Database::table("fine_fees")->get();

        $data = array(
            "user" => $student,
            "buildings" => Database::table("buildings")->get(),
            "fine_fees" => $fine_fees,
            "room_name" => $room_name
        );

        return view('extras/add_fine', $data);
    }

    public function fine_history()
    {
        $student = Database::table("users")->find(input("customerid"));
        $room_name = Database::table("rooms")->find($student->room_id)->name . '-' . Database::table("beds")->find($student->room_id)->name;
        $fine_history = Database::table("fine_history")->find(input('fine_history_id'));
        $record_person = Database::table("users")->find($fine_history->record_person);
        $fine_fees = array();
        $fine_ids = json_decode($fine_history->fine_id);
        foreach ($fine_ids as $each_fine_ids) {
            $fine_fees[] = Database::table("fine_fees")->where("id", $each_fine_ids->value)->first();
        }

        $data = array(
            "user" => $student,
            "buildings" => Database::table("buildings")->get(),
            "fine_fees" => $fine_fees,
            "fine_note" => $fine_history->note,
            "record_person" => $record_person,
            "room_name" => $room_name
        );
        return view('extras/add_fine_history', $data);
    }

    public function updateview()
    {
        $data = array(
            "student" => Database::table(input("table"))->where("id", input("customerid"))->first(),
            "enum" => Database::table("users")->enum_values('sponsor'),
            "employers" => Database::table("employers")->orderBy('name', 'asc')->get(),
            "table" => input("table")
        );
        return view('extras/update_student', $data);
    }

    public function update()
    {
        $account = Database::table(input("table"))->where("id", input("customerid"))->first();
        foreach (input()->post as $field) {
            if ($field->index == "avatar") {
                if (!empty($field->value)) {
                    $avatar = File::upload(
                        $field->value,
                        "avatar",
                        array(
                            "source" => "base64",
                            "extension" => "png"
                        )
                    );

                    if ($avatar['status'] == "success") {
                        if (!empty($account->avatar)) {
                            File::delete($account->avatar, "avatar");
                        }
                        Database::table(input("table"))->where("id", input("customerid"))->update(array("avatar" => $avatar['info']['name']));
                    }
                }
                continue;
            }
            if ($field->index == "csrf-token" || $field->index == "customerid" || $field->index == "table" || $field->index == "print_lease") {
                continue;
            }
            Database::table(input("table"))->where("id", input("customerid"))->update(array($field->index => escape($field->value)));
        }
        header('Content-type: application/json');
        exit(json_encode(responder("success", "Alright", "Student data successfully updated", "reload()")));
    }

    public function sendAgreement($emails, $user_id)
    {  //send document sign
        header('Content-type: application/json');
        $chainEmails = $chainPositions = '';
        $actionTakenBy = escape('Hiawatha');

        $activity = 'Signing request sent to <span class="text-primary">' . implode(", ", $emails) . '</span> by <span class="text-primary">' . $actionTakenBy . '</span>.';

        foreach ($emails as $index => $email) {
            $signingKey = Str::random(32);
            $signingLink = env("APP_URL") . "/view/" . $signingKey;

            $request = array("sender_note" => escape(input("message")),  "chain_emails" => $chainEmails, "chain_positions" => $chainPositions, "company" => 1, "document" => "", "signing_key" => $signingKey, "positions" => "", "email" => $email, "sender" => 1, "receiver" => $user_id);

            Database::table("requests")->insert($request);

            StudentModel::Update($user_id, array('sign_status' => StudentModel::Sent));

            $send = Mail::send(
                $email,
                "多个社交帖子登录",
                array(
                    "title" => "注册要求",
                    "subtitle" => "单击下面的链接以响应邀请。",
                    "buttonText" => "注册",
                    "buttonLink" => $signingLink,
                    "message" => "您已受邀签署社交发布协议 <br> 单击上面的链接创建密码。<br><br>谢谢<br>" . env("APP_NAME") . " 团队
                    "
                ),
                "withbutton"
            );
            if (!$send) {
                exit(json_encode(responder("error", "Email Sender Error!", $send->ErrorInfo)));
            }
        }
    }

    public function sendCustomEmail()
    {
        header('Content-type: application/json');
        $user = Auth::user();
        $student = Database::table("users")->find(input("student_id"));
        $mail_title = "";
        $mail_content = "";
        $email_switch = input("email_switch");
        if ($email_switch) {
            $mail_title = input("mail_title");
            $mail_content = input("mail_content");
        } else {
            $mail_template = Database::table("email_templates")->where("id", input("email_template_id"))->first();
            $mail_title = $mail_template->email_title;
            $mail_content = $mail_template->email_content;
        }

        //  Send Email
        $send = Mail::send(
            $student->email,
            $mail_title,
            array(
                "message" => $mail_content
            )
        );


        if ($send) {
            $mail_data = array(
                "sender_id" => $user->id,
                "reciever_id" => $student->id,
                "title" => $mail_title,
                "content" => $mail_content,
                "check_sent" => 1
            );
        } else {
            $mail_data = array(
                "sender_id" => $user->id,
                "reciever_id" => $student->id,
                "title" => $mail_title,
                "content" => $mail_content,
                "check_sent" => 0
            );
        }

        Database::table("custom_mail")->insert($mail_data);

        exit(json_encode(responder("success", "Email Sent!", "An Email sent to " . $student->email, "reload()")));
    }

    public function sendAllEmail()
    {
        $user = Auth::user();
        $all_students = Database::table("users")->where("role", "user")->where("status", "<>", StudentModel::Terminated)->get();
        foreach ($all_students as $each_student) {
            $mail_data = array(
                "sender_id" => $user->id,
                "reciever_id" => $each_student->id,
                "title" => input("mail_title"),
                "content" => input("mail_content")
            );
            Database::table("custom_mail")->insert($mail_data);
        }

        header('Content-type: application/json');
        exit(json_encode(responder("success", "Email Sent!", "Email sent to all students ", "reload()")));
    }

    public function resendDocusign()
    {
        $email = input("email");
        $this->sendAgreement([$email], input('user_id'));
        header('Content-type: application/json');
        exit(json_encode(responder("success", "Docusign Email Sent!", "Docusign Email was sent.", "reload()")));
    }

    public function sendCheckin()
    {
        $email = input("email");
        $reminder = Database::table("reminders")->first();

        $token = Str::random(32);
        $data = array('token' => $token);
        Database::table('users')->where("email", $email)->update($data);
        $link = env("APP_URL") . "/checkin?token=" . $token;

        Mail::send(
            $email,
            $reminder->subject,
            array(
                "title" => "Check-in reminder",
                "subtitle" => "Click the link below to check-in.",
                "buttonText" => "Check-in now",
                "buttonLink" => $link,
                "message" => $reminder->message
            ),
            "withbutton"
        );
        //
        header('Content-type: application/json');
        exit(json_encode(responder("success", "Email Sent!", "Checkin link email sent.", "reload()")));
    }

    public function set_arrived()
    {
        $user = Database::table("users")->find(input("user_id"));
        OrderModel::ChangeOrderDate($user, date('Y-m-d'));
        Customer::addActionLog("Student", "Arrived", "ID:" . $user->id . " " . $user->fname);

        header('Content-type: application/json');
        exit(json_encode(responder("success", "Status was changed to Arrived!", "Lease was started.", "reload()")));
    }
}
