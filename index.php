<?php
session_start();
include("php/config.php");

if(isset($_POST['submit'])){
    $userName = mysqli_real_escape_string($conn, $_POST['userName']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    $result = mysqli_query($conn, "SELECT * FROM tblUser WHERE UserName='$userName' AND Password='$password'") or die("Select Error");
    $row = mysqli_fetch_assoc($result);

    if(is_array($row) && !empty($row)){
        $_SESSION['valid'] = $row['UserName'];
        $_SESSION['username'] = $row['UserName'];
        $_SESSION['UserId'] = $row['UserId'];

        // Handle different user types and redirect accordingly
        $userType = $row['UserTypeId'];
        switch ($userType) {
            case 1: // SuperAdmin
            case 2: // Admin
                header("Location: home.php");
                break;
            case 3: // Agency
            case 4: // Group
            case 5: // User
                header("Location: homeuser.php");
                break;
            default:
                echo "error";
                break;
        }

        exit(); 
    } else {
        echo "<div id='errorMessage' class='message'>
                <p>Wrong UserName or Password</p>
              </div> <br>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/style.css">
    <title>Login</title>
    <script>
        // Hide the error message after 3 seconds
        setTimeout(function(){
            document.getElementById('errorMessage').style.display = 'none';
        }, 3000);
    </script>
</head>
<body>
    <div class="container">
        <div class="box form-box">
            <header>Login</header>
            <form action="" method="post">
                <div class="field input">
                    <label for="email">UserName</label>
                    <input type="text" name="userName" id="userName" autocomplete="off" required>
                </div>

                <div class="field input">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" autocomplete="off" required>
                </div>

                <div class="field">
                    <input type="submit" class="btn" name="submit" value="Login" required>
                </div>

                <div class="links">
                    Don't have an account? <a href="register.php">Sign Up Now</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
