<?php
include("php/config.php");
session_start();

// Check if the user is logged in
if (!isset($_SESSION['UserId'])) {
    header("Location: home.php");
    exit();
}

$userTypeId = $_SESSION['UserTypeId'];
if (!in_array($userTypeId, [1, 2, 3])) {
    header("Location: home.php");
    exit();
}

$userId = $_SESSION['UserId'];

// Fetch agencies for the logged-in user
$agencyQuery = "SELECT AgencyId, AgencyName FROM tblagency";
$resultAgency = $conn->query($agencyQuery);

// Fetch user details
$userQuery = "SELECT UserName, GroupId FROM tbluser WHERE UserId = $userId";
$resultUser = $conn->query($userQuery);
$rowUser = $resultUser->fetch_assoc();
$userName = $rowUser['UserName'];
$groupId = $rowUser['GroupId'];

// Display variables for debugging
echo "UserId: $userId<br>";
echo "UserName: $userName<br>";
echo "GroupId: $groupId<br>";

// Fetch group details
if ($groupId) {
    $groupQuery = "SELECT GroupName, AgencyId FROM tblgroup WHERE GroupId = $groupId";
    $resultGroup = $conn->query($groupQuery);

    // Display error information
    echo "Group Query Error: " . $conn->error . "<br>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bids</title>
</head>
<body>
    <h1>Welcome, <?php echo $userName; ?>!</h1>

    <h2>Bidding Requests</h2>
    <form method="post" action="process_bids_selection.php">
        <label for="agencyId">Select Agency:</label>
        <select name="agencyId" id="agencyId">
            <?php
            while ($rowAgency = $resultAgency->fetch_assoc()) {
                echo "<option value='{$rowAgency['AgencyId']}'>{$rowAgency['AgencyName']}</option>";
            }
            ?>
        </select><br>

        <label for="groupId">Select Group:</label>
        <select name="groupId" id="groupId">
            <!-- Populate with groups associated with the selected agency -->
            <?php
            if ($resultGroup->num_rows > 0) {
                $groupRow = $resultGroup->fetch_assoc();
                echo "<option value='{$groupRow['GroupId']}'>{$groupRow['GroupName']}</option>";
            }
            ?>
        </select><br>

        <input type="submit" value="Show Bids">
    </form>
</body>
</html>
