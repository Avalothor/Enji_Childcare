<?php
include("../mysql_connect.php");
mysqli_select_db($db_connection, 's3050376');

if (isset($_GET['var'])) {
    list($email, $date, $access) = explode(":", $_GET['var']);
    
    if (strlen($date) == 0) {
        $dailyArr = getDaily($email, $access, $db_connection);
        printList($dailyArr);
    } else {
        $dailyArr = getList($email, $date, $access, $db_connection);
        printList($dailyArr);
    }
}


//======================FUNCTIONS======================

function getDaily($email, $access, $db_connection)
{
    //Array that will be returned
    $dailyArr = array();
    //Counter
    $count = 0;
    if ($access) {
        $stmt = mysqli_prepare($db_connection, "SELECT c.fName, c.lName, d.date, d.activity, d.bFast, d.lunch, d.temperature, d.cID FROM day_detail d INNER JOIN child c ON c.cID = d.cID;");
    } else {
        $stmt = mysqli_prepare($db_connection, "SELECT c.fName, c.lName, d.date, d.activity, d.bFast, d.lunch, d.temperature, d.cID FROM day_detail d INNER JOIN child c ON c.cID = d.cID WHERE c.pID=?;");
        mysqli_stmt_bind_param($stmt, 's', $email);
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

function getList($email, $date, $access, $db_connection)
{
    //Array that will be returned
    $dailyArr = array();
    //Counter
    $count = 0;
    
    if ($access) {
        $stmt = mysqli_prepare($db_connection, "SELECT c.fName, c.lName, d.date, d.activity, d.bFast, d.lunch, d.temperature, d.cID FROM day_detail d INNER JOIN child c ON c.cID = d.cID WHERE d.date=?;");
        mysqli_stmt_bind_param($stmt, 's', $date);
    } else {
        $stmt = mysqli_prepare($db_connection, "SELECT c.fName, c.lName, d.date, d.activity, d.bFast, d.lunch, d.temperature, d.cID, c.pID  FROM day_detail d INNER JOIN child c ON c.cID = d.cID WHERE c.pID=? AND d.date=?;");
        mysqli_stmt_bind_param($stmt, 'ss',  $email, $date);
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


function printList($dailyArr)
{
    for ($i = 0; $i < sizeof($dailyArr); $i++) {
        print ' <div class="row mb-3 g-2">
                    <div class="col d-flex justify-content-center align-items-center">
                        <div class="form-outline">
                            <input type="text" id="fName' . $i . '" name="fName' . $i . '" class="active form-control form-control-sm" readonly value="'
            . ((isset($dailyArr[$i]["fName"])) ?  $dailyArr[$i]['fName'] . "" : "") . '">
                            <label class="form-label" for="fName' . $i . '">Name</label>
                        </div>  
                    </div>
                    <div class="col d-flex justify-content-center align-items-center">
                        <div class="form-outline">
                            <input type="text" id="lName' . $i . '" name="lName' . $i . '" class="active form-control form-control-sm" readonly value="'
            . ((isset($dailyArr[$i]["lName"])) ?  $dailyArr[$i]['lName'] . "" : "") . '">
                            <label class="form-label" for="lName' . $i . '">Surname</label>
                        </div>  
                    </div>
                    <div class="col d-flex justify-content-center align-items-center">
                        <div class="form-outline">
                            <input type="date" id="date' . $i . '" name="date' . $i . '" class="active form-control form-control-sm" readonly value="'
            . ((isset($dailyArr[$i]["date"])) ?  $dailyArr[$i]['date'] . "" : "") . '">
                            <label class="form-label" for="date' . $i . '">Date</label>
                        </div>  
                    </div>
                    <div class="col d-flex justify-content-center align-items-center">
                        <div class="form-outline">
                            <input type="text" id="activity' . $i . '" name="activity' . $i . '" class="active form-control form-control-sm" readonly value="'
            . ((isset($dailyArr[$i]["activity"])) ?  preg_replace("/[_]/", " ", $dailyArr[$i]['activity']) . "" : "") . '">
                            <label class="form-label" for="activity' . $i . '">Activity</label>
                        </div>  
                    </div>
                    <div class="col d-flex justify-content-center align-items-center">
                        <div class="form-outline">
                            <input type="text" id="bFast' . $i . '" name="bFast' . $i . '" class="active form-control form-control-sm" readonly value="'
            . ((isset($dailyArr[$i]["bFast"])) ?  $dailyArr[$i]['bFast'] . "" : "") . '">
                            <label class="form-label" for="bFast' . $i . '">Breakfast</label>
                        </div>  
                    </div>
                    <div class="col d-flex justify-content-center align-items-center">
                        <div class="form-outline">
                            <input type="text" id="lunch' . $i . '" name="lunch' . $i . '" class="active form-control form-control-sm" readonly value="'
            . ((isset($dailyArr[$i]["lunch"])) ?  $dailyArr[$i]['lunch'] . "" : "") . '">
                            <label class="form-label" for="lunch' . $i . '">Lunch</label>
                        </div>  
                    </div>
                    <div class="col d-flex justify-content-center align-items-center">
                        <div class="form-outline">
                            <input type="number" step="0.5" id="temperature' . $i . '" name="temperature' . $i . '" class="active form-control form-control-sm" readonly value="'
            . ((isset($dailyArr[$i]["temperature"])) ?  $dailyArr[$i]['temperature'] . "" : "") . '">
                            <label class="form-label" min="25" max="30" for="temperature' . $i . '">Temperature</label>
                        </div>  
                    </div>
                </div>';
    }
}
?>
