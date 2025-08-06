<?php   
// Database configuration
$db_host = 'localhost';
$db_user = 'topsol';
$db_pass = 'topsol';
$db_name = 'top sol';

// Connect to database
try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    $_SESSION['error'] = "Connection failed: " . $e->getMessage();
    header("Location: login.php");
    exit();
}
?>