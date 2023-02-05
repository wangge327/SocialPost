<div class="report-filter">
    <div class="modal-body">
        <div style="height: 35px">
            <label class="float-left mr-30" style="margin-top: 4px;">Please select Column : </label>
            <select id="total_column_select" class="form-control valid float-left mr-30" style="width: 150px;">
                <option>None</option>
                @foreach ( $filters_total as $each_column )
                <option value="{{$each_column['id']}}">{{$each_column['label']}}</option>
                @endforeach
            </select>
            <input type="button" class="btn btn-success float-left mr-30" id="add_total_row" value="Add Total">
        </div>
    </div>

    <div class="total-area" style="margin-top:30px;"></div>
</div>
<hr>

