<?php
//Redirect after a little delay
header("Refresh:0.75, url=index.php");

//Call header
require_once("header.php");
echo $header;

//Unset and delete session
session_unset();
session_destroy();

//Print message
print '<div class="container w-75 flex-grow-1 d-flex flex-column justify-content-center align-items-center">
        <div class="card col-7 shadow d-flex flex-column justify-content-center p-2 py-4">
            <h2 class="text-center">You are successfully logged out.</h2>
            <p class="text-center">You will be forwarded to <a href="index.php">home</a> page.</p>
        </div>
       </div>';
           
//Call footer
require_once("footer.php");
echo $footer;
?>