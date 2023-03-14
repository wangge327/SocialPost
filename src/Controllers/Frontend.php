<?php

namespace Simcify\Controllers;

use Simcify\Auth;
use Simcify\Database;

class Frontend
{
    public function home()
    {
        $user = Auth::user();

        $data = array(
            "user" => $user
        );
        return view('home', $data);
    }

    public function about()
    {
        $user = Auth::user();

        $data = array(
            "user" => $user
        );
        return view('frontend/about', $data);
    }

    public function contact()
    {
        $user = Auth::user();

        $data = array(
            "user" => $user
        );
        return view('frontend/contact', $data);
    }

    public function policy()
    {
        $user = Auth::user();

        $data = array(
            "user" => $user
        );
        return view('frontend/policy', $data);
    }
}
