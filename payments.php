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

$tableData = "";
$totalAmount = 0;
$sqlQuary = "SELECT py.*, regi.name FROM payments py JOIN registration regi ON py.acc_id = regi.uid";
$result = $conn->query($sqlQuary);
if($result->num_rows > 0){
    $count = 1;
    while($row = $result->fetch_assoc()){
        $totalAmount = $totalAmount + $row["amount"];
        $tableData = $tableData.'<tr>
                    <th scope="row">'.$count.'</th>
                    <td>'.$row["name"].'</td>
                    <td>'.$row["user_name"].'</td>
                    <td>'.$row["amount"].'</td>
                    
                </tr>';
        $count++;
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

    <div style="margin: 30px;">
        <h1 style="margin-bottom: 30px;">Payments </h1>
        <h3 style="margin-bottom: 30px;">Total Earn : Rs. <?php echo $totalAmount?></h3>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Acc Owner Name</th>
                    <th scope="col">Booked User Name</th>
                    <th scope="col">Amount</th>
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