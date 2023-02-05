<?php

namespace Simcify\Controllers;

use Simcify\Date;
use Simcify\File;
use Simcify\Auth;
use Simcify\Database;
use DotEnvWriter\DotEnvWriter;
use Simcify\Models\PaymentModel;
use Simcify\Models\ReportModel;

class Settings
{

    /**
     * Get settings view
     * 
     * @return \Pecee\Http\Response
     */
    public function get()
    {
        $user = Auth::user();
        $timezones = Database::table("timezones")->get();
        $company = Database::table("companies")->where("id", Auth::user()->company)->first();
        $reminders = Database::table("reminders")->where("company", Auth::user()->company)->get();
        $payment_reminders = Database::table("payment_reminders")->where("company", Auth::user()->company)->get();
        $currentDateTime = Database::table("users")->getCurrentDatetime();
        if ($user->role == "user") {
            $room =  Database::table("rooms")->where("id", Auth::user()->room_id)->first();
            $bed  = Database::table("beds")->where("id", Auth::user()->bed_id)->first();
            return view('settings', compact("user", "company", "reminders", "timezones", "currentDateTime", "room", "bed"));
        }
        return view('settings', compact("user", "company", "reminders", "payment_reminders", "timezones", "currentDateTime"));
    }

    public function changeOrderBySpecialFee($bed_id, $special_weekly, $special_daily, $previousWeekly)
    {
        ReportModel::SendError("Special Fee was updated bed_id:" . $bed_id . " currently weekly:" . $special_weekly . " previous:" . $previousWeekly);
        $orders = Database::table("orders")->where("bed_id", $bed_id)->where("status", "init")->get();
        if (count($orders) > 0) {
            foreach ($orders as $order) {
                $student = Database::table("users")->find($order->student_id);
                $fees = Database::table("companies")->first();
                if ($student->weekly_rate == $fees->weekly) {
                    $amount = Date::DateDiffInWeeks($order->lease_start, $order->lease_end) * $special_weekly;
                    Database::table("orders")->where("id", $order->id)->update(array('room_fee' => $amount, 'room_due_status' => $amount));
                }
            }
        }
    }

    public function updateAvatar()
    {
        if (isset($_FILES['avatar'])) {
            $errors = array();
            $file_name = $_FILES['avatar']['name'];
            $file_size = $_FILES['avatar']['size'];
            $file_tmp = $_FILES['avatar']['tmp_name'];
            $file_type = $_FILES['avatar']['type'];
            $file_ext = strtolower(end(explode('.', $_FILES['avatar']['name'])));

            $extensions = array("jpeg", "jpg", "png");

            if (in_array($file_ext, $extensions) === false) {
                $errors[] = "extension not allowed, please choose a JPEG or PNG file.";
            }

            if ($file_size > 2097152) {
                $errors[] = 'File size must be excately 2 MB';
            }
            $directory = getcwd() . '/uploads/avatar/';
            $filename = uniqid() . "." . $file_ext;
            try {
                if (empty($errors) == true) {
                    move_uploaded_file($file_tmp, $directory . $filename);
                    if (!empty(Auth::user()->avatar)) {
                        File::delete(Auth::user()->avatar, "avatar");
                    }
                    if (isset($_POST["user_id"]))
                        Database::table(config('auth.table'))->where("id", $_POST["user_id"])->update(array("avatar" => $filename));
                    else
                        Database::table(config('auth.table'))->where("id", Auth::user()->id)->update(array("avatar" => $filename));
                    echo "Success " . $filename;
                } else {
                    print_r($errors);
                }
            } catch (Exception $e) {
                print_r($e->getMessage());
            }
        }
    }

