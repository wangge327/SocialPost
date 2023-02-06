<?php

use Simcify\Database;

$reports_items = Database::table("report")->get();
?>
<link href="<?= url(""); ?>assets/css/pushy.css" rel="stylesheet">
<div class="left-bar">
    <div class="slimscroll-menu">
        <li>
            <a href="<?= url(""); ?>">
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
        <li class="pushy-submenu">
            <a>
                <label class="menu-icon"><i class="ion-ios-list"></i> </label>
                <span class="text">Rooms & Beds</span>
            </a>
            <ul>
                <li class="pushy-link">
                    <a href="<?= url("Room@getRoomList"); ?>">
                        <span class="text">Rooms List</span>
                    </a>
                </li>
                <li class="pushy-link">
                    <a href="<?= url("Room@getList"); ?>">
                        <span class="text">Beds Status</span>
                    </a>
                </li>
                <li class="pushy-link">
                    <a href="<?= url("FeeManage@specialFee"); ?>">
                        <span class="text">Bed Fees</span>
                    </a>
                </li>
            </ul>
        </li>

        <li class="pushy-submenu">
            <a>
                <label class="menu-icon"><i class="ion-ios-list"></i> </label>
                <span class="text">Report</span>
            </a>
            <ul>
                <li class="pushy-link"><a href="<?= url("Report@create"); ?>">New Custom Report</a></li>
                <?php
                if (count($reports_items) > 0) :
                    foreach ($reports_items as $report) :
                ?>

                        <li class="pushy-link">
                            <a href="<?= url("Report@get") . 'browse/' . $report->id ?>"><?php echo $report->name ?></a>
                        </li>
                <?php
                    endforeach;
                endif;
                ?>
            </ul>
        </li>
        <li><a href="<?= url("Employer@get"); ?>">
                <label class="menu-icon"><i class="ion-ios-flower"></i> </label><span class="text">Employers</span>
            </a>
        </li>
        @if (env("SITE_Portal"))
        <li><a href="<?= url("Company@get"); ?>">
                <label class="menu-icon"><i class="ion-ios-flower"></i> </label><span class="text">Sponsors</span>
            </a>
        </li>
        @endif
        <li>
            <a href="<?= url("Facebook@get"); ?>">
                <label class="menu-icon"><i class="ion-calculator"></i> </label><span class="text">Facebook</span>
            </a>
        </li>



        <li><a href="<?= url("Drawer@get"); ?>">
                <label class="menu-icon"><i class="ion-calculator"></i> </label><span class="text">Drawer</span>
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

        <li class="pushy-submenu fb-submenu">
            <a>
                <label class="menu-icon"><i class="ion-social-facebook"></i> </label>
                <span class="text">Facebook管理</span>
            </a>
            <ul>
                <li class="pushy-link">
                    <a href="<?= url("Facebook@get"); ?>">
                        <span class="text">Facebookgit账号</span>
                    </a>
                </li>
                <li class="pushy-link">
                    <a href="<?= url("Facebook@addPage"); ?>">
                        <span class="text">Facebook Page管理</span>
                    </a>
                </li>
            </ul>
        </li>

        <li class="pushy-submenu tw-submenu">
            <a>
                <label class="menu-icon"><i class="ion-social-twitter"></i> </label>
                <span class="text">Twitter管理</span>
            </a>
            <ul>
                <li class="pushy-link">
                    <a href="">
                        <span class="text">Twitter账号</span>
                    </a>
                </li>
                <li class="pushy-link">
                    <a href="">
                        <span class="text">Twitter Page管理</span>
                    </a>
                </li>
            </ul>
        </li>
        @endif

        <li><a href="<?= url("Settings@get"); ?>">
                <label class="menu-icon"><i class="ion-gear-a"></i> </label><span class="text">设置</span>
            </a>
        </li>
    </div>
</div>