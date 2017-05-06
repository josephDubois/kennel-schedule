<?php require_once('../config.php'); ?>
<!-- Page Content -->
<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">New Day Care Booking</h1>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <div class="row">
          <div class="col-lg-12">
            <div class="panel panel-info">
                <div class="panel-heading">
                    Booking Overview
                </div>
                <div class="panel-body">
                  <div class="form-group col-lg-6">
                      <label>Dogs Selected:</label>
                      <?php
                        foreach ($_SESSION['selectedDogs'] as $value) {
                          echo '<p class="form-control-static">'.$dogs[$value]['name'].'</p>';
                        }
                       ?>

                  </div>
                  <div class="form-group col-lg-6">
                      <label>Dates Selected: </label>
                      <?php
                          echo  '<p class="form-control-static">';
                          foreach ($_SESSION['dates'] as $value) {
                            echo  $value.", ";
                          }
                          echo '</p>';
                       ?>

                  </div>
                  <div class="form-group col-lg-12">
                      <label>Price: </label>
                      <?php
                      $_SESSION['subtotal'] = subTotalDayCare($_SESSION['dates'], count($_SESSION['selectedDogs']));
                      $_SESSION['tax'] =  tax($_SESSION['subtotal']);
                      $_SESSION['process'] = processFee($_SESSION['subtotal']);
                      $_SESSION['total'] = total($_SESSION['subtotal'], $_SESSION['tax'], $_SESSION['process']);
                      ?>
                      <?php echo '<p class="form-control-static">Subtotal: $'.$_SESSION['subtotal'].'</p>'?>
                      <?php echo '<p class="form-control-static">Tax: $'.$_SESSION['tax'].'</p>'?>
                      <?php echo '<p class="form-control-static">Process Fee: $'.$_SESSION['process'].'</p>'?>
                      <?php echo '<p class="form-control-static">Total: $'.$_SESSION['total'].'</p>'?>
                  </div>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">
                    Step 2 - Payment Information
                </div>
                <div class="panel-body">
                  <form id="payment-form" role="form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"])."?page=charge"; ?>">
                    <div class="form-group col-lg-6">
                      <label>Name *:</label>
                      <input name="cardholder-name" class="field form-control" type="text" placeholder="Jane Doe" />
                    </div>
                    <div class="form-group col-lg-6">
                      <label>Card *:</label>
                        <div id="card-element" class="field"></div>
                    </div>
                    <button type="submit" class="btn btn-primary">Pay $<?php echo $_SESSION['total']; ?></button>
                    <div class="outcome">
                      <div class="error"></div>
                    </div>
                  </form>
                </div>
            </div>
          </div>
        </div>
        <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
</div>
<!-- /#page-wrapper -->
<script src="../js/payment-form.js"></script>
