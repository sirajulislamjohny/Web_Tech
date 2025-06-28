<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Analytics - Admin Dashboard</title>
  <style>
    body {
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
      background-color: #f2f2f2;
    }
    .sidebar {
      position: fixed;
      width: 200px;
      height: 100vh;
      background-color: #66cc66;
      padding-top: 20px;
      display: flex;
      flex-direction: column;
      align-items: center;
    }
    .sidebar img {
      width: 100px;
      border-radius: 50%;
      margin-bottom: 20px;
    }
    .sidebar a {
      text-decoration: none;
      width: 120px;
      margin: 10px 0;
      padding: 10px;
      border-radius: 8px;
      color: white;
      font-weight: bold;
      text-align: center;
      display: block;
    }
    .analytic { background-color: #f8e561; color: #333; }
    .main {
      margin-left: 220px;
      padding: 20px;
    }
    .header h1 {
      background-color: #7d91f1;
      padding: 10px 20px;
      border-radius: 15px;
      color: white;
    }
    .charts {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
      gap: 20px;
      margin-top: 20px;
    }
    .chart-card {
      background-color: white;
      padding: 20px;
      border-radius: 15px;
      box-shadow: 0 2px 5px rgba(0,0,0,0.1);
      text-align: center;
    }
    .footer {
      margin-top: 40px;
      text-align: center;
      color: #666;
    }
  </style>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
  <div class="sidebar">
     <img src="Admin-Profile.png" alt="Admin" onclick="window.location.href='DashBoard.php'" style="cursor:pointer;" />
    <a href="inbox.php" class="inbox">Inbox</a>
    <a href="add_inventory.php" class="updates">Inventory</a>
    <a href="#" class="analytic">Analytics</a>
    <a href="patient_info.php" class="patient">Patient Info</a>
    <a href="doctor-info.php" class="doctor">Doctor Info</a>
    <button class="logout" id="logoutBtn">Log Out</button>
  </div>

  <div class="main">
    <div class="header">
      <h1>Analytics Overview</h1>
    </div>

    <div class="charts">
      <div class="chart-card">
        <h3>Patient Visits</h3>
        <canvas id="patientVisitsChart" width="300" height="200"></canvas>
      </div>

      <div class="chart-card">
        <h3>Inventory Usage</h3>
        <canvas id="inventoryUsageChart" width="300" height="200"></canvas>
      </div>

      <div class="chart-card">
        <h3>Doctor Availability</h3>
        <canvas id="doctorAvailabilityChart" width="300" height="200"></canvas>
      </div>
    </div>

    <div class="footer">
      <p>&copy; 2025 Sky Hospital. All rights reserved.</p>
    </div>
  </div>

  <script>
    // Logout button
    document.getElementById('logoutBtn').addEventListener('click', function () {
      if (confirm('Are you sure you want to log out?')) {
        window.location.href = 'index.html';
      }
    });

    // Fetch patient visits data from PHP and render chart
    fetch('fetch_data.php')
      .then(response => response.json())
      .then(data => {
        if(data.error) {
          alert(data.error);
          return;
        }
        
        const ctx = document.getElementById('patientVisitsChart').getContext('2d');
        new Chart(ctx, {
          type: 'bar',
          data: {
            labels: data.labels,
            datasets: [{
              label: 'Visits',
              data: data.visits,
              backgroundColor: 'rgba(54, 162, 235, 0.6)'
            }]
          },
          options: {
            responsive: true,
            scales: {
              y: {
                beginAtZero: true,
                precision: 0
              }
            }
          }
        });
      })
      .catch(error => console.error('Error fetching data:', error));

    // For demonstration, hardcode inventory and doctor data (you can also fetch from backend similarly)
    const inventoryCtx = document.getElementById('inventoryUsageChart').getContext('2d');
    new Chart(inventoryCtx, {
      type: 'line',
      data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May'],
        datasets: [{
          label: 'Items Used',
          data: [50, 40, 60, 70, 30],
          borderColor: 'rgba(255, 99, 132, 0.7)',
          fill: false,
          tension: 0.1
        }]
      }
    });

    const doctorCtx = document.getElementById('doctorAvailabilityChart').getContext('2d');
    new Chart(doctorCtx, {
      type: 'doughnut',
      data: {
        labels: ['Available', 'Busy', 'On Leave'],
        datasets: [{
          label: 'Doctor Availability',
          data: [60, 25, 15],
          backgroundColor: [
            'rgba(75, 192, 192, 0.7)',
            'rgba(255, 206, 86, 0.7)',
            'rgba(255, 99, 132, 0.7)'
          ]
        }]
      }
    });

  </script>
</body>
</html>
