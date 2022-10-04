<?php
    //Starts session, connects database
    session_start();
    require('../../mysql_connect.php');
    mysqli_select_db($db_connection,'s3050376');
    
    //Starts output buffering     
    ob_start();
    //Creates basic html structure
    echo ('<!DOCTYPE html>
           <html lang="en">
           <head>
            <meta charset="UTF-8">'
            . ((isset($_SESSION['accessLevel'])) ? '<script type="text/javascript"> var access='.$_SESSION['accessLevel'].'; var email="'.$_SESSION['email'].'"; </script>' :"").
            '<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
            <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/4.0.0/mdb.min.css" rel="stylesheet"/>
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
            <link rel="stylesheet" href="css/bootstrap.css">
            <link rel="stylesheet" href="css/style.css">
            <title>Enji Childcare</title>
           </head>
           <body class="min-vh-100 d-flex flex-column">
           <nav class="navbar navbar-expand-sm navbar-light bg-turq rounded m-1 mb-3">
            <div class="container-fluid">
                <a class="navbar-brand" href="index.php"><img src="images/logos/logo_hw_80.png"></a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mynavbar">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="mynavbar">
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="service.php">Services</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="testimonial.php">Testimonials</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="contact_us.php">Contact Us</a>
                        </li>    
                    </ul>
                    <ul class="navbar-nav d-flex mx-2">'
                    .(isset($_SESSION['accessLevel']) ?  
                        '<ul class="navbar-nav d-flex mx-2">
                        <span class="navbar-text px-2 py-0 border-end border-secondary dropdown">
                        <small class="nav-link dropdown-toggle pb-0" id="navbarScrollingDropdown" role="button" data-bs-toggle="dropdown">Welcome, '.$_SESSION['fName'].' '.$_SESSION['lName'].'</small>
                        <ul class="dropdown-menu dropdown-menu-end bg-turq w-75">
                            <li><a class="dropdown-item" href="add_child.php">Add Child</a></li>
                            <li><a class="dropdown-item" href="register.php">Register to a Plan</a></li>
                            <li><a class="dropdown-item" href="day_details.php">Daily Details</a></li>
                            <li><a class="dropdown-item" href="testimonial_add.php">Add Testimony</a></li>
                            <li class="align-items-center">
                                <hr class="w-75 my-2 m-auto dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="#">Settings or Profile</a></li>
                        </ul>
                    </span>
                    <li class="nav-item d-flex align-items-center">
                        <a class="nav-link" href="logout.php">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-box-arrow-right" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 2h-8A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0v2z" />
                                <path fill-rule="evenodd" d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3z" />
                            </svg>
                            Logout
                        </a>
                    </li>
                       ' : '<li class="nav-item d-flex align-items-center">
                            <a class="nav-link" href="login.php?id=login">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-box-arrow-in-right my-2" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd" d="M6 3.5a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v9a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-2a.5.5 0 0 0-1 0v2A1.5 1.5 0 0 0 6.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2h-8A1.5 1.5 0 0 0 5 3.5v2a.5.5 0 0 0 1 0v-2z"/>
                                    <path fill-rule="evenodd" d="M11.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 1 0-.708.708L10.293 7.5H1.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3z"/>
                                </svg>
                                Login
                            </a>
                        </li>
                        <li class="nav-item d-flex align-items-center">
                            <a class="nav-link" href="login.php?id=register">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-person-fill my-2" viewBox="0 0 16 16">
                                    <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z" />
                                </svg>
                                Register
                            </a>
                        </li>').    
                    '</ul>
                </div>
            </div>
        </nav>');
        (isset($_SESSION['accessLevel']) && $_SESSION['accessLevel'] == 1) ? include("sidebar.php"):"";

//Saves it to $header
$header = ob_get_contents();
ob_end_clean();
