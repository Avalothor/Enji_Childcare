<?php
require("header.php");
echo $header;

//Redirect if not logged in
isset($_SESSION['accessLevel']) ? "" : header("Location: login.php?id_login");

//VARIABLES
//Control switch for admin
$isAdmin;
//Storage for daily details
$dailyArr = getDaily($db_connection);


//======================FUNCTIONS======================

function getDaily($db_connection)
{
    //Array that will be returned
    $dailyArr = array();
    //Counter
    $count = 0;

    if ($_SESSION['accessLevel']) {
        $stmt = mysqli_prepare($db_connection, "SELECT c.fName, c.lName, d.date, d.activity, d.bFast, d.lunch, d.temperature, d.cID FROM day_detail d INNER JOIN child c ON c.cID = d.cID;");
    } else {
        $stmt = mysqli_prepare($db_connection, "SELECT c.fName, c.lName, d.date, d.activity, d.bFast, d.lunch, d.temperature, d.cID FROM day_detail d INNER JOIN child c ON c.cID = d.cID WHERE c.pID=?;");
        mysqli_stmt_bind_param($stmt, 's', $_SESSION['email']);
    }

    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    while ($row = mysqli_fetch_array($result, MYSQLI_NUM)) {
        $dailyArr[$count]['fName'] = $row[0];
        $dailyArr[$count]['lName'] = $row[1];
        $dailyArr[$count]['date'] = $row[2];
        $dailyArr[$count]['activity'] = $row[3];
        $dailyArr[$count]['bFast'] = $row[4];
        $dailyArr[$count]['lunch'] = $row[5];
        $dailyArr[$count]['temperature'] = $row[6];
        $dailyArr[$count]['cID'] = $row[7];
        $count++;
    }
    return $dailyArr;
}
?>

<div class="container w-100 flex-grow-1 pb-4 d-flex flex-column">
    <h2 class="row d-flex justify-content-center pt-3 mb-3">園児Enji Childcare Daily Details Portal</h2>
    <h4 class="text-center">Edit Daily Details:</h4>
    <div class="my-1 row justify-content-end">
        <div class="col d-flex justify-content-end align-items-center gx-1">
            <h5 class="d-inline flex-grow-1 mb-0 me-2 text-end">Search: </h5>
        </div>
        <div class="col-2 px-0">
            <input type="text" placeholder="Search" class="form-control-sm form-control" onkeyup="getData(this.value);">
        </div>
        <div class="col-2 ps-1">
            <input type="date" class="form-control-sm form-control" onchange="getDate(this.value)">
        </div>
    </div>
    <div class=" flex-grow-1 d-flex flex-column justify-content-center align-items-center" id="list">
        <?php
        for ($i = 0; $i < sizeof($dailyArr); $i++) {
            print ' <div class="row mb-3 g-2">
                            <div class="col d-flex justify-content-center align-items-center">
                                <div class="form-outline">
                                    <input type="text" id="fName' . $i . '" name="fName' . $i . '" class="form-control form-control-sm" readonly value="'
                . ((isset($dailyArr[$i]["fName"])) ?  $dailyArr[$i]['fName'] . "" : "") . '">
                                    <label class="form-label" for="fName' . $i . '">Name</label>
                                </div>  
                            </div>
                            <div class="col d-flex justify-content-center align-items-center">
                                <div class="form-outline">
                                    <input type="text" id="lName' . $i . '" name="lName' . $i . '" class="form-control form-control-sm" readonly value="'
                . ((isset($dailyArr[$i]["lName"])) ?  $dailyArr[$i]['lName'] . "" : "") . '">
                                    <label class="form-label" for="lName' . $i . '">Surname</label>
                                </div>  
                            </div>
                            <div class="col d-flex justify-content-center align-items-center">
                                <div class="form-outline">
                                    <input type="date" id="date' . $i . '" name="date' . $i . '" class="form-control form-control-sm" readonly value="'
                . ((isset($dailyArr[$i]["date"])) ?  $dailyArr[$i]['date'] . "" : "") . '">
                                    <label class="form-label" for="date' . $i . '">Date</label>
                                </div>  
                            </div>
                            <div class="col d-flex justify-content-center align-items-center">
                                <div class="form-outline">
                                    <input type="text" id="activity' . $i . '" name="activity' . $i . '" class="form-control form-control-sm" readonly value="'
                . ((isset($dailyArr[$i]["activity"])) ?  preg_replace("/[_]/", " ", $dailyArr[$i]['activity']) . "" : "") . '">
                                    <label class="form-label" for="activity' . $i . '">Activity</label>
                                </div>  
                            </div>
                            <div class="col d-flex justify-content-center align-items-center">
                                <div class="form-outline">
                                    <input type="text" id="bFast' . $i . '" name="bFast' . $i . '" class="form-control form-control-sm" readonly value="'
                . ((isset($dailyArr[$i]["bFast"])) ?  $dailyArr[$i]['bFast'] . "" : "") . '">
                                    <label class="form-label" for="bFast' . $i . '">Breakfast</label>
                                </div>  
                            </div>
                            <div class="col d-flex justify-content-center align-items-center">
                                <div class="form-outline">
                                    <input type="text" id="lunch' . $i . '" name="lunch' . $i . '" class="form-control form-control-sm" readonly value="'
                . ((isset($dailyArr[$i]["lunch"])) ?  $dailyArr[$i]['lunch'] . "" : "") . '">
                                    <label class="form-label" for="lunch' . $i . '">Lunch</label>
                                </div>  
                            </div>
                            <div class="col d-flex justify-content-center align-items-center">
                                <div class="form-outline">
                                    <input type="number" step="0.5" id="temperature' . $i . '" name="temperature' . $i . '" class="form-control form-control-sm" readonly value="'
                . ((isset($dailyArr[$i]["temperature"])) ?  $dailyArr[$i]['temperature'] . "" : "") . '">
                                    <label class="form-label" min="25" max="30" for="temperature' . $i . '">Temperature</label>
                                </div>  
                            </div>
                        </div>';
        }
        ?>
    </div>
</div>

<?php
require("footer.php");
echo $footer;
?>