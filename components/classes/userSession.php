<?php
class UserSession
{
  private $conn;
  private $userID;

  // Constructor to initialize the database connection and userID
  public function __construct()
  {
    if (session_status() === PHP_SESSION_NONE) {
      session_start();
    }
    date_default_timezone_set('Asia/Manila');

    include __DIR__ . '/../processes/db_connection.php';
    $this->conn = $conn;

    $this->userID = $_SESSION['userID'] ?? null;
  }

  // save the user details to session to be used for displaying in the profile of employee
  public function saveSessionData()
  {
    $getDetails = $this->conn->prepare("SELECT * FROM user as u INNER JOIN agency as a ON a.agencyID = u.agency WHERE u.userID = ?");
    // echo $this->userID;
    $getDetails->bind_param("i", $this->userID);
    $getDetails->execute();
    $result = $getDetails->get_result();
    $user = $result->fetch_assoc();

    $_SESSION['username'] = $user['username'];
    $_SESSION['userID'] = $user['userID'];
    $_SESSION['prefix'] = $user['prefix'];
    $_SESSION['firstName'] = $user['firstName'];
    $_SESSION['lastName'] = $user['lastName'];
    $_SESSION['middleInitial'] = $user['middleInitial'];
    $_SESSION['suffix'] = $user['suffix'];
    $_SESSION['position'] = $user['position'];
    $_SESSION['initials'] = $user['initials'];
    $_SESSION['role'] = $user['role'];
    $_SESSION['agency'] = $user['agencyName'];

    return true;
  }

