<header>
    <!-- Hambager -->
    <div class="humbager">
        <i class="ion-navicon-round"></i>
    </div>
    <!-- logo -->
    <div class="logo">
        <a href="<?= url("/"); ?>">
            <!--                --><?php //echo SITE_TITLE; 
                                    ?>
            <!--<span class="hidden-xs"> | HIAWATHA Admin</span>-->
            <img src="<?= url("/"); ?>uploads/app/{{ env('APP_LOGO'); }}" class="img-responsive">
        </a>
    </div>

    <!-- top right -->
    <ul class="nav header-links pull-right">
        <!--            <li class="notify  hidden-xs">-->
        <!--                <a href="{{ url('Notification@get') }}" class="notification-holder">-->
        <!--                    <span class="notifications">-->
        <!--                        <i class="notifications-count ion-ios-bell"></i>-->
        <!--                    </span>-->
        <!--                </a>-->
        <!--            </li>-->

        <li class="profile">
            <div class="dropdown">
                <span class="dropdown-toggle" data-toggle="dropdown">
                    <span class="profile-name"> <span class="hidden-xs"> {{ $user->fname }} </span> <i class="ion-ios-arrow-down"></i> </span>
                    <span class="avatar">
                        @if( !empty($user->avatar) )
                        <img src="<?= url("/"); ?>uploads/avatar/{{ $user->avatar }}" class="user-avatar img-circle">
                        @else
                        <img src="<?= url("/"); ?>assets/images/avatar.png" class="user-avatar">
                        @endif
                    </span>
                </span>
                <ul class="dropdown-menu" role="menu" aria-labelledby="menu1">
                    <li role="presentation"><a role="menuitem" href="<?= url("Settings@get"); ?>">
                            <i class="ion-ios-person-outline"></i> Profile</a></li>
                    <li role="presentation" class="divider"></li>
                    <li role="presentation"><a role="menuitem" href="<?= url("Settings@get"); ?>">
                            <i class="ion-ios-gear-outline"></i> Settings</a></li>
                    <li role="presentation" class="divider"></li>
                    <li role="presentation"><a role="menuitem" href="<?= url("Auth@signout"); ?>">
                            <i class="ion-ios-arrow-right"></i> Logout</a></li>
                </ul>
            </div>
        </li>
    </ul>
</header>

<script type="text/javascript">
    window._mfq = window._mfq || [];
    (function() {
        var mf = document.createElement("script");
        mf.type = "text/javascript";
        mf.defer = true;
        mf.src = "//cdn.mouseflow.com/projects/<?= env('Mouseflow_Script', 'db8e75a0-90d3-4cfb-9a62-a7e091e1e5be.js') ?>";
        document.getElementsByTagName("head")[0].appendChild(mf);
    })();
</script>