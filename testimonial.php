<?php
require('header.php');
echo $header;

//Storage Variables
//Holds all results
$resultArr = array();
//Holds data for seperate categories
$baby = array();
$toddler = array();
$wobbler = array();
$school = array();
//Count variable for mysqli_fetch
$count = 0;

//SQL query
$stmt = mysqli_prepare($db_connection, "SELECT t.service, t.testimony, p.fName, p.lName FROM testimonial t INNER JOIN parent p ON t.pID = p.email WHERE approved=true;");
mysqli_stmt_execute($stmt);
$rslt = mysqli_stmt_get_result($stmt);
while ($row = mysqli_fetch_array($rslt, MYSQLI_NUM)) {
    for ($i = 0; $i < 4; $i++) {
        $resultArr[$count][$i] = $row[$i];
    }
    $count++;
}

//Seperate each child category
for ($j = 0; $j < sizeof($resultArr); $j++) {
    if ($resultArr[$j][0] == 'baby') {
        $baby[] = $resultArr[$j];
    } else if ($resultArr[$j][0] == 'wobbler') {
        $wobbler[] = $resultArr[$j];
    } else if ($resultArr[$j][0] == 'toddler') {
        $toddler[] = $resultArr[$j];
    } else {
        $school[] = $resultArr[$j];
    }
}
?>

<div class="container w-75 flex-grow-1">
    <main class="pb-2">
        <br>
        <h2 class="row d-flex justify-content-center "> 園児 Enji testimonial</h2>


        <!-- Babies testimonial -->

        <div class="container mt-3">
            <h3> Babies testimonial</h3>
            <div class="row row-cols-1 row-cols-md-2 g-4">

                <div class="col">
                    <div class="card mb-3">
                        <div class="card-body justify-content-center">

                            <p class="card-text"><?php echo ($baby[0][1]) ?></p>
                            <span class="row justify-content-center">
                                <h5 class=" col-5 text-center pt-1 border-top"><span><?php echo ($baby[0][2] . " " . $baby[0][3]) ?></span> </h5>
                            </span>
                        </div>

                    </div>
                </div>

                <div class="col">
                    <div class="card mb-3">
                        <div class="card-body">
                            <p class="card-text"><?php echo ($baby[1][1]) ?></p>
                            <span class="row justify-content-center">
                                <h5 class=" col-5 text-center pt-1 border-top"><span><?php echo ($baby[1][2] . " " . $baby[1][3]) ?></span> </h5>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <a href="moreTestimonies.php?id=baby" class="d-flex flex-row-reverse text-decoration-none">
                <span class="btn btn-success"> More </span>
            </a>
        </div>




        <!-- Wobbler testmonial  -->
        <div class="container mt-3">
            <h3> Wobbler testimonial</h3>
            <div class="row row-cols-1 row-cols-md-2 g-4">

                <div class="col">
                    <div class="card mb-3">
                        <div class="card-body">
                            <p class="card-text"><?php echo ($wobbler[0][1]) ?></p>
                            <span class="row justify-content-center">
                                <h5 class=" col-5 text-center pt-1 border-top"><span><?php echo ($wobbler[0][2] . " " . $wobbler[0][3]) ?></span> </h5>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="col">
                    <div class="card mb-3">
                        <div class="card-body">
                            <p class="card-text"><?php echo ($wobbler[1][1]) ?></p>
                            <span class="row justify-content-center">
                                <h5 class=" col-5 text-center pt-1 border-top"><span><?php echo ($wobbler[1][2] . " " . $wobbler[1][3]) ?></span> </h5>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <a href="moreTestimonies.php?id=wobbler" class="d-flex flex-row-reverse text-decoration-none">
                <span class="btn btn-success"> More </span>
            </a>
        </div>


        <!-- Toddler testmonial  -->
        <div class="container mt-3">
            <h3> Toddler testimonial</h3>
            <div class="row row-cols-1 row-cols-md-2 g-4">

                <div class="col">
                    <div class="card mb-3">
                        <div class="card-body">
                            <p class="card-text"><?php echo ($toddler[0][1]) ?></p>
                            <span class="row justify-content-center">
                                <h5 class=" col-5 text-center pt-1 border-top"><span><?php echo ($toddler[0][2] . " " . $toddler[0][3]) ?></span> </h5>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="col">
                    <div class="card mb-3">
                        <div class="card-body">
                            <p class="card-text"><?php echo ($toddler[0][1]) ?></p>
                            <span class="row justify-content-center">
                                <h5 class=" col-5 text-center pt-1 border-top"><span><?php echo ($toddler[1][2] . " " . $toddler[1][3]) ?></span> </h5>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <a href="moreTestimonies.php?id=toddler" class="d-flex flex-row-reverse text-decoration-none">
                <span class="btn btn-success"> More </span>
            </a>
        </div>


        <!-- preschool testmonial  -->
        <div class="container mt-3">
            <h3> Preschool testimonial</h3>
            <div class="row row-cols-1 row-cols-md-2 g-4">

                <div class="col">
                    <div class="card mb-3">
                        <div class="card-body">
                            <p class="card-text"><?php echo ($school[0][1]) ?></p>
                            <span class="row justify-content-center">
                                <h5 class=" col-5 text-center pt-1 border-top"><span><?php echo ($school[0][2] . " " . $school[0][3]) ?></span></h5>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="col">
                    <div class="card mb-3">
                        <div class="card-body">
                            <p class="card-text"><?php echo ($school[1][1]) ?></p>
                            <span class="row justify-content-center">
                                <h5 class=" col-5 text-center pt-1 border-top"><span><?php echo ($school[1][2] . " " . $school[1][3]) ?></span> </h5>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <a href="moreTestimonies.php?id=pre-school" class="d-flex flex-row-reverse text-decoration-none">
                <span class="btn btn-success"> More </span>
            </a>
        </div>
    </main>
</div>



<?php
require('footer.php');
echo $footer;
?>