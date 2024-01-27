<?php
include("php/config.php");

$agencyId = $_GET['agencyId'];

$sql = "SELECT GroupId, GroupName FROM tblgroup WHERE AgencyId = $agencyId";
$result = $conn->query($sql);

$groups = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $groups[] = $row;
    }
}

echo json_encode($groups);
?>
