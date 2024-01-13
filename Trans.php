<?php
include("php/config.php");
session_start();

// Check if the user is logged in
if (!isset($_SESSION['UserId'])) {
    header("Location: home.php");
    exit();
}

$userId = $_SESSION['UserId'];

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
}

// Fetch agencies for the logged-in user
$agencyQuery = "";
if ($userTypeId == 2) { // Admin user
    $agencyQuery = "SELECT DISTINCT a.AgencyId, a.AgencyName FROM tblagency a
                    JOIN tblgroup g ON a.AgencyId = g.AgencyId
                    WHERE a.AgencyId = $agencyId";
} elseif ($userTypeId == 4) { // SuperAdmin user
    $agencyQuery = "SELECT DISTINCT a.AgencyId, a.AgencyName FROM tblagency a";
}

$resultAgency = $conn->query($agencyQuery);

// Fetch group details
$groupQuery = "";
if ($agencyId && $userTypeId == 2) { // Admin user
    $groupQuery = "SELECT GroupId, GroupName FROM tblgroup WHERE AgencyId = $agencyId";
} elseif ($userTypeId == 4) { // SuperAdmin user
    $groupQuery = "SELECT DISTINCT g.GroupId, g.GroupName FROM tblgroup g
                    JOIN tblagency a ON g.AgencyId = a.AgencyId";
}

$resultGroup = $conn->query($groupQuery);

// Fetch user details for the selected group
$userListQuery = "";
if ($selectedGroupId) {
    $userListQuery = "SELECT UserId, UserName FROM tbluser WHERE GroupId = $selectedGroupId";
}

$resultUserList = $userListQuery ? $conn->query($userListQuery) : false;

// Fetch transaction details for the selected user, agency, or group
$transactionQuery = "";
if ($selectedUserId) {
    $transactionQuery = "SELECT AmountPaid, TransactionTime FROM tbltransaction WHERE UserId = $selectedUserId";
} elseif ($selectedAgencyId) {
    $transactionQuery = "SELECT AmountPaid, TransactionTime FROM tbltransaction t
                         JOIN tbluser u ON t.UserId = u.UserId
                         WHERE u.AgencyId = $selectedAgencyId";
} elseif ($selectedGroupId) {
    $transactionQuery = "SELECT AmountPaid, TransactionTime FROM tbltransaction t
                         JOIN tbluser u ON t.UserId = u.UserId
                         WHERE u.GroupId = $selectedGroupId";
}

$resultTransaction = $transactionQuery ? $conn->query($transactionQuery) : false;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
</head>
<body>
    <h1>Welcome, <?php echo $userName; ?>!</h1>

    <?php if ($selectedUserId || $selectedAgencyId || $selectedGroupId): ?>
        <h2>Your Transactions - <?php echo $selectedUserName ?: $selectedAgencyOrGroupName; ?></h2>
        <!-- Display user's transactions -->
        <?php
        if ($resultTransaction) {
            echo "<table border='1'>
                    <tr>
                        <th>Debit</th>
                        <th>Credit</th>
                        <th>Total</th>
                        <th>Date</th>
                    </tr>";
            while ($transactionRow = $resultTransaction->fetch_assoc()) {
                echo "<tr>
                        <td>{$transactionRow['AmountPaid']}</td>
                        <td>0</td>
                        <td>{$transactionRow['AmountPaid']}</td>
                        <td>{$transactionRow['TransactionTime']}</td>
                    </tr>";
            }
            echo "</table>";
        }
        ?>
    <?php endif; ?>

    <h2>Your Current Status</h2>
    <!-- Display user's credit, debit, and total -->

    <h2>Group and User Details</h2>
    <form method="post" action="Trans.php">
        <label for="agencyId">Select Agency:</label>
        <select name="agencyId" id="agencyId" onchange="this.form.submit()">
            <option value="" <?php echo empty($selectedAgencyId) ? 'selected' : ''; ?>></option>
            <?php
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
</body>
</html>
