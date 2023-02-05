<?php
session_start();
include 'WebClientPrint.php';
use Neodynamic\SDK\Web\WebClientPrint;
?>
<input type="hidden" id="sid" name="sid" value="<?php echo session_id(); ?>" />

<div id="card-top" style="position: absolute; left: -500px;">
<div id="card" style="width: 641px; height:1012px; border-radius: 10px; position: relative; ">
    <div style="width: 100%; height: 5%; border-radius: 10px 10px 0 0;">
    </div>
    <div style="padding: 0 15px;">
        <div style="width:100%;">
            <div style="padding: 8%;">
                @if( empty($student->avatar) )
                <img src="/webcam/assets/img/avatar.jpg" style="width: 100%;border: 2px solid #7AC143;" />
                @else
                    @if(file_exists($_SERVER['DOCUMENT_ROOT']."/uploads/avatar/".$student->avatar))
                    <img src="/uploads/avatar/{{ $student->avatar }}" style="width: 100%;height: 500px;border: 2px solid #7AC143;" />
                    @else
                    <img src="/webcam/assets/img/avatar.jpg" style="width: 100%;border: 2px solid #7AC143;" />
                    @endif
                @endif
            </div>
        </div>
        <div style="width:100%;padding: 5px;margin-top: 6%;text-align: center;transform: scale(1.5)">
            <div style="font: 25px Arial;">
                {{$student->fname . " " . $student->lname}}</div>
            <div style="font: 30px Arial;">
                {{$student->season}}</div>
            <div style="font: 24px Arial; margin-top: 10px;">
                {{$student->sponsor}}
            </div>
            <div style="margin-top: 7px;">
                <img src="/uploads/app/logo.png" style="width: 30%;">
            </div>
            <div style="font: 18px Arial;color: #6A6C70;margin-top: 7px;">
                200 W. Hiawatha Dr, Wisconsin Dells, WI
            </div>
            <div style="font: 18px Arial;color: #6A6C70;margin-top: 7px;">
                608.253.0200 | https://www.irhliving.com/
            </div>
        </div>
    </div>
    <div style="width: 98%; margin: 0 auto; height: 3px; background-color: #7AC143; border-radius: 0 0 10px 10px;position: absolute; bottom: 4px; left: 6px;"></div>
</div>
</div>

<div id="card-area" style="height: 540px; width: 400px; margin-top: -140px; float: left;margin-left: -100px; transform: scale(0.5)">
</div>
<div class="row" style="padding-left: 20px; margin-top: 40px; float: left; margin-left: 100px; width: 285px;">
    <div style="margin-left: 20px; display: none">
        <label class="checkbox">
            <input type="checkbox" id="useDefaultPrinter" checked/> <strong>Print to Default printer</strong> or...
        </label>
    </div>

    <div id="loadPrinters">
        Click to load and select one of the installed printers!
        <br /><br />
        <input type="button" onclick="javascript:jsWebClientPrint.getPrinters();" value="Load installed Printers" class="btn btn-primary"/>

    </div>

    <div id="installedPrinters" style="visibility:hidden">
        <label for="installedPrinterName">Select an installed Printer:</label>
        <select name="installedPrinterName" id="installedPrinterName"></select>
    </div>

    <input id="printBtn" type="button" class="btn btn-success" value="Print" />
</div>

<script type="text/javascript">
    var wcppGetPrintersTimeout_ms = 10000; //10 sec
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

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js" type="text/javascript"></script>

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
    $webClientPrintControllerAbsoluteURL = $currentAbsoluteURL .'/WebClientPrintController.php';

    //PrintHtmlCardController.php is at the same page level as WebClientPrint.php
    $printHtmlCardControllerAbsoluteURL = $currentAbsoluteURL .'/PrintHtmlCardController.php';

    //Specify the ABSOLUTE URL to the WebClientPrintController.php and to the file that will create the ClientPrintJob object
    echo WebClientPrint::createScript($webClientPrintControllerAbsoluteURL, $printHtmlCardControllerAbsoluteURL, session_id());
?>

<script src="<?=url("");?>assets/js/html2canvas.js"></script>

<script>
    var card_str = $( "#card-top" ).html();
    let file_name="{{ $file_name }}";
    $( "#card-area" ).html( card_str );
    $(document).ready(function(){
        $("#printBtn").click(function () {
            //1. generate an image of HTML content through html2canvas utility
            html2canvas(document.getElementById('card'),{scale:1,x:0, y:0, width:641,height:1012}).then(function (canvas) {

                var b64Prefix = "data:image/png;base64,";
                var imgBase64DataUri = canvas.toDataURL("image/png");

                //$("#test").attr("src", imgBase64DataUri);

                var imgBase64Content = imgBase64DataUri.substring(b64Prefix.length, imgBase64DataUri.length);
                //2. save image base64 content to server-side Application Cache
                $.ajax({
                    type: "POST",
                    url: '/views/customer/print/StoreImageFileController.php',
                    data: {
                        file_name:file_name,
                        base64ImageContent : imgBase64Content,
                        'csrf-token' : '{{csrf_token()}}'
                    },
                    success: function (imgFileName) {
                        jsWebClientPrint.print('useDefaultPrinter=' + $('#useDefaultPrinter').attr('checked') + '&printerName=' + $('#installedPrinterName').val() + '&imageFileName=' + imgFileName);
                        location.reload();
                    }
                });
            });

        });
    });
</script>