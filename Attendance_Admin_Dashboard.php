<?php
// attendance_dashboard.php

// CSV export if requested
if(isset($_GET['action']) && $_GET['action'] === 'fetch_csv') {
    $servername = "localhost";
    $dbusername = "root";
    $dbpassword = "123@123";
    $dbname = "attendance_system";

    $conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);
    if ($conn->connect_error) { die("Connection failed: " . $conn->connect_error); }

    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="Student_attendance.csv"');

    $output = fopen('php://output', 'w');
    fputcsv($output, ['ID', 'Role', 'Username', 'Roll Number']);
    $result = $conn->query("SELECT id, role, username, IFNULL(rollno, '') AS rollno FROM users");
    while ($row = $result->fetch_assoc()) {
        fputcsv($output, $row);
    }
    fclose($output);
    exit;
}

$servername = "localhost";
$dbusername = "root";
$dbpassword = "123@123";
$dbname = "attendance_system";

$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);
if ($conn->connect_error) { die("Connection failed: " . $conn->connect_error); }

$today = date('Y-m-d');
$totalStudents = 61;

// Query to count students marked Present today
$sql_present = "SELECT COUNT(DISTINCT u.id) AS presentCount 
                FROM users u 
                LEFT JOIN attendance a ON u.id = a.user_id AND a.date = ? AND a.status = 'Present'";
$stmt = $conn->prepare($sql_present);
$stmt->bind_param("s", $today);
$stmt->execute();
$stmt->bind_result($presentCount);
$stmt->fetch();
$stmt->close();

$absentCount = max(0, $totalStudents - $presentCount);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Simple Attendance Dashboard</title>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
body {
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  background: linear-gradient(135deg, #000000, #4b2e05, #f5f5dc);
  margin: 0;
  padding: 1rem;
  min-height: 100vh;
  color: #222;
  display: flex;
  justify-content: center;
  align-items: flex-start;
}

.dashboard {
  background: rgba(255, 255, 255, 0.95);
  padding: 15px 15px 25px;
  border-radius: 12px;
  width: 90%;
  max-width: 450px; /* Smaller max width */
  box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
  text-align: center;
  max-width: 450px; /* or desired width */
  margin: 2rem auto; /* centers horizontally */
  /* no additional right margin or padding */
}

h1 {
  margin-bottom: 0.5rem;
  font-weight: 700;
  color: #4b2e05;
  font-size: 1.5rem; /* Smaller heading */
}

#date {
  margin-bottom: 1.5rem; /* Reduced margin */
  font-weight: 500;
  color: #6f5846;
  font-size: 1rem;
}

#attendanceChart {
  width: 100% !important;
  max-width: 300px; /* Smaller max width */
  height: auto !important;
  max-height: 300px; /* Smaller max height */
  margin: 0 auto 1.5rem;
  display: block;
}

.stats {
  display: flex;
  justify-content: space-around;
  margin-bottom: 1.5rem; /* Reduced margin */
  font-weight: 600;
}

.stats div {
  font-size: 1rem; /* Slightly smaller font size */
}

#downloadCSV {
  background-color: #4b2e05;
  color: #f5f5dc;
  border: none;
  padding: 8px 18px;
  border-radius: 8px;
  cursor: pointer;
  font-weight: 700;
  font-size: 0.9rem;
  transition: background-color 0.3s ease;
}

#downloadCSV:hover {
  background-color: #3a2303;
}

</style>
</head>
<body>
<div class="dashboard">
  <h1>Welcome, To the Attendance Tracker</h1>
  <div id="date"></div>

  <canvas id="attendanceChart"></canvas>

  <div class="stats">
    <div><span id="presentCount"><?php echo $presentCount; ?></span> Present</div>
    <div><span id="absentCount"><?php echo $absentCount; ?></span> Absent</div>
  </div>

  <button id="downloadCSV">Download Attendance</button>
</div>

<script>
  const dateEl = document.getElementById('date');
  const presentEl = document.getElementById('presentCount');
  const absentEl = document.getElementById('absentCount');

  function updateDate() {
    const today = new Date();
    dateEl.textContent = today.toLocaleDateString(undefined, {
      weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'
    });
  }
  updateDate();

  function setupDashboard() {
    const presentCount = parseInt(presentEl.textContent);
    const absentCount = parseInt(absentEl.textContent);

    const ctx = document.getElementById('attendanceChart').getContext('2d');
    new Chart(ctx, {
      type: 'doughnut',
      data: {
        labels: ['Present', 'Absent'],
        datasets: [{
          data: [presentCount, absentCount],
          backgroundColor: ['#4b2e05', '#000000'],
        }],
      },
      options: {
        responsive: true,
        plugins: {
          legend: { position: 'bottom' },
          title: { display: true, text: 'Attendance Today' }
        }
      }
    });
  }
  setupDashboard();

  document.getElementById('downloadCSV').addEventListener('click', () => {
    window.location.href = '?action=fetch_csv';
  });

</script>
</body>
</html>