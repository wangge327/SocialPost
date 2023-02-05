<?php

namespace Simcify\Controllers;

use Exception;
use Simcify\Models\BalanceModel;
use Simcify\Str;
use Simcify\File;
use Simcify\Mail;
use Simcify\Auth;
use Simcify\Database;
use Simcify\Signer;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use Simcify\Models\StudentModel;
use PDO;

class Member
{
    public function takePicture($user_id)
    {
        $select_user = Database::table("users")->find($user_id);
        $user = Auth::user();
        $user->page_title = '拍照片';
        $student = $select_user;
        return view('customer/take_picture', compact("user", "select_user", "student"));
    }

    public function takePictureID($user_id)
    {
        $select_user = Database::table("users")->find($user_id);
        $user = Auth::user();
        $user->page_title = 'View ID | take picture for ID';
        $student = $select_user;
        $passports = Database::table('passports')->where("user_id", $user_id)->first();
        return view('customer/take_picture_id', compact("user", "select_user", "student", "passports"));
    }



    public function updateIDPassport()
    {
        if (isset($_FILES['avatar'])) {
            $errors = array();
            $file_name = $_FILES['avatar']['name'];
            $file_size = $_FILES['avatar']['size'];
            $file_tmp = $_FILES['avatar']['tmp_name'];
            $file_type = $_FILES['avatar']['type'];
            $file_ext = strtolower(end(explode('.', $_FILES['avatar']['name'])));

            $extensions = array("jpeg", "jpg", "png");

            if (in_array($file_ext, $extensions) === false) {
                $errors[] = "extension not allowed, please choose a JPEG or PNG file.";
            }

            if ($file_size > 2097152) {
                $errors[] = 'File size must be excately 2 MB';
            }
            $directory = getcwd() . '/uploads/avatar/';
            $filename = uniqid() . "." . $file_ext;
            try {
                if (empty($errors) == true) {
                    move_uploaded_file($file_tmp, $directory . $filename);

                    if (isset($_POST["user_id"])) {
                        $passports = Database::table('passports')->where("user_id", $_POST["user_id"])->first();
                    } else {
                        $passports = Database::table('passports')->where("user_id", Auth::user()->id)->first();
                    }


                    if (empty($passports)) {
                        $insert_array = array(
                            "user_id" => $_POST["user_id"],
                            "path0" => "",
                            "path1" => "",
                            "type" => $_POST["document_type"]
                        );
                        if ($_POST["front_back"] == "front")
                            $insert_array['path0'] = $filename;
                        else
                            $insert_array['path1'] = $filename;

                        Database::table("passports")->insert($insert_array);
                    } else {
                        $update_array = array();
                        if ($_POST["front_back"] == "front")
                            $update_array['path0'] = $filename;
                        else
                            $update_array['path1'] = $filename;

                        $update_array['type'] = $_POST["document_type"];
                        if (isset($_POST["user_id"])) {
                            Database::table('passports')->where("user_id", $_POST["user_id"])->update($update_array);
                        } else {
                            Database::table('passports')->where("user_id", Auth::user()->id)->update($update_array);
                        }
                    }
                    echo "Success " . $filename;
                } else {
                    print_r($errors);
                }
            } catch (Exception $e) {
                print_r($e->getMessage());
            }
        }
    }
    public function updateDashboardUser()
    {
        foreach (input()->post as $field) {

            if ($field->index == "csrf-token" || $field->index == "customerid") {
                continue;
            }
            StudentModel::Update(input("customerid"), array($field->index => escape($field->value)));
        }
        header('Content-type: application/json');
        exit(json_encode(responder("success", "好吧", "个人信息成功更新", "reload()")));
    }

    public function saveNote()
    {
        $d = array('extra_note' => input('extra_note'));
        if (input('internal_note', null) != null)
            $d['internal_note'] = input('internal_note');
        StudentModel::Update(input("user_id"), $d);
        header('Content-type: application/json');
        exit(json_encode(responder("success", "好吧.", "杂记成功更新", "reload()")));
    }

    public function get_errors()
    {
        $customers = Database::table("users_errors")->get();
        $customersData = array();
        foreach ($customers as $customer) {
            $customersData[] = array(
                "user" => $customer
            );
        }
        $user = Auth::user();
        $customers = $customersData;
        return view('user_errors', compact("user", "customers"));
    }

