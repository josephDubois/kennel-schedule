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
                    Booking Types Notice
                </div>
                <div class="panel-body">
                    <p>Currently we are only offering online bookings for day care. We are working on adding overnight bookings at a later date.</p>
                </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Step 1 - Booking Information
                </div>
                <div class="panel-body">
                  <div class="row">
                    <div class="col-lg-12">
                      <?php
                      if(!empty($_SESSION['error'])){
                        $_POST['daycare'] = $_SESSION['dates'];
                      }

                      if ($_POST['daycare'] && notPast($_POST['daycare'][0])) {
                        if(empty($_SESSION['dates'])) {
                            $_SESSION['dates'] = array();
                            $_SESSION['dates'] = array_merge($_SESSION['dates'], $_POST['daycare']);
                        }else{
                            $_SESSION['dates'] = array_merge($_SESSION['dates'], $_POST['daycare']);
                            $_SESSION['dates'] = array_unique($_SESSION['dates']);
                        }
                      }else if(!$_POST['daycare']){
                        $_SESSION['dates'] = array();
                      }else{
                        echo '<div class="alert alert-danger">You cannot pick a date that is before today.</div>';
                      }
                      ?>
                      <form role="form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"])."?page=book";?>">
                        <div class="form-group col-lg-6">
                          <label>Select a Date:</label>
                            <input name="daycare[]" type="date" class="form-control" value="<?php echo date("Y-m-d"); ?>">
                        </div>
                        <div class="form-group col-lg-6">
                            <label>Selected Dates:</label>
                            <?php
                                if (!empty($_SESSION['dates'])){
                                  sort($_SESSION['dates']);
                                  echo  '<p class="form-control-static">';
                                  foreach ($_SESSION['dates'] as $value) {
                                    echo  $value.", ";
                                  }
                                  echo '</p>';
                                }else{
                                  echo '<p class="form-control-static">Select a date and click the button to add your dates.</p>';;
                                }
                             ?>

                        </div>
                        <button type="submit" class="btn btn-info">Add</button>
                      </form>
                      <br/>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-lg-12">
                      <?php if (!empty($_SESSION['error'])){ echo "<div class='alert alert-danger'>".$_SESSION['error']."</div>"; $_SESSION['error'] = ""; } ?>
                      <form role="form" method="post" action="validate/daycare-validate.php">
                        <div class="form-group">
                            <label>Select Dog(s) *:</label>
                            <select name="dogs[]" multiple="" class="form-control">
                              <?php
                                $numberOfDogs = count($dogs) - 1;
                                for ($i = 0; $i <= $numberOfDogs; $i++){
                                  echo "<option value='".$i."'>".$dogs[$i]['name']."</option>";
                                }
                              ?>
                            </select>
                        </div>
                        <button <?php if (empty($_SESSION['dates'])){ echo 'disabled="disabled"'; } ?> type="submit" class="btn btn-primary">Go To Payment</button>
                      </form>
                    </div>
                  </div>
                </div>
            </div>
          </div>
        </div>
        <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
</div>
<!-- /#page-wrapper -->
