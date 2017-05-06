<?php
session_start();
include("databaseKeys/".$_SESSION['dataBaseKey'].".php");
//determine if the user is legally logged in//
if (empty($_SESSION['user'])) {
    $_SESSION['error'] = 'Please Login.';
    header("Location: login.php");
    exit;
}
$no = "no";
/*Pagination*/
if (!isset($_GET['sort'])) {
    $rec_limit = 5;
}else{
    $rec_limit = $_GET['sort'];
}
if( isset($_GET['page'] ) ) {
    $page = $_GET['page'] + 1;
    $offset = $rec_limit * $page ;
}else {
    $page = 0;
    $offset = 0;
}
//Customer Search bar Code//
if(isset($_GET['customerSearch']) && $_GET['customerSearch'] != ""){
    $search= mysqli_real_escape_string($link,$_GET['customerSearch']);
    $query = "SELECT * FROM users WHERE first LIKE '%$search%' AND deleted='$no' OR last LIKE '%$search%' AND deleted='$no' OR phone LIKE '%$search%' AND deleted='$no' OR email LIKE '%$search%' AND deleted='$no'";
    $result=mysqli_query($link, $query);
    $rec_count = mysqli_num_rows($result);
}else{
    $query = "SELECT * FROM users WHERE deleted='no'";
    $result=mysqli_query($link, $query);
    $rec_count = mysqli_num_rows($result);
    $left_rec = $rec_count - ($page * $rec_limit);
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
    <div class="customerListContainer">
        <h3 class="floatLeft">CUSTOMERS</h3>
        <!--<button class="floatRight"><i class="fa fa-wrench" aria-hidden="true"></i> Tools</button>-->
        <button onclick="location.href='customer.php';" class="floatRight"><i class="fa fa-plus" aria-hidden="true"></i> New Customer</button>
        <div class="clear"></div>
        <hr>
        <form id="custSortForm" class="floatLeft" method="get" action="customers.php">
        <label class="floatLeft" for='sort'>Records</label>
        <select id="custSort" class='floatLeft' name='sort' onchange="this.form.submit();">
            <option value="5" <?php if(!isset($_GET['sort']) OR $_GET['sort'] == 5) echo "selected='selected'"; ?>>5</option>
            <option value="10" <?php if($_GET['sort'] == 10) echo "selected='selected'"; ?>>10</option>
            <option value="20" <?php if($_GET['sort'] == 20) echo "selected='selected'"; ?>>20</option>
        </select>
        <input type="hidden" id='customerSearch2' name='customerSearch' value="<?php echo $search;?>">
        </form>
        <form id='customerSearchForm' class="floatLeft" method='get' action="customers.php">
            <label class='floatLeft' for="customerSearch">Search: </label>
            <input id='customerSearch' class='floatLeft' name='customerSearch' placeholder='Search' value="<?php echo $search;?>">
            <input id='customerSearchButton' class='noShow' type="submit"/>
        </form>
        <div class="clear"></div>
        <table id="customerDataTable" class="dataTable" cellpadding='0' cellspacing='0'>
          <thead>
            <tr id='customerHeader' class="dataTableHeader">
                <td>Customer</td>
                <td>Email Address</td>
                <td>Phone Number</td>
                <td>Dogs</td>
                <td>Last Booking</td>
            </tr>
          </thead>
            <?php
                if (empty($search)) {
                    $query = "SELECT * FROM users WHERE deleted = 'no' LIMIT $offset, $rec_limit";
                    $result=mysqli_query($link, $query);
                    $lastPageRows=mysqli_num_rows($result);
                    if (mysqli_num_rows($result) > 0) {
                        while($row = mysqli_fetch_array($result, MYSQL_ASSOC)) {
                           $id = $row['id'];
                           $first = $row['first'];
                           $last = $row['last'];
                           $email = $row['email'];
                           $phone = $row['phone'];
                            echo "<tr><td data-href='customer.php?id=".$id."'>".$first." ".$last."</td><td data-href='customer.php?id=".$id."'>".$email."</td><td data-href='customer.php?id=".$id."'>".$phone."</td><td class='dogs'>";
                            $query2 = "SELECT * FROM dogs WHERE deleted = 'no' AND id = '".$id."'";
                            $result2=mysqli_query($link, $query2);
                            if (mysqli_num_rows($result2) > 0) {
                                while($row2 = mysqli_fetch_array($result2, MYSQL_ASSOC)) {
                                    $dogid = $row2['dogid'];
                                    $name = $row2['name'];
                                    ?>
                                    <button onclick="location.href='kennelsheet.php?dogid=<?php echo $dogid ?>';" class='profileButton3'><?php echo $name ?></button>
                               <?php }
                            }
                            echo "</td><td>";
                            $query2 = "SELECT * FROM costs WHERE clientid = '$id' ORDER BY bookdate DESC LIMIT 1";
                            $result2=mysqli_query($link, $query2);
                            if (mysqli_num_rows($result2) > 0) {
                              $row2 = mysqli_fetch_array($result2, MYSQL_ASSOC);
                              $bookdate = $row2['bookdate'];
                              echo $bookdate."</td></tr>";
                            }else{
                              echo "No Bookings.</td></tr>";
                            }

                        }
                    }else{
                        echo "<tr><td class='sorry' colspan='5'>Sorry you have not added any clients yet.</td></tr>";
                    }
                }else{
                    $query = "SELECT * FROM users WHERE MATCH(first, last, phone, email, deleted) AGAINST('$search' IN BOOLEAN MODE) AND deleted = 'no' LIMIT $offset, $rec_limit";
                    $result=mysqli_query($link, $query);
                    $lastPageRows=mysqli_num_rows($result);
                    $left_rec = $rec_count - ($page * $rec_limit);
                    if (mysqli_num_rows($result) > 0) {
                        while($row = mysqli_fetch_array($result, MYSQL_ASSOC)) {
                           $id = $row['id'];
                           $first = $row['first'];
                           $last = $row['last'];
                           $email = $row['email'];
                           $phone = $row['phone'];
                           echo "<tr><td data-href='customer.php?id=".$id."'>".$first." ".$last."</td><td data-href='customer.php?id=".$id."'>".$email."</td><td data-href='customer.php?id=".$id."'>".$phone."</td><td class='dogs'>";
                           $query1 = "SELECT * FROM dogs WHERE deleted = 'no' AND id = '".$id."'";
                           $result1=mysqli_query($link, $query1);
                           if (mysqli_num_rows($result1) > 0) {
                               while($row1 = mysqli_fetch_array($result1, MYSQL_ASSOC)) {
                                   $dogid = $row1['dogid'];
                                   $name = $row1['name'];
                                   ?>
                                   <button onclick="location.href='kennelsheet.php?dogid=<?php echo $dogid ?>';" class='profileButton3'><?php echo $name ?></button>
                              <?php }
                           }
                           echo "</td><td>";
                           $query1 = "SELECT * FROM costs WHERE clientid = '$id' ORDER BY bookdate DESC LIMIT 1";
                           $result1=mysqli_query($link, $query1);
                           if (mysqli_num_rows($result1) > 0) {
                             $row1 = mysqli_fetch_array($result1, MYSQL_ASSOC);
                             $bookdate = $row1['bookdate'];
                             echo $bookdate."</td></tr>";
                           }else{
                             echo "No Bookings.</td></tr>";
                           }
                        }
                    }
                    $query2 = "SELECT * FROM dogs WHERE MATCH(name, breed) AGAINST('$search' IN BOOLEAN MODE) AND deleted = 'no' LIMIT $offset, $rec_limit";
                    $result2=mysqli_query($link, $query2);
                    if (mysqli_num_rows($result2) > 0) {
                      while($row2 = mysqli_fetch_array($result2, MYSQL_ASSOC)) {
                        $id = $row2['id'];
                        $name = $row2['name'];

                        $query3 = "SELECT * FROM users WHERE id = $id";
                        $result3=mysqli_query($link, $query3);
                        $row3 = mysqli_fetch_array($result3, MYSQL_ASSOC);
                        $first = $row3['first'];
                        $last = $row3['last'];
                        $email = $row3['email'];
                        $phone = $row3['phone'];
                        echo "<tr><td data-href='customer.php?id=".$id."'>".$first." ".$last."</td><td data-href='customer.php?id=".$id."'>".$email."</td><td data-href='customer.php?id=".$id."'>".$phone."</td><td class='dogs'>";

                        $query4 = "SELECT * FROM dogs WHERE deleted = 'no' AND id = '".$id."'";
                        $result4=mysqli_query($link, $query4);
                          while($row4 = mysqli_fetch_array($result4, MYSQL_ASSOC)) {
                            $dogid = $row4['dogid'];
                            $name = $row4['name'];
                            ?>
                            <button onclick="location.href='kennelsheet.php?dogid=<?php echo $dogid ?>';" class='profileButton3'><?php echo $name ?></button>
                       <?php }
                       echo "</td><td>";
                           $query4 = "SELECT * FROM costs WHERE clientid = '$id' ORDER BY bookdate DESC LIMIT 1";
                           $result4=mysqli_query($link, $query4);
                           if (mysqli_num_rows($result4) > 0) {
                             $row4 = mysqli_fetch_array($result4, MYSQL_ASSOC);
                             $bookdate = $row4['bookdate'];
                             echo $bookdate."</td></tr>";
                           }else{
                             echo "No Bookings.</td></tr>";
                           }
                        }
                      }
                    if(mysqli_num_rows($result) <= 0 && mysqli_num_rows($result2) <= 0){
                        echo "<tr><td class='sorry' colspan='5'>Sorry we could not match your search criteria.</td></tr>";
                    }
                    $search='';
                }
            ?>
        </table>
        <?php
        $totalPerPage = $rec_limit * ($page + 1);
            if( $left_rec <= $rec_limit) {
                if($page != 0) {
                    $lastPage = $page - 2;
                    if (isset($_GET['sort']) && isset($_GET['customerSearch'])) {
                      echo "<div class='totalResults'>Showing ".($totalPerPage - ($rec_limit - 1))." to ".($totalPerPage - ($rec_limit - 1) + ($lastPageRows - 1))." of ".$rec_count." entries.</div>";
                      echo "<div class='centerPages'><div class='pageButton floatRight'>".($page + 1)."</div><a href = '$_PHP_SELF?page=$lastPage&sort=".$_GET['sort']."&customerSearch=".$_GET['customerSearch']."' class='floatRight pageButton'><i class='fa fa-chevron-left' aria-hidden='true'></i> Last Page</a></div>";
                    }else if (isset($_GET['sort'])) {
                      echo "<div class='totalResults'>Showing ".($totalPerPage - ($rec_limit - 1))." to ".($totalPerPage - ($rec_limit - 1) + ($lastPageRows - 1))." of ".$rec_count." entries.</div>";
                      echo "<div class='centerPages'><div class='pageButton floatRight'>".($page + 1)."</div><a href = '$_PHP_SELF?page=$lastPage&sort=".$_GET['sort']."' class='floatRight pageButton'><i class='fa fa-chevron-left' aria-hidden='true'></i> Last Page</a></div>";
                    }else if (isset($_GET['customerSearch'])) {
                      echo "<div class='totalResults'>Showing ".($totalPerPage - ($rec_limit - 1))." to ".($totalPerPage - ($rec_limit - 1) + ($lastPageRows - 1))." of ".$rec_count." entries.</div>";
                      echo "<div class='centerPages'><div class='pageButton floatRight'>".($page + 1)."</div><a href = '$_PHP_SELF?page=$lastPage&customerSearch=".$_GET['customerSearch']."' class='floatRight pageButton'><i class='fa fa-chevron-left' aria-hidden='true'></i> Last Page</a></div>";
                    }else{
                        echo "<div class='totalResults'>Showing ".($totalPerPage - ($rec_limit - 1))." to ".($totalPerPage - ($rec_limit - 1) + ($lastPageRows - 1))." of ".$rec_count." entries.</div>";
                        echo "<div class='centerPages'><div class='pageButton floatRight'>".($page + 1)."</div><a href = '$_PHP_SELF?page=$lastPage' class='floatRight pageButton'><i class='fa fa-chevron-left' aria-hidden='true'></i> Last Page</a></div>";
                    }
                }
            }else if( $page == 0 && $left_rec > $rec_limit) {
                if (isset($_GET['sort']) && isset($_GET['customerSearch'])) {
                  echo "<div class='totalResults'>Showing ".($totalPerPage - ($rec_limit - 1))." to ".$totalPerPage." of ".$rec_count." entries.</div>";
                  echo "<div class='centerPages'><a class='floatRight pageButton' href = '$_PHP_SELF?page=$page&sort=".$_GET['sort']."&customerSearch=".$_GET['customerSearch']."'>Next Page <i class='fa fa-chevron-right' aria-hidden='true'></i></a><div class='pageButton floatRight'>".($page + 1)."</div></div>";
                }else if (isset($_GET['sort'])) {
                  echo "<div class='totalResults'>Showing ".($totalPerPage - ($rec_limit - 1))." to ".$totalPerPage." of ".$rec_count." entries.</div>";
                  echo "<div class='centerPages'><a class='floatRight pageButton' href = '$_PHP_SELF?page=$page&sort=".$_GET['sort']."'>Next Page <i class='fa fa-chevron-right' aria-hidden='true'></i></a><div class='pageButton floatRight'>".($page + 1)."</div></div>";
                }else if (isset($_GET['customerSearch'])) {
                  echo "<div class='totalResults'>Showing ".($totalPerPage - ($rec_limit - 1))." to ".$totalPerPage." of ".$rec_count." entries.</div>";
                  echo "<div class='centerPages'><a class='floatRight pageButton' href = '$_PHP_SELF?page=$page&customerSearch=".$_GET['customerSearch']."'>Next Page <i class='fa fa-chevron-right' aria-hidden='true'></i></a><div class='pageButton floatRight'>".($page + 1)."</div></div>";
                }else{
                    echo "<div class='totalResults'>Showing ".($totalPerPage - ($rec_limit - 1))." to ".$totalPerPage." of ".$rec_count." entries.</div>";
                    echo "<div class='centerPages'><a class='floatRight pageButton' href = '$_PHP_SELF?page=$page'>Next Page <i class='fa fa-chevron-right' aria-hidden='true'></i></a><div class='pageButton floatRight'>".($page + 1)."</div></div>";
                }
            }else if( $page > 0) {
                $lastPage = $page - 2;
                if (isset($_GET['sort']) && isset($_GET['customerSearch'])){
                  echo "<div class='totalResults'>Showing ".($totalPerPage - ($rec_limit - 1))." to ".$totalPerPage." of ".$rec_count." entries.</div>";
                  echo "<div class='centerPages'><a href = '$_PHP_SELF?page=$page&sort=".$_GET['sort']."&customerSearch=".$_GET['customerSearch']."' class='pageButton floatRight'>Next Page <i class='fa fa-chevron-right' aria-hidden='true'></i></a>";
                  echo "<div class='pageButton floatRight'>".($page + 1)."</div>";
                  echo "<a href = '$_PHP_SELF?page=$lastPage&sort=".$_GET['sort']."&customerSearch=".$_GET['customerSearch']."' class='pageButton floatRight'><i class='fa fa-chevron-left' aria-hidden='true'></i> Last Page</a></div>";
                }else if (isset($_GET['sort'])) {
                  echo "<div class='totalResults'>Showing ".($totalPerPage - ($rec_limit - 1))." to ".$totalPerPage." of ".$rec_count." entries.</div>";
                  echo "<div class='centerPages'><a href = '$_PHP_SELF?page=$page&sort=".$_GET['sort']."' class='pageButton floatRight'>Next Page <i class='fa fa-chevron-right' aria-hidden='true'></i></a>";
                  echo "<div class='pageButton floatRight'>".($page + 1)."</div>";
                  echo "<a href = '$_PHP_SELF?page=$lastPage&sort=".$_GET['sort']."' class='pageButton floatRight'><i class='fa fa-chevron-left' aria-hidden='true'></i> Last Page</a></div>";
                }else if (isset($_GET['customerSearch'])) {
                  echo "<div class='totalResults'>Showing ".($totalPerPage - ($rec_limit - 1))." to ".$totalPerPage." of ".$rec_count." entries.</div>";
                  echo "<div class='centerPages'><a href = '$_PHP_SELF?page=$page&customerSearch=".$_GET['customerSearch']."' class='pageButton floatRight'>Next Page <i class='fa fa-chevron-right' aria-hidden='true'></i></a>";
                  echo "<div class='pageButton floatRight'>".($page + 1)."</div>";
                  echo "<a href = '$_PHP_SELF?page=$lastPage&customerSearch=".$_GET['customerSearch']."' class='pageButton floatRight'><i class='fa fa-chevron-left' aria-hidden='true'></i> Last Page</a></div>";
                }else{
                    echo "<div class='totalResults'>Showing ".($totalPerPage - ($rec_limit - 1))." to ".$totalPerPage." of ".$rec_count." entries.</div>";
                    echo "<div class='centerPages'><a href = '$_PHP_SELF?page=$page' class='pageButton floatRight'>Next Page <i class='fa fa-chevron-right' aria-hidden='true'></i></a>";
                    echo "<div class='pageButton floatRight'>".($page + 1)."</div>";
                    echo "<a href = '$_PHP_SELF?page=$lastPage' class='pageButton floatRight'><i class='fa fa-chevron-left' aria-hidden='true'></i> Last Page</a></div>";
                }
            }else{
                echo "Showing ".($totalPerPage - ($rec_limit - 1))." to ".$rec_count." of ".$rec_count." entries.";
                echo "<div class='pageButton floatRight'>".($page + 1)."</div>";
            }

        ?>
        <div class="clear"></div>
    </div>
</div>
<?php
include('footer.php');
?>
