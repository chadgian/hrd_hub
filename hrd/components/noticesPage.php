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
            <div class='notice' id='notice$noticeID'>
              <div class='noticeHeader'>
                <div class='noticeTitle'>
                  $noticeTitle
                </div>
                <div class='noticeMenu'>
                  <div class='noticeDelete' onclick='deleteNotice($noticeID)' style='cursor: pointer;'>
                    <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-trash' viewBox='0 0 16 16'>
                      <path d='M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z'/>
                      <path d='M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z'/>
                    </svg>
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

<!-- Modal -->
<div class="modal fade" id="editNoticeModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
  aria-labelledby="editNoticeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="editNoticeModalLabel">Edit Notice</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="editNotice">
          <!-- <h5>Edit Notice</h5> -->
          <form id="editNoticeForm" style="display: flex; flex-direction: column">
            <div class="editNoticeTitleCon">
              <input type="text" id="editNoticeTitle" name="editNoticeTitle" placeholder="Notice Title"
                style="width: 100%; font-size: large; font-weight: bold;" required>
            </div>
            <textarea name="editNotice" id="editNotice" required></textarea>
            <!-- <button type="submit" style="align-self: end;" class="saveNotice-btn">Post</button> -->
          </form>
          <div id="result">

          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary">Save</button>
      </div>
    </div>
  </div>
</div>

<script>
  function editNotice(noticeID) {
    toggleLoadingOverlay();
    $.ajax({
      type: "GET",
      url: "components/editNotice.php",
      data: {
        noticeID: noticeID
      },
      success: function (response) {
        if (response != "") {
          var notice = JSON.parse(response);
          // console.log(notice.noticeBody);
          $("#editNoticeTitle").val(notice.noticeTitle);
          $('#editNotice').summernote('code', notice.noticeBody);

          toggleLoadingOverlay();
          $("#editNoticeModal").modal("toggle");
        }
      }
    });
  }

  function deleteNotice(noticeID) {
    if (confirm("Are you sure to delete this notice? This action can't be undone.")) {
      toggleLoadingOverlay();
      $.ajax({
        type: "POST",
        url: "components/deleteNotice.php",
        data: {
          noticeID: noticeID
        },
        success: function (response) {
          console.log(response);
          if (response == "ok") {
            toggleLoadingOverlay();
            $("#notice" + noticeID).remove();
            alert("Notice deleted successfully!");
          }
        }
      })
    }
  }

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
      ]
      // callbacks: {
      //   onInit: function () {
      //     // Move the toolbar after the editor
      //     var toolbar = $('.note-toolbar');
      //     $('.note-editable').after(toolbar);
      //   }
      // }
    });

    $('#editNotice').summernote({
      focus: false,                  // set focus to editable area after initializing summernote
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
      ]
    });
  });

  document.getElementById('newNotice').addEventListener('submit', function (event) {
    event.preventDefault(); // Prevent the default form submission

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