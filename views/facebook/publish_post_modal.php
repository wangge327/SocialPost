<div class="modal-body">
    <p>Post Content</p>
    <div class="form-group">
        <div class="row">
            <div class="col-md-12 ">
                <input type="hidden" name="csrf-token" value="{{ csrf_token(); }}" />
                <input type="hidden" name="user_id" value="{{ $user_id }}" />
                <input type="hidden" name="fb_id" value="{{ $fb_id }}" />
                <input type="hidden" name="page_id" value="{{ $page_id }}" />
                <input type="hidden" name="page_name" value="{{ $page_name }}" />
                <input type="hidden" name="page_access_token" value="{{ $page_access_token }}" />
                <textarea style="width: 100%; height: 250px;" name="message" data-parsley-required="true"></textarea>
            </div>
        </div>
    </div>

</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    <button type="submit" class="btn btn-primary">Publish</button>
</div>