{{ view("includes/head", $data); }}

<body>
    <!-- header start -->
    {{ view("includes/header", $data); }}
    <!-- sidebar -->
    {{ view("includes/sidebar", $data); }}
    <div class="content">
        <div class="page-title">
            <h3>Facebook Pages</h3>
            <p>您可以查看在 Facebook 上创建的所有页面。</p>
            <p>如果您想添加更多Page，请单击“添加Page”</p>
        </div>
        <div class="row margin-0">
            <div class="col-md-11 bg-white" style="padding:20px 10px">
                <form class="simcy-form" action="<?= url("Facebook@addFbAccount"); ?>" data-parsley-validate="" loader="true" method="POST" enctype="multipart/form-data">

                    <div class="light-card table-responsive p-b-3em">
                        <table class="table display page-list" id="data-table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Facebook邮件</th>
                                    <th>Page 姓名</th>
                                    <th class="text-center w-70">行动</th>
                                </tr>
                            </thead>
                            <tbody>

                                @if ( count($fb_user) > 0 )
                                @foreach ( $fb_user as $index => $each_fb_user )
                                <tr>
                                    <td>{{$index + 1 }}</td>
                                    <td>{{$each_fb_user->fb_email}}</td>
                                    <td><strong></strong></td>

                                    <td class="text-center">
                                        <a class="btn btn-success"  href="<?= url("Facebook@addPage"); ?>{{$each_fb_user->fb_id}}" >设置Page</a>
                                    </td>
                                </tr>
                                    @foreach($each_fb_user->fb_page as $each_fb_page)
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td>{{$each_fb_page->page_name}}</td>

                                        <td class="text-center">
                                        </td>
                                    </tr>
                                    @endforeach
                                @endforeach

                                @else
                                <tr>
                                    <td colspan="9" class="text-center">这里是空的</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </form>
            </div>

        </div>
    </div>

    <!-- Publish Post Modal -->
    <div class="modal fade" id="publish-post" role="dialog">
        <div class="close-modal" data-dismiss="modal">&times;</div>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">发布帖子</h4>
                </div>
                <form class="update-holder simcy-form" id="update-customer-form" action="<?= url("Posting@publishPostFacebook"); ?>" data-parsley-validate="" loader="true" method="POST" enctype="multipart/form-data">
                    <div class="loader-box">
                        <div class="circle-loader"></div>
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
                "ordering": false,
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