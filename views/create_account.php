<?php include "includes/head.php" ?>
<body class="login">
    <div class="login-card">
        <div class="reset-password">
            <h4>Create your account.</h4>
            <form class="text-left simcy-form" action="<?=url("Auth@reset");?>" method="POST" data-parsley-validate="" loader="true" >
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-12">
                            <label>Username</label>
                            <input type="text" class="form-control" value="{{$email}}" disabled>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-12">
                            <label>PIN Code (Please save your Pin code)</label>
                            <input type="text" class="form-control" value="{{$pin}}" disabled>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-12">
                            <label>New Password</label> <label class="color-red">*</label>
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
                            <label>Confirm Password</label> <label class="color-red">*</label>
                            <input type="Password" class="form-control" data-parsley-required="true" data-parsley-equalto="#new-password" data-parsley-error-message="Passwords don't Match!" placeholder="Confirm Password">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-12">
                            <label>Anticipated Lease End Date</label>
                            <input type="text" class="form-control" name="lease_end" value="{{ $lease_end }}" placeholder="Lease End Date" autocomplete="off">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-12">
                            <label>Roommate (If you have roommates)</label>
                            <input type="text" class="form-control" name="roommate[]" placeholder="Your 1st roommate name">
                            <input type="text" class="form-control" name="roommate[]" placeholder="Your 2nd roommate name">
                            <input type="text" class="form-control" name="roommate[]" placeholder="Your 3rd roommate name">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-12">
                            <button class="btn btn-primary pull-right" type="submit" name="reset">
                                Create Account
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="copyright">
            <p class="text-center"><?=date("Y")?> &copy; <?=env("APP_NAME")?> | All Rights Reserved.</p>
        </div>
    </div>

    <style>
        input[name='roommate[]'] {
            margin-top:5px
        }
    </style>

    <!-- scripts -->
    <script src="<?=url("");?>assets/js/jquery-3.2.1.min.js"></script>
    <script src="<?=url("");?>assets/libs/bootstrap/js/bootstrap.min.js"></script>
    <script src="<?=url("");?>assets/js//jquery.slimscroll.min.js"></script>
    <script src="<?=url("");?>assets/js/simcify.min.js"></script>

    <!-- custom scripts -->
    <script src="<?=url("");?>assets/js/app.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>

    <script>
        $(document).ready(function() {
            $("[name='lease_end']").datepicker({
                todayHighlight: true,
                format: 'yyyy-mm-dd'
            });
        });

    </script>
</body>

</html>
