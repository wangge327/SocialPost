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

if (!isset($_GET['code'])) {
    unset($_SESSION['twitter_oauth2state']);
    unset($_SESSION['twitter_oauth2verifier']);

    // Optional: The default scopes are 'tweet.read', 'users.read',
    // and 'offline.access'. You can change them like this:
    $options = [
        'scope' => [
            'tweet.read',
            'tweet.write',
            'tweet.moderate.write',
            'users.read',
            'follows.read',
            'follows.write',
            'offline.access',
            'space.read',
            'mute.read',
            'mute.write',
            'like.read',
            'like.write',
            'list.read',
            'list.write',
            'block.read',
            'block.write',
            'bookmark.read',
            'bookmark.write',
        ],
    ];


    // If we don't have an authorization code then get one
    $authUrl = $provider->getAuthorizationUrl($options);

    $_SESSION['twitter_oauth2state'] = $provider->getState();

    // We also need to store the PKCE Verification code so we can send it with
    // the authorization code request.
    $_SESSION['twitter_oauth2verifier'] = $provider->getPkceVerifier();
    $_SESSION['site_user_id'] = $_GET['uid'];

    header('Location: '.$authUrl);

}