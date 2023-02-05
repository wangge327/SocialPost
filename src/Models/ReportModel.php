<?php
namespace Simcify\Models;
use Simcify\Auth;
use Simcify\Database;
use Simcify\Mail;

class ReportModel
{
    const Init='init';
    const Active='active';
    const Closed='closed';

    public function __construct() {
    }

    static public function Get($user)
    {

    }

    static public function SendError($message)
    {
        Mail::send(
            env("DrawerReportMail3"), "Error",
            array(
                "message" =>$message
            )
        );
    }
}