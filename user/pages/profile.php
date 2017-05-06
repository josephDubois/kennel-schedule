<!-- Page Content -->
<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">My Profile</h1>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- /.row -->
        <div class="row">
          <div class="col-lg-12">
              <div class="panel panel-default">
                  <div id="my-information" class="panel-heading">
                      My Information
                  </div>
                  <div class="panel-body">
                      <div class="row">
                        <div class="col-lg-12">
                          <?php
                          $dataErr = array();
                          $data = array();
                          if (!empty($_POST['profile'])) {
                            $data = $_POST['profile'];
                            foreach ($data as $key => $value) {
                              $data[$key] = test_input($value);
                              $emptyResult = requiredEmpty($value);
                              //check if empty//
                              if (!$emptyResult) {
                                if ($key !== 'Mobile Phone' && $key !== 'Work Phone' && $key !== 'Address Line Two') {
                                  $dataErr[$key] = 'empty';
                                }
                              }else{
                                //check for letters and spaces only//
                                if(strpos($key, 'Name') !== false || strpos($key, 'City') !== false){
                                  $lettersResult = lettersOnly($value);
                                  if ($lettersResult === false){
                                      $dataErr[$key] = 'letter';
                                    }
                                }
                                //check if it is a phone number//
                                else if(strpos($key, 'Phone') !== false){
                                  $phoneResult = formatPhone($value);
                                  if ($phoneResult === false){
                                      $dataErr[$key] = 'number';
                                    }else{
                                      $data[$key] = $phoneResult;
                                    }
                                }
                                //check if it is a postal code//
                                else if(strpos($key, 'Postal') !== false){
                                  $postalResult = formatPostal($value);
                                  if ($postalResult === false){
                                      $dataErr[$key] = 'postal';
                                    }else{
                                      $data[$key] = $postalResult;
                                    }
                                }
                              }
                            }
                            //if error array empty send data to database//
                            if (empty($dataErr)){
                              $id = $user['id'];
                              $query = "UPDATE users SET first='".$data['First Name']."', last='".$data['Last Name']."', phone='".$data['Home Phone']."', ".
                              " mobile='".$data['Mobile Phone']."', work='".$data['Work Phone']."', street='".$data['Address Line One']."', street2='".$data['Address Line Two']."', city='".$data['City']."', ".
                              "province='".$data['Province']."', postal='".$data['Postal Code']."', ename='".$data['Emergency Contact Name']."', ephone='".$data['Emergency Contact Phone']."' WHERE id = '$id'";
                              $result=mysqli_query($link, $query);
                              if(mysqli_affected_rows($link) >= 0){
                                $user = loadUserData($id, $link);
                                echo "<div class='alert alert-success'>Your information was successfully updated.</div>";
                              }else{
                                echo "<div class='alert alert-danger'>There was problem updating your information. Please try again. ERROR 06</div>";
                              }
                            }else{
                              echo '<div class="alert alert-danger">';
                              //load error messages if any//
                              foreach ($dataErr as $key => $value) {
                                if($value === 'empty' ) {
                                  echo $key." cannot be empty. ";
                                }else if($value === 'letter') {
                                  echo $key." should only have letters. ";
                                }else if($value === 'number') {
                                  echo $key." is not a phone number. ";
                                }else if($value === 'postal') {
                                  echo $key." is not a valid postal code. ";
                                }
                              }
                              echo "</div>";
                            }
                          }
                          ?>
                          <form role="form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>?page=profile#my-information">
                            <div class="form-group col-lg-6">
                                <label>First Name *:</label>
                                <input name="profile[First Name]" type="text" class="form-control" placeholder="John" value="<?php echo $user['first']; ?>">
                            </div>
                            <div class="form-group col-lg-6">
                                <label>Last Name *:</label>
                                <input name="profile[Last Name]" type="text" class="form-control" placeholder="Doe"value="<?php echo $user['last']; ?>">
                            </div>
                            <div class="form-group col-lg-6">
                                <label>Home Phone *:</label>
                                <input name="profile[Home Phone]" type="tel" class="form-control" placeholder="(705) 888-8888" value="<?php echo $user['phone']; ?>">
                            </div>
                            <div class="form-group col-lg-6">
                                <label>Mobile Phone:</label>
                                <input name="profile[Mobile Phone]" type="tel" class="form-control" placeholder="(705) 888-8888" value="<?php echo $user['mobile']; ?>">
                            </div>
                            <div class="form-group col-lg-6">
                                <label>Work Phone:</label>
                                <input name="profile[Work Phone]" type="tel" class="form-control" placeholder="(705) 888-8888" value="<?php echo $user['work']; ?>">
                            </div>
                            <div class="form-group col-lg-6">
                                <label>Address Line 1 *:</label>
                                <input name="profile[Address Line One]" type="text" class="form-control" placeholder="29 Somewhere st." value="<?php echo $user['street']; ?>">
                            </div>
                            <div class="form-group col-lg-6">
                                <label>Address Line 2:</label>
                                <input name="profile[Address Line Two]" type="text" class="form-control" placeholder="" value="<?php echo $user['street2']; ?>">
                            </div>
                            <div class="form-group col-lg-6">
                                <label>City *:</label>
                                <input name="profile[City]" type="text" class="form-control" placeholder="Sudbury" value="<?php echo $user['city']; ?>">
                            </div>
                            <div class="form-group col-lg-6">
                                <label>Province *:</label>
                                <select name="profile[Province]" class="form-control">
                                  <option value="AB" <?php echo ( $user['province'] == 'AB' ? ' selected="selected"' : ''); ?>>Alberta</option>
                                  <option value="BC" <?php echo ( $user['province'] == 'BC' ? ' selected="selected"' : ''); ?>>British Columbia</option>
                                  <option value="MB" <?php echo ( $user['province'] == 'MB' ? ' selected="selected"' : ''); ?>>Manitoba</option>
                                  <option value="NB" <?php echo ( $user['province'] == 'NB' ? ' selected="selected"' : ''); ?>>New Brunswick</option>
                                  <option value="NL" <?php echo ( $user['province'] == 'NL' ? ' selected="selected"' : ''); ?>>Newfoundland and Labrador</option>
                                  <option value="NS" <?php echo ( $user['province'] == 'NS' ? ' selected="selected"' : ''); ?>>Nova Scotia</option>
                                  <option value="ON" <?php echo ( $user['province'] == 'ON' ? ' selected="selected"' : ''); ?>>Ontario</option>
                                  <option value="PE" <?php echo ( $user['province'] == 'PE' ? ' selected="selected"' : ''); ?>>Prince Edward Island</option>
                                  <option value="QC" <?php echo ( $user['province'] == 'QC' ? ' selected="selected"' : ''); ?>>Quebec</option>
                                  <option value="SK" <?php echo ( $user['province'] == 'SK' ? ' selected="selected"' : ''); ?>>Saskatchewan</option>
                                  <option value="NT" <?php echo ( $user['province'] == 'NT' ? ' selected="selected"' : ''); ?>>Northwest Territories</option>
                                  <option value="NU" <?php echo ( $user['province'] == 'NU' ? ' selected="selected"' : ''); ?>>Nunavut</option>
                                  <option value="YT" <?php echo ( $user['province'] == 'YT' ? ' selected="selected"' : ''); ?>>Yukon</option>
                                </select>
                            </div>
                            <div class="form-group col-lg-6">
                                <label>Postal Code *:</label>
                                <input name="profile[Postal Code]" type="text" class="form-control" placeholder="H1H 1H1" value="<?php echo $user['postal']; ?>">
                            </div>
                            <div class="form-group col-lg-6">
                                <label>Emergency Contact Name *:</label>
                                <input name="profile[Emergency Contact Name]" type="text" class="form-control" placeholder="John Smith" value="<?php echo $user['ename']; ?>">
                            </div>
                            <div class="form-group col-lg-6">
                                <label>Emergency Contact Phone *:</label>
                                <input name="profile[Emergency Contact Phone]" type="tel" class="form-control" placeholder="(705) 888-8888" value="<?php echo $user['ephone']; ?>">
                            </div>
                            <button type="submit" class="btn btn-primary">Save</button>
                          </form>
                        </div>
                      </div>
                  </div>
              </div>
              <div class="panel panel-default">
                  <div id="password" class="panel-heading">
                      Change My Password
                  </div>
                  <div class="panel-body">
                      <div class="row">
                        <div class="col-lg-12">
                          <?php
                          if (!empty($_POST['password'])){
                            $passwordResult = passwordChange($_POST['password'], $link, $user['email']);
                            echo $passwordResult;
                          }
                          ?>
                          <form role="form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>?page=profile#password">
                            <div class="form-group col-lg-6">
                                <label>New Password *:</label>
                                <input name="password[New Password]" class="form-control" type="password" placeholder="******">
                            </div>
                            <div class="form-group col-lg-6">
                                <label>New Password Retype *:</label>
                                <input name="password[New Password Retype]" class="form-control" type="password" placeholder="******">
                            </div>
                            <button type="submit" class="btn btn-primary">Change</button>
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
