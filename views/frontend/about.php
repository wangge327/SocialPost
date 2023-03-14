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

<?php include "footer.php"; ?>
</body>

</html>