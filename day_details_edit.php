<?php
require("header.php");
echo $header;

//VARIABLES
//Control switch for admin
$isAdmin;
//Storage for daily details
$dailyArr = getDaily($db_connection);
//Storage for all childs that paired with their parents
$childArr = getChild($db_connection);
//Storage for activities
$activities = getActivity($db_connection);
asort($activities);
//Storage for all parents who has a child in database
$parentArr = array_keys($childArr);
//Storage for correct input
$correct = array();
//Storage for error messages
$errorMsg = array();
//Control switch for enter details form filled correctly
$isCorrect = false;
//Control switch for valid activity
$isValidAct = false;
//Control switch for successful registration
$isRgstSccs = false;
//Set page id
if (isset($_REQUEST['id']) && $_REQUEST['id'] == 'enter') $pageID = 'enter';
else $pageID = 'edit';

(isset($_SESSION['accessLevel']) && $_SESSION['accessLevel']) ? "" : header("Location: index.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $isCorrect = true;

    //Cleans submitted data from tags, slashes and spaces on the outer-side
    $data = sanitizeData($_POST);

    //Control the submitted form id
    if ($pageID == 'enter') {
        //pageID is enter => enter new details part
        //Control parent-child inputs
        if (isset($data['parent']) && array_key_exists($data['parent'], $childArr) && in_array($data['child'], $childArr[$data['parent']])) {
            $correct['parent'] = $data['parent'];
            $correct['child'] = $data['child'];
        } else {
            $errorMsg['dropdown'] = "Parent and child doesn't match. Please try again";
            $isCorrect = false;
        }

        //Control fName and lName
        if (isset($data['fName']) && preg_match("/^[a-zA-Z ]{3,25}$/", $data['fName'])) {
            $correct['fName'] = $data['fName'];
        } else {
            $errorMsg['fName'] = "Invalid name input.";
            $isCorrect = false;
        }
        if (isset($data['lName']) && preg_match("/^[a-zA-Z ]{3,25}$/", $data['lName'])) {
            $correct['lName'] = $data['lName'];
        } else {
            $errorMsg['lName'] = "Invalid last name input.";
            $isCorrect = false;
        }

        //Control date input
        //Seperate year, month and day
        $date = explode("-", $data['date']);

        //If date is valid control for the age of child 
        //Also control for isCorrect because if the parent or child input is invalid we cannot control for the day_detail table 
        if ($isCorrect && strcmp($data['date'], '') && checkdate($date[1], $date[2], $date[0])) {
            if (!$isCorrect = checkTableDate($data, $db_connection)) {
                $errorMsg['exist'] = "This day detail already exists. To edit the existing data please click <a href='day_details_edit.php'>here</a>";
                $isCorrect = false;
            } else {
                $correct['date'] = $data['date'];
            }
        } else {
            $errorMsg['date'] = "Invalid date value.";
            $isCorrect = false;
        }

        //Control activity
        if (isset($data['activity']) && (in_array($data['activity'], $activities) || $data['activity'] == "")) {
            ($data['activity'] == "") ? $correct['activity'] = '' : $correct['activity'] = $data['activity'];
        } else {
            $errorMsg['activity'] = "Activity not exist.";
            $isCorrect = false;
        }

        //Control breakfast and lunch
        if (isset($data['bFast']) && preg_match("/^[a-zA-Z ]{3,25}$/", $data['bFast'])) {
            $correct['bFast'] = $data['bFast'];
        } else {
            $errorMsg['bFast'] = "Invalid b.fast input.";
            $isCorrect = false;
        }
        if (isset($data['lunch']) && preg_match("/^[a-zA-Z ]{3,25}$/", $data['lunch'])) {
            $correct['lunch'] = $data['lunch'];
        } else {
            $errorMsg['lunch'] = "Invalid lunch input.";
            $isCorrect = false;
        }

        //Control temperature 
        if (isset($data['temperature']) && $data['temperature'] >= 10) {
            $correct['temperature'] = $data['temperature'];
        } else {
            $errorMsg['temperature'] = "Invalid temperature.";
            $isCorrect = false;
        }

        //If everything is valid insert data
        if ($isCorrect && sizeof($correct) == 9) {
            $isRgstSccs = insertDayDetails($correct, $db_connection);
        }

        if (!$isRgstSccs) {
            //If insert is not successfull add an error message, and change isCorrect 
            $errorMsg['insertFail'] = "We can't process your query. Please try again.";
            $isCorrect = false;
        }
    } else {
        //pageID is not enter => edit details part

        if (isset($data['submit']) && $data['submit'] < sizeof($dailyArr)) {
            $correct['submit'] = $data['submit'];

            //Control name inputs
            if ($data['fName' . $data['submit']] != "" && $data['lName' . $data['submit']] != "") {
                foreach ($childArr as $parent => $Arr) {
                    if (in_array($data['fName' . $data['submit']] . ":" . $data['lName' . $data['submit']], $Arr)) {
                        $correct['fName'] = $data['fName' . $data['submit']];
                        $correct['lName'] = $data['lName' . $data['submit']];
                        $correct['parent'] = $parent;
                        unset( $errorMsg[$data['submit']]['name']);
                        $isCorrect = true;
                        break;
                    } else {
                        $errorMsg[$data['submit']]['name'] = "This child doesn't exists.";
                        $isCorrect = false;
                    }
                }
            } else {
                $errorMsg[$data['submit']]['emptyName'] = "You can't leave a name column empty.";
                $isCorrect = false;
            }

            //Control date input
            //Seperate year, month and day
            $date = explode("-", $data['date' . $data['submit']]);

            //If date is valid control for the age of child 
            if (strcmp($data['date' . $data['submit']], '') && checkdate($date[1], $date[2], $date[0])) {
                $correct['date'] = $data['date' . $data['submit']];
            } else {
                $errorMsg[$data['submit']]['date'] = "Invalid date value.";
                $isCorrect = false;
            }

            //Control breakfast and lunch
            if (isset($data['bFast' . $data['submit']]) && preg_match("/^[a-zA-Z ]{3,25}$/", $data['bFast' . $data['submit']])) {
                $correct['bFast'] = $data['bFast' . $data['submit']];
            } else {
                $errorMsg[$data['submit']]['bFast'] = "Invalid b.fast input.";
                $isCorrect = false;
            }
            if (isset($data['lunch' . $data['submit']]) && preg_match("/^[a-zA-Z ]{3,25}$/", $data['lunch' . $data['submit']])) {
                $correct['lunch'] = $data['lunch' . $data['submit']];
            } else {
                $errorMsg[$data['submit']]['lunch'] = "Invalid lunch input.";
                $isCorrect = false;
            }

            //Control temperature 
            if (isset($data['temperature' . $data['submit']]) && $data['temperature' . $data['submit']] >= 10) {
                $correct['temperature'] = $data['temperature' . $data['submit']];
            } else {
                $errorMsg[$data['submit']]['temperature'] = "Invalid temperature.";
                $isCorrect = false;
            }

            if ($isCorrect) {
                $isRgstSccs = editDaily($correct,$dailyArr,$db_connection);
            }
        } else {
            $errorMsg['error'] = "Something went wrong please try again.";
            $isCorrect = false;
        }
    }
}

