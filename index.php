<?php
require_once('inc/connection.php');
$errorMessage = null;
if($_SERVER["REQUEST_METHOD"] == "POST"){
    $nic = strtolower($_POST['nic']);
    $password = $_POST['password'];
    $sqlQuary = "SELECT role,uid FROM registration WHERE nic='$nic' AND password='$password' LIMIT 1";
    $result = $conn->query($sqlQuary);
    if($result->num_rows > 0){
        //derection to the dashboard acording to the role
        while($row = $result->fetch_assoc()){
            if($row['role'] == 'admin'){
                header("Location: admin_dashboard.php?uid=".$row['uid']);
            }else{
                header("Location: user_dashboard.php?uid=".$row['uid']);
            }
        }
    }else{
        $errorMessage= "Invalid email or password";
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

  <style>
    body {
      background-color: #f8f9fa;
    }

    .container {
      background-color: #ffffff;
      padding: 30px;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .container2 {
      background-color: #ffffff;
      padding: 30px;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
      padding-bottom: 10px;
    }

  </style>

</head>

<body>

  <div class="container my-5">
    <div>
      <h1 class="text-center mb-5 mt-4">Smart Parking (Pvt)</h1>
    </div>
    <div class="row justify-content-center my-3">
      <div class="col-md-4 container2">
        <form method="POST" action="index.php">
          <div class="form-group">
            <label for="nic">NIC:</label>
            <input type="text" class="form-control" id="nic" name =  "nic" placeholder="Enter your NIC" required>
          </div>
          <div class="form-group mt-2">
            <label for="password">Password:</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required>
          </div>
          <div class="d-grid gap-2">
            
            <button type="submit" class="btn btn-primary mt-4 ">Log In</button>
            <p class="text-danger" style="text-align: center;"><?php echo $errorMessage?></p>
          </div>

          <div class="d-flex justify-content-between mt-3">
            <p>Don't have an account? <button type="button" class="btn btn-link" onclick="openRagistaionForm()">
                Sign in
              </button></p>
          </div>
        </form>
      </div>
    </div>

  </div>

  <footer class="text-center p-3" style="background-color: #f8f9fa;">
    <p>&copy; 2023 Smart Parking (Pvt) Ltd. All rights reserved.</p>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
      crossorigin="anonymous"></script>
      <script>
        function openRagistaionForm() {
          window.location.href = "registration.php";
        }
      </script>
</body>
</html>