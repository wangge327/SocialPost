<?php
namespace Simcify\Controllers;

use Simcify\Date;
use Simcify\Models\BalanceModel;
use Simcify\Models\OrderModel;
use Simcify\Models\StudentModel;
use Simcify\Nmi;
use Simcify\Mail;
use Simcify\Auth;
use Simcify\Database;
use Simcify\Models\PaymentModel;

class Checkin {
    public function CheckinDone() {
        if (input("payment_option","notCheckin")=="checkin"){
            $checkin_done  = "done";
            StudentModel::SetCheckin(input("payment_user_id"));
            return view("payment/checkin_pre", compact("checkin_done"));
        }
    }
}