  // Method to fetch user details and save to session
  public function login($username, $password)
  {
    try {
      // Prepare query to fetch user details
      $stmt = $this->conn->prepare("SELECT * FROM user WHERE username = ?");
      $stmt->bind_param("s", $username);

      if ($stmt->execute()) {
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
          $user = $result->fetch_assoc();

          // Verify the password
          if ($password === $user['password']) {
            $this->userID = $user['userID'];
            // Store user details in the session
            if ($this->saveSessionData()) {
              $this->rememberme();
              return true;  // Successfully logged in
            } else {
              return false;
            }

          } else {
            return false;
          }
        }
      } else {
        return false;
      }

    } catch (\Throwable $th) {
      return $th;
    }
  }

  // check if the data in the cookie is valid
  public function validateCookie($cookieValue)
  {
    $stmt = $this->conn->prepare("SELECT * FROM user_cookie WHERE cookieName = 'rememberme' AND cookieValue = ?");
    $stmt->bind_param("s", $cookieValue);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();
      $this->userID = $row['userID'];
      $this->saveSessionData();
      return true;
    } else {
      return false;
    }
  }

  // to keep the user logged for 1 year using cookie
  public function rememberme()
  {
    $token = $this->generateUniqueToken();
    $expiration = time() + 86400 * 365;
    $cookieName = "rememberme";

    setcookie($cookieName, $token, $expiration, "/", "", true, true);

    $stmt = $this->conn->prepare("INSERT INTO user_cookie (userID, cookieName, cookieValue, expiration) VALUES (?, ?, ?, ?)");
    $expiresAt = date('Y-m-d H:i:s', $expiration);
    $stmt->bind_param("isss", $this->userID, $cookieName, $token, $expiresAt);
    $stmt->execute();
  }

  // generate new unique token for remember me function
  private function generateUniqueToken()
  {
    $isUnique = false;
    $token = '';

    while (!$isUnique) {
      // Generate a random token (64 bytes, hex-encoded)
      $token = bin2hex(random_bytes(64));

      // Check if the token already exists in the database
      $stmt = $this->conn->prepare("SELECT * FROM user_cookie WHERE cookieValue = ?");
      $stmt->bind_param("s", $token);
      $stmt->execute();
      $result = $stmt->get_result();

      // If no rows are returned, the token is unique
      if ($result->num_rows == 0) {
        $isUnique = true;
      }
    }

    return $token; // Return the unique token
  }

  // Method to validate if a user is logged in
  public function isLoggedIn()
  {
    if (isset($_SESSION['userID'])) {
      $this->saveSessionData();
      return true;
    } else {
      return false;
    }
  }

  // Method to log out the user and destroy the session
  public function logout()
  {
    try {
      session_destroy();
      $this->userID = null;
      $_SESSION = [];
      if (isset($_COOKIE['rememberme'])) {
        $cookieValue = $_COOKIE['rememberme'];

        $stmt = $this->conn->prepare("DELETE FROM user_cookie WHERE cookieValue = ?");
        $stmt->bind_param("s", $cookieValue);
        $stmt->execute();

        setcookie('rememberme', '', time() - 3600, "/"); // Expire the cookie
      }
      return true;
    } catch (\Throwable $th) {
      return $th;
    }
  }

  // Method to fetch user details by userID
  public function fetchUserDetails($userID = null)
  {
    $userID ??= $this->userID;

    if ($userID) {
      $stmt = $this->conn->prepare("SELECT * FROM employee as e INNER JOIN agency as a ON e.agency = a.agencyID WHERE e.userID = ?");
      $stmt->bind_param("i", $userID);

      if ($stmt->execute()) {
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
          $stmt->close();
          return $result->fetch_assoc();  // Return user details
        }
      }
    }
    return null;  // No user found
  }

  private function getAgencyID($agencyName, $sector, $province)
  {
    // global $conn;

    $getEmployeeID = $this->conn->prepare("SELECT * FROM agency WHERE agencyName = ? AND sector = ? AND province = ?");
    $getEmployeeID->bind_param("sss", $agencyName, $sector, $province);
    $getEmployeeID->execute();
    $result = $getEmployeeID->get_result();

    if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();
      return $row['agencyID'];
    } else {
      $saveAgency = $this->conn->prepare("INSERT INTO agency (agencyName, sector, province) VALUES (?, ?, ?)");
      $saveAgency->bind_param("sss", $agencyName, $sector, $province);
      $saveAgency->execute();
      return $this->conn->insert_id;
    }
  }

  // Method to update user details (e.g., email, full name)
  public function updateDetails($updatedDetails)
  {
    if ($this->userID) {
      $prefix = $updatedDetails['prefix'];
      $firstName = $updatedDetails['firstName'];
      $middleInitial = $updatedDetails['middleInitial'];
      $lastName = $updatedDetails['lastName'];
      $suffix = $updatedDetails['suffix'];
      $nickname = $updatedDetails['nickname'];
      $age = $updatedDetails['age'];
      $sex = $updatedDetails['sex'];
      $civilStatus = $updatedDetails['civilStatus'];
      $phoneNumber = $updatedDetails['phoneNumber'];
      $email = $updatedDetails['email'];
      $altEmail = $updatedDetails['altEmail'];
      $sector = $updatedDetails['sector'];
      $agencyName = $updatedDetails['agencyName'];
      $position = $updatedDetails['position'];
      $fo = $updatedDetails['fo'];
      $foodRestriction = $updatedDetails['foodRestriction'];

      $agencyID = $this->getAgencyID($agencyName, $sector, $fo);

      $updateProfileStmt = $this->conn->prepare("
        UPDATE employee 
        SET prefix = ?,
        firstName = ?,
        middleInitial = ?,
        lastName = ?,
        suffix = ?,
        nickname = ?,
        age = ?,
        sex = ?,
        civilStatus = ?,
        phoneNumber = ?,
        email = ?,
        altEmail = ?,
        agency = ?,
        position = ?,
        foodRestriction = ?
        WHERE userID = ?;
      ");

      $updateProfileStmt->bind_param(
        "ssssssssssssssss",
        $prefix,
        $firstName,
        $middleInitial,
        $lastName,
        $suffix,
        $nickname,
        $age,
        $sex,
        $civilStatus,
        $phoneNumber,
        $email,
        $altEmail,
        $agencyID,
        $position,
        $foodRestriction,
        $this->userID
      );

      if ($updateProfileStmt->execute()) {

        $updateUserStmt = $this->conn->prepare("UPDATE user SET prefix = ?, firstName = ?, lastName = ?, suffix = ?, middleInitial = ?, position = ?, agency = ? WHERE userID = ?;");
        $updateUserStmt->bind_param("ssssssss", $prefix, $firstName, $lastName, $suffix, $middleInitial, $position, $agencyID, $this->userID);

        if ($updateUserStmt->execute()) {

          $this->saveSessionData();
          return true;
        }
      } else {
        return false;
      }
    }
    return false;
  }

  public function updateAdminProfile($newProfile)
  {
    if ($_SESSION['role'] == "admin") {
      $updateAdmin = $this->conn->prepare("UPDATE user 
                                            SET prefix = ?, 
                                                firstName = ?, 
                                                lastName = ?, 
                                                suffix = ?,
                                                middleInitial = ?, 
                                                position = ?,
                                                initials = ?,
                                                username = ? WHERE userID = ? ");
      $updateAdmin->bind_param("ssssssssi", $newProfile['prefix'], $newProfile['firstName'], $newProfile['lastName'], $newProfile['suffix'], $newProfile['middleInitial'], $newProfile['position'], $newProfile['initials'], $newProfile['username'], $this->userID);

      if ($updateAdmin->execute()) {
        return $this->saveSessionData();
      }

    } else {
      return false;
    }
  }
}

// Usage Example

// Instantiate the class
// $userSession = new UserSession();