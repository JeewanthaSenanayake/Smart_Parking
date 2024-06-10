<?php
require_once('vendor/autoload.php'); // Include Stripe PHP library

\Stripe\Stripe::setApiKey('sk_test_51Oh6sdBfoYjHOdSqbuZjlbjznquGoc1qivsuMd63hevvnVIZ49Yli0fc6SzRhtPbKJVJl7I1Tr3Lo3tdKaKAHPgt00SPJdUIib'); //  secret key
$ammount=$_GET['ammount'];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['stripeToken'];
    $ammount = $ammount * 100;

    try {
        $charge = \Stripe\Charge::create([
            'amount' => $ammount , // Amount in cents
            'currency' => 'lkr',
            'source' => $token,
            'description' => 'Smart Parking',
        ]);

        // Send a success response back to the client
        echo json_encode(['success' => true]);
        exit();
    } catch (\Stripe\Exception\CardException $e) {
        // Card error
        echo json_encode(['error' => $e->getMessage()]);
        exit();
    } catch (\Exception $e) {
        // General error
        echo json_encode(['error' => $e->getMessage()]);
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment</title>
    <script src="https://js.stripe.com/v3/"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        .container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body>

    <form id="payment-form" style="padding: 20px; text-align: center;">
        <div class="container">
            <h2>Smart Parking (Pvt)</h2>
            <p>Enter your card details to make the payment</p>
            <!-- <p>Total Amount
                
            </p> -->
            <h5>Rs:
                <?php echo $ammount ?>
            </h5>
            <div style="margin-top: 20px; ">
                <div id="card-element">
                    <!-- A Stripe Element will be inserted here. -->
                </div>

                <!-- Used to display form errors. -->
                <div style="color: red; margin-top: 10px;" id="card-errors" role="alert"></div>
            </div>
            <!-- loding -->
            <div class="text-center" id="loadingIndicator" style="display: none; margin-top: 20px;">
                <div class="spinner-border" role="status">
                    <span class="sr-only"></span>
                </div>
                <p>Processing...</p>
            </div>

            <!-- success -->
            <div class="text-center" id="successIndicator" style="display: none; color: green; margin-top: 20px;">
                <p>Success!</p>
            </div>

            <div class="d-grid gap-2">
                <button style="margin-top: 25px;" type="submit" onclick="startLoading()"
                    class="btn btn-primary btn-sm">Pay Now</button>
            </div>
        </div>



    </form>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
        crossorigin="anonymous"></script>
    <script>
        function startLoading() {
            document.getElementById('loadingIndicator').style.display = 'block';
        }

        var stripe = Stripe('pk_test_51Oh6sdBfoYjHOdSqaZa67VYKG0IvrXWAsPdRiY3w6D93dtIKzZkBGcCt24mmH2CFy42iWcbeKaSZH2sz7h8Ppnov00PAxbKna7'); // Replace 'your-publishable-key' with your actual publishable key
        var elements = stripe.elements();
        var card = elements.create('card');
        card.mount('#card-element');

        card.addEventListener('change', function (event) {
            var displayError = document.getElementById('card-errors');
            if (event.error) {
                displayError.textContent = event.error.message;
            } else {
                displayError.textContent = '';
            }
        });

        var form = document.getElementById('payment-form');
        form.addEventListener('submit', function (event) {
            event.preventDefault();

            stripe.createToken(card).then(function (result) {
                if (result.error) {
                    var errorElement = document.getElementById('card-errors');
                    errorElement.textContent = result.error.message;
                } else {
                    stripeTokenHandler(result.token);
                }
            });
        });

        function stripeTokenHandler(token) {
            fetch(window.location.href, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'stripeToken=' + token.id,
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // alert('Payment successful!');
                        document.getElementById('loadingIndicator').style.display = 'none'; //for stop loading
                        document.getElementById('successIndicator').style.display = 'block'; // Show the success indicator

                        setTimeout(function () { //wait 500ms before closing the window
                            window.opener.postMessage(true, '*'); //send feedback to the parent window
                            window.close();
                        }, 500);


                    } else {
                        alert('Payment failed: ' + data.error);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }
    </script>

</body>

</html>