<?php
session_start();
include("databaseKeys/".$_SESSION['dataBaseKey'].".php");
include("functions.php");
//determine if the user is legally logged in//
if (empty($_SESSION['user'])) {
    $_SESSION['error'] = 'Please Login.';
    header("Location: login.php");
    exit;
}
unset($_SESSION['split']);
unset($_SESSION['calendarId']);
unset($_SESSION['bookingId']);
if(!empty($_GET['bookingid'])) {
  $_SESSION['bookingId'] = $_GET['bookingid'];
}
include('header.php');
/*Load Needed Settings*/
$query = "SELECT * FROM prices ORDER BY id DESC LIMIT 1";
$result=mysqli_query($link, $query);
$row = mysqli_fetch_array($result, MYSQL_ASSOC);
$priceRowId = $row['id'];
$dayCarePrice = $row['daycare'];
$dayCareAdditionalPrice = $row['daycareadditional'];
$weekendPrice = $row['weekend'];
$weekendActive = $row['weekendactive'];
$holidayPrice = $row['holidayprice'];
$holidayActive = $row['holidayactive'];
$overNightPrice = $row['overnight'];
$overNightAdditionalPrice = $row['overNightAdditional'];
$tax = $row['tax'];

if (isset($_GET['calendarSelectedDates'])) {
    $date  = $_GET['calendarSelectedDates'];
    $view = $_GET['view'];
    $selectedDate= $date;
    $date = explode("/", $date);
    $day = $date[1];
    $month = $date[0];
    $year = $date[2];
}else{
    $selectedDate=date("m/d/Y");
}
/*Display Today's Date*/
?>
<div id='indexToday'>
    <?php
    echo date("l").", ".date("d M Y");
    ?>
</div>
<!--Make Settings Available to JS-->
<input id="priceRowId" type="hidden" value="<?php echo $priceRowId ?>"/>
<input id="dayCarePrice" type="hidden" value="<?php echo $dayCarePrice ?>"/>
<input id="dayCareAdditionalPrice" type="hidden" value="<?php echo $dayCareAdditionalPrice ?>"/>
<input id="weekendPrice" type="hidden" value="<?php echo $weekendPrice ?>"/>
<input id="weekendActive" type="hidden" value="<?php echo $weekendActive ?>"/>
<input id="holidayPrice" type="hidden" value="<?php echo $holidayPrice ?>"/>
<input id="holidayActive" type="hidden" value="<?php echo $holidayActive ?>"/>
<input id="overNightPrice" type="hidden" value="<?php echo $overNightPrice ?>"/>
<input id="overNightAdditionalPrice" type="hidden" value="<?php echo $overNightAdditionalPrice ?>"/>
<input id="taxPrice" type="hidden" value="<?php echo $tax ?>"/>
<?php if(!empty($_GET['clientid'])) {
  $clientId = $_GET['clientid'];
?>
<input id="clientIdLoad" type="hidden" value="<?php echo $clientId; ?>"/>
<?php
  }
