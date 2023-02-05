<?php include "includes/head.php" ?>

<body>

    <!-- header start -->
    {{ view("includes/header", $data); }}

    <!-- sidebar -->
    {{ view("includes/sidebar", $data); }}

    <div class="content">
        <div class="page-title">
            <h3>Notifications</h3>
        </div>
        <div class="row">
            <!-- Notification start -->
            <div class="col-md-12 notifications-holder">

                @if ( count($requests) > 0 )
                @foreach ( $requests as $request )
                <div class="light-card notification-item unread">
                    <div class="notification-item-image bg-warning btn-round">
                        <span><i class="ion-ios-bell-outline"></i></span>
                    </div>
                    <span class="label label-warning">Important!</span>
                <p>You have been invited you to sign <a href="{{ url('Document@open').$request->document.'?signingKey='.$request->signing_key }}"><span class="text-primary">{{$request->file}}</span></a>.</p>
                </div>
                @endforeach
                @endif


                @if ( count($notifications) > 0 )
                @foreach ( $notifications as $notification )
                <div class="light-card notification-item">
                    @if ( $notification->type == "accept" )
                    <div class="notification-item-image bg-success btn-round">
                        <span><i class="ion-ios-checkmark"></i></span>
                    </div>
                    @elseif ( $notification->type == "decline" )
                    <div class="notification-item-image bg-danger btn-round">
                        <span><i class="ion-ios-close"></i></span>
                    </div>
                    @else
                    <div class="notification-item-image bg-warning btn-round">
                        <span><i class="ion-ios-bell-outline"></i></span>
                    </div>
                    @endif
                    <div class="pull-right">
                        <span class="delete-notification" data-id="{{ $notification->id }}"><i class="ion-close-round"></i></span>
                    </div>
                    <p>{{ $notification->message }}</p>
                </div>
                @endforeach
                @else
                <div class="center-notify">
                    <i class="ion-ios-information-outline"></i>
                    <h3>It's empty here!</h3>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- footer -->
    {{ view("includes/footer"); }}

    <script src="<?=url("");?>assets/js/simcify.min.js"></script>
    <script>
        var deleteNotificationUrl = '<?=url("Notification@delete");?>';
        $(document).ready(function() {
            $(".bubble").hide();
            readNotifications("<?=url("Notification@read");?>");
        });
    </script>
</body>

</html>
