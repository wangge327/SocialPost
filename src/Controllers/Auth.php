<?php
namespace Simcify\Controllers;

use Simcify\Auth as Authenticate;
use Simcify\Database;
use Simcify\Models\StudentModel;
use Simcify\Mail;

class Auth{

    /**
     * Get Auth view
     * 
     * @return \Pecee\Http\Response
     */
    public function get() {
        $enabledguest = $guest = $signingLink = false;
        if (isset($_COOKIE['guest'])) {
            $guest = true;
            $guestData = unserialize($_COOKIE['guest']);
            $signingLink = url("Guest@open").$guestData[0]."?signingKey=".$guestData[1];
            $enabledguest = true;
            setcookie("guest", null, time()-30000, '/');
        }
        if (!isset($_GET['secure'])) {
            redirect(url("Auth@get")."?secure=true");
        }
        return view('login', compact("guest","signingLink"));
    }

    /**
     * Sign In a user
     * 
     * @return Json
     */
    public function signin() {
        $signIn = Authenticate::login(
		    input('email'), 
		    input('password'), 
		    array(
		        "rememberme" => true,
		        "redirect" => url(""),
		        "status" => "Suspended"
		    )
		);

        //Sync the MySQL and PHP datetimes
        Database::table('users')->synchronizeTimezone();

        header('Content-type: application/json');
		exit(json_encode($signIn));
    }

    /**
     * Forgot password - send reset password email
     * 
     * @return Json
     */
    public function forgot() {
        $forgot = Authenticate::forgot(
		    input('email'), 
		    env('APP_URL')."/reset/[token]"
		);
        header('Content-type: application/json');
		exit(json_encode($forgot));
    }

    /**
     * Get reset password view
     * 
     * @return \Pecee\Http\Response
     */
    public function getreset($token) {
        return view('reset', array("token" => $token));
    }

    /**
     * Reset password
     *
     * @return Json
     */
    public function reset() {
        if (input('user_id')){   //create payment
            $user_id=input('user_id');
            StudentModel::Update($user_id,
                array("password" => Authenticate::password(input("password")),
                    'lease_end'=>input('lease_end')
                )
            );
            session(config('auth.session'), $user_id);
            $roommate="";
            foreach (input('roommate') as $item) {
                if ($item!=""){
                    Database::table("roommates")->insert(array("user_id"=>$user_id,"name"=>$item));
                    $roommate.="<br>".$item;
                }
            }
            if ($roommate!=""){
                $user = Database::table("users")->find($user_id);
                $message="from Student(".$user->email.")  <strong>".$user->fname." ".$user->lname."</strong><br>".$roommate;
                Mail::send("wd@irhliving.com", "Roommate was requested",
                    array(
                        "message" =>$message
                    ),
                    "basic",null,[],
                    $user->email,
                    $user->fname." ".$user->lname
                );
            }
            $response = array(
                "status" => "success",
                "notify" => false,
//                "callback" => "redirect('".url("Payment@payment")."', true);"
                "callback" => "redirect('".url("Customer@imageUpload")."web_cam_id_passport.php', true);"
            );
            header('Content-type: application/json');
            exit(json_encode($response));
//            exit;
        }
        else{   //reset password
            $reset = Authenticate::reset(
                input('token'),
                input('password')
            );

            header('Content-type: application/json');
            exit(json_encode($reset));
        }
    }

    /**
     * Create an account
     * 
     * @return Json
     */
    public function signup() {

        $companyId = 0;
        $role = "user";


        $signup = Authenticate::signup(
		    array(
		        "fname" => input('fname'),
		        "lname" => input('lname'),
		        "email" => input('email'),
		        "role" => $role,
		        "company" => $companyId,
		        "password" => Authenticate::password(input('password'))
		    ), 
		    array(
		        "authenticate" => true,
		        "redirect" => url(""),
		        "uniqueEmail" => input('email')
		    )
		);

        header('Content-type: application/json');
		exit(json_encode($signup));
    }

    /**
     * Sign Out a logged in user
     *
     */
    public function signout() {
        Authenticate::deauthenticate();
        redirect(url("Auth@get"));
    }
}
