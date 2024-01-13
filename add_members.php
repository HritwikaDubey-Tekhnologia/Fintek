<?php 
   session_start();

   include("php/config.php");
   if(!isset($_SESSION['valid'])){
    header("Location: home.php");
   }
?>
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $groupId = isset($_POST["groupId"]) ? $_POST["groupId"] : '';
    $username = isset($_POST["username"]) ? $_POST["username"] : '';
    $userTypeId = isset($_POST["userTypeId"]) ? intval($_POST["userTypeId"]) : 0;
    $email = isset($_POST["email"]) ? $_POST["email"] : '';
    $password = isset($_POST["password"]) ? $_POST["password"] : '';

    // Insert into TblUser with the specified GroupId
    $insertUserQuery = "INSERT INTO TblUser (UserName, UserTypeId, Email, Password, GroupId) 
                        VALUES ('$username', '$userTypeId', '$email', '$password', '$groupId')";

    if ($conn->query($insertUserQuery) === TRUE) {
        echo "Record inserted successfully";
    } else {
        echo "Error: " . $insertUserQuery . "<br>" . $conn->error;
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
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-top: 50px;
        }

        h2 {
            color: #333;
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
        }

        input[type="submit"] {
            background-color: #62b4cf;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #4eabc9;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Add Member to Group</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <label for="groupId">Group ID:</label>
        <select id="groupId" name="groupId" required>
            <?php
            
            $result = $conn->query("SELECT GroupId FROM TblGroup");
            while ($row = $result->fetch_assoc()) {
                echo "<option value='" . $row['GroupId'] . "'>" . $row['GroupId'] . "</option>";
            }
            ?>
        </select>

        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>

        <label for="userTypeId">User Type:</label>
        <select id="userTypeId" name="userTypeId" required>
            <?php
            $result = $conn->query("SELECT UserTypeId, UserType FROM TblUserType");
            while ($row = $result->fetch_assoc()) {
                echo "<option value='" . $row['UserTypeId'] . "'>" . $row['UserType'] . "</option>";
            }
            ?>
        </select>


        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>

        <input type="submit" value="Add Member">
    </form>
</div>

</body>
</html>

