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


if($_SERVER['REQUEST_METHOD'] == 'POST' ){

  $isCorrect = true;
  $data = $_POST;

  $newPrice1 = $data['price1'];
  $newPrice3 = $data['price3'];
  $newPrice5 = $data['price5'];



  if(!is_numeric($newPrice1) || !is_numeric($newPrice3) || !is_numeric($newPrice5)){

      $isCorrect = false;

      echo "is_numeric problem";

      echo var_dump($newPrice1);
      echo var_dump($newPrice3);
      echo var_dump($newPrice5);
      
  }

  elseif(isset($newPrice1) == false || isset($newPrice3) == false || isset($newPrice5) == false){

    $isCorrect = false;

    echo "isset problem";
    echo var_dump($newPrice1);
    echo var_dump($newPrice3);
    echo var_dump($newPrice5);

  }

  else {

    $stmt = mysqli_prepare($db_connection, "UPDATE fee set feePerHour = ? WHERE day = 1;");
    mysqli_stmt_bind_param($stmt, 's', $newFeat1);
    mysqli_stmt_execute($stmt);

    $stmt1 = mysqli_prepare($db_connection, "UPDATE fee set feePerHour = ? WHERE day = 3;");
    mysqli_stmt_bind_param($stmt1, 's', $newFeat3);
    mysqli_stmt_execute($stmt1);


    $stmt2 = mysqli_prepare($db_connection, "UPDATE fee set feePerHour = ? WHERE day = 5;");
    mysqli_stmt_bind_param($stmt2, 's', $newFeat5);
    mysqli_stmt_execute($stmt2);
     
    }

   


  
  
}


?>

<div class="container w-75 flex-grow-1">
  <main class="container">
    <img src="https://live.staticflickr.com/65535/52058346227_c5ae88ea00_b.jpg" alt="banner" class="d-block w-100" />

    <h2 class="text-center"> Services </h2>

    <div class="informations">
      <p> In 園児Enji Childcare we charge per hour and the price varies depending of the amount of days choose. You can choose between full and part time and the ammount of days you will need our services, from 1, 3 and 5 days.</p><br>
      
      <form action="registration_edit.php" method="post">
        <div class = "align-content-center">

        <?php 
              if( $isCorrect == false) { print "<h2> Something went wrong, please fill all prices to proceed </h2> ";}
              else { print "<h2> Prices Updated </h2> ";  }
              
              
            ?>
      <ul class = "text-center fs-5" style="list-style: none;">
      <li>One day per week:   <input type="number" step="0.1" id="price1" name="price1" > € per hour </li>
      <li>Three days per week: <input type="number" step="0.1" id="price3" name="price3"> € per hour </li>
      <li>Five days per week:  <input type="number" step="0.1" id="price5" name="price5"> € per hour</li>
      </ul>
      </div>
      <div class = "d-flex flex-column p-5 ">
      <input type="submit" value ="Apply"  class = "btn btn-danger m-3 align-content-center">
      </div>
      </form>

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