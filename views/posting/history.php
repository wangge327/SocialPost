{{ view("includes/head", $data); }}
<?php
include("config.php"); // All settings in the $config-Object
?>

<body>
    <!-- header start -->
    {{ view("includes/header", $data); }}
    <!-- sidebar -->
    {{ view("includes/sidebar", $data); }}
    <div class="content">
        <div class="page-title">
            <h3>Post History</h3>
            <p>Please look at posting history to social networks.</p>
            <!--<p>请选择您注册的 Facebook 帐户的多个页面之一来因为发送帖子。</p>-->
        </div>
        <div class="row margin-0">
            <div class="col-md-11 bg-white" style="padding:20px 10px">
                <div class="light-card table-responsive p-b-3em">
                    <table class="table display page-list" id="data-table">
                        <thead>
                            <tr>
                                <th style="width:30px">No</th>
                                <th class="text-align-center" style="width:130px">Social Network</th>
                                <th class="text-align-center">Message</th>
                                <th class="text-align-center" style="width:160px">Date</th>
                            </tr>
                        </thead>
                        <tbody>

                            @if ( count($posting_history) > 0 )
                            @foreach ( $posting_history as $index => $each_history )
                            <tr>
                                <td>{{$index + 1 }}</td>
                                <td>{{$each_history->social_type}}</td>
                                <td><strong>{{$each_history->message}}</strong></td>
                                <td>
                                    {{$each_history->created_at}}
                                </td>
                            </tr>
                            @endforeach

                            @else
                            <tr>
                                <td colspan="9" class="text-center">It's empty here</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>


    <!-- footer -->
    {{ view("includes/footer"); }}

    <?php $url_para = explode("/", $_SERVER['REQUEST_URI']); ?>
    <script>
        var controller_name = "<?php echo $url_para[1] ?>";
        if (controller_name == "posting") {
            $(".posting-submenu").addClass("pushy-submenu-open");
        }

        $(document).ready(function() {
            $('#data-table').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    'copyHtml5',
                    'excelHtml5',
                    'csvHtml5'
                ]
            });
        });
    </script>


</body>

</html>