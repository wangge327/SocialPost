<?php include "includes/head.php" ?>

<body>
<!-- header start -->
{{ view("includes/header", $data); }}

<!-- sidebar -->
{{ view("includes/sidebar", $data); }}

<div class="content">
    @if ( $user->role != "user" )
    <?php include "customer/profile_header.php" ?>
    @else
    <div class="page-title">
        <h3>Payment</h3>
    </div>
    @endif
    <div class="row">
        @if ( $page == "select" )
        <?php include "payment/take_payment_by_admin.php" ?>
        @elseif( $page == "confirm.php")
        <?php include "payment/confirm.php" ?>
        @elseif( $page == "Paypal")
        <?php include "payment/paypal_form.php" ?>
        @else
        <?php include "payment/credit_card_form.php" ?>
        @endif
    </div>
</div>

<?php include "includes/footer_new.php" ?>
        
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>