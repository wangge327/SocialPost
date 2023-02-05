<div class="payment col-sm-6">
    <h4>{{ $result }}</h4>
    @if( $user->role !='user' )
        <a href="<?=url("Customer@profile");?>{{$payment_user->id}}" class="btn btn-primary"  style="margin: 20px">Go to Student Details</a>
        <a href="{{url('PrintController@receiptPrint').$payment_user->id}}"  class="btn btn-success" style="margin: 20px">Print Receipt</a>
    @endif
</div>

<script>
@if($checkin_done == "done")
	setTimeout(function(){ logout(); }, 3000);
@endif

function logout(){
	location.replace("/signout");
}
</script>