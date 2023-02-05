var filters_array = JSON.parse(JSON.stringify(filters));
var current_filter_rules_array = [];
var current_filter_total_array = [];
if(current_filter_rules != "")
    current_filter_rules_array = JSON.parse(current_filter_rules);
if(current_filter_total != "")
    current_filter_total_array = JSON.parse(current_filter_total);

var operater = {
    "string": ["=", "like","not like","!="],
    "integer": ["=", ">", "<"],
    "double": ["=", ">", "<"],
    "datetime": ["=", "<=", ">=", "<", ">","Today","Yesterday","This month","Previous month"],
    "boolean": ["1", "0"],
    "date": ["=", "<=", ">=","<", ">","Today","Yesterday","This month","Previous month"]
};
var condition = {
    "period": ["Monthly", "All"],
    "value_range": ["Min", "Max", "Avg", "Total"]
};
init_rows();

$('#add_row').on('click', function() {
    var selected_column = $("#column_select").val();
    var selected_column_array = get_row_from_array(selected_column, filters_array);

    mk_html(selected_column_array['id'], selected_column_array['label'], selected_column_array['type'], "", "");
});

$('#add_total_row').on('click', function() {
    var selected_column = $("#total_column_select").val();
    var selected_column_array = get_row_from_array(selected_column, filters_array);

    mk_total_html(selected_column_array['id'], selected_column_array['label'], selected_column_array['type']);
});

function init_rows(){
    for(i = 0 ; i < current_filter_rules_array.length ; i++){
        mk_html(current_filter_rules_array[i]['column_id'], current_filter_rules_array[i]['column_name'], current_filter_rules_array[i]['column_type'], current_filter_rules_array[i]['operater'], current_filter_rules_array[i]['row_val']);
    }

    for(i = 0 ; i < current_filter_total_array.length ; i++){
        var column_meta = JSON.parse(current_filter_total_array[i]['column_meta']);
        mk_total_html(column_meta['column_id'], column_meta['column_name'], column_meta['column_type'], current_filter_total_array[i]);
    }
}

function mk_html(column_id, column_name, column_type, column_operater, row_val){
    var rand_id = Math.floor(Math.random() * 10000);
    var operater_arr = operater[column_type];
    var column_operater_str = "";
    for(j = 0 ; j < operater_arr.length ; j++ ){
        column_operater_str += '<option value="'+ encodeURIComponent(operater_arr[j]) + '"';
        if(operater_arr[j] == column_operater)
            column_operater_str += "selected";

        column_operater_str += '>'+ operater_arr[j] +'</option>';
    }
    var required_val = "required";
    var disabled_val = "";
    var datepicker = "";
    if((column_type == "date") || (column_type == "datetime")){
        required_val = "";
        datepicker = "datepicker";
        if((column_operater == "Today") || (column_operater == "Yesterday") || (column_operater == "This month") || (column_operater == "Previous month")){
            disabled_val = "disabled";
            row_val = "";
        }
    }

    var html_cont = '<div class="each-row" id="'+ rand_id +'">\n' +
        '            <input type="hidden" name="column_id[]" value="'+ column_id +'">\n' +
        '            <input type="hidden" name="column_name[]" value="'+ column_name +'">\n' +
        '            <input type="hidden" name="column_type[]" value="'+ column_type +'">\n' +
        '            <label class="float-left mr-30">' + column_name + ' : </label>\n' +
        '            <select class="form-control valid float-left mr-50" style="width: 150px;" name="operater[]">\n' + column_operater_str +
        '            </select>\n' +
        '            <input type="text" class="form-control float-left '+datepicker+'" ' + required_val + ' ' + disabled_val +' style="width: 100px;" name="row_val[]" value="'+ row_val +'">\n' +
        '            <input type="button" class="btn btn-success float-right" value="Remove" onclick="remove_row(\''+ rand_id +'\')">\n' +
        '        </div>';

    $(".filter-area").append(html_cont);
    if((column_type == "date") || (column_type == "datetime")){
        $(".datepicker").datepicker({
            todayHighlight: !0,
            format: 'yyyy-mm-dd'
        });
    }
}

function mk_total_html(column_id, column_name, column_type, row_val = []){
    var rand_id = Math.floor(Math.random() * 10000);
    var row_title = ""
    if(row_val.length != 0)
        row_title = row_val['title'];

    var condition_period = "";
    for(j = 0 ; j < condition['period'].length ; j++ ){
        condition_period += '<option value="'+ condition['period'][j] + '"';
        if(condition['period'][j] == row_val['period'])
            condition_period += " selected";

        condition_period += '>'+ condition['period'][j] +'</option>';
    }

    var condition_value_range = "";
    for(k = 0 ; k < condition['value_range'].length ; k++ ){
        condition_value_range += '<option value="'+ condition['value_range'][k] + '"';
        if(condition['value_range'][k] == row_val['value_range'])
            condition_value_range += " selected";

        condition_value_range += '>'+ condition['value_range'][k] +'</option>';
    }

    var html_cont = '<div class="each-row modal-body" id="'+ rand_id +'">\n' +
        '            <input type="hidden" name="column_id[]" value="'+ column_id +'">\n' +
        '            <input type="hidden" name="column_name[]" value="'+ column_name +'">\n' +
        '            <input type="hidden" name="column_type[]" value="'+ column_type +'">\n' +
        '            <label class="float-left mr-30">Title for ' + column_name + ' : </label>\n' +
        '            <input type="text" class="form-control float-left" required style="width: 300px;" name="title[]" value="'+ row_title +'">\n <br/>' +
        '            <div style="margin-top: 35px;">\n' +
        '            <label class="float-left mr-10" style="width: 50px;"> Period : </label>\n' +
        '            <select class="form-control valid float-left mr-50" name="period[]" style="width: 120px;">\n' + condition_period +
        '            </select>\n' +
        '            <label class="float-left mr-10" style="width:90px"> Value Range : </label>\n' +
        '            <select class="form-control valid float-left " name="value_range[]" style="width: 120px;">\n' + condition_value_range +
        '            </select>\n' +
        '            <input type="button" class="btn btn-success float-right" value="Remove" onclick="remove_row(\''+ rand_id +'\')">\n' +
        '            </div>';
        '        </div>';

    $(".total-area").append(html_cont);
}

function remove_row(row_id){
    $("#"+row_id).remove();
}
function get_row_from_array(tstr, tarray){
    var i = 0;
    for(i = 0 ; i < tarray.length ; i++){
        if(tarray[i]['id'] == tstr){
            return tarray[i];
        }
    }
    return 0;
}
