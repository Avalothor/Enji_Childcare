<?php
require('header.php');
echo $header;

isset($_SESSION['accessLevel']) ? "" : header("Location: login.php?id=login");

//VARIABLES
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
  $isCorrect = true;

  //Cleans submitted data from tags, slashes and outer whitespaces
  $data = sanitizeData($_POST);

  //Control fname input
  if (preg_match("/^[a-zA-Z ]{3,25}$/", $data['fname'])) {
    $correct['fname'] = strtolower($data['fname']);
  } else {
    $errorMsg['fname'] = "Invalid fname input.";
    $isCorrect = false;
  }

  //Control lname input
  if (preg_match("/^[a-zA-Z ]{3,25}$/", $data['lname'])) {
    $correct['lname'] = strtolower($data['lname']);
  } else {
    $errorMsg['lname'] = "Invalid lname input.";
    $isCorrect = false;
  }

  //Control date input
  //Seperate year, month and day
  $date = explode("-", $data['date']);

  //If date is valid control for the age of child
  if (strcmp($data['date'], '') && checkdate($date[1], $date[2], $date[0]) && $date[0] <= date('Y')) {
    //Get the date difference in days
    $dateDiff = date_diff(date_create(date('Y-m-d')), date_create($data['date']))->format('%a');
    //Controls age of the child, 6 months to 5 years
    if ($dateDiff > 180 && $dateDiff < 1828)
      $correct['date'] = $data['date'];
    else {
      $errorMsg['date'] =  'Your child must be under age of 5 and older than 6 months.';
      $isCorrect = false;
    }
  } else {
    $errorMsg['date'] = "Invalid date value.";
    $isCorrect = false;
  }

  if ($isCorrect)
    if (!$isCorrect = addChild($data, $db_connection))
      $errorMsg['dbError'] = "Your query couldn't processed. Please try again later.";
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

//Adds child to the database
function addChild($data, $db_connection)
{
  $data = escape($data, $db_connection);
  $category = calculateCategory($data['date']);
  // SQL query for parent table
  $stmt = mysqli_prepare($db_connection, "INSERT INTO child VALUES(null,?,?,?,?,?)");
  mysqli_stmt_bind_param($stmt, 'sssss', $data['fname'], $data['lname'], $data['date'], $_SESSION['email'], $category);
  mysqli_stmt_execute($stmt);
  if (mysqli_stmt_affected_rows($stmt) == 1) return true;
  else print mysqli_error($db_connection);
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

//Calculates category (from age) of child
function calculateCategory($data)
{

  $dateDiff = date_diff(date_create(date('Y-m-d')), date_create($data))->format('%a');
  if ($dateDiff < 365) return 'baby';
  else if ($dateDiff < 730) return 'wobbler';
  else if ($dateDiff < 4095) return 'toddler';
  else return 'preschool';
}
?>

<div class="container w-75 flex-grow-1">
  <main class="bg-image d-flex flex-column justify-content-center">
    <div class="row">
      <div class="col row justify-content-around  <?php echo ($isCorrect) ? 'hide' : ''; ?>">
        <form action="add_child.php" method="post" class="card shadow-lg col-5">

          <h2 class="d-flex justify-content-around p-3 ">Register Child</h2>
          <!-- Error Message for empty or invalid value -->
          <small class="text-danger text-center mb-1">
            <?php
            echo (isset($errorMsg['empty'])) ? "$errorMsg[empty]" : "";
            echo (isset($errorMsg['dbError'])) ? "$errorMsg[dbError]" : "";
            ?>
          </small>

          <!-- Error Message for fname -->
          <small class="text-danger mb-1">
            <?php echo (isset($errorMsg['fname'])) ? "$errorMsg[fname]" : ""; ?>
          </small>
          <!-- fname input -->
          <div class="form-outline mb-4">
            <input type="text" name="fname" id="fname" class="form-control" <?php echo (isset($correct['fname'])) ? "value=$correct[fname]" : ""; ?>>
            <label class="form-label" for="fname">First Name</label>
          </div>

          <!-- Error Message for lname -->
          <small class="text-danger mb-1">
            <?php echo (isset($errorMsg['lname'])) ? "$errorMsg[lname]" : ""; ?>
          </small>
          <!-- lname input -->
          <div class="form-outline mb-4">
            <input type="text" name="lname" id="lname" class="form-control" <?php echo (isset($correct['lname'])) ? "value=$correct[lname]" : ""; ?>>
            <label class="form-label" for="lname">Last Name</label>
          </div>

          <!-- Error Message for age -->
          <small class="text-danger mb-1">
            <?php echo (isset($errorMsg['date'])) ? "$errorMsg[date]" : ""; ?>
          </small>
          <!-- age input -->
          <div class="form-outline mb-4">
            <!-- Check if name is a class or it is ok to be lname -->
            <input type="date" name="date" id="date" class="form-control" <?php echo (isset($correct['date'])) ? "value=" . $correct['date'] : "" ?> max="<?php echo date('Y-m-d', strtotime('-6 months', strtotime(date('Y-m-d')))); ?>" min="<?php echo date('Y-m-d', strtotime('-5 years', strtotime(date('Y-m-d')))); ?>" >
            <label class="form-label" for="date">Date of Birth</label>
          </div>


          <button type="submit" class="btn btn-primary btn-block my-3">Register</button>
        </form>
      </div>

      <!-- Successful registration message -->
      <div class='row justify-content-around <?php echo ($isCorrect) ? '' : 'hide';
                                              ?>'>
        <div class="card col-7 shadow d-flex flex-column justify-content-center p-2 py-4">
          <h2 class='text-center text-dark'>Your child registered successfully.</h2>
          <p class='text-center mb-0'>For to enroll your children to a program <a href="register.php">click</a> here.</p>
          <p class='text-center mb-0'>To return to previous page <a href="add_child.php">click</a> here.</p>
          <p class='text-center'>For home page <a href="index.php">click</a> here.</p>
        </div>
      </div>
    </div>
  </main>
</div>

<?php
require('footer.php');
echo $footer;
?>