<?php
session_start();

// Check if the user is logged in and is an admin
if (!isset($_SESSION['UserId']) || $_SESSION['usertype'] !== 'Admin') {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $groupType = $_POST["groupType"];


    // Handle the selected group type
    
    $_SESSION['message'] = "Group '$groupType' selected successfully!";
    $_SESSION['message_type'] = 'success';

} else {
    $_SESSION['message'] = "Invalid request.";
    $_SESSION['message_type'] = 'error';
}

header("Location: home.php");
exit();
?>
