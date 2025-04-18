<?php include('partials/headerSection.php')?>

<link rel="stylesheet" href="./swiper-bundle.min.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="main">
    <?php include('partials/sideMenu.php')?>

    <div class="mainContent">
        <div class="topSection flex">
            <div class="dashboardTitle">
                <h1>Analytics Dashboard</h1>
            </div>

            <div class="userBox flex">
                <a href="index.php">
                    <div class="adminImage">
                        <img src="./assets/images/pp.jpg" alt="Admin Image">
                    </div>
                </a>
                <div class="userName">
                    <span>Administrator</span>
                    <small><?php 
                        if(isset($_SESSION['firstName'])){
                            echo $_SESSION['firstName'];
                        }
                    ?></small>
                </div>
                <i class="uil uil-bell icon"></i>
            </div>
        </div>

        <div class="body">
            <div class="overViewDiv">
                <div class="intro flex">
                    <h3 class="title">Analytics Overview</h3>
                    <div class="flex">
                        <div class="addBtn">
                            <a href="dashboard.php">
                                <span>Back to Dashboard</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <?php 
                $sql = "SELECT * FROM cases_table";
                $res = mysqli_query($conn, $sql);
                if ($res == true){
                    $caseCount = mysqli_num_rows($res);
                }
                $sql2 = "SELECT * FROM cases_table WHERE case_status ='Open'";
                $res2 = mysqli_query($conn, $sql2);
                if ($res2 == true){
                    $openCases = mysqli_num_rows($res2);
                }
                $sql3 = "SELECT * FROM cases_table WHERE case_status ='Closed'";
                $res3 = mysqli_query($conn, $sql3);
                if ($res3 == true){
                    $closedCases = mysqli_num_rows($res3);
                }

                $sql4 = "SELECT incident_type, COUNT(*) as count FROM cases_table GROUP BY incident_type";
                $res4 = mysqli_query($conn, $sql4);
                $incidentTypeLabels = [];
                $incidentTypeCounts = [];
                if ($res4 == true){
                    while($row = mysqli_fetch_assoc($res4)){
                        $incidentTypeLabels[] = $row['incident_type'];
                        $incidentTypeCounts[] = $row['count'];
                    }
                }

                $sql5 = "SELECT MONTH(incident_date) as month, COUNT(*) as count FROM cases_table WHERE incident_date IS NOT NULL GROUP BY MONTH(incident_date) ORDER BY month";
                $res5 = mysqli_query($conn, $sql5);
                $monthLabels = [];
                $monthlyCounts = [];
                if ($res5 == true){
                    while($row = mysqli_fetch_assoc($res5)){
                        $monthNum = $row['month'];
                        if($monthNum >= 1 && $monthNum <= 12) {
                            $dateObj = DateTime::createFromFormat('!m', $monthNum);
                            if($dateObj !== false) {
                                $monthName = $dateObj->format('F');
                                $monthLabels[] = $monthName;
                                $monthlyCounts[] = $row['count'];
                            }
                        }
                    }
                }
            ?>

            <div class="analyticsGrid">
                <div class="chartBox">
                    <h3>Case Status Distribution</h3>
                    <div class="chartContainer">
                        <canvas id="statusChart"></canvas>
                    </div>
                </div>
                
                <div class="chartBox">
                    <h3>Incident Types Distribution</h3>
                    <div class="chartContainer">
                        <canvas id="incidentTypeChart"></canvas>
                    </div>
                </div>
                
                <div class="chartBox wide">
                    <h3>Cases Reported by Month</h3>
                    <div class="chartContainer">
                        <canvas id="monthlyChart"></canvas>
                    </div>
                </div>
                
                <div class="chartBox wide">
                    <h3>Recent Case Summary</h3>
                    <div class="recentCasesSummary">
                        <table>
                            <thead>
                                <tr>
                                    <th>Case ID</th>
                                    <th>Incident Type</th>
                                    <th>Date Reported</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $sql6 = "SELECT * FROM cases_table ORDER BY incident_date DESC LIMIT 5";
                                    $res6 = mysqli_query($conn, $sql6);
                                    if ($res6 == true){
                                        while($row = mysqli_fetch_assoc($res6)){
                                            ?>
                                            <tr>
                                                <td><?php echo $row['id']; ?></td>
                                                <td><?php echo $row['incident_type']; ?></td>
                                                <td><?php echo $row['incident_date']; ?></td>
                                                <td><?php echo $row['case_status']; ?></td>
                                                <td>
                                                    <a href="details.php?id=<?php echo $row['id']; ?>" class="viewBtn">View</a>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                    }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Case Status Chart
        const statusCtx = document.getElementById('statusChart').getContext('2d');
        const statusChart = new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: ['Open', 'Closed'],
                datasets: [{
                    data: [<?php echo $openCases; ?>, <?php echo $closedCases; ?>],
                    backgroundColor: ['#4e73df', '#1cc88a'],
                    hoverBackgroundColor: ['#2e59d9', '#17a673'],
                    hoverBorderColor: "rgba(234, 236, 244, 1)",
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });

        // Incident Type Chart
        const incidentTypeCtx = document.getElementById('incidentTypeChart').getContext('2d');
        const incidentTypeChart = new Chart(incidentTypeCtx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($incidentTypeLabels); ?>,
                datasets: [{
                    label: 'Number of Cases',
                    data: <?php echo json_encode($incidentTypeCounts); ?>,
                    backgroundColor: '#36b9cc',
                    hoverBackgroundColor: '#2c9faf',
                    borderColor: '#36b9cc',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                }
            }
        });

        // Monthly Chart
        const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
        const monthlyChart = new Chart(monthlyCtx, {
            type: 'line',
            data: {
                labels: <?php echo json_encode($monthLabels); ?>,
                datasets: [{
                    label: 'Cases per Month',
                    data: <?php echo json_encode($monthlyCounts); ?>,
                    backgroundColor: 'rgba(78, 115, 223, 0.05)',
                    borderColor: 'rgba(78, 115, 223, 1)',
                    pointRadius: 3,
                    pointBackgroundColor: 'rgba(78, 115, 223, 1)',
                    pointBorderColor: 'rgba(78, 115, 223, 1)',
                    pointHoverRadius: 5,
                    pointHoverBackgroundColor: 'rgba(78, 115, 223, 1)',
                    pointHoverBorderColor: 'rgba(78, 115, 223, 1)',
                    pointHitRadius: 10,
                    pointBorderWidth: 2,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                }
            }
        });
    });