//Get daily details again at the bottom so if we enter a new value, new value should be apper on edit tab
//Storage for daily details
$dailyArr = getDaily($db_connection);


//======================FUNCTIONS======================

//Sanitize all data by calling appropriate method
function sanitizeData($data)
{
    //If argument is an array recursive call for each element
    if (is_array($data)) {
        //Recursive run for each value
        foreach ($data as $key => $value)
            $data[$key] = sanitizeData($value);
    } else {
        //Clear all slashes on data
        while (strpos($data, '\\')) {
            $data = stripslashes($data);
        }
        $data = strip_tags($data);
        $data = trim($data);
    }
    return $data;
}

function getDaily($db_connection)
{
    //Array that will be returned
    $dailyArr = array();
    //Counter
    $count = 0;

    $stmt = mysqli_prepare($db_connection, "SELECT c.fName, c.lName, d.date, d.activity, d.bFast, d.lunch, d.temperature, d.cID FROM day_detail d INNER JOIN child c ON c.cID = d.cID;");
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

function getChild($db_connection)
{
    //Array that will be returned
    $childArr = array();

    $stmt = mysqli_prepare($db_connection, "SELECT fName, lName, pID FROM child;");
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $fName, $lName, $pID);
    while (mysqli_stmt_fetch($stmt)) {
        $childArr[$pID][] = $fName . ":" . $lName;
    }
    return $childArr;
}

