<?php
require '../../vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;

include "../../components/processes/db_connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (isset($_FILES["agencyExcel"]) && $_FILES["agencyExcel"]["error"] == UPLOAD_ERR_OK) {

    if (move_uploaded_file($_FILES["agencyExcel"]["tmp_name"], basename($_FILES["agencyExcel"]["name"]))) {

      // load excel file
      $spreadsheet = IOFactory::load($_FILES["agencyExcel"]["name"]);
      $sheet = $spreadsheet->getActiveSheet();

      // Flag to skip the first row
      $skipFirstRow = true;

      foreach ($sheet->getRowIterator() as $row) {
        // Skip the first row
        if ($skipFirstRow) {
          $skipFirstRow = false;
          continue;
        }

        $rowData = [];
        foreach ($row->getCellIterator() as $cell) {
          $rowData[] = $cell->getValue();
        }

        $saveAgency = $conn->prepare("INSERT INTO agency (agencyName, sector, lgu_type, province) VALUES (?, ?, ?, ?)");
        $saveAgency->bind_param("ssss", $rowData[0], $rowData[1], $rowData[2], $rowData[3]);

        $saveAgency->execute();
      }

      unlink($_FILES["agencyExcel"]["name"]);
    } else {
      echo "error 3";
    }
  } else {
    echo "error 2";
  }
} else {
  echo "error 1";
}