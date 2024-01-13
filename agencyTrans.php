<?php
include("config.php");
session_start();

$agencyId = $_SESSION['AgencyId'];

// Assuming data is sent via POST
$selectedGroupId = $_POST['groupId'] ?? null;
$selectedUserId = $_POST['userId'] ?? null;

// Fetch agency credit, debit, and total
$creditQuery = "SELECT COALESCE(SUM(AmountPaid), 0) AS Credit FROM tbltransaction t
               JOIN tbluser u ON t.UserId = u.UserId
               JOIN tblgroup g ON u.GroupId = g.GroupId
               WHERE g.AgencyId = $agencyId";
$debitQuery = "SELECT COALESCE(SUM(RequestedAmount), 0) AS Debit FROM tblbids b
              JOIN tbluser u ON b.UserId = u.UserId
              JOIN tblgroup g ON u.GroupId = g.GroupId
              WHERE g.AgencyId = $agencyId";
$totalQuery = "SELECT 
    (SELECT COALESCE(SUM(AmountPaid), 0) FROM tbltransaction t
     JOIN tbluser u ON t.UserId = u.UserId
     JOIN tblgroup g ON u.GroupId = g.GroupId
     WHERE g.AgencyId = $agencyId) -
    (SELECT COALESCE(SUM(RequestedAmount), 0) FROM tblbids b
     JOIN tbluser u ON b.UserId = u.UserId
     JOIN tblgroup g ON u.GroupId = g.GroupId
     WHERE g.AgencyId = $agencyId) AS Total";

$resultCredit = $conn->query($creditQuery);
$resultDebit = $conn->query($debitQuery);
$resultTotal = $conn->query($totalQuery);

$rowCredit = $resultCredit->fetch_assoc();
$rowDebit = $resultDebit->fetch_assoc();
$rowTotal = $resultTotal->fetch_assoc();

$credit = $rowCredit['Credit'];
$debit = $rowDebit['Debit'];
$total = $rowTotal['Total'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agency Dashboard</title>
</head>
<body>
    <h1>Welcome, Agency!</h1>

    <h2>Your Transactions</h2>
    <table border="1">
        <tr>
            <th>Date and Time</th>
            <th>Credit</th>
            <th>Debit</th>
            <th>Total</th>
        </tr>
        <?php
        // Retrieve and display agency transactions
        $transactionHistoryQuery = "SELECT t.TransactionTime, t.AmountPaid FROM tbltransaction t
                                   JOIN tbluser u ON t.UserId = u.UserId
                                   JOIN tblgroup g ON u.GroupId = g.GroupId
                                   WHERE g.AgencyId = $agencyId";

        $resultHistory = $conn->query($transactionHistoryQuery);

        while ($rowHistory = $resultHistory->fetch_assoc()) {
            echo "<tr>";
            echo "<td>{$rowHistory['TransactionTime']}</td>";
            echo "<td>{$rowHistory['AmountPaid']}</td>";
            echo "<td>0</td>"; // Assuming no bids in this example
            echo "<td>{$rowHistory['AmountPaid']}</td>";
            echo "</tr>";
        }
        ?>
    </table>

    <h2>Your Current Status</h2>
    <table border="1">
        <tr>
            <th>Credit</th>
            <th>Debit</th>
            <th>Total</th>
        </tr>
        <tr>
            <td><?php echo $credit; ?></td>
            <td><?php echo $debit; ?></td>
            <td><?php echo $total; ?></td>
        </tr>
    </table>

    <h2>Group and User Details</h2>
    <form method="post">
        <label for="groupId">Select Group:</label>
        <select name="groupId" id="groupId">
            <!-- Populate with groups associated with the agency -->
            <?php
            $groupQuery = "SELECT GroupId, GroupName FROM tblgroup WHERE AgencyId = $agencyId";
            $groupResult = $conn->query($groupQuery);

            while ($groupRow = $groupResult->fetch_assoc()) {
                echo "<option value='{$groupRow['GroupId']}'>{$groupRow['GroupName']}</option>";
            }
            ?>
        </select>

        <label for="userId">Select User:</label>
        <select name="userId" id="userId">
            <!-- Populate with users associated with the selected group -->
            <?php
            if ($selectedGroupId) {
                $userQuery = "SELECT UserId, UserName FROM tbluser WHERE GroupId = $selectedGroupId";
                $userResult = $conn->query($userQuery);

                while ($userRow = $userResult->fetch_assoc()) {
                    echo "<option value='{$userRow['UserId']}'>{$userRow['UserName']}</option>";
                }
            }
            ?>
        </select>

        <input type="submit" value="Show Details">
    </form>

    <!-- Display user details based on selection -->
    <?php
    if ($selectedGroupId && $selectedUserId) {
        // Fetch and display user details based on the selected group and user
        $userDetailsQuery = "SELECT TransactionTime, AmountPaid FROM tbltransaction WHERE UserId = $selectedUserId";
        $userDetailsResult = $conn->query($userDetailsQuery);
    ?>
        <h2>User Details</h2>
        <table border="1">
            <tr>
                <th>Date and Time</th>
                <th>Credit</th>
                <th>Debit</th>
                <th>Total</th>
            </tr>
            <?php
            while ($userDetailsRow = $userDetailsResult->fetch_assoc()) {
                echo "<tr>";
                echo "<td>{$userDetailsRow['TransactionTime']}</td>";
                echo "<td>{$userDetailsRow['AmountPaid']}</td>";
                echo "<td>0</td>"; // Assuming no bids in this example
                echo "<td>{$userDetailsRow['AmountPaid']}</td>";
                echo "</tr>";
            }
            ?>
        </table>
    <?php
    }
    ?>
</body>
</html>
