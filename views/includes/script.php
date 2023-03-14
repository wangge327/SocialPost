<?php

/**
 * Created by PhpStorm.
 * User: amgPC
 * Date: 2020.12.23
 * Time: 3:29 下午
 */
?>

<!-- scripts -->
<script src="<?= url("/"); ?>assets/js/jquery-3.2.1.min.js"></script>
<script src="<?= url("/"); ?>assets/libs/dropify/js/dropify.min.js"></script>
<script src="<?= url("/"); ?>assets/libs/bootstrap/js/bootstrap.min.js"></script>
<script src="<?= url("/"); ?>assets/js/simcify.min.js"></script>
<script src="<?= url("/"); ?>assets/libs/html2canvas/html2canvas.js"></script>
<script src="<?= url("/"); ?>assets/libs/clipboard/clipboard.min.js"></script>
<script src="<?= url("/"); ?>assets/libs/jquery-ui/jquery-ui.min.js"></script>
<script src="<?= url("/"); ?>assets/libs/select2/js/select2.min.js"></script>
<script src="<?= url("/"); ?>assets/libs/tagsinput/bootstrap-tagsinput.js"></script>
<script src="<?= url("/"); ?>assets/js//jquery.slimscroll.min.js"></script>
<script src="<?= url("/"); ?>assets/libs/jcanvas/jcanvas.min.js"></script>
<script src="<?= url("/"); ?>assets/js/dom-to-image.min.js"></script>
<script src="<?= url("/"); ?>assets/libs/jcanvas/signature.min.js"></script>
<script src="<?= url("/"); ?>assets/libs/jcanvas/editor.min.js"></script>
<script src="<?= url("/"); ?>assets/js/touch-punch.min.js"></script>
<script src="<?= url("/"); ?>assets/js/pdf.js"></script>
<script type="text/javascript">
    var url = '<?= url("/"); ?>uploads/files/{{ $document->filename }}',
        isTemplate = '{{ $document->is_template }}',
        signature_name = '{{ $request->user_name }}',
        signDocumentUrl = '<?= url("Guest@sign"); ?>',
        auth = false;
    createTemplateUrl = sendRequestUrl = postChatUrl = settingsPage = saveFieldsUrl = getChatUrl = deleteFieldsUrl = countNotificationsUrl = '',
        baseUrl = '<?= url("/"); ?>',
        loginPage = '<?= url("Auth@get"); ?>';
    document_key = '{{ $document->document_key }}';
    PDFJS.workerSrc = '<?= url("/"); ?>assets/js/pdf.worker.min.js';

    @if(is_object($request) && $request - > status == "Pending")
    var signingKey = '{{ $request->signing_key }}';
    var requestPositions = {
        {
            $requestPositions
        }
    };
    var requestWidth = {
        {
            $requestWidth
        }
    };
    @else
    var signingKey = '';
    @endif
</script>
<!-- custom scripts -->
<script src="<?= url("/"); ?>assets/js/app.js"></script>
<script src="<?= url("/"); ?>assets/js/signer.js"></script>
<script src="<?= url("/"); ?>assets/js/render.js"></script>
<script src="<?= url("/"); ?>assets/js/signature-pad/signature_pad.umd.js"></script>
<script src="<?= url("/"); ?>assets/js/signature-pad/app.js"></script>