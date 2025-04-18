<?php include('partials/headerSection.php')?>

<div class="main">
    <?php include('partials/sideMenu.php')?>

    <div class="mainContent">
        <div class="topSection flex">
            <div class="dashboardTitle">
                <h1>Advanced Search</h1>
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
                    <h3 class="title">Find Cases with Advanced Filters</h3>
                    <div class="flex">
                        <div class="addBtn">
                            <a href="cases.php">
                                <span>All Cases</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="searchContainer">
                <form action="" method="GET" class="advancedSearchForm">
                    <div class="searchFiltersGrid">
                        <div class="filterGroup">
                            <label for="keyword">Keyword Search</label>
                            <div class="searchBox">
                                <input type="text" name="keyword" id="keyword" placeholder="Search in case descriptions...">
                                <i class="uil uil-search"></i>
                            </div>
                        </div>

                        <div class="filterGroup">
                            <label for="incident_type">Incident Type</label>
                            <select name="incident_type" id="incident_type">
                                <option value="">All Types</option>
                                <?php 
                                $sql = "SELECT DISTINCT incident_type FROM cases_table ORDER BY incident_type";
                                $res = mysqli_query($conn, $sql);
                                if($res == TRUE){
                                    while($row = mysqli_fetch_assoc($res)){
                                        echo '<option value="'.$row['incident_type'].'">'.$row['incident_type'].'</option>';
                                    }
                                }
                                ?>
                            </select>
                        </div>

                        <div class="filterGroup">
                            <label for="status">Case Status</label>
                            <select name="status" id="status">
                                <option value="">All Statuses</option>
                                <option value="Open">Open</option>
                                <option value="Closed">Closed</option>
                            </select>
                        </div>

                        <div class="filterGroup">
                            <label for="date_from">Date From</label>
                            <input type="date" name="date_from" id="date_from">
                        </div>

                        <div class="filterGroup">
                            <label for="date_to">Date To</label>
                            <input type="date" name="date_to" id="date_to">
                        </div>

                        <div class="filterGroup">
                            <label for="location">Location</label>
                            <input type="text" name="location" id="location" placeholder="Filter by location...">
                        </div>

                        <div class="filterGroup">
                            <label for="reported_by">Reported By</label>
                            <input type="text" name="reported_by" id="reported_by" placeholder="Filter by reporter...">
                        </div>

                        <div class="filterGroup">
                            <label for="category">Incident Category</label>
                            <select name="category" id="category">
                                <option value="">All Categories</option>
                                <option value="Minor">Minor Incident</option>
                                <option value="Modorate">Modorate Incident</option>
                                <option value="Major">Major Incident</option>
                            </select>
                        </div>
                    </div>

                    <div class="searchButtonsRow">
                        <button type="submit" class="searchBtn">
                            <i class="uil uil-search"></i> Search
                        </button>
                        <button type="reset" class="resetBtn">
                            <i class="uil uil-refresh"></i> Reset Filters
                        </button>
                    </div>
                </form>

                <div class="searchResultsContainer">
                    <h3 class="resultTitle">
                        <?php
                        if(isset($_GET['keyword']) || isset($_GET['incident_type']) || isset($_GET['status']) || 
                           isset($_GET['date_from']) || isset($_GET['date_to']) || isset($_GET['location']) || 
                           isset($_GET['reported_by']) || isset($_GET['category'])) {
                            
                            $conditions = [];
                            $params = [];
                            
                            // Building query conditions based on filters
                            if(!empty($_GET['keyword'])) {
                                $keyword = $_GET['keyword'];
                                $conditions[] = "(description LIKE '%$keyword%' OR action_taken LIKE '%$keyword%')";
                            }
                            
                            if(!empty($_GET['incident_type'])) {
                                $incident_type = $_GET['incident_type'];
                                $conditions[] = "incident_type = '$incident_type'";
                            }
                            
                            if(!empty($_GET['status'])) {
                                $status = $_GET['status'];
                                $conditions[] = "case_status = '$status'";
                            }
                            
                            if(!empty($_GET['date_from'])) {
                                $date_from = $_GET['date_from'];
                                $conditions[] = "incident_date >= '$date_from'";
                            }
                            
                            if(!empty($_GET['date_to'])) {
                                $date_to = $_GET['date_to'];
                                $conditions[] = "incident_date <= '$date_to'";
                            }
                            
                            if(!empty($_GET['location'])) {
                                $location = $_GET['location'];
                                $conditions[] = "location LIKE '%$location%'";
                            }
                            
                            if(!empty($_GET['reported_by'])) {
                                $reported_by = $_GET['reported_by'];
                                $conditions[] = "reported_by LIKE '%$reported_by%'";
                            }
                            
                            if(!empty($_GET['category'])) {
                                $category = $_GET['category'];
                                $conditions[] = "incident_category = '$category'";
                            }
                            
                            // Construct the WHERE clause
                            $whereClause = !empty($conditions) ? "WHERE " . implode(" AND ", $conditions) : "";
                            
                            // Construct the final SQL query
                            $sql = "SELECT * FROM cases_table $whereClause ORDER BY incident_date DESC";
                            $res = mysqli_query($conn, $sql);
                            $count = mysqli_num_rows($res);
                            
                            echo "Found $count results for your search";
                        } else {
                            echo "Use filters above to search cases";
                        }
                        ?>
                    </h3>

                    <?php if(isset($res) && mysqli_num_rows($res) > 0): ?>
                    <div class="tableResponsive">
                        <table class="resultsTable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Incident Type</th>
                                    <th>Location</th>
                                    <th>Date Reported</th>
                                    <th>Status</th>
                                    <th>Category</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($row = mysqli_fetch_assoc($res)): ?>
                                <tr>
                                    <td><?php echo $row['id']; ?></td>
                                    <td><?php echo $row['incident_type']; ?></td>
                                    <td><?php echo $row['location']; ?></td>
                                    <td><?php echo $row['incident_date']; ?></td>
                                    <td>
                                        <span class="statusBadge <?php echo strtolower($row['case_status']); ?>">
                                            <?php echo $row['case_status']; ?>
                                        </span>
                                    </td>
                                    <td><?php echo $row['incident_category']; ?></td>
                                    <td>
                                        <a href="details.php?id=<?php echo $row['id']; ?>" class="viewBtn">
                                            <i class="uil uil-eye"></i> View
                                        </a>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php elseif(isset($res)): ?>
                    <div class="noResults">
                        <i class="uil uil-search-alt"></i>
                        <p>No cases found matching your criteria.</p>
                        <p>Try adjusting your filters or <a href="advanced-search.php">clear all filters</a>.</p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .searchContainer {
        margin-top: 20px;
    }
    
    .advancedSearchForm {
        background: white;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        margin-bottom: 20px;
    }
    
    .searchFiltersGrid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 20px;
    }
    
    .filterGroup {
        margin-bottom: 10px;
    }
    
    .filterGroup label {
        display: block;
        margin-bottom: 5px;
        font-weight: 500;
        color: #333;
    }
    
    .filterGroup input,
    .filterGroup select {
        width: 100%;
        padding: 10px;
        border: 1px solid #e3e6f0;
        border-radius: 5px;
        font-size: 14px;
    }
    
    .searchBox {
        position: relative;
    }
    
    .searchBox input {
        padding-right: 40px;
    }
    
    .searchBox i {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        color: #4e73df;
    }
    
    .searchButtonsRow {
        display: flex;
        gap: 10px;
        margin-top: 20px;
    }
    
    .searchBtn, 
    .resetBtn {
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        font-weight: 500;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 5px;
    }
    
    .searchBtn {
        background-color: #4e73df;
        color: white;
    }
    
    .resetBtn {
        background-color: #f8f9fc;
        color: #5a5c69;
        border: 1px solid #e3e6f0;
    }
    
    .searchBtn:hover {
        background-color: #2e59d9;
    }
    
    .resetBtn:hover {
        background-color: #eaecf4;
    }
    
    .searchResultsContainer {
        background: white;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }
    
    .resultTitle {
        margin-top: 0;
        margin-bottom: 20px;
        color: #333;
        font-size: 1.1rem;
    }
    
    .tableResponsive {
        overflow-x: auto;
    }
    
    .resultsTable {
        width: 100%;
        border-collapse: collapse;
    }
    
    .resultsTable th, 
    .resultsTable td {
        padding: 12px 15px;
        text-align: left;
        border-bottom: 1px solid #e3e6f0;
    }
    
    .resultsTable th {
        background-color: #f8f9fc;
        font-weight: 600;
        color: #4e73df;
    }
    
    .resultsTable tr:hover {
        background-color: #f8f9fc;
    }
    
    .statusBadge {
        display: inline-block;
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
    }
    
    .statusBadge.open {
        background-color: rgba(78, 115, 223, 0.1);
        color: #4e73df;
    }
    
    .statusBadge.closed {
        background-color: rgba(28, 200, 138, 0.1);
        color: #1cc88a;
    }
    
    .viewBtn {
        display: inline-flex;
        align-items: center;
        gap: 5px;
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
    
    .noResults {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 40px 0;
        color: #5a5c69;
        text-align: center;
    }
    
    .noResults i {
        font-size: 3rem;
        margin-bottom: 15px;
        color: #ddd;
    }
    
    .noResults p {
        margin: 5px 0;
    }
    
    .noResults a {
        color: #4e73df;
        text-decoration: none;
    }
    
    .noResults a:hover {
        text-decoration: underline;
    }
    
    @media screen and (max-width: 768px) {
        .searchFiltersGrid {
            grid-template-columns: 1fr;
        }
        
        .searchButtonsRow {
            flex-direction: column;
        }
    }
</style>

<?php include('partials/footer.php')?> 