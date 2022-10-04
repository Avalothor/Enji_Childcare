<?php
//Call header page
require_once('header.php');
echo $header;

//VARIABLES
//Controls if login or register fields filled correctly
$isCorrect = false;
//Storage for correct inputs
$correct = array();
//Storage for error messages
$errorMsg = array();
//Storage for query results
$parentResult = array();
$adminResult = array();
//Page where we forward user to
$page = "index.php";

$isRgstSccs = false;

//Start controlling the inputs 
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $isCorrect = true;

    //Cleans submitted data from tags, slashes and spaces on the outer-side
    $data = sanitizeData($_POST);


    //Control the submitted form id
    if ($_REQUEST['id'] == 'login') {

        //Makes email string to lowercase to process easier
        $data['loginEmail'] = strtolower($data['loginEmail']);

        //Controls email input
        if (filter_var($data['loginEmail'], FILTER_VALIDATE_EMAIL)) {
            //if Email input is valid, add it to correct input list
            $correct['loginEmail'] = $data['loginEmail'];
        } else {
            //if email input is not valid, add error message and change isCorrect to false
            $errorMsg['loginEmail'] = "Email value is not valid, please try again.";
            $isCorrect = false;
        }

        $correct['loginPassword'] = mysqli_real_escape_string($db_connection, $data['loginPassword']);

        //If email and password valid, look for user
        if ($isCorrect) {
            //First in parent table
            //SQL query for parent table
            $email = mysqli_real_escape_string($db_connection, $correct['loginEmail']);
            $pass = mysqli_real_escape_string($db_connection, $correct['loginPassword']);
            $stmtPrnt = mysqli_prepare($db_connection, "SELECT email, fName, lName FROM parent WHERE email=? AND pass=?;");
            mysqli_stmt_bind_param($stmtPrnt, 'ss', $email, $pass);
            mysqli_stmt_execute($stmtPrnt);
            mysqli_stmt_affected_rows($stmtPrnt);
            mysqli_stmt_bind_result($stmtPrnt, $parentResult[0], $parentResult[1], $parentResult[2]);
            mysqli_stmt_fetch($stmtPrnt);

            //Access level = user    
            $_SESSION['accessLevel'] = 0;

            $_SESSION['email'] = $parentResult[0];
            $_SESSION['fName'] = $parentResult[1];
            $_SESSION['lName'] = $parentResult[2];

            if (!isset($parentResult[0])) {
                //SQL query for admin table
                $stmtAdmn = mysqli_prepare($db_connection, "SELECT email, fName, lName FROM admin WHERE email=? AND pass=?;");
                mysqli_stmt_bind_param($stmtAdmn, 'ss', $email, $pass);
                mysqli_stmt_free_result($stmtPrnt);
                mysqli_stmt_execute($stmtAdmn);
                mysqli_stmt_bind_result($stmtAdmn, $adminResult[0], $adminResult[1], $adminResult[2]);
                mysqli_stmt_fetch($stmtAdmn);

                //Access level = admin    
                $_SESSION['accessLevel'] = 1;

                $_SESSION['email'] = $adminResult[0];
                $_SESSION['fName'] = $adminResult[1];
                $_SESSION['lName'] = $adminResult[2];

                if (!isset($adminResult[0])) {
                    //Access level = guest    
                    unset($_SESSION['accessLevel']);
                    //Set Error message and isCorrect false
                    $errorMsg['invalidUser'] = "This user is not exist. For registering <a href='login.php?id=register'> click here. </a>";
                    $isCorrect = false;
                }
            }
            echo "<br>";
        }

        //If user and password found and match forward to page this page called
        if ($isCorrect) {
            print '<meta http-equiv="refresh" content="1; url=index.php">';
        }
    } else {
        $isRgstSccs = true;

        //Makes email string to lowercase
        $data['registerEmail'] = strtolower($data['registerEmail']);

        //Controls fName
        if (isset($data['registerName']) && preg_match("/^[a-zA-Z ]{3,25}$/", $data['registerName'])) {
            $correct['registerName'] = $data['registerName'];
        } else {
            $errorMsg['registerName'] = "Invalid name input.";
            $isCorrect = false;
        }
        //Controls lName
        if (isset($data['registerSurname']) && preg_match("/^[a-zA-Z ]{3,25}$/", $data['registerSurname'])) {
            $correct['registerSurname'] = $data['registerSurname'];
        } else {
            $errorMsg['registerSurname'] = "Invalid last name input.";
            $isCorrect = false;
        }

        //Controls email
        if (filter_var($data['registerEmail'], FILTER_VALIDATE_EMAIL)) {
            //Control if email exist in database
            if (controlPrntEmail($data['registerEmail'], $db_connection) && controlAdmnEmail($data['registerEmail'], $db_connection)) {
                $correct['registerEmail'] = $data['registerEmail'];
            } else {
                //If email exists in database add error message and change isCorrect
                $errorMsg['duplicateEmail'] = "This email already exists, for sign in <a href='login.php?id=login'>click here.</a> If you forget your password please fill a form by <a href='contact_us.php'>clicking here.</a>";
                $isCorrect = false;
            }
        } else {
            //if email input is not valid, add error message and change isCorrect to false
            $errorMsg['registerEmail'] = "Email value is not valid, please try again.";
            $isCorrect = false;
        }

        //Controls password input
        $isLower = preg_match("/[A-Z]/", $data['registerPassword']);
        $isUpper = preg_match("/[a-z]/", $data['registerPassword']);
        $isNumeric = preg_match("/[\d]/", $data['registerPassword']);
        $isSpecialChar = preg_match("/[\W_]/", $data['registerPassword']);

        if ($isLower && $isUpper && $isNumeric && $isSpecialChar && strlen($data['registerPassword']) >= 8) {
            if (!strcmp($data['registerPassword'], $data['registerRepeatPassword'])) {
                //Escapes special characters in password input
                $correct['registerPassword'] = mysqli_real_escape_string($db_connection, $data['registerPassword']);
            } else {
                //if passwords doesn't match, add error message and change isCorrect
                $errorMsg['registerRepeatPassword'] = "Passwords doesn't match.";
                $isCorrect = false;
            }
        } else {
            //if password input is not valid, add error message and change isCorrect to false
            $errorMsg['registerPassword'] = 'Password should be at least 8 characters in length and should include at least one upper, one lower case letter, one number, and one special character.';
            $isCorrect = false;
        }

        //Control the EULA
        if (isset($data['terms']) && $data['terms'] == 'on') {
            $correct['terms'] = true;
        } else {
            $errorMsg['terms'] = "Please read and agree to terms if you want to proceed.";
            $isCorrect = false;
        }

        if (sizeof($correct) != 5) {
            //If all parts are not filled, add error message and change isCorrect to false
            $errorMsg['empty'] = "Empty or invalid value detected. Please fill all options correctly.";
            $isCorrect = false;
        }

        if ($isCorrect) $isRgstSccs = addUser($correct, $db_connection);

        if (!$isRgstSccs) {
            //If insert is not successfull add an error message, and change isCorrect 
            $errorMsg['insertFail'] = "There was something wrong with sign up. Please try again.";
            $isCorrect = false;
        }
    }
}

