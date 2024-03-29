<?php
session_start();

include("php/config.php");

if (!isset($_SESSION['valid'])) {
    header("Location: home.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $groupId = isset($_POST["groupId"]) ? $_POST["groupId"] : '';
    $username = isset($_POST["username"]) ? $_POST["username"] : '';
    $userTypeId = isset($_POST["userTypeId"]) ? intval($_POST["userTypeId"]) : 0;
    $password = isset($_POST["password"]) ? $_POST["password"] : '';

    // Fetch the agency associated with the selected group
    $fetchAgencyQuery = "SELECT AgencyId FROM tblgroup WHERE GroupId = '$groupId'";
    $agencyResult = $conn->query($fetchAgencyQuery);

    if ($agencyResult->num_rows > 0) {
        $agencyRow = $agencyResult->fetch_assoc();
        $agencyId = $agencyRow['AgencyId'];

        // Insert into tbluser
        $insertUserQuery = "INSERT INTO tbluser (UserName, UserTypeId, Password, GroupId, AgencyId) 
                            VALUES ('$username', '$userTypeId', '$password', '$groupId', '$agencyId')";

        if ($conn->query($insertUserQuery) === TRUE) {
            // Get the newly inserted user's ID
            $userId = $conn->insert_id;

            // Insert into tblusergroup
            $insertUserGroupQuery = "INSERT INTO tblusergroup (UserId, GroupId) 
                                     VALUES ('$userId', '$groupId')";
            $conn->query($insertUserGroupQuery);

            // Insert into tbltransaction (assuming there's a relationship with tbluser)
            $insertTransactionQuery = "INSERT INTO tbltransaction (UserId, AmountPaid) 
                                       VALUES ('$userId', 0)";
            $conn->query($insertTransactionQuery);

            echo "Record inserted successfully";
        } else {
            echo "Error: " . $insertUserQuery . "<br>" . $conn->error;
        }
    } else {
        echo "Error fetching agency information";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Members</title>
    <style>
        body {
            font-family: "Arial", sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        .container {
            width: 35%;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
        }

        h2 {
            color: #333;
            text-align: center;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #555;
        }

        select,
        input {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
            display: inline-block;
            font-size: 16px;
        }

        input[type="submit"] {
            background-color: #62b4cf;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 18px;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #4eabc9;
        }

        .container form {
            display: grid;
            grid-gap: 15px;
        }

        .container label,
        .container select,
        .container input {
            grid-column: span 2;
        }

        @media screen and (max-width: 500px) {
            .container form {
                grid-template-columns: 1fr;
            }

            .container label,
            .container select,
            .container input {
                grid-column: span 1;
            }
        }
    </style>
</head>

<body>

    <div class="container">
        <h2>Add Member to Group</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <label for="groupId">Group:</label>
            <select id="groupId" name="groupId" required>
                <?php
                $result = $conn->query("SELECT GroupId, GroupName FROM tblgroup");
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='" . $row['GroupId'] . "'>" . $row['GroupName'] . "</option>";
                }
                ?>
            </select>

            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>

            <label for="userTypeId">User Type:</label>
            <select id="userTypeId" name="userTypeId" required>
                <?php
                $result = $conn->query("SELECT UserTypeId, UserType FROM tblusertype");
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='" . $row['UserTypeId'] . "'>" . $row['UserType'] . "</option>";
                }
                ?>
            </select>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <input type="submit" value="Add Member">
        </form>
    </div>

</body>

</html>
