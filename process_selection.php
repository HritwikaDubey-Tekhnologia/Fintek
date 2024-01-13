<?php
include("php/config.php"); // Adjust the path as needed

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the selected values from the form
    $selectedAgencyId = $_POST['agencyId'] ?? null;
    $selectedGroupId = $_POST['groupId'] ?? null;
    $selectedUserId = $_POST['userId'] ?? null;

    // Perform additional validations if necessary

    // Redirect based on the selected options
    if ($selectedUserId) {
        // Redirect to a page showing details for the selected user
        header("Location: user_details.php?userId=$selectedUserId");
        exit();
    } elseif ($selectedGroupId) {
        // Redirect to a page showing details for the selected group
        header("Location: group_details.php?groupId=$selectedGroupId");
        exit();
    } elseif ($selectedAgencyId) {
        // Redirect to a page showing details for the selected agency
        header("Location: agency_details.php?agencyId=$selectedAgencyId");
        exit();
    }
}

// If no option is selected or an error occurs, redirect to a default page
header("Location: default_page.php");
exit();
?>