    public function Excel()
    {
        try {
            if (input('action') == 'export') {
                $spreadsheet = new Spreadsheet();
                $spreadsheet->getProperties()
                    ->setCreator('Holtz')
                    ->setLastModifiedBy('Holtz')
                    ->setTitle('Hiawatha Students')
                    ->setSubject('Hiawatha Student List')
                    ->setDescription('Hiawatha Students from Biitz.');
                $worksheet = $spreadsheet->getActiveSheet();
                $worksheet->setTitle("Students");
                $field_len = count(StudentModel::$fields) + 1;
                $end_alpha = chr(63 + $field_len);
                $id = 0;
                foreach (StudentModel::$fields as $key => $value) {
                    $id++;
                    $worksheet->setCellValueByColumnAndRow($id, 1, $value['name']);
                    $worksheet->getColumnDimensionByColumn($id)->setWidth(isset($value['width']) ? $value['width'] : 12);
                }

                // Set the first line style
                //
                $styleArray = [
                    'font' => [
                        'bold' => true
                    ],
                    //                    'alignment' => [
                    //                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    //                    ],
                ];
                $worksheet->getStyle('A1:' . $end_alpha . '1')->applyFromArray($styleArray); //->getFont()->setSize(14)

                $students = Database::table("users")->where("role", "user")->get();
                $len = count($students);
                for ($i = 0; $i < $len; $i++) {
                    $j = $i + 2;
                    $id = 0;
                    $array = json_decode(json_encode($students[$i]), true);
                    foreach (StudentModel::$fields as $key => $value) {
                        $id++;
                        $worksheet->setCellValueByColumnAndRow($id, $j, $array[$key]);
                        if (isset($value['formula'])) {
                            $validation = $worksheet->getCellByColumnAndRow($id, $j)->getDataValidation();
                            $validation->setType(DataValidation::TYPE_LIST)
                                ->setErrorStyle(DataValidation::STYLE_INFORMATION)
                                ->setAllowBlank(false)
                                ->setShowInputMessage(true)
                                ->setShowErrorMessage(true)
                                ->setShowDropDown(true)
                                ->setErrorTitle('Input error')
                                ->setError($value['name'] . ' is not in the list.')
                                ->setPromptTitle('Pick from the list')
                                ->setPrompt('Please pick a ' . $value['name'] . ' from the drop-down list.')
                                ->setFormula1($value['formula']);
                        }
                    }
                }
                // Set the data table style
                //            $styleArrayBody = [
                //                'borders' => [
                //                    'allBorders' => [
                //                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                //                        'color' => ['argb' => '666666'],
                //                    ],
                //                ],
                //                'alignment' => [
                //                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                //                ],
                //            ];
                //
                //            $total_rows = $len + 1;
                //            //Add all borders/centered
                //            $worksheet->getStyle('A1:' . $end_alpha . $total_rows)->applyFromArray($styleArrayBody);

                $filename = 'students.xlsx';
                header('Content-Type: application/vnd.ms-excel');
                header('Content-Disposition: attachment;filename=' . $filename);
                header('Cache-Control: max-age=0');
                $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
                $writer->save('php://output');
            } else {
                header('Content-type: application/json');

                $allowedFileType = [
                    'application/vnd.ms-excel',
                    'text/xlsx',
                    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                ];
                //                if (in_array($_FILES["file"]["type"], $allowedFileType)) {
                $targetPath = getcwd() . '/uploads/excel/' . $_FILES['file']['name'];
                move_uploaded_file($_FILES['file']['tmp_name'], $targetPath);
                //                    $Reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
                $Reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Xlsx');
                $spreadSheet = $Reader->load($targetPath);
                $excelSheet = $spreadSheet->getActiveSheet();
                $spreadSheetAry = $excelSheet->toArray();
                $sheetCount = count($spreadSheetAry);
                $resMessage = "Student Data was added successfully.";
                for ($i = 1; $i < $sheetCount; $i++) {
                    $col = -1;
                    $studentData = array();
                    foreach (StudentModel::$fields as $key => $value) {
                        $col++;
                        $studentData[$key] = $spreadSheetAry[$i][$col];
                    }
                    //                        print_r($studentData);
                    $res = Database::table("users")->insert($studentData);
                    if (!$res) {
                        $resMessage = "The excel has some errors. Please check 'Import Errors'!";
                        $studentData['error_message'] = Database::$error[2];
                        $res = Database::table("users_errors")->insert($studentData);
                    }
                }
                exit(json_encode(responder("success", "Import!", $resMessage, "reload()")));
                //                } else {
                //                    exit(json_encode(responder("error", "Import!", "Please Upload Excel File.")));
                //                }

                //            $highestRow = $worksheet->getHighestRow(); // total number of rows
                //            $lines = $highestRow - 2;
                //            if ($lines <= 0) {
                //                Exit ('There is no data in the Excel table');
                //            }
                //            for ($row = 3; $row <= $highestRow; ++$row) {
                //                $name = $worksheet->getCellByColumnAndRow(1, $row)->getValue(); //Name
                //                $chinese = $worksheet->getCellByColumnAndRow(2, $row)->getValue(); //Language
                //            }

            }
        } catch (Exception $e) {
            echo '\nCaught exception: ',  $e->getMessage(), "\n";
        }
    }

