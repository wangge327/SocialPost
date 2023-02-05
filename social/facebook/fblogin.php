<?php

require("config.php"); // All settings in the $config-Object

//사용자정보에 접근할 권한 옵션. 추가 옵션은 공식 문서 참조
$permissions = ['public_profile'];

//로그인 주소 생성. callback 주소 입력
$loginUrl = $helper->getLoginUrl('https://vhost.wang/social/facebook/fb-callback.php', $permissions);

//로그인 버튼 생성
echo '<a href="' . htmlspecialchars($loginUrl) . '" class="button-fb-login">Facebook으로 로그인</a>';
