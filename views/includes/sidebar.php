<?php

use Simcify\Database;

$reports_items = Database::table("report")->get();
?>
<link href="<?= url("/"); ?>assets/css/pushy.css" rel="stylesheet">
<div class="left-bar">
    <div class="slimscroll-menu">
        <li>
            <a href="<?= url("Dashboard@get"); ?>">
                <label class="menu-icon"><i class="ion-ios-speedometer"></i> </label><span class="text">仪表板</span>
            </a>
        </li>
        @if ( $user->role != "user" )
        <li><a href="<?= url("Customer@get"); ?>">
                <label class="menu-icon"><i class="ion-ios-people"></i> </label><span class="text">Members</span>
            </a>
        </li>
        <li hidden><a href="<?= url("Member@get"); ?>">
                <label class="menu-icon"><i class="ion-ios-personadd"></i> </label><span class="text">Import Errors</span>
            </a>
        </li>
        <li><a href="<?= url("Settings@actionLog"); ?>">
                <label class="menu-icon"><i class="ion-ios-list"></i> </label><span class="text">Action Logs</span>
            </a>
        </li>
        <li>
            <a href="<?= url("EmailTemplate@get"); ?>">
                <label class="menu-icon"><i class="ion-ios-list"></i> </label><span class="text">Email Template</span>
            </a>
        </li>

        @endif


        @if ( $user->role == "user" )

        <li class="pushy-submenu fb-submenu" style="cursor:pointer">
            <a>
                <label class="menu-icon"><i class="ion-social-facebook"></i> </label>
                <span class="text">Facebook管理</span>
            </a>
            <ul>
                <li class="pushy-link">
                    <a href="<?= url("Facebook@get"); ?>">
                        <span class="text">Facebook账号</span>
                    </a>
                </li>
                <li class="pushy-link">
                    <a href="<?= url("Facebook@getPage"); ?>">
                        <span class="text">Facebook Page管理</span>
                    </a>
                </li>
                <li class="pushy-link">
                    <a href="<?= url("Facebook@publishPost"); ?>">
                        <span class="text">发布帖子</span>
                    </a>
                </li>
            </ul>
        </li>

        <li class="pushy-submenu yt-submenu" style="cursor:pointer">
            <a>
                <label class="menu-icon"><i class="ion-social-youtube"></i> </label>
                <span class="text">Youtube 管理</span>
            </a>
            <ul>
                <li class="pushy-link">
                    <a href="<?= url("Youtube@get"); ?>">
                        <span class="text">Youtube帐户</span>
                    </a>
                </li>
                <li class="pushy-link">
                    <a href="<?= url("Youtube@getGroup"); ?>">
                        <span class="text">集团管理</span>
                    </a>
                </li>
                <li class="pushy-link">
                    <a href="<?= url("Youtube@sendComment"); ?>">
                        <span class="text">发送评论</span>
                    </a>
                </li>
            </ul>
        </li>

        <li class="pushy-submenu tw-submenu" style="cursor:pointer">
            <a>
                <label class="menu-icon"><i class="ion-social-twitter"></i> </label>
                <span class="text">Twitter管理</span>
            </a>
            <ul>
                <li class="pushy-link">
                    <a href="<?= url("Twitter@get"); ?>">
                        <span class="text">Twitter账号</span>
                    </a>
                </li>
                <li class="pushy-link">
                    <a href="<?= url("Twitter@sendTweetView"); ?>">
                        <span class="text">发送推文管理</span>
                    </a>
                </li>
            </ul>
        </li>



        <!--
        <li class="pushy-submenu posting-submenu" style="cursor:pointer">
            <a>
                <label class="menu-icon"><i class="ion-ios-flower"></i> </label>
                <span class="text">Post管理</span>
            </a>
            <ul>
                <li class="pushy-link">
                    <a href="<?= url("Posting@get"); ?>">
                        <span class="text">Publish Post</span>
                    </a>
                </li>
                <li class="pushy-link">
                    <a href="<?= url("Posting@history"); ?>">
                        <span class="text">Post History</span>
                    </a>
                </li>
            </ul>
        </li>
            -->
        @endif

        <li><a href="<?= url("Settings@get"); ?>">
                <label class="menu-icon"><i class="ion-gear-a"></i> </label><span class="text">设置</span>
            </a>
        </li>
    </div>
</div>