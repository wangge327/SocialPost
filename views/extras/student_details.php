<div class="form-group">
    <div class="row">
        <div class="col-md-6">
            <label>Gender</label>
            <select class="form-control" name="gender">
                <option value="Male" @if( !empty( $student->gender == "Male" ) ) selected @endif >Male</option>
                <option value="Female" @if( !empty( $student->gender == "Female" ) ) selected @endif >Female</option>
            </select>
        </div>
        <div class="col-md-6">
            <label>Birthday</label>
            <input type="text" class="form-control" name="birthday" value="{{ $student->birthday }}" autocomplete="off" placeholder="Birthday">
        </div>
    </div>
</div>
<div class="form-group">
    <div class="row">
        <div class="col-md-6">
            <label>Country</label> <label class="color-red">*</label>
            <input type="text" class="form-control" name="country" value="{{ $student->country }}" placeholder="Country" data-parsley-required="true">
        </div>
        <div class="col-md-6">
            <label>Address</label>
            <input type="text" class="form-control" name="address" value="{{ $student->address }}" placeholder="Address">
        </div>
    </div>
</div>
<div class="form-group">
    <div class="row">
        <div class="col-md-6">
            <label>City</label>
            <input type="text" class="form-control" name="city" value="{{ $student->city }}" placeholder="City">
        </div>
        <div class="col-md-6">
            <label>State</label>
            <input type="text" class="form-control" name="state" value="{{ $student->state }}" placeholder="state">
        </div>
    </div>
</div>

<div class="form-group">
    <div class="row">
        <div class="col-md-12">
            <label>Miscellaneous notes</label>
            <textarea class="form-control" name="extra_note" rows="3" placeholder="Miscellaneous notes">{{ $student->extra_note }}</textarea>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $(".input-daterange").datepicker({
            startDate: new Date(),
            todayHighlight: !0,
            format: 'yyyy-mm-dd'
        });

        $("[name='birthday']").datepicker({
            todayHighlight: !0,
            format: 'yyyy-mm-dd'
        });
    });
</script>