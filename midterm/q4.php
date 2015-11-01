<?php

$orderId = 87389939;

if (array_key_exists('shipping_method', $_POST)) {
    $shipper = $_POST['shipping_method'];
} else if (array_key_exists('shipping_method', $_GET)) {
    $shipper = $_GET['shipping_method'];
} else {
    $shipper = 'UPS';
}


$shippingCost = 0.0;
$upsOverage = 1;

echo "calculating shipping for $orderId via $shipper<br>";

switch($shipper) {
    case 'UPS':
        $upsOverage = 1.7;
        $shippingCost += $upsOverage * 1.8;
        break;
    case 'DHL':
        $shippingCost = 1.8;
        break;
    default:
        die();
}

echo "The cost to ship is $$shippingCost";
?>
