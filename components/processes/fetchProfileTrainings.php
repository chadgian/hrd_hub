<?php

include "db_connection.php";

$type = $_POST['type'];
$employeeID = $_POST['id'];

// Recent trainings only
if ($type == 0) {
  $fetchProfileTrainingsStmt = $conn->prepare("
  SELECT 
    rd.*,
    tp.*,
    td.* 
  FROM 
    registration_details rd
  INNER JOIN 
    training_participants tp 
  ON 
    rd.registrationID = tp.registrationID
  INNER JOIN 
    training_details td 
  ON 
    tp.trainingID = td.trainingID
  WHERE tp.employeeID = ? ORDER BY tp.participantID DESC LIMIT 1");

  $fetchProfileTrainingsStmt->bind_param("i", $employeeID);

  if ($fetchProfileTrainingsStmt->execute()) {
    $fetchProfileTrainingsResult = $fetchProfileTrainingsStmt->get_result();

    if ($fetchProfileTrainingsResult->num_rows > 0) {
      while ($fetchProfileTrainingsData = $fetchProfileTrainingsResult->fetch_assoc()) {
        $startDate = new DateTime($fetchProfileTrainingsData['startDate']);
        $endDate = new DateTime($fetchProfileTrainingsData['endDate']);
        $trainingDate = $startDate->format("F j") . "-" . $endDate->format("j, Y");

        $attendanceColor = $fetchProfileTrainingsData['attendance'] == 0 ? "#FF0707" : "#00A52E";
        $paymentColor = $fetchProfileTrainingsData['payment'] == 0 ? "#FF0707" : "#00A52E";

        $attendanceContent = $fetchProfileTrainingsData['attendance'] == 0 ? "Incomplete" : "Complete";
        $paymentContent = $fetchProfileTrainingsData['payment'] == 0 ? "Unpaid" : "Paid";

        if ($paymentContent == "Unpaid") {
          $orNumber = "N/A";
          $amount = "N/A";
          $discount = "N/A";
          $paymentDate = "N/A";
        } else {
          $orNumber = $fetchProfileTrainingsData['receiptNumber'] == "" ? "N/A" : $fetchProfileTrainingsData['receiptNumber'];
          $amount = $fetchProfileTrainingsData['amount'] == "" ? "N/A" : "₱" . $fetchProfileTrainingsData['amount'];
          $discount = $fetchProfileTrainingsData['discount'] == "" ? "N/A" : $fetchProfileTrainingsData['discount'] . "%";
          $rawPaymentDate = new DateTime($fetchProfileTrainingsData['paymentDate']);
          $paymentDate = $rawPaymentDate == "" ? "N/A" : $rawPaymentDate->format("F d, Y");
        }

        $attendanceRemarkRaw = $fetchProfileTrainingsData['attendanceRemarks'] == "" ? "N/A" : $fetchProfileTrainingsData['attendanceRemarks'];

        $attendanceRemark = match (explode("::", $attendanceRemarkRaw)[0]) {
          "0" => "Replaced",
          "1" => "Valid Cancellation",
          "2" => "Invalid Cancellation",
          "3" => "Lack of training hours",
          "4" => "Absent/No Show",
          "5" => "Other: " . explode("::", $attendanceRemarkRaw)[1],
          "N/A" => "N/A"
        };

        // echo json_encode($fetchProfileTrainingsData);

        echo "
        <div class='training-detail'>
          <div class='training-detail-header'>{$fetchProfileTrainingsData['trainingName']}</div>
          <div class='training-detail-content'>
            <div class='training-detail-group'>
              <div class='training-detail-group-title' style='flex: 1;'>
                Date of Training: 
              </div>
              <div class='training-detail-group-content' style='flex: 2;'>
                $trainingDate
              </div>
            </div>
            <div class='training-detail-group'>
              <div class='training-detail-group-title' style='flex: 1;'>
                Venue: 
              </div>
              <div class='training-detail-group-content' style='flex: 2;'>
                {$fetchProfileTrainingsData['venue']}
              </div>
            </div>
            <div class='training-detail-group'>
              <div class='training-detail-group-title' style='flex: 1;'>
                Mode of Training: 
              </div>
              <div class='training-detail-group-content' style='flex: 2;'>
                {$fetchProfileTrainingsData['mode']}
              </div>
            </div>
            <div class='training-detail-group'>
              <div class='training-detail-group-title' style='flex: 1;'>
                Registration Fee: 
              </div>
              <div class='training-detail-group-content' style='flex: 2;'>
                ₱ {$fetchProfileTrainingsData['fee']}.00
              </div>
            </div>
            <div class='training-detail-group'>
              <div class='training-detail-group-title' style='flex: 1;'>
                Training Hours: 
              </div>
              <div class='training-detail-group-content' style='flex: 2;'>
                {$fetchProfileTrainingsData['trainingHours']} hours
              </div>
            </div>
            <div class='training-detail-group'>
              <div class='training-detail-group-title' style='flex: 1;'>
                Curriculum Area: 
              </div>
              <div class='training-detail-group-content' style='flex: 2;'>
                {$fetchProfileTrainingsData['currArea']}
              </div>
            </div>
            <div class='training-detail-group'>
              <div class='training-detail-group-title' style='flex: 1;'>
                Description: 
              </div>
              <div class='training-detail-group-content' style='flex: 2;'>
                {$fetchProfileTrainingsData['details']}
              </div>
            </div>
            <div class='row mt-4'>
              <div class='col-md-6 payment-section'>
                <div class='payment-indicator my-3'>
                  Payment:
                  <div class='payment-toggle' style='color: $paymentColor; font-weight: bold;'>$paymentContent</div>
                </div>
                <div class='vertical-line'>
                  <div style='color: #24305E; font-weight: bold; font-size: medium;'>PAYMENT DETAILS:</div>
                  <div class='payment-details'>
                    <div class='payment-details-line'>
                      <div class='payment-details-line-title'>OR Number:</div>
                      <div class='payment-details-line-content' style='font-weight: bold; color: #CE2F2F; font-size: medium;'>$orNumber</div>
                    </div>
                  </div>
                  <div class='payment-details'>
                    <div class='payment-details-line'>
                      <div class='payment-details-line-title'>Amount Paid:</div>
                      <div class='payment-details-line-content' style='font-weight: bold;'>$amount</div>
                    </div>
                  </div>
                  <div class='payment-details'>
                    <div class='payment-details-line'>
                      <div class='payment-details-line-title'>Discount:</div>
                      <div class='payment-details-line-content' style='font-weight: bold;'>$discount</div>
                    </div>
                  </div>
                  <div class='payment-details'>
                    <div class='payment-details-line'>
                      <div class='payment-details-line-title'>Date of Payment:</div>
                      <div class='payment-details-line-content' style='font-weight: bold;'>$paymentDate</div>
                    </div>
                  </div>
                </div>
              </div>
              <div class='col-md-6 attendance-section mt-3'>
                <div class='attendance-indicator'>
                  Attendance:
                  <div class='attendance-toggle' style='color: $attendanceColor; font-weight: bold;'>$attendanceContent</div>
                </div>
                <div class='attendance-remark'>
                  <div class='attendance-remark-title'>Attendance Remark:</div>
                  <div class='attendance-remark-content'>$attendanceRemark</div>
                </div>
              </div>
            </div>
          </div>
        </div>
        ";


      }
    } else {
      echo "No participant found";
    }
  } else {
    echo $fetchProfileTrainingsStmt->error;
  }
} else {
  $fetchProfileTrainingsStmt = $conn->prepare("
  SELECT 
    rd.*,
    tp.*,
    td.* 
  FROM 
    registration_details rd
  INNER JOIN 
    training_participants tp 
  ON 
    rd.registrationID = tp.registrationID
  INNER JOIN 
    training_details td 
  ON 
    tp.trainingID = td.trainingID
  WHERE tp.employeeID = ? ORDER BY tp.participantID DESC");

  $fetchProfileTrainingsStmt->bind_param("i", $employeeID);

  if ($fetchProfileTrainingsStmt->execute()) {
    $fetchProfileTrainingsResult = $fetchProfileTrainingsStmt->get_result();

    if ($fetchProfileTrainingsResult->num_rows > 0) {
      while ($fetchProfileTrainingsData = $fetchProfileTrainingsResult->fetch_assoc()) {
        $trainings[] = $fetchProfileTrainingsData;
        // echo json_encode($fetchProfileTrainingsData);

        $attendanceColor = $fetchProfileTrainingsData['attendance'] == 0 ? "#FF0707" : "#00A52E";
        $paymentColor = $fetchProfileTrainingsData['payment'] == 0 ? "#FF0707" : "#00A52E";
        $startDate = new DateTime($fetchProfileTrainingsData['startDate']);
        $endDate = new DateTime($fetchProfileTrainingsData['endDate']);
        $trainingDate = $startDate->format("F j") . "-" . $endDate->format("j, Y");
        $registeredDate = new DateTime($fetchProfileTrainingsData['timeDate']);

        $participantID = $fetchProfileTrainingsData['participantID'];

        echo "
          <div class='training-detail'>
            <div class='training-detail-header'>{$fetchProfileTrainingsData['trainingName']}</div>
            <div class='training-detail-content'>
              <div class='d-flex justify-content-between'>
                <div
                  style='border-bottom: 2px solid #24305E; width: max-content; font-size: small; padding-bottom: 5px;'>
                  <b style='font-size: medium;'>$trainingDate</b>
                </div>
                <div class='profile-training-indicator' style='color: $attendanceColor; font-weight: bold;'>
                  Attendance
                  <svg xmlns='http://www.w3.org/2000/svg' width='14' height='14' fill='currentColor'
                    class='bi bi-calendar-check-fill' viewBox='0 0 16 16'>
                    <path
                      d='M4 .5a.5.5 0 0 0-1 0V1H2a2 2 0 0 0-2 2v1h16V3a2 2 0 0 0-2-2h-1V.5a.5.5 0 0 0-1 0V1H4zM16 14V5H0v9a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2m-5.146-5.146-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 0 1 .708-.708L7.5 10.793l2.646-2.647a.5.5 0 0 1 .708.708' />
                  </svg>
                </div>
              </div>
              <div class='d-flex justify-content-between'>
                <div><b>{$fetchProfileTrainingsData['trainingHours']}</b> training hours</div>
                <div class='profile-training-indicator' style='color: $paymentColor; font-weight: bold;'>
                  Payment
                  <svg xmlns='http://www.w3.org/2000/svg' width='14' height='14' fill='currentColor'
                    class='bi bi-currency-exchange' viewBox='0 0 16 16'>
                    <path
                      d='M0 5a5 5 0 0 0 4.027 4.905 6.5 6.5 0 0 1 .544-2.073C3.695 7.536 3.132 6.864 3 5.91h-.5v-.426h.466V5.05q-.001-.07.004-.135H2.5v-.427h.511C3.236 3.24 4.213 2.5 5.681 2.5c.316 0 .59.031.819.085v.733a3.5 3.5 0 0 0-.815-.082c-.919 0-1.538.466-1.734 1.252h1.917v.427h-1.98q-.004.07-.003.147v.422h1.983v.427H3.93c.118.602.468 1.03 1.005 1.229a6.5 6.5 0 0 1 4.97-3.113A5.002 5.002 0 0 0 0 5m16 5.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0m-7.75 1.322c.069.835.746 1.485 1.964 1.562V14h.54v-.62c1.259-.086 1.996-.74 1.996-1.69 0-.865-.563-1.31-1.57-1.54l-.426-.1V8.374c.54.06.884.347.966.745h.948c-.07-.804-.779-1.433-1.914-1.502V7h-.54v.629c-1.076.103-1.808.732-1.808 1.622 0 .787.544 1.288 1.45 1.493l.358.085v1.78c-.554-.08-.92-.376-1.003-.787zm1.96-1.895c-.532-.12-.82-.364-.82-.732 0-.41.311-.719.824-.809v1.54h-.005zm.622 1.044c.645.145.943.38.943.796 0 .474-.37.8-1.02.86v-1.674z' />
                  </svg>
                </div>
              </div>
              <div><b>Venue:</b> {$fetchProfileTrainingsData['venue']}</div>
              <div class='d-flex justify-content-between'>
                <div>Registration Date: <b>{$registeredDate->format("F d, Y | H:m:s")}</b></div>
                <div class='viewTrainingHistory' data-bs-toggle='modal' data-bs-target='#trainingHistory' onclick='updateViewTrainingHistoryModal($participantID)'>
                  View
                </div>
              </div>
            </div>
          </div>
        ";
      }
    } else {
      echo "No participant found";
    }
  } else {
    echo $fetchProfileTrainingsStmt->error;
  }
}