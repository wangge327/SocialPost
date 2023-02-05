{{ view("includes/head", $data); }}
<body>

{{ view("includes/header", $data); }}
{{ view("includes/sidebar", $data); }}

<div class="content">
    <?php include "profile_header.php" ?>
    <div class="row">
        <div class="light-checkin table-responsive p-b-3em">
            <div class="checkin-left">
                <form class="simcy-form page-actions lower" method="POST" loader="true"
                      action="<?= url("Customer@updateUploadLease"); ?>" enctype="multipart/form-data" style="margin:20px;">
                    <input type="hidden" name="csrf-token" value="{{ csrf_token() }}">
                    <input type="hidden" name="select_user" value="{{ $student->id }}">

                    <label>Upload Document for Lease on File</label>
                    <input type="file" name="file" id="file" >
                    <button type="submit" id="submit" name="action" value="import_unit"
                            class="btn btn-success" style="margin-top: 30px;"><i class="ion-email-unread"></i>Upload Lease
                    </button>
                </form>
            </div>
        </div>
    </div>

</div>
<!-- footer -->
{{ view("includes/footer"); }}

</body>
</html>