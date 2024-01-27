<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" integrity="sha384-xrR7STVVIB6UqzIqRj5ojKsF2OyIUWK9UdJ5SOi+1rWUCJUqO4Q6QrX06L2LSc5N" crossorigin="anonymous">

    <title>Login</title>
    <style>
        body {
            background-color: #f0f5f9;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .main-container {
            display: flex;
            align-items: center;
            position: relative;
            background-color: #fff;
            padding: 90px;
            border-radius: 16px;
        }

        .logo-container {
            position: absolute;
            top: 40px;
            left: 40px;
            display: flex;
            align-items: center;
        }

        .fa-icon {
            position: absolute;
            color: #2581c7;
            left: 20px;
            top: 50%;
            transform: translateY(-50%);
        }

        .logo {
            max-width: 150px;
            max-height: 150px;
            margin-right: 20px;
        }

        .left-image {
            max-width: 150px;
            max-height: 150px;
            margin-right: 40px;
        }

        .image-container {
            width: 400px;
            height: 400px;
            margin-right: 40px;
        }

        .image-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-top-left-radius: 16px;
            border-bottom-left-radius: 16px;
        }

        .form-container {
            background-color: #fff;
            border-radius: 16px;
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.1);
            padding: 60px;
            width: 400px;
            text-align: center;
        }

        header {
            font-size: 36px;
            font-weight: bold;
            color: #3498db;
            margin-bottom: 30px;
        }

        .field {
            margin-bottom: 30px;
            position: relative;
        }

        .field input {
            width: calc(100% - 40px);
            padding: 16px;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-sizing: border-box;
            font-size: 18px;
            padding-left: 40px;
            position: relative;
        }

        .field input:focus {
            outline: none;
            border-color: #3498db;
        }

        .fa-icon {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: #555;
        }

        .btn {
    background-color: #3498db;
    color: #fff;
    padding: 10px 20px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-size: 18px;
    transition: background-color 0.3s ease;
    text-align: center;
    display: inline-block;
    line-height: 1.4; /* Adjusted line-height to 1.4 */
    box-sizing: border-box;
}

.btn:hover {
    background-color: #2581c7;
}




        .forgot-password {
            text-align: right;
            margin-top: 20px;
            font-size: 16px;
            color: #3498db;
        }
    </style>
    <script>
        // Hide the error message after 3 seconds
        setTimeout(function() {
            document.getElementById('errorMessage').style.display = 'none';
        }, 3000);
    </script>
</head>

<body>
    <?php
    // Move session_start() to the top before any HTML output
    session_start();
    include("php/config.php");

    if (isset($_POST['submit'])) {
        $userName = mysqli_real_escape_string($conn, $_POST['userName']);
        $password = mysqli_real_escape_string($conn, $_POST['password']);

        $result = mysqli_query($conn, "SELECT * FROM tblUser WHERE UserName='$userName' AND Password='$password'") or die("Select Error");
        $row = mysqli_fetch_assoc($result);

        if (is_array($row) && !empty($row)) {
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
    <div class="main-container">
        <div class="logo-container">
            <img class="logo" src="Fintek.png" alt="Logo">
        </div>

        <div class="image-container">
            <img src="invest.svg" alt="Invest Image">
        </div>

        <div class="form-container">
            <header>User Login</header>
            <form action="" method="post">
                <div class="field input">
                    <i class="fas fa-user fa-icon"></i>
                    <input type="text" name="userName" id="userName" autocomplete="off" placeholder="Username" required>
                </div>

                <div class="field input">
                    <i class="fas fa-lock fa-icon"></i>
                    <input type="password" name="password" id="password" autocomplete="off" placeholder="Password" required>
                </div>

                <div class="field">
                    <input type="submit" class="btn" name="submit" value="Login" required>
                </div>

                <div class="forgot-password">
                    <a href="#">Forgot Password?</a>
                </div>
            </form>
        </div>
    </div>
</body>

</html>