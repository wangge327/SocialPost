{{ view("frontend/head", $data); }}
<?php
$frontend_url = url("views/frontend");
?>

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
                    <a class="read_more" href="Javascript:void(0)">Read More</a>
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

<?php include "footer.php"; ?>
</body>

</html>