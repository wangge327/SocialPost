<footer>
    <p class="text-right">© <?= date("Y") ?> <?= env("APP_NAME") ?> </p>
</footer>

<script type="text/javascript">
    var countNotificationsUrl = '<?= url("Notification@count"); ?>';
</script>
<script src="<?= url("/"); ?>assets/js/jquery-3.2.1.min.js"></script>
<script src="<?= url("/"); ?>assets/js/pushy.js"></script>

<script src="//code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.4.2/js/dataTables.buttons.min.js"></script>
<script src="//cdn.datatables.net/buttons/1.4.2/js/buttons.flash.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/pdfmake.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js"></script>
<script src="//cdn.datatables.net/buttons/1.4.2/js/buttons.html5.min.js"></script>
<script src="//cdn.datatables.net/buttons/1.4.2/js/buttons.print.min.js"></script>
<script src="<?= url("/"); ?>assets/js/jquery-validate.min.js"></script>
<script src="<?= url("/"); ?>assets/js/jquery-additional-methods.min.js"></script>
<!-- scripts -->
<script src="<?= url("/"); ?>assets/libs/bootstrap/js/bootstrap.min.js"></script>
<script src="<?= url("/"); ?>assets/js//jquery.slimscroll.min.js"></script>
<script src="<?= url("/"); ?>assets/js/simcify.js"></script>
<!-- custom scripts -->
<script src="<?= url("/"); ?>assets/js/app.js"></script>
<script src="<?= url("/"); ?>assets/js/custom.js"></script>

<script>
    var content_height = $(window).height() - 97;
    $(".content").css("min-height", content_height);
</script>