<?php
include("php/config.php");

// Check if the form is submitted
if (isset($_POST['submitBid'])) {
    // Get form data
    $agencyId = $_POST['agencyId'];
    $groupId = $_POST['groupId'];
    $userId = $_POST['userId'];
    $bidAmount = $_POST['bidAmount'];

    // Insert bid into the database
    $insertBidQuery = "INSERT INTO tblbid (AgencyId, GroupId, UserId, BidAmount) VALUES ('$agencyId', '$groupId', '$userId', '$bidAmount')";

    if ($conn->query($insertBidQuery) === TRUE) {
        // Bid inserted successfully
        echo "Bid saved successfully!";
    } else {
        // Error inserting bid
        echo "Error: " . $conn->error;
    }
}

// Close the database connection
$conn->close();
?>
