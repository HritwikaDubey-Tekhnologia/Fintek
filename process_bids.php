<?php
include("php/config.php");
session_start();

// Check if the user is logged in
if (!isset($_SESSION['UserId'])) {
    header("Location: home.php");
    exit();
}

$userId = $_SESSION['UserId'];
$userType = $_SESSION['UserType'];

// Check if the user has permission to access this page
if (!in_array($userType, ['Super Admin', 'Admin', 'Agency'])) {
    header("Location: home.php");
    exit();
}

// Fetch selected user's bids
$selectedUserId = $_POST['userId'];
$bidsQuery = "SELECT * FROM tblbids WHERE UserId = $selectedUserId";
$resultBids = $conn->query($bidsQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bid Details</title>
</head>
<body>
    <h1>Bid Details</h1>
    
    <table border="1">
        <thead>
            <tr>
                <th>BidId</th>
                <th>Requested Amount</th>
                <th>Reason</th>
                <th>Bid Date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            while ($rowBid = $resultBids->fetch_assoc()) {
                echo "<tr>";
                echo "<td>{$rowBid['BidId']}</td>";
                echo "<td>{$rowBid['RequestedAmount']}</td>";
                echo "<td>{$rowBid['Reason']}</td>";
                echo "<td>{$rowBid['BidDate']}</td>";
                echo "<td><button onclick='acceptBid({$rowBid['BidId']})'>Accept</button></td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>

    <script>
        function acceptBid(bidId) {
            // Implement the logic to accept the bid and perform further actions
            alert(`Bid with ID ${bidId} accepted!`);
        }
    </script>
</body>
</html>
