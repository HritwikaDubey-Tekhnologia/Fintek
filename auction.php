<?php 
   session_start();

   include("php/config.php");
   if(!isset($_SESSION['valid'])){
    header("Location: home.php");
   }

$successMessage = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $groupId = $_POST['groupId'];
    $auctionDate = $_POST['auctionDate'];
    $amountAuctioned = $_POST['amountAuctioned'];
    $agencyPercentage = $_POST['agencyPercentage'];

    // Insert auction data into the database
    $sql = "INSERT INTO TblAuction (GroupId, Date, AmountAuctioned) VALUES ($groupId, '$auctionDate', $amountAuctioned)";
    if ($conn->query($sql) === TRUE) {
        // Update TblGroup to store the agency percentage
        $sqlUpdateGroup = "UPDATE TblGroup SET AgencyPercentage = $agencyPercentage WHERE GroupId = $groupId";

        if ($conn->query($sqlUpdateGroup) === TRUE) {
            $_SESSION['successMessage'] = "Auction created successfully, and agency percentage updated!";
        } else {
            $_SESSION['errorMessage'] = "Error updating agency percentage: " . $conn->error;
        }
    } else {
        $_SESSION['errorMessage'] = "Error creating auction: " . $conn->error;
    }

    // Redirect to the page containing the HTML form
    header("Location: home.php?message=display");
    exit();
}
?>
