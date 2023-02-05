{{ view("includes/head", $data); }}
<body>
{{ view("includes/header", $data); }}
{{ view("includes/sidebar", $data); }}

<div class="content">
    <?php include "profile_header.php" ?>
    <div class="row bg-white" style="padding: 20px;min-height: 550px;">
        <?php include "print/html_proof_address.php" ?>
    </div>

</div>
<!-- footer -->
{{ view("includes/footer"); }}

</body>
</html>