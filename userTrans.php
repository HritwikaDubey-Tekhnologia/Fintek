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
    <title>Your Dashboard</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 80%;
            margin: 20px auto;
        }

        .card {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            padding: 20px;
        }

        h2 {
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
        }

        th {
            background-color: #3498db;
            color: #fff;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="card">
            <h2>Bidding Requests</h2>
            <?php
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
                    echo "Error: " . $conn->error;
                } else {
                    echo '<table>';
                    echo '<tr><th>User ID</th><th>User Name</th><th>Requested Amount</th><th>Bid Date</th><th>Reason</th></tr>';

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo '<tr>';
                            echo '<td>' . $row['UserId'] . '</td>';
                            echo '<td>' . $row['username'] . '</td>';
                            echo '<td>' . $row['RequestedAmount'] . '</td>';
                            echo '<td>' . $row['BidDate'] . '</td>';
                            echo '<td>' . $row['Reason'] . '</td>';
                            echo '</tr>';
                        }
                    } else {
                        echo '<tr><td colspan="5">No bid requests found</td></tr>';
                    }

                    echo '</table>';
                }
            } else {
                echo '<p>Error: $userId is not set</p>';
            }
            ?>
        </div>

        <div class="card">
            <h2>Your Transactions</h2>
            <table>
                <tr><th>Date and Time</th><th>Credit</th><th>Debit</th><th>Total</th></tr>
                <?php
                $transactionHistoryQuery = "SELECT TransactionTime, AmountPaid FROM tbltransaction WHERE UserId = ?";

                $stmtHistory = $conn->prepare($transactionHistoryQuery);
                $stmtHistory->bind_param("i", $userId);
                $stmtHistory->execute();
                $resultHistory = $stmtHistory->get_result();

                while ($rowHistory = $resultHistory->fetch_assoc()) {
                    echo '<tr>';
                    echo '<td>' . $rowHistory['TransactionTime'] . '</td>';
                    echo '<td>' . $rowHistory['AmountPaid'] . '</td>';
                    echo '<td>0</td>';
                    echo '<td>' . $rowHistory['AmountPaid'] . '</td>';
                    echo '</tr>';
                }
                ?>
            </table>

            <div class="card">
                <h2 style="color: #333;">Current Status</h2>
                <table>
                    <tr><th>Credit</th><th>Debit</th><th>Total</th></tr>
                    <?php
                    // Define variables to avoid undefined variable warnings
                    $credit = 0;
                    $debit = 0;
                    $total = 0;

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
                    <tr>
                        <td><?php echo $credit; ?></td>
                        <td><?php echo $debit; ?></td>
                        <td><?php echo $total; ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

</body>

</html>
