@if($_SESSION["google_login"])
<div class="modal-body">

    <p>Edit Comment</p>
    <div class="form-group">
        <div class="row">
            <div class="col-md-12 ">
                <input type="hidden" name="csrf-token" value="{{ csrf_token(); }}" />
                <input type="hidden" name="user_id" value="{{ $user_id }}" />
                <input type="hidden" name="video_id" value="{{ $video_id }}" />
                <input type="hidden" name="comment_thread" value="{{ $comment_thread }}" />
                <textarea style="width: 100%; height: 250px;" name="comment" data-parsley-required="true"></textarea>
            </div>
        </div>
    </div>

</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    <button type="submit" class="btn btn-primary">Send Comment</button>
</div>
@else
<div class="modal-body">
    <p style="color:red">No login with Youtube</p>
    <div class="form-group">
        <div class="row">
            <div class="col-md-12 ">
                <span>Login information is missed. Please login Youtube to send comment.</span>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    <a class="btn btn-primary" href="{{env('APP_URL')}}/youtube">Youtube login</a>
</div>
@endif