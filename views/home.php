{{ view("frontend/head", $data); }}
<?php
$frontend_url = url("views/frontend");
?>
<!-- top -->
<div class="full_bg" style="padding-top:0">
    <div class="slider_main">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <!-- carousel code -->
                    <div id="banner1" class="carousel slide">
                        <ol class="carousel-indicators">
                            <li data-target="#banner1" data-slide-to="0" class="active"></li>
                            <li data-target="#banner1" data-slide-to="1"></li>
                            <li data-target="#banner1" data-slide-to="2"></li>
                        </ol>
                        <div class="carousel-inner">
                            <!-- first slide -->
                            <div class="carousel-item active">
                                <div class="carousel-caption relative">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="dream">
                                                <h1>
                                                    Social <br>Posting <br>For <br>Your dream
                                                </h1>
                                                <a class="read_more" href="Javascript:void(0)">Get Stared</a>
                                                <a class="read_more" href="Javascript:void(0)">Contact Us</a>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="dream_img">
                                                <figure><img src="<?= $frontend_url; ?>images/dream_img.jpg" alt="#" /></figure>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <!-- controls -->
                        <a class="carousel-control-prev" href="#banner1" role="button" data-slide="prev">
                            <i class="fa fa-arrow-left" aria-hidden="true"></i>
                            <span class="sr-only">Previous</span>
                        </a>
                        <a class="carousel-control-next" href="#banner1" role="button" data-slide="next">
                            <i class="fa fa-arrow-right" aria-hidden="true"></i>
                            <span class="sr-only">Next</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end banner -->
<!-- domain -->
<div class="domain">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="titlepage text_align_center">
                    <h2>all posting count with <span class="blue_light">Social</span></h2>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-10 offset-md-1">
                <div class="domain_bg">
                    <div class="row">
                        <div class="col-sm-2">
                            <div class="domain-price">
                                <strong>Social Site <br>this month</strong>
                            </div>
                        </div>
                        <div class="col-sm-10">
                            <div class="domain-price_main ">
                                <div class="domain-price">
                                    <span>Facebook</span>
                                    <strong>129</strong>
                                </div>
                                <div class="domain-price">
                                    <span>Twitter</span>
                                    <strong>324</strong>
                                </div>
                                <div class="domain-price">
                                    <span>Youtube</span>
                                    <strong>29</strong>
                                </div>
                                <div class="domain-price">
                                    <span>Instagram</span>
                                    <strong>&nbsp;</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                    <a class="read_more" href="">See More</a>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end domain -->
<!-- guarantee -->
<div class="guarantee">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="titlepage text_align_center">
                    <h2>All list to <span class="blue_light">social network</span></h2>
                    <p>You can review all social site for publishing posts</p>
                </div>
            </div>
        </div>
        <div class="row" style="justify-content: center;">
            <div class="col-lg-3 col-md-6">
                <div id="ho_co" class="guarantee-box_main">
                    <div class="guarantee-box text_align_center">
                        <i>
                            <img width="100" src="<?= $frontend_url; ?>icon/icons8-facebook-480.png" class="user-avatar img-circle">
                        </i>
                        <h3>Facebook</h3>
                        <p>Please login at first with facebook account in dashboard and set pages of facebook, publish post what you want.</p>
                    </div>
                    <a class="read_more" href="">Read More</a>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div id="ho_co" class="guarantee-box_main">
                    <div class="guarantee-box text_align_center">
                        <i>
                            <img width="100" src="<?= $frontend_url; ?>icon/icons8-twitter-480.png" class="user-avatar img-circle">
                        </i>
                        <h3>Twitter</h3>
                        <p>Please login at first with twitter account in dashboard and publish post what you want.</p>
                    </div>
                    <a class="read_more" href="">Read More</a>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div id="ho_co" class="guarantee-box_main">
                    <div class="guarantee-box text_align_center">
                        <i>
                            <img width="100" src="<?= $frontend_url; ?>icon/icons8-youtube-240.png" class="user-avatar img-circle">
                        </i>
                        <h3>Youtube</h3>
                        <p>Please login at first with google account in dashboard and search youtube channel and video, Send post to channel.</p>
                    </div>
                    <a class="read_more" href="">Read More</a>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end guarantee -->

<!-- about -->
<div class="about">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="titlepage text_align_center">
                    <h2>About <span class="blue_light">Company</span></h2>
                </div>
            </div>
            <div class="col-md-10 offset-md-1">
                <div class="about_img text_align_center">
                    <p>
                        Our company supports making social advertising and blog posting easy.<br>
                        If anyone wants to do this, log in to the site.<br>
                        After that, you can do whatever you want by following the guided procedure.<br>
                        Thank you
                    </p>
                    <a class="read_more" href="<?= url("Frontend@about"); ?>">Read More</a>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end about -->
<!-- contact -->
<div class="contact">
    <div class="container">
        <div class="row ">
            <div class="col-md-12">
                <div class="titlepage text_align_center">
                    <h2>Contact <span class="blue_light">Us</span></h2>
                </div>
            </div>
            <div class="col-md-10 offset-md-1">
                <form id="request" class="main_form">
                    <div class="row">
                        <div class="col-md-12 ">
                            <input class="contactus" placeholder="Name" type="type" name=" Name">
                        </div>
                        <div class="col-md-12">
                            <input class="contactus" placeholder="Phone number" type="type" name="Phone Number">
                        </div>
                        <div class="col-md-12">
                            <input class="contactus" placeholder="Your Email" type="type" name="Email">
                        </div>
                        <div class="col-md-12">
                            <textarea class="textarea" placeholder="Message" type="type" Message="Name"></textarea>
                        </div>
                        <div class="col-md-12">
                            <button class="send_btn">Submit Now</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- contact -->

<?php include "frontend/footer.php"; ?>
</body>

</html>