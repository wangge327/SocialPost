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
            <h3>Facebook账号</h3>
            <p>Please select one of the many pages of your registered facebook account to send posts.</p>
        </div>
        <div class="row margin-0">
            <div class="col-md-11 bg-white" style="padding:20px 10px">
                <form class="simcy-form" action="<?= url("Facebook@addFbAccount"); ?>" data-parsley-validate="" loader="true" method="POST" enctype="multipart/form-data">


                    <div class="form-group">
                        <div class="row">
                            @if(empty($fb_user))
                            <div class="col-md-12">
                                <label style="color:red">You didn't register Facebook account</label>
                            </div>
                            @else
                            <div class="col-md-12">
                                <label>Facebook ID : </label>
                                <span class="color-red">{{$fb_user->fb_id}}</span>
                            </div>
                            @endif
                        </div>
                        <div class="row">
                            @if(empty($fb_user))
                            <div class="col-md-12">
                                <a href="<?= url("Facebook@get"); ?>">Please redirect to login with Facebook account. </a>
                            </div>
                            @else
                            <div class="col-md-12">
                                <label>Facebook Name : </label>
                                <span class="color-red">{{$fb_user->fb_name}}</span>
                            </div>
                            @endif
                        </div>
                    </div>

                    <div class="light-card table-responsive p-b-3em">
                        <table class="table display page-list" id="data-table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Page ID</th>
                                    <th>Page Name</th>
                                    <th>Status</th>
                                    <th class="text-center w-70">Action</th>
                                </tr>
                            </thead>
                            <tbody>

                                @if ( count($fb_pages_api) > 0 )
                                @foreach ( $fb_pages_api as $index => $each_fb_page )
                                <tr>
                                    <td>{{$index + 1 }}</td>
                                    <td>{{$each_fb_page->id}}</td>
                                    <td><strong>{{$each_fb_page->name}}</strong></td>
                                    <td>
                                        @if($each_fb_page->id == $fb_pages->page_id)
                                        <span class="label label-success">Set</span>
                                        @else
                                        <span class="label label-warning">Unset</span>
                                        @endif
                                    </td>

                                    <td class="text-center">
                                        @if($each_fb_page->id == $fb_pages->page_id)
                                        <a class="btn btn-success send-to-server-click" data="status:Unset|user_id:{{$user->id}}|fb_id:{{$fb_user->fb_id}}|page_id:{{$each_fb_page->id}}|page_name:{{$each_fb_page->name}}|page_access_token:{{$each_fb_page->page_token}}|csrf-token:{{ csrf_token() }}" url="<?= url("Facebook@AddPageDB"); ?>" warning-title="Are you sure?" warning-message="This Page will be Unset." warning-button="Continue" loader="true">Unset</a>
                                        @else
                                        <a class="btn btn-primary send-to-server-click" data="status:Set|user_id:{{$user->id}}|fb_id:{{$fb_user->fb_id}}|page_id:{{$each_fb_page->id}}|page_name:{{$each_fb_page->name}}|page_access_token:{{$each_fb_page->page_token}}|csrf-token:{{ csrf_token() }}" url="<?= url("Facebook@AddPageDB"); ?>" warning-title="Are you sure?" warning-message="This Page will be Set." warning-button="Continue" loader="true">Set</a>
                                        @endif

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
                </form>
            </div>

        </div>
    </div>


    <!-- footer -->
    {{ view("includes/footer"); }}

    <?php $url_para = explode("/", $_SERVER['REQUEST_URI']); ?>
    <script>
        var controller_name = "<?php echo $url_para[1] ?>";
        if (controller_name == "facebook") {
            $(".fb-submenu").addClass("pushy-submenu-open");
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