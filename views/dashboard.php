<?php include "includes/head.php" ?>

<body>
    {{ view("includes/header", $data); }}
    {{ view("includes/sidebar", $data); }}

    <div class="content">
        <div class="row">
            @if ( $user->role == "user" )
            <div style="padding-left: 18px;">
                <?php include "customer/dashboard_student.php" ?>
            </div>
            @endif
        </div>
    </div>

    <!-- footer -->
    {{ view("includes/footer"); }}

</body>

</html>