<!-- Page Content -->
<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header"><?php echo $dogs[$_GET['number']]['name']; ?></h1>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- /.row -->
        <div class="row">
          <div class="col-lg-12">
            <?php
              $date = dateDiff($dogs[$_GET['number']]['age']);
              $issues = 0;
              if ($dogs[$_GET['number']]['meet'] == "") {
                $issues++;
                echo "<div class='alert alert-danger'>This dog is not yet approved to visit Friends Fur Ever Pet Resort. You will not be able to book this dog until you make a Meet N'Greet appointment.".
                " Please <a href=\"mailto:darlene@ffpr.ca?subject=Meet%20and%20Greet%20Appointment\">email us</a> or call us at (705) 692-3647 to schedule an appointment. Please visit our <a href='https://friendsfur-ever.ca/faq' target='_blank'>FAQ's</a>".
                " to become familiar with our requirements.</div>";
              }

              if(!notPast($dogs[$_GET['number']]['vdate'])) {
                $issues++;
                echo "<div class='alert alert-danger'>This dog's Vaccination's are expired. They must be updated and a copy sent to use before another booking can be made.</div>";
              }
              if($date['Year'] >= 0 && $date['Month'] < 8 && $dogs[$_GET['number']]['fixed'] === 'No') {
                $issues++;
                echo "<div class='alert alert-danger'>Your dog must be fixed after 8 months to visit our kennel.</div>";
              }

              if($issues === 0){
                echo "<div class='alert alert-success'>This dog is approved to visit Friends Fur Ever Pet Resort.</div>";
              }

            ?>
              <div id="general" class="panel panel-default">
                  <div id="my-information" class="panel-heading">
                      General Information
                  </div>
                  <div class="panel-body">
                      <div class="row">
                        <div class="col-lg-12">
                          <?php
                            $generalError = array();
                            $generalData = array();
                            if($_POST['general']) {
                              $generalData = $_POST['general'];

                              foreach ($generalData as $key => $value) {
                                $generalData[$key] = test_input($value);
                                $emptyResult = requiredEmpty($value);
                                $addData[$key] = mysqli_real_escape_string($link, $addData[$key]);
                                //check if empty//
                                if (!$emptyResult) {
                                  if ($key !== 'Notes') {
                                    $generalError[$key] = 'empty';
                                  }
                                }else{
                                  //check for letters and spaces only//
                                  if(strpos($key, 'Name') !== false || strpos($key, 'Breed') !== false || strpos($key, 'Color') !== false){
                                    $lettersResult = lettersOnly($value);
                                    if ($lettersResult === false){
                                        $generalError[$key] = 'letter';
                                      }
                                  }
                                }
                              }

                              if (empty($generalError)){
                                $updateResult = dogGeneralUpdate($dogs[$_GET['number']]['dogid'], $generalData, $link);
                                $dogs = loadDogsData($user['id'], $link);
                                echo '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>'.$updateResult.'</div>';
                              }else{
                                echo '<div class="alert alert-danger">';
                                //load error messages if any//
                                foreach ($generalError as $key => $value) {
                                  if($value === 'empty' ) {
                                    echo $key." cannot be empty. ";
                                  }else if($value === 'letter') {
                                    echo $key." should only have letters. ";
                                  }
                                }
                                echo "</div>";
                              }
                            }
                          ?>
                          <form role="form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"])."?page=dog&number=".$_GET['number']."#general";?>">
                            <div class="form-group col-lg-6">
                                <label>Name *:</label>
                                <input name="general[Name]" type="text" class="form-control" placeholder="Amy" value="<?php echo $dogs[$_GET['number']]['name']; ?>">
                            </div>
                            <div class="form-group col-lg-6">
                                <label>Breed *:</label>
                                <input name="general[Breed]" type="text" class="form-control" placeholder="Black Lab" value="<?php echo $dogs[$_GET['number']]['breed']; ?>">
                            </div>
                            <div class="form-group col-lg-6">
                                <label>Colour *:</label>
                                <input name="general[Color]" type="text" class="form-control" placeholder="Black" value="<?php echo $dogs[$_GET['number']]['color']; ?>">
                            </div>
                            <div class="form-group col-lg-6">
                                <label>Gender *:</label>
                                <select name="general[Gender]" class="form-control">
                                  <option value="Male" <?php echo ( $dogs[$_GET['number']]['gender'] == 'Male' ? ' selected="selected"' : ''); ?>>Male</option>
                                  <option value="Female" <?php echo ( $dogs[$_GET['number']]['gender'] == 'Female' ? ' selected="selected"' : ''); ?>>Female</option>
                                </select>
                            </div>
                            <div class="form-group col-lg-6">
                                <label>Spayed/Neutered *:</label>
                                <select name="general[Fixed]" class="form-control">
                                  <option value="Yes" <?php echo ( $dogs[$_GET['number']]['fixed'] == 'Yes' ? ' selected="selected"' : ''); ?>>Yes</option>
                                  <option value="No" <?php echo ( $dogs[$_GET['number']]['fixed'] == 'No' ? ' selected="selected"' : ''); ?>>No</option>
                                </select>
                            </div>
                            <div class="form-group col-lg-6">
                                <label>Weight *:</label>
                                <select name="general[Weight]" class="form-control">
                                  <option value="Large" <?php echo ( $dogs[$_GET['number']]['weight'] == 'Large' ? ' selected="selected"' : ''); ?>>Greater Than 35LBS (16KG)</option>
                                  <option value="Small" <?php echo ( $dogs[$_GET['number']]['weight'] == 'Small' ? ' selected="selected"' : ''); ?>>Less Than 35LBS (16KG)</option>
                                </select>
                            </div>
                            <div class="form-group col-lg-12">
                                <label>Notes :</label>
                                <textarea name="general[Notes]" type="text" class="form-control" placeholder="Any extra information."><?php echo $dogs[$_GET['number']]['special']; ?></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Save</button>
                          </form>
                        </div>
                      </div>
                  </div>
              </div>
              <div class="panel panel-default">
                  <div id="age" class="panel-heading">
                      Current Age
                  </div>
                  <div class="panel-body">
                      <div class="row">
                        <div class="col-lg-12">
                          <?php
                            if($_POST['age']) {
                              $ageError = "";
                              $age = $_POST['age'];
                              $emptyResult = requiredEmpty($age['date']);
                              if (!$emptyResult) {
                                $ageError = 'empty';
                              }else{
                                $dateResult = dateOnly($age['date']);
                                if(!$dateResult) {
                                  $ageError = 'date';
                                }
                              }
                              if (empty($ageError)){
                                $ageResult = dogAgeUpdate($dogs[$_GET['number']]['dogid'], $age['date'], $link);
                                if($ageResult) {
                                  $dogs = loadDogsData($user['id'], $link);
                                  echo '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>The dog\'s age was successfully updated.</div>';
                                }else{
                                  echo '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>There was a problem updating the dog\'s age.</div>';
                                }
                              }else{
                                echo '<div class="alert alert-danger">';
                                if($ageError === 'empty' ) {
                                  echo "The birth date cannot be empty. ";
                                }else if($ageError === 'date') {
                                  echo "The birth date should be a date. ";
                                }
                                echo "</div>";
                              }
                            }
                          ?>
                          <form role="form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"])."?page=dog&number=".$_GET['number']."#age";?>">
                            <div class="form-group col-lg-6">
                                <label>Birth Date *:</label>
                                <input aria-describedby="helpBlock" name="age[date]" type="date" class="form-control" placeholder="0" value="<?php echo $dogs[$_GET['number']]['age']; ?>">
                            </div>
                            <div class="form-group col-lg-6">
                                <label>Age:</label>
                                <p class="form-control-static"><?php echo $date['Year']." Year(s) and ".$date['Month']." Month(s)." ?></p>
                            </div>
                            <span id="helpBlock" class="help-block">We just need a rough idea of how old your dog is. It does not need to be precise.</span>
                            <button type="submit" class="btn btn-primary">Save</button>
                          </form>
                        </div>
                      </div>
                  </div>
              </div>
              <div class="panel panel-default">
                  <div id="food" class="panel-heading">
                      Food Information
                  </div>
                  <div class="panel-body">
                      <div class="row">
                        <div class="col-lg-12">
                          <?php
                            if($_POST['food']) {
                              $foodError = array();
                              $foodData = $_POST['food'];

                              foreach ($foodData as $key => $value) {
                                $foodData[$key] = test_input($value);
                                $emptyResult = requiredEmpty($value);
                                //check if empty//
                                if (!$emptyResult) {
                                  if ($key !== 'Notes') {
                                    $foodError[$key] = 'empty';
                                  }
                                }
                              }

                              if (empty($foodError)){
                                $updateResult = dogFoodUpdate($dogs[$_GET['number']]['dogid'], $foodData, $link);
                                if($updateResult) {
                                  $dogs = loadDogsData($user['id'], $link);
                                  echo '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>The dog\'s food information was successfully updated.</div>';
                                }else{
                                  echo '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>There was a problem updating the dog\'s food information.</div>';
                                }
                              }else{
                                echo '<div class="alert alert-danger">';
                                //load error messages if any//
                                foreach ($foodError as $key => $value) {
                                  if($value === 'empty' ) {
                                    echo $key." cannot be empty. ";
                                  }else if($value === 'letter') {
                                    echo $key." should only have letters. ";
                                  }
                                }
                                echo "</div>";
                              }

                            }
                          ?>
                          <form role="form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"])."?page=dog&number=".$_GET['number']."#food";?>">
                            <div class="form-group col-lg-6">
                                <label>What brand of food does your dog eat? *:</label>
                                <input name="food[Brand Of Food]" type="text" class="form-control" placeholder="Wellness Natural Pet Food" value="<?php echo $dogs[$_GET['number']]['brand']; ?>">
                            </div>
                            <div class="form-group col-lg-6">
                                <label>How much food do you give for every meal? *:</label>
                                <input name="food[Amount Of Food]" type="text" class="form-control" placeholder="1 Cup" value="<?php echo $dogs[$_GET['number']]['amount']; ?>">
                            </div>
                            <div class="form-group col-lg-12">
                                <label>How often do you feed your dog? *:</label>
                                <input name="food[How Often]" type="text" class="form-control" placeholder="1 Cup" value="<?php echo $dogs[$_GET['number']]['often']; ?>">
                            </div>
                            <button type="submit" class="btn btn-primary">Save</button>
                          </form>
                        </div>
                      </div>
                  </div>
              </div>
              <div class="panel panel-default">
                  <div id="vet" class="panel-heading">
                      Veterinarian Information
                  </div>
                  <div class="panel-body">
                      <div class="row">
                        <div class="col-lg-12">
                          <?php
                            if($_POST['vet']) {
                              $vetError = array();
                              $vetData = $_POST['vet'];

                              foreach ($vetData as $key => $value) {
                                $vetData[$key] = test_input($value);
                                $emptyResult = requiredEmpty($value);
                                //check if empty//
                                if (!$emptyResult) {
                                  if ($key !== 'Notes') {
                                    $vetError[$key] = 'empty';
                                  }
                                }else if(strpos($key, 'Phone') !== false){
                                  //check for letters and spaces only//
                                    $phoneResult = formatPhone($value);
                                    if ($phoneResult === false){
                                        $vetError[$key] = 'phone';
                                      }else{
                                        $vetData[$key] = $phoneResult;
                                      }
                                  }else if(strpos($key, 'Date') !== false){
                                    //check for letters and spaces only//
                                    $dateResult = dateOnly($value);
                                    if(!$dateResult) {
                                      $vetError = 'date';
                                    }
                                  }
                                }

                              if (empty($vetError)){
                                $updateResult = dogVetUpdate($dogs[$_GET['number']]['dogid'], $vetData, $link);
                                if($updateResult) {
                                  $dogs = loadDogsData($user['id'], $link);
                                  echo '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>The dog\'s veterinarian information was successfully updated.</div>';
                                }else{
                                  echo '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>There was a problem updating the dog\'s veterinarian information.</div>';
                                }
                              }else{
                                echo '<div class="alert alert-danger">';
                                //load error messages if any//
                                foreach ($vetError as $key => $value) {
                                  if($value === 'empty' ) {
                                    echo $key." cannot be empty. ";
                                  }else if($value === 'phone') {
                                    echo $key." is not a valid phone number. ";
                                  }else if($value === 'date') {
                                    echo $key." is not a valid date. ";
                                  }
                                }
                                echo "</div>";
                              }

                            }
                          ?>
                          <form role="form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"])."?page=dog&number=".$_GET['number']."#vet";?>">
                            <div class="form-group col-lg-6">
                                <label>Veterinarian Name *:</label>
                                <input name="vet[Veterinarian Name]" type="text" class="form-control" placeholder="John Smith" value="<?php echo $dogs[$_GET['number']]['vet']; ?>">
                            </div>
                            <div class="form-group col-lg-6">
                                <label>Veterinarian Phone *:</label>
                                <input name="vet[Veterinarian Phone]" type="tel" class="form-control" placeholder="(705) 888-8888" value="<?php echo $dogs[$_GET['number']]['vphone']; ?>">
                            </div>
                            <div class="form-group col-lg-12">
                                <label>Last Vaccination Date *:</label>
                                <input disabled='disabled' name="vet[Vaccination Date]" type="date" class="form-control" placeholder="<?php echo date("Y/m/d"); ?>" value="<?php echo $dogs[$_GET['number']]['vdate']; ?>">
                            </div>
                            <button type="submit" class="btn btn-primary">Save</button>
                          </form>
                        </div>
                      </div>
                  </div>
              </div>
              <div class="panel panel-default">
                  <div id="my-information" class="panel-heading">
                      Options
                  </div>
                  <div class="panel-body">
                      <div class="row">
                        <div class="col-lg-12">
                          <?php echo $_SESSION['Error']; ?>
                          <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteModal">Delete</button>
                        </div>
                        <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModal" aria-hidden="true" style="display: none;">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                            <h4 class="modal-title" id="myModalLabel">Delete <?php echo $dogs[$_GET['number']]['name']; ?>?</h4>
                                        </div>
                                        <div class="modal-body">
                                            Are you sure you really want to delete <?php echo $dogs[$_GET['number']]['name']; ?>?
                                        </div>
                                        <div class="modal-footer">
                                          <form role="form" method="post" action="deletedog.php">
                                            <input id="dognumber" name="dognumber" type="hidden" value="<?php echo $_GET['number']; ?>"/>
                                            <input id="dogid" name="dogid" type="hidden" value="<?php echo $dogs[$_GET['number']]['dogid']; ?>"/>
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-danger">Delete</button>
                                          </form>
                                        </div>
                                    </div>
                                    <!-- /.modal-content -->
                                </div>
                                <!-- /.modal-dialog -->
                            </div>
                      </div>
                  </div>
              </div>
          </div>
        </div>
    </div>
    <!-- /.container-fluid -->
</div>
<!-- /#page-wrapper -->
