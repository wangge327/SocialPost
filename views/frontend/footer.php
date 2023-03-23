<footer>
    <div class="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="infoma text_align_left">
                        <h3>Choose.</h3>
                        <ul class="commodo">
                            <li>Social Login</li>
                            <li>Get Account infors</li>
                            <li>Publish Post</li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-6">
                    <div class="infoma">
                        <h3>Get Support.</h3>
                        <ul class="conta">
                            <li><i class="fa fa-map-marker" aria-hidden="true"></i>Address : Yanji Jilin, China</li>
                            <li><i class="fa fa-phone" aria-hidden="true"></i>Call : +86 139 0443 3961</li>
                            <li> <i class="fa fa-envelope" aria-hidden="true"></i><a href="Javascript:void(0)"> Email : yan@yituierp.co</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="infoma">
                        <h3>Company.</h3>
                        <ul class="menu_footer">
                            <li><a href="<?= url("Frontend@home"); ?>">Home</a></li>
                            <li><a href="<?= url("Frontend@about"); ?>">About </a></li>
                            <li><a href="<?= url("Frontend@contact"); ?>">Contact Us</a></li>
                            <li><a href="<?= url("Frontend@policy"); ?>">Privacy Policy</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6 col-sm-6">
                    <div class="infoma text_align_left">
                        <h3>Services.</h3>
                        <ul class="commodo">
                            <li>Facebook</li>
                            <li>Twitter</li>
                            <li>Youtube</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="copyright">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <p>© <?= date("Y") ?> All Rights Reserved. <a href="<?= env("APP_URL") ?>"> 延边鑫海源经贸有限公司</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
<!-- end footer -->


<script type="text/javascript">
    var countNotificationsUrl = '<?= url("Notification@count"); ?>';
</script>
<script src="<?= url("/"); ?>assets/js/jquery-3.2.1.min.js"></script>

<script src="//code.jquery.com/jquery-1.12.4.js"></script>

<!-- Javascript files-->
<script src="<?= $frontend_url; ?>js/jquery.min.js"></script>
<script src="<?= $frontend_url; ?>js/bootstrap.bundle.min.js"></script>
<!-- sidebar -->
<script src="<?= $frontend_url; ?>js/custom.js"></script>

<script>
    var content_height = $(window).height() - 97;
    $(".content").css("min-height", content_height);

    <?php $url_para = explode("/", $_SERVER['REQUEST_URI']); ?>

    var controller_name = "<?php echo $url_para[1] ?>";
    $(".nav-id-" + controller_name).addClass("active");
</script>