<?php
session_start();
include("databaseKeys/".$_SESSION['dataBaseKey'].".php");
//determine if the user is legally logged in//
if (empty($_SESSION['user'])) {
    $_SESSION['error'] = 'Please Login.';
    header("Location: login.php");
    exit;
}
//Customer Search bar Code//
if (isset($_GET['id'])){
    //check to see if a customers id was loaded//
    $id = $_GET['id'];
}
include('header.php');
/*Display Today's Date*/
?>
<div id='indexToday'>
    <?php
    echo date("l").", ".date("d M Y");
    ?>
</div>
<div id="homeContainer">
    <?php
            $dogid = $_GET['dogid'];
            $query = "SELECT * FROM dogs WHERE dogid='$dogid'";
            $result=mysqli_query($link, $query);
            if (mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_array($result, MYSQL_ASSOC);
                $id = $row['id'];
                $name = $row['name'];
                $breed = $row['breed'];
                $color = $row['color'];
                $age = $row['age'];
                $age = (int)$age;
                $ageDateStamp = $row['ageDateStamp'];
                $currentAge;
                if (substr($ageDateStamp,0, 4) === date("Y")) {
                    $newMonth = date("m") - substr($ageDateStamp, 5, 2);
                    if($newMonth != 0) {
                        $currentAge = $newMonth / 12 + $age;
                        $rounded = round($currentAge);
                        if ($rounded == 1) {
                            $currentAge = round($currentAge)." Year";
                        }else if($rounded > 1) {
                            $currentAge = round($currentAge)." Years";
                        }else{
                            $currentAge = ($currentAge * 12)." Months";
                        }
                    }else{
                        if ($age == 1) {
                            $currentAge = $age." Year";
                        }else if($age > 1) {
                            $currentAge = $age." Years";
                        }else{
                            $currentAge = ($age * 12)." Months";
                        }
                    }
                } else {
                   $newYear = date("Y") - substr($ageDateStamp,0, 4);
                   /*Check if current month is sooner or later then past month*/
                   if (substr($ageDateStamp, 5, 2) === date("m")) {
                        $currentAge = round($newYear + $age)." Years";
                   }else if (substr($ageDateStamp, 5, 2) > date("m")) {
                        $newMonth = substr($ageDateStamp, 5, 2) - date("m");
                        $currentAge = round($newYear - ($newMonth / 12) + $age)." Years";
                   }else {
                        $newMonth = date("m") - substr($ageDateStamp, 5, 2);
                        $currentAge = round($newMonth / 12 + $newYear + $age)." Years";
                   }
                }
                $gender = $row['gender'];
                if ($gender === 'Male') {
                    $icon = 'mars';
                }else{
                    $icon = 'venus';
                }
                $fixed = $row['fixed'];
                $brand = $row['brand'];
                $amount = $row['amount'];
                $often = $row['often'];
                $vname = $row['vet'];
                $vdate = $row['vdate'];
                $vphone = $row['vphone'];
                $message = $row['special'];
                $query2 = "SELECT * FROM users WHERE id='$id'";
                $result2=mysqli_query($link, $query2);
                if (mysqli_num_rows($result2) > 0) {
                    $row2 = mysqli_fetch_array($result2, MYSQL_ASSOC);
                    $first = $row2['first'];
                    $last = $row2['last'];
                    $phone = $row2['phone'];
                    $mobile = $row2['mobile'];
                    $work = $row2['work'];
                    $email = $row2['email'];
                    $ephone = $row2['ephone'];
                    $ename = $row2['ename'];
                }else{


                }
            }else{

            }
    ?>
    <div class="custContainer floatLeft custProfileLeft">
        <h3><?php echo $name;  ?></h3>
        <h4 class="brownText">OWNED BY <?php echo strtoupper ($first)." ".strtoupper ($last); ?></h4>
        <button>Create a Booking</button><button onclick="window.location.href='mailto:<?php echo $email;?>';">Email</button>
        <br/><br/>
        <button class='profileButton2'><i class="fa fa-calendar" aria-hidden="true"></i> <?php echo $currentAge;  ?></button>
        <button class='profileButton2'><i class="fa fa-<?php echo $icon; ?>" aria-hidden="true"></i> <?php echo $gender; ?></button>
        <button class='profileButton2'><i class="fa fa-dot-circle-o" aria-hidden="true"></i> <?php echo $breed; ?></button>
    </div>
    <div class="custContainer floatLeft custProfileRight">
        <h3 class='floatLeft'>Kennel Sheet For <?php echo $name; ?></h3>
        <h4 onclick="window.print();" id='profilePrint' class='profileMenu floatRight'><i class="fa fa-print" aria-hidden="true"></i> Print</h4>
        <h4 onclick="window.location.href='customer.php?id=<?php echo $id; ?>&dogid=<?php echo $dogid; ?>'" id='profileEdit' class='profileMenu floatRight'><i class="fa fa-pencil" aria-hidden="true"></i> Edit</h4>
        <div class="clear"></div>
        <hr>
        <div class='kennelSheetColumn floatLeft'>
            <div id="kennelSheetContainerRed" class="kennelSheetContainer">
                <div id='kennelSheetHeaderRed' class="kennelSheetHeader">Medical Info</div>
                <div class='kennelSheetContents'>Vet Name: <?php echo $vname; ?><br/><br/>
                Vet Phone Number: <?php echo $vphone; ?><br/><br/>
                Vaccination Expiry: <?php echo $vdate; ?><br/>
                </div>
            </div>
            <div id="kennelSheetContainerBlue" class="kennelSheetContainer">
                <div id="kennelSheetHeaderBlue" class="kennelSheetHeader">Feeding Info</div>
                <div class='kennelSheetContents'>
                    Brand: <?php echo $brand; ?><br/><br/>
                    Amount: <?php echo $amount; ?><br/><br/>
                    Frequency: <?php echo $often; ?><br/>
                </div>
            </div>
            <div class="kennelSheetContainer">
                <div class="kennelSheetHeader">Owner</div>
                <div class='kennelSheetContents'><?php echo $first." ".$last;?></div>
            </div>
            <div class="kennelSheetContainer">
                <div class="kennelSheetHeader">Owner Contact</div>
                <div class='kennelSheetContents'>
                    Phone: <?php echo $phone; ?><br/><br/>
                    Mobile: <?php echo $mobile; ?><br/><br/>
                    Work: <?php echo $work; ?><br/><br/>
                    Email: <?php echo $email; ?>
                </div>
            </div>
        </div>
        <div class='kennelSheetColumn floatLeft'>
            <div class="kennelSheetContainer">
                <div class="kennelSheetHeader">Emergency Name</div>
                <div class='kennelSheetContents'><?php echo $ename;?></div>
            </div>
            <div class="kennelSheetContainer">
                <div class="kennelSheetHeader">Emergency Number</div>
                <div class='kennelSheetContents'><?php echo $ephone;?></div>
            </div>
            <div class="kennelSheetContainer">
                <div class="kennelSheetHeader">Notes</div>
                <div class='kennelSheetContents'><?php echo $message;?></div>
            </div>
        </div>
    </div>
    <div class="clear"></div>
