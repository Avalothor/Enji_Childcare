<?php
require('header.php');
echo $header;

isset($_SESSION['accessLevel']) ? "" : header("Location: login.php?id=login");

//VARIABLES
//Storage for childs
$childrenArr = getChild($db_connection);
//Control for empty child array
$isEmpty = false;
if (sizeof($childrenArr) == 0) {$isEmpty = true;}
//Controls if all values are valid
$isCorrect = false;
//Storage for correct inputs
$correct = array();
//Storage for error messages
$errorMsg = array();
//Storage for date (yyy,mm,dd) and date differences from today
$date;
$dateDiff;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    //Cleans submitted data from tags, slashes and outer whitespaces
    $data = sanitizeData($_POST);
    list($data['fName'], $data['lName']) = explode(" ", $data['name']);

    //Controls if child exist in array
    for ($i = 0; $i < sizeof($childrenArr); $i++) {
        if (in_array($data['fName'], $childrenArr[$i]) && in_array($data['lName'], $childrenArr[$i])) {
            $isCorrect = true;
            //Get childID
            $data['cID'] = $childrenArr[$i]['cID'];
            //Rearrange the key
            $data['isFullTime'] = $data['isFullTime' . $i];
            //Clear data array
            unset($data['isFullTime' . $i]);
            $data['name'] = str_replace(" ", "", $data['name']);
            break;
        }
    }
    //Add error message if names are not in the array
    if ($isCorrect) {
        //Assign full/part time control
        if (isset($data['isFullTime'])) {
            if ($data['isFullTime']) {
                $data['isFullTime'] = 1;
            } else {
                $data['isFullTime'] = 0;
            }
        } else {
            $errorMsg['issFullTime'] = "Invalid choice, please select part or full time.";
            $isCorrect = false;
        }

        //Control days per week input
        if ($data['days'] == 1 || $data['days'] == 2 || $data['days'] == 3) {
            $correct['days'] = $data['days'];
        } else {
            $errorMsg['days'] = "Invalid day input, please try again.";
            $isCorrect = false;
        }

        if (!$isCorrect = controlContract($data, $db_connection)) {
            //If contract exists add error message
            $errorMsg['contract'] = 'This child has an active contract already.';
        }

        //If everything is valid this point try to add contract to database
        if ($isCorrect)
            if (!$isCorrect = createContract($data, $db_connection)) {
                $errorMsg['generic'] = "Your query couldn't processed. Please try again later.";
                $isCorrect = false;
            }
    } else {
        $errorMsg['generic'] = "There was an error while processing your query, please try again.";
    }
}

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

//Gets children of particular user from database
function getChild($db_connection)
{
    //Array that will be returned
    $childrenArr = array();
    //Count of rows
    $count = 0;

    if ($_SESSION['accessLevel']) {
        $stmt = mysqli_prepare($db_connection, "SELECT cID, fName, lName, category FROM child;");
    } else {
        $stmt = mysqli_prepare($db_connection, "SELECT cID, fName, lName, category FROM child  WHERE pID = ?;");
        mysqli_stmt_bind_param($stmt, 's', $_SESSION['email']);
    }

    mysqli_stmt_execute($stmt);
    $rslt = mysqli_stmt_get_result($stmt);
    while ($row = mysqli_fetch_array($rslt, MYSQLI_NUM)) {
        $childrenArr[$count]['cID'] = $row[0];
        $childrenArr[$count]['fName'] = $row[1];
        $childrenArr[$count]['lName'] = $row[2];
        $childrenArr[$count]['category'] = $row[3];
        $count++;
    }
    return $childrenArr;
}

//Control if an contract for the child exists
function controlContract($data, $db_connection)
{
    $data = escape($data, $db_connection);

    $stmt = mysqli_prepare($db_connection, "SELECT child FROM contract WHERE child=?;");
    mysqli_stmt_bind_param($stmt, 'i', $data['cID']);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    if (!mysqli_stmt_affected_rows($stmt)) return true;
    return false;
}

