<?php
require('header.php');
echo $header;

//Redirect if not admin
(isset($_SESSION['accessLevel']) && $_SESSION['accessLevel']) ? "" : header("Location: index.php");
//Set page ID
if (isset($_REQUEST['id']) && $_REQUEST['id'] == 'approve') $pageID = true;
else $pageID = false;
//VARIABLES
//Storage for approved testimonials
$approved = getApproved($db_connection);
//Storage for un-approved testimonials
$nonApproved = getNonApproved($db_connection);

//======================FUNCTIONS======================

//Get all approved testimonies
function getApproved($db_connection)
{
    $approved = array();
    $count = 0;

    $stmt = mysqli_prepare($db_connection, "SELECT t.pID, t.service, t.testimony, t.date, p.fName, p.lName FROM testimonial t INNER JOIN parent p ON t.pID = p.email WHERE approved = 1;");
    mysqli_stmt_execute($stmt);
    $rslt = mysqli_stmt_get_result($stmt);
    while ($row = mysqli_fetch_array($rslt, MYSQLI_NUM)) {
        $approved[$count]['pID'] = $row[0];
        $approved[$count]['service'] = $row[1];
        $approved[$count]['testimony'] = $row[2];
        $approved[$count]['date'] = $row[3];
        $approved[$count]['fName'] = $row[4];
        $approved[$count]['lName'] = $row[5];
        $count++;
    }
    return $approved;
}

//Get all approved testimonies
function getNonApproved($db_connection)
{
    $nonApproved = array();
    $count = 0;

    $stmt = mysqli_prepare($db_connection, "SELECT t.pID, t.service, t.testimony, t.date, p.fName, p.lName FROM testimonial t INNER JOIN parent p ON t.pID = p.email WHERE approved = 0;");
    mysqli_stmt_execute($stmt);
    $rslt = mysqli_stmt_get_result($stmt);
    while ($row = mysqli_fetch_array($rslt, MYSQLI_NUM)) {
        $nonApproved[$count]['pID'] = $row[0];
        $nonApproved[$count]['service'] = $row[1];
        $nonApproved[$count]['testimony'] = $row[2];
        $nonApproved[$count]['date'] = $row[3];
        $nonApproved[$count]['fName'] = $row[4];
        $nonApproved[$count]['lName'] = $row[5];
        $count++;
    }
    return $nonApproved;
}