</div>
<div class="kennelSheetCardPrint">
     <h1><?php echo $name; ?></h1>
     <div class='kennelCardColumn floatLeft'>
        <div class="kennelCardBoxes"><span class='kennelCardHeader'>Gender: </span><?php echo $gender; ?></div>
        <div class="kennelCardBoxes"><span class='kennelCardHeader'>Age: </span><?php echo $currentAge; ?></div>
        <div class="kennelCardBoxes"><span class='kennelCardHeader'>Medical Info: </span><br/><br/>
            Vet Name:<?php echo $vname; ?><br/><br/>
            Vet Phone:<?php echo $vphone; ?><br/><br/>
            Vac. Expiry:<?php echo $vdate; ?><br/><br/>
        </div>
        <div class="kennelCardBoxes"><span class='kennelCardHeader'>Feeding Info: </span><br/><br/>
            Brand:<?php echo $brand; ?><br/><br/>
            Amount:<?php echo $amount; ?><br/><br/>
            Frequency:<?php echo $often; ?><br/><br/>
        </div>
     </div>
     <div class='kennelCardColumn floatLeft'>
        <div class="kennelCardBoxes"><span class='kennelCardHeader'>Breed: </span><?php echo $breed; ?></div>
        <div class="kennelCardBoxes"><span class='kennelCardHeader'>Owner: </span><?php echo $first." ".$last; ?></div>
        <div class="kennelCardBoxes"><span class='kennelCardHeader'>Owner Contact: </span><br/><br/>
            Phone:<?php echo $phone; ?><br/><br/>
            Mobile:<?php echo $mobile; ?><br/><br/>
            Work:<?php echo $work; ?><br/><br/>
            Email:<?php echo $email; ?><br/><br/>
        </div>
        <div class="kennelCardBoxes"><span class='kennelCardHeader'>Emergency Name: </span><?php echo $ename; ?></div>
        <div class="kennelCardBoxes"><span class='kennelCardHeader'>Emergency Phone: </span><?php echo $ephone; ?></div>
        <div class="kennelCardBoxes"><span class='kennelCardHeader'>Notes: </span><?php echo $message; ?></div>
     </div>
     <div class="clear"></div>
</div>
<?php
    include('footer.php');
?>
