<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Admin Dashboard</title>
  <style>
    body {
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
      background-color: #66cc66;
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
    .inbox { background-color: #647cfb; }
    .updates { background-color: #5bf576; }
    .analytic { background-color: #f8e561; color: #333; }
    .patient { background-color: #7d3ac1; }
    .doctor { background-color: #c29f58; }
    .logout {
      background-color: #e8483b;
      color: white;
      font-weight: bold;
      width: 120px;
      margin: 10px 0;
      padding: 10px;
      border-radius: 8px;
      border: none;
      cursor: pointer;
      text-align: center;
      font-family: 'Segoe UI', sans-serif;
      display: block;
    }
    .logout:hover {
      background-color: #c9302c;
    }
    .main {
      margin-left: 220px;
      padding: 20px;
    }
    .header {
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    .header-left {
      display: flex;
      align-items: center;
      gap: 20px;
    }
    .header h1 {
      background-color: #7d91f1;
      padding: 10px 20px;
      border-radius: 15px;
      color: white;
    }
    .search-bar {
      display: flex;
      align-items: center;
      gap: 10px;
    }
    .search-bar input {
      padding: 8px;
      border-radius: 8px;
      border: none;
      width: 200px;
    }
    .search-bar button {
      padding: 8px 12px;
      border-radius: 8px;
      border: none;
      background-color: #4a90e2;
      color: white;
      cursor: pointer;
      font-weight: bold;
    }
    .grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 20px;
      margin-top: 30px;
    }
    .card {
      background-color: white;
      border-radius: 15px;
      padding: 20px;
    }
    .notif {
      background-color: #00ffe7;
      padding: 10px;
      border-radius: 8px;
      margin-bottom: 10px;
      color: #000;
    }
    .section-title {
      text-align: center;
      margin-top: 10px;
      font-weight: bold;
    }
    .bar-chart {
      height: 250px;
      display: flex;
      align-items: flex-end;
      gap: 10px;
      justify-content: space-around;
      margin-top: 20px;
    }
    .bar {
      width: 40px;
      background-color: #6a4fd6;
      color: white;
      text-align: center;
      font-size: 12px;
      display: flex;
      align-items: flex-end;
      justify-content: center;
      padding-bottom: 5px;
    }
    .footer {
      margin-top: 40px;
      text-align: center;
      color: white;
    }
    .appointment-card {
      background-color: white;
      border-radius: 15px;
      padding: 20px;
      margin-top: 30px;
    }
    .review-card {
      background-color: white;
      border-radius: 15px;
      padding: 20px;
      margin-top: 20px;
      display: flex;
      justify-content: center;
    }
    .settings {
      position: absolute;
      top: 20px;
      right: 20px;
      display: flex;
      align-items: center;
      gap: 10px;
    }
    .inventory-card {
      grid-column: span 2;
    }
  </style>
</head>
<body>
  <div class="sidebar">
   <img src="Admin-Profile.png" alt="Admin" onclick="window.location.href='DashBoard.php'" style="cursor:pointer;" />
    <a href="inbox.php" class="inbox">Inbox</a>
    <a href="add_inventory.php" class="updates">Inventory</a>
    <a href="analytics.php" class="analytic">Analysic</a>
    <a href="patient_info.php" class="patient">Patient Info</a>
    <a href="doctor-info.php" class="doctor">Doctor Info</a>
    <button class="logout" id="logoutBtn" >Log Out</button>
  </div>

  <div class="main">
    <div class="header">
      <div class="header-left">
        <h1>Admin Dashboard</h1>
        <div class="search-bar">
          <input type="text" placeholder="Search..." id="searchInput" />
          <button onclick="performSearch()">Search</button>
        </div>
      </div>
      <div class="settings">
        <img src="https://cdn-icons-png.flaticon.com/512/3524/3524659.png" width="24" alt="Settings"/>
        <span>Settings</span>
      </div>
    </div>

    <div class="grid">
      <div>
        <div class="card">
          <div class="notif">Mr. Aslam<br/>wants to fix appointment with you<br/>5 min ago</div>
          <div class="notif">Mr. Karim<br/>wants to fix appointment with you<br/>10 min ago</div>
        </div>
        <div class="section-title">Patient Data</div>
      </div>
      <div>
        <div class="card">
          <div class="notif">Dr. Aysha<br/>Accepted appointment<br/>5 min ago</div>
          <div class="notif">Dr. Sharmin<br/>Accepted appointment<br/>5 min ago</div>
        </div>
        <div class="section-title">Doctor Data</div>
      </div>
      <div>
        <div class="card">
          <div class="notif">Receive - 17/06/2025 02:30 - +3 Taka</div>
          <div class="notif">Receive - 17/06/2025 02:30 - +5 Taka</div>
          <div class="notif">Receive - 17/06/2025 02:30 - +7 Taka</div>
          <div class="notif">Receive - 17/06/2025 02:30 - +5 Taka</div>
        </div>
        <div class="section-title">Transaction Data</div>
      </div>
      <div class="inventory-card">
        <div class="card">
          <h4>Inventory Available</h4>
          <div class="bar-chart">
            <div class="bar" style="height: 50px;">Masks</div>
            <div class="bar" style="height: 70px;">Gloves</div>
            <div class="bar" style="height: 100px;">Syringes</div>
            <div class="bar" style="height: 60px;">Bottles</div>
            <div class="bar" style="height: 90px;">PPE</div>
          </div>
        </div>
        <div style="text-align:center; margin-top:10px; font-weight:500;">Medical Inventory </div>
      </div>
    </div>

    <div class="appointment-card">
      <div class="notif">Karim <br/>Heart Patient<br/><b>5:00pm to 5:30pm</b></div>
      <div class="notif">Mr. Safkat <br/>Heart Patient<br/><b>5:00pm to 5:30pm</b></div>
      <div class="notif">Rofik <br/>Heart Patient<br/><b>5:00pm to 5:30pm</b></div>
      <div class="section-title">Doctor Appointments</div>
    </div>

    <div class="review-card">
      <img src="https://quickchart.io/chart?c={type:'pie',data:{labels:['Good','Average','Best'],datasets:[{data:[45,10,45]}]}}" width="300" alt="Review Chart" />
    </div>
    <div class="section-title">Patient Review</div>

    <div class="footer">
      <p>&copy; 2025 Sky Hospital. All rights reserved.</p>
    </div>
  </div>

  <script>
    function performSearch() {
      const query = document.getElementById('searchInput').value.toLowerCase();
      const notifs = document.querySelectorAll('.notif');
      notifs.forEach(n => {
        const text = n.innerText.toLowerCase();
        n.style.display = text.includes(query) ? 'block' : 'none';
      });
    }

    document.getElementById('logoutBtn').addEventListener('click', function() {
    const confirmed = confirm('Are you sure you want to log out?');
    if (confirmed) {
      window.location.href = 'index.html';
    }
  });

  </script>
</body>
</html>
