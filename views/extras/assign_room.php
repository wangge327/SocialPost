<div class="modal-body">
    <!--    <p>Room assignment screen which the user will be presented with two options.</p>-->
    <label>Student Name: {{ $user->fname }} {{ $user->lname }}</label>
    <br>
    <label>Lease Date: from {{ $user->lease_start}} to {{ $user->lease_end}}</label>
    <hr>
    <?php include "select_room.php" ?>

    <input type="hidden" name="csrf-token" value="{{ csrf_token(); }}"/>
    <input type="hidden" name="user_id" value="{{ $user->id }}"/>
    <hr>
    @if (env("SITE_Portal"))
        <div class="form-group">
            <div class="row">
                <div class="col-sm-3">
                    <label class="control-label" style="padding-top: 6px">Card Number</label>
                </div>
                <div class="col-sm-9">
                    <input type="number" class="form-control" placeholder="Please input card number" name="card_number">
                </div>
            </div>
        </div>
    <hr>
    @endif
    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal" onClick="window.location.reload();">Assign Later</button>
        <button type="submit" class="btn btn-primary">Assign Room</button>
    </div>
</div>
