<div class="modal-body">
    <label>Student Name: {{ $user->fname }} {{ $user->lname }}</label>
    <label style="margin-left: 50px;">Room Name: {{ $room_name }}</label>
    <hr>
    <label>Lease Date: from {{ $user->lease_start}} to {{ $user->lease_end}}</label>
    <hr>
    <div class="form-group">
        <label>Fine history</label>
        {{ view("includes/fine_history_checkout", $data); }}
    </div>
	

    <input type="hidden" name="csrf-token" value="{{ csrf_token(); }}"/>
    <input type="hidden" name="user_id" value="{{ $user->id }}"/>
    <input type="hidden" name="total_amount" value="{{ $total_amount }}"/>
    <hr>
    <div class="checkout-total">
        <label class="color-red">Total amount:</label>&nbsp;&nbsp; ${{$total_amount}}
    </div>

    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Check Out</button>
    </div>
</div>