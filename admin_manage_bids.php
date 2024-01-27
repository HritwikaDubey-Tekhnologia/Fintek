<?php
session_start();
include("php/config.php");

$userId = isset($_SESSION['UserId']) ? $_SESSION['UserId'] : null;

if (!$userId) {
    echo "User ID not found.";
    exit;
}

// Fetch user details
$userQuery = "SELECT UserName, UserTypeId, AgencyId, GroupId FROM tbluser WHERE UserId = $userId";
$resultUser = $conn->query($userQuery);
$rowUser = $resultUser->fetch_assoc();
$userName = $rowUser['UserName'];
$userTypeId = $rowUser['UserTypeId'];
$agencyId = $rowUser['AgencyId'];
$selectedGroupId = isset($_POST['groupId']) ? $_POST['groupId'] : $rowUser['GroupId'];
$selectedUserId = isset($_POST['userId']) ? $_POST['userId'] : null;
$selectedAgencyId = isset($_POST['agencyId']) ? $_POST['agencyId'] : $agencyId;

// Fetch selected user's name
$selectedUserName = "";
if ($selectedUserId) {
    $selectedUserQuery = "SELECT UserName FROM tbluser WHERE UserId = $selectedUserId";
    $resultSelectedUser = $conn->query($selectedUserQuery);
    $rowSelectedUser = $resultSelectedUser->fetch_assoc();
    $selectedUserName = $rowSelectedUser['UserName'];
}

// Fetch selected agency or group name
$selectedAgencyOrGroupName = "";
if ($selectedAgencyId) {
    $selectedAgencyOrGroupQuery = "SELECT AgencyName FROM tblagency WHERE AgencyId = $selectedAgencyId";
    $resultSelectedAgencyOrGroup = $conn->query($selectedAgencyOrGroupQuery);
    $rowSelectedAgencyOrGroup = $resultSelectedAgencyOrGroup->fetch_assoc();
    $selectedAgencyOrGroupName = $rowSelectedAgencyOrGroup['AgencyName'];
} elseif ($selectedGroupId) {
    $selectedAgencyOrGroupQuery = "SELECT GroupName FROM tblgroup WHERE GroupId = $selectedGroupId";
    $resultSelectedAgencyOrGroup = $conn->query($selectedAgencyOrGroupQuery);
    $rowSelectedAgencyOrGroup = $resultSelectedAgencyOrGroup->fetch_assoc();
    $selectedAgencyOrGroupName = $rowSelectedAgencyOrGroup['GroupName'];
}

// Fetch selected group name
$selectedGroupName = "";
if ($selectedGroupId) {
    $selectedGroupQuery = "SELECT GroupName FROM tblgroup WHERE GroupId = $selectedGroupId";
    $resultSelectedGroup = $conn->query($selectedGroupQuery);
    $rowSelectedGroup = $resultSelectedGroup->fetch_assoc();
    $selectedGroupName = $rowSelectedGroup['GroupName'];
}

$agencyQuery = "";
if ($userTypeId == 2) {
    $agencyQuery = "SELECT DISTINCT a.AgencyId, a.AgencyName, g.GroupName FROM tblagency a
                    JOIN tblgroup g ON a.AgencyId = g.AgencyId
                    WHERE a.AgencyId = $agencyId";
} elseif ($userTypeId == 4) {
    $agencyQuery = "SELECT DISTINCT a.AgencyId, a.AgencyName, g.GroupName FROM tblagency a
                    LEFT JOIN tblgroup g ON a.AgencyId = g.AgencyId";
}

$resultAgency = $conn->query($agencyQuery);

$groupQuery = "";
if ($selectedAgencyId && $userTypeId == 2) {
    $groupQuery = "SELECT GroupId, GroupName FROM tblgroup WHERE AgencyId = $selectedAgencyId";
} elseif ($userTypeId == 4) {
    $groupQuery = "SELECT DISTINCT g.GroupId, g.GroupName FROM tblgroup g
                    JOIN tblagency a ON g.AgencyId = a.AgencyId";
}

$resultGroup = $conn->query($groupQuery);

$userListQuery = "";
if ($selectedGroupId) {
    $userListQuery = "SELECT UserId, UserName FROM tbluser WHERE GroupId = $selectedGroupId";
}

$resultUserList = $userListQuery ? $conn->query($userListQuery) : false;

$resultBids = false;

if (isset($_POST['submitDetails'])) {
    $bidsQuery = "";
    if ($selectedGroupId) {
        $bidsQuery = "SELECT b.BidId, u.UserName, b.RequestedAmount, b.Reason, b.BidDate
                      FROM tblbids b
                      LEFT JOIN tbluser u ON b.UserId = u.UserId
                      WHERE u.GroupId = $selectedGroupId";
    } elseif ($selectedAgencyId) {
        $bidsQuery = "SELECT b.BidId, u.UserName, b.RequestedAmount, b.Reason, b.BidDate
                      FROM tblbids b
                      LEFT JOIN tbluser u ON b.UserId = u.UserId
                      WHERE u.AgencyId = $selectedAgencyId";
    }

    $resultBids = $bidsQuery ? $conn->query($bidsQuery) : false;
}

