<?php
if (!session_id()) {
	session_start();
}

require_once '../../vendor/autoload.php';

$fb = new Facebook\Facebook([
	'app_id' => '3369220703294243',
	'app_secret' => '791fa65812add8d6cdf2531dba7a8067',
	'default_graph_version' => 'v15.0'
]);

$helper = $fb->getRedirectLoginHelper();

$social_post_app_token = '3369220703294243|9BZR19CwPP-Pk8DOr1nyN0v140g';
