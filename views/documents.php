<?php include "includes/head.php" ?>

<body>

    <!-- header start -->
    {{ view("includes/header", $data); }}

    <!-- sidebar -->
    {{ view("includes/sidebar", $data); }}

    <div class="content">
        <div class="page-title documents-page" style="overflow:visible;">
            <div class="row">
                <div class="col-md-6 col-xs-6">
                    <h3>Documents</h3>
                    <p class="breadcrumbs text-muted"><span class="home-folder">Home Folder</span></p>
                </div>
                <div class="col-md-6 col-xs-6 text-right page-actions">
                    <button href="" class="btn btn-default go-back hidden-xs"><i class="ion-ios-arrow-back"></i> Back</button>
                    <button class="btn btn-primary-ish  hidden-xs" data-toggle="modal" data-target="#createFolder" data-backdrop="static" data-keyboard="false"><i class="ion-folder"></i> Create Folder</button>
                    <div class="dropdown">
                        <button class="btn btn-primary dropdown-toggle" data-toggle="dropdown"><i class="ion-arrow-down-b"></i> New File </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 documents-group-holder">
                <div class="documents-filter light-card hidden-xs">
                    <div class="light-card-title">
                        <h4>Filter</h4>
                    </div>
                    <div class="documents-filter-form">
                        <form>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-12">
                                        <label class="radio"><input type="radio" name="status" value="" checked><span class="outer"><span class="inner"></span></span>All</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-12">
                                        <label class="radio"><input type="radio" name="status" value="Signed"><span class="outer"><span class="inner"></span></span>Signed</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-12">
                                        <label class="radio"><input type="radio" name="status" value="Unsigned"><span class="outer"><span class="inner"></span></span>Un-Signed</label>
                                    </div>
                                </div>
                            </div>
                            <div class="divider"></div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-12">
                                        <label class="radio"><input type="radio" name="type" value="" checked><span class="outer"><span class="inner"></span></span>All</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-12">
                                        <label class="radio"><input type="radio" name="type" value="files"><span class="outer"><span class="inner"></span></span>Files</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-12">
                                        <label class="radio"><input type="radio" name="type" value="folders"><span class="outer"><span class="inner"></span></span>Folders</label>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="row documents-grid">
                    <div class="col-md-12 content-list">
                        <div class="loader-box">
                            <div class="circle-loader"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- footer -->
    {{ view("includes/footer"); }}

    <div class="select-option">
        <div class="btn-group btn-group-justified">
            <a href="" action="open" class="btn btn-primary" data-toggle="tooltip" data-container="body" data-placement="top" data-original-title="Open"><i class="ion-ios-eye"></i></a>
            @if ( in_array("delete",json_decode($user->permissions)) )
            <a href="" action="delete" class="btn btn-primary" data-toggle="tooltip" data-container="body" data-placement="top" data-original-title="Delete"><i class="ion-ios-trash"></i></a>
            @endif
            <a href="" action="rename" class="btn btn-primary" data-toggle="tooltip" data-container="body" data-placement="top" data-original-title="Rename"><i class="ion-edit"></i></a>
            @if ( env('DISABLE_SHARE') != "Enabled" )
            <a href="" action="share" class="btn btn-primary" data-toggle="tooltip" data-container="body" data-placement="top" data-original-title="Share"><i class="ion-share"></i></a>
            @endif
        </div>
    </div>


    <!-- Upload file Modal -->
    <div class="modal fade" id="uploadFile" role="dialog">
        <div class="close-modal" data-dismiss="modal">&times;</div>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Upload File</h4>
                </div>
                <form class="simcy-form" id="upload-file-form" action="<?= url("Document@uploadfile"); ?>" data-parsley-validate="" loader="true" method="POST">
                    <div class="modal-body">
                        <p>Only PDF allowed.</p>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12">
                                    <label>File name</label>
                                    <input type="text" class="form-control" name="name" placeholder="File name" data-parsley-required="true">
                                    <input type="hidden" name="folder" value="1">
                                    <input type="hidden" name="csrf-token" value="{{ csrf_token() }}" />
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12">
                                    <label>Choose file</label>
                                    <input type="file" name="file" class="dropify" data-parsley-required="true" data-allowed-file-extensions="pdf">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Upload file</button>
                    </div>
                </form>
            </div>

        </div>
    </div>


    <!-- Create folder Modal -->
    <div class="modal fade" id="createFolder" role="dialog">
        <div class="close-modal" data-dismiss="modal">&times;</div>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Create Folder</h4>
                </div>
                <form class="simcy-form" id="create-folder-form" action="<?= url("Document@createfolder"); ?>" data-parsley-validate="" loader="true" method="POST">
                    <div class="modal-body">
                        <p class="text-muted">Use folders to organise your files.</p>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12">
                                    <label>Folder name</label>
                                    <input type="text" class="form-control" name="name" placeholder="Folder name" data-parsley-required="true">
                                    <input type="hidden" name="folder" value="1">
                                    <input type="hidden" name="csrf-token" value="{{ csrf_token() }}" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button class="btn btn-primary" type="submit">Create Folder</button>
                    </div>
                </form>
            </div>

        </div>
    </div>


    <!-- Rename folder Modal -->
    <div class="modal fade" id="renamefolder" role="dialog">
        <div class="close-modal" data-dismiss="modal">&times;</div>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Rename Folder</h4>
                </div>
                <form class="simcy-form" id="rename-folder-form" action="<?= url("Document@updatefolder"); ?>" data-parsley-validate="" loader="true" method="POST">
                    <div class="modal-body">
                        <p class="text-muted">Change the name of your folder.</p>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12">
                                    <label>Folder name</label>
                                    <input type="text" class="form-control" name="foldername" placeholder="Folder name" data-parsley-required="true">
                                    <input type="hidden" name="folder" value="1">
                                    <input type="hidden" name="folderid">
                                    <input type="hidden" name="csrf-token" value="{{ csrf_token() }}" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button class="btn btn-primary" type="submit">Rename Folder</button>
                    </div>
                </form>
            </div>

        </div>
    </div>


    <!-- Rename file Modal -->
    <div class="modal fade" id="renamefile" role="dialog">
        <div class="close-modal" data-dismiss="modal">&times;</div>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Rename File</h4>
                </div>
                <form class="simcy-form" id="rename-file-form" action="<?= url("Document@updatefile"); ?>" data-parsley-validate="" loader="true" method="POST">
                    <div class="modal-body">
                        <p class="text-muted">Change the name of your file.</p>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12">
                                    <label>File name</label>
                                    <input type="text" class="form-control" name="filename" placeholder="File name" data-parsley-required="true">
                                    <input type="hidden" name="folder" value="1">
                                    <input type="hidden" name="fileid">
                                    <input type="hidden" name="csrf-token" value="{{ csrf_token() }}" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button class="btn btn-primary" type="submit">Rename File</button>
                    </div>
                </form>
            </div>

        </div>
    </div>

    <!-- Shared Modal -->
    <div class="modal fade" id="shared" role="dialog">
        <div class="close-modal" data-dismiss="modal">&times;</div>
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Update Information </h4>
                </div>
                <form class="shared-holder simcy-form" action="" data-parsley-validate="" loader="true" method="POST" enctype="multipart/form-data">
                    <div class="loader-box">
                        <div class="circle-loader"></div>
                    </div>
                </form>
            </div>

        </div>
    </div>



    <!-- Share Modal -->
    <div class="modal fade" id="sharefile" role="dialog">
        <div class="close-modal" data-dismiss="modal">&times;</div>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Document Sharing</h4>
                </div>
                <div class="modal-body">
                    <p>Anyone with the link below can view and edit this document.</p>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-12">
                                <label>Sharing link</label>
                                <input type="text" id="foo" class="form-control sharing-link" placeholder="Sharing link" readonly="readonly">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary copy-link" data-clipboard-action="copy" data-clipboard-target="#foo">Copy Link</button>
                </div>
            </div>

        </div>
    </div>


    <!-- folder right click -->
    <div id="folder-menu" class="dropdown clearfix folder-actions">
        <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu" style="display:block;position:static;margin-bottom:5px;">
            <li><a tabindex="-1" action="open" href="">Open</a>
            </li>
            <li><a tabindex="-1" action="rename" href="">Rename</a>
            </li>
            <li><a tabindex="-1" action="protect" href="">Protect</a>
            </li>
            @if ( $user->role != "user" )
            <li><a tabindex="-1" action="access" href="">Accessibility</a>
            </li>
            @endif
            @if ( in_array("delete",json_decode($user->permissions)) )
            <li class="divider"></li>
            <li><a tabindex="-1" action="delete" href="">Delete</a>
            </li>
            @endif
        </ul>
    </div>


    <!-- folder right click -->
    <div id="back-folder-menu" class="dropdown clearfix folder-actions">
        <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu" style="display:block;position:static;margin-bottom:5px;">
            <li><a tabindex="-1" action="open" href="">Open</a>
            </li>
        </ul>
    </div>

    <!--  file right click -->
    <div id="file-menu" class="dropdown clearfix file-actions">
        <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu" style="display:block;position:static;margin-bottom:5px;">
            <li><a action="open" href="">Open</a>
            </li>
            <li><a action="rename" href="">Rename</a>
            </li>
            <li><a action="duplicate" href="">Duplicate</a>
            </li>
            @if ( env('DISABLE_SHARE') != "Enabled" )
            <li><a action="share" href="">Share</a>
            </li>
            @endif
            <li><a action="download" href="">Download</a>
            </li>
            @if ( $user->role != "user" )
            <li><a tabindex="-1" action="access" href="">Accessibility</a>
            </li>
            @endif
            @if ( in_array("delete",json_decode($user->permissions)) )
            <li class="divider"></li>
            <li><a action="delete" href="">Delete</a>
            </li>
            @endif
        </ul>
    </div>


    <script src="<?= url("/"); ?>assets/libs/jquery-ui/jquery-ui.min.js"></script>
    <script src="<?= url("/"); ?>assets/libs/bootstrap/js/bootstrap.min.js"></script>

    <script src="<?= url("/"); ?>assets/libs/select2/js/select2.min.js"></script>
    <script src="<?= url("/"); ?>assets/js/simcify.min.js"></script>
    <script src="<?= url("/"); ?>assets/libs/clipboard/clipboard.min.js"></script>

    <script src="<?= url("/"); ?>assets/js/files.js"></script>

</body>

</html>