<footer>
    <p class="text-right">&copy; <?= date("Y") ?> <?= env("APP_NAME") ?> </p>
</footer>

<script type="text/javascript">
    var countNotificationsUrl = '<?= url("Notification@count"); ?>';
</script>

<script src="<?= url(""); ?>assets/js/jquery-3.2.1.min.js"></script>
<script src="<?= url(""); ?>assets/libs/bootstrap/js/bootstrap.min.js"></script>
<script src="<?= url(""); ?>assets/js//jquery.slimscroll.min.js"></script>

<script src="<?= url(""); ?>assets/libs/jquery-ui/jquery-ui.min.js"></script>
<script src="<?= url(""); ?>assets/libs/jcanvas/jcanvas.min.js"></script>
<script src="<?= url(""); ?>assets/js/dom-to-image.min.js"></script>
<script src="<?= url(""); ?>assets/libs/jcanvas/signature.min.js"></script>
<script src="<?= url(""); ?>assets/js/simcify.min.js"></script>
<script src="<?= url(""); ?>assets/js/jquery-validate.min.js"></script>
<script src="<?= url(""); ?>assets/js/jquery-additional-methods.min.js"></script>

<!-- custom scripts -->
<script src="<?= url(""); ?>assets/js/app.js"></script>
<script src="<?= url(""); ?>assets/js/custom.js"></script>
<script src="<?= url(""); ?>assets/js/signature-pad/signature_pad.umd.js"></script>
<script src="<?= url(""); ?>assets/js/signature-pad/app.js"></script>
<script src="<?= url(""); ?>assets/js/pushy.js"></script>

</body>

</html>