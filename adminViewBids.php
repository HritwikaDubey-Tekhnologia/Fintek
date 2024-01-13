<?php 
   session_start();

   include("php/config.php");
   if(!isset($_SESSION['valid'])){
    header("Location: home.php");
   }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin View and Accept Bids</title>
    <style>
    </style>
</head>
<body>


    <form action="adminViewBids.php" method="post">
        <label for="amount">Requested Amount:</label>
        <input type="number" id="amount" name="amount" required>

        <label for="reason">Reason:</label>
        <textarea id="reason" name="reason" rows="4" required></textarea>

        <button type="submit">Submit Request</button>
    </form>

</body>
</html>
