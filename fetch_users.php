<?php
include("php/config.php");

$groupId = $_GET['groupId'];

$sql = "SELECT UserId, UserName FROM tbluser WHERE GroupId = $groupId";
$result = $conn->query($sql);

$users = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
}

echo json_encode($users);
?>
