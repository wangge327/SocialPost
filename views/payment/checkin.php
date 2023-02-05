{{ view("includes/head", $data); }}

<body>
<style>
.content{
    width: 1000px;
    margin: 0 auto;
}
.pull-right{
    display: none !important;
}
.img-responsive, .thumbnail > img, .thumbnail a > img, .carousel-inner > .item > img, .carousel-inner > .item > a > img {
    display: block;
    max-width: 100%;
    height: auto;
}
.panel-heading{
	padding: 10px 15px;
    font-size: 16px;
}
</style>
{{ view("includes/header", $data); }}
<div class="content">
    <div class="page-title">
        <h3>Check in</h3>
    </div>
    <div class="row">
        <form method="post" action="<?= url("Payment@submitPaymentMode"); ?>">
            <div class="light-checkin table-responsive p-b-3em">
            <?php include "fee_check.php" ?>
            <div class="checkin-left">
                <?php include "web_cam.php" ?>
            </div>
            <div class="checkin-right">
                <h4 class="panel-heading">
                    Student Name: {{ $current_user->fname}} {{ $current_user->lname}}
                </h4></br>
                <div class="balance-info">
                    <span>Lease from {{ $current_user->lease_start }} to {{ $current_user->lease_end }} in {{ $current_user->unit }}</span>
                @if($owed_price>0)
                    <div class="balane-t">
                        <span style="width: 180px;">Your Owed Balance : </span>
                        <h1 style="margin-top: 10px;">$<label id="total_price"></label></h1>
                    </div>
                    <div class="clear"></div>
                @else
                    <span class="balane-n">You have no balance. You already Paid all</span>
                @endif
                </div>
                <div class="choose-payment">

                    <input type="hidden" name="csrf-token" value="{{ csrf_token() }}">
                    <input type="hidden" name="payment_user_id" value="{{ $current_user->id }}">
                    <input type="hidden" name="price_total" value="">
                    <input type="hidden" name="payment_option" value="checkin">

                    <p>Please choose payment method</p>
                    <button type="submit" name="payment_type" class="btn btn-primary"
                            value="Credit Card">
                        Credit Card
                    </button>
    <!--                <button type="submit" name="payment_type" class="btn btn-primary"-->
    <!--                        value="Paypal">-->
    <!--                    Paypal-->
    <!--                </button>-->
                    <button type="submit" name="payment_type" class="btn btn-primary"
                            value="Cash">
                        Pay at Window
                    </button>
                </div>
            </div>
            </div>
        </form>
    </div>
</div>
{{ view("includes/footer_camera"); }}
</body>

</html>