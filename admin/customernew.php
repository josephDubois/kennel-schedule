<?php
include('header.php');
/*Display Today's Date*/
?>
<div id='indexToday'>
    <?php
    echo date("l").", ".date("d M Y");
    ?>
</div>
<div id="homeContainer">
    <div class="custContainer floatLeft custProfileLeft">
        <h3>New Customer</h3>
    </div>
    <div class="custContainer floatLeft custProfileRight">
        <h3 class='floatLeft'>Profile</h3>
        <h4 class='profileMenu floatRight'>Personal Info</h4>
        <div class="clear"></div>
        <hr>
        <form>
            <label>First Name:</label><br/>
            <input type='text'/>
            <label>Last Name:</label><br/>
            <input type='text'/>
            <label>Email Address:</label><br/>
            <input type='text'/>
            <label>Phone Number:</label><br/>
            <input type='text'/>
            <label>Mobile Number:</label><br/>
            <input type='text'/>
            <label>Work Number:</label><br/>
            <input type='text'/>
            <label>Address Line 1:</label><br/>
            <input type='text'/>
            <label>Address Line 2:</label><br/>
            <input type='text'/>
            <label>City:</label><br/>
            <input type='text'/>
            <label>Province:</label><br/>
            <input type='text'/>
            <label>Postal Code:</label><br/>
            <input type='text'/>
            <label>Emergency Name:</label><br/>
            <input type='text'/>
            <label>Emergency Phone Number:</label><br/>
            <input type='text'/>
            <label>Notes:</label><br/>
            <input type='text'/>
        </form>
    </div>
    <div class="clear"></div>
</div>
<?php
include('footer.php');
?>