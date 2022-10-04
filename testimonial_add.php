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
//Storage Variable for SQL result
$resultArr = array();
$resultArr = getCategory($resultArr, $db_connection);
//Control switch for empty contract 
$isEmpty = false;
//Array for category verification 
$categoryCheck = array('baby', 'wobbler', 'toddler', 'preschool');
//Control switch for to data inserted database successfully
$isRgstSccs = false;

if (sizeof($resultArr) == 0) $isEmpty = true;

if (!$isEmpty) {
  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $isCorrect = true;

    //Cleans submitted data from tags, slashes and outer whitespaces
    $data = sanitizeData($_POST);
    //Assign name and email from Session
    $data['fName'] = $_SESSION['fName'];
    $data['email'] = $_SESSION['email'];

    if (isset($data['service']) && in_array($data['service'], $categoryCheck)) {
      $correct['service'] = $data['service'];
    } else {
      //If necessary inputs are empty add error message and change isCorrect
      $errorMsg['service'] = "Invalid category, please try again.";
      $isCorrect = false;
    }

    if (isset($data['msg'])) {
      $correct['msg'] = $data['msg'];
    } else {
      //If necessary inputs are empty add error message and change isCorrect
      $errorMsg['msg'] = "Empty value detected.";
      $isCorrect = false;
    }

    //Control for empty values
    if (!$isCorrect || sizeof($correct) != 2) {
      //If necessary inputs are empty add error message and change isCorrect
      $errorMsg['empty'] = "Empty value detected.";
      $isCorrect = false;
    }

    //Control if testimonial exist
    if ($isCorrect = controlTestimonial($data, $db_connection)) {
      //Insert testimonial
      if ($isRgstSccs = insertMsg($data, $db_connection))
        $isRgstSccs = insertMsg($data, $db_connection);
      else {
        $errorMsg['db'] = 'We cannot process your query, please try again later.';
        $isCorrect = false;
      }
    } else {
      $errorMsg['exist'] = 'You already submitted testimonial for this service.';
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

//Gets distinct categories from contract-child-parent table
function getCategory($resultArr, $db_connection)
{
  //Count variable for mysqli_fetch
  $count = 0;
  //SQL query for getting the data 
  $stmt = mysqli_prepare($db_connection, "SELECT DISTINCT(c.category) FROM contract co
                                          INNER JOIN child c ON c.cID = co.child
                                          INNER JOIN parent p ON p.email = c.pID
                                          where pID = ?;");
  mysqli_stmt_bind_param($stmt, 's', $srvc);
  $srvc = $_SESSION['email'];
  mysqli_stmt_execute($stmt);
  $rslt = mysqli_stmt_get_result($stmt);

  while ($row = mysqli_fetch_array($rslt, MYSQLI_NUM)) {
    for ($i = 0; $i < sizeof($row); $i++) {
      $resultArr[$count][$i] = $row[$i];
    }
    $count++;
  }
  return $resultArr;
}


//Controls if testimony exist for particular parent and service
function controlTestimonial($data, $db_connection)
{
  $stmt = mysqli_prepare($db_connection, "SELECT * FROM testimonial WHERE pID=? AND service=?;");
  mysqli_stmt_bind_param($stmt, 'ss', $data['email'], $data['service']);
  mysqli_stmt_execute($stmt);
  mysqli_stmt_store_result($stmt);
  if (!mysqli_stmt_affected_rows($stmt)) return true;
  return false;
}

//Inserts values to database
function insertMsg($data, $db_connection)
{
  $data = escape($data, $db_connection);
  $date = date("Y-m-d");
  echo $date;
  //SQL query for parent table
  $stmt = mysqli_prepare($db_connection, "INSERT INTO testimonial VALUES(?, ?, ?, ?, 0);");
  mysqli_stmt_bind_param($stmt, 'ssss', $data['email'], $data['service'], $data['msg'], $date);
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


<div class="container w-75 flex-grow-1 d-flex flex-column">
  <h2 class="row d-flex justify-content-center pt-3 <?php echo ($isCorrect) ? 'hide' : ''; ?>">園児Enji Childcare Testimonial Portal</h2>
  <small class="text-danger d-flex justify-content-center">
    <?php
    echo (isset($errorMsg['empty'])) ? "$errorMsg[empty]" : "";
    echo (isset($errorMsg['exist'])) ? "$errorMsg[exist]" : "";
    echo (isset($errorMsg['db'])) ? "$errorMsg[db]" : "";
    ?>
  </small>
  <!-- Wrapper container -->
  <div class="flex-grow-1 d-flex flex-column pb-3 <?php echo ($isCorrect) ? 'hide' : ''; ?>">
    <form class="d-flex flex-column justify-content-between flex-grow-1 <?php echo ($isEmpty) ? "hide" : ""; ?>" action="testimonial_add.php" method="POST">
      <div class='d-flex justify-content-center my-2'>
        <!-- Error Message for category -->
        <small class="text-danger">
          <?php echo (isset($errorMsg['service'])) ? "$errorMsg[service]" : ""; ?>
        </small>
        <h4 class='px-1'>Category: </h4>
      </div>
      <div class=" d-flex justify-content-around align-items-center">
        <!-- Print categories -->
        <?php
        for ($i = 0; $i < sizeof($resultArr); $i++) {

          print " <!--Print Row -->
                    <div class = 'form-check'>
                      <input type='radio' class = 'form-check-input' id='" . $resultArr[$i][0] . "' name='service' value='" . $resultArr[$i][0] . "' required>
                      <label for='" . $resultArr[$i][0] . "' class = 'form-check-label m-1'>" . $resultArr[$i][0] . "</label><br>  
                    </div>";
        }
        ?>
      </div>
      <!-- Error Message for msg -->
      <small class="text-danger">
        <?php echo (isset($errorMsg['msg'])) ? "$errorMsg[msg]" : ""; ?>
      </small>
      <!-- Message input -->
      <div class="form-outline mb-4 flex-grow-1 d-flex">
        <textarea type="text" name="msg" id="msg" class="form-control flex-grow-1" required></textarea>
        <label class="form-label" for="msg">Message</label>
      </div>

      <!-- Form submit button -->
      <div class="d-grid">
        <button type="submit" class="btn btn-primary btn-lg">Submit</button>
      </div>
    </form>

    <div class='container w-75 flex-grow-1 d-flex flex-column justify-content-center align-items-center <?php echo ($isEmpty) ? "" : "hide"; ?>'>
      <div class='card shadow d-flex flex-column justify-content-center p-4'>
        <h2 class='text-center'>It appears that you didn't enroll your children to any program.</h2>
        <p class='text-center'>For to enroll your children please <a href='register.php'>click here</a></p>
      </div>
    </div>
  </div>
  <div class='min-h-100 d-flex flex-column align-items-center justify-content-center pt-5 <?php echo ($isCorrect) ? '' : "hide"; ?>'>
    <h3 class='text-center'>Thanks for give us your Testimony!!.</h3>
  </div>
</div>
<?php
require('footer.php');
echo $footer;
?>