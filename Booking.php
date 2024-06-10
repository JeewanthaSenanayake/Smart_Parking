<?php
require_once('inc/connection.php');

$uid=null;
$selectedSlotes = null;
if($_SERVER["REQUEST_METHOD"] == "GET" || $_SERVER["REQUEST_METHOD"] == "POST"){
    
    $uid = $_GET['uid'];
    $selectedSlotes = $_GET['slotid'];
   
    
    $sqlQuary = "SELECT name FROM registration WHERE uid='$uid' LIMIT 1";
    $result = $conn->query($sqlQuary);
    if($result->num_rows > 0){
        while($row = $result->fetch_assoc()){
            $name = $row['name'];
        }
    }


    // SQL query to select records based on UIDs
    $sql = "SELECT slot_name, slotId FROM solts_table WHERE slotId IN ($selectedSlotes)";
    $result = $conn->query($sql);
    $bookingData=null;
    if($result->num_rows > 0){
        while($row = $result->fetch_assoc()){
            $slotId_temp = $row['slotId'];
            $bookingData = $bookingData.' <div class="card border-success mb-3">
            <div class="card-header bg-transparent border-success"><b>'.$row['slot_name'].'</b></div>
            <div class="card-body text-success">
                <div class="row mt-2">
                    <div class="col">
                        <label for="vehical_number" class="d-flex justify-content-start">Vehical Number :</label>
                    </div>
                    <div class="col">
                        <input class="d-flex justify-content-start form-control" type="text" name="vehical_number'.$slotId_temp.'"
                            id="vehical_number'.$slotId_temp.'" placeholder="Enter your mobile number" required>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col">
                        <label for="vehical_type" class="d-flex justify-content-start">Vehical Type :</label>
                    </div>
                    <div class="col">

                        <select class="form-select" aria-label="Default select example" id="vehical_type'.$slotId_temp.'" name="vehical_type'.$slotId_temp.'" required>
                            
                            <option value="car">Car</option>
                            <option value="bus">Bus</option>
                            <option value="bike">Bike</option>
                            <option value="van">Van</option>
                            <option value="three_wheel">Three Wheel</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col">
                        <label for="in_date" class="d-flex justify-content-start">In :</label>
                    </div>
                    <div class="col">
                        <input type="datetime-local" class="form-control" id="in_date'.$slotId_temp.'" name="in_date'.$slotId_temp.'" required>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col">
                        <label for="out_date" class="d-flex justify-content-start">Out :</label>
                    </div>
                    <div class="col">
                        <input type="datetime-local" class="form-control" id="out_date'.$slotId_temp.'" name="out_date'.$slotId_temp.'" required>
                    </div>
                </div>
            </div>

        </div>';
        }
    }

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $your_name = $_POST['your_name'];
        $your_nic = $_POST['your_nic'];
        $your_mobile = $_POST['your_mobile'];
        $totalPayment = $_POST['totalPaymentval'];

        $state;
        foreach ( explode(',', $selectedSlotes)as $key ) {
            $vehical_number = $_POST['vehical_number'.$key];
            $vehical_type = $_POST['vehical_type'.$key];
            $in_date = $_POST['in_date'.$key];
            $out_date = $_POST['out_date'.$key];
            if($vehical_type == "car"){
                $vehical_type = "Car";
            }else if($vehical_type == "bus"){
                $vehical_type = "Bus";
            }else if($vehical_type == "bike"){
                $vehical_type = "Bike";
            }else if($vehical_type == "van"){
                $vehical_type = "Van";
            }else if($vehical_type == "three_wheel"){
                $vehical_type = "Three Wheel";
            }else if($vehical_type == "other"){
                $vehical_type = "Other";
            }

            $sqlUpdate = "UPDATE `solts_table` SET `bookedCar`='$vehical_number',`inTime`='$in_date',`outTime`='$out_date',`isAvailable`='0' WHERE `slotId`='$key'";
            $state = $conn->query($sqlUpdate);
            if($state == FALSE){
                break;
            }
            $sqlInsert = "INSERT INTO `parking_history`(`uid`,`slotId`,`vehical_number`, `vehical_type`, `in_date`, `out_date`, `your_nic`, `your_name`, `your_mobile`) VALUES ('$uid','$key','$vehical_number','$vehical_type','$in_date','$out_date','$your_nic','$your_name','$your_mobile')";
            $state = $conn->query($sqlInsert);

            if($state == FALSE){
                break;
            }
            
        }
        $sqlPayemts = "INSERT INTO `payments`(`acc_id`, `user_name`, `amount`) VALUES ('$uid','$your_name','$totalPayment')";
        $state = $conn->query($sqlPayemts);
        if($state== TRUE){
            header("Location: user_dashboard.php?uid=".$uid);
        }

    }

}

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
            <div class="col">
                <h3 style="text-align: left">Smart Parking </h3>
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
    <form id="data_form" action="Booking.php?uid=<?php echo $uid?>&slotid=<?php echo $selectedSlotes?>" method="post"
        style="margin: 30px;">
        <div class="row">

            <div class="col">
                <div class="card text-center">
                    <div class="card-header">
                        Booking
                    </div>
                    <div class="row card-body">
                        
                        <div class="col-5 ">
                            <div class="row">
                                <div class="col">
                                    <label for="your_name" class="d-flex justify-content-start">Your Name :</label>
                                </div>
                                <div class="col">
                                    <input type="text" name="your_name" id="your_name" placeholder="Your name"
                                        class="form-control" required>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col">
                                    <label for="your_nic" class="d-flex justify-content-start">Your NIC :</label>
                                </div>
                                <div class="col">
                                    <input type="text" name="your_nic" id="your_nic" placeholder="Your NIC"
                                        class="form-control" required>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col">
                                    <label for="your_mobile" class="d-flex justify-content-start">Your Mobile Number
                                        :</label>
                                </div>
                                <div class="col">
                                    <input type="text" name="your_mobile" id="your_mobile"
                                        placeholder="Your mobile number" class="form-control" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-2"></div>
                        <div class="col">
                            <?php echo $bookingData?>

                        </div>
                        <div class="d-grid gap-2">
                            <h2>Total Payment : <span id="totalPayment">Rs: 0.00</span></h2>
                            <input type="text" name="totalPaymentval" id="totalPaymentval" hidden>
                            <button type="button" onclick="submitForm()" on class="btn btn-primary mt-2 " id="submitbtn">Book Now</button>
                        </div>
                    </div>

                </div>

            </div>

        </div>

    </form>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
        crossorigin="anonymous"></script>
    <script>

        function submitForm() {
            
            var popupWindow = window.open('payment_pop_up.php?ammount='+document.getElementById("totalPaymentval").value, 'Popup', 'width=400, height=400');
            
            // Add an event listener to receive feedback from the popup
            window.addEventListener('message', function(event) {
                // Log the feedback to the console (you can modify this part)
                console.log('Feedback from popup:', event.data);
                if(event.data===true){
                    var form = document.getElementById("data_form");
                    form.submit();
                }
            });
        }

        function logout() {
            window.location.href = "index.php";
        }
        var x = "<?php echo $selectedSlotes?>";
        slotids = x.split(",").map(Number);
        var vehicaletype = []
        var in_date = []
        var out_date = []
        var paymentList = []

        document.getElementById("submitbtn").disabled = true;
        for (const i in slotids) {
            paymentList.push(0);

            vehicaletype[i] = document.getElementById("vehical_type" + slotids[i]);
            in_date[i] = document.getElementById("in_date" + slotids[i]);
            out_date[i] = document.getElementById("out_date" + slotids[i]);

            vehicaletype[i].addEventListener('change', function () {
                calculatePayment(vehicaletype[i].value, calculateTimeDifference(in_date[i].value, out_date[i].value), i);
            });
            in_date[i].addEventListener('change', function () {
                console.log(out_date[i].value);
                if (new Date(in_date[i].value) <= new Date()) {
                    alert("In date should be greater than current date");
                    in_date[i].value = null;
                }
                if (in_date[i].value > out_date[i].value && out_date[i].value != "") {
                    alert("Out date should be greater than in date");
                    out_date[i].value = in_date[i].value = null;
                }
                calculatePayment(vehicaletype[i].value, calculateTimeDifference(in_date[i].value, out_date[i].value), i);
            });
            out_date[i].addEventListener('change', function () {
                if (in_date[i].value > out_date[i].value) {
                    alert("Out date should be greater than in date");
                    out_date[i].value = in_date[i].value = null;
                }
                calculatePayment(vehicaletype[i].value, calculateTimeDifference(in_date[i].value, out_date[i].value), i);

            });
        }

        function calculatePayment(vehi, time, index) {
            var payment, totalPayment = 0;
            if (vehi == "car") {
                payment = time * 70;
            } else if (vehi == "bus") {
                payment = time * 100;
            } else if (vehi == "bike") {
                payment = time * 20;
            } else if (vehi == "van") {
                payment = time * 85;
            } else if (vehi == "three_wheel") {
                payment = time * 40;
            } else if (vehi == "other") {
                payment = time * 150;
            }
            paymentList[index] = payment;

            let butunState = false;
            for (const i in paymentList) {
                // console.log(paymentList);
                if (paymentList[i] == 0 || isNaN(paymentList[i])) {
                    butunState = true;
                } else {
                    totalPayment += paymentList[i];
                }
            }
            totalPayment = totalPayment+170.00; //stripe can pay minimum 0.5$ so we add Rs.170.00 to the total payment
            document.getElementById("submitbtn").disabled = butunState;
            document.getElementById("totalPayment").innerText = "Rs: " + totalPayment.toFixed(2);
            document.getElementById("totalPaymentval").value = totalPayment.toFixed(2);

        }

        function calculateTimeDifference(in_date, out_date) {
            //calculate time parking time
            var datetime1 = new Date(out_date);
            var datetime2 = new Date(in_date);
            var timeDifference = Math.abs(datetime1 - datetime2);
            var hoursDifference = timeDifference / (1000 * 60 * 60);

            return hoursDifference;
        }

    </script>
</body>

</html>