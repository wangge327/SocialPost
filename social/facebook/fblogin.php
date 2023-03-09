<body>
<script>
	window.fbAsyncInit = function() {
		FB.init({
			appId            : '3369220703294243',
			autoLogAppEvents : true,
			xfbml            : true,
			version          : 'v16.0'
		});
	};
</script>
<script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_US/sdk.js"> </script>

</body>
<?php

require("config.php"); // All settings in the $config-Object

//사용자정보에 접근할 권한 옵션. 추가 옵션은 공식 문서 참조
$permissions = ['public_profile'];
$permissions = ['public_profile, email, pages_show_list'];

//로그인 주소 생성. callback 주소 입력
$loginUrl = $helper->getLoginUrl('https://vhost.wang/social/facebook/fb-callback.php', $permissions);

//로그인 버튼 생성
echo '<a href="' . htmlspecialchars($loginUrl) . '" class="button-fb-login">Facebook으로 로그인</a>';
?>