function getActivity($db_connection)
{
    //Array that will be returned
    $actArr = array();

    $stmt = mysqli_prepare($db_connection, "SELECT name FROM activity;");
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $actName);
    while (mysqli_stmt_fetch($stmt)) {
        $actArr[] = $actName;
    }
    return $actArr;
}

function checkTableDate($data, $db_connection)
{
    //Get cID
    $cID = getCID($data, $db_connection);
    $data = escape($data, $db_connection);

    $stmt = mysqli_prepare($db_connection, "SELECT * FROM day_detail WHERE cID=? AND date=?;");
    mysqli_stmt_bind_param($stmt, 'is', $cID, $data['date']);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    if (!mysqli_stmt_affected_rows($stmt)) return true;
    return false;
}

function getCID($data, $db_connection)
{
    $data = escape($data, $db_connection);

    $stmt = mysqli_prepare($db_connection, "SELECT cID FROM child WHERE pID=? AND fName=? AND lName=?;");
    mysqli_stmt_bind_param($stmt, 'sss', $data['parent'], $data['fName'], $data['lName']);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_array($result, MYSQLI_NUM);
    return $row[0];
}

function insertDayDetails($data, $db_connection)
{
    $cID = getCID($data, $db_connection);

    $data = escape($data, $db_connection);
    $stmt = mysqli_prepare($db_connection, "INSERT INTO day_detail VALUES(?,?,?,?,?,?);");
    mysqli_stmt_bind_param($stmt, "issssd", $cID, $data['date'], $data['activity'], $data['bFast'], $data['lunch'], $data['temperature']);
    mysqli_stmt_execute($stmt);
    if (mysqli_stmt_affected_rows($stmt) > 0) return true;
    return false;
}

//Runs real_escape_string for each value
function escape($data, $db_connection)
{
    foreach ($data as $key => $value) {
        $data[$key] = mysqli_real_escape_string($db_connection, $value);
    }
    return $data;
}

function editDaily($data,$dailyArr,$db_connection){
    $cID = getCID($data,$db_connection);
    
    $data = escape($data,$db_connection);
    $stmt = mysqli_prepare($db_connection,"UPDATE day_detail SET cID=?,date=?,activity=?,bFast=?,lunch=?,temperature=? WHERE cID=? AND date=?;");
    mysqli_stmt_bind_param($stmt,"issssiis",$cID,$data['date'],$data['activity'],$data['bFast'],$data['lunch'],$data['temperature'],$dailyArr[$data['submit']]['cID'],$dailyArr[$data['submit']]['date']);
    mysqli_stmt_execute($stmt);
    if (mysqli_stmt_affected_rows($stmt) > 0) return true;
    return false;
}

?>