//======================FUNCTIONS======================

//Sanitize all data by calling appropriate method
function sanitizeData($data)
{
    //If argument is an array recursive call for each element
    if (is_array($data)) {
        foreach ($data as $key => $value)
            //Recursive run for each value except passwords
            if (!strpos($key, 'Password'))
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

//Controls if email exists in parent database
function controlPrntEmail($data, $db_connection)
{
    //SQL query for parent table
    $stmt = mysqli_prepare($db_connection, "SELECT email FROM parent WHERE email=?");
    mysqli_stmt_bind_param($stmt, 's', $data);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_affected_rows($stmt);
    mysqli_stmt_bind_result($stmt, $result);

    while (mysqli_stmt_fetch($stmt))
        if (strcmp($result, $data) == 0)
            return false;

    return true;
}

//Controls if email exists in admin database
function controlAdmnEmail($data, $db_connection)
{
    //SQL query for parent table
    $stmt = mysqli_prepare($db_connection, "SELECT email FROM admin WHERE email=?");
    mysqli_stmt_bind_param($stmt, 's', $data);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_affected_rows($stmt);
    mysqli_stmt_bind_result($stmt, $result);

    while (mysqli_stmt_fetch($stmt))
        if (strcmp($result, $data) == 0)
            return false;

    return true;
}

function addUser($data, $db_connection)
{
    $data = escape($data, $db_connection);
    $address = 'placeholder';
    //SQL query for parent table
    $stmt = mysqli_prepare($db_connection, "INSERT INTO parent VALUES(?,?,?,?,?)");
    mysqli_stmt_bind_param($stmt, 'sssss', $data['registerEmail'], $data['registerName'], $data['registerSurname'], $data['registerPassword'], $address);
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
<div class="container w-75 flex-grow-1  <?php echo ($isRgstSccs && $isCorrect) ? "hide" : ""; ?>">
    <!-- Form Part -->
    <div class="d-flex justify-content-center p-2 h-100">
        <div class="w-50">

            <!-- Pill navs -->
            <ul class="nav nav-pills nav-justified mb-3" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link <?php echo (isset($_REQUEST['id']) && $_REQUEST['id'] == 'login') ? "active" : ""; ?>" id="home-tab" data-bs-toggle="pill" data-bs-target="#pills-login" type="button" role="tab">Login</button>
                </li>
                <li class="nav-item ms-1" role="presentation">
                    <button class="nav-link <?php echo (!isset($_REQUEST['id']) || $_REQUEST['id'] != 'login') ? "active" : ""; ?>" id="profile-tab" data-bs-toggle="pill" data-bs-target="#pills-register" type="button" role="tab">Register</button>
                </li>
            </ul>
            <!-- Pill navs -->

            <!-- Pills content -->
            <div class="tab-content">
                <div class="tab-pane fade <?php echo (isset($_REQUEST['id']) && $_REQUEST['id'] == 'login') ? "show active" : ""; ?>" id="pills-login" role="tabpanel">
                    <form action="login.php?id=login" method="POST">
                        <small class="text-danger d-flex justify-content-center">
                            <span><?php echo isset($errorMsg['invalidUser']) ? $errorMsg['invalidUser'] : "" ?></span>
                        </small>

                        <p class="text-center">Sign in:</p>

                        <!-- Error Message for email -->
                        <small class="text-danger mb-1">
                            <?php echo (isset($errorMsg['loginEmail'])) ? "$errorMsg[loginEmail]" : ""; ?>
                        </small>
                        <!-- Email input -->
                        <div class="form-outline mb-4">
                            <input type="email" name="loginEmail" id="loginEmail" class="form-control" required value=<?php echo (isset($correct['loginEmail'])) ? "$correct[loginEmail]" : " " ?>>
                            <label class="form-label" for="email">Email</label>
                        </div>

                        <!-- Error Message for password -->
                        <small class="text-danger mb-1">
                            <?php
                            if (isset($errorMsg['loginPassword']))
                                echo "$errorMsg[loginPassword]";
                            ?>
                        </small>
                        <!-- Password input -->
                        <div class="form-outline mb-4">
                            <input type="password" name="loginPassword" id="loginPassword" class="form-control" required>
                            <label class="form-label" for="loginPassword">Password</label>
                        </div>

                        <!-- 2 column grid layout -->
                        <div class="row mb-4">
                            <div class="col-md-6 d-flex justify-content-center">
                                <!-- Checkbox -->
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch" id="switchRemember" name="switchRemember">
                                    <label class="form-check-label" for="switchRemember">Remember me</label>
                                </div>
                            </div>

                            <div class="col-md-6 d-flex justify-content-center">
                                <!-- Simple link -->
                                <a href="contact_us.php">Forgot password?</a>
                            </div>
                        </div>

                        <!-- Submit button -->
                        <button type="submit" class="btn btn-primary btn-block mb-4">Sign in</button>

                        <!-- Register buttons -->
                        <div class="text-center">
                            <p>Not a member? <a href="login.php?id=register">Register</a></p>
                        </div>
                    </form>
                </div>

                <div class="tab-pane fade <?php echo (!isset($_REQUEST['id']) || $_REQUEST['id'] != 'login') ? "show active" : ""; ?>" id="pills-register" role="tabpanel">
                    <form action="login.php?id=register" method="POST">
                        <small class="text-danger d-flex justify-content-center">
                            <span><?php echo isset($errorMsg['insertFail']) ? $errorMsg['insertFail'] : "" ?></span>
                            <span><?php echo isset($errorMsg['empty']) ? $errorMsg['empty'] : "" ?></span>
                        </small>

                        <p class="text-center">Sign Up:</p>

                        <!-- Error Message for fName -->
                        <small class="text-danger mb-1">
                            <?php echo (isset($errorMsg['registerName'])) ? "$errorMsg[registerName]" : ""; ?>
                        </small>
                        <!-- Name input -->
                        <div class="form-outline mb-4">
                            <input type="text" id="registerName" name="registerName" class="form-control" required <?php echo (isset($correct['registerName'])) ? "value=$correct[registerName]" : ""; ?>>
                            <label class="form-label" for="registerName">Name</label>
                        </div>

                        <!-- Error Message for lName -->
                        <small class="text-danger mb-1">
                            <?php echo (isset($errorMsg['registerSurname'])) ? "$errorMsg[registerSurname]" : ""; ?>
                        </small>
                        <!-- Surname input -->
                        <div class="form-outline mb-4">
                            <input type="text" id="registerSurname" name="registerSurname" class="form-control" required <?php echo (isset($correct['registerSurname'])) ? "value=$correct[registerSurname]" : ""; ?>>
                            <label class="form-label" for="registeSurname">Last Name</label>
                        </div>

                        <!-- Error Message for email -->
                        <small class="text-danger mb-1">
                            <?php echo (isset($errorMsg['registerEmail'])) ? "$errorMsg[registerEmail]" : "";
                            echo (isset($errorMsg['duplicateEmail'])) ? "$errorMsg[duplicateEmail]" : "";  ?>
                        </small>
                        <!-- Email input -->
                        <div class="form-outline mb-4">
                            <input type="email" id="registerEmail" name="registerEmail" class="form-control" required <?php echo (isset($correct['registerEmail'])) ? "value=$correct[registerEmail]" : ""; ?>>
                            <label class="form-label" for="registerEmail">Email</label>
                        </div>

                        <!-- Error Message for password -->
                        <small class="text-danger mb-1">
                            <?php echo (isset($errorMsg['registerPassword'])) ? "$errorMsg[registerPassword]" : ""; ?>
                        </small>
                        <!-- Password input -->
                        <div class="form-outline mb-4">
                            <input type="password" id="registerPassword" name="registerPassword" class="form-control" required>
                            <label class="form-label" for="registerPassword">Password</label>
                        </div>

                        <!-- Error Message for password -->
                        <small class="text-danger mb-1">
                            <?php echo (isset($errorMsg['registerRepeatPassword'])) ? "$errorMsg[registerRepeatPassword]" : ""; ?>
                        </small>
                        <!-- Repeat Password input -->
                        <div class="form-outline mb-4">
                            <input type="password" id="registerRepeatPassword" name="registerRepeatPassword" class="form-control" required>
                            <label class="form-label" for="registerRepeatPassword">Repeat password</label>
                        </div>
                               
                        <!-- Error Message for terms -->
                        <small class="text-danger d-flex justify-content-center mb-1">
                            <?php echo (isset($errorMsg['terms'])) ? "$errorMsg[terms]" : ""; ?>
                        </small>
                        <!-- Switch -->
                        <div class="form-check form-switch d-flex justify-content-center mb-4">
                            <input class="form-check-input" type="checkbox" role="switch" id="switchRegister" name="terms" required />
                            <label class="form-check-label" for="switchRegister">I have read and agree to the terms</label>
                        </div>

                        <!-- Submit button -->
                        <button type="submit" class="btn btn-primary btn-block mb-3">Sign up</button>
                    </form>
                </div>
            </div>
            <!-- Pills content -->
        </div>
    </div>
</div>
<!-- Register message -->
<div class='container w-75 flex-grow-1 d-flex flex-column justify-content-center align-items-center <?php echo ($isRgstSccs && $isCorrect) ? "" : "hide"; ?>'>
    <div class="card col-7 shadow d-flex flex-column justify-content-center p-2 py-4">
        <h2 class='text-center'>You are successfully sign up.</h2>
        <p class='text-center'>You will be forwarded to login page.</p>
    </div>
</div>


<?php
require_once('footer.php');
echo $footer;
($isRgstSccs && $isCorrect) ? print '<meta http-equiv="refresh" content="1; url=login.php?id=login">' : '';
?>