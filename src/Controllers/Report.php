<?php
namespace Simcify\Controllers;

use Simcify\Auth;
use Simcify\Database;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PDO;
use Simcify\Models\PaymentModel;
use function Simcify\print_r2;

class Report extends Admin {

    public function create(){
        $user = Auth::user();
        $reports = array();
        $reports_db = Database::table("report")->get();
        foreach($reports_db as $each_reports_db){
            $each_report = array();
            $each_report['report'] = $each_reports_db;
            $each_report['template'] = Database::table("report_templates")->where("id", $each_reports_db->template_id)->first();
            $reports[] = $each_report;
        }
        $reports_template = Database::table("report_templates")->get();
        return view('report/report_create', compact("user","reports", "reports_template"));
    }

    public function createReport(){
        header('Content-type: application/json');
        $report_data = array(
            "template_id" => input("template_id"),
            "name" => input("name"),
            "description" => input("description")
        );
        Database::table("report")->insert($report_data);
        exit(json_encode(responder("success","Alright", "Report successfully created","reload()")));
    }

    public function updateview() {
        $report = Database::table("report")->where("id", input("reportid"))->first();
        $reports_template = Database::table("report_templates")->get();

        $data = array(
            "report" => $report,
            "reports_template" => $reports_template
        );
        return view('extras/updatereport', $data);
    }

    public function update() {

        foreach (input()->post as $field) {
            if ($field->index == "csrf-token" || $field->index == "reportid") {
                continue;
            }
            Database::table("report")->where("id" , input("reportid"))->update(array($field->index => escape($field->value)));
        }
        header('Content-type: application/json');
        exit(json_encode(responder("success", "Alright!", "Report successfully updated","reload()")));
    }

    public function delete() {
        Database::table("report")->where("id", input("reportid"))->delete();
        Database::table("report_filters")->where("report_id", input("reportid"))->delete();
        Database::table("report_total")->where("report_id", input("reportid"))->delete();
        header('Content-type: application/json');
        exit(json_encode(responder("success", "Report Deleted!", "Report successfully deleted.","reload()")));
    }

    public function browse($r_id){
        $user = Auth::user();
        $report = Database::table("report")->find($r_id);

        $reports_template = Database::table("report_templates")->get();
        $report_template = Database::table("report_templates")->find($report->template_id);
        $tables=explode(',',$report_template->tables);
        $report_columns = array();
        $filters=array();
        foreach ($tables as $table){
            $each_columns=Database::table("report_columns")->where('table_name',$table)->get('name,id')[0];
            $t_each_coulmns['group_name'] = $each_columns->name;
            $columns_meta=Database::table("report_columns_meta")->where("table_name", $table)->get();
            $t_each_coulmns['report_columns_meta'] = $columns_meta;
            $report_columns[] = $t_each_coulmns;
            //filters
            foreach ($columns_meta as $col_meta){
                $filter=array('id'=>$col_meta->table_name.'.'.$col_meta->field_name,'label'=>$col_meta->name,'type'=>$col_meta->type);
                if ($col_meta->values)
                    $filter['values']=json_decode($col_meta->values);
                if ($col_meta->input)
                    $filter['input']=$col_meta->input;
                if ($col_meta->validation)
                    $filter['validation']=json_decode($col_meta->validation);
                if ($col_meta->plugin){
                    $filter['plugin']=$col_meta->plugin;
                    $filter['plugin_config']=json_decode('{"format": "yyyy-mm-dd","todayBtn": "linked","todayHighlight": "true","autoclose": "true"}');
                }

                $report_columns_meta = json_decode($report->columns_meta_id);
                if(!empty($report_columns_meta)){
                    if(in_array($col_meta->id, $report_columns_meta))
                        $filters[] = $filter;
                }
            }
        }

        $total_columns = json_decode($report->total_ids);
        $report_columns_meta_id_array = json_decode($report->columns_meta_id);
//        print_r2($filters);

        $current_filter_rules = $this->get_current_filter_rules($report->id);
        $report_view = $this->generate_query($report,$report_template, $current_filter_rules);
        $filters_total = $this->get_total_filter($filters);
        $current_filter_total = $this->get_current_filter_total($report->id);
        $total_value = $this->get_total_value($report->id, $report_view['report_view_content']);

        return view('report/report_browse', compact("user", "report",  "report_columns", "total_columns", "report_columns_meta_id_array", "report_view", "report_template", "reports_template","filters","current_filter_rules","filters_total","current_filter_total","total_value"));
    }

