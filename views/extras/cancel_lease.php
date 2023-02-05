<div class="modal-body">
    <label>Student Name: {{ $user->fname }} {{ $user->lname }}</label>
    <hr>
    <label>Lease Date: from {{ $user->lease_start}} to {{ $user->lease_end}}</label>
    <hr>
    <div class="form-group">
    @if ( count($cancel_lease_items) > 0 )
    @foreach ( $cancel_lease_items as $index => $cancel_lease_item )
        <div class="row" style="margin-top: 5px;">
            <input class="col-sm-1" type="checkbox" name="cancel_lease_item_ids[]" value="{{ $cancel_lease_item->id }}">
            <div class="col-sm-9" style="padding-left: 0;">
                {{ $cancel_lease_item->name }}
            </div>
        </div>
    @endforeach
    @endif
    </div>

    <input type="hidden" name="csrf-token" value="{{ csrf_token(); }}"/>
    <input type="hidden" name="user_id" value="{{ $user->id }}"/>

    <hr>
    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Cancel Lease</button>
    </div>
</div>
