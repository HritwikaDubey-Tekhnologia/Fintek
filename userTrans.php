<?php
session_start();

include("php/config.php");


if (!isset($_SESSION['valid'])) {
    header("Location: home.php");
}

$userId = $_SESSION['UserId'];


$query = "SELECT tblUser.*, tblGroup.GroupId, tblGroup.GroupName, tblAgency.AgencyName
          FROM tblUser
          JOIN tblGroup ON tblUser.GroupId = tblGroup.GroupId
          LEFT JOIN tblAgency ON tblGroup.AgencyId = tblAgency.AgencyId
          WHERE tblUser.UserId = ?";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $res_Utypeid = $row['UserTypeId'];
    $res_username = $row['UserName'];
    $res_UserId = $row['UserId'];
    $res_GroupId = $row['GroupId']; 
    $res_GroupName = $row['GroupName'];
    $res_AgencyName = $row['AgencyName'];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>

    <div class="w3-container w3-card w3-white w3-round w3-margin"><br>

        <h2 style="color: #333;">Bidding Requests</h2>

        <?php
        // Check if $userId is set
        if (isset($userId)) {
            $sql = "SELECT TblBids.UserId, TblUser.username, TblBids.RequestedAmount, TblBids.BidDate, TblBids.Reason
                    FROM TblBids
                    JOIN TblUser ON TblBids.UserId = TblUser.UserId
                    WHERE TblUser.GroupId = ?";

            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $res_GroupId); 
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result === false) {
                // Handle error
                echo "Error: " . $conn->error;
            } else {
                echo '<table style="border-collapse: collapse; width: 100%;">';
                echo '<tr style="background-color: #f2f2f2;"><th style="padding: 8px; text-align: left;">User ID</th><th style="padding: 8px; text-align: left;">User Name</th><th style="padding: 8px; text-align: left;">Requested Amount</th><th style="padding: 8px; text-align: left;">Bid Date</th><th style="padding: 8px; text-align: left;">Reason</th></tr>';

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo '<tr>';
                        echo '<td style="padding: 8px; border: 1px solid #dddddd;">' . $row['UserId'] . '</td>';
                        echo '<td style="padding: 8px; border: 1px solid #dddddd;">' . $row['username'] . '</td>';
                        echo '<td style="padding: 8px; border: 1px solid #dddddd;">' . $row['RequestedAmount'] . '</td>';
                        echo '<td style="padding: 8px; border: 1px solid #dddddd;">' . $row['BidDate'] . '</td>';
                        echo '<td style="padding: 8px; border: 1px solid #dddddd;">' . $row['Reason'] . '</td>';
                        echo '</tr>';
                    }
                } else {
                    echo '<tr><td colspan="4" style="padding: 8px; border: 1px solid #dddddd;">No bid requests found</td></tr>';
                }

                echo '</table>';
            }
        } else {
            echo '<p>Error: $userId is not set</p>';
        }
        ?>

    </div>

    <div class="w3-container w3-card w3-white w3-round w3-margin"><br>

        <?php
        // Calculate user credit, debit, and total
        $creditQuery = "SELECT COALESCE(SUM(AmountPaid), 0) AS Credit FROM tbltransaction WHERE UserId = ?";
        $debitQuery = "SELECT COALESCE(SUM(RequestedAmount), 0) AS Debit FROM tblbids WHERE UserId = ?";
        $totalQuery = "SELECT 
                        COALESCE((SELECT SUM(AmountPaid) FROM tbltransaction WHERE UserId = ?), 0) -
                        COALESCE((SELECT SUM(RequestedAmount) FROM tblbids WHERE UserId = ?), 0) AS Total";

        $stmtCredit = $conn->prepare($creditQuery);
        $stmtCredit->bind_param("i", $userId);
        $stmtCredit->execute();
        $resultCredit = $stmtCredit->get_result();

        $stmtDebit = $conn->prepare($debitQuery);
        $stmtDebit->bind_param("i", $userId);
        $stmtDebit->execute();
        $resultDebit = $stmtDebit->get_result();

        $stmtTotal = $conn->prepare($totalQuery);
        $stmtTotal->bind_param("ii", $userId, $userId);
        $stmtTotal->execute();
        $resultTotal = $stmtTotal->get_result();

        $rowCredit = $resultCredit->fetch_assoc();
        $rowDebit = $resultDebit->fetch_assoc();
        $rowTotal = $resultTotal->fetch_assoc();

        $credit = $rowCredit['Credit'];
        $debit = $rowDebit['Debit'];
        $total = $rowTotal['Total'];
        ?>


        <h2>Your Transactions</h2>
        <div class="w3-container w3-card w3-white w3-round w3-margin">
            <br>
            <table style="border-collapse: collapse; width: 100%;">
                <tr style="background-color: #f2f2f2;">
                    <th style="padding: 8px; text-align: left;">Date and Time</th>
                    <th style="padding: 8px; text-align: left;">Credit</th>
                    <th style="padding: 8px; text-align: left;">Debit</th>
                    <th style="padding: 8px; text-align: left;">Total</th>
                </tr>
                <?php
                // Retrieve and display user transactions
                $transactionHistoryQuery = "SELECT TransactionTime, AmountPaid FROM tbltransaction WHERE UserId = ?";

                $stmtHistory = $conn->prepare($transactionHistoryQuery);
                $stmtHistory->bind_param("i", $userId);
                $stmtHistory->execute();
                $resultHistory = $stmtHistory->get_result();

                while ($rowHistory = $resultHistory->fetch_assoc()) {
                    echo '<tr>';
                    echo '<td style="padding: 8px; border: 1px solid #dddddd;">' . $rowHistory['TransactionTime'] . '</td>';
                    echo '<td style="padding: 8px; border: 1px solid #dddddd;">' . $rowHistory['AmountPaid'] . '</td>';
                    echo '<td style="padding: 8px; border: 1px solid #dddddd;">0</td>'; // Assuming no bids in this example
                    echo '<td style="padding: 8px; border: 1px solid #dddddd;">' . $rowHistory['AmountPaid'] . '</td>';
                    echo '</tr>';
                }
                ?>
            </table>
        </div>

        <div class="w3-container w3-card w3-white w3-round w3-margin">
            <br>
            <h2 style="color: #333;">Current Status</h2>
            <table style="border-collapse: collapse; width: 100%;">
                <tr style="background-color: #f2f2f2;">
                    <th style="padding: 8px; text-align: left;">Credit</th>
                    <th style="padding: 8px; text-align: left;">Debit</th>
                    <th style="padding: 8px; text-align: left;">Total</th>
                </tr>
                <tr>
                    <td style="padding: 8px; border: 1px solid #dddddd;"><?php echo $credit; ?></td>
                    <td style="padding: 8px; border: 1px solid #dddddd;"><?php echo $debit; ?></td>
                    <td style="padding: 8px; border: 1px solid #dddddd;"><?php echo $total; ?></td>
                </tr>
            </table>
        </div>
    </div>

</body>

</html>
