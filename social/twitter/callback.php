<?php
session_start();

require_once '../../vendor/autoload.php';
use Simcify\Application;
use Simcify\Auth;
use Simcify\Database;

$app = new Application();

$provider = new Smolblog\OAuth2\Client\Provider\Twitter([
    'clientId'          => env("TWITTER_CLIENT_ID"),
    'clientSecret'      => env("TWITTER_CLIENT_SECRET"),
    'redirectUri'       => env("APP_URL")."/social/twitter/callback.php"
]);

if (empty($_GET['state']) || ($_GET['state'] !== $_SESSION['twitter_oauth2state'])) {

	unset($_SESSION['twitter_oauth2state']);
	exit('Invalid state');

} else {

	try {

		// Try to get an access token (using the authorization code grant)
		$token = $provider->getAccessToken('authorization_code', [
			'code' => $_GET['code'],
			'code_verifier' => $_SESSION['twitter_oauth2verifier'],
		]);

		// Optional: Now you have a token you can look up a users profile data
		// We got an access token, let's now get the user's details
        $twitter_user = $provider->getResourceOwner($token);
        $twitter_user = accessProtected($twitter_user, "response");

		// Use these details to create a new profile
		$_SESSION['twitter_token'] = $token->getToken();

        $db_data = array(
            "user_id" => $_SESSION["site_user_id"],
            "twitter_id" => $twitter_user["id"],
            "twitter_name" => $twitter_user["username"],
            "twitter_token" => $token->getToken(),
            "updated_time" => date("Y-m-d H:i:s"),
        );

		$twitter_oauth = Database::table("twitter_oauth")->where("user_id", $_SESSION["site_user_id"])->first();

        if (empty($twitter_oauth)) {
            Database::table("twitter_oauth")->insert($db_data);
        } else {
            Database::table("twitter_oauth")->where("user_id", $_SESSION["site_user_id"])->update($db_data);
        }

        header('Location: '.env("APP_URL")."/twitter");

	} catch (Exception $e) {
		echo '<pre>';
		print_r($e);
		echo '</pre>';

		// Failed to get user details
		exit('Oh dear...');
	}
}

function accessProtected($obj, $prop) {
    $reflection = new ReflectionClass($obj);
    $property = $reflection->getProperty($prop);
    $property->setAccessible(true);
    return $property->getValue($obj);
}
