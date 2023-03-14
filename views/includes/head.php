<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Hiawatha Student Housing by Hiawatha inc.">
    <meta name="author" content="Bai Ming">
    <link rel="icon" type="image/png" sizes="16x16" href="<?= url("/"); ?>uploads/app/{{ env('APP_ICON'); }}">
    <title><?= env("APP_NAME") ?></title>

    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" />
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.4.2/css/buttons.dataTables.min.css" />

    <!-- Ion icons -->
    <link href="<?= url("/"); ?>assets/fonts/ionicons/css/ionicons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=B612+Mono:400,400i,700|Charm:400,700|EB+Garamond:400,400i,700|Noto+Sans+TC:400,700|Open+Sans:400,400i,700|Pacifico|Reem+Kufi|Scheherazade:400,700|Tajawal:400,700&amp;subset=arabic" crossorigin="anonymous" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Berkshire+Swash|Cookie|Courgette|Dr+Sugiyama|Grand+Hotel|Great+Vibes|League+Script|Meie+Script|Miss+Fajardose|Niconne|Pacifico|Petit+Formal+Script|Rochester|Sacramento|Tangerine" crossorigin="anonymous" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="<?= url("/"); ?>assets/libs/bootstrap/css/bootstrap.css" rel="stylesheet">
    <link href="<?= url("/"); ?>assets/libs/select2/css/select2.min.css" rel="stylesheet">
    <link href="<?= url("/"); ?>assets/libs/tagsinput/bootstrap-tagsinput.css" rel="stylesheet">
    <link href="<?= url("/"); ?>assets/css/simcify.min.css" rel="stylesheet">
    <link href="<?= url("/"); ?>assets/libs/jquery-ui/jquery-ui.min.css" rel="stylesheet">
    <!-- Signature pad css -->
    <link rel="stylesheet" href="<?= url("/"); ?>assets/css/signature-pad.css">
    <!-- Signer CSS -->
    <link href="<?= url("/"); ?>assets/css/style.css" rel="stylesheet">
    <script src="<?= url("/"); ?>assets/js/jscolor.js"></script>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" rel="stylesheet" type="text/css" />

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-JW8WGXR58B"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'G-JW8WGXR58B');
    </script>

</head>