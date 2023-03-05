{{ view("includes/head", $data); }}
<body>
    <!-- header start -->
    {{ view("includes/header", $data); }}
    <!-- sidebar -->
    {{ view("includes/sidebar", $data); }}
    <div class="content">
        <div class="page-title">
            <h3>Publish Post</h3>
            <p>Please add post content to publish post</p>
        </div>
        <div class="row margin-0">
            <div class="col-md-12" style="padding:0">
                <div class="light-card table-responsive p-b-3em">
                <form class="simcy-form" action="<?= url("Posting@publishPost"); ?>" data-parsley-validate="" loader="true" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="user_id" value="{{ $user->id }}">
                    <input type="hidden" name="csrf-token" value="{{ csrf_token() }}" >

                    <p>Post Content</p>
                    <textarea style="width: 650px; height: 250px;" name="message"></textarea>

                    <br><br>

                    <div>
                        <p>Please select social network</p>
                        <div class="m-item m-b-l">
                            <input type="checkbox" name="social_type[]" value="Facebook">
                            <label>Facebook</label>
                        </div>
                        <div class="m-item m-b-l">
                            <input type="checkbox" name="social_type[]" value="Twitter">
                            <label>Twitter</label>
                        </div>
                    </div>

                    <br><br><br>

                    <button type="submit" class="btn btn-primary">Publish Post</button>
                </form>
                </div>
            </div>

        </div>
    </div>


    <!-- footer -->
    {{ view("includes/footer"); }}

    <?php   $url_para = explode("/", $_SERVER['REQUEST_URI']); ?>
    <script>
        var controller_name = "<?php echo $url_para[1] ?>";
        if(controller_name == "posting"){
            $(".fb-submenu").addClass("pushy-submenu-open");
        }
    </script>


</body>

</html>
