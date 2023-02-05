<?php
namespace Simcify\Controllers;

use Simcify\Auth;


class Admin{
    public $user;
    public function __construct() {
        $this->user = Auth::user();
        if ($this->user->role == "user") {
            return view('errors/404_permission');
        }
    }
}
