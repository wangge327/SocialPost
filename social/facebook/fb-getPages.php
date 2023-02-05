<?php

require("config.php"); // All settings in the $config-Object

$access_token_string = 'EAAMgXEHNwxABALRsn1kcAPZCuJcZALuFguwkMGlVSQPaFtrZAnyOI0Eo2y6mbw3d0FFE7ZCcZBoFzTAdEKp22spgWIJiUtRG56g9tEM9Y8ZAdF8IC0Lmfg1sTFWq79V4mCgqZBbuiSlGielCSkgfrFOWxPYmJGZCOHvKt8B2qsBSW6ddDzQ5HGay10hMwMsqma2cqBy4OcoiqmMZC7lz704fWCWDZCKiGOAE9Is7ZCTp5JjfAZDZD';
$user_details = "https://graph.facebook.com/me&access_token=" . $access_token_string;

$response = file_get_contents($user_details);
$response = json_decode($response);

print_r($response);