{{ view("includes/head", $data); }}
<body>
{{ view("includes/header", $data); }}
{{ view("includes/sidebar", $data); }}

<div class="content">
    <?php include "profile_header.php" ?>
    <div class="row">
        <div class="table-responsive p-b-3em" style="min-height: 500px">
            <div class="checkin-left">
                {{ view("web_cam", $data); }}
            </div>
        </div>
    </div>

</div>
<!-- footer -->
{{ view("includes/footer_camera"); }}

</body>
</html>