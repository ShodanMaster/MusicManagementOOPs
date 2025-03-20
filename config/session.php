<?php 

// echo "dxfcghjkl;"; exit;
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: authenticate.php");
    exit();
}
?>