    function get_total_filter($filter_array){
        $return_array = array();
        foreach($filter_array as $each_filter){
            if(($each_filter['type'] == "integer") || ($each_filter['type'] == "double"))
                $return_array[] = $each_filter;
        }
        return $return_array;
    }

    function get_current_filter_total($report_id){
        $current_db_total = Database::table("report_total")->where("report_id", $report_id)->get();
        if(empty($current_db_total))
            $current_filter_total = array();
        else{
            $current_filter_total_array = array();
            foreach($current_db_total as $each_total){
                $current_filter_total_array[] = $each_total;
            }
            $current_filter_total = json_encode($current_filter_total_array);
        }
        return $current_filter_total;
    }

    function get_total_value($report_id, $report_view_content){
        $return_val = array();
        $filter_total_array = array();
        $filter_total = $this->get_current_filter_total($report_id);
        if(!empty($filter_total))
            $filter_total_array = json_decode($filter_total);

        foreach((array)$filter_total_array as $each_total_array){
            $t_return_val = array();
            $column_meta_array = json_decode($each_total_array->column_meta);
            $column_id = str_replace('.','_', $column_meta_array->column_id);

            $t_return_val['title'] = $each_total_array->title;
            $t_return_val['value'] = $this->get_tota_period_range($column_id, $report_view_content, $each_total_array->period, $each_total_array->value_range);
            $return_val[] = $t_return_val;

            if(($column_id == "invoices_price") && ($each_total_array->period == "All") && ($each_total_array->value_range == "Total")){
                $sub_total_array = $this->get_sub_total($column_id, $report_view_content, $each_total_array->title);
                foreach($sub_total_array as $sub_total){
                    $return_val[] = $sub_total;
                }
            }
        }
        return $return_val;
    }

    function get_tota_period_range($column_id, $report_view_content_array, $period, $value_range){
        $return_value = 0;
        $report_view_content = array();

        if($period == "Monthly") {
            $current_month = date("Y-m");

            foreach($report_view_content_array as $each_report_view_content){
                if($column_id == "invoices_price"){
                    if(strpos($each_report_view_content->invoices_created_at, $current_month) !== false){
                        $report_view_content[] = $each_report_view_content;
                    }
                }
            }
        }
        else{
            $report_view_content = $report_view_content_array;
        }

        if(empty($report_view_content))
            return 0;

        if($value_range == "Min"){
            $return_value = $report_view_content[0]->$column_id;
            foreach($report_view_content as $each_content){
                if($return_value > $each_content->$column_id)
                    $return_value = $each_content->$column_id;
            }
        }
        if($value_range == "Max"){
            $return_value = $report_view_content[0]->$column_id;
            foreach($report_view_content as $each_content){
                if($return_value < $each_content->$column_id)
                    $return_value = $each_content->$column_id;
            }
        }
        if($value_range == "Avg"){
            foreach($report_view_content as $each_content){
                $return_value += $each_content->$column_id;
            }
            $arr_count = count($report_view_content);
            $return_value = $return_value / $arr_count;
        }
        if($value_range == "Total"){
            foreach($report_view_content as $each_content){
                $return_value += $each_content->$column_id;
            }
        }
        return $return_value;
    }

    function get_sub_total($column_id, $report_view_content_array, $sub_title){
        $payment_mode = ["Cash", "Credit Card", "Check"];
        $return_sub_total = array();
        foreach($payment_mode as $each_payment_mode){
            $each_return_array = array();
            $each_return_array['title'] = $sub_title . " for " . $each_payment_mode;
            $each_total = 0;
            foreach($report_view_content_array as $each_content){
                if($each_content->invoices_payment_mode == $each_payment_mode){
                    $each_total += $each_content->$column_id;
                }
            }
            $each_return_array['value'] = $each_total;
            $return_sub_total[] = $each_return_array;
        }
        return $return_sub_total;
    }

    function get_current_filter_rules($report_id){
        $current_db_filter = Database::table("report_filters")->where("report_id", $report_id)->first();
        if(empty($current_db_filter))
            $current_filter_rules = "";
        else
            $current_filter_rules = $current_db_filter->filter_value;

        return $current_filter_rules;
    }

