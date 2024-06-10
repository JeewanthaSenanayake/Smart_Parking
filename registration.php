<?php
 require_once('inc/connection.php');
 $error_mage=null;
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $cpassword = $_POST['cpassword'];
        $mobileNumber = $_POST['mobileNumber'];
        $nic = strtolower($_POST['nic']) ;
        $dropdown = $_POST['dropdown'];
        $adminPin = null;
        $adminPinVerify = true;
        $role="user";
        
        if($dropdown == 'admin'){
            $adminPinVerify = false;
            $adminPin = $_POST['adminPin'];
            if($adminPin == 'admin'){
                $role = 'admin';
                $adminPinVerify = true;
            }else{
                $error_mage = "Invalid Admin Pin, You can't create admin account.";
                $adminPinVerify = false;
            }           
        }

        if($adminPinVerify){
            try {
                $sqlQuary = "INSERT INTO registration (`name`, `email`, `password`, `mobileNumber`, `nic`, `role`) 
            VALUES ('$name','$email','$password','$mobileNumber','$nic','$role')";
            $result = $conn->query($sqlQuary);
            if($result){
                header("Location: index.php"); 
            }
            } catch (\Throwable $th) {
                $error_mage="This NIC already exist";
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
        <div class="row justify-content-center my-3">
            <div class="col-md-4 container2">
                <?php if($error_mage != null){ echo '<div class="alert alert-danger" role="alert">'.$error_mage.'</div>'; }?>
                <form method="POST" action="registration.php">
                    <div class="form-group">
                        <label for="username">Name:</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Enter your name"
                            required>
                    </div>
                    <div class="form-group mt-2">
                        <label for="username">E-mail:</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Enter your E-mail"
                            required>
                    </div>
                    <div class="form-group mt-2">
                        <label for="password">Password:</label>
                        <input type="password" class="form-control" id="password" name="password"
                            placeholder="Enter your password" required>
                        <p id="passwordHints" ></p>
                    </div>
                    <div class="form-group mt-2">
                        <label for="password">Conform Password:</label>
                        <input type="password" class="form-control" id="cpassword" name="cpassword"
                            placeholder="Conform your password" required>
                            <p id="passwordMatch" ></p>
                    </div>
                    <div class="form-group mt-2">
                        <label for="username">Mobile number:</label>
                        <input type="tel" class="form-control" id="mobileNumber" name="mobileNumber"
                            placeholder="Enter your mobile number" required>
                    </div>
                    <div class="form-group mt-2">
                        <label for="username">NIC:</label>
                        <input type="text" class="form-control" id="nic" name="nic" placeholder="Enter your NIC number"
                            required>
                    </div>
                    <div class="form-group mt-2">
                        <label for="username">Account Type:</label>

                        <select class="form-select" aria-label="Default select example" id="dropdown" name="dropdown">
                            <!-- <option selected>User Account</option> -->
                            <option value="user">User Account</option>
                            <option value="admin">Admin Account</option>
                        </select>

                    </div>
                    <div id="adminPipInput"></div>
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary mt-4 " id="signIn">Sign In</button>
                    </div>
                </form>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
        crossorigin="anonymous"></script>


    <script>
        function openRagistaionForm() {
            window.location.href = "registration.php";
        }
        const dropdown = document.getElementById('dropdown');

        dropdown.addEventListener('change', function () { // for admin account create validation

            if (dropdown.value == "admin") {
                document.getElementById('adminPipInput').innerHTML =
                    '<div class="form-group mt-2"><label for="password">Admin Pin:</label><input type="password" class="form-control" id="adminPin" name="adminPin" placeholder="Enter Admin Pin" required></div>';
            } else {
                document.getElementById('adminPipInput').innerHTML = '';
            }
        });

        const password = document.getElementById('password');
        const cpassword = document.getElementById('cpassword');
        const passwordMatch = document.getElementById('passwordMatch');
        cpassword.addEventListener('change', function () {
            if ((password.value != cpassword.value) && (cpassword.value != '')) {
                passwordMatch.style.color = 'red';
                passwordMatch.innerHTML = 'Password and Conform Password are not matching';
                document.getElementById('signIn').disabled = true;
            }else{
                passwordMatch.innerHTML = '';
                document.getElementById('signIn').disabled = false;
            }
        });
        password.addEventListener('change', function () { // for password validation
             const passwordHints = document.getElementById('passwordHints');
             passwordHints.style.color = 'red';
            if(password.value.length < 8){
                passwordHints.innerHTML = 'Password must be at least 8 characters long';
            }
            else if(password.value.length > 15){
                passwordHints.innerHTML = 'Password must be less than 15 characters long';
            }
            else if(password.value.search(/[a-z]/) == -1){
                passwordHints.innerHTML = 'Password must contain at least one lowercase letter';
            }
            else if(password.value.search(/[A-Z]/) == -1){
                passwordHints.innerHTML = 'Password must contain at least one uppercase letter';
            }
            else if(password.value.search(/[0-9]/) == -1){
                passwordHints.innerHTML = 'Password must contain at least one number';
            }
            else if(password.value.search(/[!@#$%^&*]/) == -1){
                passwordHints.innerHTML = 'Password must contain at least one special character';
            }else{
                passwordHints.innerHTML = '';
            }

            if (password.value != cpassword.value && (cpassword.value != '')) {
                // alert('Password and Conform Password are not matching');
                passwordMatch.style.color = 'red';
                passwordMatch.innerHTML = 'Password and Conform Password are not matching';
                document.getElementById('signIn').disabled = true;
            }else{
                passwordMatch.innerHTML = '';
                document.getElementById('signIn').disabled = false;
            }
        });

    </script>
</body>

</html>