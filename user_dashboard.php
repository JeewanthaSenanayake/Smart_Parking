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

$sqlQuary = "SELECT * FROM solts_table";
$result = $conn->query($sqlQuary);
    $slotGride="";
    if($result->num_rows > 0){
        while($row = $result->fetch_assoc()){
            $slotid=$row["slotId"];
            if($row["isAvailable"]==1){
                
                $slotGride = $slotGride.'<div class="col">
            <div class="card text-bg-secondary mb-3 clickable-card" onclick="changeColor(this,'.$slotid.')">
            <div class="card-header text-center">'.$row["slot_name"].'</div>
            <div class="card-body">
                <h5 class="card-title text-center">Available</h5>
                <p class="card-text text-center"> Book now</p></div></div></div>';
            }else{
                
                $slotGride = $slotGride.'<div class="col">
            <div class="card text-bg-danger mb-3 " >
            <div class="card-header text-center">'.$row["slot_name"].'</div>
            <div class="card-body">
                <h5 class="card-title text-center">Booked</h5>
                <p class="card-text text-center">Try another slot</p></div></div></div>';
            }
            
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
                <h3 style="text-align: left">Smart Parking </h3>

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
        <div class="row mb-4">
            <div class="col">
                <h3>Book Your Slots</h3>
            </div>
            <div class="col-3" style="text-align: right; margin-right: 30px;">
                <button style="text-align: right; background-color:green;" class="btn btn-success btn-sm"
                    id="logoutButton" onclick="Continue()">Continue <i class="bi bi-arrow-right-circle"></i></button>
                    <p id="ContinueError"></p>
                <div>
                </div>
            </div>

            <div class="row row-cols-1 row-cols-md-4 g-4">

                <?php echo $slotGride?>

            </div>
        </div>



        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
            crossorigin="anonymous"></script>

        <script>
            function logout() {
                window.location.href = "index.php";
            }
            var selectedSlotId = [];
            function changeColor(card, slotid) {
                //add and remove slot id to array
                var index = selectedSlotId.indexOf(slotid);
                if (index === -1) {
                    // If not in the array, add it
                    selectedSlotId.push(slotid);
                } else {
                    // If already in the array, remove it
                    selectedSlotId.splice(index, 1);
                }

                // console.log(selectedSlotId);
                // color changing
                if (card.classList.contains('text-bg-secondary')) {
                    card.classList.remove('text-bg-secondary')
                    card.classList.add('text-bg-success')
                } else {
                    card.classList.remove('text-bg-success')
                    card.classList.add('text-bg-secondary')
                }

                if(selectedSlotId.length > 0){
                    document.getElementById("ContinueError").innerHTML = "";
                }
            }
            function Continue() {
                var ContinueError = document.getElementById("ContinueError");
                if(selectedSlotId.length == 0){
                    ContinueError.innerHTML = '<div class="alert alert-danger mt-1" role="alert">Please select a slot to continue</div>';
                    
                }else{
                    window.location.href = "booking.php?uid=<?php echo $uid?>&slotid=" + selectedSlotId;
                }
                
            }

            
        </script>
</body>
</html>