{{ view("includes/head", $data); }}

<body>
    <script>
        window.fbAsyncInit = function() {
            FB.init({
                appId: '{{env("FACEBOOK_APP_ID")}}',
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
                    var access_token = response.authResponse.accessToken;
                    console.log('Welcome!  Fetching your information.... ');
                    FB.api('/me?fields=email,name,id,birthday,gender', function(response) {
                        var out = [];

                        for (var key in response) {
                            if (response.hasOwnProperty(key)) {
                                out.push(key + '=' + encodeURIComponent(response[key]));
                            }
                        }

                        var redirect_url = '{{env("APP_URL")}}/facebook/callback?' + out.join('&') + "&access_token=" + access_token;
                        location.href = redirect_url;
                    });
                } else {
                    alert('用户取消登录或未完全授权。');
                }
            }, {
                scope: 'public_profile, email, pages_show_list, pages_read_engagement, pages_manage_posts'
            });
        }
    </script>

    <script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_US/sdk.js"></script>

    <!-- header start -->
    {{ view("includes/header", $data); }}
    <!-- sidebar -->
    {{ view("includes/sidebar", $data); }}
    <div class="content">
        <div class="page-title">
            <div class="pull-right page-actions lower">
                <a class="btn btn-primary" onclick="fb_login()">
                    <!--<a class="btn btn-primary" href="<?= htmlspecialchars($loginUrl)  ?> ">-->
                    <!--<i class="ion-plus-round"></i> 添加Facebook 帐户 -->
                    使用Facebook登入
                </a>
            </div>
            <h3>Facebook账号</h3>
            <p>所有添加的 Facebook 帐户列表</p>
        </div>
        <div class="row margin-0">
            <div class="col-md-12" style="padding:0">
                <div class="light-card table-responsive p-b-3em">
                    <table class="table display companies-list" id="data-table">
                        <thead>
                            <tr>
                                <th>Facebook ID</th>
                                <th>Facebook名称 </th>
                                <th>Facebook邮件 </th>
                                <th>创建日期</th>
                                <th class="text-center w-70">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ( count($fb_user) > 0 )
                            @foreach ( $fb_user as $index => $each_fb_user )
                            <tr>
                                <td><strong>{{ $each_fb_user->fb_id }}</strong> </td>
                                <td><strong>{{ $each_fb_user->fb_name }}</strong> </td>
                                <td><strong>{{ $each_fb_user->fb_email }}</strong> </td>
                                <td><strong>{{ $each_fb_user->created_at }}</strong> </td>

                                <td class="text-center">
                                    <div class="dropdown">
                                        <span class="company-action dropdown-toggle" data-toggle="dropdown"><i class="ion-ios-more"></i></span>
                                        <ul class="dropdown-menu" role="menu">
                                            <li role="presentation">
                                                <!--
                                                <a class="fetch-display-click" data="fineid:{{ $fine_fee->id }}|csrf-token:{{ csrf_token() }}" url="<?= url("Facebook@updateview"); ?>" holder=".update-holder" modal="#update" href="">Edit</a> -->
                                                <a class="send-to-server-click" data="tbid:{{ $each_fb_user->id }}|csrf-token:{{ csrf_token() }}" url="<?= url("Facebook@delete"); ?>" warning-title="你确定吗？" warning-message="此Facebook帐户将被删除。" warning-button="Continue" loader="true" href="">删除</a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                            @else
                            <tr>
                                <td colspan="9" class="text-center">这里是空的</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>


    <!-- footer -->
    {{ view("includes/footer"); }}

    <?php $url_para = explode("/", $_SERVER['REQUEST_URI']); ?>
    <script>
        var controller_name = "<?php echo $url_para[1] ?>";
        if (controller_name == "facebook") {
            $(".fb-submenu").addClass("pushy-submenu-open");
        }

        $(document).ready(function() {
            $('#data-table').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    'copyHtml5',
                    'excelHtml5',
                    'csvHtml5'
                ]
            });
        });
    </script>


</body>

</html>