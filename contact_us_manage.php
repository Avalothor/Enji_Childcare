<?php
require('header.php');
echo $header;

(isset($_SESSION['accessLevel']) && $_SESSION['accessLevel']) ? "" : header("Location: index.php");

//Storage Variable for SQL result
$resultArr = array();
//Count variable for mysqli_fetch
$count = 0;
//Formatted id for mysql and other process
(isset($_REQUEST['id']) ? $msg = preg_replace("/[-]/", "", $_REQUEST['id']) : $msg = NULL);

$stmt = mysqli_prepare($db_connection, "SELECT c.query, c.email, c.name, c.date, c.msg, c.phone FROM contact_us c; ");
//mysqli_stmt_bind_param($stmt, 'ssssss', $msg);
mysqli_stmt_execute($stmt);
$rslt = mysqli_stmt_get_result($stmt);
while ($row = mysqli_fetch_array($rslt, MYSQLI_NUM)) {
    for ($i = 0; $i < sizeof($row); $i++) {
        $resultArr[$count][$i] = $row[$i];  // contact us table has 6 columns 
    }
    $count++;
}

?>
<div class="container w-75 flex-grow-1 py-4">

    <h2 class='row d-flex justify-content-center mb-5'> 園児 Enji Contact Us Manage</h2>

    <?php
    // TODO : 
    for ($i = 0; $i < $count; $i++) {

        print " 
        <div class = 'd-flex justify-content-center align-items-center'>
            <div class= 'card col-7 mb-2 justify-content-center align-items-center p-3 shadow'>
                <div class = 'row'>
                    <div class = 'card-title col-9 align-items-center'>                           
                        <h5> Query N°" . $resultArr[$i][0] . "</h5>
                        Email: " . $resultArr[$i][1] . " Date: " . $resultArr[$i][3] . "
                    </div>  
                    <button class='btn btn-primary col' type='button' data-bs-toggle='collapse' data-bs-target='#query" . $resultArr[$i][0] . "'> 
                        Expand 
                    </button>
                </div>
                <div class='collapse p-0' id='query" . $resultArr[$i][0] . "'>
                    <span>
                        <div class = ' d-flex  justify-content-center'>
                            Name: " . $resultArr[$i][2] . ",  Phone: " . $resultArr[$i][5] . "
                        </div >
                        <hr class='w-10 m-auto'>
                        <div class = ' d-flex justify-content-left'  >
                            <h5  class='p-2'> Message: </h5>
                        </div>
                        <div class = ' d-flex justify-content-center    '>
                            " . $resultArr[$i][4] . "         
                        </div>
                    </span>
                </div>
            </div>
        </div>
        ";
    }
    ?>
    
</div>






<?php
require('footer.php');
echo $footer;
?>