<?php
// attendance_dashboard.php
// CSV export on ?action=fetch_csv
if(isset($_GET['action']) && $_GET['action'] === 'fetch_csv') {
    $servername = "localhost";
    $dbusername = "root";
    $dbpassword = "123@123";
    $dbname = "attendance_system";

    $conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="user_attendance.csv"');

    $output = fopen('php://output', 'w');
    fputcsv($output, ['ID', 'Role', 'Username', 'Roll Number']);

    $result = $conn->query("SELECT id, role, username, IFNULL(rollno, '') AS rollno FROM users");
    while ($row = $result->fetch_assoc()) {
        fputcsv($output, $row);
    }
    fclose($output);
    exit;
}
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
    position: relative;
  }
  .dashboard {
    background: rgba(255,255,255,0.95);
    padding: 20px;
    border-radius: 12px;
    width: 100%;
    max-width: 700px;
    box-shadow: 0 10px 20px rgba(0,0,0,0.15);
    text-align: center;
    position: relative;
  }
  h1 {
    margin-bottom: 0.5rem;
    font-weight: 700;
    color: #4b2e05;
  }
  #date {
    margin-bottom: 2rem;
    font-weight: 500;
    color: #6f5846;
  }
  #attendanceChart {
    max-width: 300px;
    max-height: 300px;
    margin: 0 auto 1.5rem;
    display: block;
  }
  .stats {
    display: flex;
    justify-content: space-around;
    margin-bottom: 2rem;
    font-weight: 600;
  }
  .stats div {
    font-size: 1.1rem;
  }
  #downloadCSV {
    background-color: #4b2e05;
    color: #f5f5dc;
    border: none;
    padding: 10px 20px;
    border-radius: 10px;
    cursor: pointer;
    font-weight: 700;
    font-size: 1rem;
    transition: background-color 0.3s ease;
  }
  #downloadCSV:hover {
    background-color: #3a2303;
  }
  /* Profile dropdown styling */
  .profile {
    position: fixed;
    top: 20px;
    right: 20px;
    user-select: none;
  }
  .profile-icon {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    background: #4b2e05;
    color: #f5f5dc;
    font-weight: bold;
    font-size: 24px;
    line-height: 44px;
    text-align: center;
    cursor: pointer;
    box-shadow: 0 2px 8px rgba(0,0,0,0.3);
  }
  .dropdown {
    position: absolute;
    top: 50px;
    right: 0;
    background: white;
    color: #222;
    border-radius: 8px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.2);
    border: 1px solid #ddd;
    width: 180px;
    display: none;
    flex-direction: column;
    padding: 8px 0;
    z-index: 1000;
  }
  .dropdown a {
    padding: 10px 20px;
    text-decoration: none;
    color: #333;
    font-weight: 600;
    transition: background 0.2s ease;
  }
  .dropdown a:hover {
    background: #f0eee9;
  }
</style>
</head>
<body>
  <div class="profile" tabindex="0">
    <div class="profile-icon" id="profileIcon">U</div>
    <div class="dropdown" id="profileDropdown">
      <a href="#">Profile Settings</a>
      <a href="#">FAQs</a>
    </div>
  </div>
  <div class="dashboard">
    <h1>Welcome, To the Attendance Tracker</h1>
    <div id="date"></div>

    <canvas id="attendanceChart"></canvas>

    <div class="stats">
      <div><span id="presentCount">0</span> Present</div>
      <div><span id="absentCount">0</span> Absent</div>
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
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric',
      });
    }
    updateDate();

    async function fetchAttendanceData() {
      // Simulated data; replace with backend API call if needed
      return new Promise((resolve) => {
        setTimeout(() => {
          resolve([
            { id: 1, status: 'Present' },
            { id: 2, status: 'Absent' },
            { id: 3, status: 'Present' },
            { id: 4, status: 'Present' },
            { id: 5, status: 'Absent' },
          ]);
        }, 500);
      });
    }

    async function setupDashboard() {
      const data = await fetchAttendanceData();

      const presentCount = data.filter((item) => item.status === 'Present').length;
      const absentCount = data.filter((item) => item.status === 'Absent').length;

      presentEl.textContent = presentCount;
      absentEl.textContent = absentCount;

      const ctx = document.getElementById('attendanceChart').getContext('2d');
      new Chart(ctx, {
        type: 'doughnut',
        data: {
          labels: ['Present', 'Absent'],
          datasets: [
            {
              data: [presentCount, absentCount],
              backgroundColor: ['#4b2e05', '#000000'],
            },
          ],
        },
      });
    }

    setupDashboard();

    document.getElementById('downloadCSV').addEventListener('click', () => {
      window.location.href = '?action=fetch_csv';
    });

    // Profile dropdown toggle
    const profileIcon = document.getElementById('profileIcon');
    const profileDropdown = document.getElementById('profileDropdown');

    profileIcon.addEventListener('click', () => {
      if (profileDropdown.style.display === 'flex') {
        profileDropdown.style.display = 'none';
      } else {
        profileDropdown.style.display = 'flex';
      }
    });

    // Close dropdown if click outside
    document.addEventListener('click', (event) => {
      if (!profileIcon.contains(event.target) && !profileDropdown.contains(event.target)) {
        profileDropdown.style.display = 'none';
      }
    });
  </script>
</body>
</html>