?>
<input id="whichContainer" type="hidden" value="bookingDate"/>
<!--END-->
<div id="homeContainer">
      <button class="toggle-button"><i class="fa fa-angle-double-left" aria-hidden="true"></i></button>
    <div id='bookingColumnLeft' class='floatLeft'>
      <?php
          if (!empty($_SESSION['oneTime'])) {
       ?>

          <div class="bookingContainer"><h2>It's Saved!</h2>Your booking was successfully saved. An email was sent to the client if there is one on file.</div>

       <?php
              unset($_SESSION['oneTime']);
            }
            if (!empty($_SESSION['updateTime'])) {
        ?>
        <div class="bookingContainer"><h2>Update Successful!</h2></div>
        <?php
              unset($_SESSION['updateTime']);
            }
            if (!empty($_GET['bookingid'])) {
        ?>
              <div id="bookingDetailContainer" class="bookingContainer">
                <input id="editBookingId" type="hidden" value="<?php echo $_GET['bookingid']?>"/>
                <h2 id="bookingDetailCustomerName" class="floatLeft bookingSummaryDetails"></h2><h2 id="bookingDetailDates" class="floatRight textRight bookingSummaryDetails"></h2>
                <div class="clear"></div>
                <hr/>
                <span id="bookingDetailDogButtons"></span><div id="bookingDetailDateBooked" class="floatRight">Date of Booking: 2016-10-18</div>
                <div class="clear"></div>
              </div>
              <button id="bookingShowDetailButton" class="profileButton3 bookingContainer">Show Booking Details</button>
              <div id="bookingShowDetailContainer">
        <?php
            }
        ?>
        <div id='bookingDate' class="bookingContainer">
            <h3 class="blueText floatLeft"><i class="fa fa-calendar" aria-hidden="true"></i> Create a Booking</h3>
            <button id='bookingDateAccordianButton' onclick="switchAccordian($(this).attr('id'));" class="floatRight accordianArrow"><i class="fa fa-angle-down fa-2x fa-rotate-180" aria-hidden="true"></i></button>
            <div class="clear"></div>
            <div id='bookingDatePanel' class="panel show">
              <hr>
              <script>
                holidaysCalculate();
                /*Variable To Specify What Typ of Booking*/
                var typeOfBooking = 'Day Care';
              </script>
              <form id='bookingDates' method="post">
                <div class="bookingColumn">
                    <label for='service'>Choose a Service:</label><br/>
                    <select onchange="loadService();" id='bookingService' name='service'>
                        <option value="Day Care">Day Care</option>
                        <option value="Over Night">Over Night</option>
                        <!--<option value="Meet N'Greet">Meet N'Greet</option>-->
                    </select>
                </div>
                <div class="clear"></div>
                <div id='dayCareSelected'>
                  <div class="bookingColumn">
                    <label for='dayCareDate'>Date:</label><br/>
                    <input name='dayCareDate' id='dayCareDateSelect' value="<?php echo date("m/d/Y"); ?>" readonly="true"/>
                  </div>
                  <div class="clear"></div>
                  <button id="addDayDate" onclick="addDayCareDate();" class="profileButton3 squareButton"><i class="fa fa-plus" aria-hidden="true"></i> Add Date</button>
                  <input id='editSwitch' type="hidden" value='Off'/>
                  <input id='editId' type="hidden" value=''/>
                  <div id='dayCareDateError' class="redText"></div>
                </div>
                <div id='overNightSelected'>
                  <div class="bookingColumn">
                    <label for='checkin'>Check In:</label><br/>
                    <input readonly="true" name='checkin' id='nightCheckIn' onchange="$('#nightCheckOut').val(''); $('#bookKennelsBox').hide();" value="<?php echo date("m/d/Y"); ?>"/>
                  </div>
                  <div class="bookingColumn">
                    <label for='checkout'>Check Out:</label><br/>
                    <input readonly="true" onchange="loadService();" name='checkout' id='nightCheckOut' value=""/>
                  </div>
                  <div id='bookKennelsBox'>
                      <div id='legend'>
                          <div class="legendItem"><i class="fa fa-square blueText" aria-hidden="true"></i> Your Booking</div>
                          <div class="legendItem"><i class="fa fa-square redText" aria-hidden="true"></i> Booked</div>
                          <div class="legendItem"><i class="fa fa-square brownText" aria-hidden="true"></i> Available</div>
                      </div>
                      <table id='bookingKennels'>
                          <tr>
                              <td>Kennels</td>
                              <td id='bookKennelDates'></td>
                          </tr>
                          <?php
                              $query = "SELECT * FROM kennels";
                              $result=mysqli_query($link, $query);
                              $totalKennel = 0;
                              if (mysqli_num_rows($result) > 0) {
                                  while($row = mysqli_fetch_array($result, MYSQL_ASSOC)) {
                                      $id = $row['id'];
                                      $name = $row['name'];
                                      $type = $row['size'];
                                       echo "<tr id='".$id."KennelRow' class='kennelBookingRow'>".
                                              "<td id='".$id."kennel' class='bookKennelLeftColumn'><button onclick='buttonBook(this.id);' id='".$id."button' class='button2'>Book</button>".$name."<br/>(".$type.")</td>".
                                              "<td><div onclick='storeORremoveKennel(".$id.");' id='".$id."progress' class='progressbar'></div></td>".
                                          "</tr>";
                                          $totalKennel++;
                                  }
                              }else {
                                  echo "<tr>".
                                          "<td colspan='2'>There was a problem loading the kennels.</td>".
                                      "</tr>";
                              }
                          ?>
                      </table>
                      <div id="splitBookingBox">
                        <h2>Kennel Set</h2>
                      </div>
                      <button id="splitBookingButton" onclick="splitRePosition();"><i class="fa fa-plus" aria-hidden="true"></i> Split Booking</button>
                      <input id="totalKennel" type="hidden" value="<?php echo $totalKennel; ?>"/>
                      <div class="overlay"><i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i></div>
                  </div>
                  <div class="clear"></div>
                </div>
                <div id='meetGreetSelected'>
                  <div class="bookingColumn">
                    <label for='meetGreetDate'>Date:</label><br/>
                    <input name='meetGreetDate' id='meetGreetDateSelect' value="<?php echo date("m/d/Y"); ?>"/>
                  </div>
                  <div class="clear"></div>
                  <button id="addMeetDate" onclick="addMeetGreetDate();" class="profileButton3 squareButton"><i class="fa fa-plus" aria-hidden="true"></i> Add Date</button>
                  <input id='meetSwitch' type="hidden" value='Off'/>
                  <input id='meetEditId' type="hidden" value=''/>
                  <div id='meetGreetDateError' class="redText"></div>
                </div>
              </form>
              <hr>
              <button onclick='bookingNext($(this).attr("id"));' id='serviceNext' class="floatRight nextButton">Next</button>
              <div class="clear"></div>
            </div>
        </div>
        <div id='bookingCustomer' class="bookingContainer">
            <h3 class="blueText floatLeft"><i class="fa fa-users" aria-hidden="true"></i> Customer</h3>
            <button id='bookingCustomerAccordianButton' onclick="switchAccordian($(this).attr('id'));" class="floatRight accordianArrow"><i class="fa fa-angle-down fa-2x" aria-hidden="true"></i></button>
            <div class="clear"></div>
            <div id="bookingCustomerPanel" class="panel">
              <hr>
              <div>
                <script>
                  var customerType = 'New';
                  var customerId;
                </script>
                <form>
                  <div id="liveSearchBox">
                    <label>Find a Customer:</label><br/>
                    <input autocomplete="off" type="text" id="clientLiveSearch" placeholder="Search for an existing customer...">
                    <button onclick='liveSearchReset();' id='bookingClientReset'><i class="fa fa-repeat" aria-hidden="true"></i> Reset</button>
                    <div id='liveResult'></div>
                    <div class='clear'></div>
                  </div>
                    <label>First Name:</label><br/>
                    <input autocomplete="off" id="bookingClient0" type='text'/>
                    <label>Last Name:</label><br/>
                    <input autocomplete="off" id="bookingClient1" type='text'/>
                    <label>Email Address:</label><br/>
                    <input autocomplete="off" id="bookingClient2" type='text'/>
                    <label>Phone Number:</label><br/>
                    <input autocomplete="off" id="bookingClient3" type='text'/>
                    <label>Mobile Number:</label><br/>
                    <input autocomplete="off" id="bookingClient4" type='text'/>
                    <label>Work Number:</label><br/>
                    <input autocomplete="off" id="bookingClient5" type='text'/>
                    <label>Address Line 1:</label><br/>
                    <input autocomplete="off" id="bookingClient6" type='text'/>
                    <label>Address Line 2:</label><br/>
                    <input autocomplete="off" id="bookingClient7" type='text'/>
                    <label>City:</label><br/>
                    <input autocomplete="off" id="bookingClient8" type='text'/>
                    <label>Province:</label><br/>
                    <input autocomplete="off" id="bookingClient9" type='text'/>
                    <label>Postal Code:</label><br/>
                    <input autocomplete="off" id="bookingClient10" type='text'/>
                    <label>Emergency Name:</label><br/>
                    <input autocomplete="off" id="bookingClient11" type='text'/>
                    <label>Emergency Phone Number:</label><br/>
                    <input autocomplete="off" id="bookingClient12" type='text'/>
                    <label>Notes:</label><br/>
                    <input autocomplete="off" id="bookingClient13" type='text'/>
                </form>
              </div>
              <hr>
              <div id="customerMessage"></div>
              <button onclick='bookingClientNext($(this).attr("id"));' id='clientNext' class="floatRight nextButton">Next</button>
              <div class="clear"></div>
            </div>
        </div>
        <div id='bookingPet' class="bookingContainer">
            <h3 class="blueText floatLeft"><i class="fa fa-paw" aria-hidden="true"></i> Dogs</h3>
            <button id='bookingPetAccordianButton' onclick="switchAccordian($(this).attr('id'));" class="floatRight accordianArrow"><i class="fa fa-angle-down fa-2x" aria-hidden="true"></i></button>
            <div class="clear"></div>
            <div id='bookingPetPanel' class="panel">
              <hr>
              <div id='bookingLoadDogs'>

              </div>
              <form id='addDog'>
                  <h3>Add a new dog</h3>
                  <label>Name:</label>
                  <input id='addName' placeholder="Your Dog's Name" maxlength="20" />
                  <label >Breed:</label>
                  <select id='addBreed'>
                    <optgroup label="Mixed Breeds">
                      <option>Small Mix</option>
                      <option>Large Mix</option>
                      <option>Manitoulin Special</option>
                    </optgroup>
                    <optgroup label="Pure Bred">
                      <option>Abruzzenhund</option>
                      <option>Affenpinscher</option>
                      <option>Afghan Hound</option>
                      <option>Africanis</option>
                      <option>Aidi</option>
                      <option>Ainu Dog</option>
                      <option>Airedale Terrier</option>
                      <option>Akbash Dog</option>
                      <option>Akitas</option>
                      <option>Akita (American)</option>
                      <option>Akita Inu (Japanese)</option>
                      <option>Alano Espa&ntilde;ol</option>
                      <option>Alapaha Blue Blood Bulldog</option>
                      <option>Alaskan Husky</option>
                      <option>Alaskan Klee Kai</option>
                      <option>Alaskan Malamute</option>
                      <option>Alopekis</option>
                      <option>Alpine Dachsbracke</option>
                      <option>American Allaunt</option>
                      <option>American Alsatian</option>
                      <option>American Black and Tan Coonhound</option>
                      <option>American Blue Gascon Hound</option>
                      <option>American Blue Lacy</option>
                      <option>American Bull Molosser</option>
                      <option>American Bulldog</option>
                      <option>American Bullnese</option>
                      <option>American Bully</option>
                      <option>American Cocker Spaniel</option>
                      <option>American Crested Sand Terrier</option>
                      <option>American Eskimo Dog</option>
                      <option>American Foxhound</option>
                      <option>American Hairless Terrier</option>
                      <option>American Indian Dog</option>
                      <option>American Lo-Sze Pugg &trade;</option>
                      <option>American Mastiff</option>
                      <option>American Mastiff (Panja)</option>
                      <option>American Pit Bull Terrier</option>
                      <option>American Staffordshire Terrier</option>
                      <option>American Staghound</option>
                      <option>American Toy Terrier</option>
                      <option>American Tundra Shepherd Dog</option>
                      <option>American Water Spaniel</option>
                      <option>American White Shepherd</option>
                      <option>Anatolian Shepherd Dog</option>
                      <option>Andalusian Podenco</option>
                      <option>Anglos-Françaises</option>
                      <option>Anglos-Françai Grand</option>
                      <option>Anglos-Françaises de Moyenne Venerie</option>
                      <option>Anglos-Françaises de Petite Venerie</option>
                      <option>Appenzell Mountain Dog</option>
                      <option>Ariege Pointing Dog</option>
                      <option>Ariegeois</option>
                      <option>Armant</option>
                      <option>Aryan Molossus</option>
                      <option>Argentine Dogo</option>
                      <option>Armenian Gampr</option>
                      <option>Atlas Terrier</option>
                      <option>Australian Bandog</option>
                      <option>Australian Bulldog</option>
                      <option>Australian Cattle Dog</option>
                      <option>Australian Cobberdog</option>
                      <option>Australian Kelpie</option>
                      <option>Australian Koolie</option>
                      <option>Australian Labradoodle</option>
                      <option>Australian Shepherd</option>
                      <option>Australian Stumpy Tail Cattle Dog</option>
                      <option>Australian Terrier</option>
                      <option>Austrian Black and Tan Hound</option>
                      <option>Austrian Brandlbracke</option>
                      <option>Austrian Shorthaired Pinscher</option>
                      <option>Auvergne Pointing Dog</option>
                      <option>Azawakh</option>

                      <option>Balkan Hound</option>
                      <option>Balkan Scenthound</option>
                      <option>Balkanski Gonic</option>
                      <option>Banjara Greyhound</option>
                      <option>Banter Bulldogge</option>
                      <option>Barbet</option>
                      <option>Basenji</option>
                      <option>Basset Artesien Normand</option>
                      <option>Basset Bleu de Gascogne</option>
                      <option>Basset Fauve de Bretagne</option>
                      <option>Basset Hound</option>
                      <option>Bavarian Mountain Hound</option>
                      <option>Beagle</option>
                      <option>Beagle Harrier</option>
                      <option>Bearded Collie</option>
                      <option>Beauceron</option>
                      <option>Bedlington Terrier</option>
                      <option>Bedouin Shepherd Dog</option>
                      <option>Belgian Griffons</option>
                      <option>Belgian Mastiff</option>
                      <option>Belgian Shepherd Groenendael</option>
                      <option>Belgian Shepherd Laekenois</option>
                      <option>Belgian Shepherd Malinois</option>
                      <option>Belgian Shepherd Tervuren</option>
                      <option>Belgian Shorthaired Pointer</option>
                      <option>Belgrade Terrier </option>
                      <option>Bench-legged Feist</option>
                      <option>Bergamasco</option>
                      <option>Berger des Picard</option>
                      <option>Berger des Pyr&eacute;n&eacute;es</option>
                      <option>Berger Du Languedoc</option>
                      <option>Bernese Hound</option>
                      <option>Bernese Mountain Dog</option>
                      <option>Bhagyari Kutta</option>
                      <option>Bichon Frise</option>
                      <option>Bichon Havanais</option>
                      <option>Biewer</option>
                      <option>Billy</option>
                      <option>Black and Tan Coonhound</option>
                      <option>Black Forest Hound</option>
                      <option>Black Mouth Cur</option>
                      <option>Black Norwegian Elkhound</option>
                      <option>Black Russian Terrier</option>
                      <option>Bleus de Gascogne</option>
                      <option>Bloodhound</option>
                      <option>Blue Gascony Basset</option>
                      <option>Blue Heeler</option>
                      <option>Blue Lacy</option>
                      <option>Blue Picardy Spaniel</option>
                      <option>Bluetick Coonhound</option>
                      <option>Boerboel</option>
                      <option>Bohemian Shepherd</option>
                      <option>Bohemian Terrier</option>
                      <option>Bolognese</option>
                      <option>Bonsai Bulldogge</option>
                      <option>Border Collie</option>
                      <option>Border Terrier</option>
                      <option>Borzoi</option>
                      <option>Bosanski Ostrodlaki Gonic Barak</option>
                      <option>Bosnian-Herzegovinian Sheepdog - Tornjak</option>
                      <option>Boston Terrier</option>
                      <option>Bouvier de Ardennes</option>
                      <option>Bouvier des Flandres</option>
                      <option>Boxer</option>
                      <option>Boykin Spaniel</option>
                      <option>Bracco Italiano</option>
                      <option>Braque du Bourbonnais</option>
                      <option>Braque Dupuy</option>
                      <option>Braque Francais</option>
                      <option>Brazilian Terrier</option>
                      <option>Briard</option>
                      <option>Brittany Spaniel</option>
                      <option>Briquet</option>
                      <option>Briquet Griffon Vendeen </option>
                      <option>Broholmer</option>
                      <option>Brussels Griffon</option>
                      <option>Bukovina Sheepdog</option>
                      <option>Buldogue Campeiro</option>
                      <option>Bull Terrier</option>
                      <option>Bully Kutta</option>
                      <option>Bulldog</option>
                      <option>Bullmastiff</option>

                      <option>Cairn Terrier</option>
                      <option>Cajun Squirrel Dog</option>
                      <option>Cambodian Razorback Dog</option>
                      <option>Canaan Dog</option>
                      <option>Canadian Eskimo Dog</option>
                      <option>Canadian Inuit Dog</option>
                      <option>Canary Dog</option>
                      <option>Canarian Warren Hound</option>
                      <option>Cane Corso Italiano</option>
                      <option>Canis Panther</option>
                      <option>Canoe Dog </option>
                      <option>C&atilde;o da Serra da Estrela</option>
                      <option>C&atilde;o da Serra de Aires</option>
                      <option>C&atilde;o de Castro Laboreiro</option>
                      <option>C&atilde;o de Fila de São Miguel</option>
                      <option>Caravan Hound</option>
                      <option>Carlin Pinscher</option>
                      <option>Carolina Dog</option>
                      <option>Carpathian Sheepdog</option>
                      <option>Catahoula Leopard Dog</option>
                      <option>Catalan Sheepdog</option>
                      <option>Cardigan Welsh Corgi</option>
                      <option>Caucasian Ovtcharka</option>
                      <option>Cavalier King Charles Spaniel</option>
                      <option>Central Asian Ovtcharka</option>
                      <option>Cesky Fousek</option>
                      <option>Cesky Terrier</option>
                      <option>Chart Polski</option>
                      <option>Chesapeake Bay Retriever</option>
                      <option>Chien D'Artois</option>
                      <option>Chien De L' Atlas</option>
                      <option>Chien Française</option>
                      <option>Chihuahua</option>
                      <option>Chin</option>
                      <option>Chinese Chongqing Dog</option>
                      <option>Chinese Crested</option>
                      <option>Chinese Foo Dog</option>
                      <option>Chinese Imperial Dog</option>
                      <option>Chinese Shar-Pei</option>
                      <option>Chinook</option>
                      <option>Chippiparai</option>
                      <option>Chiribaya Shepherd</option>
                      <option>Chortaj </option>
                      <option>Chow Chow</option>
                      <option>Cierny Sery</option>
                      <option>Cirneco Dell'Etna</option>
                      <option>Clumber Spaniel</option>
                      <option>Cocker Spaniel</option>
                      <option>Collie (Rough and Smooth)</option>
                      <option>Combai</option>
                      <option>Continental Bulldog</option>
                      <option>Continental Toy Spaniel</option>
                      <option>Coochi</option>
                      <option>Corgi</option>
                      <option>Coton de Tulear</option>
                      <option>Cretan Hound</option>
                      <option>Croatian Sheepdog</option>
                      <option>Curly-Coated Retriever</option>
                      <option>Cypro Kukur</option>
                      <option>Czechoslovakian Wolfdog</option>
                      <option>Czesky Terrier</option>

                      <option>Dachshund</option>
                      <option>Dakotah Shepherd</option>
                      <option>Dalmatian</option>
                      <option>Dandie Dinmont Terrier</option>
                      <option>Danish Broholmer</option>
                      <option>Danish-Swedish Farmdog</option>
                      <option>Denmark Feist</option>
                      <option>Deutsche Bracke</option>
                      <option>Deutsch Drahthaar </option>
                      <option>Deutscher Wachtelhund</option>
                      <option>Dingo</option>
                      <option>Doberman Pinscher</option>
                      <option>Dogo Argentino</option>
                      <option>Dogue de Bordeaux</option>
                      <option>Dorset Olde Tyme Bulldogge</option>
                      <option>Drentse Patrijshond</option>
                      <option>Drever</option>
                      <option>Dunker</option>
                      <option>Dutch Shepherd Dog</option>
                      <option>Dutch Smoushond</option>

                      <option>East-European Shepherd</option>
                      <option>East Russian Coursing Hound</option>
                      <option>East Siberian Laika</option>
                      <option>Elkhound</option>
                      <option>English Bulldog</option>
                      <option>English Bullen Bordeaux Terrier</option>
                      <option>English Cocker Spaniel</option>
                      <option>English Coonhound</option>
                      <option>English Foxhound</option>
                      <option>English Pointer</option>
                      <option>English Setter</option>
                      <option>English Shepherd</option>
                      <option>English Springer Spaniel</option>
                      <option>English Toy Spaniel</option>
                      <option>Entlebucher Sennenhund</option>
                      <option>Estonian Hound</option>
                      <option>Estrela Mountain Dog</option>
                      <option>Eurasier</option>

                      <option>Farm Collie</option>
                      <option>Fauve de Bretagne</option>
                      <option>Feist</option>
                      <option>Field Spaniel</option>
                      <option>Fila Brasileiro</option>
                      <option>Finnish Hound</option>
                      <option>Finnish Lapphund</option>
                      <option>Finnish Spitz</option>
                      <option>Flat-Coated Retriever</option>
                      <option>Foxhound</option>
                      <option>Fox Terrier</option>
                      <option>French Brittany Spaniel</option>
                      <option>French Bulldog</option>
                      <option>French Mastiff</option>
                      <option>French Pointing Dog</option>
                      <option>French Spaniel</option>
                      <option>French Tricolor Hound</option>
                      <option>French White and Black Hound</option>
                      <option>French White and Orange Hound</option>

                      <option>Galgo Español</option>
                      <option>Gammel Dansk Hoensehund</option>
                      <option>Gascons-Saintongeois</option>
                      <option>Georgian Shepherd</option>
                      <option>Georgian Mountain Dog</option>
                      <option>German Hunt Terrier</option>
                      <option>German Longhaired Pointer</option>
                      <option>German Rough-haired Pointing Dog</option>
                      <option>German Pinscher</option>
                      <option>German Sheeppoodle</option>
                      <option>German Shepherd Dog</option>
                      <option>German Shorthaired Pointer</option>
                      <option>German Spitz</option>
                      <option>German Spitz Giant</option>
                      <option>German Spitz Medium</option>
                      <option>German Spitz Small</option>
                      <option>German Wirehaired Pointer</option>
                      <option>Giant Maso Mastiff</option>
                      <option>Giant Schnauzer</option>
                      <option>Glen of Imaal Terrier</option>
                      <option>Golddust Yorkshire Terrier</option>
                      <option>Golden Retriever</option>
                      <option>Gordon Setter</option>
                      <option>Gran Mastin de Borinquen </option>
                      <option>Grand Anglo-Français</option>
                      <option>Grand Anglo-Français Tricolore</option>
                      <option>Grand Anglo-Français Blanc et Noir</option>
                      <option>Grand Anglo-Français Blanc et Orange</option>
                      <option>Grand Basset Griffon Vendeen</option>
                      <option>Grand Bleu de Gascogne</option>
                      <option>Grand Gascon Saintongeois</option>
                      <option>Grand Griffon Vendeen </option>
                      <option>Great Dane</option>
                      <option>Great Pyrenees</option>
                      <option>Greater Swiss Mountain Dog</option>
                      <option>Greek Hound</option>
                      <option>Greek Sheepdog</option>
                      <option>Greenland Dog</option>
                      <option>Greyhound</option>
                      <option>Griffon Bleu de Gascogne</option>
                      <option>Griffon Fauve de Bretagne</option>
                      <option>Griffon Nivernais</option>
                      <option>Groenendael</option>
                      <option>Grosser M&uuml;nsterlander Vorstehhund</option>
                      <option>Guatemalan Bull Terrier</option>

                      <option>Hairless Khala</option>
                      <option>Halden Hound</option>
                      <option>Hamilton Hound</option>
                      <option>Hanoverian Hound</option>
                      <option>Harlequin Pinscher</option>
                      <option>Harrier</option>
                      <option>Havanese</option>
                      <option>Hawaiian Poi Dog </option>
                      <option>Hellenikos Ichnilatis</option>
                      <option>Hellenikos Poimenikos</option>
                      <option>Hertha Pointer</option>
                      <option>Himalayan Sheepdog</option>
                      <option>Hokkaido Dog</option>
                      <option>Hanoverian Scenthound</option>
                      <option>Hovawart</option>
                      <option>Hungarian Greyhound</option>
                      <option>Hungarian Kuvasz</option>
                      <option>Hungarian Puli</option>
                      <option>Hungarian Wire-haired Pointing Dog</option>
                      <option>Husky</option>
                      <option>Hygenhund</option>

                      <option>Ibizan Hound</option>
                      <option>Icelandic Sheepdog</option>
                      <option>Inca Hairless Dog</option>
                      <option>Irish Glen Imaal Terrier</option>
                      <option>Irish Red and White Setter</option>
                      <option>Irish Setter</option>
                      <option>Irish Staffordshire Bull Terrier</option>
                      <option>Irish Terrier</option>
                      <option>Irish Water Spaniel</option>
                      <option>Irish Wolfhound</option>
                      <option>Istrian Coarse-haired Hound</option>
                      <option>Istrian Shorthaired Hound</option>
                      <option>Italian Greyhound</option>
                      <option>Italian Hound</option>
                      <option>Italian Spinoni</option>

                      <option>Jack Russell Terrier</option>
                      <option>Jamthund</option>
                      <option>Japanese Spaniel (Chin)</option>
                      <option>Japanese Spitz</option>
                      <option>Japanese Terrier</option>
                      <option>Jindo</option>

                      <option>Kai Dog</option>
                      <option>Kangal Dog</option>
                      <option>Kangaroo Dog</option>
                      <option>Kanni</option>
                      <option>Karabash</option>
                      <option>Karakachan</option>
                      <option>Karelian Bear Dog</option>
                      <option>Karelian Bear Laika</option>
                      <option>Karelo-Finnish Laika</option>
                      <option>Karst Shepherd</option>
                      <option>Keeshond</option>
                      <option>Kelb Tal-Fenek</option>
                      <option>Kemmer Feist</option>
                      <option>Kerry Beagle</option>
                      <option>Kerry Blue Terrier</option>
                      <option>King Charles Spaniel</option>
                      <option>King Shepherd</option>
                      <option>Kishu Ken</option>
                      <option>Klein Poodle</option>
                      <option>Kokoni</option>
                      <option>Komondor</option>
                      <option>Koochee</option>
                      <option>Kooikerhondje</option>
                      <option>Koolie</option>
                      <option>Korean Dosa Mastiff</option>
                      <option>Krasky Ovcar</option>
                      <option>Kromfohrlander</option>
                      <option>Kuchi</option>
                      <option>Kugsha Dog</option>
                      <option>Kunming Dog</option>
                      <option>Kuvasz</option>
                      <option>Kyi-Leo</option>

                      <option>Labrador Husky</option>
                      <option>Labrador Retriever</option>
                      <option>Lagotto Romagnolo</option>
                      <option>Lakeland Terrier</option>
                      <option>Lakota Mastino</option>
                      <option>Lancashire Heeler</option>
                      <option>Landseer</option>
                      <option>Lapinporokoira</option>
                      <option>Lapphunds</option>
                      <option>Large Münsterländer</option>
                      <option>Larson Lakeview Bulldogge </option>
                      <option>Latvian Hound</option>
                      <option>Leavitt Bulldog</option>
                      <option>Leonberger</option>
                      <option>Levesque</option>
                      <option>Lhasa Apso</option>
                      <option>Lithuanian Hound</option>
                      <option>Llewellin Setter</option>
                      <option>Longhaired Whippet</option>
                      <option>Louisiana Catahoula Leopard Dog</option>
                      <option>Löwchen (Little Lion Dog)</option>
                      <option>Lucas Terrier</option>
                      <option>Lundehund</option>

                      <option>Magyar Agar</option>
                      <option>Mahratta Greyhound</option>
                      <option>Majestic Tree Hound </option>
                      <option>Majorca Shepherd Dog</option>
                      <option>Maltese</option>
                      <option>Mammut Bulldog</option>
                      <option>Manchester Terrier</option>
                      <option>Maremma Sheepdog</option>
                      <option>Markiesje</option>
                      <option>Mastiff</option>
                      <option>McNab</option>
                      <option>Mexican Hairless</option>
                      <option>Mi-Ki</option>
                      <option>Middle Asian Ovtcharka</option>
                      <option>Miniature American Eskimo</option>
                      <option>Miniature Australian Bulldog</option>
                      <option>Miniature Australian Shepherd</option>
                      <option>Miniature Bull Terrier</option>
                      <option>Miniature Fox Terrier</option>
                      <option>Miniature Pinscher</option>
                      <option>Miniature Poodle</option>
                      <option>Miniature Schnauzer</option>
                      <option>Miniature Shar-Pei</option>
                      <option>Mioritic Sheepdog</option>
                      <option>Moscow Toy Terrier</option>
                      <option>Moscow Vodolaz </option>
                      <option>Moscow Watchdog</option>
                      <option>Mountain Cur</option>
                      <option>Mountain Feist</option>
                      <option>Mountain View Cur</option>
                      <option>Moyen Poodle</option>
                      <option>Mucuchies</option>
                      <option>Mudi</option>
                      <option>Munsterlander</option>

                      <option>Native American Indian Dog</option>
                      <option>Neapolitan Mastiff</option>
                      <option>Nebolish Mastiff</option>
                      <option>Nenets Herding Laika</option>
                      <option>New Guinea Singing Dog</option>
                      <option>New Zealand Heading Dog</option>
                      <option>New Zealand Huntaway</option>
                      <option>Newfoundland</option>
                      <option>Norrbottenspets</option>
                      <option>Norfolk Terrier</option>
                      <option>North American Miniature Australian Shepherd</option>
                      <option>Northeasterly Hauling Laika </option>
                      <option>Northern Inuit Dog</option>
                      <option>Norwegian Buhund</option>
                      <option>Norwegian Elkhound</option>
                      <option>Norwegian Hound</option>
                      <option>Norwegian Lundehund</option>
                      <option>Norwich Terrier</option>
                      <option>Nova Scotia Duck-Tolling Retriever</option>


                      <option>Ol' Southern Catchdog</option>
                      <option>Old Danish Chicken Dog</option>
                      <option>Old English Mastiff</option>
                      <option>Old English Sheepdog (Bobtail)</option>
                      <option>Old-Time Farm Shepherd</option>
                      <option>Olde Boston Bulldogge</option>
                      <option>Olde English Bulldogge</option>
                      <option>Olde Victorian Bulldogge</option>
                      <option>Original English Bulldogge</option>
                      <option>Original Mountain Cur</option>
                      <option>Otterhound</option>
                      <option>Otto Bulldog</option>
                      <option>Owczarek Podhalanski</option>

                      <option>Pakistani Bull Dog (Gull Dong) </option>
                      <option>Pakistani Bull Terrier (Pakistani Gull Terr)</option>
                      <option>Pakistani Mastiff (Pakisani Bully Kutta)</option>
                      <option>Pakistani Shepherd Dog (Bhagyari Kutta)</option>
                      <option>Pakistani Tazi Hound</option>
                      <option>Pakistani Vikhan Dog</option>
                      <option>Panda Shepherd</option>
                      <option>Papillon</option>
                      <option>Parson Russell Terrier</option>
                      <option>Patterdale Terrier</option>
                      <option>Pekingese</option>
                      <option>Pembroke Welsh Corgi</option>
                      <option>Pencil-tail Feist</option>
                      <option>Perdiguero de Burgos</option>
                      <option>Perdiguero Navarro </option>
                      <option>Perro Cimarron</option>
                      <option>Perro de Pastor Mallorquin </option>
                      <option>Perro de Presa Canario</option>
                      <option>Perro de Presa Mallorquin</option>
                      <option>Perro Dogo Mallorquin</option>
                      <option>Perro Ratonero Andaluz</option>
                      <option>Peruvian Inca Orchid (PIO)</option>
                      <option>Petit Basset Griffon Vendeen</option>
                      <option>Petit Bleu de Gascogne</option>
                      <option>Petit Brabancon</option>
                      <option>Petit Gascon Saintongeois</option>
                      <option>Pharaoh Hound</option>
                      <option>Phu Quoc Ridgeback Dog</option>
                      <option>Picardy Spaniel</option>
                      <option>Pit Bull Terrier</option>
                      <option>Plott Hound</option>
                      <option>Plummer Hound</option>
                      <option>Pocket Beagle</option>
                      <option>Podenco Ibicenco</option>
                      <option>Pointer</option>
                      <option>Poitevin</option>
                      <option>Polish Hound</option>
                      <option>Polish Tatra Sheepdog</option>
                      <option>Polish Lowland Sheepdog</option>
                      <option>Pomeranian</option>
                      <option>Poodle</option>
                      <option>Porcelaine</option>
                      <option>Portuguese Hound</option>
                      <option>Portuguese Pointer</option>
                      <option>Portuguese Water Dog</option>
                      <option>Posavac Hound</option>
                      <option>Potsdam Greyhound </option>
                      <option>Prazsky Krysavik</option>
                      <option>Presa Canario</option>
                      <option>Price Boar Beisser</option>
                      <option>Pudelpointer</option>
                      <option>Pug</option>
                      <option>Puli (Pulik)</option>
                      <option>Pumi</option>
                      <option>Pyrenean Mastiff</option>
                      <option>Pyrenean Mountain Dog</option>
                      <option>Pyrenean Shepherd</option>
                      <option>Queensland Heeler</option>
                      <option>Queen Elizabeth Pocket Beagle</option>

                      <option>Rafeiro do Alentejo</option>
                      <option>Rajapalayam</option>
                      <option>Rampur Greyhound</option>
                      <option>Rastreador Brasileiro</option>
                      <option>Rat Terrier</option>
                      <option>Redbone Coonhound</option>
                      <option>Red-Tiger Bulldog</option>
                      <option>Rhodesian Ridgeback</option>
                      <option>Roman Rottweiler</option>
                      <option>Rottweiler</option>
                      <option>Rough Collie</option>
                      <option>Rumanian Sheepdog</option>
                      <option>Russian Bear Schnauzer</option>
                      <option>Russian Harlequin Hound </option>
                      <option>Russian Hound</option>
                      <option>Russian Spaniel</option>
                      <option>Russian Toy</option>
                      <option>Russian Tsvetnaya Bolonka</option>
                      <option>Russian Wolfhound</option>
                      <option>Russo-European Laika</option>


                      <option>Saarlooswolfhond</option>
                      <option>Sabueso Español</option>
                      <option>Sage Ashayeri</option>
                      <option>Sage Koochee</option>
                      <option>Sage Mazandarani</option>
                      <option>Saint Bernard</option>
                      <option>Saluki</option>
                      <option>Samoyed</option>
                      <option>Sanshu Dog</option>
                      <option>Sapsari</option>
                      <option>Sarplaninac</option>
                      <option>Schapendoes</option>
                      <option>Schiller Hound</option>
                      <option>Schipperke</option>
                      <option>Schnauzer</option>
                      <option>Scotch Collie</option>
                      <option>Scottish Deerhound</option>
                      <option>Scottish Terrier (Scottie)</option>
                      <option>Sealydale Terrier </option>
                      <option>Sealyham Terrier</option>
                      <option>Segugio Italiano</option>
                      <option>Serbian Hound </option>
                      <option>Shar-Pei</option>
                      <option>Shetland Sheepdog (Sheltie)</option>
                      <option>Shiba Inu</option>
                      <option>Shih Tzu</option>
                      <option>Shika Inu</option>
                      <option>Shikoku</option>
                      <option>Shiloh Shepherd</option>
                      <option>Siberian Husky</option>
                      <option>Siberian Laika</option>
                      <option>Silken Windhound</option>
                      <option>Silky Terrier</option>
                      <option>Simaku </option>
                      <option>Skye Terrier</option>
                      <option>Sloughi</option>
                      <option>Slovakian Hound</option>
                      <option>Slovakian Rough Haired Pointer</option>
                      <option>Slovensky Cuvac</option>
                      <option>Slovensky Hrubosrsty Stavac</option>
                      <option>Smalandsstovare</option>
                      <option>Small Bernese Hound</option>
                      <option>Small Greek Domestic Dog</option>
                      <option>Small Jura Hound</option>
                      <option>Small Lucerne Hound</option>
                      <option>Small Munsterlander</option>
                      <option>Small Schwyz Hound</option>
                      <option>Small Swiss Hound</option>
                      <option>Smooth Collie</option>
                      <option>Smooth Fox Terrier</option>
                      <option>Soft Coated Wheaten Terrier</option>
                      <option>South Russian Ovtcharka</option>
                      <option>Spaniel de Pont-Audemer </option>
                      <option>Spanish Bulldog</option>
                      <option>Spanish Hound</option>
                      <option>Spanish Mastiff</option>
                      <option>Spanish Water Dog</option>
                      <option>Spinone Italiano</option>
                      <option>Springer Spaniel</option>
                      <option>Srpski Gonic</option>
                      <option>Srpski Trobojni Gonic</option>
                      <option>Srpski Planinski Gonic</option>
                      <option>St. Germain Pointing Dog</option>
                      <option>Stabyhoun</option>
                      <option>Staffordshire Bull Terrier</option>
                      <option>Standard American Eskimo</option>
                      <option>Standard Poodle</option>
                      <option>Standard Schnauzer</option>
                      <option>Stephens' Stock Mountain Cur</option>
                      <option>Stichelhaar</option>
                      <option>Strellufstover</option>
                      <option>Styrian Roughhaired Mountain Hound</option>
                      <option>Sussex Spaniel</option>
                      <option>Swedish Elkhound</option>
                      <option>Swedish Lapphund </option>
                      <option>swedishvallhund.htm">Swedish Vallhund</option>
                      <option>Swiss Hound</option>
                      <option>Swiss Laufhund</option>
                      <option>Swiss Shorthaired Pinscher</option>

                      <option>Tahltan Bear Dog </option>
                      <option>Taigan</option>
                      <option>Tamaskan Dog</option>
                      <option>Tasy</option>
                      <option>Teacup Poodle</option>
                      <option>Teddy Roosevelt Terrier</option>
                      <option>Telomian</option>
                      <option>Tenterfield Terrier</option>
                      <option>Tepeizeuintli</option>
                      <option>Thai Bangkaew Dog</option>
                      <option>Thai Ridgeback</option>
                      <option>The Carolina Dog</option>
                      <option>Tibetan KyiApso  </option>
                      <option>Tibetan Mastiff</option>
                      <option>Tibetan Spaniel</option>
                      <option>Tibetan Terrier</option>
                      <option>Titan Bull-Dogge</option>
                      <option>Titan Terrier</option>
                      <option>Tornjak</option>
                      <option>Tosa Inu</option>
                      <option>Toy American Eskimo</option>
                      <option>Toy Fox Terrier</option>
                      <option>Toy German Spitz  </option>
                      <option>Toy Manchester Terrier</option>
                      <option>Toy Poodle</option>
                      <option>Transylvanian Hound</option>
                      <option>Treeing Tennessee Brindle</option>
                      <option>Treeing Walker Coonhound</option>
                      <option>Tuareg Sloughi</option>
                      <option>Tyroler Bracke </option>

                      <option>Utonagan</option>
                      <option>Victorian Bulldog</option>
                      <option>Villano de Las Encartaciones</option>
                      <option>Vizsla</option>
                      <option>Volpino Italiano </option>
                      <option>Vucciriscu</option>

                      <option>Weimaraner</option>
                      <option>Welsh Corgi</option>
                      <option>Welsh Sheepdog</option>
                      <option>Welsh Springer Spaniel</option>
                      <option>Welsh Terrier</option>
                      <option>West Highland White Terrier (Westie)</option>
                      <option>West Russian Coursing Hound </option>
                      <option>West Siberian Laika</option>
                      <option>Westphalian Dachsbracke </option>
                      <option>Wetterhoun</option>
                      <option>Wheaten Terrier</option>
                      <option>Whippet</option>
                      <option>White English Bulldog</option>
                      <option>White German Shepherd</option>
                      <option>Wire Fox Terrier</option>
                      <option>Wirehaired Pointing Griffon</option>
                      <option>Wirehaired Vizsla</option>

                      <option>Xoloitzcuintle</option>
                      <option>Yorkshire Terrier</option>
                      <option>Yugoslavian Hound  </option>
                    </optgroup>
                  </select>
                  <label >Colour:</label>
                  <input id='addColor' placeholder="e.g. black, white, grey, brown" maxlength="20"/>
                  <label >Current Age:</label>
                  <select id='addAge'>
                      <option value='0'>Less then 1 Month</option>
                      <option value="<?php echo 1 / 12 ?>">1 Month</option>
                      <option value="<?php echo 2 / 12 ?>">2 Months</option>
                      <option value="<?php echo 3 / 12 ?>">3 Months</option>
                      <option value="<?php echo 4 / 12 ?>">4 Months</option>
                      <option value="<?php echo 5 / 12 ?>">5 Months</option>
                      <option value="<?php echo 6 / 12 ?>">6 Months</option>
                      <option value="<?php echo 7 / 12 ?>">7 Months</option>
                      <option value="<?php echo 8 / 12 ?>">8 Months</option>
                      <option value="<?php echo 9 / 12 ?>">9 Months</option>
                      <option value="<?php echo 10 / 12 ?>">10 Months</option>
                      <option value="<?php echo 11 / 12 ?>">11 Months</option>
                      <option value='1'>1 Year</option>
                      <option value='2'>2 Years</option>
                      <option value='3'>3 Years</option>
                      <option value='4'>4 Years</option>
                      <option value='5'>5 Years</option>
                      <option value='6'>6 Years</option>
                      <option value='7'>7 Years</option>
                      <option value='8'>8 Years</option>
                      <option value='9'>9 Years</option>
                      <option value='10'>10 Years</option>
                      <option value='11'>11 Years</option>
                      <option value='12'>12 Years</option>
                      <option value='13'>13 Years</option>
                      <option value='14'>14 Years</option>
                      <option value='15'>15 Years</option>
                      <option value='16'>16 Years</option>
                      <option value='17'>17 Years</option>
                      <option value='18'>18 Years</option>
                      <option value='19'>19 Years</option>
                      <option value='20'>20 Years</option>
                      <option value='21'>More Then 20 Years</option>
                  </select>
                  <label>Gender:</label>
                  <select id='addGender'>
                      <option value="Male">Male</option>
                      <option value="Female">Female</option>
                  </select><br/>
                  <label >Spayed/neutered?:</label>
                  <select id='addFixed'><br/>
                      <option value="Yes">Yes</option>
                      <option value="No">No</option>
                  </select><br/>
                  <label>Current Weight:</label>
                  <select id='addWeight'><br/>
                      <option>More Then 35LBS (16KG)</option>
                      <option>Less Then 35LBS (16KG)</option>
                  </select>
                  <label >Vaccination Expiry Date:</label>
                  <input id='addVdate' placeholder='yyyy-mm-dd'  type="date" value="2016-09-01"/>
                  <label >Vet's Phone Number:</label>
                  <input id='addVphone' type="text" placeholder="e.g. 17058887777"/><br/>
                  <label>Vet's Name:</label>
                  <input id='addVname' type="text" placeholder="Vet Name"/>
                  <label >Brand of Food:</label>
                  <input id='addBrand' type="text" placeholder="Brand"/>
                  <label >Feeding Frequency:</label>
                  <input id='addOften' type="text" placeholder="How Often?"/><br/>
                  <label class="grey">Amount of Food:</label>
                  <input id='addAmount' type="text" placeholder="Amount"/>
                  <label>Special Instructions:</label>
                  <textarea id='addMessage' placeholder="Type any extra information you would like to leave with us here...."></textarea>
                  <p id='addDogMessage' class="redText"></p>
                  <input id='userId' type="hidden" value=''/>
                  <button class="floatLeft addDogButtons">Cancel</button>
                  <button id='saveDogButton' class='profileButton3 floatRight'>Save Dog</button>
                  <div class="clear"></div>
              </form>
              <hr>
              <button class='addDogButtons floatLeft'><i class="fa fa-plus" aria-hidden="true"></i> Add Another Pet</button>
              <button onclick='bookingDogNext();' id='dogNext' class="floatRight nextButton">Next</button>
              <div class="clear"></div>
            </div>
        </div>
        <?php
            if (!empty($_GET['bookingid'])) {
        ?>
      </div>
      <?php }?>
        <div id='bookingCost' class="bookingContainer">
            <h3 class="blueText floatLeft"><i class="fa fa-credit-card" aria-hidden="true"></i> Payment</h3>
            <button id='bookingCostAccordianButton' onclick="switchAccordian($(this).attr('id'));" class="floatRight accordianArrow"><i class="fa fa-angle-down fa-2x" aria-hidden="true"></i></button>
            <div class="clear"></div>
            <div id="bookingCostPanel" class="panel">
              <hr>
              <form>
                <button id="addCharge"><i class="fa fa-plus" aria-hidden="true"></i> Add Charge/Discount</button>
                <div id="addChargeBox">
                  <input id="otherChargeCount" type="hidden" value="0"/>
                </div>
                <div class="clear"></div>
                <br/>
                <label>Deposit</label><br/>
                <input id="deposit" onchange="changeDeposit($(this).val());" type="number" value="0" min="0"/>
                <label>Payment Type</label><br/>
                <select id="paymentType">
                  <option>Cash</option>
                  <option>Cheque</option>
                  <option>e-transfer</option>
                  <option>Credit Card</option>
                  <option>Debit Card</option>
                </select>
                <label>Notes</label><br/>
                <input type="text" id="paymentNote" maxlength="100"></input>
              </form>
              <hr>
              <?php if (!empty($_GET['bookingid'])) { ?>
                <button onclick='markPaid();' id='markAsPaid' class="floatLeft profileButton3">Mark As Paid</button>
                <button onclick='updateBooking($(this).attr("id"));' id='updateBooking1' class="floatRight">Update Booking</button>
              <?php }else{?>
                <button onclick='saveBooking($(this).attr("id"));' id='saveBooking1' class="floatRight">Save Booking</button>
              <?php }?>
              <div class="clear"></div>
            </div>
        </div>
        <?php if (!empty($_GET['bookingid'])) { ?>
          <button id="deleteEntireBooking" class="deleteRed bookingContainer">Delete Booking</button>
        <?php }?>
    </div>
    <div class="mobileSummary">
      <header></header>
      <div id='bookingColumnRight' class='floatLeft'>
          <div id="bookingSummary" class="bookingContainer">
              <h3 class="blueText"><i class="fa fa-shopping-cart" aria-hidden="true"></i> Summary</h3>
              <hr>
              <span id="bookingDateSummary"><button id="bookingDateSummaryButton" class="selected"><i class="fa fa-calendar" aria-hidden="true"></i> Dates</button></span><br/>
              <span id="bookingKennelSummaryButton"><button onclick="location.href='#bookingCustomer';"><i class="fa fa-square" aria-hidden="true"></i> Kennels</button><br/></span>
              <button id="bookingCustomerSummaryButton"><i class="fa fa-users" aria-hidden="true"></i> Customer</button><br/>
              <button id="bookingPetSummaryButton"><i class="fa fa-paw" aria-hidden="true"></i> Dogs</button><br/>
              <button id="bookingCostSummaryButton"><i class="fa fa-credit-card" aria-hidden="true"></i> Payment</button><br/>
              <div id="invoiceList"></div>
              <div id="extraChargesList"></div>
              <div id="subTotal">
                <div class="floatLeft">Subtotal:</div>
                <div id="insertSubTotal" class="floatRight"></div>
                <div class="clear"></div>
              </div>
              <div>
                <div class="floatLeft">Tax:</div>
                <div id="insertTax" class="floatRight"></div>
                <div class="clear"></div>
              </div>
              <div class="clear"></div>
              <hr>
              <div>
                <div id="insertDeposit"></div>
                <div class='clear'></div>
                <div class="floatLeft">Total:</div>
                <div id="insertTotal" class="floatRight"></div>
                <div class="clear"></div>
              </div>
              <?php if (!empty($_GET['bookingid'])) { ?>
                <button onclick='updateBooking($(this).attr("id"));' id="updateBooking2" class="button2 savebooking">Update Booking</button>
              <?php }else{?>
                <button onclick='saveBooking($(this).attr("id"));' id="saveBooking2" class="button2 savebooking">Save Booking</button>
              <?php }?>
          </div>
      </div>
  </div>
    <div class="clear"></div>
    <div class="overlay overlayHide">
      <div id="splitBookingCalendar">
        <div id='calendarContainer'>
            <h3 class="blueText floatLeft"><i class="fa fa-calendar" aria-hidden="true"></i> Calendar</h3>
                <div class="clear"></div>
                <hr>
                <button id='splitBookingSave' onclick="saveSplitDates();"><i class="fa fa-check" aria-hidden="true"></i> Save</button><button onclick="splitRePosition();" id='splitBookingClose'><i class="fa fa-times" aria-hidden="true"></i> Cancel</button>
                <input type='hidden' id='currentMonth' value='<?php echo date("m"); ?>' />
                <input type='hidden' id='currentYear' value='<?php echo date("Y"); ?>' />
                <div class="clear"></div>
                <div id='calendarKennels'>
                    <?php
                        loadKennels($numberOfKennels, $kennelName, $size);
                    ?>
                </div>
                <div id='calendarSplitDates'>
                    <?php
                            loadSplitCalendar($numberOfKennels);
                    ?>
                    <div id='bookingBlocks'></div>
                    <div id='bookDragHighLight'></div>
                    <script>
                        var nextMonth = $('#currentMonth').val();
                        var nextYear = $('#currentYear').val();
                        var lastMonth = $('#currentMonth').val();
                        var lastYear = $('#currentYear').val();
                    </script>
                </div>
                <div class="clear"></div>
        </div>
      </div>
      <div class="loadingContainer"><i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i><span class="sr-only">Loading...</span></div>
    </div>
</div>
<script>loadCostList();</script>
<script src="js/chosen.jquery.min.js" type="text/javascript"></script>
<?php
//run editing a booking function when booking id is set in url//
if (!empty($_GET['bookingid'])) {
?>
<script type="text/javascript" src="js/editbooking.min.js"></script>
  <script>
    $( document ).ready(function() {
      loadAllBookingData();
    });
  </script>
<?php }else if(!empty($_GET['clientid'])){
  ?>
  <script>
    $( document ).ready(function() {
      liveSearchLoadClient($('#clientIdLoad').val());
      liveSearchLoadDogs($('#clientIdLoad').val());
    });
  </script>
<?php }
include('footer.php');
?>
