<?php
session_start();

include("php/config.php");

if (!isset($_SESSION['valid'])) {
    header("Location: home.php");
}

$successMessage = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $groupId = $_POST['groupId'];
    $auctionDate = $_POST['auctionDate'];
    $amountAuctioned = $_POST['amountAuctioned'];

    // Insert auction data into the database
    $sql = "INSERT INTO tblauction (GroupId, StartDate, AmountAuctioned) VALUES ($groupId, '$auctionDate', $amountAuctioned)";

    if ($conn->query($sql) === TRUE) {
        $_SESSION['successMessage'] = "Auction created successfully!";
    } else {
        $_SESSION['errorMessage'] = "Error creating auction: " . $conn->error;
    }

    // Redirect to the page containing the HTML form
    header("Location: home.php?message=display");
    exit();
}
?>
