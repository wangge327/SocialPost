<style>
    .tp-back {
        font-size: 14px !important;
    }
</style>
<div class="page-title" style="font-size: 16px;">
    <div class="row">
        <div class="col-md-7">
            <div class="row" style="margin: 10px">
            </div>
            <div class="row">
                @if( $user->page_title !='Student Profile' )
                <div class="col-md-4">
                    @if( $user->role =='user' )
                    <a href="<?= url('Dashboard@get'); ?>" class="dt-button tp-back">返回仪表板</a>
                    @else
                    <a href="<?= url("Customer@profile"); ?>{{$student->id}}" class="dt-button tp-back">Back to Student Profile</a>
                    @endif
                </div>
                <div class="col-md-4">
                    <label>{{ $user->page_title }}</label>
                </div>
                @endif
            </div>
        </div>
        <div class="col-md-3 bg-white" style="margin-top:10px ">
        </div>
        <div class="col-md-2" style="margin-top:10px">
            <span class=" avatar dash-avatar">
                @if( !empty($student->avatar) )
                <img src="<?= url("/"); ?>uploads/avatar/{{ $student->avatar }}" class="user-avatar img-circle">
                @else
                <img src="<?= url("/"); ?>assets/images/avatar.png" class="user-avatar">
                @endif
            </span>
        </div>
    </div>
</div>