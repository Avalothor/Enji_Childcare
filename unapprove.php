<?php
include("../mysql_connect.php");
mysqli_select_db($db_connection, 's3050376');

if (isset($_GET['key'])) {
    $arr = explode(":",$_GET['key']);
    $isCorrect = unapprove($arr[0], $arr[1], $db_connection);

    if ($isCorrect) echo "Approvement deleted.";
    else echo "Something went wrong";
}




//======================FUNCTIONS======================

function unapprove($email, $service, $db_connection)
{
    $stmt = mysqli_prepare($db_connection, "UPDATE testimonial SET approved=0 WHERE pID=? AND service=?;");
    mysqli_stmt_bind_param($stmt,"ss",$email,$service);
    mysqli_stmt_execute($stmt);
    if (mysqli_stmt_affected_rows($stmt) == 1) return true;
    return false;
}
