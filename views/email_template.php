<?php include "includes/head.php" ?>
<body>
    <!-- header start -->
    {{ view("includes/header", $data); }}
    <!-- sidebar -->
    {{ view("includes/sidebar", $data); }}
    <div class="content">
        <div class="page-title">
            <div class="pull-right page-actions lower">
                <button class="btn btn-primary" data-toggle="modal" data-target="#create_template" data-backdrop="static" data-keyboard="false"><i class="ion-plus-round"></i> New Template</button>
            </div>
            <h3>Email Template</h3>
            <p>All Templates lists.</p>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="light-card table-responsive p-b-3em">
                    <table class="table display companies-list" id="data-table">
                        <thead>
                            <tr>
                                <th class="text-center w-70"></th>
                                <th>Template Name</th>
                                <th>Email Title</th>
                                <th class="text-center w-70">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ( count($email_templates) > 0 )
                            @foreach ( $email_templates as $index => $template )
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td><strong>{{ $template->name }}</strong> </td>
                                <td><strong>{{ $template->email_title }}</strong> </td>

                                <td class="text-center">
                                    <div class="dropdown">
                                        <span class="company-action dropdown-toggle" data-toggle="dropdown"><i class="ion-ios-more"></i></span>
                                        <ul class="dropdown-menu" role="menu">
                                            <li role="presentation">
                                                <a class="fetch-display-click" data="templateid:{{ $template->id }}|csrf-token:{{ csrf_token() }}" url="<?=url("EmailTemplate@updateview");?>" holder=".update-holder" modal="#update" href="">Edit</a>
                                                <a class="send-to-server-click"  data="templateid:{{ $template->id }}|csrf-token:{{ csrf_token() }}" url="<?=url("EmailTemplate@delete");?>" warning-title="Are you sure?" warning-message="This Template will be deleted." warning-button="Continue" loader="true" href="">Delete</a>
                                            </li>
                                        </ul>
                                    </div>
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

    <!--Create Template-->
    <div class="modal fade" id="create_template" role="dialog">
        <div class="close-modal" data-dismiss="modal">&times;</div>
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Create Template</h4>
                </div>
                <form class="simcy-form" id="create-template-form" action="<?=url("EmailTemplate@create");?>" data-parsley-validate="" loader="true" method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <p>Fill in Template's details</p>
                        <div class="form-group">
	                        <div class="row">
	                            <div class="col-md-12 ">
	                                <label>Template Name</label>
	                                <input type="text" class="form-control" id="template_name" placeholder="Email Template Name" data-parsley-required="true" name="template_name">
	                                <input type="hidden" name="csrf-token" value="{{ csrf_token() }}" />
	                            </div>
	                        </div>
	                    </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12 ">
                                    <label>Email Title</label>
                                    <input type="text" class="form-control" id="email_title" placeholder="Email Title" data-parsley-required="true" name="email_title">
                                    <input type="hidden" name="csrf-token" value="{{ csrf_token() }}" />
                                </div>
                            </div>
                        </div>
	                    <div class="form-group">
	                        <div class="row">
	                            <div class="col-md-12">
	                                <label>Email Content</label>
                                    <input type="hidden" name="email_content" id="email_content_hidden">
                                    <textarea class="form-control" id="email_content" placeholder="Email Content"></textarea>
	                            </div>
	                        </div>
	                    </div>

                    </div>
                    <div class="modal-footer">                    	
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary submit-bt">Create Template</button>
                    </div>
                </form>
            </div>

        </div>
    </div>


    <!-- Update Template Modal -->
    <div class="modal fade" id="update" role="dialog">
        <div class="close-modal" data-dismiss="modal">&times;</div>
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Update Email Template</h4>
                </div>
                <form class="update-holder simcy-form" id="update-template-form" action="<?=url("EmailTemplate@update");?>" data-parsley-validate="" loader="true" method="POST" enctype="multipart/form-data">
                    <div class="loader-box"><div class="circle-loader"></div></div>
                </form>
            </div>

        </div>
    </div>

    <!-- footer -->
    {{ view("includes/footer"); }}

    <script src="<?=url("");?>assets/js/ckeditor/ckeditor.js"></script>
    <script src="<?=url("");?>assets/js/ckeditor/adapters/jquery.js"></script>

    <script>
        $(document).ready(function() {
            $("#email_content").ckeditor();

            @if ( count($email_templates) > 0 )
            $('#data-table').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    'copyHtml5',
                    'excelHtml5',
                    'csvHtml5'
                ]
            });
            @endif

            $(".submit-bt").click(function()
            {
                var ckeditor_content = encodeURI(CKEDITOR.instances.email_content.getData());
                $("#email_content_hidden").val(ckeditor_content);
                $("#create-template-form").submit();
            });
        });
    </script>


</body>

</html>
