<?php
include("php/config.php");

// Fetch data from the database
$query = "
    SELECT
        g.GroupId,
        g.GroupName,
        a.AuctionId,
        a.StartDate AS AuctionStartDate,
        a.EndDate AS AuctionEndDate,
        a.AmountAuctioned,
        u.UserName AS MemberName,
        b.RequestedAmount AS BetAmount,
        b.Reason AS BetReason
    FROM
        tblgroup g
    JOIN
        tbluser u ON g.GroupId = u.GroupId
    JOIN
        tblbids b ON u.UserId = b.UserId
    JOIN
        tblauction a ON g.GroupId = a.GroupId
    ORDER BY
        g.GroupId, a.AuctionId, u.UserId, b.BidId;
";

$result = mysqli_query($conn, $query);

// Display data in HTML table
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Betting Room Details</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #2196F3;
            color: white;
        }

        tr:hover {
            background-color: #f5f5f5;
        }
    </style>
</head>
<body>

    <h2>Betting Room Details</h2>

    <?php
    if ($result && mysqli_num_rows($result) > 0) {
        $currentGroup = '';
        while ($row = mysqli_fetch_assoc($result)) {
            if ($currentGroup != $row['GroupName']) {
                // Display group header
                echo "<h3>{$row['GroupName']}</h3>";
                $currentGroup = $row['GroupName'];
            }

            // Display data in a table
            echo "<table>";
            echo "<tr>";
            echo "<th>Auction ID</th>";
            echo "<th>Auction Start Date</th>";
            echo "<th>Auction End Date</th>";
            echo "<th>Amount Auctioned</th>";
            echo "<th>Member Name</th>";
            echo "<th>Bet Amount</th>";
            echo "<th>Bet Reason</th>";
            echo "</tr>";

            do {
                echo "<tr>";
                echo "<td>{$row['AuctionId']}</td>";
                echo "<td>{$row['AuctionStartDate']}</td>";
                echo "<td>{$row['AuctionEndDate']}</td>";
                echo "<td>{$row['AmountAuctioned']}</td>";
                echo "<td>{$row['MemberName']}</td>";
                echo "<td>{$row['BetAmount']}</td>";
                echo "<td>{$row['BetReason']}</td>";
                echo "</tr>";
            } while ($row = mysqli_fetch_assoc($result));

            echo "</table>";
        }
    } else {
        echo "<p>No data found</p>";
    }

    // Close the connection
    mysqli_close($conn);
    ?>

</body>
</html>
