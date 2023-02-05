<div class="modal-header">
    <h4 class="modal-title">Fine History</h4>
</div>
<div class="modal-body" style="height: 100%">
    <label>Student Name: <span class="color-red">{{ $user->fname }} {{ $user->lname }}</span></label>
    <label style="margin-left: 50px;">Room Name: {{ $room_name }}</label>
    <hr>
    <label>Lease Date: from {{ $user->lease_start}} to {{ $user->lease_end}}</label>
    <hr>
    <label>Person who is logged assigning the Fine: <span class="color-red"> {{ $record_person->fname}} {{ $record_person->lname}}</span></label>
    <hr>
    <div class="form-group">
        <label>Fine Fees</label>
        @if ( count($fine_fees) > 0 )
        @foreach ( $fine_fees as $index => $fine_fee )
            <div class="row fine_item" style="margin-top: 5px;">
                <div class="col-sm-9" style="padding-left: 0;margin-left: 20px">
                    {{ $fine_fee->type }} - ${{ $fine_fee->amount }}
                </div>
            </div>
        @endforeach
        @endif
    </div>

    <hr>
    <div class="form-group">
        <label>Note : &nbsp;{{$fine_note}}</label>
    </div>
    <hr>
    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    </div>
</div>
