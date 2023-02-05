<?php

namespace Simcify\Models;

use Simcify\Database;

class StudentModel
{
    //Action
    const Active = 'Active';  //Student has completed check in process
    const Checked_Out = 'Checked Out';
    const Canceled = 'Canceled';
    const Suspended = 'Suspended'; //Admin has suspended student
    const Deleted = 'Deleted';

    //status
    const Created = 'Created';
    const Arrived = 'Arrived';
    const Extended = 'Extended';
    const Terminated = 'Terminated';  //Student has completed checkout process

    //Sign_status
    const Pending = 'Pending';
    const Sent = 'Sent';
    const Signed = 'Signed';
    const Lease_on_File = 'Lease on File';

    static public $fields = [
        'fname' => ['name' => 'First Name'],
        'lname' => ['name' => 'Last Name'],
        'email' => [
            'name' => 'Email',
            'width' => 25
        ],
        'phone' => ['name' => 'Phone Number'],
        'gender' => [
            'name' => 'Gender',
            'width' => 10
        ],
        'address' => ['name' => 'Address'],
        'city' => ['name' => 'City'],
        'state' => ['name' => 'State'],
        'country' => ['name' => 'Country'],
        'zip' => [
            'name' => 'Zip Code',
            'width' => 10
        ],
        'employer' => ['name' => 'Employer'],
        'sponsor' => [
            'name' => 'sponsor',
            'formula' => '"None,AAG,Intrax,CIEE,Interexchange,Greenheart,Spirit,Asse,Aspire,Geovision,CCI,CCUSA,GEC"'
        ],
        'lease_start' => [
            'name' => 'Move In Date',
            'width' => 16
        ],
        'lease_end' => [
            'name' => 'Move Out Date',
            'width' => 16
        ],
        'security_deposit' => [
            'name' => 'Security Deposit',
            'width' => 16
        ],
        'lease_type' => [
            'name' => 'Lease type',
            'formula' => '"English,Spanish"',
            'width' => 10
        ],
        'intern' => [
            'name' => 'Intern',
            'width' => 10
        ],
        'extra_note' => ['name' => 'Notes']
    ];

    public static function Update($id, $array)
    {
        Database::table("users")->where("id", $id)->update($array);
    }

    public static function SetCheckin($id)
    {
        StudentModel::Update($id, array('is_check_in' => true));
    }

    public static function getPhoneCodeByIso($iso)
    {
        $country_phone = Database::table("country_phone")->where("iso", $iso)->first();
        return $country_phone->phonecode;
    }

    public static function getPhoneNumber($user)
    {
        return "+" . StudentModel::getPhoneCodeByIso($user->phone_country) . " " . $user->phone;
    }

    public static function delete($user, $action)
    {
        StudentModel::Update($user->id, array('status' => StudentModel::Terminated, 'action' => $action));
    }
}
