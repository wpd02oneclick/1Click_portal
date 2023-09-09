<?php
require_once "vendor/autoload.php";

use Omnipay\Omnipay;

define('CLIENT_ID', 'AX0GyN77K8c3jo86VrFPKvAB7ZTUIoKgzEHFzSBNUhGJg5gMpQMpzZBVZQ3P5dL5mAKnYpE1dnPQEnuy');
define('CLIENT_SECRET', 'EOO0CfyWcyHE0cxy60MdDw4QxqSktCsvakveHIVrbXinkZjQRQVYUoqThI-wt6XkjFwqrN5a_2v4LHq7');

define('PAYPAL_RETURN_URL', 'https://1click.com.pk/cv.writing/success.php');
define('PAYPAL_CANCEL_URL', 'https://1click.com.pk/cv.writing/cancel.php');
define('PAYPAL_CURRENCY', 'USD'); // set your currency here


$gateway = Omnipay::create('PayPal_Rest');
$gateway->setClientId(CLIENT_ID);
$gateway->setSecret(CLIENT_SECRET);
$gateway->setTestMode(false); //set it to 'false' when go livey