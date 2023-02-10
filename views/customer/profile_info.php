<style>
    table tr td.profile-item-title {
        color: #A9ABB3;
        padding-right: 15px;
    }

    table tr td.profile-item-value {
        color: #000;
        font-weight: 600;
    }

    .contact .btn {
        margin: 6px;
    }
</style>
<div class="row bg-white">
    <div class="col-md-10">
        <div class="row margin-0 line-height-30">
            <h4>联系信息</h4>
            <table style="margin: auto">
                <tbody>
                    <tr>
                        <td class="profile-item-title">名称</td>
                        <td class="profile-item-value">{{ $student->fname }} {{ $student->lname }}</td>
                    </tr>
                    <tr>
                        <td class="profile-item-title">电子邮件</td>
                        <td class="profile-item-value">{{ $student->email }}</td>
                    </tr>
                    <tr>
                        <td class="profile-item-title">地址</td>
                        <td class="profile-item-value">{{ $student->address }} {{ $student->city }} {{ $student->state }}, {{ $student->country }}</td>
                    </tr>
                    <tr>
                        <td class="profile-item-title">电话</td>
                        <td class="profile-item-value">{{ $student->phone }}</td>
                    </tr>
                    <tr>
                        <td class="profile-item-title">PIN码</td>
                        <td class="profile-item-value">{{ $student->pin }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="col-md-2 contact">
        <button class="btn btn-primary" data-toggle="modal" data-target="#member_edit">编辑</button>
        @if($user->role != "user")
        <a href="{{url('PrintController@idPrint').$student->id}}" id="printBtn" class="btn btn-success">ID Print</a>
        <a href="{{url('PrintController@proofOfAddress').$student->id}}" class="btn btn-success">Proof of Address</a>
        @endif
    </div>
</div>

<div class="mg-top-20 admin-student">
    <button class="btn btn-success btn-2" data-toggle="modal" data-target="#note_dialog">杂记</button>

    <a class="btn btn-success btn-2" href="{{ url('Member@takePicture').$student->id }}">拍照片 </a>




    @if($user->role!='user')
    <a class="btn btn-success btn-2" href="{{ url('Member@takePictureID').$student->id }}">Take Picture for ID </a>
    <button class="btn btn-success btn-2" data-toggle="modal" data-target="#send-email" data-backdrop="static" data-keyboard="false">Send Email</button>

    @if($student->status != 'Terminated')
    <a class="send-to-server-click btn btn-success btn-2" data="email:{{ $student->email }}|user_id:{{ $student->id }}|csrf-token:{{ csrf_token() }}" url="<?= url("Customer@resendDocusign"); ?>" warning-title="Are you sure?" warning-message="The docusign email will be sent to this student's email." warning-button="Continue" loader="true" href="">Resend Docusign</a>
    <a class="send-to-server-click btn btn-success btn-2" data="email:{{ $student->email }}|csrf-token:{{ csrf_token() }}" url="<?= url("Customer@sendCheckin"); ?>" warning-title="Are you sure?" warning-message="The check-in link will be sent to this student's email." warning-button="Continue" loader="true" href="">Send Checkin</a>

    @if ($student->status!='Created')
    <a class="fetch-display-click btn btn-success btn-2" data="customerid:{{ $student->id }}|csrf-token:{{ csrf_token() }}" url="<?= url("Customer@add_fine"); ?>" holder=".holder-add-fine" modal="#add_fine" href="">Add Fine</a>

    @endif
    @endif
    @endif
</div>

{{ view("includes/social_history", $data); }}

<!-- Edit User Account-->
<div class="modal fade" id="member_edit" role="dialog">
    <div class="close-modal" data-dismiss="modal">&times;</div>
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">编辑个人信息</h4>
            </div>
            <form class="simcy-form" action="<?= url("Member@updateDashboardUser"); ?>" data-parsley-validate="" loader="true" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="customerid" value="{{ $user->id }}">
                <input type="hidden" name="csrf-token" value="{{ csrf_token() }}" />
                <div class="modal-body">
                    <p>填写个人信息</p>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6">
                                <label>姓</label>
                                <input type="text" class="form-control" name="fname" value="{{ $student->fname }}" placeholder="First Name" data-parsley-required="true">
                            </div>

                            <div class="col-md-6">
                                <label>名字</label>
                                <input type="text" class="form-control" name="lname" value="{{ $student->lname }}" placeholder="Last Name" data-parsley-required="true">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6">
                                <label>地址</label>
                                <input type="text" class="form-control" name="address" value="{{ $user->address }}" placeholder="Address">
                            </div>

                            <div class="col-md-6">
                                <label>城市</label>
                                <input type="text" class="form-control" name="city" value="{{ $user->city }}" placeholder="City">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6">
                                <label>省</label>
                                <input type="text" class="form-control" name="state" value="{{ $user->state }}" placeholder="state">
                            </div>
                            <div class="col-md-6">
                                <label>国家</label>
                                <input type="text" class="form-control" name="country" value="{{ $user->country }}" placeholder="Country" data-parsley-required="true">
                            </div>
                        </div>
                    </div>
                    <!--
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-12">
                                <label>Email</label>
                                <input type="text" class="form-control" name="email" value="{{ $user->email }}" placeholder="Email" data-parsley-required="true">
                            </div>
                        </div>
                    </div>
-->
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6">
                                <label>电话</label>
                                <input type="text" class="form-control" name="phone" value="{{ $user->phone }}" placeholder="Phone">
                            </div>
                            <div class="col-md-6">
                                <label>PIN码</label>
                                <input type="text" class="form-control" name="pin" value="{{ $user->pin }}" placeholder="Pin code" data-parsley-required="true">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                    <button type="submit" class="btn btn-primary">保存个人信息</button>
                </div>
            </form>
        </div>

    </div>
</div>

<!-- Note dialog -->
<div class="modal fade" id="note_dialog" role="dialog">
    <div class="close-modal" data-dismiss="modal">&times;</div>
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">杂记</h4>
            </div>
            <form class="simcy-form" action="<?= url("Member@saveNote"); ?>" data-parsley-validate="" loader="true" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="user_id" value="{{ $student->id }}">
                <input type="hidden" name="csrf-token" value="{{ csrf_token() }}" />

                <div class="modal-body" style="height: 270px;">
                    @if ($user->role!='user')
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-12">
                                <label>Internal note (Only Admin Staff can see)</label>
                                <textarea class="form-control" name="internal_note" rows="3">{{ $student->internal_note }}</textarea>
                            </div>
                        </div>
                    </div>
                    @endif
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-12">
                                <label>请输入字符串</label>
                                <textarea class="form-control" name="extra_note" rows="3">{{ $student->extra_note }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                    <button type="submit" class="btn btn-primary">保存</button>
                </div>
            </form>
        </div>
    </div>
</div>