<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    // If no active session, redirect to login page
    header("Location: login.php");
    exit();
}

?>


<!DOCTYPE html>
<html>

<head>
    <title>SER-E-SER</title>
    <link rel="icon" type="image/x-icon" href="fvicon.png">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>
<style>
    body {
        margin: 0;
        padding: 0;
        height: 100vh;
        width: 100vw;
        overflow: hidden;
    }

    .frame {
        display: flex;
        width: 100vw;
        overflow: hidden;
        position: fixed;
        left: 0;
        top: 75px;
        height: calc(100vh - 75px);

    }

    .frame__body {
        padding: 20px 0 20px 40px;
        width: 100%;
        display: flex;
        flex-direction: column;
        height: 100%;
        overflow: hidden;
    }
</style>

<header>
    <?php
    include 'header.php';
    ?>
</header>
<div class="frame">
    <?php
    $_GET['page'] = 'clientes';
    include 'navbar.php';
    ?>
    <div class="frame__body">
        <?php
        include 'clientes/clientes.php';
        ?>
    </div>
</div>

<body>

</body>

</html>