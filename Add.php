<?php
session_start();

include("php/config.php");
if (!isset($_SESSION['valid'])) {
    header("Location: home.php");
    exit(); // Added exit to stop further execution
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $groupName = $_POST["groupName"];
    $startDate = $_POST["startDate"];
    $amount = $_POST["amount"];
    $agencyPercentage = $_POST["agencyPercentage"];
    $agency = $_POST["agency"];

    // Insert into TblGroup
    $insertGroupQuery = "INSERT INTO tblgroup (GroupName, StartDate, Amount, AgencyPercentage, AgencyId) 
                        VALUES ('$groupName', '$startDate', $amount, $agencyPercentage, (SELECT AgencyId FROM tblagency WHERE AgencyName = '$agency'))";

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

        input[type="text"],
        input[type="date"],
        input[type="number"] {
            width: calc(100% - 22px);
            /* Adjust the width as needed */
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
        .container input {
            grid-column: span 2;
        }

        @media screen and (max-width: 500px) {
            .container form {
                grid-template-columns: 1fr;
            }

            .container label,
            .container input {
                grid-column: span 1;
            }
        }
    </style>
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
            <select id="agency" name="agency" required style="width: calc(100% - 22px); padding: 10px; margin-bottom: 15px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px; display: inline-block; font-size: 16px;">
                <?php
                // Fetch existing agency names from tblagency
                $agencyQuery = "SELECT * FROM tblagency";
                $result = $conn->query($agencyQuery);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='" . $row['AgencyName'] . "'>" . $row['AgencyName'] . "</option>";
                    }
                }
                ?>
            </select>

            <input type="submit" value="Submit">
        </form>
    </div>
</body>

</html>