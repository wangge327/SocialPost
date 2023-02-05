<div class="modal-body">
    <p>Update student info.</p>
    <div class="form-group">
        <div class="row">
            <div class="col-md-6 ">
                <label>First name</label>
                <input type="text" class="form-control" name="fname" value="{{ $student->fname }}" placeholder="First name"
                       data-parsley-required="true">
                <input type="hidden" name="csrf-token" value="{{ csrf_token(); }}"/>
                <input type="hidden" name="customerid" value="{{ $student->id }}"/>
                <input type="hidden" name="table" value="{{ $table }}"/>
            </div>
            <div class="col-md-6">
                <label>Last name</label>
                <input type="text" class="form-control" name="lname" value="{{ $student->lname }}" placeholder="Last name"
                       data-parsley-required="true">
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="row">
            <div class="col-md-6">
                <label>Email address</label>
                <input type="email" class="form-control" name="email" value="{{ $student->email }}"
                       placeholder="Email address" data-parsley-required="true">
            </div>
            <div class="col-md-6">
                <label>Phone number</label>
                <input type="text" class="form-control" name="phone" value="{{ $student->phone }}"
                       placeholder="Phone number">
            </div>
        </div>
    </div>
    <?php include 'student_details.php'?>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    <button type="submit" class="btn btn-primary">Save Changes</button>
</div>
