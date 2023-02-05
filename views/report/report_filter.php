<div class="report-filter">
    <div class="modal-body">
        <div style="height: 35px">
            <label class="float-left mr-30" style="margin-top: 4px;">Please select Column : </label>
            <select id="column_select" class="form-control valid float-left mr-30" style="width: 150px;">
                <option>None</option>
                @foreach ( $filters as $each_column )
                <option value="{{$each_column['id']}}">{{$each_column['label']}}</option>
                @endforeach
            </select>
            <input type="button" class="btn btn-success float-left mr-30" id="add_row" value="Add Filter">
        </div>
    </div>

    <div class="filter-area modal-body" style="margin-top:30px;"></div>
</div>
<hr>
<script src="<?=url("");?>assets/js/jquery-3.2.1.min.js"></script>
<script>
    $(document).ready(function() {
        $(".datepicker").datepicker({
            todayHighlight: !0,
            format: 'yyyy-mm-dd'
        });
    });
</script>