//Clears all special characters
function clearSpecChar($str){
    return preg_replace("/\W/","_",$str);
}
?>
<div class="container w-100 flex-grow-1 pb-4 d-flex flex-column">
    <h2 class="row d-flex justify-content-center pt-3 mb-3">園児Enji Childcare Testimonial Portal</h2>
    <!-- Pill navs -->
    <div class="d-flex justify-content-center">
        <div class="w-75">
            <ul class="nav nav-pills nav-justified mb-3" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link <?php echo ($pageID) ? "active" : "" ?>" id="home-tab" href="#" data-bs-toggle="pill" data-bs-target="#pills-login" type="button" role="tab" value="edit">Approved</button>
                </li>
                <li class="nav-item ms-1" role="presentation">
                    <button class="nav-link <?php echo (!$pageID) ? "active" : "" ?>" href="#" id="profile-tab" data-bs-toggle="pill" data-bs-target="#pills-register" type="button" role="tab" value="enter">Non-Approved</button>
                </li>
            </ul>
        </div>
    </div>

    <!-- Pills content -->
    <div class="tab-content flex-grow-1">
        <!-- Approved Testimonials -->
        <div class="tab-pane fade <?php echo ($pageID) ? "show active" : "" ?>" id="pills-login" role="tabpanel">
            <div class="container w-75 flex-grow-1 d-flex flex-column">
                <main class="d-flex flex-column justify-content-center align-items-center flex-grow-1 container-fluid h-100">
                    <h2 class="text-center align-self-stretch p-3">Approved Testimonials</h2>
                    <div class="d-flex flex-column align-items-center w-100 flex-grow-1 pb-4">
                        <small class="text-danger text-center mb-1" id="successMsg">
                        </small>
                        <?php
                        for ($i = 0; $i < sizeof($approved); $i++) {
                            print ' <div class="col-9 mb-2 row justify-content-around align-items-center p-3 shadow" id="approved_'
                            .clearSpecChar($approved[$i]['pID']."_".$approved[$i]['service']).'">
                                        <span class="col-3">'
                                        .$approved[$i]['fName']." ".$approved[$i]['lName'].
                                        '</span>
                                        <span class="col-3">
                                            <small>'
                                            .$approved[$i]['pID'].
                                            '</small>
                                        </span>
                                        <span class="col-3">
                                            <small>'
                                            .$approved[$i]['service'].
                                            '</small>
                                        </span>
                                        <a class="bi bi-arrow-down-square-fill col-1 p-0 fs-4" data-bs-toggle="collapse" data-bs-target="#'
                                        .clearSpecChar($approved[$i]['pID']."".$approved[$i]['service']).
                                        '"></a>
                                        <div class="collapse col-9 mb-2 row justify-content-around align-items-center p-3 " id="'
                                        .clearSpecChar($approved[$i]['pID'].$approved[$i]['service']).
                                        '">
                                            <hr class="w-75 m-auto">
                                            <hr class="w-75 m-auto">
                                            <h4 class="text-center my-3">Testimonial</h4>
                                            <hr class="w-75 m-auto">
                                            <div class="my-2 d-flex justify-content-start">
                                                <div class="form-check form-check-inline">'
                                                    .$approved[$i]['testimony'].    
                                                '</div>
                                            </div>
                                            <hr class="my-3 w-75 m-auto row">
                                            <a class="bi bi-hand-thumbs-down-fill my-3 row text-decoration-none text-primary justify-content-center p-0 fs-4" onclick="unApprove(this.id);" id="'
                                            .clearSpecChar($approved[$i]['pID']."_".$approved[$i]['service']).
                                            '"></a>
                                        </div>
                                    </div>';
                        }
                        ?>
                    </div>
                </main>
            </div>
        </div>
        <!-- Non-Approved Testimonials -->
        <div class="tab-pane fade <?php echo (!$pageID) ? "show active" : "" ?>" id="pills-register" role="tabpanel">
        <div class="container w-75 flex-grow-1 d-flex flex-column">
                <main class="d-flex flex-column justify-content-center align-items-center flex-grow-1 container-fluid h-100">
                    <h2 class="text-center align-self-stretch p-3">Un-Approved Testimonials</h2>
                    <div class="d-flex flex-column align-items-center w-100 flex-grow-1 pb-4">
                        <small class="text-danger text-center mb-1" id="unSuccessMsg">
                        </small>
                        <?php
                        for ($i = 0; $i < sizeof($nonApproved); $i++) {
                            print ' <div class="col-9 mb-2 row justify-content-around align-items-center p-3 shadow" id="unapproved_'
                            .clearSpecChar($nonApproved[$i]['pID']."_".$nonApproved[$i]['service']).'">
                                        <span class="col-3">'
                                        .$nonApproved[$i]['fName']." ".$nonApproved[$i]['lName'].
                                        '</span>
                                        <span class="col-3">
                                            <small>'
                                            .$approved[$i]['pID'].
                                            '</small>
                                        </span>
                                        <span class="col-3">
                                            <small>'
                                            .$nonApproved[$i]['service'].
                                            '</small>
                                        </span>
                                        <a class="bi bi-arrow-down-square-fill col-1 p-0 fs-4" data-bs-toggle="collapse" data-bs-target="#'
                                        .clearSpecChar($nonApproved[$i]['pID']."".$nonApproved[$i]['service']).
                                        '"></a>
                                        <div class="collapse col-9 mb-2 row justify-content-around align-items-center p-3 " id="'
                                        .clearSpecChar($nonApproved[$i]['pID'].$nonApproved[$i]['service']).
                                        '">
                                            <hr class="w-75 m-auto">
                                            <hr class="w-75 m-auto">
                                            <h4 class="text-center my-3">Testimonial</h4>
                                            <hr class="w-75 m-auto">
                                            <div class="my-2 d-flex justify-content-start">
                                                <div class="form-check form-check-inline">'
                                                    .$nonApproved[$i]['testimony'].    
                                                '</div>
                                            </div>
                                            <hr class="my-3 w-75 m-auto row">
                                            <a class="bi bi-hand-thumbs-up-fill my-3 row text-decoration-none text-primary justify-content-center p-0 fs-4"  onclick="approve(this.id);" id="'
                                            .clearSpecChar($nonApproved[$i]['pID']."_".$nonApproved[$i]['service']).
                                            '"></a>
                                        </div>
                                    </div>';
                        }
                        ?>
                    </div>
                </main>
            </div>
        </div>
    </div>
    <!-- Pills content -->




</div>
<?php
require('footer.php');
echo $footer;
?>