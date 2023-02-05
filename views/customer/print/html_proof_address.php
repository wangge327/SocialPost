<?php
session_start();
include 'WebClientPrint.php';
use Neodynamic\SDK\Web\WebClientPrint;

$title_font_size = "120px";
$bottom_font_size = "50px";
$font_size = "60px";
?>
<input type="hidden" id="sid" name="sid" value="<?php echo session_id(); ?>" />

<div id="card-top" style=" left: -3000px; position: absolute;">
<div id="card" style="width: 2480px; height:3508px; position: relative; padding: 5% 4%; border: 1px solid;">
    <div style="width: 100%; height: 10%; ">
        &nbsp;<img src="/uploads/app/logo.png" style="width: 20%;margin: 1%;">
    </div>
    <div style="width: 100%;text-align: center;font-size: {{$title_font_size}};font-weight: bold;">Proof of Address</div>
    <div style="padding: 0 15px; margin-top: 20%;">
        <div style="width:30%; float: right;">
            @if( empty($student->avatar) )
            <img src="/webcam/assets/img/avatar.jpg" style="width: 90%;border: 2px solid #7AC143;" />
            @else
            @if(file_exists($_SERVER['DOCUMENT_ROOT']."/uploads/avatar/".$student->avatar))
            <img src="/uploads/avatar/{{ $student->avatar }}" style="width: 90%;border: 2px solid #7AC143;" />
            @else
            <img src="/webcam/assets/img/avatar.jpg" style="width: 90%; border: 2px solid #7AC143;" />
            @endif
            @endif
        </div>
        <div style="width:70%;padding: 5px; float: right;">
            <div style="font: {{$font_size}} Arial;">
                Name :<span style="text-decoration: underline; margin-left: 10px;">{{$student->fname . " " . $student->lname}}</span></div>

            <div style="font: {{$font_size}} Arial;margin-top: 4%;">
                Address :<span style="text-decoration: underline; margin-left: 10px;">{{ $student->address }} {{ $student->city }} {{ $student->state }}, {{ $student->country }}</span></div>

            <div style="font: {{$font_size}} Arial;margin-top: 4%;">
                Email :<span style="text-decoration: underline; margin-left: 10px;">{{ $student->email }} </span></div>

            <div style="font: {{$font_size}} Arial;margin-top: 4%;">
                Phone :<span style="text-decoration: underline; margin-left: 10px;">{{ $student->phone_number }} </span></div>

            <div style="font: {{$font_size}} Arial;margin-top: 4%;">
                Lease Start date :<span style="text-decoration: underline; margin-left: 10px;">{{ $student->lease_start }} </span></div>

            <div style="font: {{$font_size}} Arial;margin-top: 4%;">
                Lease End date :<span style="text-decoration: underline; margin-left: 10px;">{{ $student->lease_end }} </span></div>

            <div style="font: {{$font_size}} Arial;margin-top: 4%;">
                Room Number :
                <span style="text-decoration: underline; margin-left: 10px;">
                @if($student->unit == "")
                    Not Assigned Room
                @else
                    {{$student->unit}}
                @endif
            </span>
            </div>



        </div>
    </div>
    <div style="position: absolute; bottom: 6%; right: 3%">
        <div style="font: {{$bottom_font_size}} Arial;color: #6A6C70;margin-top: 7px;">
            200 W. Hiawatha Dr, Wisconsin Dells, WI
        </div>
        <div style="font: {{$bottom_font_size}} Arial;color: #6A6C70;margin-top: 7px;">
            608.253.0200 | https://www.irhliving.com
        </div>
    </div>
    <div style="width: 98%; margin: 0 auto; height: 0.3%; background-color: #7AC143; border-radius: 0 0 10px 10px;position: absolute; bottom: 5%; left: 2%;"></div>
    <div style="font: {{$bottom_font_size}} Arial; position: absolute; bottom: 1%; right: 2%;">
        <?= env("APP_NAME") ?>
    </div>
</div>
</div>

<div id="card-area" style="width: 2480px; height: 3508px; margin-top: -1390px; float: left; margin-left: -950px; transform: scale(0.2);">
</div>
<div class="row" style="padding-left: 20px; top: 400px; float: left; right:12%; width: 285px; position: absolute;">
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
            html2canvas(document.getElementById('card'),{scale:1,x:0, y:0, width:2480,height:3508}).then(function (canvas) {

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