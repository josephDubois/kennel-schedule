<?php
require_once('../stripe/init.php');

$stripe = array(
  "secret_key"      => "sk_test_fPhA0cGjlzd77nt1AJYR3cu2",
  "publishable_key" => "pk_test_NXoUKrRQwHrcm4cnlib9Q9HY"
);

\Stripe\Stripe::setApiKey($stripe['secret_key']);
?>
