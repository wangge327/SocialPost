<?php include "includes/head.php" ?>

<body class="login">
    <div class="login-card">
        <img src="<?= url("/"); ?>uploads/app/{{ env('APP_ICON'); }}" class="img-responsive">
        <div class="reset-password">
            <h4 class="mb-30">Enter your new password.</h4>
            <form class="text-left simcy-form" action="<?= url("Auth@reset"); ?>" method="POST" data-parsley-validate="" loader="true">
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-12">
                            <label>New Password</label>
                            <input type="Password" class="form-control" name="password" data-parsley-required="true" data-parsley-minlength="6" data-parsley-error-message="Password is too short!" id="new-password" placeholder="New Password">
                            <input type="hidden" name="csrf-token" value="{{ csrf_token() }}" />
                            <input type="hidden" name="token" value="{{ $token }}">
                            <input type="hidden" name="user_id" value="{{ $user_id }}">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-12">
                            <label>Confirm Password</label>
                            <input type="Password" class="form-control" data-parsley-required="true" data-parsley-equalto="#new-password" data-parsley-error-message="Passwords don't Match!" placeholder="Confirm Password">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-12">
                            <button class="btn btn-primary pull-right" type="submit" name="reset">
                                Reset password
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="copyright">
            <p class="text-center"><?= date("Y") ?> &copy; <?= env("APP_NAME") ?> | All Rights Reserved.</p>
        </div>
    </div>

    <!-- scripts -->
    <script src="<?= url("/"); ?>assets/js/jquery-3.2.1.min.js"></script>
    <script src="<?= url("/"); ?>assets/libs/bootstrap/js/bootstrap.min.js"></script>
    <script src="<?= url("/"); ?>assets/js//jquery.slimscroll.min.js"></script>
    <script src="<?= url("/"); ?>assets/js/simcify.min.js"></script>

    <!-- custom scripts -->
    <script src="<?= url("/"); ?>assets/js/app.js"></script>
</body>

</html>