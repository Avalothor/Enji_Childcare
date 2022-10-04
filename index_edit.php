<?php

require('header.php');
echo $header;


    $isCorrect = false;

    //Storage Variable
    $resultArr=array();
    $pageArr= array();
    $activityArr= array();
    $activityCheck = array(); // to validate the activity values
    //Count variable for mysqlI_fetch
    $count = 0;
    $count1= 0;
    $count2= 0;

    //storing values form testimonial table
    $stmt = mysqli_prepare($db_connection, "SELECT t.service, t.testimony, p.fName, p.lName FROM testimonial t INNER JOIN parent p ON t.pID = p.email WHERE approved=true;");
    mysqli_stmt_execute($stmt);
    $rslt = mysqli_stmt_get_result($stmt);
    while ($row = mysqli_fetch_array($rslt, MYSQLI_NUM)) {
        for ($i = 0; $i < 4; $i++) {
            $resultArr[$count][$i] = $row[$i];
        }
        $count++;
    }
    
    //storing values form page table
    $stmt = mysqli_prepare($db_connection, "SELECT * FROM page;");
    mysqli_stmt_execute($stmt);
    $rslt = mysqli_stmt_get_result($stmt);
    while ($row = mysqli_fetch_array($rslt, MYSQLI_NUM)) {
        for ($i = 0; $i < sizeof($row) ; $i++) {
            $pageArr[0][$i] = $row[$i];
        }
    }

    // Store feature values

    $feature1 = $pageArr[0][1];
    $feature2 = $pageArr[0][2];


     //storing values form  active table into $ity
     $stmt = mysqli_prepare($db_connection, "SELECT * FROM activity;");
     mysqli_stmt_execute($stmt);
     $rslt = mysqli_stmt_get_result($stmt);
     while ($row = mysqli_fetch_array($rslt, MYSQLI_NUM)) {
         for ($i = 0; $i < sizeof($row) ; $i++) {
             $activityArr[$count2][$i] = $row[$i];
            
         }
         $count2++;

       
     }
    

    
    //storing values form activity table into $active1
    $stmt = mysqli_prepare($db_connection, "SELECT info FROM activity WHERE name=?;");
    mysqli_stmt_bind_param($stmt,'s',$feature1);
    mysqli_stmt_execute($stmt);
    $rslt = mysqli_stmt_get_result($stmt);
    while ($row = mysqli_fetch_array($rslt, MYSQLI_NUM)) {
            $activity1  = $row[0];
    }

    //storing values form activity table into $active2
    $stmt = mysqli_prepare($db_connection, "SELECT info FROM activity WHERE name=?;");
    mysqli_stmt_bind_param($stmt,'s',$feature2);
    mysqli_stmt_execute($stmt);
    $rslt = mysqli_stmt_get_result($stmt);
    while ($row = mysqli_fetch_array($rslt, MYSQLI_NUM)) {
            $activity2  = $row[0];
    }


    // Use the form data to update page table

    if($_SERVER['REQUEST_METHOD'] == 'POST' ){

        $isCorrect = true;
        $data = $_POST;

        // replacing underscore to match key logic 
        $newFeat1 = preg_replace("/[ ]/", "_", $data['feature1']);
        $newFeat2 = preg_replace("/[ ]/", "_", $data['feature2']);

        // storing values of frist index of the object to validate it. 
        for ($i=0; $i <sizeof($activityArr); $i++) { 
                $activityCheck[$i] = $activityArr[$i][0];
        }

        if(in_array($newFeat1, $activityCheck) == false && in_array($newFeat2, $activityCheck) == false){

            $isCorrect = false;
            
        }

        else {

            $stmt = mysqli_prepare($db_connection, "UPDATE page set feature1 = ?, feature2 = ? WHERE feature1 = ? AND feature2 = ? ;");
            mysqli_stmt_bind_param($stmt, 'ssss', $newFeat1, $newFeat2, $feature1, $feature2);

            mysqli_stmt_execute($stmt);

           
          }

         


        
        
    }






