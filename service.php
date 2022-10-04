<?php
require('header.php');
echo $header;

// controling 
$isCorrect = false;

//fee variables 
$feeArr = array();

//initialising count
$count = 0;

//storing values form fee table
$stmt = mysqli_prepare($db_connection, "SELECT feePerHour FROM fee;");
mysqli_stmt_execute($stmt);
$rslt = mysqli_stmt_get_result($stmt);
while ($row = mysqli_fetch_array($rslt, MYSQLI_NUM)) {
    for ($i = 0; $i < sizeof($row); $i++) {  // only two columns in this table
        $feeArr[$count][$i] = $row[$i];
    }
    $count++;
}

?>

<div class="container w-75 flex-grow-1">
  <main class="container">
    <img src="https://live.staticflickr.com/65535/52058346227_c5ae88ea00_b.jpg" alt="banner" class="d-block w-100" />

    <h2 class="text-center"> Services </h2>

    <div class="informations">
      <p> In 園児Enji Childcare we charge per hour and the price varies depending of the amount of days choose. You can choose between full and part time and the ammount of days you will need our services, from 1, 3 and 5 days.</p><br>
      
      <ul class = "text-center fs-5" style="list-style: none;">
      <li>One day per week: <?php echo $feeArr[0][0]?> € per hour </li>
      <li>Three days per week: <?php echo $feeArr[1][0]?> € per hour </li>
      <li>Five days per week: <?php echo $feeArr[2][0]?> € per hour</li>
      </ul>

      <p> Under you can find more about our categories </p>
    </div>

    <div class="row row-cols-1 row-cols-md-2 g-4">
      <div class="col">
        <div class="card">
          <img src="images/photos/baby.jpg" class="card-img-top" alt="Babies">
          <div class="card-body">
            <h5 class="card-title">Babies</h5>
            <p class="card-text"> In this category we accept babies from 6 months to one year. Our professionals are trained to give all care and attention that your new born needs</p>
            <div class="row ">
              <div class="col d-flex justify-content-center">
                <button type="null" class="btn btn-success">6 months to 1 year</button>
              </div>
              <div class="col  d-flex justify-content-center">
                <button type="button" class="btn btn-warning">
                  <a class="nav-link active text-reset"  href="<?php echo isset($_SESSION['accessLevel']) ? "register.php" : "login.php?id=login"; ?>"> Register</a>
                </button>
              </div>

            </div>
          </div>
        </div>
      </div>
      <div class="col">
        <div class="card">
          <img src="images/photos/wobbler.jpg" class="card-img-top" alt="wobbler">
          <div class="card-body">
            <h5 class="card-title">Wobblers</h5>
            <p class="card-text">In this category we accept kids from one to two years. Our professionals are trained to give all care and attention that your Wobbler needs</p>
            <div class="row ">
              <div class="col d-flex justify-content-center">
                <button type="null" class="btn btn-success">1 to 2 years</button>
              </div>
              <div class="col  d-flex justify-content-center">
              <button type="button" class="btn btn-warning">
                  <a class="nav-link active text-reset"  href="<?php echo isset($_SESSION['accessLevel']) ? "register.php" : "login.php?id=login"; ?>"> Register</a>
                </button>
              </div>

            </div>

          </div>
        </div>
      </div>
      <div class="col">
        <div class="card">
          <img src="images/photos/toddler.jpg" class="card-img-top" alt="toddlers">
          <div class="card-body">
            <h5 class="card-title">Toddlers </h5>
            <p class="card-text">In this category we accept babies from 2 to 3 years. Our professionals are trained to give all care and attention that your Toddler needs</p>
            <div class="row ">
              <div class="col d-flex justify-content-center">
                <button type="null" class="btn btn-success">2 to 3 years</button>
              </div>
              <div class="col  d-flex justify-content-center">
              <button type="button" class="btn btn-warning">
                  <a class="nav-link active text-reset"  href="<?php echo isset($_SESSION['accessLevel']) ? "register.php" : "login.php?id=login"; ?>"> Register</a>
                </button>
              </div>

            </div>
          </div>
        </div>
      </div>
      <div class="col">
        <div class="card">
          <img src="images/photos/preschool2.jpg" class="card-img-top" alt="preschool">
          <div class="card-body">
            <h5 class="card-title">Preschool </h5>
            <p class="card-text">In this category we accept babies from 3 to 5 years. Our professionals are trained to give all care and attention that your kid needs</p>
            <div class="row ">
              <div class="col d-flex justify-content-center">
                <button type="null" class="btn btn-success">3 to 4 years</button>
              </div>
              <div class="col  d-flex justify-content-center">
              <button type="button" class="btn btn-warning">
                  <a class="nav-link active text-reset"  href="<?php echo isset($_SESSION['accessLevel']) ? "register.php" : "login.php?id=login"; ?>"> Register</a>
                </button>              </div>

            </div>
          </div>
        </div>
      </div>
    </div>
  </main>
</div>


<?php
require('footer.php');
echo $footer;
?>