<?php 
   session_start();

   include("php/config.php");
   if(!isset($_SESSION['valid'])){
    header("Location: home.php");
   }
?>
<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $groupName = $_POST["groupName"];
    $startDate = $_POST["startDate"];
    $amount = $_POST["amount"];
    $agencyPercentage = $_POST["agencyPercentage"];
    $agency = $_POST["agency"];

    // Insert into TblGroup
    $insertGroupQuery = "INSERT INTO TblGroup (GroupName, StartDate, Amount, AgencyPercentage, Agency) 
                        VALUES ('$groupName', '$startDate', $amount, $agencyPercentage, '$agency')";
   
    if ($conn->query($insertGroupQuery) === TRUE) {
        echo "Record inserted successfully";
    } else {
        echo "Error: " . $insertGroupQuery . "<br>" . $conn->error;
    }

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Group</title>
</head>
<body>

<div class="container">
    <h2>Create Group</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
     
        <label for="groupName">Group Name:</label>
        <input type="text" id="groupName" name="groupName" required>

        <label for="startDate">Start Date:</label>
        <input type="date" id="startDate" name="startDate" required>

        <label for="amount">Amount:</label>
        <input type="number" id="amount" name="amount" required>

        <label for="agencyPercentage">Agency Percentage:</label>
        <input type="text" id="agencyPercentage" name="agencyPercentage" required>

        <label for="agency">Agency:</label>
        <input type="text" id="agency" name="agency" required>

        <input type="submit" value="Create Group">
    </form>
</div>


</body>
</html>
