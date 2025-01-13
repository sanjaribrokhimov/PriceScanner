<!DOCTYPE html>
<html lang="en" class="white">

<?php

$current_file = basename($_SERVER['PHP_SELF'], ".php");
?>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo ucfirst($current_file); ?></title>

    <!-- Styles -->
    <link href="assets/css/lib/calendar2/semantic.ui.min.css" rel="stylesheet">
    <link href="assets/css/lib/calendar2/pignose.calendar.min.css" rel="stylesheet">
    <link href="assets/css/lib/font-awesome.min.css" rel="stylesheet">
    <link href="assets/css/lib/themify-icons.css" rel="stylesheet">
    <link href="assets/css/lib/menubar/sidebar.css" rel="stylesheet">
    <link href="assets/css/lib/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/lib/unix.css" rel="stylesheet">

    <link href="assets/css/style.css" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <!-- Bootstrap JavaScript Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        var local_url = ''
        var orders = [];
    </script>
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

    <style>
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

        h1 {
        font-size: 24px;
        margin-bottom: 20px;
    }

    li {
        color: black;
        margin-bottom: 10px;
        display: flex;
        align-items: center;
    }

  

    #hotelImageContainer {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-bottom: 20px;
    }

    .hotel-image {
        width: calc(50% - 10px);
        height: auto;
        border-radius: 5px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    #map {
        width: 100%;
        height: 400px;
        margin: 20px 0;
        border: 1px solid #ccc;
        border-radius: 5px;
    }

    .amenities-content {
        margin-top: 20px;
    }

    .contact-title {
        font-weight: bold;
        color: #333;
    }

    .user-profile-name {
        font-size: 28px;
        font-weight: bold;
        color: #007bff;
    }
    </style>


</head>

<body style=" background-color:slategray;">
    
    <!-- Modal ADD ROOM -->
    <div id="customModal" class="modal fade">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Add Room</h2>
            <form id="addRoomForm">
                <div class="form-group">
                    <label for="room_type">Room type:</label>
                    <select class="form-control" id="room_type" name="room_type">
                        <option value="double">Double</option>
                        <option value="single">Single</option>
                        <option value="suite">Suite</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="bed_type">Bed Price</label>
                    <select class="form-control" id="bed_type" name="bed_type">
                        <option value="queen">Queen</option>
                        <option value="king">King</option>
                        <option value="twin">Twin</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="is_available">Status:</label>
                    <select class="form-control" id="is_available" name="is_available">
                        <option value="true">Available</option>
                        <option value="false">Unavailable</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="capacity">Hona sig'imi</label>
                    <input type="text" id="capacity" name="capacity" required>
                </div>
                <div class="form-group">
                    <label for="num_adults">Kattalar soni</label>
                    <input type="text" id="num_adults" name="num_adults" required>
                </div>
                <div class="form-group">
                    <label for="features">Features:</label>
                    <input type="text" id="features" name="features" required>
                </div>
                <div class="form-group">
                    <label for="price_per_night">Room Price</label>
                    <input type="number" id="price_per_night" name="price_per_night" required>
                </div>
                <div class="form-group">
                    <label for="quantity">Количество:</label>
                    <input type="number" id="quantity" name="quantity" value="1" required>
                </div>
                <button type="submit" id="saveRoomButton" class="btn">Save Room</button>
            </form>
        </div>
    </div>

    <!-- hider -->
    <div id="menuh" class="sidebar sidebar-hide-to-small sidebar-shrink sidebar-gestures">
        <div class="nano">
            <div class="nano-content">
                <ul>
                    <li class="label">Main</li>
                    <li><a href="index.php"><i class="ti-home"></i> Home</a></li>
                    <li><a href="services.php"><i class="bi bi-tools"></i> Services</a></li>
                    <li><a href="orders.php"><i class="ti ti-email"></i> Orders</a></li>
                    <li><a href="../login.php"><i class="ti-close"></i> Logout</a></li>
                </ul>
            </div>
        </div>
    </div>
    <br>
    <!-- Sidebar и Header -->
    <div id="sidebar-css" class="header">
        <div class="pull-left">
            <div class="logo">
                <a href="index.php">
                    <span>Partners Hotel</span>
                </a>
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

                <li class="header-icon dib"><img class="avatar-img" src="assets/images/avatar/1.jpg" alt="" />
                    <span id="first-name" class="user-avatar">name<i class="ti-angle-down f-s-10"></i></span>
                    <div class="drop-down dropdown-profile">
                        <div class="dropdown-content-heading">
                        </div>
                        <div class="dropdown-content-body">
                            <ul>

                                <li id="logout-btn"><a href="../login.php" id="logout-btn"><i class="ti-power-off"></i> <span>Logout</span></a></li>
                            </ul>
                        </div>
                    </div>
                </li>

            </ul>
        </div>
    </div>
    <script>
        // Получаем данные из localStorage
        const firstName = localStorage.getItem('first_name');
        const lastName = localStorage.getItem('last_name');

        // Проверяем, если данные существуют, и вставляем их в элемент
        if (firstName && lastName) {
            document.getElementById('first-name').textContent = `${firstName} ${lastName}`;
        } else {
            // Если данных нет, можно оставить placeholder или другое значение
            document.getElementById('first-name').textContent = 'Guest';
        }
    </script>
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
                    <!-- /# column -->
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
                    <!-- /# column -->
                </div>