    public function importWithUnit()
    {
        try {
            header('Content-type: application/json');
            Database::table("users")->where("email", "like", "student%@hiawatha.ws")->delete();


            $targetPath = getcwd() . '/uploads/excel/' . $_FILES['file']['name'];
            move_uploaded_file($_FILES['file']['tmp_name'], $targetPath);

            $Reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Xlsx');
            $spreadSheet = $Reader->load($targetPath);
            $excelSheet = $spreadSheet->getActiveSheet();
            $spreadSheetAry = $excelSheet->toArray();
            $sheetCount = count($spreadSheetAry);

            for ($i = 1; $i < $sheetCount; $i++) {
                $studentData = array();
                for ($j = 0; $j < count($spreadSheetAry[$i]); $j++) {
                    if ($spreadSheetAry[0][$j] == "First Name")
                        $studentData["fname"] = $spreadSheetAry[$i][$j];
                    if ($spreadSheetAry[0][$j] == "Last Name")
                        $studentData["lname"] = $spreadSheetAry[$i][$j];
                    if ($spreadSheetAry[0][$j] == "Unit")
                        $studentData["unit"] = $spreadSheetAry[$i][$j];
                    if ($spreadSheetAry[0][$j] == "Start Date")
                        $studentData["lease_start"] = date("Y-m-d", strtotime($spreadSheetAry[$i][$j]));
                    if ($spreadSheetAry[0][$j] == "End Date")
                        $studentData["lease_end"] = date("Y-m-d", strtotime($spreadSheetAry[$i][$j]));
                    if ($spreadSheetAry[0][$j] == "Balance")
                        $studentData["balance"] = floatval(str_replace("$", "", $spreadSheetAry[$i][$j]));
                    if ($spreadSheetAry[0][$j] == "Security Deposit")
                        $studentData["security_deposit"] = $spreadSheetAry[$i][$j];
                    $studentData["email"] = "student" . $i . "@hiawatha.ws";
                }

                $res = Database::table("users")->insert($studentData);

                if (!$res) {
                    $resMessage = "The excel has some errors. Please check 'Import Errors'!";
                    $studentData['error_message'] = Database::$error[2];
                    $res = Database::table("users_errors")->insert($studentData);
                }
            }

            Database::table("beds")->update(array('student_id' => 0, 'status' => "Vacant"));

            $student =  Database::table("users")->where("role", "user")->get();
            foreach ($student as $each_student) {
                $room_data = array();
                $bed_data = array();
                $t_unit = explode("-", $each_student->unit);
                $room = Database::table("rooms")->where("name", $t_unit[0])->first();
                $bed = Database::table("beds")->where("name", $t_unit[1])->where("room_id", $room->id)->first();
                if (empty($room)) {
                    $room_data["building_id"] = $building_id = intval($t_unit[0] / 1000);
                    $room_data["name"] = $t_unit[0];
                    Database::table("rooms")->insert($studentData);
                }
                if (empty($bed)) {
                    $temp_room = Database::table("rooms")->where("name", $t_unit[0])->first();
                    $bed_data["name"] = $t_unit[1];
                    $bed_data["room_id"] = $temp_room->id;
                    Database::table("beds")->insert($bed_data);
                }

                $room = Database::table("rooms")->where("name", $t_unit[0])->first();
                $bed = Database::table("beds")->where("name", $t_unit[1])->where("room_id", $room->id)->first();
                $room_name = $room->name . '-' . $bed->name;
                StudentModel::Update($each_student->id, array('bed_id' => $bed->id, 'room_id' => $room->id, 'unit' => $room_name));
                Database::table("beds")->where("id", $bed->id)->update(array('student_id' => $each_student->id, 'status' => "Occupied"));
            }

            exit(json_encode(responder("success", "Import!", "Student Data was added With Unit successfully.", "reload()")));
        } catch (Exception $e) {
            echo '\nCaught exception: ',  $e->getMessage(), "\n";
        }
    }

    public function ImportAll()
    {
        $students = Database::table("users_errors")->get();
        $cn = 0;
        foreach ($students as $student) {
            unset($student->error_message);
            $array = json_decode(json_encode($student), true);
            unset($array['id']);
            $res = Database::table("users")->insert($array);
            if (!$res) {
                Database::table("users_errors")->where("id", $student->id)->update('error_message', Database::$error[2]);
            } else {
                $cn++;
                Database::table("users_errors")->where("id", $student->id)->delete();
            }
        }
        $errCn = count($students) - $cn;
        header('Content-type: application/json');
        exit(json_encode(responder("success", "Import Result", $cn . " Student data were imported. " . $errCn . " data have still errors", "reload()")));
    }
}
