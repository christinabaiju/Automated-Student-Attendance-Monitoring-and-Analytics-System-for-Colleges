<?php
// Connect to MySQL database
$servername = "localhost";
$dbusername = "root";
$dbpassword = "123@123";
$dbname = "attendance_system";

$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Sanitize inputs
  $role = $_POST['role'];
  $username = $conn->real_escape_string($_POST['username']);
  $password = $_POST['password']; // In production, always hash passwords
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  $rollno = $role === 'student' ? $conn->real_escape_string($_POST['rollno']) : NULL;

  if ($role === "student") {
    $stmt = $conn->prepare("INSERT INTO users (role, username, password, rollno) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $role, $username, $hashed_password, $rollno);
  } else {
    $stmt = $conn->prepare("INSERT INTO users (role, username, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $role, $username, $hashed_password);
    header("Location: attendance_admin_dashboard.php");
        exit();
  }

  if ($stmt->execute()) {
    $message = "Marked Attendance!";
  } else {
    $message = "Error: " . $stmt->error;
  }

  $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>ATTENDANCE</title>
  <link rel="stylesheet" href="Login_CSS.css">
</head>
<body>
  <form id="loginForm" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
 <h2>ATTENDANCE</h2>
    <label>
      <input type="radio" name="role" value="student" checked /> Student
    </label>
    <label>
      <input type="radio" name="role" value="admin" /> Admin
    </label>
<br>

    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required />

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required />

    <div id="rollNoSection">
      <label for="rollno">Roll Number:</label>
      <input type="text" id="rollno" name="rollno" />
    </div>
   <br>

    <button type="submit">Submit</button>
  <?php if ($message) { echo "<br><p>$message</p>"; } ?>
  </form>

  <script>
    const studentRadio = document.querySelector('input[name="role"][value="student"]');
    const adminRadio = document.querySelector('input[name="role"][value="admin"]');
    const rollNoSection = document.getElementById('rollNoSection');
    const rollnoInput = document.getElementById('rollno');

    function toggleRollNo() {
      if (studentRadio.checked) {
        rollNoSection.style.display = 'block';
        rollnoInput.required = true;
      } else {
        rollNoSection.style.display = 'none';
        rollnoInput.required = false;
        rollnoInput.value = '';
      }
    }

    toggleRollNo();

    studentRadio.addEventListener('change', toggleRollNo);
    adminRadio.addEventListener('change', toggleRollNo);
  </script>
</body>
</html>
