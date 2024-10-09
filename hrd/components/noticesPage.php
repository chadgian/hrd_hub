<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
<div class='container-fluid body-content'>
  <div class='row body-content-row'>
    <div class='col-md-2 left-side'>
      <?php include 'components/navbar.php'; ?>
    </div>
    <div class='col-md-7 middle-content'>
      <div class="addNotice">
        <h5>New Notice</h5>
        <form id="newNotice" style="display: flex; flex-direction: column">
          <div class="newNoticeTitleCon">
            <input type="text" id="newNoticeTitle" name="noticeTitle" placeholder="Notice Title" style="width: 100%;"
              required>
          </div>
          <textarea name="writeNotice" id="writeNotice" required></textarea>
          <button type="submit" style="align-self: end;" class="saveNotice-btn">Post</button>
        </form>
        <div id="result">

        </div>
        <script>
          $(document).ready(function () {
            $('#writeNotice').summernote({
              focus: true,                  // set focus to editable area after initializing summernote
              placeholder: 'Write notice',
              tabsize: 2,
              toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'italic', 'clear', 'strikethrough', 'superscript', 'subscript']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'hr', 'picture', 'video']],
                ['view', ['fullscreen', 'codeview', 'help']],
                ['misc', ['undo', 'redo', 'clear']]
              ],
              callbacks: {
                onInit: function () {
                  // Move the toolbar after the editor
                  var toolbar = $('.note-toolbar');
                  $('.note-editable').after(toolbar);
                }
              }
            });
          });

          document.getElementById('newNotice').addEventListener('submit', function (event) {
            event.preventDefault(); // Prevent the default form submission

            // Get form data
            // var formData = new FormData(this);

            // Example: Display the submitted data in a div
            // var resultDiv = document.getElementById('result');
            // resultDiv.innerHTML = '';
            // for (var [key, value] of formData.entries()) {
            //   resultDiv.innerHTML += key + ': ' + value + '<br>';
            // }

            // You can also handle the form data here, e.g., send it via AJAX

            $.ajax({
              type: "POST",
              url: "components/saveNotice.php",
              data: {
                noticeTitle: $("#newNoticeTitle").val(),
                noticeBody: $("#writeNotice").val(),
                author: <?php echo $_SESSION['userID']; ?>
              },
              success: function (response) {
                if (response == "ok") {
                  console.log($("#writeNotice").val());
                  $("#writeNotice").val("");
                  $("#newNoticeTitle").val("");
                  $(".note-editable").html("");
                  location.reload();
                } else {
                  alert(response);
                }
              }
            })
          });
        </script>
      </div>
      <div class="noticesBody">
        <?php
        include "../components/processes/db_connection.php";

        $getNoticesStmt = $conn->prepare("SELECT * FROM notices as n INNER JOIN user as u ON n.userID = u.userID ORDER BY timeDate DESC");
        $getNoticesStmt->execute();
        $getNoticeResult = $getNoticesStmt->get_result();

        if ($getNoticeResult->num_rows > 0) {
          while ($getNoticeData = $getNoticeResult->fetch_assoc()) {
            $noticeID = $getNoticeData['noticeID'];

            $noticeTitle = $getNoticeData['noticeTitle'];
            $noticeBody = $getNoticeData['noticeBody'];
            $author = $getNoticeData['initials'];

            $showNotice = $getNoticeData['showNotice'];
            $noticeTime = (new DateTime($getNoticeData['timeDate']))->format("F d, Y | h:i A");


            echo "
            <div class='notice'>
              <div class='noticeHeader'>
                <div class='noticeTitle'>
                  $noticeTitle
                </div>
                <div class='noticeEdit' onclick='editNotice($noticeID)'>
                  <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor'
                    class='bi bi-pencil-square' viewBox='0 0 16 16'>
                    <path
                      d='M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z' />
                    <path fill-rule='evenodd'
                      d='M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z' />
                  </svg>
                </div>
              </div>
              <div class='noticeBody'>
                <p>
                  $noticeBody
                </p>
              </div>
              <div class='noticeFooter'>
                <div class='noticeAuthor'>
                  $author
                </div>
                <div class='noticeTime'>
                  <i>$noticeTime</i>
                </div>
              </div>
            </div>
            ";
          }
        } else {
          echo "<i>No notice published</i>";
        }
        ?>
      </div>
    </div>
    <div class='col-md-3 right-side'>
      <?php include 'components/recents.php'; ?>
    </div>
  </div>
</div>