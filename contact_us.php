<?php
require('header.php');
echo $header;

//VARIABLES
//Controls if all values are valid
$isCorrect = false;
//Storage for correct inputs
$correct = array();
//Storage for error messages
$errorMsg = array();



if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $isCorrect = true;

  //Cleans submitted data from tags, slashes and outer whitespaces
  $data = sanitizeData($_POST);
  //Controls name input
  if (preg_match("/^[a-zA-Z ]{0,25}$/", $data['name'])) {
    $correct['name'] = $data['name'];
  } else {
    $errorMsg['name'] = "Invalid name input.";
    $isCorrect = false;
  }

  //Makes email string to lowercase to process easier
  $data['email'] = strtolower($data['email']);

  //Controls email input
  if (filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
    //if Email input is valid, add it to correct input list
    $correct['email'] = $data['email'];
  } else {
    //if email input is not valid, add error message and change isCorrect to false
    $errorMsg['email'] = "Email value is not valid, please try again.";
    $isCorrect = false;
  }

  //Format phone input
  $data['phone'] = formatPhone($data['phone']);
  //Controls phone
  if (preg_match('/^[0-9]{10}+$/', $data['phone'])) {
    $correct['phone'] = mysqli_real_escape_string($db_connection, $data['phone']);
  } else {
    //if phone number is not valid add error message and change isCorrect to false
    $errorMsg['phone'] = "Phone number is invalid";
    $isCorrect = false;
  }

  if (isset($correct['name']) && isset($correct['email']) && isset($data['msg'])) {
    $correct['msg'] = $data['msg'];
    $isCorrect = insertMsg($correct, $db_connection);
  } else {
    //If necessary inputs are empty add error message and change isCorrect
    $errorMsg['empty'] = "Empty value detected.";
    $isCorrect = false;
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

//Cleans whitespaces and dashes inside the phone data
function formatPhone($data)
{
  $data = str_replace(" ", "", $data);
  $data = str_replace("-", "", $data);
  return $data;
}

//Inserts values to database
function insertMsg($data, $db_connection)
{
  //Initiate form data if logged in
  $data = initiateData($data);
  $data = escape($data, $db_connection);
  $date = date("Y-m-d");
  //SQL query for parent table
  if (isset($data['phone'])) {
    $stmt = mysqli_prepare($db_connection, "INSERT INTO contact_us VALUES(NULL, ?, ?, ?, ?, ?);");
    mysqli_stmt_bind_param($stmt, 'sssss', $data['email'], $data['name'], $date, $data['msg'], $data['phone']);
  } else {
    $stmt = mysqli_prepare($db_connection, "INSERT INTO contact_us VALUES(NULL, ?, ?, ?, ?, NULL);");
    mysqli_stmt_bind_param($stmt, 'ssss', $data['email'], $data['name'], $date, $data['msg']);
  }
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

//Adds name and email to data if logged in
function initiateData($data)
{
  if (isset($_SESSION['accessLevel']) && !$_SESSION['accessLevel']) {
    $data["email"] = $_SESSION['email'];
    $data["name"] = $_SESSION['fName'] . " " . $_SESSION['lName'];
  }
  return $data;
}
?>

<div class="container w-75 flex-grow-1">
  <main>
    <h2 class="row d-flex justify-content-center pt-3 <?php echo ($isCorrect) ? 'hide' : ''; ?>">Contact Us</h2>

    <!-- Wrapper container -->
    <div class="row row-cols-1 row-cols-md-2 g-4 pt-2  <?php echo ($isCorrect) ? 'hide' : ''; ?>">

      <div class="col d-flex justify-content-around ">
        <iframe class=" border border-dark" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d4765.277511355918!2d-6.2759982623151105!3d53.331821108232404!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x48670c1833b915c7%3A0x4f83acae16f5062e!2sGriffith%20College!5e0!3m2!1sen!2sie!4v1652461612181!5m2!1sen!2sie" width="500" height="400" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
      </div>

      <div class=col>
        <form class="px-3 pb-3" action="contact_us.php" method="POST">
          <!-- Error Message for empty value -->
          <small class="text-danger">
            <?php echo (isset($errorMsg['empty'])) ? "$errorMsg[empty]" : ""; ?>
          </small>

          <!-- Error Message for name -->
          <small class="text-danger">
            <?php echo (isset($errorMsg['name'])) ? "$errorMsg[name]" : ""; ?>
          </small>
          <!-- name input -->
          <div class="form-outline mb-4">
            <input type="text" name="name" id="name" class="form-control" <?php
                                                                          echo (isset($correct['name'])) ? "value=$correct[name]" : "";
                                                                          echo (isset($_SESSION['accessLevel']) && !$_SESSION['accessLevel']) ? "value='$_SESSION[fName] $_SESSION[lName]' readonly" : "";
                                                                          ?>>
            <label class="form-label" for="name">Name</label>
          </div>

          <!-- Error Message for email -->
          <small class="text-danger">
            <?php echo (isset($errorMsg['email'])) ? "$errorMsg[email]" : ""; ?>
          </small>
          <!-- Email input -->
          <div class="form-outline mb-4">
            <input type="email" name="email" id="email" class="form-control" required <?php
                                                                                      echo (isset($correct['email'])) ? "value=$correct[email]" : " ";
                                                                                      echo (isset($_SESSION['accessLevel']) && !$_SESSION['accessLevel']) ? "value='$_SESSION[email]' readonly" : "";
                                                                                      ?>>
            <label class="form-label" for="email">Email</label>
          </div>

          <!-- Error Message for email -->
          <small class="text-danger">
            <?php echo (isset($errorMsg['phone'])) ? "$errorMsg[phone]" : ""; ?>
          </small>
          <!-- Phone address input -->
          <div class="form-outline mb-4">
            <input type="tel" name="phone" id="phone" class="form-control" placeholder="805-260-1126" <?php echo (isset($correct['phone']) ? "value=" . $correct['phone'] : "") ?>>
            <label class="form-label" for="phone">Phone number (Optional)</label>
          </div>

          <!-- Error Message for email -->
          <small class="text-danger">
            <?php echo (isset($errorMsg['msg'])) ? "$errorMsg[msg]" : ""; ?>
          </small>
          <!-- Message input -->
          <div class="form-outline mb-4">
            <textarea type="text" name="msg" id="msg" class="form-control" style="height: 11rem;" required></textarea>
            <label class="form-label" for="msg">Message</label>
          </div>

          <!-- Form submit button -->
          <div class="d-grid">
            <button type="submit" class="btn btn-primary btn-lg">Submit</button>
          </div>

        </form>
      </div>
    </div>
    <div class='min-h-100 d-flex flex-column align-items-center justify-content-center pt-5 <?php echo ($isCorrect) ? '' : "hide"; ?>'>
      <h3 class='text-center'>Thanks for reaching us.</h3>
      <p class='text-center'>Your query is very important to us. We will be in contact in 48 hours.</p>
    </div>
  </main>
</div>
<?php
require('footer.php');
echo $footer;
?>