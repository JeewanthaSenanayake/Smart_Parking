<?php
require_once('inc/connection.php');
//relentless the slots that are booked and the time is over
$sqlQuary = "SELECT * FROM solts_table";
$result = $conn->query($sqlQuary);
if($result->num_rows > 0){
    $currentDateTimeObj = new DateTime('now', new DateTimeZone('Asia/Kolkata'));
    while($row = $result->fetch_assoc()){
        
        $givenDateTimeObj = new DateTime($row["outTime"],new DateTimeZone('Asia/Kolkata'));
        
        if( $row["outTime"]!=null && $givenDateTimeObj < $currentDateTimeObj){
            
                $temp_id = $row["slotId"];
                $sqlQuary2 = "UPDATE `solts_table` SET `isAvailable`=1, `outTime`=null, `inTime`=null, `bookedCar`=null WHERE `slotId`=$temp_id";
                $conn->query($sqlQuary2);
            
        }
    }
}

$uid = null;
if($_SERVER["REQUEST_METHOD"] == "GET"){
    $uid = $_GET['uid'];
    $sqlQuary = "SELECT name FROM registration WHERE uid='$uid' LIMIT 1";
    $result = $conn->query($sqlQuary);
    if($result->num_rows > 0){
        while($row = $result->fetch_assoc()){
            $name = $row['name'];
        }
    }
}

$tableData = "";
$sqlQuary = "SELECT ph.*, s.slot_name, s.isAvailable FROM parking_history ph JOIN solts_table s ON ph.slotId = s.slotId WHERE ph.uid=$uid ORDER BY s.isAvailable ASC ";
$result = $conn->query($sqlQuary);
if($result->num_rows > 0){
    $count = 1;
    $status = "";
    
    while($row = $result->fetch_assoc()){
        if($row["isAvailable"] == 1){
            $status = "Completed";
        }else{
            $status = "Reserved For You";
        }
        $tableData = $tableData.'<tr>
                    <th scope="row">'.$count.'</th>
                    <td>'.$row["slot_name"].'</td>
                    <td>'.$row["vehical_number"].'</td>
                    <td>'.$row["vehical_type"].'</td>
                    <td>'.$row["in_date"].'</td>
                    <td>'.$row["out_date"].'</td>
                    <td>'.$status.'</td>
                </tr>';
        $count++;
    }
}




$conn->close();
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Smart Parking (Pvt)</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        header {
            background-color: #3498db;
            color: #ffffff;
            padding: 10px;
            text-align: center;
        }
    </style>

</head>

<body>
    <header class="red" style="background-color: rgb(20, 20, 20);">
        <div class="row">
            <div class="col-2">
                <h3 style="text-align: left; ">Smart Parking </h3>
            </div>
            <div class="col" style="text-align: left; margin-top: 7px;">
                <a style="margin-right: 10px;" href="user_dashboard.php?uid=<?php echo $uid?>">Home</a>
                <a style="margin-right: 10px;" href="user_booking.php?uid=<?php echo $uid?>">My Booking</a>
            </div>
            <div class="col mt-2" style="text-align: right">
                <div id="userGreeting">Hi,
                    <?php echo $name?>
                </div>
            </div>
            <div class="col-1 mt-2" style="text-align: right">
                <button style="text-align: right" class="btn btn-secondary btn-sm" id="logoutButton"
                    onclick="logout()"><i class="bi bi-box-arrow-right"></i>Logout</button>
                <div>
                </div>
    </header>

    <div style="margin: 30px;">
        <h1 style="margin-bottom: 30px;">My Bookings</h1>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Slot Name</th>
                    <th scope="col">Vehicle Number</th>
                    <th scope="col">Vehicle Type</th>
                    <th scope="col">In Date</th>
                    <th scope="col">Out Date</th>
                    <th scope="col">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php echo $tableData?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
        crossorigin="anonymous"></script>

    <script>
        function logout() {
            window.location.href = "index.php";
        }
    </script>
</body>

</html>