</script>

<style>
    html, body {
        height: 100%;
        overflow-y: auto;
    }
    
    .main {
        height: 100%;
        overflow-y: auto;
    }
    
    .mainContent {
        height: auto;
        min-height: 100vh;
        overflow-y: auto;
    }
    
    .body {
        height: auto;
        overflow-y: auto;
        padding-bottom: 40px;
    }
    
    .analyticsGrid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        grid-gap: 20px;
        margin-top: 20px;
    }
    
    .chartBox {
        background: white;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        padding: 20px;
        position: relative;
    }
    
    .chartBox h3 {
        margin-top: 0;
        margin-bottom: 15px;
        font-size: 1.2rem;
        color: #333;
    }
    
    .chartContainer {
        height: 300px;
        position: relative;
    }
    
    .wide {
        grid-column: span 2;
    }
    
    .recentCasesSummary {
        overflow-x: auto;
    }
    
    .recentCasesSummary table {
        width: 100%;
        border-collapse: collapse;
    }
    
    .recentCasesSummary th, 
    .recentCasesSummary td {
        padding: 12px 15px;
        text-align: left;
        border-bottom: 1px solid #e3e6f0;
    }
    
    .recentCasesSummary th {
        background-color: #f8f9fc;
        font-weight: 600;
        color: #4e73df;
    }
    
    .recentCasesSummary tr:hover {
        background-color: #f8f9fc;
    }
    
    .viewBtn {
        display: inline-block;
        padding: 5px 10px;
        background: #4e73df;
        color: white;
        border-radius: 4px;
        text-decoration: none;
        font-size: 0.8rem;
    }
    
    .viewBtn:hover {
        background: #2e59d9;
    }
    
    @media screen and (max-width: 768px) {
        .analyticsGrid {
            grid-template-columns: 1fr;
        }
        
        .wide {
            grid-column: span 1;
        }
    }
</style>

<?php include('partials/footer.php')?> 