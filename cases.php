<?php include('partials/headerSection.php')?>

  <div class="main">
  <?php include('partials/sideMenu.php')?>

      <div class="mainContent">
        <div class="topSection flex">
        <div class="dashboardTitle">
            <h1>Cases Page</h1>
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
           <div class="intro flex" >
             <h3 class="title">All Cases</h3>
             <?php 
             
            if(isset($_SESSION['addedCase'])){
              echo $_SESSION['addedCase'];
              unset($_SESSION['addedCase']);
            }
            if(isset($_SESSION['updatedCase'])){
              echo $_SESSION['updatedCase'];
              unset($_SESSION['updatedCase']);
            }
             ?>
            <div class="buttons flex" style="gap: 10px;">
              <div class="addBtn">
                <a href="addCase.php">
                  <span>Add Case</span>
                </a>
              </div>
              <div class="addBtn excelBtn" >
                <a href="<?php SITEURL?>exportCases.php">
                  <span>Export Excel</span>
                </a>
              </div>
              <div class="addBtn">
                <a href="obsearc.php">
                  <span>Search Case</span>
                </a>
              </div>
            </div>
           </div>
          </div>
         
          <div class="mainItems">
            <table>
           
                <tr  class="tableHeader">
                  <th>OB No.</th>
                  <th>Incident Type</th>
                  <th>Time(Reported)</th>
                  <th>Status</th>
                  <th>Category</th>
                  <th>Image(s)</th>
                  <th>Edit</th>
                  <th>Details</th>
                  <th>Follow</th>
                </tr>


                <?php 
                
                $sql = "SELECT id, incident_type, incident_date, incident_time, case_status, case_category, image_one, image_two, image_three FROM cases_table";
                $res = mysqli_query($conn, $sql);

                if($res  == TRUE){
                  $count = mysqli_num_rows($res);
                  if($count>0){
                    while($row = mysqli_fetch_assoc($res)){
                      $id = $row['id'];
                      $incidentType = $row['incident_type'];
                      $incidentDate = $row['incident_date'];
                      $incidentTime = $row['incident_time'];
                      $caseStatus = $row['case_status'];
                      $caseCategory = $row['case_category'];
                      $img1 = $row['image_one'];
                      $img2 = $row['image_two'];
                      $img3 = $row['image_three'];

                      if($caseStatus=='Open'){
                        ?>
                        <tr class="tableData">
                          <td><?php echo $incidentDate;?>/<?php echo $id;?></td>
                          <td><?php echo $incidentType;?></td>
                          <td><?php echo $incidentTime;?></td>
                          <td><?php echo $caseStatus;?></td>
                          <td><?php echo $caseCategory?></td>
                          <td><i class="uil uil-paperclip icon" style="color: blue;"></i></td>
                   

                          <td><a href="<?php echo SITEURL?>updateCase.php?id= <?php echo $id;?>"><i class="uil uil-edit icon editIcon"></i></a></td>

                          <td><a href="<?php echo SITEURL?>details.php?id= <?php echo $id;?>"><i class="uil uil-file-info-alt icon detailsIcon"></i></a></td>
                          
                         <td><a href="<?php echo SITEURL?>following.php?id= <?php echo $id;?>"><i class="uil uil-arrow-from-right icon followIcon"></i></a></td>
                        </tr>
  
                        <?php
                      }

                      else{
                        ?>
                        <tr class="tableData">
                          <td><?php echo $incidentDate;?>/<?php echo $id;?></td>
                          <td><?php echo $incidentType;?></td>
                          <td><?php echo $incidentTime;?></td>
                          <td><?php echo $caseStatus;?></td>
                          <td><?php echo $caseCategory?></td>
                          <td><i class="uil uil-paperclip icon" style="color: blue;"></i></td>
                   

                          <td><a href="<?php echo SITEURL?>updateCase.php?id= <?php echo $id;?>"><i class="uil uil-edit icon editIcon"></i></a></td>

                          <td><a href="<?php echo SITEURL?>details.php?id= <?php echo $id;?>"><i class="uil uil-file-info-alt icon detailsIcon"></i></a></td>
                          
                          <td>
                           <i class="uil uil-check-circle icon tickIcon"></i>
                           </td>
  
                        <?php
                      }
                      
                    
                      }

                  }
                  else{
                    ?>
                    <tr>
                    <td colspan="9"><span class="fail" style="textalign:center; color: red; font-size:25px; margin: 2rem 1rem;">No data in the database!</span></td>
                    </tr>
                    <?php

                  }

                }
                else{
                  die("Failed to connect!");
                }

                ?>
            
         
              
            </table>
          </div>
          
        

        </div>
      </div>
  </div>

  <?php include('partials/footer.php')?>