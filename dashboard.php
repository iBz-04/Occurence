<?php include('partials/headerSection.php')?>

<link rel="stylesheet" href="./swiper-bundle.min.css">

  <div class="main">
      
      <?php include('partials/sideMenu.php')?>


      <div class="mainContent">
        <div class="topSection flex">
          <div class="dashboardTitle">
            <h1>Police Docket System</h1>
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


        <!-- Body Section  ====================================== -->
        <div class="body ">
          <div class="overViewDiv">
              <div class="intro flex" >
              <h3 class="title">Overview</h3>

              <?php 
              
              if(isset($_SESSION['loginMessage'])){
                echo $_SESSION['loginMessage'];
                unset ($_SESSION['loginMessage']);
              }
              ?>
              <div class="flex">
                <div class="iconDiv">
                  <i class="uil uil-toggle-off icon toggleIcon"></i>
                </div>
                <div class="addBtn">
                  <a href="logOut.php">
                    <span>Log Out</span>
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
          $sql4 = "SELECT * FROM incident_types";
          $res4 = mysqli_query($conn, $sql4);
          if ($res4 == true){
             $incidentTypesCount = mysqli_num_rows($res4);
          }
          
          
          
          ?>
          
          <div class="analysisDiv flex">
                  <div class="card active">

                    <div class="cartTitle flex">
                      <i class="uil uil-cell icon"></i>
                      <span>Total Cases </span>
                     <div class="addIconsDiv flex">
                      <a href="addCase.php">
                        <i class="uil uil-plus icon"></i>
                      </a>
                    </div>
                    </div>

                    <div class="cardBody">
                      <h2><?php echo $caseCount;?>
                        <span class="percentage">Cases
                          <i class="uil uil-arrow-up icon"></i>
                        </span>
                      </h2>
                    </div>

                    <small>Added by all administrators</small>
                  </div>
                  <div class="card">

                    <div class="cartTitle flex">
                      <i class="uil uil-chat-info icon"></i>
                      <span>Open Cases</span>

                    </div>

                    <div class="cardBody">
                      <h2><?php echo $openCases;?>
                        <span class="percentage">Cases
                          <i class="uil uil-arrow-up icon"></i>
                        </span>
                      </h2>
                    </div>

                    <small>Cases being followed up</small>
                  </div>
                  <div class="card">

                    <div class="cartTitle flex">
                      <i class="uil uil-check-circle icon"></i>
                      <span>Closed Cases</span>
                    
                    </div>

                    <div class="cardBody">
                      <h2><?php echo $closedCases;?>
                        <span class="percentage">Cases
                          <i class="uil uil-arrow-up icon"></i>
                        </span>
                      </h2>
                    </div>

                    <small>Closed by administrator </small>
                  </div>
                  <div class="card">

                    <div class="cartTitle flex">
                      <i class="uil uil-info-circle icon"></i>
                      <span>Incident Types</span>
                     <div class="addIconsDiv flex">
                      <a href="addIncident.php">
                        <i class="uil uil-plus icon"></i>
                      </a>
                     </div>
                    </div>

                    <div class="cardBody">
                      <h2><?php echo $incidentTypesCount;?>
                        <span class="percentage">Types
                          <i class="uil uil-arrow-up icon"></i>
                        </span>
                      </h2>
                    </div>

                    <small>Current Incident Types</small>
                  </div>
                  
                  
          </div>

          <div class="recentCases">
            <div class="graphDiv swiper-container">
              <div class="swiper-wrapper">
                  <div class="swiper-slide">
                    <div class="swiperImage">
                      <img src="./assets/ATP Images/atp-(4).jpg" alt="">
                    </div>
                  </div>
                  <!-- <div class="swiper-slide">
                    <div class="swiperImage">
                      <img src="./assets/ATP Images/atp-(2).jpg" alt="">
                    </div>
                  </div>
                  <div class="swiper-slide">
                    <div class="swiperImage">
                      <img src="./assets/ATP Images/atp-(3).jpg" alt="">
                    </div>
                  </div>
                  <div class="swiper-slide">
                    <div class="swiperImage">
                    <img src="./assets/ATP Images/atp-(1).jpg" alt="">
                    </div>
                  </div> -->
              </div>
            </div>

            <div class="rCases">
              <div class="intro" >
                <h3 class="title">Recently Occured Cases</h3>
                </div>
                <?php 
                 $sql = "SELECT * FROM cases_table  order by RAND() LIMIT 0,4 " ;
                 $res = mysqli_query($conn, $sql);
                 if ($res == true){
                   $caseCount = mysqli_num_rows($res);
                   if($caseCount>0){
                     while($row = mysqli_fetch_assoc($res)){
                      $incidentType = $row['incident_type'];
                      $incidentDate = $row['incident_date'];
                      $caseStatus = $row['case_status'];

                      ?>
                       <div class="single_Case flex">                   
                  <div class="imgText">
                    <span class="imageName">
                      <?php echo $incidentType?>
                    </span>
                    <small><?php echo $incidentDate?> <strong><?php echo $caseStatus?> Case</strong></small>
                  </div>
                </div>
                      <?php
                     }
                   }
                 }
                ?>

               
                <!-- <div class="single_Case flex">                   
                  <div class="imgText">
                    <span class="imageName">
                      Ambulance requests
                    </span>
                    <small>2/2/2022 <strong>West Car-park</strong></small>
                  </div>
                </div>
                <div class="single_Case flex">                   
                  <div class="imgText">
                    <span class="imageName">
                      Ambulance requests
                    </span>
                    <small>2/2/2022 <strong>West Car-park</strong></small>
                  </div>
                </div>
                <div class="single_Case flex">                   
                  <div class="imgText">
                    <span class="imageName">
                      Ambulance requests
                    </span>
                    <small>2/2/2022 <strong>West Car-park</strong></small>
                  </div>
                </div>
                   -->
                  
              </div>  
            </div>

          </div>

         
      </div>
  </div>


  <!-- Swiper Js -->
  <script src="./swiper-bundle.min.js"></script>
  <!-- index Js -->
  <script src="./index.js"></script>


  <!-- Function to change theme color -->
<script>
const iconDivv = document.querySelector('.iconDiv')
iconDivv.addEventListener('click', function(){
    document.body.classList.toggle('black')
    if(document.body.classList.contains('black')){
      iconDivv.innerHTML = `<i class="uil uil-toggle-on icon toggleIcon"></i>`
    }
    else{
      iconDivv.innerHTML = `<i class="uil uil-toggle-off icon toggleIcon"></i>`
    }  
})

  </script>
  
  <?php include('partials/footer.php')?>