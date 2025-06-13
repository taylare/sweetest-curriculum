<?php

 
  require_once('./config.php');

  $token  = $_POST['stripeToken'];
  $email  = $_POST['stripeEmail'];
   
  $totalamt = $_POST['totalamt'];
  

  $customer = \Stripe\Customer::create(array(
      'email' => $email,
      'source'  => $token
  ));

  $charge = \Stripe\Charge::create(array(
      'customer' => $customer->id,
      'amount'   => $totalamt,
      'currency' => 'cad'
  ));

  $amount = number_format(($totalamt / 100), 2);
  echo '<h3>Successfully charged $'.$amount.' </h3>Thank you for shopping at My Simple Shopping Cart';

?>