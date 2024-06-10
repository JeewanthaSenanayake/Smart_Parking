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

$sqlQuary = "SELECT * FROM solts_table";
$result = $conn->query($sqlQuary);
    $slotGride="";
    if($result->num_rows > 0){
        while($row = $result->fetch_assoc()){
            $slotid=$row["slotId"];
            if($row["isAvailable"]==1){
                
                $slotGride = $slotGride.'<div class="col">
            <div class="card text-bg-secondary mb-3 ">
            <div class="card-header text-center">'.$row["slot_name"].'</div>
            <div class="card-body">
                <h5 class="card-title text-center">Available</h5>
                <p class="card-text text-center"> - </p></div></div></div>';
            }else{
                
                $slotGride = $slotGride.'<div class="col">
            <div class="card text-bg-danger mb-3 " >
            <div class="card-header text-center">'.$row["slot_name"].'</div>
            <div class="card-body">
                <h5 class="card-title text-center">Booked</h5>
                <p class="card-text text-center">'.$row["bookedCar"].'</p></div></div></div>';
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

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $slot_name = $_POST['slot_name'];
    $uid = $_POST['uid'];
    $sqlQuary = "INSERT INTO solts_table (`slot_name`) VALUES ('$slot_name')";
    $result = $conn->query($sqlQuary);
    if($result){
        header("Location: admin_dashboard.php?uid=".$uid);
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
                <a style="margin-right: 10px;" href="admin_dashboard.php?uid=<?php echo $uid?>">Home</a>
                <a style="margin-right: 10px;" href="parking_history.php?uid=<?php echo $uid?>">Parking History</a>
                <a style="margin-right: 10px;" href="payments.php?uid=<?php echo $uid?>">Payments</a>
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

    <div style="margin-left: 500px; margin-right: 500px; margin-top: 20px;">
    <form method="POST" action="admin_dashboard.php">
        <div class="form-group">
            <label for="slot_name">Create New Slot:</label>
            <input type="text" class="form-control" id="slot_name" name="slot_name"
                placeholder="Enter name for new parking slot" required>
            <input type="text" name="uid" id="uid" value='<?php echo $uid ?>' hidden>
        </div>
        <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary mt-2 " id="signIn">Create new slot</button>
                    </div>
    </form>
</div>

    <div style="margin: 30px;">
        <div class="row mb-4">
            <div class="col">
                <h3>Slots</h3>
            </div>

            <div class="row row-cols-1 row-cols-md-4 g-4">

                <?php echo $slotGride?>

            </div>
        </div>
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