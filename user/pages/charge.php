<?php
  require_once('../config.php');

  $token  = $_POST['stripeToken'];

  $customer = \Stripe\Customer::create(array(
      'email' => $user['email'],
      'source'  => $token
  ));

  $charge = \Stripe\Charge::create(array(
      'customer' => $customer->id,
      'amount'   => $_SESSION['total'] * 100,
      'currency' => 'cad'
  ));
?>
<!-- Page Content -->
<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Booking Confirmation</h1>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <div class="row">
          <div class="col-lg-12">
            <div class="panel panel-green">
                <div class="panel-heading">
                    Booking Details
                </div>
                <div class="panel-body">
                  <p>Your day care booking is confirmed. You will now receive an email as confirmation of your booking. A seperate email will be sent containing your payment. Thanks for booking with
                  Friends Fur Ever Pet Resort! We look forward to having your family stay with us.</p>
                </div>
            </div>
          </div>
        </div>
    </div>
</div>
