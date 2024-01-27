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
} elseif ($selectedGroupId) {
    $selectedAgencyOrGroupQuery = "SELECT GroupName FROM tblgroup WHERE GroupId = $selectedGroupId";
    $resultSelectedAgencyOrGroup = $conn->query($selectedAgencyOrGroupQuery);
    $rowSelectedAgencyOrGroup = $resultSelectedAgencyOrGroup->fetch_assoc();
    $selectedAgencyOrGroupName = $rowSelectedAgencyOrGroup['GroupName'];
}

// Fetch agencies for the logged-in user
$agencyQuery = "";
if ($userTypeId == 2) { // Admin user
    $agencyQuery = "SELECT DISTINCT a.AgencyId, a.AgencyName, g.GroupName FROM tblagency a
                    JOIN tblgroup g ON a.AgencyId = g.AgencyId
                    WHERE a.AgencyId = $agencyId";
} elseif ($userTypeId == 4) { // SuperAdmin user
    $agencyQuery = "SELECT DISTINCT a.AgencyId, a.AgencyName, g.GroupName FROM tblagency a
                    LEFT JOIN tblgroup g ON a.AgencyId = g.AgencyId";
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

// Initialize transaction result variable
$resultTransaction = false;

// Check if the form is submitted
if (isset($_POST['submitDetails'])) {
    // Fetch transaction details for the selected user, agency, or group
    $transactionQuery = "";
    if ($selectedGroupId) {
        $transactionQuery = "SELECT AmountPaid, TransactionTime, GroupName FROM tbltransaction t
                             JOIN tbluser u ON t.UserId = u.UserId
                             LEFT JOIN tblgroup g ON u.GroupId = g.GroupId
                             WHERE u.GroupId = $selectedGroupId";
    } elseif ($selectedUserId) {
        $transactionQuery = "SELECT AmountPaid, TransactionTime, GroupName FROM tbltransaction t
                             JOIN tbluser u ON t.UserId = u.UserId
                             LEFT JOIN tblgroup g ON u.GroupId = g.GroupId
                             WHERE u.UserId = $selectedUserId";
    } elseif ($selectedAgencyId) {
        $transactionQuery = "SELECT AmountPaid, TransactionTime, GroupName FROM tbltransaction t
                             JOIN tbluser u ON t.UserId = u.UserId
                             LEFT JOIN tblgroup g ON u.GroupId = g.GroupId
                             WHERE u.AgencyId = $selectedAgencyId";
    }

    $resultTransaction = $transactionQuery ? $conn->query($transactionQuery) : false;
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
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        .container {
            background-color: #fff;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
            width: 60%; /* Adjust the width as needed */
            margin-top: 20px;
        }

        h1, h2 {
            color: #2196F3;
            text-align: center;
        }

        .form-container {
            border: 1px solid #ccc;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-top: 20px;
        }

        form {
            text-align: center;
        }

        label {
            display: block;
            margin: 10px 0;
            font-weight: bold;
        }

        select, input[type="submit"] {
            padding: 10px;
            margin: 5px 0;
            border: 1px solid #2196F3;
            border-radius: 4px;
            box-sizing: border-box;
            width: 100%;
        }

        input[type="submit"] {
            background-color: #2196F3;
            color: #fff;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #1565C0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #2196F3;
            color: #fff;
        }

        tr:hover {
            background-color: #f5f5f5;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Welcome, <?php echo $userName; ?>!</h1>

        <?php if ($selectedGroupId || $selectedUserId || $selectedAgencyId) : ?>
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
                            <th>Group Name</th>
                        </tr>";
                while ($transactionRow = $resultTransaction->fetch_assoc()) {
                    echo "<tr>
                            <td>{$transactionRow['AmountPaid']}</td>
                            <td>0</td>
                            <td>{$transactionRow['AmountPaid']}</td>
                            <td>{$transactionRow['TransactionTime']}</td>
                            <td>{$transactionRow['GroupName']}</td>
                        </tr>";
                }
                echo "</table>";
            }
            ?>
        <?php endif; ?>

        <div class="form-container">
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
                    $resultGroup->data_seek(0); // Reset the result pointer
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
                        $resultUserList->data_seek(0); // Reset the result pointer
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
        </div>
    </div>
</body>
</html>
