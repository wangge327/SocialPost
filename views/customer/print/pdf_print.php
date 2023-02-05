<?php

include 'WebClientPrint.php';
use Neodynamic\SDK\Web\WebClientPrint;
use Neodynamic\SDK\Web\Utils;

?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
    <div class="panel panel-default">
        <div id="collapseTwo"  role="tabpanel" aria-labelledby="headingTwo">
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-4 col-md-offset-2">
                        <hr />
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" id="useDefaultPrinter" />
                                <strong>Print to Default printer</strong> or...
                            </label>
                        </div>
                        <div id="loadPrinters">
                            Click to load and select one of the installed printers!
                            <br />
                            <a onclick="javascript:jsWebClientPrint.getPrinters();" class="btn btn-success">Load installed printers...</a>
                            <br />
                            <br />
                        </div>
                        <div id="installedPrinters" style="visibility: hidden">
                            <label for="installedPrinterName">Select an installed Printer:</label>
                            <select name="installedPrinterName" id="installedPrinterName" class="form-control"></select>
                        </div>
                        <script type="text/javascript">
                            //var wcppGetPrintersDelay_ms = 0;
                            var wcppGetPrintersTimeout_ms = 60000; //60 sec
                            var wcppGetPrintersTimeoutStep_ms = 500; //0.5 sec
                            function wcpGetPrintersOnSuccess() {
                                // Display client installed printers
                                if (arguments[0].length > 0) {
                                    var p = arguments[0].split("|");
                                    var options = '';
                                    for (var i = 0; i < p.length; i++) {
                                        options += '<option>' + p[i] + '</option>';
                                    }
                                    $('#installedPrinters').css('visibility', 'visible');
                                    $('#installedPrinterName').html(options);
                                    $('#installedPrinterName').focus();
                                    $('#loadPrinters').hide();
                                } else {
                                    alert("No printers are installed in your system.");
                                }
                            }
                            function wcpGetPrintersOnFailure() {
                                // Do something if printers cannot be got from the client
                                alert("No printers are installed in your system.");
                            }
                        </script>
                    </div>
                    <div class="col-md-4">
                        <hr />
                        <div id="fileToPrint">
                            <label for="ddlFileType">Select a sample File to print:</label>
                            <select id="ddlFileType" class="form-control">
                                <option>PDF</option>
                                <option>TXT</option>
                                <option>DOC</option>
                                <option>XLS</option>
                                <option>JPG</option>
                                <option>PNG</option>
                                <option>TIF</option>
                            </select>
                            <br />
                            <a class="btn btn-success btn-lg" onclick="javascript:jsWebClientPrint.print('useDefaultPrinter=' + $('#useDefaultPrinter').attr('checked') + '&printerName=' + encodeURIComponent($('#installedPrinterName').val()) + '&filetype=' + $('#ddlFileType').val());">Print File...</a>
                        </div>
                    </div>
                </div>
                <h5>File Preview</h5>
                <iframe id="ifPreview" style="width: 100%; height: 500px;" frameborder="0"></iframe>

            </div>
        </div>
    </div>

</div>


<script src="https://ajax.aspnetcdn.com/ajax/jquery/jquery-2.2.0.min.js"></script>
<script src="https://ajax.aspnetcdn.com/ajax/bootstrap/3.3.6/bootstrap.min.js"></script>

<?php

//Get Absolute URL of this page
$currentAbsoluteURL = (@$_SERVER["HTTPS"] == "on") ? "https://" : "http://";
$currentAbsoluteURL .= $_SERVER["SERVER_NAME"];
if($_SERVER["SERVER_PORT"] != "80" && $_SERVER["SERVER_PORT"] != "443")
{
    $currentAbsoluteURL .= ":".$_SERVER["SERVER_PORT"];
}
$currentAbsoluteURL .= "/views/customer/print";

//WebClientPrinController.php is at the same page level as WebClientPrint.php
$webClientPrintControllerAbsoluteURL = $currentAbsoluteURL.'/WebClientPrintController.php';
//DemoPrintFileController.php is at the same page level as WebClientPrint.php
$demoPrintFileControllerAbsoluteURL = $currentAbsoluteURL.'/DemoPrintFileController.php';

//Specify the ABSOLUTE URL to the WebClientPrintController.php and to the file that will create the ClientPrintJob object
echo WebClientPrint::createScript($webClientPrintControllerAbsoluteURL, $demoPrintFileControllerAbsoluteURL, session_id());
?>


<script type="text/javascript">
    $("#ddlFileType").change(function () {
        var s = $("#ddlFileType option:selected").text();
        if (s == 'PDF') $("#ifPreview").attr("src", "//docs.google.com/gview?url=http://webclientprintphp.azurewebsites.net/files/LoremIpsum.pdf&embedded=true");
        if (s == 'TXT') $("#ifPreview").attr("src", "//docs.google.com/gview?url=http://webclientprintphp.azurewebsites.net/files/LoremIpsum.txt&embedded=true");
        if (s == 'TIF') $("#ifPreview").attr("src", "//docs.google.com/gview?url=http://webclientprintphp.azurewebsites.net/files/patent2pages.tif&embedded=true");
        if (s == 'XLS') $("#ifPreview").attr("src", "//docs.google.com/gview?url=http://webclientprintphp.azurewebsites.net/files/SampleSheet.xls&embedded=true");
        if (s == 'DOC') $("#ifPreview").attr("src", "//docs.google.com/gview?url=http://webclientprintphp.azurewebsites.net/files/LoremIpsum.doc&embedded=true");
        if (s == 'JPG') $("#ifPreview").attr("src", "//webclientprintphp.azurewebsites.net/files/penguins300dpi.jpg");
        if (s == 'PNG') $("#ifPreview").attr("src", "//webclientprintphp.azurewebsites.net/files/SamplePngImage.png");
    }).change();
</script>