    /**
     * Update profile on settings page
     * 
     * @return Json
     */
    public function updateprofile()
    {
        header('Content-type: application/json;');
        $account = Database::table(config('auth.table'))->where("email", input("email"))->first();
        if (!empty($account) && $account->id != Auth::user()->id) {
            exit(json_encode(responder("error", "Oops", input("email") . " already exists.")));
        }
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
                        if (!empty(Auth::user()->avatar)) {
                            File::delete(Auth::user()->avatar, "avatar");
                        }
                        Database::table(config('auth.table'))->where("id", Auth::user()->id)->update(array("avatar" => $avatar['info']['name']));
                    }
                }
                continue;
            }

            if ($field->index == "csrf-token") {
                continue;
            }

            Database::table(config('auth.table'))->where("id", Auth::user()->id)->update(array($field->index => $field->value));
        }
        exit(json_encode(responder("success", "好吧", "个人信息成功更新", "reload()")));
    }

    /**
     * Update company on settings page
     * 
     * @return Json
     */
    public function updatecompany()
    {
        if (input("security") == 0) {
            header('Content-type: application/json');
            exit(json_encode(responder("error", "Zero Value", "Please input non zero value for Security Deposit")));
        }
        foreach (input()->post as $field) {
            if ($field->index == "csrf-token") {
                continue;
            }

            Database::table("companies")->where("id", Auth::user()->company)->update(array($field->index => escape($field->value)));
        }

        $r = Payment::UpdatePlanNMI(env("NMI_PLAN_DEFAULT_ID"), input("weekly"));

        // Action Log
        Customer::addActionLog("Setting", "Update Fees", "Updated Fees");

        header('Content-type: application/json');
        exit(json_encode(responder("success", "Alright", "Company info successfully updated")));
    }

    /**
     * Update reminders on settings page
     * 
     * @return Json
     */
    public function updatereminders()
    {
        $user = Auth::user();
        if (empty(input("reminders"))) {
            Database::table("companies")->where("id", $user->company)->update(array("reminders" => "Off"));
        } else {
            Database::table("companies")->where("id", $user->company)->update(array("reminders" => "On"));
        }
        Database::table("reminders")->where("company", $user->company)->delete();
        foreach (input("subject") as $index => $subject) {
            $reminder = array(
                "company" => $user->company,
                "days" => input("days")[$index],
                "subject" => escape(input("subject")[$index]),
                "message" => escape(input("message")[$index])
            );
            Database::table("reminders")->insert($reminder);
        }
        header('Content-type: application/json');
        exit(json_encode(responder("success", "Alright", "Check-in Reminders successfully updated")));
    }

    public function updatepaymentreminders()
    {
        $user = Auth::user();
        if (empty(input("reminders"))) {
            Database::table("companies")->where("id", $user->company)->update(array("reminders" => "Off"));
        } else {
            Database::table("companies")->where("id", $user->company)->update(array("reminders" => "On"));
        }
        Database::table("payment_reminders")->where("company", $user->company)->delete();
        foreach (input("subject") as $index => $subject) {
            $reminder = array(
                "company" => $user->company,
                "hours" => input("hours")[$index],
                "subject" => escape(input("subject")[$index]),
                "message" => escape(input("message")[$index])
            );
            Database::table("payment_reminders")->insert($reminder);
        }
        header('Content-type: application/json');
        exit(json_encode(responder("success", "Alright", "Payment Reminders successfully updated")));
    }

    /**
     * Update password on settings page
     * 
     * @return Json
     */
    public function updatepassword()
    {
        header('Content-type: application/json');
        if (hash_compare(Auth::user()->password, Auth::password(input("current")))) {
            Database::table(config('auth.table'))->where("id", Auth::user()->id)->update(array("password" => Auth::password(input("password"))));
            exit(json_encode(responder("success", "好吧", "密码更新成功。", "reload()")));
        } else {
            exit(json_encode(responder("error", "错误", "您输入的密码不正确。")));
        }
    }

    /**
     *
     */
    public function synchronizeTimezone()
    {
        header('Content-type: application/json');
        Database::table('users')->synchronizeTimezone();
        exit(json_encode(responder("success", "Alright", "System time synchronized successfully", "reload()")));
    }

    public function actionLog()
    {
        $action_log = array();
        $t_action_log = Database::table("action_log")->get();
        foreach ($t_action_log as $each_action_log) {
            $person = Database::table("users")->find($each_action_log->admin_id);
            $each_action_log->person = $person->fname . " " . $person->lname;
            $action_log[] = $each_action_log;
        }

        $data = array(
            "user" => Auth::user(),
            "action_log" => $action_log
        );
        return view('action_log', $data);
    }
}
