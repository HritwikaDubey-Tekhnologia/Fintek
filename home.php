<?php
session_start();

include("php/config.php");
if (!isset($_SESSION['valid'])) {
    header("Location: home.php");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style/style.css">
    <title>Home</title>
    <style>
        form {
            display: flex;
            flex-direction: column;
            max-width: 400px;
            margin: 0 auto;
        }

        label {
            margin-bottom: 8px;
        }

        input {
            margin-bottom: 16px;
            padding: 8px;
        }

        button {
            padding: 8px;
            background-color: #3498db;
            color: #ffffff;
            cursor: pointer;
        }

        .profile-section {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin: 10px;
            /* Adjust as needed */
        }


        .profile-image {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            overflow: hidden;
            background-color: #3498db;
            /* Set your desired background color */
            display: flex;
            justify-content: center;
            align-items: center;
            margin-right: 20px;
            /* Adjust as needed */
        }

        .profile-text {
            color: #ffffff;
            /* Set your desired text color */
            font-size: 24px;
            font-weight: bold;
        }

        .profile-details {
            flex-grow: 1;
            margin-left: 20px;
            /* Adjust as needed */
        }
    </style>

</head>

<body>
    <div class="nav">
        <div class="logo">
            <p><a href="home.php">FinTek</a> </p>
        </div>
        <div class="right-links">

            <?php
            $UserId = $_SESSION['UserId'];
            $query = mysqli_query($conn, "SELECT * FROM tblUser WHERE UserId = $UserId");

            while ($result = mysqli_fetch_assoc($query)) {
                $res_Utypeid = $result['UserTypeId'];
                $res_username = isset($result['username']) ? $result['username'] : ''; // Use isset to check if the key exists
                $res_UserId = $result['UserId'];
            }

            echo "<a href='edit.php?UserId=$res_UserId'>Change Profile</a>";
            ?>

            <a href="php/logout.php">
                <<button class="btn" style="background-color: #3498db; line-height: normal;">Log Out</button>

            </a>

        </div>


    </div>


    <!DOCTYPE html>
    <html>

    <head>
        <title>FinTech</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
        <link rel="stylesheet" href="https://www.w3schools.com/lib/w3-theme-blue-grey.css">
        <link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Open+Sans'>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <style>
            html,
            body,
            h1,
            h2,
            h3,
            h4,
            h5 {
                font-family: "Open Sans", sans-serif
            }
        </style>
    </head>

    <body class="w3-theme-l5" style="overflow: hidden;">

        <!-- Navbar -->
        <div class="w3-top">
            <div class="w3-bar w3-theme-d2 w3-left-align w3-large">
                <a class="w3-bar-item w3-button w3-hide-medium w3-hide-large w3-right w3-padding-large w3-hover-white w3-large w3-theme-d2" href="javascript:void(0);" onclick="openNav()"><i class="fa fa-bars"></i></a>


            </div>
        </div>

        <!-- Navbar on small screens -->
        <div id="navDemo" class="w3-bar-block w3-theme-d2 w3-hide w3-hide-large w3-hide-medium w3-large">
            <a href="#" class="w3-bar-item w3-button w3-padding-large">Link 1</a>
            <a href="#" class="w3-bar-item w3-button w3-padding-large">Link 2</a>
            <a href="#" class="w3-bar-item w3-button w3-padding-large">Link 3</a>
            <a href="#" class="w3-bar-item w3-button w3-padding-large">My Profile</a>
        </div>

        <!-- Page Container -->
        <div class="w3-container w3-content" style="max-width:1400px;margin-top:80px">
            <!-- The Grid -->
            <div class="w3-row">
                <!-- Left Column -->
                <div class="w3-col m3">
                    <!-- Profile -->
                    <div class="w3-card w3-round w3-white">
                        <div class="w3-container">
                            <div class="profile-section">
                                <?php
                                // Assume $userName contains the user's name fetched from the database
                                $username = "Admin";
                                $firstLetter = substr($username, 0, 1);
                                ?>

                                <div class="profile-image">
                                    <span class="profile-text"><?php echo $firstLetter; ?></span>
                                </div>

                                <div class="profile-details">
                                    <p><b><?php echo $username ?></b></p>
                                    <p style="margin: 0;">Welcome</p>

                                </div>
                            </div>
                        </div>
                    </div>

                    <br>

                    <div class="w3-card w3-round">
                        <div class="w3-white">

                            <button onclick="location.href='Trans.php'" class="w3-button w3-block w3-theme-l1 w3-left-align" style="background-color: #77aaff;">Go to Transactions Page</button>

                            <div id="redirectDiv" style="margin-top: 1px;">
                                <button onclick="redirectToCreateGroups()" class="w3-button w3-block w3-theme-l2 w3-left-align" style="background-color: #66bbff;">Create Groups</button>
                            </div>

                            <script>
                                function redirectToCreateGroups() {
                                    window.location.href = 'Add.php';
                                }
                            </script>

                            <div id="redirectDiv1" style="margin-top: 1px;">
                                <button onclick="redirectToAddMembers()" class="w3-button w3-block w3-theme-l3 w3-left-align" style="background-color: #55ccff;">Add Members</button>
                            </div>

                            <script>
                                function redirectToAddMembers() {
                                    window.location.href = 'add_members.php';
                                }
                            </script>

                            <div id="redirectDiv2" style="margin-top: 1px;">
                                <button onclick="redirectToBettingRoom()" class="w3-button w3-block w3-theme-l4 w3-left-align" style="background-color: #44ddff;">Go to Betting Room</button>
                            </div>

                            <script>
                                function redirectToBettingRoom() {
                                    window.location.href = 'bettingRooms.php';
                                }
                            </script>

                        </div>
                    </div>

                    <br>

                    <!-- Alert Box -->
                    <div class="w3-container w3-display-container w3-round w3-theme-l4 w3-border w3-theme-border w3-margin-bottom w3-hide-small" style="position: relative; background-color: #f8f8f8; color: #333; padding: 20px; border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
                        <span onclick="this.parentElement.style.display='none'" class="w3-button w3-theme-l3 w3-display-topright" style="cursor: pointer; padding: 10px; margin-top: 0px; color: #555;">
                            <i class="fa fa-remove"></i>
                        </span>
                        <h3 style="margin-bottom: 15px;">Upcoming Events:</h3>
                        <?php
                        $currentDate = date('Y-m-d');
                        $sql = "SELECT TblAuction.StartDate, TblAuction.AmountAuctioned, TblGroup.GroupName
            FROM TblAuction
            JOIN TblGroup ON TblAuction.GroupId = TblGroup.GroupId
            WHERE TblAuction.StartDate > '$currentDate'
            ORDER BY TblAuction.StartDate ASC
            LIMIT 3";

                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            echo '<div style="background-color: #ec8689; color: #fff; border-radius: 8px; padding: 15px; margin-bottom: 20px;">';

                            while ($row = $result->fetch_assoc()) {
                                echo '<p style="margin-bottom: 10px;">- Group: ' . $row['GroupName'] . '<br>- Date: ' . $row['StartDate'] . '<br>- Amount: ' . $row['AmountAuctioned'] . '</p>';
                            }
                            echo '</div>';
                        } else {
                            echo '<p style="color: #555;">No upcoming auctions</p>';
                        }
                        ?>
                    </div>


                    <!-- End Left Column -->
                </div>

                <!-- Middle Column -->
                <div class="w3-col m7">

                    <div class="w3-row-padding">
                        <div class="w3-col m12">
                            <div class="w3-card w3-round w3-white">
                                <div class="w3-container w3-padding">
                                    <h2>Create Auction</h2>

                                    <form action="auction.php" method="post">
                                        <label for="agencyId" style="font-weight: bold;">Select Agency:</label>
                                        <select name="agencyId" id="agencyId" required onchange="fetchGroups()" style="width: 100%; padding: 8px; margin-bottom: 10px;">
                                            <!-- Fetch and display agency options from the database -->
                                            <?php
                                            $sqlAgency = "SELECT AgencyId, AgencyName FROM tblagency";
                                            $resultAgency = $conn->query($sqlAgency);

                                            if ($resultAgency->num_rows > 0) {
                                                while ($rowAgency = $resultAgency->fetch_assoc()) {
                                                    echo '<option value="' . $rowAgency['AgencyId'] . '">' . $rowAgency['AgencyName'] . '</option>';
                                                }
                                            } else {
                                                echo '<option value="" disabled>No agencies available</option>';
                                            }
                                            ?>
                                        </select>

                                        <label for="groupId" style="font-weight: bold;">Select Group:</label>
                                        <select name="groupId" id="groupId" required style="width: 100%; padding: 8px; margin-bottom: 10px;">
                                            <!-- Groups will be populated dynamically based on the selected agency -->
                                        </select>

                                        <label for="auctionDate" style="font-weight: bold;">Auction Date:</label>
                                        <input type="date" name="auctionDate" id="auctionDate" required style="width: 100%; padding: 8px; margin-bottom: 10px;">

                                        <label for="amountAuctioned" style="font-weight: bold;">Amount Auctioned:</label>
                                        <input type="number" name="amountAuctioned" id="amountAuctioned" required style="width: 100%; padding: 8px; margin-bottom: 10px;">

                                        <button type="submit" style="background-color: #0192E0; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer;">Create Auction</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>


                    <script>
                        function fetchGroups() {
                            var agencyId = document.getElementById("agencyId").value;
                            var groupIdSelect = document.getElementById("groupId");

                            // Fetch and display group options based on the selected agency
                            fetch("fetch_groups.php?agencyId=" + agencyId)
                                .then(response => response.json())
                                .then(groups => {
                                    groupIdSelect.innerHTML = ""; // Clear previous options

                                    if (groups.length > 0) {
                                        groups.forEach(group => {
                                            var option = document.createElement("option");
                                            option.value = group.GroupId;
                                            option.text = group.GroupName;
                                            groupIdSelect.add(option);
                                        });
                                    } else {
                                        var option = document.createElement("option");
                                        option.value = "";
                                        option.text = "No groups available";
                                        option.disabled = true;
                                        groupIdSelect.add(option);
                                    }
                                })
                                .catch(error => console.error('Error fetching groups:', error));
                        }
                    </script>





                    <!-- <div class="w3-container w3-card w3-white w3-round w3-margin"><br>

                        <h2 style="color: #333;">Transaction Details</h2>

                        <?php

                        // $sql = "SELECT UserId, AmountPaid FROM tblTransaction";
                        // $result = $conn->query($sql);

                        // echo '<table style="border-collapse: collapse; width: 100%;">';
                        // echo '<tr style="background-color: #f2f2f2;"><th style="padding: 8px; text-align: left;">User ID</th><th style="padding: 8px; text-align: left;">Amount Paid</th></tr>';

                        // if ($result->num_rows > 0) {
                        //     while ($row = $result->fetch_assoc()) {
                        //     echo '<tr>';
                        //     echo '<td style="padding: 8px; border: 1px solid #dddddd;">' . $row['UserId'] . '</td>';
                        //     echo '<td style="padding: 8px; border: 1px solid #dddddd;">' . $row['AmountPaid'] . '</td>';
                        //     echo '</tr>';
                        // }
                        // } else {
                        //     echo '<tr><td colspan="2" style="padding: 8px; border: 1px solid #dddddd;">No transactions found</td></tr>';
                        // }

                        // echo '</table>';
                        // 
                        ?>

                    </div>
 -->

                    <!-- --------------- -->




                    <!-- ------------- -->


                    <div class="w3-container w3-card w3-white w3-round w3-margin" id="includedContent"><br>
                        <h2 style="color: #333;">Auction Details</h2>
                        <script>
                            // Fetch the content of the HTML file using fetch API
                            fetch('admin_manage_bids.php')
                                .then(response => response.text())
                                .then(data => {
                                    // Insert the content into the specified element
                                    document.getElementById('formContainer').innerHTML = data;
                                })
                                .catch(error => console.error('Error fetching included file:', error));
                        </script>
                    </div>



                    <!-- ------------ -->

                    <!-- End Middle Column -->
                </div>

                <!-- Right Column -->
                <div class="w3-col m2">
                    <div class="w3-card w3-round w3-white w3-center">
                        <div class="w3-container">
                        <?php
                            // Include the payment.php file
                            include("payment.php");
                            ?>
                        </div>
                            </div>
                </div>

                        <br>

                        <br>

                        <!-- <div class="w3-card w3-round w3-white w3-padding-32 w3-center">
                            <?php
                            // Include the payment.php file
                            // include("payment.php");
                            ?>


                        </div> -->
                        <!-- <h2>Admin View - All Bids</h2>