    function generate_query($report,$template, $current_filter_rules=""): array
    {
        $filter_rules_array = json_decode($current_filter_rules);
        $columns_meta_id = $report->columns_meta_id;

        $tablesArray = array();
        $columns_meta_id_array = json_decode($columns_meta_id);
        $DB=Database::table($template->join);

        /****  SELECT  ****/
        $display_filelds = array();
        $isFirst=true;
        if(!empty($columns_meta_id_array)){
            foreach($columns_meta_id_array as $each_columns_meta_id){
                $column_meta = Database::table("report_columns_meta")->find($each_columns_meta_id);
                $table=$column_meta->table_name;
                $select_fields[]="`".$table . "." . $column_meta->field_name . "` AS " . $table . "_" . $column_meta->field_name;
                $display_filelds[] = $column_meta->name;
            }
        }

        if (strpos($template->tables,'users')!== false){
            $DB->where("users`.`role","user");
            $DB->where("users`.`account_type","Real");
        }

        foreach((array)$filter_rules_array as $each_rule){
            $column_id_spilt = explode(".", $each_rule->column_id);
            $tbn = $column_id_spilt[0];
            $fldn = $column_id_spilt[1];
            $val = $each_rule->row_val;
            if($each_rule->operater == "like"){
                $val = '%' . $val . '%';
            }

            if($each_rule->operater == "Today"){
                $val = date("Y-m-d");
                $DB->where($tbn.'`.`'.$fldn, "like", '%'.$val.'%');
            }
            elseif($each_rule->operater == "Yesterday"){
                $val = date("Y-m-d",strtotime("-1 days"));
                $DB->where($tbn.'`.`'.$fldn, "like", '%'.$val.'%');
            }
            elseif($each_rule->operater == "This month"){
                $val = date("Y-m");
                $DB->where($tbn.'`.`'.$fldn, "like", '%'.$val.'%');
            }
            elseif($each_rule->operater == "Previous month"){
                $val = date('Y-m', strtotime(date('Y-m')." -1 month"));
                $DB->where($tbn.'`.`'.$fldn, "like", '%'.$val.'%');
            }
            else{
                $DB->where($tbn.'`.`'.$fldn, $each_rule->operater, $val);
            }
        }

        $report_view_content = empty($select_fields)?$select_fields:$DB->get($select_fields);

        $return_array['report_view_header'] = $display_filelds;
        $return_array['report_view_content'] = $report_view_content;
        //print_r($DB->queryStr);
        //print_r($DB->params);
        return $return_array;
    }

    function generate_query_filter($filter_rules){
        $filter_rules_array = json_decode($filter_rules);
        $query = " AND (";
        $counter = 1;
        foreach($filter_rules_array as $each_rule){
            $query .= " " . $each_rule->column_id;
            if($each_rule->operater == "like"){
                $query .= ' LIKE "%' . $each_rule->row_val . '%"';
            }
            elseif($each_rule->column_type == "string"){
                $query .= " " . $each_rule->operater .' "' . $each_rule->row_val. '"' ;
            }
            else{
                $query .= " " . $each_rule->operater .' ' . $each_rule->row_val ;
            }

            if($counter != count($filter_rules_array)){
                $query .= " AND ";
            }
            $counter++;
        }
        $query .= " ) ";
        return $query;
    }

    public function updateColumns(){
        header('Content-type: application/json');
        $columns_array = array();

        foreach (input()->post as $field) {
            if ($field->index == "csrf-token" || $field->index == "reportid") {
                continue;
            }
            $columns_array[] = $field->index;
        }
        $report_data = array(
            "columns_meta_id" => json_encode($columns_array)
        );
        Database::table("report")->where("id" , input("reportid"))->update($report_data);

        exit(json_encode(responder("success", "Columns updated!", "Report columns successfully updated.","reload()")));
    }


    public function reportFilter(){
        header('Content-type: application/json');
        $r_filter = Database::table("report_filters")->where("report_id" , input("reportid"))->first();

        if(!empty($r_filter)){
            Database::table("report_filters")->where("id", $r_filter->id)->delete();
        }

        $filter_value = array();
        for($i = 0; $i < count($_POST['column_id']) ; $i++){
            $row_val = $_POST["row_val"][$i];
            $operater = urldecode($_POST["operater"][$i]);
            if(($operater == "Today") || ($operater == "Yesterday") || ($operater == "This month") || ($operater == "Previous month"))
                $row_val = "";
            $filter_value[] = array(
                "column_id" => $_POST['column_id'][$i],
                "column_name" => $_POST['column_name'][$i],
                "column_type" => $_POST["column_type"][$i],
                "operater" => $operater,
                "row_val" => $row_val
            );
        }

        $report_filter_data = array(
            "report_id" => input("reportid"),
            "filter_value" => json_encode($filter_value)
        );
        Database::table("report_filters")->insert($report_filter_data);

        exit(json_encode(responder("success", "Report Filter Updated!", "Report filters successfully updated.","reload()")));

    }

