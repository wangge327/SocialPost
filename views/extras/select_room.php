    <div class="form-group">
        <div class="row">
            <label class="col-sm-3 control-label">Building</label>
            <div class="col-sm-9">
                <select name="building_id" class="form-control chosen-select" id="building-name" data-parsley-required="true">
                    <option value="">Select Building</option>
                    @foreach ( $buildings as $key => $element )
                        <option value="{{$element->id}}">{{$element->name}}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="row">
            <label class="col-sm-3 control-label">Room</label>
            <div class="col-sm-9">
                <select name="room_id" class="form-control" id="room-name" data-parsley-required="true">
                    <option value="">Select Room</option>
                </select>
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="row">
            <label class="col-sm-3 control-label">Bed</label>
            <div class="col-sm-9">
                <select name="bed_id" class="form-control" id="city-name" data-parsley-required="true">
                    <option value="">Select Bed</option>
                </select>
            </div>
        </div>
    </div>

