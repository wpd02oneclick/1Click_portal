<?php
include("stripe-config.php");

if (!isset($_POST["stripeToken"]) || !isset($_POST["stripeTokenType"]) || !isset($_POST["amount"])) {
    // Handle missing data error here (redirect or show an error message)
    exit("Error: Required data is missing.");
}

$token = $_POST["stripeToken"];
$token_card_type = $_POST["stripeTokenType"];
$amount = $_POST["amount"];

// Convert the amount to cents (integer)
$amount_in_cents = intval(floatval($amount) * 100);

try {
    $charge = \Stripe\Charge::create([
        "amount" => $amount_in_cents,
        "currency" => 'usd', // Use USD currency code
        "source" => $token,
    ]);

    if ($charge->status === 'succeeded') {
        




        header("Location: index.php?amount=$amount");
        exit();
    } else {
        // Handle failed charge here (redirect or show an error message)
        exit("Error: Payment was not successful.");
    }
} catch (\Stripe\Exception\ApiErrorException $e) {
    // Handle Stripe API errors here
    // You can log the error for debugging purposes
    exit("Stripe API Error: " . $e->getMessage());
}
