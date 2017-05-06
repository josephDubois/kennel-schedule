<!-- Page Content -->
<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">New Dog</h1>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- /.row -->
        <div class="row">
          <div class="col-lg-12">
              <div id="general" class="panel panel-default">
                  <div id="my-information" class="panel-heading">
                      New Dog Information
                  </div>
                  <div class="panel-body">
                      <div class="row">
                        <div class="col-lg-12">
                          <?php
                            $addData = array();
                            if ($_POST['add']){
                              $addError = array();
                              $addData = $_POST['add'];
                              foreach ($addData as $key => $value) {
                                $addData[$key] = test_input($value);
                                $emptyResult = requiredEmpty($value);
                                //check if empty//
                                if (!$emptyResult) {
                                  if ($key !== 'Notes') {
                                    $addError[$key] = 'empty';
                                  }
                                }else if(strpos($key, 'Name') !== false || strpos($key, 'Breed') !== false || strpos($key, 'Color') !== false){
                                  $letterResult = lettersOnly($value);
                                  if(!$letterResult){
                                    $addError[$key] = 'letter';
                                  }
                                }else if(strpos($key, 'Date') !== false){
                                  $dateResult = dateOnly($value);
                                  if(!$dateResult){
                                    $addError[$key] = 'date';
                                  }
                                }else if(strpos($key, 'Phone') !== false){
                                  $phoneResult = formatPhone($value);
                                  if(!$phoneResult){
                                    $addError[$key] = 'phone';
                                  }else{
                                    $addData[$key] = $phoneResult;
                                  }
                                }
                              }

                              if(empty($addError)){
                                $addResult = addDog($user['id'], $addData, $link);
                                if($addResult) {
                                  $dogs = loadDogsData($user['id'], $link);
                                  echo "<div class='alert alert-success'>The dog was successfully added.</div>";
                                  $_POST = array();
                                }else{
                                  echo "<div class='alert alert-danger'>There was a problem adding this dog. Please try again.</div>";
                                }
                              }else{
                                echo '<div class="alert alert-danger">';
                                //load error messages if any//
                                foreach ($addError as $key => $value) {
                                  if($value === 'empty' ) {
                                    echo $key." cannot be empty. ";
                                  }else if($value === 'letter' ) {
                                    echo $key." can only be letters. ";
                                  }else if($value === 'date' ) {
                                    echo $key." must be a valid date. ";
                                  }else if($value === 'phone' ) {
                                    echo $key." must be a valid phone number. ";
                                  }
                                }
                                echo "</div>";
                              }
                            }
                          ?>
                          <form role="form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"])."?page=adddog"; ?>">
                            <div class="form-group col-lg-6">
                                <label>Name *:</label>
                                <input name="add[Name]" type="text" class="form-control" placeholder="Amy" value="<?php if ($_POST['add']) { echo $addData['Name']; } ?>">
                            </div>
                            <div class="form-group col-lg-6">
                                <label>Breed *:</label>
                                <input name="add[Breed]" type="text" class="form-control" placeholder="Black Lab" value="<?php if ($_POST['add']) { echo $addData['Breed']; } ?>">
                            </div>
                            <div class="form-group col-lg-6">
                                <label>Colour *:</label>
                                <input name="add[Color]" type="text" class="form-control" placeholder="Black" value="<?php if ($_POST['add']) { echo $addData['Color']; } ?>">
                            </div>
                            <div class="form-group col-lg-6">
                                <label>Gender *:</label>
                                <?php echo $addData['Gender']; ?>
                                <select name="add[Gender]" class="form-control">
                                  <option value="Male" <?php if ($_POST['add']) { echo ( $addData['Gender'] == 'Male' ? ' selected="selected"' : ''); }?>>Male</option>
                                  <option value="Female" <?php if ($_POST['add']) { echo ( $addData['Gender'] == 'Female' ? ' selected="selected"' : ''); }?>>Female</option>
                                </select>
                            </div>
                            <div class="form-group col-lg-6">
                                <label>Spayed/Neutered *:</label>
                                <select name="add[Fixed]" class="form-control">
                                  <option value="Yes" <?php if ($_POST['add']) { echo ( $addData['Fixed'] == 'Yes' ? ' selected="selected"' : ''); }?>>Yes</option>
                                  <option value="No" <?php if ($_POST['add']) { echo ( $addData['Fixed'] == 'No' ? ' selected="selected"' : ''); }?>>No</option>
                                </select>
                            </div>
                            <div class="form-group col-lg-6">
                                <label>Weight *:</label>
                                <select name="add[Weight]" class="form-control">
                                  <option value="Large" <?php if ($_POST['add']) { echo ( $addData['Weight'] == 'Large' ? ' selected="selected"' : ''); }?>>Greater Than 35LBS (16KG)</option>
                                  <option value="Small" <?php if ($_POST['add']) { echo ( $addData['Weight'] == 'Small' ? ' selected="selected"' : ''); }?>>Less Than 35LBS (16KG)</option>
                                </select>
                            </div>
                            <div class="form-group col-lg-12">
                                <label>Notes :</label>
                                <textarea name="add[Notes]" type="text" class="form-control" placeholder="Any extra information."><?php if ($_POST['add']) { echo $addData['Notes']; } ?></textarea>
                            </div>
                            <div class="form-group col-lg-12">
                                <label>Birth Date *:</label>
                                <input aria-describedby="helpBlock" name="add[Date]" type="date" class="form-control" placeholder="0" value="<?php if ($_POST['add']) { echo $addData['Date']; } ?>">
                            </div>
                            <span id="helpBlock" class="help-block">We just need a rough idea of how old your dog is. It does not need to be precise.</span>
                            <div class="form-group col-lg-6">
                                <label>What brand of food does your dog eat? *:</label>
                                <input name="add[Brand Of Food]" type="text" class="form-control" placeholder="Wellness Natural Pet Food" value="<?php if ($_POST['add']) { echo $addData['Brand Of Food']; } ?>">
                            </div>
                            <div class="form-group col-lg-6">
                                <label>How much food do you give for every meal? *:</label>
                                <input name="add[Amount Of Food]" type="text" class="form-control" placeholder="1 Cup" value="<?php if ($_POST['add']) { echo $addData['Amount Of Food']; } ?>">
                            </div>
                            <div class="form-group col-lg-12">
                                <label>How often do you feed your dog? *:</label>
                                <input name="add[How Often]" type="text" class="form-control" placeholder="1 Cup" value="<?php if ($_POST['add']) { echo $addData['How Often']; } ?>">
                            </div>
                            <div class="form-group col-lg-6">
                                <label>Veterinarian Name *:</label>
                                <input name="add[Veterinarian Name]" type="text" class="form-control" placeholder="John Smith" value="<?php if ($_POST['add']) { echo $addData['Veterinarian Name']; } ?>">
                            </div>
                            <div class="form-group col-lg-6">
                                <label>Veterinarian Phone *:</label>
                                <input name="add[Veterinarian Phone]" type="tel" class="form-control" placeholder="(705) 888-8888" value="<?php if ($_POST['add']) { echo $addData['Veterinarian Phone']; } ?>">
                            </div>
                            <div class="form-group col-lg-12">
                                <label>Last Vaccination Date *:</label>
                                <input name="add[Vaccination Date]" type="date" class="form-control" placeholder="<?php echo date("Y/m/d"); ?>" value="<?php if ($_POST['add']) { echo $addData['Vaccination Date']; } ?>">
                            </div>
                            <button type="submit" class="btn btn-primary">Save</button>
                          </form>
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
