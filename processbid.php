
<?php 
   session_start();

   include("php/config.php");
   if(!isset($_SESSION['valid'])){
    header("Location: homeuser.php");
   }
?>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $requestedAmount = $_POST["amount"];
    $reason = $_POST["reason"];

    if (isset($_SESSION['UserId'])) {
        $userId = $_SESSION['UserId'];

        $sql = "INSERT INTO TblBids (UserId, RequestedAmount, Reason) VALUES ($userId, $requestedAmount, '$reason')";
        
        if ($conn->query($sql) === TRUE) {
            $_SESSION['message'] = "Bid submitted successfully!";
            $_SESSION['message_type'] = 'success';
        } else {
            $_SESSION['message'] = "Error: " . $sql . "<br>" . $conn->error;
            $_SESSION['message_type'] = 'error';
        }
    } else {
        $_SESSION['message'] = "User ID not found in session.";
        $_SESSION['message_type'] = 'error';
    }
} else {
    $_SESSION['message'] = "Invalid request.";
    $_SESSION['message_type'] = 'error';
}



header("Location: homeuser.php");


?>
