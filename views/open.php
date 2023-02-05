<?php include "includes/head.php" ?>

<body class="open_template">
    <!-- header start -->
    <header>
        <!-- Hambager -->
        <div class="humbager">
            <i class="ion-navicon-round"></i>
        </div>
        <!-- logo -->
        <div class="logo">
            <a href="{{ url('') }}">
                <img src="{{ url('') }}uploads/app/{{ env('APP_LOGO'); }}" class="img-responsive">
            </a>
        </div>

    </header>


    <div class="content mb-30" style="margin: 0 0 0 0">
        <div class="row">
            <div class="col-md-8 put-center">
                <div class="page-title">
                    <h3>设置密码</h3>
                </div>
                <div class="light-card document">
                    <div class="signer-document">
                        <form class="simcy-form" id="create-password-form" action="<?= url("Customer@createPassword"); ?>" data-parsley-validate="" loader="true" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="csrf-token" value="{{ csrf_token() }}">
                            <input type="hidden" name="user_id" value="{{ $request->user_id }}">
                            <div class="row">
                                <div class="col-md-12">
                                    <label>亲爱的 {{ $request->user_name }}</label><br>
                                    <label>感谢您接受请求</label><br>
                                    <label>您可以设置密码来激活您的帐户。</label><br><br><br>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label>密码</label>
                                    <input type="password" class="form-control" name="password">
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-6">
                                    <label>确认密码</label>
                                    <input type="password" class="form-control" name="confirm_password">
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-6">
                                    <button class="btn btn-success" type="submit">
                                        <i class="ion-code-download"></i>
                                        提交
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>


        </div>
    </div>

    <!-- footer -->
    <?php include "includes/guest_footer.php"; ?>