//Adds child to the database
function createContract($data, $db_connection)
{
    $data = escape($data, $db_connection);
    // SQL query for insertion to contract table
    $stmt = mysqli_prepare($db_connection, "INSERT INTO contract VALUES(null,?,?,?)");
    mysqli_stmt_bind_param($stmt, 'iii', $data['cID'], $data['days'], $data['isFullTime']);
    mysqli_stmt_execute($stmt);
    if (mysqli_stmt_affected_rows($stmt) == 1) return true;
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
?>

<div class="container w-75 flex-grow-1 d-flex flex-column <?php echo ($isEmpty) ? "hide" : ""; ?>">
    <main class="d-flex flex-column justify-content-center align-items-center flex-grow-1 container-fluid h-100">
        <h2 class="text-center align-self-stretch p-3">Register Child</h2>
        <div class="d-flex flex-column align-items-center w-100 flex-grow-1 pb-4 <?php echo ($isCorrect) ? 'hide' : ''; ?> ">
            <small class="text-danger text-center mb-1">
                <?php
                echo (isset($errorMsg['empty'])) ? "$errorMsg[empty]" : "";
                echo (isset($errorMsg['generic'])) ? "$errorMsg[generic]" : "";
                echo (isset($errorMsg['contract'])) ? "$errorMsg[contract]" : "";
                ?>
            </small>
            <?php
            for ($i = 0; $i < sizeof($childrenArr); $i++) {
                print '<div class="col-9 mb-2 row justify-content-around align-items-center p-3 shadow">
                    <span class="col-5">'
                    . $childrenArr[$i]['fName'] . ' ' . $childrenArr[$i]['lName'] .
                    '</span>
                    <span class="col">'
                    . '<small>' . $childrenArr[$i]['category'] . '</small>' .
                    '</span>
                    <button class="col-2 btn btn-sm btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#'
                    . str_replace(" ", "", $childrenArr[$i]['fName'] . $childrenArr[$i]['lName']) .
                    '">
                        Select
                    </button>
                    <div class="collapse col-9 mb-2 row justify-content-around align-items-center p-3 " id="'
                    . str_replace(" ", "", $childrenArr[$i]['fName'] . $childrenArr[$i]['lName']) .
                    '">
                        <hr class="w-75 m-auto">
                        <hr class="w-75 m-auto">
                        <form class="my-3" action="register.php" method="POST">
                            <h4 class="text-center">Full/Part Time</h4>
                            <div class="d-flex justify-content-around">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="isFullTime' . $i . '" id="' . $i . '0" value="0" required/>
                                    <label class="form-check-label" for="' . $i . '0"> Part Time </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="isFullTime' . $i . '" id="' . $i . '1"  value="1"/>
                                    <label class="form-check-label" for="' . $i . '1"> Full Time </label>
                                </div>
                            </div>
                            <hr class="my-3 w-75 m-auto">
                            <h4 class="text-center">Days per Week</h4>
                            <div class="d-flex justify-content-around">
                                <select class="form-select w-50" name="days" required>
                                    <option value="" class="disable" disabled selected hidden>Please select an option</option>
                                    <option value="1">One Day per Week</option>
                                    <option value="3">Three Days per Week</option>
                                    <option value="5">Five Days per Week</option>
                                </select>
                            </div>
                            <div class="d-flex justify-content-around">
                                <button type="submit" name="name" value="' . $childrenArr[$i]['fName'] . ' ' . $childrenArr[$i]['lName'] . '" class="btn btn-primary btn-block my-3">Register</button>
                            </div>
                        </form>
                    </div>
                   </div>';
            }
            ?>
        </div>
        <!-- Successful registration message -->
        <div class='row justify-content-around w-100 <?php echo ($isCorrect) ? '' : 'hide'; ?>'>
            <div class="col-7 shadow d-flex flex-column justify-content-center p-2 py-4">
                <h4 class='text-center text-dark'>Transaction is successful, your contract will be mailed to you.</h4>
                <p class='text-center mb-0'>For to return home page <a href="index.php">click</a> here.</p>
                <p class='text-center'>For to register a different child, <a href="index.php">click</a> here.</p>
            </div>
        </div>

    </main>
</div>
<div class='container w-75 flex-grow-1 d-flex flex-column justify-content-center align-items-center <?php echo ($isEmpty) ? "" : "hide"; ?>'>
    <div class='card shadow d-flex flex-column justify-content-center p-2 py-4'>
        <h2 class='text-center'>It appears that you didn't add your children.</h2>
        <p class='text-center'>For to add children please <a href='add_child.php'>click here</a></p>
    </div>
</div>

<?php
require('footer.php');
echo $footer;
?>