?>
<div class="container w-75 flex-grow-1">

    <div class="container-fluid">
        <div id="carouselExampleCaptions" class="carousel slide " data-bs-ride="carousel">
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0" class="active"
                    aria-current="true" aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1"
                    aria-label="Slide 2"></button>
                <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="2"
                    aria-label="Slide 3"></button>
            </div>
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="images/photos/kidGlue.jpg" class="d-block w-100" alt="Kids glue">
                    <div class="carousel-caption d-none d-md-block">
                        <h5> Wobbler</h5>
                        <p>In 園児Enji Child care fun and work are always together</p>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="images/photos/toddlereating.jpg" class="d-block w-100" alt="toddlers eating">
                    <div class="carousel-caption d-none d-md-block">
                        <h5>Toddler eating</h5>
                        <p> Every meal is important for our kids</p>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="images/photos/kidplaying.jpg" class="d-block w-100" alt="play time">
                    <div class="carousel-caption d-none d-md-block">
                        <h5>Play time</h5>
                        <p>Sports are always on spot</p>
                    </div>
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions"
                data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions"
                data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>

        <main class="d-flex row">
            <div id="about">
                <h2 class="text-center">About Us</h2>
                <p> Lorem ipsum dolor, sit amet consectetur adipisicing elit. Dolores itaque excepturi veritatis
                    obcaecati, suscipit magnam magni! Provident nostrum non aspernatur consequuntur maiores quod maxime
                    doloribus quas? Veniam tempore suscipit sit.</p>

            </div>

            <?php

                // printing after send form

                    If($isCorrect == true) {
                         print "<h1 class'text-center'> Main page Updated Successfully ! </h1>";
                    }else echo "Values doesn't exist in the tables, please try again";

            ?>

            <form action="index_edit.php" method="POST">
            <div class="d-flex row">
                <!-- php -->


                <div class="d-flex col p-5"> <img src="images/photos/<?php echo $feature1 ?>.jpg" class="d-block w-100"
                        alt="Easter event" /></div>


                <div class="d-flex col py-5">
                    <div class="d-flex flex-column">   
                        <h2 class="d-flex justify-content-center pb-3">
                    
                        <div class="feature1">
                        <!--Form element Mercedes-->
                        <select name="feature1" id="feature1" value = "<?php if(isset($feature1)) echo $feature1; ?>">
                            <option value="" disabled selected>Select your option</option>
                            
                            <?php
                            for ($i=0; $i <sizeof($activityArr) ; $i++) { 
                                                          

                            print "
                                        <option value='". preg_replace("/[_]/", " ", $activityArr[$i][0])."'>
                                        
                                        ". preg_replace("/[_]/", " ", $activityArr[$i][0])."
                                        
                                        </option>
                                                              
                            ";

                            }

                        ?>

                    </select>

                    </div>

                        </h2>
                        <p class="d-flex flex-grow-1 p align-items-center fs-3"><?php echo ($activity1);  ?> </p>
                    </div>
                </div>
            </div>

            <div class="d-flex row">
                <div class="d-flex col p-5"> <img src="images/photos/<?php echo $feature2 ?>.jpg" class="d-block w-100"
                        alt="Football time" /></div>


                <div class="d-flex col py-5">
                    <div class="d-flex flex-column">
                        <h2 class="d-flex justify-content-center pb-3">
                                              
                        <div class="feature1">
                        <!--Form element Mercedes-->
                        <select name="feature2" id="feature2" value = "<?php if(isset($feature2)) echo $feature2; ?>">
                            <option value="" disabled selected>Select your option</option>
                            
                            <?php
                            for ($i=0; $i <sizeof($activityArr) ; $i++) { 
                                                          

                            print "
                                        <option value='". preg_replace("/[_]/", " ", $activityArr[$i][0])."'>
                                        
                                        ". preg_replace("/[_]/", " ", $activityArr[$i][0])."
                                        
                                        </option>
                                                              
                            ";

                            }

                        ?>

                    </select>

                    </div>
                    
                    
                    
                        </h2>
                        
                        <p class="d-flex flex-grow-1 p align-items-center fs-3"> <?php echo ($activity2);  ?></p>

                        <!--/ php -->
                    </div>
                </div>
                        <div class = "d-flex flex-column p-5 ">
                            <input type="submit" value ="Apply"  class = "btn btn-danger m-3">
                        </div>
                </form>
            </div>

        </main>
    </div>
    <!--Container Div -->

    <!-- Testimonial test preview -->
    <!-- Carousel wrapper -->

    <div id="carouselExampleControls" class="carousel slide text-center carousel-dark" data-bs-ride="carousel">
        <h3>See what other parents said </h3>
        <hr class="w-75 m-auto">
        <br>
        <div class="carousel-inner d-flex">
           


            <?php

            // suffle array to always display testimonies in different order
            shuffle($resultArr);

            for ($i=0; $i < sizeof($resultArr); $i++) { 
                
                // necessary to active and not crash carousel
                if($i==0) {  $active = "active";}
                else $active = "";
            print "
            
                    <div class='carousel-item ".$active."'>
                     
                        <div class='row d-flex justify-content-center mb-3'>
                            <div class='col-lg-8'>
                                <p class='text-muted'>
                                    <i class='fas fa-quote-left pe-2'></i>
                                    ". $resultArr[$i][1]."
                                </p>
                                <h5 class='mb-1'>". $resultArr[$i][2]."</h5>
                                <small class='mb-2'> service: ". $resultArr[$i][0]."</small>

                            </div>
                        </div>
                    </div>
            
                
                ";
            }
        ?>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls"
                data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls"
                data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>

        <!-- Carousel wrapper -->
        <!--  End of test  -->
    </div>


    <?php
require('footer.php');
echo $footer;

($isCorrect) ? print '<meta http-equiv="refresh" content="1; url=index.php">' : '';

?>