<table>
    <thead>
        <tr>
            <th>Employee ID</th>
            <th>Requested Amount</th>
            <th>Reason</th>
        </tr>
    </thead>
    <tbody>
        <?php
        // Fetch all bids from the database
        $sql = "SELECT TblBids.UserId, TblBids.RequestedAmount, TblBids.Reason, TblUser.username
                FROM TblBids
                JOIN TblUser ON TblBids.UserId = TblUser.UserId";

        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>{$row['UserId']} ({$row['username']})</td>";
                echo "<td>{$row['RequestedAmount']}</td>";
                echo "<td>{$row['Reason']}</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='3'>No bids found</td></tr>";
        }


        ?>
    </tbody>
</table> -->
                    </div>

                    <!-- End Right Column -->
                </div>

                <!-- End Grid -->
            </div>

            <!-- End Page Container -->
        </div>
        <br>

        <!-- Footer -->
        <footer class="w3-container w3-theme-d3 w3-padding-16">
            <p>&copy;FinTech BC. All rights reserved.</p>
            <!-- Social Media Icons -->
            <a href="https://www.w3schools.com/w3css/" target="_blank" style="text-decoration: none; color: #fff; margin: 0 10px;">
                <img src="https://img.icons8.com/color/48/000000/facebook.png" alt="Facebook" style="width: 30px; height: 30px; vertical-align: middle;">
            </a>
            <a href="https://www.w3schools.com/w3css/" target="_blank" style="text-decoration: none; color: #fff; margin: 0 10px;">
                <img src="https://img.icons8.com/color/48/000000/twitter.png" alt="Twitter" style="width: 30px; height: 30px; vertical-align: middle;">
            </a>
            <a href="https://www.w3schools.com/w3css/" target="_blank" style="text-decoration: none; color: #fff; margin: 0 10px;">
                <img src="https://img.icons8.com/color/48/000000/instagram.png" alt="Instagram" style="width: 30px; height: 30px; vertical-align: middle;">
            </a>
            <a href="https://www.linkedin.com/" target="_blank" style="text-decoration: none; color: #fff; margin: 0 10px;">
                <img src="https://img.icons8.com/color/48/000000/linkedin.png" alt="LinkedIn" style="width: 30px; height: 30px; vertical-align: middle;">
            </a>
        </footer>

        <script>
            // Accordion
            function myFunction(id) {
                var x = document.getElementById(id);
                if (x.className.indexOf("w3-show") == -1) {
                    x.className += " w3-show";
                    x.previousElementSibling.className += " w3-theme-d1";
                } else {
                    x.className = x.className.replace("w3-show", "");
                    x.previousElementSibling.className =
                        x.previousElementSibling.className.replace(" w3-theme-d1", "");
                }
            }

            // Used to toggle the menu on smaller screens when clicking on the menu button
            function openNav() {
                var x = document.getElementById("navDemo");
                if (x.className.indexOf("w3-show") == -1) {
                    x.className += " w3-show";
                } else {
                    x.className = x.className.replace(" w3-show", "");
                }
            }
        </script>


    </body>

    </html>