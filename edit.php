<?php 
    session_start();
    include("php/config.php");
    if (!isset($_SESSION['valid'])) {
        header("Location: index.php");
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/style.css">
    <title>Change Profile</title>
</head>
<body>
    <div class="nav">
        <div class="logo">
            <p><a href="home.php">FinTech BC</a></p>
        </div>

        <div class="right-links">
            <a href="php/logout.php"> <button class="btn">Log Out</button> </a>
        </div>
    </div>
    <div class="container">
        <div class="box form-box">
            <?php 
               if (isset($_POST['submit'])) {
                $username = $_POST['username'];
                $usertype = $_POST['usertype'];
                $email = $_POST['email'];

                $UserId = $_SESSION['UserId'];

                $edit_query = mysqli_query($conn,"UPDATE tbluser SET UserName='$username', UserType='$usertype', Email='$email' WHERE UserId=$UserId ") or die("error occurred");

                if ($edit_query) {
                    echo "<div class='message'>
                            <p>Profile Updated!</p>
                          </div> <br>";
                    echo "<a href='home.php'><button class='btn'>Go Home</button>";
                }
               } else {
                $UserId = $_SESSION['UserId'];
                $query = mysqli_query($conn,"SELECT * FROM tbluser WHERE UserId=$UserId ");

                while($result = mysqli_fetch_assoc($query)) {
                    $res_Uname = isset($result['UserName']) ? $result['UserName'] : '';
                    $res_Utype = isset($result['UserType']) ? $result['UserType'] : '';
                    $res_Email = isset($result['Email']) ? $result['Email'] : '';
                }
            ?>
            <header>Change Profile</header>
            <form action="" method="post">
                <div class="field input">
                    <label for="username">Username</label>
                    <input type="text" name="username" id="username" value="<?php echo $res_Uname; ?>" autocomplete="off" required>
                </div>

                <div class="field input">
                    <label for="age">Usertype</label>
                    <input type="text" name="usertype" id="usertype" value="<?php echo $res_Utype; ?>" autocomplete="off" required>
                </div>

                <div class="field input">
                    <label for="email">Email</label>
                    <input type="text" name="email" id="email" value="<?php echo $res_Email; ?>" autocomplete="off" required>
                </div>

                <div class="field">
                    <input type="submit" class="btn" name="submit" value="Update" required>
                </div>
                
            </form>
        </div>
        <?php } ?>
    </div>
</body>
</html>
