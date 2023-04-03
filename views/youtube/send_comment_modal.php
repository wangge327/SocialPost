@if($_SESSION["google_login"])
<div class="modal-body">

    <p>编辑评论</p>
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
    <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
    <button type="submit" class="btn btn-primary">发表评论</button>
</div>
@else
<div class="modal-body">
    <p style="color:red">没有登录Youtube</p>
    <div class="form-group">
        <div class="row">
            <div class="col-md-12 ">
                <span>登录信息丢失。 请登录 Youtube 发送评论。</span>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
    <a class="btn btn-primary" href="{{env('APP_URL')}}/youtube">Youtube登录</a>
</div>
@endif