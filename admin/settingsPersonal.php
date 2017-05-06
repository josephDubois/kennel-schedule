<?php
session_start();
include("databaseKeys/".$_SESSION['dataBaseKey'].".php");
//determine if the user is legally logged in//
if (empty($_SESSION['user'])) {
    $_SESSION['error'] = 'Please Login.';
    header("Location: login.php");
    exit;
}
$error = $_SESSION['error'];
unset($_SESSION['error']);
include('header.php');
/*Display Today's Date*/
?>
<div id='indexToday'>
    <?php
    echo date("l").", ".date("d M Y");
    ?>
</div>
<div id="settingsPersonalContainer">
  <div class="settingsPersonalColumnLeft">
    <div class='settingsPersonalBox'>
        <h3 class="blueText"><i class="fa fa-cogs" aria-hidden="true"></i> Personal Info</h3>
        <hr>
        <button class="selected">Kennels</button><br/>
        <hr>
        <button onclick="location.href='settings.php';"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back To Settings</button>
    </div>
  </div>
  <div class="settingsPersonalColumnRight">
    <div class='settingsPersonalBox'>
        <h3 class="blueText"><i class="fa fa-cogs" aria-hidden="true"></i> Kennels</h3>
        <hr>
        <?php

            $query = "SELECT * FROM kennels";
            $result=mysqli_query($link, $query);
            $kennels = mysqli_num_rows($result);

        ?>
        <form id='kennelSettings' action="saveKennels.php" method="post">
            <label>How many kennels do you have?</label>
            <input id='kennelNumber' name="kennelNumber" type='number' max='100' value='<?php echo $kennels; ?>'/>
            <button onclick="editKennels(<?php echo $kennels; ?>);" id='kennelsEdit' class="greyButton"><i class="fa fa-plus" aria-hidden="true"></i> Edit Kennel Names & Types</button>
            <div id='kennelEditBox'>
               <?php
                    while($row = mysqli_fetch_array($result, MYSQL_ASSOC)) {
                        $id = $row['id'];
                        $name = $row['name'];
                        $type = $row['size'];
                        echo "<label for='".$id."name'>Kennel ".$id." Name:</label><input id='".$id."name' name='".$id."name' type='text' value='".$name."'>";
                        echo "<label for='".$id."type'>Kennel ".$id." Type:</label><select id='".$id."type' name='".$id."type'>".
                            "<option"; if($type === "small") { echo " selected=selected";} echo " value='small'>Small</option>".
                            "<option"; if($type === "large") { echo " selected=selected";} echo " value='large'>Large</option>".
                        "</select>";
                    }
               ?>
            </div>
        </form>
        <hr>
        <p id='editKennelMessage' class="redText"><?php if(isset($error)) echo $error; ?></p>
        <button id='saveKennels' class="blueButton floatRight">Save</button>
        <div class="clear"></div>
    </div>
  </div>
  <div class="clear"></div>
</div>
<?php
include('footer.php');
?>
