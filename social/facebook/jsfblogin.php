<body>
	<script>
		window.fbAsyncInit = function() {
			FB.init({
				appId: '3369220703294243',
				autoLogAppEvents: false,
				xfbml: false,
				version: 'v16.0'
			});

			FB.getLoginStatus(function(response) {
				if (response.status === 'connected') {
					console.log(response.authResponse);
				}
			});
		};

		function fb_login() {
			FB.login(function(response) {
				if (response.authResponse) {
					console.log('Welcome!  Fetching your information.... ');
					FB.api('/me?fields=email,name,id,birthday,gender', function(response) {
						console.log(response);
					});
				} else {
					console.log('User cancelled login or did not fully authorize.');
				}
			});
		}
	</script>

	<script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_US/sdk.js"></script>

	<button onclick="fb_login()">Facebook Login</button>
</body>