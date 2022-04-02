<?php
include "../config.php";

$connect = mysqli_connect($dbServer, $dbUsername , $dbPassword, $dbDatabase);
$output = '';
$uname = $_POST["username"];

if(!empty($_POST["username"])) {
$result = mysqli_query($connect, "SELECT count(*) FROM membership_users WHERE memberID='" . $_POST["username"] . "'");
$row = mysqli_fetch_row($result);
$user_count = $row[0];
if($user_count>0){
    echo '<label class="col-md-3 control-label" for="username">Username</label>  
    <div class="col-md-9">
    <input id="username" name="username" type="text" placeholder="" class="form-control input-md" required="" onBlur="checkAvailability()">
    <span class="status-not-available"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span> Username "'.$uname.'" Not Available. Please make another try.</span>
    </div>';
} 


else {    
    echo '<label class="col-md-3 control-label" for="username">Username</label>  
    <div class="col-md-9">
    <input id="username" value="'.$uname.'" name="username" type="text" placeholder="" class="form-control input-md" required="" onBlur="checkAvailability()">
    <span class="status-not-available"><span class="glyphicon glyphicon-ok" aria-hidden="true"></span> Username Available.</span>
    </div>';
}
}
?>