    public function reportTotal(){
        header('Content-type: application/json');
        $r_filter = Database::table("report_total")->where("report_id" , input("reportid"))->first();

        if(!empty($r_filter)){
            Database::table("report_total")->where("report_id" , input("reportid"))->delete();
        }

        $filter_value = array();
        for($i = 0; $i < count($_POST['column_id']) ; $i++){
            $column_meta = array(
                "column_id" => $_POST['column_id'][$i],
                "column_name" => $_POST['column_name'][$i],
                "column_type" => $_POST["column_type"][$i]
            );
            $column_meta = json_encode($column_meta);
            $filter_total = array(
                "report_id" => input("reportid"),
                "column_meta" => $column_meta,
                "title" => $_POST["title"][$i],
                "period" => $_POST["period"][$i],
                "value_range" => $_POST["value_range"][$i]
            );

            Database::table("report_total")->insert($filter_total);
        }

        exit(json_encode(responder("success", "Report Total Updated!", "Report total rules successfully updated.","reload()")));

    }


    public function updateAccess(){
        header('Content-type: application/json');
        exit(json_encode(responder("success", "Columns updated!", "Report columns successfully updated.","reload()")));
    }

    public function exportExcel(){
        $rid = input("reportid");
        $report = Database::table("report")->where("id", $rid)->first();
        $report_template = Database::table("report_templates")->find($report->template_id);
        $current_filter_rules = $this->get_current_filter_rules($report->id);

        $report_view = $this->generate_query($report, $report_template, $current_filter_rules);

        $total_value = $this->get_total_value($report->id, $report_view['report_view_content']);

        try {
            $spreadsheet = new Spreadsheet();
            $spreadsheet->getProperties()
                ->setCreator('Holtz')
                ->setLastModifiedBy('Holtz')
                ->setTitle('Hiawatha Report')
                ->setSubject('Hiawatha Report List')
                ->setDescription('Hiawatha Report from Biitz.');
            $worksheet = $spreadsheet->getActiveSheet();
            $worksheet->setTitle("Report");
            //  First row
            $worksheet->setCellValueByColumnAndRow(1, 1, $report->name);
            $styleArray = [
                'font' => [
                    'bold' => true
                ]
            ];
            $worksheet->getStyle('A1:' . 'A1')->applyFromArray($styleArray);
            $spreadsheet->getActiveSheet()->mergeCells('A1:C1');

            //   Second Row
            $worksheet->setCellValueByColumnAndRow(1, 2, $report->description);
            $styleArray = [
                'font' => [
                    'bold' => true
                ]
            ];
            $worksheet->getStyle('A2:' . 'A2')->applyFromArray($styleArray);
            $spreadsheet->getActiveSheet()->mergeCells('A2:F2');
            $spreadsheet->getActiveSheet()->mergeCells('A3:F3');

            //  Third Row
            $third_row = array();
            foreach($total_value as $each_value){
                $third_row[] = $each_value['title'];
            }
            $third_row_leng = 0;
            foreach($third_row as $each_col){
                $third_row_leng++;
                $worksheet->setCellValueByColumnAndRow($third_row_leng, 4, $each_col);
            }

            //   Fourth Row
            $fourth_row = array();
            foreach($total_value as $each_value){
                $fourth_row[] = $each_value['value'];
            }
            $fourth_leng = 0;
            foreach($fourth_row as $each_col){
                $fourth_leng++;
                $worksheet->setCellValueByColumnAndRow($fourth_leng, 5, $each_col);
            }
            $spreadsheet->getActiveSheet()->mergeCells('A6:F6');

            //   Fifth Row
            $fifth_row  = $report_view["report_view_header"];
            $fifth_leng = 0;
            foreach($fifth_row as $each_col){
                $fifth_leng++;
                $worksheet->setCellValueByColumnAndRow($fifth_leng, 7, $each_col);
            }

            $report_cont = $report_view["report_view_content"];
            $j = 7;
            foreach($report_cont as $each_cont){
                $j++;
                $sixth_leng = 0;
                foreach($each_cont as $each_cell){
                    $sixth_leng++;
                    $worksheet->setCellValueByColumnAndRow($sixth_leng, $j, $each_cell);
                }
            }

            $filename = 'Report.xlsx';
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename=' . $filename);
            header('Cache-Control: max-age=0');
            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
            $writer->save('php://output');
        }
        catch(Exception $e) {
            echo '\nCaught exception: ',  $e->getMessage(), "\n";
        }
    }

    public function createQueryBuilder()
    {
        $pdo = container(PDO::class);
        $builder = new Builder(new Connection($pdo), new MySQLGrammar(), new MySQLProcessor());
        return $builder;
    }
}
