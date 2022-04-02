<?php
include "coordinator_action.php";

?>
<?php
	$currDir = dirname(__FILE__);
        $currDir = substr($currDir, 0, -5);
//        echo $currDir;
	include("$currDir/defaultLang.php");
	include("$currDir/language.php");
	include("$currDir/lib.php");
	
	/* grant access to the groups 'Admins' and 'Data entry' */
	$mi = getMemberInfo();
	if(!in_array($mi['group'], array('Admins', 'Coordinator'))){
            header("location: ../index.php?signIn=1");
//		exit;
	}

       
include_once("$currDir/header.php");
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Dashboard</title>

    <!-- Bootstrap -->
    <link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Libre+Franklin" rel="stylesheet">
    <link rel="stylesheet" href="../resources/select2/select2.css">

    <style>
      body {
        font-family: 'Libre Franklin', sans-serif;
      }.tr_link{cursor:pointer}</style>
  </head>
  <body>
        <div class="container-fluid">
            <?php $id = (int) $_GET['id']; ?>
            <?php
            $where = array("username" => $mi['username']);
            $getUserID = $obj->getUser("phrepuser", $where);
            if ($getUserID) {
                foreach ($getUserID as $user) {
                    echo '<input type="hidden" class="form-control" value="' . $user[id] . '" name="userid">';
                    $userid = $user[id];
                }
            }
            ?>
            <div class="row">
                <div class="col-lg-2"></div>
                <div class="col-lg-8">
                    <form class="form-horizontal" role="form" action="coordinator_action.php" method="POST">  
                        <fieldset>
                        <?php
                            if (isset($_GET["update"])) {
                                if (isset($_GET["id"])) {
                                    $id = $_GET["id"];

                                    $row = $obj->selectSecretary($id);
                                    foreach($row as $r){
                                        
                        ?>

                        <!-- Form Name -->
                        <legend>Update Secretary</legend>
                        
                        <input id="id" name="id" type="hidden" value="<?php echo $id; ?>">
                        <input id="usernameid" name="usernameid" type="hidden" value="<?php echo $r[username]; ?>">
                        
                        <!-- Text input-->
                        <div class="form-group" id="usernametextbox">
                          <label class="col-md-3 control-label" for="username">Username</label>  
                          <div class="col-md-8">
                              <input id="username" value="<?php echo $r["memberID"];?>" name="username" type="text" placeholder="" class="form-control input-md" required="" disabled="" onBlur="checkAvailability()">
                              <span id="user-availability-status"><small class="text-danger">Please contact PHREP Coordinator to request username change.</small></span>
                          </div>
                        </div>
                        
                        <!-- Text input-->
                        <div class="form-group">
                          <label class="col-md-3 control-label" for="email">Email</label>  
                          <div class="col-md-8">
                          <input id="email" value="<?php echo $r["email"];?>" name="email" type="text" placeholder="" class="form-control input-md" required="">

                          </div>
                        </div>
                        
                        <!-- Select Basic -->
                        <div class="form-group">
                          <label class="col-md-3 control-label" for="title">Title</label>
                          <div class="col-md-8">
                            <select id="title" name="title" class="form-control">
                              <option selected value="<?php echo $r["title"];?>"><?php echo $r["title"];?></option>
                              <option value="Mr.">Mr.</option>
                              <option value="Ms.">Mrs.</option>
                              <option value="Dr.">Dr.</option>
                              <option value="Atty.">Atty.</option>
                              <option value="Rev.">Rev.</option>
                              <option value="Hon.">Hon.</option>
                              <option value="Hon.">Sec.</option>
                            </select>
                          </div>
                        </div>

                        <!-- Text input-->
                        <div class="form-group">
                          <label class="col-md-3 control-label" for="fname">First Name</label>  
                          <div class="col-md-8">
                          <input id="fname" value="<?php echo $r["fname"];?>" name="fname" type="text" placeholder="" class="form-control input-md" required="">

                          </div>
                        </div>

                        <!-- Text input-->
                        <div class="form-group">
                          <label class="col-md-3 control-label" for="mname">Middle Name</label>  
                          <div class="col-md-8">
                          <input id="mname" value="<?php echo $r["mname"];?>" name="mname" type="text" placeholder="" class="form-control input-md">

                          </div>
                        </div>

                        <!-- Text input-->
                        <div class="form-group">
                          <label class="col-md-3 control-label" for="lname">Last Name</label>  
                          <div class="col-md-8">
                          <input id="lname" value="<?php echo $r["lname"];?>" name="lname" type="text" placeholder="" class="form-control input-md" required="">

                          </div>
                        </div>

                        <!-- Text input-->
                        <div class="form-group">
                          <label class="col-md-3 control-label" for="pnum">Contact Number</label>  
                          <div class="col-md-8">
                          <input id="pnum" value="<?php echo $r["pnum"];?>" name="pnum" type="text" placeholder="" class="form-control input-md" required="">

                          </div>
                        </div>

                        <!-- Text input-->
                        <div class="form-group">
                          <label class="col-md-3 control-label" for="institution">Institution</label>  
                          <div class="col-md-8">
                          <input id="institution" value="<?php echo $r["institution"];?>" name="institution" type="text" placeholder="" class="form-control input-md" required="">

                          </div>
                        </div>
                        
                        
                        <!-- Button (Double) -->
                        <div class="form-group">
                          <label class="col-md-3 control-label" for="savesec"></label>
                          <div class="col-md-8">
                            <button id="savesec" name="updatesec" class="btn btn-primary">Update</button>
                            <button id="cancel" name="cancel" class="btn btn-danger">Cancel</button>
                          </div>
                        </div>
                        
                        
                        <?php
                                }
                        } 
                            } else{
                        ?>
                        <!-- Form Name -->
                        <legend>Register Secretary</legend>
                        
                        <!-- Text input-->
                        <div class="form-group" id="usernametextbox">
                          <label class="col-md-3 control-label" for="username">Username</label>  
                          <div class="col-md-8">
                              <input id="username" name="username" type="text" placeholder="" class="form-control input-md" required="" onBlur="checkAvailability()">
                              
                          </div>
                        </div>
                        

                        <!-- Text input-->
                        <div class="form-group">
                          <label class="col-md-3 control-label" for="email">Email</label>  
                          <div class="col-md-8">
                          <input id="email" name="email" type="text" placeholder="" class="form-control input-md" required="">

                          </div>
                        </div>
                        
                        <!-- Select Basic -->
                        <div class="form-group">
                          <label class="col-md-3 control-label" for="title">Title</label>
                          <div class="col-md-8">
                            <select id="title" name="title" class="form-control">
                                <option disabled selected>(Select title)</option>
                              <option value="Mr.">Mr.</option>
                              <option value="Ms.">Mrs.</option>
                              <option value="Dr.">Dr.</option>
                              <option value="Atty.">Atty.</option>
                              <option value="Rev.">Rev.</option>
                              <option value="Hon.">Hon.</option>
                              <option value="Hon.">Sec.</option>
                            </select>
                          </div>
                        </div>

                        <!-- Text input-->
                        <div class="form-group">
                          <label class="col-md-3 control-label" for="fname">First Name</label>  
                          <div class="col-md-8">
                          <input id="fname" name="fname" type="text" placeholder="" class="form-control input-md" required="">

                          </div>
                        </div>

                        <!-- Text input-->
                        <div class="form-group">
                          <label class="col-md-3 control-label" for="mname">Middle Name</label>  
                          <div class="col-md-8">
                          <input id="mname" name="mname" type="text" placeholder="" class="form-control input-md">

                          </div>
                        </div>

                        <!-- Text input-->
                        <div class="form-group">
                          <label class="col-md-3 control-label" for="lname">Last Name</label>  
                          <div class="col-md-8">
                          <input id="lname" name="lname" type="text" placeholder="" class="form-control input-md" required="">

                          </div>
                        </div>

                        <!-- Text input-->
                        <div class="form-group">
                          <label class="col-md-3 control-label" for="pnum">Contact Number</label>  
                          <div class="col-md-8">
                          <input id="pnum" name="pnum" type="text" placeholder="" class="form-control input-md" required="">

                          </div>
                        </div>

                        <!-- Text input-->
                        <div class="form-group">
                          <label class="col-md-3 control-label" for="institution">Institution</label>  
                          <div class="col-md-8">
                          <input id="institution" name="institution" type="text" placeholder="" class="form-control input-md" required="">

                          </div>
                        </div>
                        
                        <input id="password" name="password" type="hidden" value="<?php $get = $obj->generateRandomString(); echo MD5($get); ?>">
                        <input id="subject" name="subject" type="hidden" value="Thank you for signing up!">
                        <!-- Button (Double) -->
                        <div class="form-group">
                          <label class="col-md-3 control-label" for="savesec"></label>
                          <div class="col-md-8">
                            <button id="savesec" name="savesec" class="btn btn-primary">Save</button>
                            <button id="cancel" name="cancel" class="btn btn-danger">Cancel</button>
                          </div>
                        </div>
                        
                        <?php                                
                        }
                        ?>
                        
                        </fieldset>
                        </form>

                    
                    
                </div>
                <div class="col-lg-2"></div>
            </div>
            <hr>
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">

                        <table class="table table-condensed table-bordered">
                            <tr>
                                <th>#</th>
                                <th>Full Name</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Contact</th>
                                <th>Institution</th>
                                <th><center>Action</center></th>
                            </tr>
                            <?php 
                            $getsec = $obj->getSecretary();
                            foreach($getsec as $sec){
                                echo '<tr>';
                                echo '<td>'.$sec[id].'</td>';
                                echo '<td>'.$sec[lname].', '.$sec[fname].' '.$sec[mname].'</td>';
                                echo '<td>'.$sec[memberID].'</td>';
                                echo '<td>'.$sec[email].'</td>';
                                echo '<td>'.$sec[pnum].'</td>';
                                echo '<td>'.$sec[institution].'</td>';
                                echo '<td><center><a class="btn btn-default" href="co_regsecretary.php?update=1&id='.$sec[id].'" role="button">EDIT</a>';
                                echo ' | <a class="btn btn-danger" href="coordinator_action.php?deletesec=1&id='.$sec[id].'&u='.$sec[memberID].'" role="button">DELETE</a>';
                                echo'</center></td>';
                                echo '</tr>';
                            }
                            
                            ?>
                        </table>
                      </div>
                </div>
            </div>
            
            
        </div>    

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
     <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="../bootstrap/js/bootstrap.min.js"></script>
     
  </body>
  
</html>
<?php include_once("$currDir/footer.php"); ?>
<script>
jQuery(document).ready(function($) {
    $(".clickable-row").click(function() {
        window.location = $(this).data("href");
    });
});


$('tr[data-href]').on("click", function() {
    document.location = $(this).data('href');
});
</script>

<script>
$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip();   
});
</script>

<script>
function checkAvailability() {
$("#loaderIcon").show();
jQuery.ajax({
url: "check_username.php",
data:'username='+$("#username").val(),
type: "POST",
success:function(data){
$("#usernametextbox").html(data);
},
error:function (){}
});
}
</script>