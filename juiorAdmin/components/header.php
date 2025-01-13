<!DOCTYPE html>
<html lang="en" class="">
<?php

$current_file = basename($_SERVER['PHP_SELF'], ".php");
?>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo ucfirst($current_file); ?></title>
    <link href="assets/css/lib/chartist/chartist.min.css" rel="stylesheet">
    <link href="assets/css/lib/font-awesome.min.css" rel="stylesheet">
    <link href="assets/css/lib/themify-icons.css" rel="stylesheet">
    <link href="assets/css/lib/owl.carousel.min.css" rel="stylesheet" />
    <link href="assets/css/lib/owl.theme.default.min.css" rel="stylesheet" />
    <link href="assets/css/lib/weather-icons.css" rel="stylesheet" />
    <link href="assets/css/lib/menubar/sidebar.css" rel="stylesheet">
    <link href="assets/css/lib/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/lib/unix.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <link href="assets/css/lib/calendar2/semantic.ui.min.css" rel="stylesheet">
    <link href="assets/css/lib/calendar2/pignose.calendar.min.css" rel="stylesheet">
    <link href="assets/css/lib/font-awesome.min.css" rel="stylesheet">
    <link href="assets/css/lib/themify-icons.css" rel="stylesheet">
    <link href="assets/css/lib/menubar/sidebar.css" rel="stylesheet">
    <link href="assets/css/lib/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/lib/unix.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="customCss/add-company.css">
    <link rel="stylesheet" href="customCss/listCompanies.css">
    <link rel="stylesheet" href="customCss/edit-company.css">
    <style>
        .form-control {
            transition: all 0.3s ease;
            border: 2px solid #ccc;
            padding: 10px;
            border-radius: 4px;
        }

        .form-control:focus {
            border-color: #ff9800;
            background-color: #fff3e0;
            box-shadow: 0 0 5px rgba(255, 152, 0, 0.8);
        }

        #close-popup {
            position: absolute;
            top: 10px;
            right: 10px;
        }
        div {
            color: black;

        }

        input {
            color: black;
            border: solid 2px black;
        }

        label {
            color: black;
        }

        /* Все элементы с тегом <p> будут иметь красный цвет */
        p {
            color: red;
        }
        #menuh {
            background-color: black;
            ;
            position: fixed;
            /* Фиксированное положение */
        }

        li {
            color: white;
        }
        #sidebar-css{
            background-color: dodgerblue;
        }
    </style>

</head>

<body style=" background-color: #067be8;">
<div id="menuh" class="sidebar sidebar-hide-to-small sidebar-shrink sidebar-gestures">
            <div class="nano">
                <div class="nano-content">
                    <ul>
                        <li class="label">Main</li>
                        <li><a href="index.php"><i class="ti-home"></i> Home</a></li>
                        </li>
                        <li><a href="addCompany.php"><i class="ti-plus"></i>Companies</a></li>
                        <li id="logout-btn"><a href="login.php"><i class="ti-close"></i>Logout</a></li>
                    </ul>
                </div>
            </div>
        </div>
        
        
        <div id="sidebar-css" class="header">
            <div class="pull-left">
                <div class="logo"><a
                        href="index.php"><!-- <img src="assets/images/logo.png" alt="" /> --><span>Admin</span></a>
                </div>
                <div class="hamburger sidebar-toggle">
                    <span class="line"></span>
                    <span class="line"></span>
                    <span class="line"></span>
                </div>
            </div>
            <div class="pull-right p-r-15">
                <ul>
                    
                    <li class="header-icon dib"><a href="#search"><i class="ti-search"></i></a></li>
                    <li class="header-icon dib"><i class="ti-bell"></i>
                        <div class="drop-down">
                            <div class="dropdown-content-heading">
                                <span class="text-left">Recent Notifications</span>
                            </div>
        
                    <li class="header-icon dib"><img class="avatar-img" src="assets/images/avatar/1.jpg" alt="" /> <span
                            class="user-avatar"> <i class="ti-angle-down f-s-10"></i></span>
                        <div class="drop-down dropdown-profile">
                            <div class="dropdown-content-heading">
        
                            </div>
                            <div class="dropdown-content-body">
                                <ul>
        
                                    <li id="logout-btn"><a href="login.php" id="logout-btn"><i class="ti-power-off"></i> <span>Logout</span></a></li>
                                </ul>
                            </div>
                        </div>
                    </li>
                    
                </ul>
            </div>
        </div>
    

    <div class="content-wrap">
        <div class="main">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-8 p-r-0 title-margin-right">
                        <div class="page-header">
                            <div class="page-title">
                            <?php $dashName = basename($_SERVER['PHP_SELF'], ".php");
                                ?>
                                <h1><?php echo ucfirst($dashName); ?></h1>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 p-l-0 title-margin-left">
                        <div class="page-header">
                            <div class="page-title">
                                <ol class="breadcrumb text-right" style="padding: 0; margin: 0;">
                                    <!-- Кнопка "Назад" с синим цветом -->
                                    <button class="btn btn-primary" style="margin-left: 10px; margin-top: 10px;" onclick="history.back()">
                                        << Back</button>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>