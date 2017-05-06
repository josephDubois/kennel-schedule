<?php

session_start();
include("databaseKeys/".$_SESSION['dataBaseKey'].".php");
//determine if the user is legally logged in//
if (empty($_SESSION['user'])) {
    $_SESSION['error'] = 'Please Login.';
    header("Location: login.php");
    exit;
}
$id = mysqli_real_escape_string($link,$_POST['id1']);
$count = 1;
$query = "SELECT * FROM dogs WHERE id='$id' AND deleted='no'";
$result=mysqli_query($link, $query);
if (mysqli_num_rows($result) > 0) {
    while($row = mysqli_fetch_array($result, MYSQL_ASSOC)) {
       $dogid = $row['dogid'];
       $name = $row['name'];
       $breed = $row['breed'];
       $color = $row['color'];
       $age = $row['age'];
       $age = (int)$age;
       $gender = $row['gender'];
       $fixed = $row['fixed'];
       $brand = $row['brand'];
       $amount = $row['amount'];
       $often = $row['often'];
       $vname = $row['vet'];
       $vdate = $row['vdate'];
       $vphone = $row['vphone'];
       $message = $row['special'];

       echo "<div class='dogContainer'>".$name."<button id='".$dogid."' class='floatRight editDogButton'><i class='fa fa-pencil' aria-hidden='true'></i> Edit</button>".
       "<form class='editDog edit".$dogid."'>".
                "<input type='hidden' id='dogId".$count."' value='".$dogid."'/>".
                "<label>Name:</label>".
                "<input id='addName".$count."' placeholder='Your Dog's Name' maxlength='20' value ='".$name."'/>".
                "<label >Breed:</label>".
                "<input type='text' id='addBreed".$count."' value='".$breed."'/>".
                "<label >Colour:</label>".
                "<input id='addColor".$count."' placeholder='e.g. black, white, grey, brown' maxlength='20' value='".$color."'/>".
                "<label >Current Age:</label>".
                "<select id='addAge".$count."'>".
                    "<option value='0' ";if ($age === 0)  echo "selected = 'selected'"; echo ">Less then 1 Month</option>".
                    "<option value='".(1 / 12); if ($age === (1 / 12))  echo "selected = 'selected'"; echo "'>1 Month</option>".
                    "<option value='".(2 / 12 ); if ($age === (2 / 12))  echo "selected = 'selected'"; echo "'>2 Months</option>".
                    "<option value='".(3 / 12 ); if ($age === (3 / 12))  echo "selected = 'selected'"; echo "'>3 Months</option>".
                    "<option value='".(4 / 12 ); if ($age === (4 / 12))  echo "selected = 'selected'"; echo "'>4 Months</option>".
                    "<option value='".(5 / 12 ); if ($age === (5 / 12))  echo "selected = 'selected'"; echo "'>5 Months</option>".
                    "<option value='".(6 / 12 ); if ($age === (6 / 12))  echo "selected = 'selected'"; echo "'>6 Months</option>".
                    "<option value='".(7 / 12 ); if ($age === (7 / 12))  echo "selected = 'selected'"; echo "'>7 Months</option>".
                    "<option value='".(8 / 12 ); if ($age === (8 / 12))  echo "selected = 'selected'"; echo "'>8 Months</option>".
                    "<option value='".(9 / 12 ); if ($age === (9 / 12))  echo "selected = 'selected'"; echo "'>9 Months</option>".
                    "<option value='".(10 / 12 ); if ($age === (10 / 12))  echo "selected = 'selected'"; echo "'>10 Months</option>".
                    "<option value='".(11 / 12 ); if ($age === (11 / 12))  echo "selected = 'selected'"; echo "'>11 Months</option>".
                    "<option value='1' ";if ($age === 1)  echo "selected = 'selected'"; echo ">1 Year</option>".
                    "<option value='2' ";if ($age === 2)  echo "selected = 'selected'"; echo ">2 Years</option>".
                    "<option value='3' ";if ($age === 3)  echo "selected = 'selected'"; echo ">3 Years</option>".
                    "<option value='4' ";if ($age === 4)  echo "selected = 'selected'"; echo ">4 Years</option>".
                    "<option value='5' "; if ($age === 5) echo "selected = 'selected'"; echo ">5 Years</option>".
                    "<option value='6' ";if ($age === 6)  echo "selected = 'selected'"; echo ">6 Years</option>".
                    "<option value='7' ";if ($age === 7)  echo "selected = 'selected'"; echo ">7 Years</option>".
                    "<option value='8' ";if ($age === 8)  echo "selected = 'selected'"; echo ">8 Years</option>".
                    "<option value='9' ";if ($age === 9)  echo "selected = 'selected'"; echo ">9 Years</option>".
                    "<option value='10' ";if ($age === 10)  echo "selected = 'selected'"; echo ">10 Years</option>".
                    "<option value='11' ";if ($age === 11)  echo "selected = 'selected'"; echo ">11 Years</option>".
                    "<option value='12' ";if ($age === 12)  echo "selected = 'selected'"; echo ">12 Years</option>".
                    "<option value='13' ";if ($age === 13)  echo "selected = 'selected'"; echo ">13 Years</option>".
                    "<option value='14' ";if ($age === 14)  echo "selected = 'selected'"; echo ">14 Years</option>".
                    "<option value='15' ";if ($age === 15)  echo "selected = 'selected'"; echo ">15 Years</option>".
                    "<option value='16' ";if ($age === 16)  echo "selected = 'selected'"; echo ">16 Years</option>".
                    "<option value='17' ";if ($age === 17)  echo "selected = 'selected'"; echo ">17 Years</option>".
                    "<option value='18' ";if ($age === 18)  echo "selected = 'selected'"; echo ">18 Years</option>".
                    "<option value='19' ";if ($age === 19)  echo "selected = 'selected'"; echo ">19 Years</option>".
                    "<option value='20' ";if ($age === 20)  echo "selected = 'selected'"; echo ">20 Years</option>".
                    "<option value='21' ";if ($age === 21)  echo "selected = 'selected'"; echo ">More Then 20 Years</option>".
                "</select>".
                "<label>Gender:</label>".
                "<select id='addGender".$count."'>".
                    "<option value='Male' ";if ($gender === 'Male')  echo "selected = 'selected'"; echo ">Male</option>".
                    "<option value='Female' ";if ($gender === 'Female')  echo "selected = 'selected'"; echo ">Female</option>".
               "</select><br/>".
                "<label >Spayed/neutered?:</label>".
                "<select id='addFixed".$count."'><br/>".
                    "<option value='Yes' ";if ($fixed === 'Yes')  echo "selected = 'selected'"; echo ">Yes</option>".
                    "<option value='No' ";if ($fixed === 'No')  echo "selected = 'selected'"; echo ">No</option>".
                "</select><br/>".
                "<label>Current Weight:</label>".
                "<select id='addWeigh".$count."t'><br/>".
                    "<option ";if ($weight === 'More Then 35LBS (16KG)')  echo "selected = 'selected'"; echo ">More Then 35LBS (16KG)</option>".
                    "<option ";if ($weight === 'Less Then 35LBS (16KG)')  echo "selected = 'selected'"; echo ">Less Then 35LBS (16KG)</option>".
                "</select>".
                "<label >Vaccination Expiry Date:</label>".
                "<input id='addVdate".$count."' placeholder='yyyy-mm-dd'  type='date' value='".$vdate."'/>".
                "<label >Vet's Phone Number:</label>".
                "<input id='addVphone".$count."' type='text' placeholder='e.g. 17058887777' maxlength='20' value='".$vphone."'/><br/>".
                "<label>Vet's Name:</label>".
                "<input id='addVname".$count."' type='text' placeholder='Vet Name' maxlength='20' value='".$vname."'/>".
                "<label >Brand of Food:</label>".
                "<input id='addBrand".$count."' type='text' placeholder='Brand' maxlength='20' value='".$brand."'/>".
                "<label >Feeding Frequency:</label>".
                "<input id='addOften".$count."' type='text' placeholder='How Often?' maxlength='20' value='".$often."'/><br/>".
                "<label class='grey'>Amount of Food:</label>".
                "<input id='addAmount".$count."' type='text' placeholder='Amount' maxlength='20' value='".$amount."'/>".
                "<label>Special Instructions:</label>".
                "<textarea id='addMessage".$count."' placeholder='Type any extra information you would like to leave with us here....'>".$message."</textarea>".
                "<hr>".
                "<p id='addDogMessage".$count."' class='redText'></p>".
                "<button id='cancel".$dogid."' class='floatLeft cancelEditDog'>Cancel</button>"."<button onclick='deleteDog(".$dogid.",".$id.")' class='floatLeft deleteDog profileButton4'>Delete</button>".
                "<button id='update".$count."' class='profileButton3 floatRight updateDogButton'>Save Dog</button>".
                "<div class='clear'></div>".
            "</form>".
       "</div>";
        $count++;
    }
    echo "<input type='hidden' id='userId' value='".$id."'/>".
    "<script>".
     "$('.editDogButton').click(function() {".
          "var dogid = $(this).attr('id');".
          "editDog(dogid);".
      "});".
      "$('.cancelEditDog').click(function() {".
         "event.preventDefault();".
          "showEditDog();".
      "});".
      "$('.updateDogButton').click(function() {".
         "event.preventDefault();".
         "var id = $('#userId').val();".
         "var number = $(this).attr('id');".
         "number = number.replace('update', '');".
         "updateDog(id, number);".
     "});".
     "$('.deleteDog').click(function() {".
         "event.preventDefault();".
     "});".
    "</script>";
    if(isset($_SESSION['dogid'])) {
               echo "<script>".
                        "loadEditDog(".$_SESSION['dogid'].");".
                "</script>";
                unset($_SESSION['dogid']);
    }

}else{
    echo "<h3>This client has no dogs.</h3>";
}

?>