if (isset($_POST['saveNumber'])) {
    $enteredNumber = isset($_POST['enteredNumber']) ? $_POST['enteredNumber'] : '';

    echo "Entered Number: " . htmlspecialchars($enteredNumber);

    // Dynamically calculate values
    if (!empty($selectedGroupId)) {
        $totalAmountPaidQuery = "SELECT SUM(tbltransaction.AmountPaid) AS TotalAmountPaid 
                                FROM tbltransaction
                                JOIN tbluser ON tbltransaction.UserId = tbluser.UserId
                                WHERE tbluser.GroupId = $selectedGroupId";

        $totalAmountPaidResult = $conn->query($totalAmountPaidQuery);

        if ($totalAmountPaidResult) {
            $totalAmountPaidRow = $totalAmountPaidResult->fetch_assoc();
            $totalAmountPaid = $totalAmountPaidRow['TotalAmountPaid'];
            // Display the total amount inside a div tag
            echo '<br>Total Amount of ' . $selectedGroupName . ': ' . $totalAmountPaid;

            $remainingAmount = $totalAmountPaid - $enteredNumber;
            echo "<br>Remaining Amount: $remainingAmount";
        } else {
            // Handle the query execution error
            echo "Error calculating total amount paid: " . $conn->error;
        }
    } else {
        echo "Error: No GroupId selected.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            color: #333;
            margin: 20px;
        }

        h1,
        h2 {
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table,
        th,
        td {
            border: 1px solid #ddd;
        }

        th,
        td {
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #2196F3;
            color: white;
        }

        tr:hover {
            background-color: #f5f5f5;
        }

        label {
            display: block;
            margin: 10px 0;
            font-weight: bold;
        }

        select,
        input[type="submit"],
        input[type="text"] {
            padding: 8px;
            margin: 5px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
    </style>
</head>

<body>
    <h1>Welcome, <?php echo $userName; ?>!</h1>

    <?php if ($selectedGroupId || $selectedUserId || $selectedAgencyId) : ?>
        <?php if ($resultBids) : ?>
            <h2>Bid Information for <?php echo $selectedAgencyOrGroupName; ?></h2>
            <table border='1'>
                <tr>
                    <th>Bid ID</th>
                    <th>User</th>
                    <th>Requested Amount</th>
                    <th>Reason</th>
                    <th>Bid Date</th>
                    <!-- Add more columns as needed -->
                </tr>
                <?php while ($bidRow = $resultBids->fetch_assoc()) : ?>
                    <tr>
                        <td><?php echo isset($bidRow['BidId']) ? $bidRow['BidId'] : 'N/A'; ?></td>
                        <td><?php echo isset($bidRow['UserName']) ? $bidRow['UserName'] : 'N/A'; ?></td>
                        <td><?php echo isset($bidRow['RequestedAmount']) ? $bidRow['RequestedAmount'] : 'N/A'; ?></td>
                        <td><?php echo isset($bidRow['Reason']) ? $bidRow['Reason'] : 'N/A'; ?></td>
                        <td><?php echo isset($bidRow['BidDate']) ? $bidRow['BidDate'] : 'N/A'; ?></td>
                        <!-- Add more cells as needed -->
                    </tr>
                <?php endwhile; ?>
            </table>
        <?php else : ?>
            <!-- Handle case where no bids are found -->
        <?php endif; ?>
    <?php endif; ?>

    <form method="post" action="#">
        <label for="agencyId">Select Agency:</label>
        <select name="agencyId" id="agencyId" onchange="this.form.submit()">
            <option value="" <?php echo empty($selectedAgencyId) ? 'selected' : ''; ?>></option>
            <?php
            $resultAgency->data_seek(0); // Reset the result pointer
            while ($rowAgency = $resultAgency->fetch_assoc()) {
                echo "<option value='{$rowAgency['AgencyId']}'";
                if ($rowAgency['AgencyId'] == $selectedAgencyId) echo " selected";
                echo ">{$rowAgency['AgencyName']}</option>";
            }
            ?>
        </select><br>

        <label for="groupId">Select Group:</label>
        <select name="groupId" id="groupId" onchange="this.form.submit()">
            <option value="" <?php echo empty($selectedGroupId) ? 'selected' : ''; ?>></option>
            <?php
            $resultGroup->data_seek(0);
            while ($rowGroup = $resultGroup->fetch_assoc()) {
                echo "<option value='{$rowGroup['GroupId']}'";
                if ($rowGroup['GroupId'] == $selectedGroupId) echo " selected";
                echo ">{$rowGroup['GroupName']}</option>";
            }
            ?>
        </select><br>

        <label for="userId">Select User:</label>
        <select name="userId" id="userId" onchange="this.form.submit()">
            <option value="" <?php echo empty($selectedUserId) ? 'selected' : ''; ?>></option>
            <?php
            if ($resultUserList) {
                $resultUserList->data_seek(0);
                while ($rowUserList = $resultUserList->fetch_assoc()) {
                    echo "<option value='{$rowUserList['UserId']}'";
                    if ($rowUserList['UserId'] == $selectedUserId) echo " selected";
                    echo ">{$rowUserList['UserName']}</option>";
                }
            }
            ?>
        </select><br>

        <input type="submit" name="submitDetails" value="Show Details">
    </form>
       

  
    <form method="post" action="#">
    <label for="enteredNumber">Enter Number:</label>
    <input type="text" name="enteredNumber" id="enteredNumber">

    <!-- Add a hidden input to include the groupId when submitting -->
    <input type="hidden" name="groupId" value="<?php echo $selectedGroupId; ?>">

    <input type="submit" name="saveNumber" value="Save Number">
</form>

</body>

</html>
