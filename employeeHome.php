<?php
include 'components/functions/checkLogin.php';
checkLogin();
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}

$username = $_SESSION['username'];
$prefix = $_SESSION['prefix'];
$firstName = $_SESSION['firstName'];
$middleInitial = $_SESSION['middleInitial'];
$lastName = $_SESSION['lastName'];
$suffix = $_SESSION['suffix'];
$agency = $_SESSION['agency'];
$position = $_SESSION['position'];
$userID = $_SESSION['userID'];
$username = $_SESSION['username'];

include 'components/processes/db_connection.php';

$getEmployeeDetailStmt = $conn->prepare("SELECT * FROM employee as e INNER JOIN agency as a ON e.agency = a.agencyID WHERE userID = ?");
$getEmployeeDetailStmt->bind_param("i", $userID);

if ($getEmployeeDetailStmt->execute()) {
  $getEmployeeDetailResult = $getEmployeeDetailStmt->get_result();

  if ($getEmployeeDetailResult->num_rows > 0) {
    while ($getEmployeeDetailData = $getEmployeeDetailResult->fetch_assoc()) {

      $employeeID = $getEmployeeDetailData['employeeID'];

      // personal information
      $prefixDetail = $getEmployeeDetailData['prefix'];
      $firstNameDetail = $getEmployeeDetailData['firstName'];
      $middleInitialDetail = $getEmployeeDetailData['middleInitial'];
      $lastNameDetail = $getEmployeeDetailData['lastName'];
      $suffixDetail = $getEmployeeDetailData['suffix'];
      $nicknameDetail = $getEmployeeDetailData['nickname'];
      $ageDetail = $getEmployeeDetailData['age'];
      $sexDetail = $getEmployeeDetailData['sex'];
      $civilStatusDetail = $getEmployeeDetailData['civilStatus'];

      // contact information
      $phoneNumberDetail = $getEmployeeDetailData['phoneNumber'];
      $emailDetail = $getEmployeeDetailData['email'];
      $altEmailDetail = $getEmployeeDetailData['altEmail'];

      //agency information

      $sectorDetail = $getEmployeeDetailData['sector'];

      $agencyDetail = $getEmployeeDetailData['agencyName'];
      $positionDetail = $getEmployeeDetailData['position'];
      $foDetail = $getEmployeeDetailData['province'];

      //food restriction
      $foodRestrictionDetail = $getEmployeeDetailData['foodRestriction'];
    }
  } else {
    echo "No employee record found";
  }
} else {
  echo $getEmployeeDetailStmt->error;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>CSC-RO6 L&D Hub</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="assets/img/icon/favicon.ico" rel="icon">
  <link href="assets/img/icon/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link
    href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
    rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/animate.css/animate.min.css" rel="stylesheet">
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">
  <!-- <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet"> -->
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">
  <link href="https://cdn.datatables.net/1.11.3/css/dataTables.bootstrap4.min.css" rel="stylesheet">
  <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="assets/css/style.css" rel="stylesheet">

  <!-- =======================================================
  * Template Name: Selecao
  * Template URL: https://bootstrapmade.com/selecao-bootstrap-template/
  * Updated: Mar 17 2024 with Bootstrap v5.3.3
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<body>

  <!-- ======= Header ======= -->
  <header id="header" class="fixed-top d-flex align-items-center  header-transparent ">
    <div class="container d-flex align-items-center justify-content-between">

      <div class="logo">
        <!-- <h1><a href="/hrd_hub"><img src="assets/img/logo.png" alt="" width="100%" height="100%"></a></h1> -->
        <!-- Uncomment below if you prefer to use an image logo -->
        <a href="/hrd_hub"><img src="assets/img/logo.png" alt="" class="img-fluid"></a>
      </div>

      <nav id="navbar" class="navbar">
        <ul>
          <li><a class="nav-link scrollto active" href="#hero">Home</a></li>
          <li><a class="nav-link scrollto" href="#guidelines">Guidelines</a></li>
          <li><a class="nav-link scrollto" href="#trainings">Trainings</a></li>
          <li><a class="nav-link scrollto" href="#policies">Training Policies</a></li>
          <li><a class="nav-link scrollto" href="#faq">FAQs</a></li>
          <li><a class="nav-link scrollto" href="#team">About</a></li>
          <li><a class="nav-link scrollto " href="#contact">Contact Us</a></li>
          <li><a class="nav-link scrollto " href="profile.php">Profile</a></li>
        </ul>
        <i class="bi bi-list mobile-nav-toggle"></i>
      </nav><!-- .navbar -->

    </div>
  </header><!-- End Header -->

  <!-- ======= Hero Section ======= -->
  <section id="hero" class="d-flex flex-column justify-content-end align-items-center">

    <div id="heroCarousel" data-bs-interval="5000" class="container carousel carousel-fade" data-bs-ride="carousel">

      <!-- Slide 1 -->
      <div class="carousel-item active">
        <div class="carousel-container">
          <h2 class="animate__animated animate__fadeInDown">Welcome to <span>CSC RO VI</span></h2>
          <p class="animate__animated fanimate__adeInUp">Do you wish to work on your skill set this year?<br>
            Do you seek to boost your career opportunities through professional growth?<br>
            Do you intend to develop yourself and meet the best version of you as a public servant this year?<br>
            <br>
            We are ready to help!
          </p>
          <a href="#trainings" class="btn-get-started animate__animated animate__fadeInUp scrollto">View List of
            Trainings</a>
        </div>
      </div>

    </div>

    <svg class="hero-waves" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
      viewBox="0 24 150 28 " preserveAspectRatio="none">
      <defs>
        <path id="wave-path" d="M-160 44c30 0 58-18 88-18s 58 18 88 18 58-18 88-18 58 18 88 18 v44h-352z">
      </defs>
      <g class="wave1">
        <use xlink:href="#wave-path" x="50" y="3" fill="rgba(255,255,255, .1)">
      </g>
      <g class="wave2">
        <use xlink:href="#wave-path" x="50" y="0" fill="rgba(255,255,255, .2)">
      </g>
      <g class="wave3">
        <use xlink:href="#wave-path" x="50" y="9" fill="#fff">
      </g>
    </svg>

  </section><!-- End Hero -->

  <main id="main">

    <!-- ======= Guidelines Section ======= -->
    <section id="guidelines" class="guidelines">
      <div class="container">

        <div class="section-title" data-aos="zoom-out">
          <h2>Guidelines</h2>
          <p>How to register?</p>
        </div>
        <p data-aos="fade-up">
          Here's how to join our training programs:
        </p>
        <div class="row content" data-aos="fade-up">
          <div class="col-lg-6">
            <ul>
              <li><i class="ri-check-double-line"></i>Check the list of our Course Offerings per quarter and identify
                the HR Intervention you need.</li>
              <li><i class="ri-check-double-line"></i>Download and accomplish the <u>Confirmation Slip</u> and have it
                signed by your Office/Agency Head.
                <br><small>(It is highly advised to Download the provided Confirmation Slip. CSC RO VI will not grant
                  any request for access of the Confirmation Slip.)</small>
              </li>
              <li><i class="ri-check-double-line"></i><u>Click the "Register"</u> located after every training row and
                encode all required (*) information.</li>
              <li><i class="ri-check-double-line"></i>Upload your signed Confirmation Slip upon filling-out the Online
                Confirmation Form.</li>
            </ul>
          </div>
          <div class="col-lg-6 pt-4 pt-lg-0 mt-0">
            <ul>
              <li><i class="ri-check-double-line"></i>Upon successful confirmation, you will be furnished a copy of your
                submitted confirmation slip through the email you provided. </li>
              <li><i class="ri-check-double-line"></i>Check out the Advisory for further instructions on payment of
                registration fee and the specific venue. The Advisory will be released as soon as the target number of
                participants has been reached. </li>
              <li><i class="ri-check-double-line"></i>No walk-in participants shall be accepted.</li>
            </ul>
            <p>
              Download the confirmation slip here.
            </p>
            <a href="https://docs.google.com/document/d/1zpErKPHplS372J2YuByOVdzByVrTOUFO/edit?usp=sharing&ouid=105828518073961897200&rtpof=true&sd=true"
              target="_blank" class="btn-learn-more">Confirmation Slip</a>
          </div>
        </div>

      </div>
    </section><!-- End Guidelines Section -->

    <!-- ======= Notices Section ======= -->
    <section id="notices" class="notices">
      <div class="notices-body " data-aos="zoom-out">
        <h2>Important Notices</h2>
        <?php
        include 'components/processes/db_connection.php';

        $noticeStmt = $conn->prepare("SELECT * FROM notices ORDER BY noticeID ASC");

        if ($noticeStmt->execute()) {
          $noticeResult = $noticeStmt->get_result();
          while ($noticeData = $noticeResult->fetch_assoc()) {
            $noticeTitle = $noticeData['noticeTitle'];
            $noticeBody = $noticeData['noticeBody'];

            echo "
            <div class='notices-content' data-aos='fade-up'>
              <div class='notices-title'>
                $noticeTitle
              </div>
              <div class='notices-item'>
                $noticeBody
              </div>
            </div>
            ";
          }
        }

        ?>
        <!-- <div class="notices-content" data-aos="fade-up">
          <div class="notices-title">
            
          </div>
          <div class="notices-item">
            Lorem ipsum dolor sit amet consectetur adipisicing elit. Sequi, fuga! Aliquam, ipsa recusandae nihil mollitia incidunt, officia velit iste fugiat architecto qui perspiciatis eveniet. Soluta nesciunt laborum modi rem est!
          </div>
        </div>
        <div class="notices-content" data-aos="fade-up" >
          <div class="notices-title">
            Important Notice #1
          </div>
          <div class="notices-item">
            Lorem ipsum dolor sit amet consectetur adipisicing elit. Sequi, fuga! Aliquam, ipsa recusandae nihil mollitia incidunt, officia velit iste fugiat architecto qui perspiciatis eveniet. Soluta nesciunt laborum modi rem est! Lorem ipsum dolor, sit amet consectetur adipisicing elit. Culpa enim dolore quos nostrum sunt rem. Recusandae, distinctio amet facere praesentium non, sint id vitae dolore fugit nostrum at quos reprehenderit!
          </div>
        </div> -->
      </div>
    </section>

    <!-- ======= Trainings Section ======== -->

    <!-- ==== Scripts ==== -->

    <section id="trainings" class="trainings">
      <div class="container mt-5" data-aos="zoom-out">
        <div class="section-title" data-aos="zoom-out">
          <h2>Lists of trainings</h2>
          <p>CSC RO VI TRAININGS</p>
        </div>
        <div class="trainingTableContainer">
          <table id="trainingTable" class="table table-striped table-bordered" style="width:100%">
            <thead>
              <tr>
                <th>Name of Training</th>
                <th>Date of Training</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody id="training-body">
              <!-- <tr>
                <td>1</td>
                <td>Mastering Appointments Processing</td>
                <td>Feb. 21-23, 2024</td>
                <td>Iloilo City</td>
                <td>Face to Face</td>
                <td>₱6,000</td>
                <td>24 hrs.</td>
                <td>45</td>
                <td class="register-body"><span class="register-btn" data-bs-toggle="modal" data-bs-target="#staticBackdrop">Register</span></td>
              </tr> -->
            </tbody>
          </table>
        </div>
      </div>
    </section>
    <!-- ======= Policies Section ======= -->
    <section id="policies" class="policies">
      <div class="container">

        <div class="section-title" data-aos="zoom-out">
          <h2>Policies</h2>
          <p>Training Policies</p>
        </div>

        <div class="accordion" id="accordionPanelsStayOpenExample">
          <div class="accordion-item">
            <h2 class="accordion-header">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                data-bs-target="#panelsStayOpen-collapseOne" aria-expanded="false"
                aria-controls="panelsStayOpen-collapseOne">
                <span>TRAINING INVITATION AND CONFIRMATION OF ATTENDANCE</span> <i class="bi bi-chevron-down arrow"></i>
              </button>
            </h2>
            <div id="panelsStayOpen-collapseOne" class="accordion-collapse collapse">
              <div class="accordion-body">
                <ul>
                  <li>Special training courses shall be announced separately. </li>
                  <li>Prospective participants shall be duly nominated by the agency head. To confirm their attendance
                    to the training, a properly accomplished Confirmation Slip must be uploaded on the L&D Online
                    Confirmation Form at least <strong> a week before the schedule of training or when there are slots
                      available . </strong></li>
                  <li><strong>Submission of Confirmations shall only be made by accomplishing the Online Confirmation
                      Form. </strong></li>
                  <li>Acceptance of participants shall be on a <strong><i><u>“first to confirm-first-served
                          basis.”</u></i></strong></li>
                  <li>When the desired number of participants to a particular training program is reached, the CSC RO
                    VI—HRD shall release an Advisory containing the list of accepted participants.</li>
                  <li>Participants listed in the Advisory are enjoined to pay the corresponding registration fee at the
                    nearest CSC Office and email/fax a copy of their Official Receipt to CSC RO VI-HRD before or during
                    the scheduled training.</li>
                  <li>No walk-in participants shall be accepted.</li>
                  <li>The amount corresponding to <strong> 50% of registration fee for each participant </strong> shall
                    be charged against the Agency with confirmed participant/s who fail/s to attend the training without
                    informing the CSC RO VI - HRD at least three (3) working days prior to the start of the training to
                    cover the expenses such as venue/catering reservations which the Office must pay based on the number
                    of participants stated in the Contract. </li>
                  <li>For Online Courses (MOLD Series), participants are also advised to register online and pay the
                    registration fee through our Field Offices or the Regional Office.</li>
                </ul>
              </div>
            </div>
          </div>
          <div class="accordion-item">
            <h2 class="accordion-header">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                data-bs-target="#panelsStayOpen-collapseTwo" aria-expanded="false"
                aria-controls="panelsStayOpen-collapseTwo">
                CONDUCT OF TRAINING <i class="bi bi-chevron-down arrow"></i>
              </button>
            </h2>
            <div id="panelsStayOpen-collapseTwo" class="accordion-collapse collapse">
              <div class="accordion-body">
                <ul>
                  <li>Participants are required to log their attendance (morning and afternoon) in the Attendance Sheet.
                  </li>
                  <li>Complete attendance, observance of training rules, submission of outputs and active participation
                    shall be required from the participants during the training.</li>
                  <li>Each participant shall be required to submit a Learning Action Plan (LAP) at the end of the
                    training. The same shall be monitored to ensure Return of Investment to the Agency.</li>
                  <li>A Training Certificate will be awarded to the participant upon <strong> completion of at least 90%
                      of the total training hours </strong> and meeting all training requirements such as submission of
                    required outputs, LAP and payment of registration fees.</li>
                  <li>In cases where a participant fails to meet the required attendance of at least 90% of the total
                    training hours, <strong><u><i> only a Certificate of Appearance covering the training hours
                          corresponding to the sessions attended </i></u></strong> shall be issued.</li>
                  <li>The required minimum number of confirmed participants for face-to-face training program is twenty
                    (20). No training program shall be conducted unless the required minimum number of participants is
                    accepted by the CSC RO VI-HRD.</li>
                  <li>A Training Advisory shall be released by the CSC RO VI-HRD should there be changes in the
                    schedule/ venue/ design or cancellation of a training/seminar. </li>
                </ul>
              </div>
            </div>
          </div>
          <div class="accordion-item">
            <h2 class="accordion-header">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                data-bs-target="#panelsStayOpen-collapseThree" aria-expanded="false"
                aria-controls="panelsStayOpen-collapseThree">
                REQUEST FOR CONDUCT OF TRAINING PROGRAMS <i class="bi bi-chevron-down arrow"></i>
              </button>
            </h2>
            <div id="panelsStayOpen-collapseThree" class="accordion-collapse collapse">
              <div class="accordion-body">
                <p>Requests of agencies for conduct of L&D interventions exclusively for their personnel may be granted
                  by the CSC RO VI subject to the following conditions:</p>
                <ul>
                  <li>The program or course being requested is among the identified CSC RO VI programs available for
                    agency request, as follows:
                    <ol>
                      <li>Supervisory Development Course (Tracks 1, 2 & 3)</li>
                      <li>Seminar-Workshop on the Improvement of Frontline Transactions</li>
                      <li>Basic Customer Service Skills Training</li>
                      <li>Values Orientation Workshop</li>
                      <li>Public Service Ethics and Accountability</li>
                      <li>Team Building Seminar</li>
                      <li>Seminar on Gender and Development</li>
                    </ol>
                  </li>
                  <li>Learning and Development programs or courses requested by agencies which are not among those
                    listed above shall be subject to evaluation by the HRD. Such requests must be accompanied by a
                    training design approved by the Head of Agency; </li>
                  <li>There are facilitators and training administrators available to conduct the program/course on the
                    specified schedule; and</li>
                  <li>A Memorandum of Agreement shall be forged between the CSC RO VI and the requesting agency
                    detailing the responsibilities of each party in the conduct of the training. Click the link below
                    for the detailed responsibilities of both parties to be included in the MOA:
                    <ol>
                      <li><strong><i><a href="#">Responsibilities of CSC RO VI</a></i></strong></li>
                      <li><strong><i><a href="#">Responsibilities of the Requesting Agency</a></i></strong></li>
                    </ol>
                  </li>
                  <li>Regular training programs may be requested by agencies to be conducted exclusively for their
                    personnel, subject to the following conditions:
                    <ol>
                      <li>Availability of Subject Matter Experts (SME)/facilitators and training administrators;</li>
                      <li>Adherence to the existing approved training design and standards;</li>
                      <li>The requesting agency shall guarantee attendance of the required number of participants;</li>
                      <li>Payment of registration fees per participant. Registration fees may vary depending on the
                        city/province where the training will be conducted.</li>
                    </ol>
                  </li>
                </ul>
              </div>
            </div>
          </div>
          <div class="accordion-item">
            <h2 class="accordion-header">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                data-bs-target="#panelsStayOpen-collapseFour" aria-expanded="false"
                aria-controls="panelsStayOpen-collapseFour">
                REQUEST FOR SME/RESOURCE SPEAKER SERVICES <i class="bi bi-chevron-down arrow"></i>
              </button>
            </h2>
            <div id="panelsStayOpen-collapseFour" class="accordion-collapse collapse">
              <div class="accordion-body">
                <p>Agency requests for conduct of orientations or lectures on specific topics for a half day or less
                  shall be considered as resource speaker/SME services only. The request may be granted subject to the
                  following conditions:</p>
                <ul>
                  <li>The topic is within the mandate or scope of the Civil Service Commission;</li>
                  <li>Requesting agencies shall be required to submit a written request addressed to the Office of the
                    Regional Director (ORD) with a copy of the program of activities indicating the actual date and time
                    the topic/s will be delivered;</li>
                  <li>The CSC RO VI personnel assigned to serve as resource speaker/SME shall be issued a Regional
                    Office Order authorizing him/her to render the resource speaker/SME services;</li>
                  <li>Authorized resource speakers/SMEs shall be allowed to receive honoraria according to existing CSC
                    guidelines. All payments for honoraria by the requesting agency (cash or check) shall be made
                    directly to the Regional or Field Office or deposited to Training Fund LBP Account No. 0032-1025-49.
                    Checks should be payable to Civil Service Commission;</li>
                  <li>The requesting agency shall submit a copy of the Official Receipt or deposit slip for payment of
                    honoraria to the CSC RO VI – MSD, together with a certification as to the number of hours rendered
                    for the resource speaker/SME services, within five (5) working days from the date the services were
                    rendered. The MSD shall cause the payment of honorarium to the concerned CSC RO VI personnel,
                    subject to existing guidelines;</li>
                  <li>The requesting agency shall take charge of transportation and accommodation of resource
                    persons/SMEs;</li>
                  <li>Resource speaker/SME services rendered for a whole day shall be treated as one (1) training
                    program and therefore subject to the conditions under the item "Request for Conduct of Training
                    Program".</li>
                </ul>
              </div>
            </div>
          </div>
        </div>

        <!-- <div class="row">
          <div class="col-lg-4 col-md-4">
            <div class="icon-box" data-aos="zoom-in-left">
              <div class="icon"><i class="bi bi-envelope-open" style="color: #ff689b;"></i></div>
              <h4 class="title"><a href="">Lorem Ipsum</a></h4>
              <p class="description">Voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate non provident</p>
            </div>
          </div>
          <div class="col-lg-4 col-md-4 mt-5 mt-md-0">
            <div class="icon-box" data-aos="zoom-in-left" data-aos-delay="100">
              <div class="icon"><i class="bi bi-book" style="color: #e9bf06;"></i></div>
              <h4 class="title"><a href="">Dolor Sitema</a></h4>
              <p class="description">Minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat tarad limino ata</p>
            </div>
          </div>

          <div class="col-lg-4 col-md-4 mt-5 mt-lg-0 ">
            <div class="icon-box" data-aos="zoom-in-left" data-aos-delay="200">
              <div class="icon"><i class="bi bi-card-checklist" style="color: #3fcdc7;"></i></div>
              <h4 class="title"><a href="">Sed ut perspiciatis</a></h4>
              <p class="description">Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur</p>
            </div>
          </div>
          <div class="col-lg-4 col-md-4 mt-5">
            <div class="icon-box" data-aos="zoom-in-left" data-aos-delay="300">
              <div class="icon"><i class="bi bi-binoculars" style="color:#41cf2e;"></i></div>
              <h4 class="title"><a href="">Magni Dolores</a></h4>
              <p class="description">Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum</p>
            </div>
          </div>

      </div> -->
    </section><!-- End Policies Section -->

    <!-- ======= F.A.Q Section ======= -->
    <section id="faq" class="faq">
      <div class="container">

        <div class="section-title" data-aos="zoom-out">
          <h2>F.A.Q</h2>
          <p>Frequently Asked Questions</p>
        </div>

        <ul class="faq-list">

          <li>
            <div data-bs-toggle="collapse" class="collapsed question" href="#faq1">Non consectetur a erat nam at lectus
              urna duis? <i class="bi bi-chevron-down icon-show"></i><i class="bi bi-chevron-up icon-close"></i></div>
            <div id="faq1" class="collapse" data-bs-parent=".faq-list">
              <p>
                Feugiat pretium nibh ipsum consequat. Tempus iaculis urna id volutpat lacus laoreet non curabitur
                gravida. Venenatis lectus magna fringilla urna porttitor rhoncus dolor purus non.
              </p>
            </div>
          </li>

          <li>
            <div data-bs-toggle="collapse" href="#faq2" class="collapsed question">Feugiat scelerisque varius morbi enim
              nunc faucibus a pellentesque? <i class="bi bi-chevron-down icon-show"></i><i
                class="bi bi-chevron-up icon-close"></i></div>
            <div id="faq2" class="collapse" data-bs-parent=".faq-list">
              <p>
                Dolor sit amet consectetur adipiscing elit pellentesque habitant morbi. Id interdum velit laoreet id
                donec ultrices. Fringilla phasellus faucibus scelerisque eleifend donec pretium. Est pellentesque elit
                ullamcorper dignissim. Mauris ultrices eros in cursus turpis massa tincidunt dui.
              </p>
            </div>
          </li>

          <li>
            <div data-bs-toggle="collapse" href="#faq3" class="collapsed question">Dolor sit amet consectetur adipiscing
              elit pellentesque habitant morbi? <i class="bi bi-chevron-down icon-show"></i><i
                class="bi bi-chevron-up icon-close"></i></div>
            <div id="faq3" class="collapse" data-bs-parent=".faq-list">
              <p>
                Eleifend mi in nulla posuere sollicitudin aliquam ultrices sagittis orci. Faucibus pulvinar elementum
                integer enim. Sem nulla pharetra diam sit amet nisl suscipit. Rutrum tellus pellentesque eu tincidunt.
                Lectus urna duis convallis convallis tellus. Urna molestie at elementum eu facilisis sed odio morbi quis
              </p>
            </div>
          </li>

          <li>
            <div data-bs-toggle="collapse" href="#faq4" class="collapsed question">Ac odio tempor orci dapibus. Aliquam
              eleifend mi in nulla? <i class="bi bi-chevron-down icon-show"></i><i
                class="bi bi-chevron-up icon-close"></i></div>
            <div id="faq4" class="collapse" data-bs-parent=".faq-list">
              <p>
                Dolor sit amet consectetur adipiscing elit pellentesque habitant morbi. Id interdum velit laoreet id
                donec ultrices. Fringilla phasellus faucibus scelerisque eleifend donec pretium. Est pellentesque elit
                ullamcorper dignissim. Mauris ultrices eros in cursus turpis massa tincidunt dui.
              </p>
            </div>
          </li>

          <li>
            <div data-bs-toggle="collapse" href="#faq5" class="collapsed question">Tempus quam pellentesque nec nam
              aliquam sem et tortor consequat? <i class="bi bi-chevron-down icon-show"></i><i
                class="bi bi-chevron-up icon-close"></i></div>
            <div id="faq5" class="collapse" data-bs-parent=".faq-list">
              <p>
                Molestie a iaculis at erat pellentesque adipiscing commodo. Dignissim suspendisse in est ante in. Nunc
                vel risus commodo viverra maecenas accumsan. Sit amet nisl suscipit adipiscing bibendum est. Purus
                gravida quis blandit turpis cursus in
              </p>
            </div>
          </li>

          <li>
            <div data-bs-toggle="collapse" href="#faq6" class="collapsed question">Tortor vitae purus faucibus ornare.
              Varius vel pharetra vel turpis nunc eget lorem dolor? <i class="bi bi-chevron-down icon-show"></i><i
                class="bi bi-chevron-up icon-close"></i></div>
            <div id="faq6" class="collapse" data-bs-parent=".faq-list">
              <p>
                Laoreet sit amet cursus sit amet dictum sit amet justo. Mauris vitae ultricies leo integer malesuada
                nunc vel. Tincidunt eget nullam non nisi est sit amet. Turpis nunc eget lorem dolor sed. Ut venenatis
                tellus in metus vulputate eu scelerisque. Pellentesque diam volutpat commodo sed egestas egestas
                fringilla phasellus faucibus. Nibh tellus molestie nunc non blandit massa enim nec.
              </p>
            </div>
          </li>

        </ul>

      </div>
    </section><!-- End F.A.Q Section -->

    <!-- ======= Team Section ======= -->
    <section id="team" class="team">
      <div class="container">

        <div class="section-title" data-aos="zoom-out">
          <h2>CSC RO VI</h2>
          <p>Management and Key Officials</p>
        </div>

        <div class="row" data-aos="fade-up">

          <div class="col-lg-3 col-md-6 d-flex align-items-stretch">
            <div class="big-card">
              <div class="profile-pic">
                <img src="assets/img/team/team-1.jpg" alt="" width="100%">
              </div>
              <div class="details">
                <span class="name">NELSON G. SARMIENTO</span><span class="position">Director IV</span>
              </div>
            </div>
          </div>

          <div class="col-lg-3 col-md-6 d-flex align-items-stretch">
            <div class="big-card">
              <div class="profile-pic">
                <img src="assets/img/team/team-1.jpg" alt="" width="100%">
              </div>
              <div class="details">
                <span class="name">NELSON G. SARMIENTO</span><span class="position">Director IV</span>
              </div>
            </div>
          </div>

          <div class="col-lg-3 col-md-6 d-flex align-items-stretch">
            <div class="big-card">
              <div class="profile-pic">
                <img src="assets/img/team/team-1.jpg" alt="" width="100%">
              </div>
              <div class="details">
                <span class="name">NELSON G. SARMIENTO</span><span class="position">Director IV</span>
              </div>
            </div>
          </div>

          <div class="col-lg-3 col-md-6 d-flex align-items-stretch">
            <div class="big-card">
              <div class="profile-pic">
                <img src="assets/img/team/team-1.jpg" alt="" width="100%">
              </div>
              <div class="details">
                <span class="name">NELSON G. SARMIENTO</span><span class="position">Director IV</span>
              </div>
            </div>
          </div>

          <div class="col-lg-3 col-md-6 d-flex align-items-stretch">
            <div class="big-card">
              <div class="profile-pic">
                <img src="assets/img/team/team-1.jpg" alt="" width="100%">
              </div>
              <div class="details">
                <span class="name">NELSON G. SARMIENTO</span><span class="position">Director IV</span>
              </div>
            </div>
          </div>

          <div class="col-lg-3 col-md-6 d-flex align-items-stretch">
            <div class="big-card">
              <div class="profile-pic">
                <img src="assets/img/team/team-1.jpg" alt="" width="100%">
              </div>
              <div class="details">
                <span class="name">NELSON G. SARMIENTO</span><span class="position">Director IV</span>
              </div>
            </div>
          </div>

        </div>

        <div class="mt-5" data-aos="zoom-out">
          <h2>HUMAN RESOURCE DIVISION</h2>
        </div>

        <div class="row mt-5" data-aos="fade-up">
          <div class="col-lg-4 col-md-6 d-flex align-items-stretch">
            <div class="small-card">
              <div class="name">SHEILA P. ARENDAIN</div>
              <div class="position">OIC-CHEF HRS</div>
            </div>
          </div>
        </div>

        <div class="row mt-3" data-aos="fade-up">

          <div class="col-lg-4 col-md-6 d-flex align-items-stretch">
            <div class="small-card">
              <div class="name">CHRISTOPHER Z. CANG</div>
              <div class="position">HRS I</div>
            </div>
          </div>

          <div class="col-lg-4 col-md-6 d-flex align-items-stretch">
            <div class="small-card">
              <div class="name">ELAINE L. SEGURA</div>
              <div class="position">HRS II</div>
            </div>
          </div>

          <div class="col-lg-4 col-md-6 d-flex align-items-stretch">
            <div class="small-card">
              <div class="name">IANA FRANCES V. GATPATAN</div>
              <div class="position">HRS II</div>
            </div>
          </div>

          <div class="col-lg-4 col-md-6 d-flex align-items-stretch">
            <div class="small-card">
              <div class="name">MARYDEL MAY B. DELGADO-JIMENO</div>
              <div class="position">HRS II</div>
            </div>
          </div>

        </div>

      </div>
    </section><!-- End Team Section -->

    <!-- ======= Contact Section ======= -->
    <section id="contact" class="contact">
      <div class="container">

        <div class="section-title" data-aos="zoom-out">
          <h2>Contact</h2>
          <p>Contact Us</p>
        </div>

        <div class="row mt-5">

          <div class="col-lg-4" data-aos="fade-right">
            <div class="info">
              <div class="address">
                <i class="bi bi-geo-alt"></i>
                <h4>Location:</h4>
                <p>No. 7 Oñate St., Mandurriao, Iloilo City 5000</p>
              </div>

              <div class="email">
                <i class="bi bi-envelope"></i>
                <h4>Email:</h4>
                <p>training.cscro6@gmail.com</p>
              </div>

              <div class="phone">
                <i class="bi bi-phone"></i>
                <h4>Call:</h4>
                <p>Telephone Nos. : (033) 321-2667 to 69 <br>

                  Hot Line: (033) 321-1253 <br>

                  Telefax: 321-2667/5086706 <br>

                  Phone Number: (+63) 969-613-1854</p>
              </div>

            </div>

          </div>

          <div class="col-lg-8 mt-5 mt-lg-0" data-aos="fade-left">
            <!-- <div><h2 class="text-center" style="font-weight: bold;">Concerns or Feedback</h2></div> -->
            <form action="components/processes/messagesProcess.php" method="post" enctype="multipart/form-data">

              <div class="row">
                <div class="col-md-6 form-group">
                  <input type="text" name="name" class="form-control" id="name" placeholder="Your Name" required>
                </div>
                <div class="col-md-6 form-group mt-3 mt-md-0">
                  <input type="email" class="form-control" name="email" id="email" placeholder="Your Email" required>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12 form-group">
                  <input type="text" class="form-control" name="agency" id="agency" placeholder="Your Agency" required>
                </div>
              </div>
              <div class="form-group mt-3">
                <input type="text" class="form-control" name="subject" id="subject" placeholder="Subject" required>
              </div>
              <div class="form-group mt-3">
                <textarea class="form-control" name="message" id="message" rows="5" placeholder="Message"
                  required></textarea>
              </div>
              <div class="text-center"><button type="submit" class="concern-btn text-center">Send Message</button></div>
            </form>

          </div>

        </div>

      </div>
    </section><!-- End Contact Section -->

  </main><!-- End #main -->

  <!-- ======= Footer ======= -->
  <footer id="footer">
    <div class="container">
      <h3>Civil Service Commission - Regional Office VI</h3>
      <p>Gawing Lingkod Bayani ang Bawat Kawani.</p>
      <div class="copyright">
        &copy; Copyright 2024. All Rights Reserved
      </div>
      <div class="credits">
        <!-- All the links in the footer should remain intact. -->
        <!-- You can delete the links only if you purchased the pro version. -->
        <!-- Licensing information: https://bootstrapmade.com/license/ -->
        <!-- Purchase the pro version with working PHP/AJAX contact form: https://bootstrapmade.com/selecao-bootstrap-template/ -->
        Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a> and CSC RO6 GIP Interns
      </div>
    </div>
  </footer><!-- End Footer -->

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
      class="bi bi-arrow-up-short"></i></a>

  <!-- Training details Modal -->
  <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
      <div class="modal-content register-modal">
        <div class="modal-header">
          <h4 class="modal-title fs-5" id="staticBackdropLabel">TRAINING DETAILS</h4>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i
              class="bi bi-x-lg"></i></button>
        </div>
        <div class="modal-body training-details d-flex gap-3">
          <div class="row">
            <div class="col-md-4">
              <p class="details-title">Training Name:</p>
            </div>
            <div class="col-md-8" id="trainingName"></div>
          </div>
          <div class="row">
            <div class="col-md-4" class="details-title">
              <p class="details-title">Date of Training:</p>
            </div>
            <div class="col-md-8" id="trainingDate"></div>
          </div>
          <div class="row">
            <div class="col-md-4" class="details-title">
              <p class="details-title">Venue:</p>
            </div>
            <div class="col-md-8" id="trainingVenue"></div>
          </div>
          <div class="row">
            <div class="col-md-4" class="details-title">
              <p class="details-title">Mode of Training:</p>
            </div>
            <div class="col-md-8" id="trainingMode"></div>
          </div>
          <div class="row">
            <div class="col-md-4" class="details-title">
              <p class="details-title">Registration Fee:</p>
            </div>
            <div class="col-md-8" id="trainingFee"></div>
          </div>
          <div class="row">
            <div class="col-md-4" class="details-title">
              <p class="details-title">Training Hours:</p>
            </div>
            <div class="col-md-8" id="trainingHours"></div>
          </div>
          <div class="row">
            <div class="col-md-4" class="details-title">
              <p class="details-title">Remaining Slots:</p>
            </div>
            <div class="col-md-8" id="trainingSlots"></div>
          </div>
          <div class="row">
            <div class="col-md-4" class="details-title">
              <p class="details-title">Curricculum Area:</p>
            </div>
            <div class="col-md-8" id="trainingCurrArea"></div>
          </div>
          <div class="row">
            <div class="col-md-4" class="details-title">
              <p class="details-title">Description:</p>
            </div>
            <div class="col-md-8" id="trainingDetails"></div>
          </div>
        </div>
        <div class="modal-footer">
          <div class="d-flex justify-content-end"><button type="button" class="proceed-btn" data-bs-toggle="modal"
              data-bs-target="#conSlipModal">Proceed</button></div>
        </div>
      </div>
    </div>
  </div>

  <!-- Confirmation Slip Modal -->
  <div class="modal fade" id="conSlipModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="conSlipModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <span class="modal-title fs-5" id="conSlipModalLabel">Confirmation Slip</span>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <!-- Upload Confirmation Slip -->
          <div>Before you proceed, make sure that you have already filled and downloaded the confirmation slip. <br>If
            you
            haven't yet, download the confirmation slip <a href="assets/conf_slips/2024 Confirmation Slip.docx"
              downlaod>here</a>.</div>
          <div class="form-group">
            <label for="uploadFile" class="info-text">Accepted format of document: PDF, DOCX, DOC, JPEG, JPG,
              PNG</label>
            <input type="file" class="form-control-file confirmation-slip-input" id="uploadFile" name="uploadFile"
              required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" data-bs-toggle="modal"
            data-bs-target="#staticBackdrop">Back</button>
          <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#regReviewModal"
            id="confSlip-btn" onclick="toReviewDetails()">Register</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Registration Modal -->
  <div class="modal fade" id="registerBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="registerBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
      <div class="modal-content register-modal">
        <div class="modal-header">
          <h4 class="modal-title fs-5" id="registerBackdropLabel">REGISTRATION FORM</h4>
          <button type="button" class="btn-close" data-bs-dismiss="modal" data-bs-toggle="modal"
            data-bs-target="#conSlipModal" aria-label="Close"><i class="bi bi-x-lg"></i></button>
        </div>
        <div class="modal-body">
          <form class="regForm" enctype="multipart/form-data">
            <h5 class="mb-4" id="trainingNameRegForm">PUBLIC SECTOR UNION</h5>
            <input type="hidden" id="trainingID" name="trainingID">
            <!-- Personal Information -->
            <h6 class="mb-3 form-title">PERSONAL INFORMATION</h6>
            <div class="form-row">
              <div class="form-group col-md-2">
                <label for="prefix">Prefix <small><i>(optional)</i></small></label>
                <input type="text" class="form-control" id="prefix" name="prefix" placeholder="e.g. Atty."
                  value="<?php echo $prefixDetail; ?>">
              </div>
              <div class="form-group col-md-3">
                <label for="firstName">First Name <small>*</small></label>
                <input type="text" class="form-control" id="firstName" name="firstName" placeholder="e.g. Juan"
                  value="<?php echo $firstNameDetail; ?>" required>
              </div>
              <div class="form-group col-md-2">
                <label for="middleInitial">Middle Initial <small>*</small></label>
                <input type="text" class="form-control" id="middleInitial" name="middleInitial" placeholder="e.g. B"
                  value="<?php echo $middleInitialDetail; ?>">>
              </div>
              <div class="form-group col-md-3">
                <label for="lastName">Last Name <small>*</small></label>
                <input type="text" class="form-control" id="lastName" name="lastName" placeholder="e.g. Dela Cruz"
                  value="<?php echo $lastNameDetail; ?>">
                required>
              </div>
              <div class="form-group col-md-2">
                <label for="suffix">Suffix <small>*</small></label>
                <input type="text" class="form-control" id="suffix" name="suffix" value="<?php echo $suffixDetail; ?>"
                  required>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group col-md-4">
                <label for="age">Nickname <small><u>(to be shown in your training ID)</u></small>
                  <small>*</small></label>
                <input type="text" class="form-control" id="nickname" name="nickname" placeholder="e.g. Juan"
                  value="<?php echo $nicknameDetail; ?>" required>
              </div>
              <div class="form-group col-md-2">
                <label for="gender">Sex <small>*</small></label>
                <input type="text" class="form-control" id="sex" name="sex" value="<?php echo $sexDetail; ?>" required>
              </div>
              <div class="form-group col-md-2">
                <label for="age">Age <small>*</small></label>
                <input type="number" class="form-control" id="age" name="age" value="<?php echo $ageDetail; ?>"
                  required>
              </div>
              <div class="form-group col-md-4">
                <label for="civilStatus">Civil Status <small>*</small></label>
                <input type="text" class="form-control" name="civilStatus" id="civilStatus" name="civilStatus"
                  value="<?php echo $civilStatusDetail; ?>" required>
              </div>
            </div>

            <!-- Contact Information -->
            <h6 class="mb-3 form-title">CONTACT INFORMATION</h6>
            <div class="form-row">
              <div class="form-group col-md-4">
                <label for="phoneNumber">Phone Number <small>*</small></label>
                <input type="text" class="form-control" id="phoneNumber" name="phoneNumber"
                  placeholder="e.g. 09123456789" value="<?php echo $phoneNumberDetail; ?>" required>
              </div>
              <div class="form-group col-md-4">
                <label for="personalEmail">Email Address <small>*</small><small id='emailNotice'></small></label>
                <input type="email" class="form-control" id="personalEmail" name="personalEmail"
                  placeholder="e.g. juandelacruz@gmail.com" value="<?php echo $emailDetail; ?>" required>
              </div>
              <div class="form-group col-md-4">
                <label for="altEmail">Alternative Email Address <small><i>(optional)</i></small></label>
                <input type="email" class="form-control" id="altEmail" name="altEmail"
                  placeholder="e.g. juandelacruz@gmail.com" value="<?php echo $altEmailDetail; ?>">
              </div>
            </div>

            <!-- Agency Information -->
            <h6 class="mb-3 form-title">AGENCY INFORMATION</h6>
            <div class="form-row">
              <div class="form-group col-md-6">
                <label for="sector">Sector <small>*</small></label>
                <input type="text" class="form-control" id="sector" name="sector" value="<?php echo $sectorDetail; ?>">
              </div>
              <div class="form-group col-md-6">
                <label for="agencyName">Name of Agency / Organization <small><u>(please don't
                      abbreviate)</u></small> <small>*</small></label>
                <input type="text" class="form-control" id="agencyName" name="agencyName"
                  placeholder="e.g. Civil Service Commission Regional Office No. 6" value="<?php echo $agencyDetail; ?>"
                  required>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group col-md-6">
                <label for="position">Position <small>*</small></label>
                <input type="text" class="form-control" id="position" name="position"
                  placeholder="Human Resource Specialist I" value="<?php echo $positionDetail; ?>" required>
              </div>
              <div class="form-group col-md-6">
                <label for="location">CSC Field Office that has jurisdiction in your area <small>*</small></label>
                <input type="text" name="fo" id="fo" class="form-control" value="<?php echo $foDetail; ?>">
              </div>
            </div>

            <!-- Food Restrictions -->
            <h6 class="mb-3 form-title">FOOD RESTRICTIONS <small>(leave blank if none)</small></h6>
            <div class="form-group">
              <input type="text" class="form-control" id="foodRestrictions" name="foodRestrictions"
                placeholder="If any, please specify." value="<?php echo $foodRestrictionDetail; ?>">
            </div>
          </form><!-- Register Button -->
          <div class="d-flex justify-content-center">
            <button class="btn btn-primary btn-block register-submit" onclick="toReviewDetails()">PROCEED</button>
            <!-- onclick="registerParticipant()" -->
          </div>
        </div>
        <div class="modal-footer">
          <span class="footer-msg">The information collected will be used only for training purposes. Contact details
            will be used solely to reach out to participants and will not be shared with anyone else without the
            individual's consent.</span>
        </div>
      </div>
    </div>
  </div>

  <!-- Regitsration form review Modal -->
  <div class="modal fade" id="regReviewModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="regReviewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title fs-5" id="regReviewModalLabel">Review Registration Details</h4>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <h5 id="trainingNameReview" style="color: #24305E; font-weight: bold; margin-bottom: 1em;"></h5>
          <!-- personal information -->
          <div class="review-information-group">
            <h6 class="review-information-title mb-3 font-weight-bold" style="color: #24305E;">PERSONAL INFORMATION</h6>
            <div class="row mb-2" style="padding: 0; margin: 0; border-bottom: 1px solid #b8b8b8;">
              <div class=" col-md-4 d-flex">
                <div class="review-information-name">First Name:</div>
                <div class="review-information-content" id="reviewFirstName">Nicca</div>
              </div>
              <div class="col-md-4 d-flex">
                <div class="review-information-name">Prefix:</div>
                <div class="review-information-content" id="reviewPrefix">Atty.</div>
              </div>
              <div class="col-md-4 d-flex">
                <div class="review-information-name">Suffix:</div>
                <div class="review-information-content" id="reviewSuffix">III</div>
              </div>
            </div>
            <div class="row mb-2" style="padding: 0; margin: 0; border-bottom: 1px solid #b8b8b8;">
              <div class="col-md-4 d-flex">
                <div class="review-information-name">Middle Initial:</div>
                <div class="review-information-content" id="reviewMiddleInitial">B.</div>
              </div>
              <div class="col-md-4 d-flex">
                <div class="review-information-name">Sex:</div>
                <div class="review-information-content" id="reviewSex">Female</div>
              </div>
              <div class="col-md-4 d-flex">
                <div class="review-information-name">Age:</div>
                <div class="review-information-content" id="reviewAge">22 years old</div>
              </div>
            </div>
            <div class="row mb-2" style="padding: 0; margin: 0;">
              <div class="col-md-4 d-flex">
                <div class="review-information-name">Last Name:</div>
                <div class="review-information-content" id="reviewLastName">Senato</div>
              </div>
              <div class="col-md-4 d-flex">
                <div class="review-information-name">Nickname:</div>
                <div class="review-information-content" id="reviewNickname">Mae</div>
              </div>
              <div class="col-md-4 d-flex">
                <div class="review-information-name">Civil Status:</div>
                <div class="review-information-content" id="reviewCivilStatus">Single</div>
              </div>
            </div>
          </div>
          <!-- Contact information -->
          <div class="review-information-group">
            <h6 class="review-information-title mb-3 font-weight-bold" style="color: #24305E;">CONTACT INFORMATION</h6>
            <div class="row mb-2" style="padding: 0; margin: 0; border-bottom: 1px solid #b8b8b8;">
              <div class=" col-md-12 d-flex">
                <div class="review-information-name">Phone Number:</div>
                <div class="review-information-content" style="flex:2;" id="reviewNumber">09915496598</div>
              </div>
            </div>
            <div class="row mb-2" style="padding: 0; margin: 0; border-bottom: 1px solid #b8b8b8;">
              <div class="col-md-12 d-flex">
                <div class="review-information-name">Personal Email:</div>
                <div class="review-information-content" style="flex:2;" id="reviewEmail">chagianvillanueva@gmail.com
                </div>
              </div>
            </div>
            <div class="row mb-2" style="padding: 0; margin: 0;">
              <div class="col-md-12 d-flex">
                <div class="review-information-name">Alternative Email:</div>
                <div class="review-information-content" style="flex:2;" id="reviewAltEmail">N/A</div>
              </div>
            </div>
          </div>
          <!-- Agency information -->
          <div class="review-information-group">
            <h6 class="review-information-title mb-3 font-weight-bold" style="color: #24305E;">AGENCY INFORMATION</h6>
            <div class="row mb-2" style="padding: 0; margin: 0; border-bottom: 1px solid #b8b8b8;">
              <div class=" col-md-12 d-flex">
                <div class="review-information-name">Sector:</div>
                <div class="review-information-content" style="flex:2;" id="reviewSector">Local Government Unit</div>
              </div>
            </div>
            <div class="row mb-2" style="padding: 0; margin: 0; border-bottom: 1px solid #b8b8b8;">
              <div class="col-md-12 d-flex">
                <div class="review-information-name">Agency Name:</div>
                <div class="review-information-content" style="flex:2;" id="reviewAgencyName">Civil Service Commission -
                  Regional Office No.
                  VI</div>
              </div>
            </div>
            <div class="row mb-2" style="padding: 0; margin: 0; border-bottom: 1px solid #b8b8b8;">
              <div class="col-md-12 d-flex">
                <div class="review-information-name">Position:</div>
                <div class="review-information-content" style="flex:2;" id="reviewPosition">GIP Intern</div>
              </div>
            </div>
            <div class="row mb-2" style="padding: 0; margin: 0;">
              <div class="col-md-12 d-flex">
                <div class="review-information-name">CSC Field Office:</div>
                <div class="review-information-content" style="flex:2;" id="reviewFO">CSC FO - Iloilo</div>
              </div>
            </div>
          </div>
          <!-- Food Restriction -->
          <div class="review-information-group">
            <h6 class="review-information-title mb-3 font-weight-bold" style="color: #24305E;">FOOD RESTRICTIONS</h6>
            <div class="row mb-2" style="padding: 0; margin: 0; border-bottom: 1px solid #b8b8b8;">
              <div class=" col-md-12 d-flex">
                <div class="review-information-content" style="flex:2;" id="reviewFoodRestrictions">Seafoods, Crabs,
                  Shrimps, Octapus etc. </div>
              </div>
            </div>
          </div>
          <!-- cONFIRMATION Slip -->
          <div class="review-information-group">
            <h6 class="review-information-title mb-3 font-weight-bold" style="color: #24305E;">CONFIRMATION SLIP</h6>
            <div class="row mb-2" style="padding: 0; margin: 0; border-bottom: 1px solid #b8b8b8;">
              <div class=" col-md-12 d-flex">
                <div class="review-information-content" style="flex:2;" id="reviewConfSlip">BidaKawani_Senato.pdf</div>
              </div>
            </div>
          </div>
          <!-- Agreement -->
          <h6 class="mb-3 form-title">AGREEMENT</h6>
          <div class="form-check mb-2">
            <input class="agreementCheckbox form-check-input" type="checkbox" id="privacyPolicy" required>
            <label class="form-check-label info-text" for="privacyPolicy">
              I hereby agree and consent for the processing of my personal data information in regards of the Data
              Privacy Act.
            </label>
          </div>
          <div class="form-check mb-2">
            <input class="agreementCheckbox form-check-input" type="checkbox" id="paymentAgreement" required>
            <label class="form-check-label info-text" for="paymentAgreement">
              I hereby agree that by registering for this seminar, I will be charged with the amount corresponding to
              the training fee as stated in the guidelines, and that I will be responsible for any expenses in case of
              withdrawal or cancellation.
            </label>
          </div>
          <div class="form-check mb-2">
            <input class="agreementCheckbox form-check-input" type="checkbox" id="certificateAgreement" required>
            <label class="form-check-label info-text" for="certificateAgreement">
              I agree that the Certificate of Completion shall only be issued to me if I pay the registration fee and
              completely submit all the accomplished outputs.
            </label>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" data-bs-toggle="modal"
            data-bs-target="#conSlipModal">Back</button>
          <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#registrationLoading"
            id="regReview-btn" onclick="registerParticipant()">Register</button>
          <!-- onclick="registerParticipant()" -->
        </div>
      </div>
    </div>
  </div>

  <!-- Registration loading modal -->
  <div class="modal fade" id="registrationLoading" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="registrationLoadingLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-body">
          <div id="reg-loading-content" class="d-flex flex-column justify-content-center align-items-center">
            <div id="reg-loader" style="display: flex; justify-content: center; width: 25%;">
              <img src="assets/img/reg-loading.svg" alt="">
            </div>
            <div id="loading-status" style="text-align: center; font-weight: 500; font-size: large;"></div>
            <button type="button" class="btn btn-secondary mt-3" data-bs-dismiss="modal" id="regisrationLoadingBack-btn"
              style="width: 25%;">Close</button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/aos/aos.js"></script>
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
  <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>

  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <!-- DataTables JS -->
  <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.11.3/js/dataTables.bootstrap4.min.js"></script>

  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>

  <script>
    $(document).ready(function () {
      $.ajax({
        url: 'components/processes/fetchTrainings.php',
        type: 'GET',
        success: function (data) {
          $('#training-body').html(data);
          $('#trainingTable').DataTable({
            "ordering": false // Enable sorting
          });
        }
      });

      $('#confSlip-btn')
        .prop('disabled', true);

      document.getElementById("uploadFile").addEventListener('input', function (e) {
        var file = e.target.files[0];

        if (file) {
          $('#confSlip-btn')
            .prop('disabled', false);
        } else {
          $('#confSlip-btn')
            .prop('disabled', true);
        }
      })

      checkAgreement();
    });


  </script>

  <script>

    function trainingDetails(id) {
      const trainingIDInput = document.getElementById("trainingID");
      trainingIDInput.value = id;

      $.ajax({
        url: 'components/processes/fetchTrainingDetails.php',
        data: { id: id },
        dataType: 'json',
        success: function (data) {
          var trainingName = data.trainingName;
          var trainingDescription = data.details;
          var currArea = data.currArea;
          var startDate = new Date(data.startDate);
          var endDate = new Date(data.endDate);

          const months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

          console.log(startDate + ", " + endDate);
          if (startDate.getDate() == endDate.getDate()) {
            var date = `${months[startDate.getMonth()]} ${startDate.getDate()}, ${startDate.getFullYear()}`;
          } else {
            var date = `${months[startDate.getMonth()]} ${startDate.getDate()}-${endDate.getDate()}, ${startDate.getFullYear()}`;
          }

          var remainingSlots = data.targetPax - data.registeredPax;

          $('#trainingName').text(trainingName);
          $('#trainingNameRegForm').text(trainingName);
          $('#trainingDetails').text(trainingDescription);
          $('#trainingCurrArea').text(currArea);
          $('#trainingDate').text(date);
          $('#trainingVenue').text(data.venue);
          $('#trainingMode').text(data.mode);
          $('#trainingFee').text("₱" + data.fee + ".00");
          $('#trainingHours').text(data.trainingHours + " hours");
          $('#trainingSlots').text(remainingSlots);

          console.log(data.openReg);
          if (data.openReg == "0") {
            $('.proceed-btn')
              .prop('disabled', true)       // Disable the button
              .text('Registration Close')   // Change the text
              .css('background-color', 'grey'); // Change the color
          } else {
            $('.proceed-btn')
              .prop('disabled', false)       // Disable the button
              .text('Proceed')   // Change the text
              .css('background-color', '#DC3636'); // Change the color
          }

        }
      });
    }

    function checkAgreement() {
      $("#regReview-btn").prop('disabled', true);

      $(".agreementCheckbox").change(function () {
        const allChecked = $(".agreementCheckbox").length === $(".agreementCheckbox:checked").length;

        console.log($(".agreementCheckbox:checked").length);

        if (allChecked) {
          $("#regReview-btn").prop('disabled', false);
        } else {
          $("#regReview-btn").prop('disabled', true);
        }


      });
    }

    async function registerParticipant() {



      $("#regisrationLoadingBack-btn").hide();
      $("#reg-loader").html("<img src='assets/img/reg-loading.svg' >");
      $("#loading-status").html("<div style='font-size: large;'>Checking registration status...</div>");

      const prefix = document.getElementById("prefix").value;
      const firstName = document.getElementById("firstName").value;
      const middleInitial = document.getElementById("middleInitial").value.trim().charAt(0);
      const lastName = document.getElementById("lastName").value;
      const suffix = document.getElementById("suffix").value;
      const nickname = document.getElementById("nickname").value;
      const sex = document.getElementById("sex").value;
      const age = document.getElementById('age').value;
      const civilStatus = document.getElementById('civilStatus').value;
      const phoneNumber = document.getElementById('phoneNumber').value;
      const personalEmail = document.getElementById("personalEmail").value;
      const altEmail = document.getElementById("altEmail").value;
      const position = document.getElementById("position").value;
      const sector = document.getElementById("sector").value;
      const agencyName = document.getElementById("agencyName").value;
      const fo = document.getElementById("fo").value;
      const foodRestrictions = document.getElementById("foodRestrictions").value;

      const userID = <?php echo $_SESSION['userID'] ?? "-1"; ?>;

      const trainingID = document.getElementById("trainingID").value;

      const alreadyRegistered = await checkDuplicateRegistration(trainingID, <?php echo $employeeID; ?>);

      if (alreadyRegistered == "0") {

        var registrationFormData = new FormData();
        registrationFormData.append("prefix", prefix);
        registrationFormData.append("firstName", firstName);
        registrationFormData.append("middleInitial", middleInitial);
        registrationFormData.append("lastName", lastName);
        registrationFormData.append("suffix", suffix);
        registrationFormData.append("nickname", nickname);
        registrationFormData.append("sex", sex);
        registrationFormData.append("age", age);
        registrationFormData.append("civilStatus", civilStatus);
        registrationFormData.append("phoneNumber", phoneNumber);
        registrationFormData.append("email", personalEmail);
        registrationFormData.append("altEmail", altEmail);
        registrationFormData.append("position", position);
        registrationFormData.append("sector", sector);
        registrationFormData.append("agencyName", agencyName);
        registrationFormData.append("fo", fo);
        registrationFormData.append("foodRestrictions", foodRestrictions);
        registrationFormData.append("confirmationSlip", document.getElementById("uploadFile").files[0]);
        registrationFormData.append("trainingID", trainingID);
        registrationFormData.append("userID", userID);

        $("#loading-status").html("<div style='font-size: large;'>Saving registration...</div>");

        $.ajax({
          url: 'components/processes/registrationProcess.php',
          type: 'POST',
          data: registrationFormData,
          processData: false,
          contentType: false,
          success: function (response) {
            switch (response) {
              case "ok":
                console.log("OKAY");
                const loadingStatus = "<b style='color: #00A52E;'>You have been registered to the training.</b><div style='font-size: small; line-height: 1.5;     margin-top: 10px;'>Your login credentials will be sent to your email within 3 working days including the instructions to login on this website.</div><br><div style='font-size: small;'>If you have any questions, please <span style='text-decoration: underline; color: blue; cursor: pointer;'>click here</span>.</div>";

                $("#loading-status").html(loadingStatus);
                $("#regisrationLoadingBack-btn").show();
                $("#reg-loader").html("<img src='assets/img/reg-done.svg' >");
                break;

              default:
                console.log(response);
                $("#loading-status").html(response);
                break;
            }
          },
          error: function (jqXHR, textStatus, errorThrown) {
            console.log("failed");
            console.error('Error in registration data:', textStatus, errorThrown);
          }
        });
      } else if (alreadyRegistered == "1") {
        const loadingStatus = "<b style='color: #DC3636;'>You are already registered to this training.</b><div style='font-size: small; line-height: 1.5;     margin-top: 10px;'>Please open your profile to check the trainings you have previously registered.</div><br><div style='font-size: small;'>If you have any questions, please <span style='text-decoration: underline; color: blue; cursor: pointer;'>click here</span>.</div>";

        $("#loading-status").html(loadingStatus);
        $("#regisrationLoadingBack-btn").show();
        $("#reg-loader").html("<img src='assets/img/reg-failed.svg' >");
      } else {
        console.log(alreadyRegistered);
      }
    }

    async function checkDuplicateRegistration(trainingID, employeeID) {

      return new Promise((resolve, reject) => {
        $.ajax({
          url: 'components/processes/checkDuplicateRegistration.php',
          type: 'POST',
          data: {
            trainingID: trainingID,
            employeeID: employeeID
          },
          success: function (response) {
            resolve(response);
          },
          error: function (jqXHR, textStatus, errorThrown) {
            reject('checking employee record error: ', textStatus, errorThrown);
          }
        });
      });
    }

    function toReviewDetails() {
      const prefix = document.getElementById("prefix").value == "" ? "N/A" : document.getElementById("prefix").value;
      const firstName = document.getElementById("firstName").value;
      const middleInitial = document.getElementById("middleInitial").value == "" ? "N/A" : document.getElementById("middleInitial").value;
      const lastName = document.getElementById("lastName").value;
      const suffix = document.getElementById("suffix").value == "" ? "N/A" : document.getElementById("suffix").value;
      const nickname = document.getElementById("nickname").value;
      const sex = document.getElementById("sex").value;
      const age = document.getElementById('age').value;
      const civilStatus = document.getElementById('civilStatus').value;
      const phoneNumber = document.getElementById('phoneNumber').value;
      const personalEmail = document.getElementById("personalEmail").value;
      const altEmail = document.getElementById("altEmail").value == "" ? "N/A" : document.getElementById("altEmail").value;
      const position = document.getElementById("position").value;
      // const sector = document.getElementById("sector").value;
      const agencyName = document.getElementById("agencyName").value;
      const fo = document.getElementById("fo").value;
      const foodRestrictions = document.getElementById("foodRestrictions").value == "" ? "N/A" : document.getElementById("foodRestrictions").value;

      const confSlipName = document.getElementById("uploadFile").files[0].name;

      let sector = "";

      switch (document.getElementById("sector").value) {
        case "lgu":
          sector = "Local Government Unit";
          break;

        case "suc":
          sector = "State University and College";
          break;

        case "gocc":
          sector = "Government Owned or Controlled Corporation";
          break;

        case "nga":
          sector = "National Government Agency";
          break;

        default:
          sector = "Others";
          break;
      };

      $("#reviewFirstName").html(firstName);
      $("#reviewPrefix").html(prefix);
      $("#reviewSuffix").html(suffix);
      $("#reviewMiddleInitial").html(middleInitial);
      $("#reviewSex").html(sex);
      $("#reviewAge").html(age);
      $("#reviewLastName").html(lastName);
      $("#reviewNickname").html(nickname);
      $("#reviewCivilStatus").html(civilStatus);
      $("#reviewNumber").html(phoneNumber);
      $("#reviewEmail").html(personalEmail);
      $("#reviewAltEmail").html(altEmail);
      $("#reviewSector").html(sector);
      $("#reviewAgencyName").html(agencyName);
      $("#reviewPosition").html(position);
      $("#reviewFO").html(fo);
      $("#reviewFoodRestrictions").html(foodRestrictions);
      $("#reviewConfSlip").html(confSlipName);

      $("#regReviewModal").modal("toggle");

    }


  </script>

  <script src="assets/js/main.js"></script>

</body>

</html>