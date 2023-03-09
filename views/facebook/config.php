<?php

if (!session_id()) {
	session_start();
}


$fb = new Facebook\Facebook([
	'app_id' => env("FACEBOOK_APP_ID"),
	'app_secret' => env("FACEBOOK_APP_SECRET"),
	'default_graph_version' => env("FACEBOOK_DEFAULT_GRAPH_VERSION"),
	'cookie'     => false,
	'xfbml'       => false
]);

$helper = $fb->getRedirectLoginHelper();