<div class="container w-100 flex-grow-1 pb-4 d-flex flex-column">
    <h2 class="row d-flex justify-content-center pt-3 mb-3 <?php echo ($isCorrect) ? 'hide' : ''; ?>">園児Enji Childcare Daily Details Portal</h2>
    <!-- Pill navs -->
    <div class="d-flex justify-content-center">
        <div class="w-75">
            <ul class="nav nav-pills nav-justified mb-3" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link <?php echo ($pageID != 'enter') ? "active" : "" ?>" id="home-tab" href="#" data-bs-toggle="pill" data-bs-target="#pills-login" type="button" role="tab" value="edit">Edit Details</button>
                </li>
                <li class="nav-item ms-1" role="presentation">
                    <button class="nav-link <?php echo ($pageID == 'enter') ? "active" : "" ?>" href="#" id="profile-tab" data-bs-toggle="pill" data-bs-target="#pills-register" type="button" role="tab" value="enter">Enter Details</button>
                </li>
            </ul>
        </div>
    </div>
    <!-- Pills content -->
    <div class="tab-content flex-grow-1">
        <!-- Edit daily details -->
        <div class="tab-pane fade <?php echo ($pageID != 'enter') ? "show active" : "" ?>" id="pills-login" role="tabpanel">
            <h4 class="text-center">Edit Daily Details:</h4>
            <h5 class="text-center text-primary mb-2"><?php echo (isset($errorMsg['error'])) ? $errorMsg['error'] : ""; ?></h5>
            <?php
            for ($i = 0; $i < sizeof($dailyArr); $i++) {
                print '
                    <form action="day_details_edit.php" method="POST">
                        <!-- Error Messages -->
                        <div class="row g-2">
                            <div class="col row">
                                <div class="col-3 text-center p-0 me-5 d-flex justify-content-center align-items-center">
                                    <small class="text-danger text-center mb-1">'
                    . ((isset($errorMsg[$i]["name"])) ? $errorMsg[$i]['name'] : "")
                    . ((isset($errorMsg[$i]["emptyName"])) ? $errorMsg[$i]['emptyName'] : "") .
                    '</small>
                                </div>
                                <div class="col text-center p-0 d-flex justify-content-center align-items-center">
                                    <small class="text-danger mb-1">'
                    . ((isset($errorMsg[$i]["date"])) ? $errorMsg[$i]['date'] : "") .
                    '</small>
                                </div>
                                <div class="col text-center p-0 d-flex justify-content-center align-items-center">
                                    <small class="text-danger mb-">'
                    . ((isset($errorMsg[$i]["activity"])) ? $errorMsg[$i]['activity'] : "") .
                    '</small>
                                </div>
                                <div class="col text-center p-0 d-flex justify-content-center align-items-center">
                                    <small class="text-danger mb-1">'
                    . ((isset($errorMsg[$i]["bFast"])) ? $errorMsg[$i]['bFast'] : "") .
                    '</small>
                                </div>
                                <div class="col text-center p-0 d-flex justify-content-center align-items-center">
                                    <small class="text-danger mb-1">'
                    . ((isset($errorMsg[$i]["lunch"])) ? $errorMsg[$i]['lunch'] : "") .
                    '</small>
                                </div>
                                <div class="col text-center p-0 d-flex justify-content-center align-items-center">
                                    <small class="text-danger mb-1">'
                    . ((isset($errorMsg[$i]["temperature"])) ? $errorMsg[$i]['temperature'] : "") .
                    '</small>
                                </div>
                            </div>
                            <div class="col-1">
                                <small class="text-danger mb-1">
                                </small>
                            </div>
                        </div>
                        <!-- Form Values -->
                        <div class="row mb-3 g-2">
                            <div class="col d-flex justify-content-center align-items-center">
                                <div class="form-outline">
                                    <input type="text" id="fName' . $i . '" name="fName' . $i . '" class="form-control form-control-sm" required value="'
                    . ((isset($dailyArr[$i]["fName"])) ?  $dailyArr[$i]['fName'] . "" : "") . '">
                                    <label class="form-label" for="fName' . $i . '">Name</label>
                                </div>  
                            </div>
                            <div class="col d-flex justify-content-center align-items-center">
                                <div class="form-outline">
                                    <input type="text" id="lName' . $i . '" name="lName' . $i . '" class="form-control form-control-sm" required value="'
                    . ((isset($dailyArr[$i]["lName"])) ?  $dailyArr[$i]['lName'] . "" : "") . '">
                                    <label class="form-label" for="lName' . $i . '">Surname</label>
                                </div>  
                            </div>
                            <div class="col d-flex justify-content-center align-items-center">
                                <div class="form-outline">
                                    <input type="date" id="date' . $i . '" name="date' . $i . '" class="form-control form-control-sm" required value="'
                    . ((isset($dailyArr[$i]["date"])) ?  $dailyArr[$i]['date'] . "" : "") . '">
                                    <label class="form-label" for="date' . $i . '">Date</label>
                                </div>  
                            </div>
                            <div class="col d-flex justify-content-center align-items-center">  
                                <select class="form-select form-select-sm" name="activity' . $i . '" id="activity' . $i . '" required>';
                foreach ($activities as $activity) {
                    print '<option value=' . $activity .  (($activity == "") ? " selected=selected>" : ">" . preg_replace("/[_]/", " ", $activity)) . '</option>';
                }
                print '</select>
                            </div>
                            <div class="col d-flex justify-content-center align-items-center">
                                <div class="form-outline">
                                    <input type="text" id="bFast' . $i . '" name="bFast' . $i . '" class="form-control form-control-sm" required value="'
                    . ((isset($dailyArr[$i]["bFast"])) ?  $dailyArr[$i]['bFast'] . "" : "") . '">
                                    <label class="form-label" for="bFast' . $i . '">Breakfast</label>
                                </div>  
                            </div>
                            <div class="col d-flex justify-content-center align-items-center">
                                <div class="form-outline">
                                    <input type="text" id="lunch' . $i . '" name="lunch' . $i . '" class="form-control form-control-sm" required value="'
                    . ((isset($dailyArr[$i]["lunch"])) ?  $dailyArr[$i]['lunch'] . "" : "") . '">
                                    <label class="form-label" for="lunch' . $i . '">Lunch</label>
                                </div>  
                            </div>
                            <div class="col d-flex justify-content-center align-items-center">
                                <div class="form-outline">
                                    <input type="number" step="0.5" id="temperature' . $i . '" name="temperature' . $i . '" class="form-control form-control-sm" required value="'
                    . ((isset($dailyArr[$i]["temperature"])) ?  $dailyArr[$i]['temperature'] . "" : "") . '">
                                    <label class="form-label" min="25" max="30" for="temperature' . $i . '">Temperature</label>
                                </div>  
                            </div>
                            <!-- Submit Button -->
                            <div class="col-1 d-flex justify-content-center align-items-center">
                                <button type="submit" name="submit" value="' . $i . '" class="btn btn-primary btn-sm flex-grow-1">Edit</button>
                            </div>
                        </div>
                    </form>';
            }
            ?>
        </div>
        <!-- Enter new Daily Details -->
        <div class="tab-pane fade <?php echo ($pageID == 'enter') ? "show active" : "" ?>" id="pills-register" role="tabpanel">
            <div class="d-flex flex-column justify-content-center align-items-center">
                <h4 class="text-center">Enter details:</h4>
                <h5 class="text-center text-primary"><?php ($isRgstSccs && $isCorrect) ? print 'You query was successful.' : ''; ?></h5>
                <small class="text-danger my-2">
                    <?php
                    echo (isset($errorMsg['dropdown'])) ? $errorMsg['dropdown'] : "";
                    echo (isset($errorMsg['exist'])) ? $errorMsg['exist'] : "";
                    echo (isset($errorMsg['insertFail'])) ? $errorMsg['insertFail'] : "";
                    ?>
                </small>
            </div>
            <form action="day_details_edit.php?id=enter" method="POST">
                <div class="d-flex">
                    <div class="d-flex flex-grow-1">
                        <span class="col-2"></span>
                        <select class="form-select form-select-sm w-25 col-3" name="parent" id="parent" required>
                            <option value="" class="disable" disabled selected hidden>Please select a parent</option>
                            <?php
                            foreach ($parentArr as $parent) {
                                print '<option value=' . $parent . ' ' . ((isset($correct['parent']) && $correct['parent'] == $parent) ? "selected=selected" : "") . ' >' . $parent . '</option>';
                            }
                            ?>
                        </select>
                        <span class="col-2"></span>
                        <?php
                        foreach ($parentArr as $parent) {
                            print '<select class="form-select form-select-sm w-25 col-3 ' . ((isset($correct['parent']) && $correct['parent'] == $parent) ? "" : "hide") . ' child" name="child" id="' . $parent . '">
                                <option value="" class="disable" disabled selected hidden>Please select a child</option>';
                            for ($k = 0; $k < sizeof($childArr[$parent]); $k++) {
                                print '<option value="' . $childArr[$parent][$k] . '"' . ((isset($correct['child']) && $correct['child'] == $childArr[$parent][$k]) ? "selected=selected required" : "") . ' >' . preg_replace("/[:]/", " ", $childArr[$parent][$k]) . '</option>';
                            }
                            print '</select>';
                        }
                        ?>
                        <span class="col-2"></span>
                    </div>
                </div>
                <div>
                    <!-- Form Values -->
                    <div class="row mb-1 mt-2 g-2 px-2">
                        <div class="col d-flex justify-content-center align-items-center">
                            <div class="form-outline">
                                <input type="text" id="fName" name="fName" class="form-control form-control-sm" required <?php echo (isset($correct['fName']) ? "value=" . $correct['fName'] : ""); ?>>
                                <label class="form-label" for="fName">Name</label>
                            </div>
                        </div>
                        <div class="col d-flex justify-content-center align-items-center">
                            <div class="form-outline">
                                <input type="text" id="lName" name="lName" class="form-control form-control-sm" required <?php echo (isset($correct['lName']) ? "value=" . $correct['lName'] : ""); ?>>
                                <label class="form-label" for="lName">Surname</label>
                            </div>
                        </div>
                        <div class="col d-flex justify-content-center align-items-center">
                            <div class="form-outline">
                                <input type="date" id="date" name="date" class="form-control form-control-sm" required <?php echo (isset($correct['date']) ? "value=" . $correct['date'] : ""); ?>>
                                <label class="form-label" for="date">Date</label>
                            </div>
                        </div>
                        <div class="col d-flex justify-content-center align-items-center">
                            <div class="form-outline">
                                <input type="text" id="activity" name="activity" class="form-control form-control-sm" <?php echo (isset($correct['activity']) ? "value=" . $correct['activity'] : ""); ?>>
                                <label class="form-label" for="activity">Activity (Optional)</label>
                            </div>
                        </div>
                        <div class="col d-flex justify-content-center align-items-center">
                            <div class="form-outline">
                                <input type="text" id="bFast" name="bFast" class="form-control form-control-sm" required <?php echo (isset($correct['bFast']) ? "value=" . $correct['bFast'] : ""); ?>>
                                <label class="form-label" for="bFast">Breakfast</label>
                            </div>
                        </div>
                        <div class="col d-flex justify-content-center align-items-center">
                            <div class="form-outline">
                                <input type="text" id="lunch" name="lunch" class="form-control form-control-sm" required <?php echo (isset($correct['lunch']) ? "value=" . $correct['lunch'] : ""); ?>>
                                <label class="form-label" for="lunch">Lunch</label>
                            </div>
                        </div>
                        <div class="col d-flex justify-content-center align-items-center">
                            <div class="form-outline">
                                <input type="number" step="0.5" min="10" id="temperature" name="temperature" class="form-control form-control-sm w-100" required <?php echo (isset($correct['temperature']) ? "value=" . $correct['temperature'] : ""); ?>>
                                <label class="form-label" for="temperature">Temperature</label>
                            </div>
                        </div>
                        <!-- Submit Button -->
                        <button type="submit" class="btn btn-primary btn-sm col-1">Enter</button>
                    </div>

                    <!-- Error Messages -->
                    <div class="row mb-3 g-2">
                        <div class="col text-center d-flex justify-content-center align-items-center">
                            <small class="text-danger mb-1">
                                <?php echo (isset($errorMsg['fName'])) ? $errorMsg['fName'] : "" ?>
                            </small>
                        </div>
                        <div class="col text-center d-flex justify-content-center align-items-center">
                            <small class="text-danger mb-1">
                                <?php echo (isset($errorMsg['lName'])) ? $errorMsg['lName'] : "" ?>
                            </small>
                        </div>
                        <div class="col text-center d-flex justify-content-center align-items-center">
                            <small class="text-danger mb-1">
                                <?php echo (isset($errorMsg['date'])) ? $errorMsg['date'] : "" ?>
                            </small>
                        </div>
                        <div class="col text-center d-flex justify-content-center align-items-center">
                            <small class="text-danger mb-1">
                                <?php echo (isset($errorMsg['activity'])) ? $errorMsg['activity'] : "" ?>
                            </small>
                        </div>
                        <div class="col text-center d-flex justify-content-center align-items-center">
                            <small class="text-danger mb-1">
                                <?php echo (isset($errorMsg['bFast'])) ? $errorMsg['bFast'] : "" ?>
                            </small>
                        </div>
                        <div class="col text-center d-flex justify-content-center align-items-center">
                            <small class="text-danger mb-1">
                                <?php echo (isset($errorMsg['lunch'])) ? $errorMsg['lunch'] : "" ?>
                            </small>
                        </div>
                        <div class="col text-center d-flex justify-content-center align-items-center">
                            <small class="text-danger mb-1">
                                <?php echo (isset($errorMsg['temperature'])) ? $errorMsg['temperature'] : "" ?>
                            </small>
                        </div>
                        <div class="col-1 text-center d-flex justify-content-center align-items-center">
                            <small class="text-danger mb-1">
                            </small>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- Pills content -->
</div>

<?php
require("footer.php");
echo $footer;
?>