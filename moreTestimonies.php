<?php
require('header.php');
echo $header;

//Storage Variable for SQL result
$resultArr = array();
//Count variable for mysqli_fetch
$count = 0;
//Formatted id for mysql and other process
(isset($_REQUEST['id']) ? $srvc = preg_replace("/[-]/", "", $_REQUEST['id']) : $srvc = NULL);
//Holds number of rows
$numberOfRows;

if ($srvc == 'baby' || $srvc == 'wobbler' || $srvc == 'toddler' || $srvc == 'preschool') {
    //SQL query for getting the data 
    $stmt = mysqli_prepare($db_connection, "SELECT t.service, t.testimony, p.fName, p.lName FROM testimonial t INNER JOIN parent p ON t.pID = p.email WHERE approved=true AND service=?;");
    mysqli_stmt_bind_param($stmt, 's', $srvc);
    mysqli_stmt_execute($stmt);
    $rslt = mysqli_stmt_get_result($stmt);
    while ($row = mysqli_fetch_array($rslt, MYSQLI_NUM)) {
        for ($i = 0; $i < 4; $i++) {
            $resultArr[$count][$i] = $row[$i];
        }
        $count++;
    }

    $numberOfRows = (sizeof($resultArr) - 1) / 2;

    print "<div class='container w-75 flex-grow-1'>
                <main class='pb-2'><br>  
                    <h2 class='row d-flex justify-content-center '> 園児 Enji " . (($_REQUEST['id'] == 'pre-school') ? ucfirst($_REQUEST['id']) . ' child ' : ucfirst($_REQUEST['id'])) . "care testimonials</h2>";
                    for ($i = 0; $i <= $numberOfRows; $i++) {
                        print " <!-- Row -->
                    <div class='container mt-3'>
                        <div class='row row-cols-1 row-cols-md-2 g-4'>
                            <!-- Column 1 -->
                            <div class='col'>
                                <div class='card mb-3'>
                                    <div class='card-body justify-content-center'>
                                        <p class='card-text'>" . ($resultArr[0 + 2 * $i][1]) . "</p>
                                        <span class='row justify-content-center'>
                                            <h5 class='col-5 text-center pt-1 border-top'><span>" . ($resultArr[0 + 2 * $i][2] . " " . $resultArr[0 + 2 * $i][3]) . "</span></h5>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <!-- Column 2 -->
                            <div class='col'>
                                <div class='card mb-3'>
                                    <div class='card-body justify-content-center'>
                                        <p class='card-text'>" . ($resultArr[1 + 2 * $i][1]) . "</p>
                                        <span class='row justify-content-center'>
                                            <h5 class='col-5 text-center pt-1 border-top'><span>" . ($resultArr[1 + 2 * $i][2] . " " . $resultArr[1 + 2 * $i][3]) . "</span></h5>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>        
                </main>
            </div>";
    }
} else {
    header("Location: index.php");
}
?>





<?php
require('footer.php');
echo $footer;
?>