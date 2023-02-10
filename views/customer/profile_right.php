@if($user->role != "user" )
<div class="bg-white padding-10" style="margin-top: 10px">
    <div class="row text-align-center">
        @if($student->status == 'Created' || $student->status == 'Arrived' )
        <button class="btn btn-primary" data-toggle="modal" data-target="#change-lease-start" data-backdrop="static" data-keyboard="false">Change Arrived Date</button>
        @endif
        <button class="fetch-display-click btn btn-primary" data="customerid:{{ $student->id }}|csrf-token:{{ csrf_token() }}" url="<?= url("Customer@cancel_lease"); ?>" holder=".holder-cancel-lease" modal="#cancel_lease_modal" href="">Cancel Lease</button>
    </div>
</div>
@endif

<div class="bg-white padding-10">
    <div class="row text-align-center margin-0">
        Facebook 帖子数 :&nbsp;
        <label class="color-red">20</label>
    </div>
</div>

<div class="bg-white padding-10 mg-top-20">
    <div class="row text-align-center margin-0">
        Twitter 帖子数 :&nbsp;
        <label class="color-red">20</label>
    </div>
</div>

<div class="bg-white padding-10 mg-top-20">
    <div class="row text-align-center margin-0">
        Youtube 帖子数 :&nbsp;
        <label class="color-red">20</label>
    </div>
</div>
